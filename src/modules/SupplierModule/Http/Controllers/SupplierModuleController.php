<?php

namespace Modules\SupplierModule\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class SupplierModuleController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function getSupplierList()
    {
        return 'supplier';
    }
}
