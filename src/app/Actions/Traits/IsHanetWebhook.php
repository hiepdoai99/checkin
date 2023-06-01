<?php

namespace App\Actions\Traits;

use Illuminate\Http\Request;

trait IsHanetWebhook
{
    /**
     * Execute the action.
     *
     * @return mixed
     */
    public function isHanet(array $webhook) : bool
    {
        return md5(hanet()->clientSecret . $webhook['id']) === $webhook['hash'];
    }
}
