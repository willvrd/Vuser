<?php

namespace Modules\Vuser\Http\Controllers\Api\Auth;

use Modules\Vcore\Http\Controllers\Api\VcoreApiController;

use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

// Requests & Response
use Illuminate\Http\Request;

// Transformers
use Modules\Vuser\Transformers\UserTransformer;

// Request
use Modules\Vuser\Http\Requests\LoginRequest;

class LoginApiController extends VcoreApiController
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
    protected $redirectTo = RouteServiceProvider::HOME;

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
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {

        try {

            //Validate Request
            $this->validateRequestApi(new LoginRequest($request->all()));

            // If the class is using the ThrottlesLogins trait, we can automatically throttle
            // the login attempts for this application. We'll key this by the username and
            // the IP address of the client making these requests into this application.
            if (method_exists($this, 'hasTooManyLoginAttempts') &&
                $this->hasTooManyLoginAttempts($request)) {
                $this->fireLockoutEvent($request);

                return $this->sendLockoutResponse($request);
            }

            if ($this->attemptLogin($request)) {

                $response = $this->sendLoginResponse($request);

            }else{

                // If the login attempt was unsuccessful we will increment the number of attempts
                // to login and redirect the user back to the login form. Of course, when this
                // user surpasses their maximum number of attempts they will get locked out.
                $this->incrementLoginAttempts($request);

                throw new \Exception('User or Password invalid', 401);
            }

        } catch (\Exception $e) {
            \Log::error($e);
            $status = $this->getStatusError($e->getCode());
            $response = ["errors" => $e->getMessage()];
        }

         return response()->json($response, $status ?? 200);
    }

     /**
     * Send the response after the user was authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    protected function sendLoginResponse(Request $request)
    {

        $this->clearLoginAttempts($request);

        if ($response = $this->authenticated($request, $this->guard()->user())) {
            return $response;
        }

        $tokenName = $request->input('device_name')."-".$request->user()->id;
        $token =  $request->user()->createToken($tokenName)->accessToken;

        //Response
        $response = [
            'data' => [
                'token' => $token,
                'user'  => new UserTransformer($request->user()),
            ]
        ];

        return $response;

    }


    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {

        try {

            $request->user()->token()->revoke();
            $response = [
                'data' => 'Successfully logged out'
            ];

        } catch (\Exception $e) {
                \Log::error($e);
                $status = $this->getStatusError($e->getCode());
                $response = ["errors" => $e->getMessage()];
        }
        return response()->json($response, $status ?? 200);

    }




}
