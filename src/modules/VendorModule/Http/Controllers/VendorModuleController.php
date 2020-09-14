<?php

namespace Modules\VendorModule\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class VendorModuleController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function getVendorList()
    {
        return 'vendor';
    }
}
