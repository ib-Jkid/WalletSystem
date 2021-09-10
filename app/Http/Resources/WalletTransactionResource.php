<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class WalletTransactionResource extends JsonResource
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
            "id" =>  $this->public_id,
            "amount" => $this->amount / 100,
            "type" => $this->type,
            "date" => Carbon::parse($this->created_at)->toDateTimeString()
        ];
    }
}