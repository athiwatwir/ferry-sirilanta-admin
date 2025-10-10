<?php

namespace App\Http\Controllers;

use App\Models\Section;
use App\Models\Station;
use App\Services\StationHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class StationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $sections = Section::with('stations')->orderBy('sort')->get();
        //$stations = Station::with('section')->orderBy('sort')

        return view('pages.station.index', [
            'title' => 'Stations',
            'sections' => $sections
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $sections = Section::orderBy('name')->get()->pluck('name', 'id');
        $types = StationHelper::getTypes();

        return view('pages.station.create', [
            'title' => 'Create Station',
            'sections' => $sections,
            'types' => $types,
            'breadcrumbs' => [
                'Station List' => route('station.index'),
                'Create Station' => ''
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name_en' => 'required|string',
            'name_th' => 'required|string',
            'piername_en' => 'string|nullable',
            'piername_th' => 'string|nullable',
            'nickname' => 'required|string|unique:stations',
            'section_id' => 'required|string',
        ]);

        $station = Station::create($request->all());
        if ($station) {
            $this->clearCache();
            session()->flash('success', __('messages.saved'));
        }

        return redirect()->route('station.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $station = Station::where('id', $id)->first();
        if (!$station) {
            return redirect()->route('station.index');
        }

        $sections = Section::orderBy('name')->get()->pluck('name', 'id');
        $types = StationHelper::getTypes();

        $count = Station::where('section_id', $station->section_id)->count();     // ไม่ต้องดึง all() มาให้เปลือง memory

        $sortOptions = collect(range(1, $count))->mapWithKeys(function ($i) {
            return [$i => $i];
        });

        return view('pages.station.edit', [
            'title' => 'Edit Station',
            'sections' => $sections,
            'station' => $station,
            'types' => $types,
            'breadcrumbs' => [
                'Station List' => route('station.index'),
                'Edit Station' => ''
            ],
            'sortOptions' => $sortOptions
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name_en' => 'required|string',
            'name_th' => 'required|string',
            'piername_en' => 'string|nullable',
            'piername_th' => 'string|nullable',
            'nickname' => 'required|string',
            'section_id' => 'required|string',
        ]);

        $station = Station::find($id);
        $oldSort = $station->sort;
        $newSort = $request->sort;
        $oldStation = clone $station;

        $station->update($request->all());

        if ($oldSort != $newSort) {
            $stations = Station::where('section_id', $station->section_id)->orderBy('sort')->orderByDesc('updated_at')->get();
            foreach ($stations as $index => $_station) {
                $_station->sort = $index + 1; // +1 ถ้าอยากให้เริ่มจาก 1 แทน 0
                $_station->save(); // อย่าลืม save ถ้าต้องการบันทึกลง database
            }
        }

        if ($station) {
            $this->clearCache();
            session()->flash('success', __('messages.updated'));
        }

        return redirect()->route('station.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Station::where('id', $id)->delete();
        session()->flash('success', __('messages.deleted'));
        $this->clearCache();
        return redirect()->route('station.index');
    }

    public function changeStatus(Request $request, $id)
    {
        // $id = $request->id;
        $station = Station::where('id', $id)->first();

        if ($station) {
            $station->isactive = $station->isactive == 'Y' ? 'N' : 'Y';
            $station->save();
            session()->flash('success', __('messages.updated'));

            $this->clearCache();
        }
        return redirect()->route('station.index');
    }


    private function clearCache()
    {
        Cache::forget('depart_station_service');
        Cache::forget('dest_station_service');
    }
}
