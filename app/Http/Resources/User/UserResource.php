<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'type' => 'users',
            'id' => (string)$this->user_id,
            'attributes' => [
                'user_type' => $this->user_type,
                'user_name' => $this->user_name,
                'user_document' => $this->user_document,
                'user_email' => $this->user_email,
                'user_wallet' => $this->user_wallet,
            ],
            'links' => [
                'self' => route('showUsers', $this->user_id)
            ]
        ];
    }
}
