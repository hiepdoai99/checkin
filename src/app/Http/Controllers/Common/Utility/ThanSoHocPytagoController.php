<?php

namespace App\Http\Controllers\Common\Utility;

use App\Actions\ThanSoHocPytago;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ThanSoHocPytagoController extends Controller
{
    public function get(Request $request)
    {
        Validator::make($request->all(), [
            'full_name' => ['required', 'string'],
            'ngay' => ['required', 'integer', 'min:1'],
            'thang' => ['required', 'integer', 'min:1'],
            'nam' => ['required', 'integer', 'min:1'],
        ])->validate();
        return response()->json(
            app(ThanSoHocPytago::class)->execute($request->ngay, $request->thang, $request->nam, $request->full_name),
            200
        );
    }
}
