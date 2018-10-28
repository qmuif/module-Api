<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Авторизация по логину
     */
    public function username()
    {
        return 'login';
    }

    /**
     * Api авторизация
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        //если авторизаци я прошла успешно
        $this->validateLogin($request);
        if($this->attemptLogin($request)) {
            $user = $this->guard()->user();
            //создаем токен
            $user->generateToken();
            return response()->json([
                'status' => true,
                'token' => $user->api_token
            ])->setStatusCode(200,'Successful authorization');
        }
        //иначе возвращаем ошибку
        return $this->sendFailedLoginResponse($request);
    }

    /**
     * Возврат ошибки
     * @return \Illuminate\Http\JsonResponse
     */
    protected function sendFailedLoginResponse()
    {
        return response()->json([
            'status'=>false,
            'message'=>'Invalid authorization data'
        ],401)->setStatusCode(401,'Invalid authorization data');
    }
}

