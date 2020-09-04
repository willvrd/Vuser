<?php

namespace Modules\Vuser\Http\Controllers\Api\Auth;

use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\RegistersUsers;

// Base
use Modules\Vcore\Http\Controllers\Api\VcoreApiController;

// Requests & Response
use Illuminate\Http\Request;

// Events
use Illuminate\Auth\Events\Registered;

// Request
use Modules\Vuser\Http\Requests\RegisterRequest;

// Repositories
use Modules\Vuser\Repositories\UserRepository;


class RegisterApiController extends VcoreApiController
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    private $user;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserRepository $user)
    {
        $this->middleware('guest');
        $this->user = $user;
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {

        try {

            //Validate Request
            $this->validateRequestApi(new RegisterRequest($request->all()));

            event(new Registered($user = $this->user->create($request->all())));

            $response = [
                'data' => 'User Created'
            ];

        } catch (\Exception $e) {
            \Log::error($e);
            $status = $this->getStatusError($e->getCode());
            $response = ["errors" => $e->getMessage()];
        }

        return response()->json($response, $status ?? 200);

    }


}
