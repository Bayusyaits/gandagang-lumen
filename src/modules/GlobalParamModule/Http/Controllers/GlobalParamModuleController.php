<?php

namespace Modules\GlobalParamModule\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class GlobalParamModuleController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function getCategory()
    {
        return 'global param category';
    }
}
