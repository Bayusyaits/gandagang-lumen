<?php

namespace Modules\CustomerModule\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class CustomerModuleController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function getCustomerList()
    {
        return 'customer list';
    }
}
