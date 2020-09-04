<?php

namespace Modules\Vuser\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class RoleTransformer extends JsonResource
{
    public function toArray($request)
    {
        $item = [
            'id' => $this->when($this->id, $this->id),
            'name' => $this->when($this->name, $this->name),
            'guardName' => $this->when($this->guard_name, $this->guard_name),
            'permissions' => PermissionTransformer::collection($this->whenLoaded('permissions')),
            'users' => UserTransformer::collection($this->whenLoaded('users')),
            'createdAt' => $this->when($this->created_at, $this->created_at->format('Y-m-d H:i:s')),
            'updatedAt' => $this->when($this->updated_at, $this->updated_at->format('Y-m-d H:i:s'))
        ];

        return $item;

    }
}
