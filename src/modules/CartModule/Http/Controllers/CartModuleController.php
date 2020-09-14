<?php

namespace Modules\CartModule\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class CartModuleController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function getCartList()
    {
        return 'cart list';
    }
}
