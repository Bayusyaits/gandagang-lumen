<?php

namespace Modules\SellerModule\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class SellerModuleController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function getSellerList()
    {
        return 'seller';
    }
}
