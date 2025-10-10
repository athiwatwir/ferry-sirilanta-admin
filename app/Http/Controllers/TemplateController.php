<?php

namespace App\Http\Controllers;

use App\Models\Route;
use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class TemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $routes = Route::with([
            'departStation.section', // preload section ผ่าน depart
            'subRoutes'
        ])
            ->join('stations as depart', 'routes.depart_station_id', '=', 'depart.id')
            ->join('sections', 'depart.section_id', '=', 'sections.id')
            ->orderBy('sections.sort', 'asc')
            ->select('routes.*')
            ->get();

        return view('pages.template.create', [
            'title' => 'Create Route Template',
            'routes' => $routes
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        //dd($request->all());
        $template = Template::create([
            'name' => $request->name
        ]);

        $now = Carbon::now();

        $lines = [];
        foreach ($request->subroute_ids as $subrouteId) {
            $lines[] = [
                'id' => (string) Str::uuid(),
                'template_id' => $template->id,
                'sub_route_id' => $subrouteId,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }
        DB::table('template_lines')->insert($lines);

        return redirect()->route('routeSchedule.create');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $template = Template::with(['templateLines'])->where('id', $id)->first();

        // dd($template);
        return view('pages.template.show', [
            'title' => 'Template Detail',
            'template' => $template
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
