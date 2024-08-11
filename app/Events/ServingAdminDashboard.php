<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Http\Request;

class ServingAdminDashboard
{
    use Dispatchable;

    public function __construct(public Request $request)
    {
    }
}
