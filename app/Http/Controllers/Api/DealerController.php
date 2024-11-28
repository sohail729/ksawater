<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use stdClass;

class DealerController extends Controller
{
    public function updateProfile(Request $request)
    {
        $dealer = User::with('detail')->where('id',  $request->dealer_id)->first();

        if (empty($dealer)) {
            return $this->responseJson(404, 'Dealer not found!');
        }

        if (!empty($request->password)) {
            $dealer->password = $request->password;
        }
        if (!empty($request->image)) {
            $dealer->detail->image = $request->image;
        }
        if (!empty($request->phone)) {
            $dealer->detail->phone = $request->phone;
        }
        if (!empty($request->address)) {
            $dealer->detail->address = $request->address;
        }
        if (!empty($request->city)) {
            $dealer->detail->city = $request->city;
        }
        if (!empty($request->postcode)) {
            $dealer->detail->postcode = $request->postcode;
        }
        if (!empty($request->fullname)) {
            $dealer->fullname = $request->fullname;
        }

        $dealer->save();

        $dealerData = new stdClass;
        $dealerData->id = $dealer->id;
        $dealerData->status = $dealer->status;
        $dealerData->image = $dealer->detail->image;
        $dealerData->phone = $dealer->detail->phone;
        $dealerData->email = $dealer->email;
        $dealerData->postcode = $dealer->detail->postcode;
        $dealerData->address = $dealer->detail->address;
        $dealerData->city = $dealer->detail->city;
        $dealerData->username = $dealer->username;
        $dealerData->fullname = $dealer->fullname;
        $dealerData->package = $dealer->package;
        $dealerData->upload_limit = $dealer->upload_limit;
        $dealerData->payment_status = $dealer->payment_status;
        $dealerData->payment_date = $dealer->payment_date;
        $dealerData->subscription_end = $dealer->subscription_end;
        $dealerData->is_verified = $dealer->is_verified;
        $dealerData->verified_on = $dealer->verified_on;
        $dealerData->is_featured = $dealer->is_featured;
        $dealerData->featured_limit = $dealer->featured_limit;
        $dealerData->featured_start = $dealer->featured_start;
        $dealerData->featured_end = $dealer->featured_end;
        $dealerData->registered_on = $dealer->created_at->format('Y-m-d H:i:s');
        return $this->responseJson(200, 'Profile Updated', $dealerData);
    }
}
