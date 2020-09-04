<?php

namespace Modules\Vuser\Http\Controllers\Api;

// Requests & Response
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Modules\Vuser\Http\Requests\CreatePermissionRequest;
use Modules\Vuser\Http\Requests\AssignPermissionRequest;
use Modules\Vuser\Http\Requests\RevokePermissionRequest;

// Base
use Modules\Vcore\Http\Controllers\Api\VcoreApiController;

// Repositories
use Modules\Vuser\Repositories\PermissionRepository;

// Transformers
use Modules\Vuser\Transformers\PermissionTransformer;

class PermissionApiController extends VcoreApiController
{

    private $permission;

    public function __construct(
        PermissionRepository $permission
    ){
        $this->permission = $permission;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {

        try {

            //Request to Repository
            $permissions = $this->permission->getItemsBy($this->getParamsRequest($request));

            //Response
            $response = ['data' => PermissionTransformer::collection($permissions)];

            //If request pagination add meta-page
            $request->page ? $response['meta'] = ['page' => $this->pageTransformer($permissions)] : false;


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
            $permission = $this->permission->getItem($criteria,$this->getParamsRequest($request));

            //Break if no found item
            if (!$permission) throw new \Exception('Item not found', 204);

            $response = [
                'data' => $permission ? new PermissionTransformer($permission) : '',
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

            $this->validateRequestApi(new CreatePermissionRequest($data));

            $permission = $this->permission->create($data);

            $response = ["data" => new PermissionTransformer($permission)];

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

            $this->validateRequestApi(new CreatePermissionRequest($data));

            $params = $this->getParamsRequest($request);

            // Search entity
            $entity = $this->permission->getItem($criteria,$params);

            //Break if no found item
            if (!$entity) throw new \Exception('Item not found', 204);

            $permission = $this->permission->update($entity,$data);

            $response = ['data' => new PermissionTransformer($permission)];

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
            $entity = $this->permission->getItem($criteria,$params);

            //Break if no found item
            if (!$entity) throw new \Exception('Item not found', 204);

            $this->permission->destroy($entity);

            $response = ['data' => 'Item deleted'];

        } catch (\Exception $e) {
            \Log::Error($e);
            \DB::rollback();//Rollback to Data Base
            $status = $this->getStatusError($e->getCode());
            $response = ["errors" => $e->getMessage()];
        }

        return response()->json($response, $status ?? 200);

    }


    /** Assign Permission
    * @param Request $request
    */
    public function assign(Request $request){

        \DB::beginTransaction();

        try {

            $data = $request['attributes'] ?? [];

            $this->validateRequestApi(new AssignPermissionRequest($data));

            $this->permission->assign($data);

            $response = ['data' => 'Permission(s) assigned to Role'];

            \DB::commit();

        } catch (\Exception $e) {
            \Log::error($e);
            \DB::rollback();
            $status = $this->getStatusError($e->getCode());
            $response = ["errors" => $e->getMessage()];
        }
        return response()->json($response, $status ?? 200);
    }

    /** Revoke Permission
    * @param Request $request
    */
    public function revoke(Request $request){

        \DB::beginTransaction();

        try {

            $data = $request['attributes'] ?? [];

            $this->validateRequestApi(new RevokePermissionRequest($data));

            $this->permission->revoke($data);

            $response = ['data' => 'Permission revoked'];

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
