<?php

namespace Modules\PaymentModule\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class PaymentModuleController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function getPaymentList()
    {
        return 'payment';
    }
}
