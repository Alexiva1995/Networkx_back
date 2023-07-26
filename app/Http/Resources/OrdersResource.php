<?php

namespace App\Http\Resources;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Resources\Json\JsonResource;

class OrdersResource extends JsonResource
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
            'id' => $this->id,
            'user_id' => $this->user->id,
            'user_email' => $this->user->email,
            'user_name' => strtolower(explode(" ", $this->user->name)[0] . " " . explode(" ", $this->user->last_name)[0]),
            'status' => $this->status,
            'description' => $this->package->description,
            'hash_id' => $this->hash,
            'amount' => $this->amount,
            'date' => $this->created_at->format('Y-m-d'),
            'update_date' => $this->updated_at->format('Y-m-d'),
        ];
    }
}
