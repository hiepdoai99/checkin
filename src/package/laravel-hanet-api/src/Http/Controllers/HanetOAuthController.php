<?php
namespace Htqxd\LaravelHanetApi\Http\Controllers;

use Illuminate\Http\Request;

class HanetOAuthController
{
    public function __invoke(Request $request)
    {
        return redirect()->away(
            hanet()->getAuthorizationUrl()
        );
    }
    
}
