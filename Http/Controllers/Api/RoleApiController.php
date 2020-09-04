<?php

namespace Modules\Vuser\Http\Controllers\Api;

// Requests & Response
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Modules\Vuser\Http\Requests\CreateRoleRequest;
use Modules\Vuser\Http\Requests\AssignRoleRequest;
use Modules\Vuser\Http\Requests\UnAssignRoleRequest;

// Base
use Modules\Vcore\Http\Controllers\Api\VcoreApiController;

// Repositories
use Modules\Vuser\Repositories\RoleRepository;

// Transformers
use Modules\Vuser\Transformers\RoleTransformer;

class RoleApiController extends VcoreApiController
{

    private $role;

    public function __construct(
        RoleRepository $role
    ){
        $this->role = $role;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {

        try {

            //Request to Repository
            $roles = $this->role->getItemsBy($this->getParamsRequest($request));

            //Response
            $response = ['data' => RoleTransformer::collection($roles)];

            //If request pagination add meta-page
            $request->page ? $response['meta'] = ['page' => $this->pageTransformer($roles)] : false;


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
            $role = $this->role->getItem($criteria,$this->getParamsRequest($request));

            //Break if no found item
            if (!$role) throw new \Exception('Item not found', 204);

            $response = [
                'data' => $role ? new RoleTransformer($role) : '',
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

            $this->validateRequestApi(new CreateRoleRequest($data));

            $role = $this->role->create($data);

            $response = ["data" => new RoleTransformer($role)];

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

            $this->validateRequestApi(new CreateRoleRequest($data));

            $params = $this->getParamsRequest($request);

            // Search entity
            $entity = $this->role->getItem($criteria,$params);

            //Break if no found item
            if (!$entity) throw new \Exception('Item not found', 204);

            $role = $this->role->update($entity,$data);

            $response = ['data' => new RoleTransformer($role)];

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
            $entity = $this->role->getItem($criteria,$params);

            //Break if no found item
            if (!$entity) throw new \Exception('Item not found', 204);

            $this->role->destroy($entity);

            $response = ['data' => 'Item deleted'];

        } catch (\Exception $e) {
            \Log::Error($e);
            \DB::rollback();//Rollback to Data Base
            $status = $this->getStatusError($e->getCode());
            $response = ["errors" => $e->getMessage()];
        }

        return response()->json($response, $status ?? 200);

    }

    /** Assign Role User
     * @param Request $request
     */
    public function assign(Request $request){

        \DB::beginTransaction();

        try {

            $data = $request['attributes'] ?? [];

            $this->validateRequestApi(new AssignRoleRequest($data));

            $this->role->assign($data);

            $response = ['data' => 'Role(s) assigned to User'];

            \DB::commit();

        } catch (\Exception $e) {
            \Log::error($e);
            \DB::rollback();
            $status = $this->getStatusError($e->getCode());
            $response = ["errors" => $e->getMessage()];
        }
        return response()->json($response, $status ?? 200);
    }

    /** Revoke Role User
     * @param Request $request
     */
    public function unassign(Request $request){

        \DB::beginTransaction();

        try {

            $data = $request['attributes'] ?? [];

            $this->validateRequestApi(new UnAssignRoleRequest($data));

            $this->role->unassign($data);

            $response = ['data' => 'Role unassigned to User'];

            \DB::commit();

        } catch (\Exception $e) {
            \Log::error($e);
            \DB::rollback();
            $status = $this->getStatusError($e->getCode());
            $response = ["errors" => $e->getMessage()];
        }
        return response()->json($response, $status ?? 200);
    }

}
