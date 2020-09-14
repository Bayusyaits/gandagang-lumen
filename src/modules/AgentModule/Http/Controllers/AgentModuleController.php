<?php

namespace Modules\AgentModule\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class AgentModuleController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function getAgentList()
    {
        return 'agent list';
    }
}
