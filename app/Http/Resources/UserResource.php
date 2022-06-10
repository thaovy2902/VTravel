<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'avatar' => $this->avatar,
            'phone_number' => $this->phone_number,
            'address' => $this->address,
            'is_active' => (bool) $this->is_active,
            'role_name' => $this->whenLoaded('role')->name,
            'role_slug' => $this->whenLoaded('role')->slug,
            'role_id' => $this->role_id,
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
        ];
    }
}
