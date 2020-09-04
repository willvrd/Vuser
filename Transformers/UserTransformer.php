<?php

namespace Modules\Vuser\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class UserTransformer extends JsonResource
{
    public function toArray($request)
    {
        $item = [
            'id' => $this->when($this->id, $this->id),
            'name' => $this->when($this->name, $this->name),
            'email' => $this->when($this->email, $this->email),
            'emailVerifiedAt' => ($this->email_verified_at)?$this->email_verified_at->format('Y-m-d H:i:s'):null,
            'roles' => RoleTransformer::collection($this->whenLoaded('roles')),
            'phone' => $this->when($this->phone, $this->phone),
            'birthday' => $this->when($this->birthday, $this->birthday),
            'identification' => $this->when($this->identification, $this->identification),
            'image' => $this->when($this->image, $this->image),
            'options' => $this->when($this->options, $this->options),
            'createdAt' => $this->when($this->created_at, $this->created_at->format('Y-m-d H:i:s')),
            'updatedAt' => $this->when($this->updated_at, $this->updated_at->format('Y-m-d H:i:s'))
        ];

        return $item;

    }
}
