<?php

namespace Modules\StorageModule\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class StorageModuleController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function getFile()
    {
        return 'storage file';
    }
}
