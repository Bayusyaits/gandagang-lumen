<?php

namespace Modules\ShipperModule\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class ShipperModuleController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function getShipperList()
    {
        return 'shipper';
    }
}
