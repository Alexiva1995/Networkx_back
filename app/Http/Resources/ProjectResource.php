<?php

namespace App\Http\Resources;

use App\Http\Resources\FormularyResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
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
            'user' => [
                'name' => ucwords(strtolower($this->order->user->name." ".$this->order->user->last_name)),
                'email' => $this->order->user->email,
            ],
            'order' => $this->order,
            'formulary' => $this->formulary,
            'amount' => $this->amount,
            'phase1' => $this->phase1,
            'phase2' => $this->phase2,
            'complete' => $this->complete,
            'status' => $this->status,
            'package' => $this->order->packageMembership,
            'package_type' => intval($this->order->packageMembership->type),
            'package_account' => $this->order->packageMembership->account,
            'package_name' => $this->order->packageMembership->getTypeName(),
        ];
    }
}
