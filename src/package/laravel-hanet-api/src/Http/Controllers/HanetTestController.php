<?php
namespace Htqxd\LaravelHanetApi\Http\Controllers;

use Htqxd\LaravelHanetApi\Actions\GetHanetPlaces;
use Illuminate\Http\Request;

class HanetTestController
{
    public function index(Request $request)
    {
        dd(hanet()->device()->getListDevice()->getData(true));
        dd(hanet()->person()->getListByPlace('13045')->getData(true));
    }
    
}
