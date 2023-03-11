<?php

namespace Laravel\Telescope\Http\Controllers;

use App\Models\UniverseDashboards;
use Illuminate\Routing\Controller;
use Laravel\Telescope\Telescope;

class HomeController extends Controller
{
    /**
     * Display the Telescope view.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //where('name', '!=', 'Overview')->
        $menu = UniverseDashboards::get()->groupBy('group');

        //    dd($menu);
        return view('telescope::layout', [
            'cssFile' => Telescope::$useDarkTheme ? 'app-dark.css' : 'app.css',
            'menu_items' => $menu,
            'telescopeScriptVariables' => Telescope::scriptVariables(),
        ]);
    }
}
