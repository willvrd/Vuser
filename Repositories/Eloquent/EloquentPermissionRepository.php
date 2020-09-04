<?php

namespace Modules\Vuser\Repositories\Eloquent;

use Modules\Vuser\Repositories\PermissionRepository;
use Modules\Vcore\Repositories\Eloquent\EloquentBaseRepository;

class EloquentPermissionRepository extends EloquentBaseRepository implements PermissionRepository
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
            ->orWhere('guard_name', 'like', '%' . $filter->search . '%')
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

        $model = $this->getModel($data);
        $model->syncPermissions($data['permissions']);

    }

    public function revoke($data){

        $model = $this->getModel($data);
        $model->revokePermissionTo($data['permission']);

    }

    public function getModel($data){

        $repo = "Modules\Vuser\Repositories\RoleRepository";
        if($data['criteriaType']==="user")
            $repo = "Modules\Vuser\Repositories\UserRepository";

        $model = app($repo)->find($data['criteriaId']);

        if (!$model) throw new \Exception('Item not found', 204);

        return $model;

    }

}
