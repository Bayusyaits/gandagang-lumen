<?php

namespace Modules\NotificationModule\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class NotificationModuleController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function postNotification()
    {
        return 'post notification';
    }
}
