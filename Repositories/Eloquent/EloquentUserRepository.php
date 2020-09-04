<?php

namespace Modules\Vuser\Repositories\Eloquent;

use Modules\Vuser\Repositories\UserRepository;
use Modules\Vcore\Repositories\Eloquent\EloquentBaseRepository;

// Hash
use Illuminate\Support\Facades\Hash;


class EloquentUserRepository extends EloquentBaseRepository implements UserRepository
{

    public function create($data){

        $this->hashPassword($data);

        $model = $this->model->create($data);

        $roles[0] = "user";
        if(isset($data['roles'])){
            if((in_array("super-admin",$data['roles']) || in_array("admin",$data['roles'])) && !(\Auth::check())){
                $roles[0] = "user";
            }else{
                $roles = $data['roles'];
            }
        }

        $model->syncRoles($roles);

        return $model;

    }

     /**
     * Hash the password key
     * @param array $data
     */
    private function hashPassword(array &$data)
    {
        $data['password'] = Hash::make($data['password']);
    }

}
