<?php

namespace Modules\PromoModule\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class PromoModuleController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function getPromoList()
    {
        return 'Promo List';
    }
}
