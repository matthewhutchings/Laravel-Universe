<?php

namespace Laravel\Telescope\Http\Controllers;

use App\Models\UniverseDashboards;
use Illuminate\Routing\Controller;
use Laravel\Telescope\Telescope;

class DashboardController extends Controller
{
    /**
     * Display the Telescope view.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        return UniverseDashboards::whereSlug($slug)->first();
    }
}
