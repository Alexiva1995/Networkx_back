<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class UserCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
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
