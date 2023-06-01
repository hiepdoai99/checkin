<?php
namespace Htqxd\LaravelHanetApi\Http\Controllers;

use Illuminate\Http\Request;
use Htqxd\LaravelHanetApi\Models\Hanet;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class HanetCallbackController
{
    public function __invoke(Request $request)
    {
        $response = hanet()->getAccessToken($request->query('code'));
        
        if (! Schema::hasTable('hantes')) {
            Log::critical('[HANET] Table hanets not exists!');
            return 0;
        }

        return Hanet::firstOrCreate(
            ['email' => $response['email']],
            $response
        );
    }
    
}
