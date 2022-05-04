<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Handle an authentication attempt.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function authenticate(Request $request)
    {
         
    if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
        return response()->json([
            'message' => 'Ugurla girish etdiniz'
        ]);
    }
    else{
        return response()->json([
            'message' => 'login ugursuz'
        ]);
    }
}
    
}
