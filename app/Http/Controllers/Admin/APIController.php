<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\NetworkHelper;
use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Category;
use App\Models\DeliveryLogs;
use App\Models\Donation;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\PaymentLogs;
use App\Models\Product;
use App\Models\Team;
use App\Models\User;
use App\Models\UserPhoneOTP;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Stripe\Checkout\Session;

class APIController extends Controller
{

    public function riderLogin(Request $request){
        if(empty($request->phone) || empty($request->password)){
            return $this->responseJson(400, 'Invalid credentials.');
        }

        $rider = Team::where('phone', $request->phone)->where('type', 'rider')->first();
        if(empty($rider)){
            return $this->responseJson(400, 'Rider doesn\'t exists.');
        }

        if($rider->status == 0){
            return $this->responseJson(400, 'Your account has been blocked. Please contact the administrator for further assistance.');
        }

        if (!$token = auth()->guard('rider')->attempt(['phone' => $request->phone, 'password' => $request->password])) {
            return $this->responseJson(400, 'Invalid Credentials.');
        }

        $riderData['id'] = $rider->id;
        $riderData['fullname'] = $rider->fullname;
        $riderData['phone'] = $rider->phone;
        $riderData['email'] = $rider->email;
        $riderData['token'] = [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('rider')->factory()->getTTL() * 60 // TTL in seconds
        ];
        $riderData['created_at'] = $rider->created_at;
        if(auth()->guard('rider')){
            return $this->responseJson(200, 'Logged in successfully', $riderData);
        }
        return $this->responseJson(400, 'Login Failed.');
    }

    public function customerLogin(Request $request){
        if(empty($request->phone) || empty($request->password)){
            return $this->responseJson(400, 'Invalid credentials.');
        }

        $user = User::where('phone', $request->phone)->first();
        if(empty($user)){
            return $this->responseJson(400, 'User doesn\'t exists.');
        }

        if($user->status == 0){
            return $this->responseJson(400, 'Your account has been temporarily blocked. Please reach out to support for more information.');
        }

        $token = auth()->guard('api')->login($user);
        $userData['id'] = $user->id;
        $userData['fullname'] = $user->fullname;
        $userData['phone'] = $user->phone;
        $userData['email'] = $user->email;
        $userData['token'] = [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60 // TTL in seconds
        ];
        $userData['created_at'] = $user->created_at;
        if(auth()->guard('api')){
            return $this->responseJson(200, 'Logged in successfully', $userData);
        }
        return $this->responseJson(400, 'Login Failed.');
    }

    public function getConfig(){
        $categories = Category::all();
        $banners = Banner::all();

        $categories->transform(function ($category) {
            $category->image = url('storage/uploads/categories/' . $category->image);
            return $category;
        });

        $banners->transform(function ($banner) {
            $banner->image = url('storage/uploads/banners/' . $banner->image);
            return $banner;
        });

        $data = [
            'categories' => $categories,
            'banners' => $banners,
        ];

        return $this->responseJson(200, 'Config Data!', $data);
    }

    public function getProductsByCategory(Request $request){
        $category_id = $request->category_id;

        if(empty($category_id)){
            return $this->responseJson(400, 'Category id is required!');
        }

        $products = Product::where('category_id', $category_id)->where('status', 1)->get();
        return $this->responseJson(200, 'Products List By Category.', $products);
    }

    public function getProductById(Request $request){
        $product_id = $request->product_id;

        if(empty($product_id)){
            return $this->responseJson(400, 'Category id is required!');
        }

        $product = Product::where('id', $product_id)->where('status', 1)->first();
        if($product){
            return $this->responseJson(200, 'Product By Id', $product);
        }
        return $this->responseJson(400, 'Product Not Found.');
    }

    public function searchByQuery(Request $request){
        $query = $request->input('query');

        $keywords = explode(' ', $query);
        $products = Product::query()->where('status', 1);

        $products->where(function ($query) use ($keywords) {
            foreach ($keywords as $keyword) {
                $query->orWhere('title', 'LIKE', "%{$keyword}%")
                    ->orWhere('description', 'LIKE', "%{$keyword}%");
            }
        });
        $products = $products->get();
        if($products){
            return $this->responseJson(200, 'Searched Products By Query', $products);
        }
        return $this->responseJson(400, 'Products Not Found.');
    }

    public function updateRiderAction(Request $request){
        Log::debug("updateRiderAction Data: ". json_encode($request->all()));
        $orderID = $request->order_id;
        $riderID = $request->rider_id;
        $status = $request->status;

        $rules = [
            'order_id' => 'required',
            'rider_id' => 'required',
            'status' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules, [
            'order_id.required' => 'Order id is required',
            'rider_id.required' => 'Rider id is required',
            'status.required' => 'Status is required'
        ]);

        if ($validator->fails()) {
            return $this->responseJson(422, null, null, $validator->messages()->all());
        }

        $order = Order::find($orderID);
        if($order){
            $rider = Team::find($riderID);
            $order->update([
                'rider_id' => $rider->id,
                'rider_name' => $rider->fullname,
                'rider_phone' => $rider->phone,
                'status' => $status,
                'reject_reason' => $request->reject_reason ?? ""
            ]);

            $logs = DeliveryLogs::create([
                'order_id' => $order->id,
                'rider_id' => $rider->id,
                'status' => $status,
                'created_at' => now()->format('Y-m-d H:i:s')
            ]);

            if($status == 'rejected'){
                $product_ids = $order->detail ? $order->detail->pluck('product_id') : collect();
                Product::whereIn('id', $product_ids)->increment('stock');
            }

            return $this->responseJson(200, 'Rider Action Updated.');
        }
        return $this->responseJson(400, 'Order not found.');
    }

    public function createOrder(Request $request){
        Log::debug("Order Data: ". json_encode($request->all()));

        $rules = [
            'user_id' => 'required',
            'fullname' => 'required|string',
            'email' => 'required|email:rfc,dns,strict',
            'amount' => 'required',
            'address' => 'required',
            'phone' => 'required',
            'postal' => 'required',
            'is_donation' => 'required',
            'payment_method' => 'required',
            'items' => 'required|array',
            'mosque_name' => 'required_if:is_donation,true',
            'mosque_address' => 'required_if:is_donation,true',
            'mosque_latlng' => 'required_if:is_donation,true',
        ];

        $validator = Validator::make($request->all(), $rules, [
            'user_id.required' => 'User id is required',
            'fullname.required' => 'Fullname is required',
            'email.required' => 'Email is required',
            'email.email' => 'Email must be a valid email address',
            'amount.required' => 'Amount is required',
            'address.required' => 'Address is required',
            'phone.required' => 'Phone is required',
            'postal.required' => 'Postal is required',
            'payment_method.required' => 'Payment method is required',
            'items.required' => 'Items array is required',
            'items.array' => 'Items must be a valid array of objects',
            'is_donation.required' => 'is_donation check is required',
            'mosque_name.required_if' => 'Mosque Name is required',
            'mosque_address.required_if' => 'Mosque Address is required',
            'mosque_latlng.required_if' => 'Mosque LatLng is required',
        ]);

        if ($validator->fails()) {
            return $this->responseJson(422, null, null, $validator->messages()->all());
        }

        $userID = $request->user_id;
        if($request->user_id == 1000 && $request->is_new_user){
            $oldUser = User::where('email', $request->email)->first();
            if($oldUser){
                $userID = $oldUser->id;
            }else{
                $rules = [
                    'email' => 'required|email:rfc,dns,strict|unique:users,email',
                    'password' => 'required|min:3'
                ];

                $validator = Validator::make($request->all(), $rules, [
                    'email.required' => 'Email is required',
                    'email.email' => 'Email must be a valid email address',
                    'email.unique' => 'Email must be unique',
                    'password.required' => 'Password is required',
                    'password.min' => 'Password must have atleast 3 characters'
                ]);

                if ($validator->fails()) {
                    return $this->responseJson(422, null, null, $validator->messages()->all());
                }

                $userData['fullname'] = $request->fullname;
                $userData['phone'] = $request->phone;
                $userData['email'] = $request->email;
                $userData['postal'] = $request->postal;
                $userData['status'] = 1;
                $userData['address'] = $request->address;
                $userData['password'] = bcrypt($request->password);
                $user = User::create($userData);
                $userID = $user->id;
            }
        }

        $outofstock = [];
        if (!empty($request->items)) {
            $productIds = array_map(function ($item) {
                return $item['product_id'];
            }, $request->items);
            $products = Product::whereIn('id', $productIds)->get();
            foreach ($products as $key => $product) {
                $i = 0;
                if($product['stock'] <= 0 || $product['status'] == 0){
                    $outofstock['items'][$i]['id'] = $product['id'];
                    $outofstock['items'][$i]['name'] = $product['title'];
                    $i++;
                }
            }
            if(count($outofstock) > 0 ){
                $outofstock['out_of_stock'] = true;
                return $this->responseJson(200, 'Some items are not available at the moment.', $outofstock);
            }
        }

        $order = new Order();
        $order->user_id = $userID;
        $order->is_donation = $request->is_donation ? 1 : 0;
        $order->order_number = generateUniqueOrderNumber();
        $order->fullname = $request->fullname;
        $order->email = $request->email;
        $order->amount = number_format($request->amount, 2);
        $order->address = $request->address;
        $order->phone = $request->phone;
        $order->postal = $request->postal;
        $order->payment_method = $request->payment_method;
        $order->order_time = now()->format('Y-m-d H:i:s');
        $order->status = 'pending';
        $order->payment_status = 'unpaid';

        if (!empty($request->delivery_instructions)) {
            $order->delivery_instructions = $request->delivery_instructions;
        }
        $order->save();

        if (!empty($request->items)) {
            $productIds = array_map(function ($item) {
                return $item['product_id'];
            }, $request->items);

            $items = $request->items;
            if(count($outofstock) > 0 ){
                $outOfStockIDs = array_column($outofstock, 'id');
                $filteredItems = array_filter($items, function ($item) use ($outOfStockIDs) {
                    return !in_array($item['product_id'], $outOfStockIDs);
                });
                $items = $filteredItems;
            }

            foreach ($items as $key => $item) {
                $detail = new OrderDetail();
                $detail->order_id = $order->id;
                $detail->price = $item['price'];
                $detail->qty = $item['qty'];
                $detail->product_name = $products[$key]->title;
                $detail->product_id = $item['product_id'];
                $detail->total = number_format($item['price'], 2) * $item['qty'];
                $detail->save();
                $products[$key]->decrement('stock');
            }
        }

        Log::debug("Order Created - OrderId: ". $order->id);

        if($request->is_donation){
            Donation::create([
                "user_id" => $order->user_id,
                "order_id" => $order->id,
                "amount" => $order->amount,
                "mosque_name" => $request->mosque_name,
                "mosque_address" => $request->mosque_address,
                "mosque_latlng" => $request->mosque_latlng
            ]);
        }

        if($request->payment_method == 'card'){
            foreach ($order->detail as $detail) {
                $lineItems[] = [
                    'price_data' => [
                        'currency' => 'SAR',
                        'product_data' => [
                            'name' => $detail->product->title,
                        ],
                        'unit_amount' => number_format($detail->price, 2) * 100,
                    ],
                    'quantity' => $detail->qty,
                ];
            }

            $metadata = [
                'guest_user' => $order->user_id == 1000 ? true : false,
                'order_id' => $order->id,
                'customer_email' => $order->email,
                'customer_phone' => $order->phone,
            ];

            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => $lineItems,
                'mode' => 'payment',
                'phone_number_collection' => ['enabled' => true],
                'payment_intent_data' => ['metadata' => $metadata],
                'success_url' => route('payment.callback', ['order_id' => $order->id]),
                'cancel_url' => route('payment.callback', ['order_id' => $order->id]),
            ]);


            $order->payment_ref_id = $session->id;
            $order->save();
        }

        $data = $this->getOrderData($order);
        $logData['user_id'] = $order->user_id;
        $logData['order_id'] = $order->id;
        $logData['payment_status'] = 'unpaid';
        $logData['payment_method'] = 'card';
        $logData['created_at'] = now()->format('Y-m-d H:i:s');

        if($request->payment_method == 'card'){
            $data['payment_ref_id'] = $session->id;
            $data['payment_url'] = $session->url;
            $logData['payment_ref_id'] = $session->id;
        }

        PaymentLogs::create($logData);

        return $this->responseJson(200, 'Order Created.', $data);
    }

    public function paymentCallback(Request $request){
        $order = Order::find($request->order_id);
        $session = Session::retrieve($order->payment_ref_id);
        if($session->payment_status == 'paid'){
            PaymentLogs::create([
                "user_id" => $order->user_id,
                "order_id" => $order->id,
                "payment_status" => 'paid',
                "payment_ref_id" => $session->payment_intent,
                "payment_method" => 'card',
                "created_at" => now()->format('Y-m-d H:i:s'),
            ]);
            $order->update(['payment_status'=> 'paid', 'payment_ref_id' => $session->payment_intent]);
            $data = $this->getOrderData($order);
            return $this->responseJson(200, 'Payment Successful.', $data);
        }
        return $this->responseJson(500, 'Something went wrong.');
    }

    private function getOrderData($order){
        $data['order_id'] = $order->id;
        $data['user_id'] = $order->user_id;
        $data['is_guest'] = false;
        if($order->user_id == 1000){
            $data['is_guest'] = true;
        }
        $data['fullname'] = $order->fullname;
        $data['email'] = $order->email;
        $data['amount'] = $order->amount;
        $data['address'] = $order->address;
        $data['phone'] = $order->phone;
        $data['postal'] = $order->postal;
        $data['payment_method'] = $order->payment_method;
        $data['order_time'] = $order->order_time;
        $data['status'] = $order->status;
        $data['payment_status'] = $order->payment_status;

        if(!empty($order->detail)){
            foreach ($order->detail as $key => $detail) {
                $data['items'][$key]['id'] = $detail->product_id;
                $data['items'][$key]['name'] = $detail->product_name;
                $data['items'][$key]['price'] = $detail->price;
                $data['items'][$key]['qty'] = $detail->qty;
                $data['items'][$key]['total'] = $detail->total;
            }
        }
        return $data;
    }

    public function sendOTP(Request $request)
    {
        $phone = $request->phone;
        if(empty($phone)){
            return $this->responseJson(400, 'Please enter phone number.');
        }
        $user = User::where('phone', $phone)->first();
        if (!empty($user)){
            return $this->responseJson(400, 'A user with this phone number already exists, please use another phone number and try again.');
        }

        $phoneWithCC = '966' . $phone;
        $code = rand(100000, 999999);

        $smsData['number'] = $phone;
        $smsData['senderName'] = "Mobile.SA";
        $smsData['sendAtOption'] = "Now";
        $smsData['messageBody'] = $code . ' is your verification code.';
        $smsData['allow_duplicate'] = true;

        $header['Authorization'] = 'Bearer '. env('MADAR_SMS_KEY');

        Log::debug("Madar SMS Request: ". $phone);
        $response = NetworkHelper::makeRequest('post', env('MADAR_SMS_URL'), 'send', $header, $smsData);
        Log::debug("Madar SMS Response: ". $phone);
        $responseArray = json_decode($response['data']);
        if ($response['status'] == '200') {
            UserPhoneOTP::updateOrCreate(
                [
                    'phone' => $phoneWithCC
                ],
                [
                    'otp' => $code,
                    'status' => 1,
                    'ref_id' => $responseArray->data->message->id
                ]);
            return $this->responseJson(200, 'OTP Sent.');
        }
        return $this->responseJson(400, 'Something went wrong.');
    }

    public function createCustomer(Request $request){
        $rules = [
            'fullname' => 'required',
            'phone' => 'required',
            'otp' => 'required',
            // 'email' => 'required|email:rfc,dns,strict|unique:users,email',
            // 'postal' => 'required',
            // 'address' => 'required',
            'password' => 'required|min:3',
        ];

        $validator = Validator::make($request->all(), $rules, [
            'fullname.required' => 'Fullname is required',
            'phone.required' => 'Phone number is required',
            'otp.required' => 'OTP is required',
            // 'email.required' => 'Email is required',
            // 'email.email' => 'Email must be a valid email address',
            // 'email.unique' => 'Email must be unique',
            // 'postal.required' => 'Postal code is required',
            // 'address.required' => 'Address is required',
            'password.required' => 'Password is required',
            'password.min' => 'Password must have atleast 3 characters'
        ]);



        if ($validator->fails()) {
            return $this->responseJson(422, null, null, $validator->messages()->all());
        }

        $otp = $request->otp;
        $fullname = $request->fullname;
        $phone = $request->phone;
        $phoneWithCC = '966' . $phone;
        $userOTP = UserPhoneOTP::where('phone', $phoneWithCC)->where('otp', $otp)->where('status', 1)->first();
        if(empty($userOTP)){
            return $this->responseJson(400, 'The OTP you entered is incorrect. Please try again.');
        }
        $userOTP->update(['status' => 0]);
        $requestData['status'] = 1;
        $requestData['fullname'] = $fullname;
        $requestData['phone'] =  $phone;
        $requestData['password'] = bcrypt($request->password);

        $user = User::create($requestData);
        $token = auth()->guard('api')->login($user);

        $userData['id'] = $user->id;
        $userData['fullname'] = $user->fullname;
        $userData['phone'] = $user->phone;
        $userData['token'] = [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60 // TTL in seconds
        ];
        $userData['created_at'] = $user->created_at;

        if(auth()->guard('api')){
            return $this->responseJson(200, 'Logged in successfully', $userData);
        }

        return $this->responseJson(400, 'Login Failed.');
    }


    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth('api')->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }
}
