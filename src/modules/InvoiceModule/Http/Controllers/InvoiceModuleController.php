<?php

namespace Modules\InvoiceModule\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class InvoiceModuleController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function getInvoiceList()
    {
        return 'invoice';
    }
}
