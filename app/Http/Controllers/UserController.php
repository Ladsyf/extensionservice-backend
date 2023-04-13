<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function store(Request $request)
    {
        $formFields = $request->validate([
            'firstName' => 'required',
            'lastName' => 'required',
            'email' => 'required | unique:users',
            'phoneNumber' => 'required',
            'password' => 'required | confirmed'
        ]);
        $formFields['password'] = bcrypt($formFields['password']);
        User::create($formFields);
        return response(['status' => 'Success', 'message' => 'Successfully registered!'], 200);
    }
}
