<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\PaymentLogs;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Stripe\Checkout\Session;
use Stripe\Customer;

class APIController extends Controller
{

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
            'payment_method' => 'required',
            'items' => 'required|array',
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


    public function createCustomer(Request $request){
        $rules = [
            'fullname' => 'required',
            'phone' => 'required',
            'email' => 'required|email:rfc,dns,strict|unique:users,email',
            'postal' => 'required',
            'address' => 'required',
            'password' => 'required|min:3',
        ];

        $validator = Validator::make($request->all(), $rules, [
            'fullname.required' => 'Fullname is required',
            'phone.required' => 'Phone number is required',
            'email.required' => 'Email is required',
            'email.email' => 'Email must be a valid email address',
            'email.unique' => 'Email must be unique',
            'postal.required' => 'Postal code is required',
            'address.required' => 'Address is required',
            'password.required' => 'Password is required',
            'password.min' => 'Password must have atleast 3 characters'
        ]);



        if ($validator->fails()) {
            return $this->responseJson(422, null, null, $validator->messages()->all());
        }
        $requestData = $request->all();
        $requestData['status'] = 1;
        $requestData['password'] = bcrypt($request->password);

        $user = User::create($requestData);
        return $this->responseJson(200, 'User Created.', $user);
    }
}
