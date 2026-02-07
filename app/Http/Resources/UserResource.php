<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
			return [
				'id' => $this->id,
				'name' => $this->name,
				'email' => $this->email,
				'phone' => $this->phone,
				'tel' => $this->tel,
				'cpf' => $this->cpf,
				'rg' => $this->rg,
				'sex' => $this->sex,
				'birthdate' => $this->birthdate,
				'country' => $this->country,
				'state' => $this->state,
				'city' => $this->city,
				'affiliated' => $this->affiliated,
			];
    }
}
