<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status,
            'due_date' => Carbon::parse($this->due_date)->format('d-m-Y H:i'),
            'created_at' => Carbon::parse($this->created_at)->format('d-m-Y H:i'),
            'user_id' => $this->user_id,
            'user' => $this->whenLoaded('user', [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'username' => $this->user->username,
                'email' => $this->user->email,
            ]),
        ];
    }
}
