<?php

namespace App\Http\Controllers;

use App\Events\SendOtpCode;
use App\Mail\OTPMail;
use App\Mail\ResetPawword;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller implements HasMiddleware
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */

    // use HasMiddleware auth guard is api to check token
    public static function middleware(): array
    {
        return [
            new Middleware('auth:api', only: ['me', 'refresh', 'logout']),
        ];
    }


    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    /*url : {{url}}/api/auth/login
    Method : POST
    Body : email,password
    Check Email , Password and user has OTP equal null to login and return token
    */
    public function login()
    {

        $credentials = request(['email', 'password']);


        if (!$token = auth('api')->attempt($credentials)) {
            return $this->sendResponse([], "Invalid Credentials");
        }
        if (auth('api')->user()->otp != null) {
            return $this->sendResponse([], "Not Verified. Check Your Email");
        }

        return $this->respondWithToken($token);
    }


    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    /*
     *  url : {{url}}/api/auth/register
     *  Method : POST
     *  Body : name,email,password
     *  Check Email , Password and Send  OTP to Email with event and mail trap
     *  Check Email For OTP
     * return user data and token
     * **/

    public function register(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')),
            'otp' => rand(100000, 999999),
        ]);
        event(new SendOtpCode($user));


//        $token = auth('api')->login($user);

        return $this->sendResponse([], "Check Your Email For OTP Code");
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth('api')->user());

    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */

    /*url : {{url}}/api/auth/logout
    Method : POST
    Body : no body
    return message
    */
    public function logout()
    {
        auth()->logout();

        return $this->sendResponse([], 'Successfully logged out');

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
     * @param string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        $data=[
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ];
        return $this->sendResponse($data, 'Login Successfully');

    }


    /*url : {{url}}/api/auth/verifyOTP
    Method : POST
    Body : otp_code
    Check user has same OTP and otp_code IN table equal null to login and return user data
    */
    public function verifyOTP(Request $request)
    {
        $request->validate([
            'otp_code' => 'required',
        ]);

        $user = User::query()->where('otp', $request->otp_code)->first();

        if ($user) {
            $user->update(['otp' => null]);

            return $this->sendResponse($user, "Register Successfully");

        } else {
            return $this->sendResponse([], "OTP Wrong ");
        }
    }


    /*url : {{url}}/api/auth/forgot
    Method : POST
    Body : email
    Check Email For codepassword and send codepassword to Email to reset password
    */
    public function forgot(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::query()->where('email', $request->email)->first();
        if ($user) {
            $user->codepassword = rand(100000, 999999);
            Mail::to($user->email)->send(new ResetPawword($user->codepassword));
            $user->save();
        }
        return $this->sendResponse([], "Check Your Email");
    }


    /*url : {{url}}/api/auth/reset
    Method : POST
    Body : password, password_confirmation,codepassword,email
    Check Email and  codepassword  craeted to new password and Hashed password
    */
    public function reset(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:6|confirmed',
            'codepassword' => 'required',
            'email' => 'required|email',
        ]);
        $user = User::query()->where('codepassword', $request->codepassword)->where('email', $request->email)->first();
        if ($user) {
            $user->password = Hash::make($request->password);
            $user->codepassword = null;
            $user->save();
            return $this->sendResponse([], "Reset Successfully");
        }
        return $this->sendResponse([], "Code Wrong ");

    }
}
