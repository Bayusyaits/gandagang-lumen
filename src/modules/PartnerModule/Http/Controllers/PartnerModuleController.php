<?php

namespace Modules\PartnerModule\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class PartnerModuleController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function getPartnerList()
    {
        return 'partner';
    }
}
