<?php

namespace Modules\Vuser\Http\Controllers\Api;

// Requests & Response
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Vuser\Http\Requests\RegisterRequest;
use Modules\Vuser\Http\Requests\UpdateUserRequest;

// Base
use Modules\Vcore\Http\Controllers\Api\VcoreApiController;

// Entities
use App\User;

// Repositories
use Modules\Vuser\Repositories\UserRepository;

// Transformers
use Modules\Vuser\Transformers\UserTransformer;

class UserApiController extends VcoreApiController
{

    private $user;

    public function __construct(
        UserRepository $user
    ){
        $this->user = $user;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {

        try {

            //Request to Repository
            $users = $this->user->getItemsBy($this->getParamsRequest($request));

            //Response
            $response = ['data' => UserTransformer::collection($users)];

            //If request pagination add meta-page
            $request->page ? $response['meta'] = ['page' => $this->pageTransformer($users)] : false;


        } catch (\Exception $e) {
            \Log::error($e);
            $status = $this->getStatusError($e->getCode());
            $response = ["errors" => $e->getMessage()];
        }
        return response()->json($response, $status ?? 200);

    }

     /** SHOW
   * @param Request $request
   *  URL GET:
   *  &fields = type string
   *  &include = type string
   */
    public function show($criteria, Request $request)
    {
        try {
            //Request to Repository
            $user = $this->user->getItem($criteria,$this->getParamsRequest($request));

            //Break if no found item
            if (!$user) throw new \Exception('Item not found', 204);

            $response = [
                'data' => $user ? new UserTransformer($user) : '',
            ];

        } catch (\Exception $e) {
            \Log::error($e);
            $status = $this->getStatusError($e->getCode());
            $response = ["errors" => $e->getMessage()];
        }
        return response()->json($response, $status ?? 200);
    }

    /**
   * Create.
   * @param  Request $request
   * @return Response
   */
    public function create(Request $request)
    {

        \DB::beginTransaction();

        try{

            $data = $request['attributes'] ?? [];

            $this->validateRequestApi(new RegisterRequest($data));

            $user = $this->user->create($data);

            $response = ["data" => new UserTransformer($user)];

            \DB::commit();

        } catch (\Exception $e) {
            \Log::error($e);
            \DB::rollback();
            $status = $this->getStatusError($e->getCode());
            $response = ["errors" => $e->getMessage()];
        }

        return response()->json($response, $status ?? 200);

    }

    /**
   * Update the specified resource in storage.
   * @param  Request $request
   * @return Response
   */
    public function update($criteria, Request $request)
    {
        \DB::beginTransaction();

        try {

            $data = $request['attributes'] ?? [];

            $this->validateRequestApi(new UpdateUserRequest($data));

            $params = $this->getParamsRequest($request);

            // Search entity
            $entity = $this->user->getItem($criteria,$params);

            //Break if no found item
            if (!$entity) throw new \Exception('Item not found', 204);

            $user = $this->user->update($entity,$data);

            $response = ['data' => new UserTransformer($user)];

            \DB::commit();

        } catch (\Exception $e) {
            \Log::error($e);
            \DB::rollback();
            $status = $this->getStatusError($e->getCode());
            $response = ["errors" => $e->getMessage()];
        }

        return response()->json($response, $status ?? 200);

    }

    /**
   * Remove the specified resource from storage.
   * @return Response
   */
    public function delete($criteria, Request $request)
    {
        try {

            $params = $this->getParamsRequest($request);

            // Search entity
            $entity = $this->user->getItem($criteria,$params);

            //Break if no found item
            if (!$entity) throw new \Exception('Item not found', 204);

            $this->user->destroy($entity);

            $response = ['data' => 'Item deleted'];

        } catch (\Exception $e) {
            \Log::Error($e);
            \DB::rollback();//Rollback to Data Base
            $status = $this->getStatusError($e->getCode());
            $response = ["errors" => $e->getMessage()];
        }

        return response()->json($response, $status ?? 200);

    }

}
