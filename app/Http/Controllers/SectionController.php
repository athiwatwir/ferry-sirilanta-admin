<?php

namespace App\Http\Controllers;

use App\Models\Section;
use App\Models\Station;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sections = Section::orderBy('sort')->get();

        return view('pages.section.index', [
            'title' => 'Section Management',
            'sections' => $sections
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
        ]);

        $section = Section::create($request->all());

        if ($section) {
            session()->flash('success', __('messages.saved'));
        }

        return redirect()->route('section.index');
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
        $section = Section::find($id); // ใช้ find แทน where + first
        $count = Section::count();     // ไม่ต้องดึง all() มาให้เปลือง memory

        $sortOptions = collect(range(1, $count))->mapWithKeys(function ($i) {
            return [$i => $i];
        });
        return view('pages.section.edit', [
            'title' => 'Edit Section ' . $section->name,
            'section' => $section,
            'sortOptions' => $sortOptions,
            'breadcrumbs' => [
                'Section List' => route('section.index'),
                'Edit Section' => ''
            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string',
            'sort' => 'required|integer',
        ]);

        $section = Section::find($id);
        $oldSort = $section->sort;
        $newSort = $request->sort;

        $section->update($request->all());
        if ($section) {
            session()->flash('success', __('messages.updated'));
        }

        if ($oldSort != $newSort) {
            $sections = Section::orderBy('sort')->orderByDesc('updated_at')->get();
            foreach ($sections as $index => $section) {
                $section->sort = $index + 1; // +1 ถ้าอยากให้เริ่มจาก 1 แทน 0
                $section->save(); // อย่าลืม save ถ้าต้องการบันทึกลง database
            }
        }

        return redirect()->route('section.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $stationCount = Station::where('section_id', $id)->count();
        if ($stationCount > 0) {
            session()->flash('error', 'This section has many stations, cant not delete.');
            return redirect()->route('section.index');
        }

        Section::where('id', $id)->delete();
        session()->flash('success', __('messages.deleted'));
        return redirect()->route('section.index');
    }

    public function changeStatus(Request $request, $id)
    {
        // $id = $request->id;
        $section = Section::where('id', $id)->first();

        if ($section) {
            $section->isactive = $section->isactive == 'Y' ? 'N' : 'Y';
            $section->save();
            session()->flash('success', __('messages.updated'));
        }


        return redirect()->route('section.index');
    }
}
