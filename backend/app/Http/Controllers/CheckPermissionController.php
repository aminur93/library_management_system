<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckPermissionController extends Controller
{
    public function checkPermission(Request $request)
    {

        $user = Auth::user();

        $permission = $request->query('permission');

        if ($user && $user->hasPermissionTo($permission))
        {
            return response()->json(['message' => 'Permission granted'], 200);
        }

        return response()->json(['message' => 'Forbidden'], 403);
    }
}
