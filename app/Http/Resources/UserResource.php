<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'user_name' => $this->user_name,
            'email' => $this->email,
            'admin' => $this->admin,
            'status' => $this->status,
            'affiliate' => $this->affiliate,
            'created_at' => $this->created_at,
        ];
    }
}
