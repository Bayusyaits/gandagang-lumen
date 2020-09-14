<?php

namespace Modules\WarehouseModule\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class WarehouseModuleController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function getWarehouseList()
    {
        return 'warehouse list';
    }
}
