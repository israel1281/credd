<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function updatePhone(Request $request) {
        $request->validate([
            'phone' => [
                'required', 'string', 'size:11', 'regex:/0[7-9][01]\d{8}$/i',
                Rule::unique(User::class, 'phone')->ignore(auth()->user()->id)
            ],
        ],
        [
            'phone.unique' => 'This phone number is already registered',
            'phone.regex' => 'Please input a valid phone number'
        ]);

        $phoneNumber = formatPhoneNumber($request->phone);
        if (auth()->user()->phone != $phoneNumber) {
            $oldPhoneNumber = auth()->user()->phone;
            auth()->user()->phone_verified_at = null;
            auth()->user()->phone = $phoneNumber;
            auth()->user()->save();
            notifyAdmins('User phone number updated!',
                auth()->user()->name." changed phone number from ".$oldPhoneNumber." to ".$phoneNumber.".");
            return apiSuccess(null, 'Phone number updated successfully!');
        } else {
            return apiSuccess(null, 'No changes made to phone number!');
        }
    }
}
