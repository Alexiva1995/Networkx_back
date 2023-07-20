<?php

namespace App\Http\Resources;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Resources\Json\JsonResource;

class FormularyResource extends JsonResource
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
            'project_id' => $this->project_id,
            'name' => $this->name,
            'login' => $this->login,
            'password' => Crypt::decryptString($this->password),
            'leverage' => $this->leverage,
            'balance' => $this->balance,
            'server' => $this->server,
            'date' => $this->date,
        ];
    }
}
