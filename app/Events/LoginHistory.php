<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Auth\Authenticatable;

class LoginHistory
{
    use Dispatchable, SerializesModels;

    public $user;

    public function __construct(Authenticatable $user)
    {
        $this->user = $user;
    }
}
