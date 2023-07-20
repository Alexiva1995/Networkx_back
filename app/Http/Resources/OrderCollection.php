<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class OrderCollection extends ResourceCollection
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
            'id' => $this->id,
            'user_id' => $this->user_id,
            'coupon_id' => $this->coupon_id,
            'amount' => $this->amount,
            'hash' => $this->hash,
            'image' => $this->image,
            'type' => $this->type,
            'status' => $this->status,
            'membership_packages_id' => $this->membership_packages_id,
            'package_name' => $this->package_name,
            'updated_at' => $this->updated_at,
            'program' => [
                'name' => $this->packageMembership->getTypeName(),
                'phase' => null
            ],
            'user' => [
                'name' => $this->user->name,
                'last_name' => $this->user->last_name,
                'user_name' => $this->user->user_name,
                'email' => $this->user->email,
                'profile_picture' => $this->user->profile_picture,
                'profile_picture' => $this->user->profile_picture,
            ]
        ];
    }
}
