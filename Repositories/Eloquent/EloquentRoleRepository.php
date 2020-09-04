<?php

namespace Modules\Vuser\Repositories\Eloquent;

use Modules\Vuser\Repositories\RoleRepository;
use Modules\Vcore\Repositories\Eloquent\EloquentBaseRepository;

class EloquentRoleRepository extends EloquentBaseRepository implements RoleRepository
{

    public function getItemsBy($params = false)
    {

      // INITIALIZE QUERY
      $query = $this->model->query();

      // RELATIONSHIPS
      $defaultInclude = [];
      $query->with(array_merge($defaultInclude, $params->include));

      // FILTERS
      if($params->filter) {
        $filter = $params->filter;

        //add filter by search
        if (isset($filter->search)) {
            //find search in columns
            $query->where(function ($query) use ($filter) {
            $query->where('id', 'like', '%' . $filter->search . '%')
            ->orWhere('name', 'like', '%' . $filter->search . '%')
            ->orWhere('updated_at', 'like', '%' . $filter->search . '%')
            ->orWhere('created_at', 'like', '%' . $filter->search . '%');
            });
        }

        //add filter by date
        if (isset($filter->date)) {
          $date = $filter->date;//Short filter date
          $date->field = $date->field ?? 'created_at';
          if (isset($date->from))//From a date
              $query->whereDate($date->field, '>=', $date->from);
          if (isset($date->to))//to a date
              $query->whereDate($date->field, '<=', $date->to);
        }

         //Order by
        if (isset($filter->order)) {
          $orderByField = $filter->order->field ?? 'created_at';//Default field
          $orderWay = $filter->order->way ?? 'desc';//Default way
          $query->orderBy($orderByField, $orderWay);//Add order to query
        }


      }

      /*== FIELDS ==*/
      if (isset($params->fields) && count($params->fields))
        $query->select($params->fields);

      /*== REQUEST ==*/
      if (isset($params->page) && $params->page) {
        return $query->paginate($params->take);
      } else {
        $params->take ? $query->take($params->take) : false;//Take
        return $query->get();
      }

    }


    public function assign($data){

        $user = app("Modules\Vuser\Repositories\UserRepository")->find($data['userId']);
        $user->syncRoles($data['roles']);

    }

    public function unassign($data){

        $user = app("Modules\Vuser\Repositories\UserRepository")->find($data['userId']);
        $user->removeRole($data['role']);

    }

}
