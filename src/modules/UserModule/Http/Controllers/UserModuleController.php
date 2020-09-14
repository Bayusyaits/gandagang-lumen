<?php

namespace Modules\UserModule\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class UserModuleController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function getCompletingData(Request $request, $userRoleType = '')
    {
        return generateToken(32);
        return response()->json([
            'data' => $userRoleType
        ], 400);
        ;
    }
}
