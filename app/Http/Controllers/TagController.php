<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tags = Tag::orderBy('sort', 'ASC')->get();

        return view('pages.tag.index', [
            'title' => 'Tag',
            'tags' => $tags
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
        //
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
        $tag = Tag::whereId($id)->first();
        $icons = [

            env("168_URL") . '/images/icon-station/01.png',
            env("168_URL") . '/images/icon-station/02.png',
            env("168_URL") . '/images/icon-station/03.png',
            env("168_URL") . '/images/icon-station/04.png',
            env("168_URL") . '/images/icon-station/05.png',
            env("168_URL") . '/images/icon-station/06.png',
            env("168_URL") . '/images/icon-station/07.png',
            env("168_URL") . '/images/icon-station/08.png',
            env("168_URL") . '/images/icon-station/09.png',
            env("168_URL") . '/images/icon-station/10.png',
            env("168_URL") . '/images/icon-station/11.png',
            env("168_URL") . '/images/icon-station/12.png',
            env("168_URL") . '/images/icon-station/13.png',
            env("168_URL") . '/images/icon-station/14.png',
            env("168_URL") . '/images/icon-station/15.png',
            env("168_URL") . '/images/icon-station/16.png',
        ];

        return view('pages.tag.edit', [
            'title' => 'Edit Tag ' . $tag->name,
            'tag' => $tag,
            'icons' => $icons
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        Tag::whereId($id)->update(['icon_1' => $request->icon_1, 'badge_text' => $request->badge_text]);
        session()->flash('success', __('messages.updated'));
        return redirect()->route('tag.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function station(string $tagId)
    {
        $tag = Tag::whereId($tagId)->with(['stations'])->first();
        //dd($tag->stations);
        return view('pages.tag.station', [
            'tag' => $tag,
            'breadcrumbs' => [
                'Tag List' => route('tag.index'),
                'Station' => ''
            ],
            'title' => 'Station in Tag #' . $tag->name
        ]);
    }

    public function updateSort(Request $request)
    {
        $station_tag_ids = $request->station_tag_ids; // เช่น ["uuid1", "uuid2", "uuid3", ...]
        $tagId = $request->tag_id;

        if (empty($station_tag_ids)) {
            return redirect()->back()->with('error', 'No tags provided.');
        }

        $cases = [];
        $ids = [];

        foreach ($station_tag_ids as $index => $id) {
            $id = addslashes($id); // ป้องกัน injection ถ้าเป็น UUID
            $cases[] = "WHEN id = '$id' THEN " . ($index + 1);
            $ids[] = "'$id'";
        }

        $cases_sql = implode(' ', $cases);
        $ids_sql = implode(',', $ids);

        $query = "UPDATE station_tags SET sort = CASE $cases_sql END WHERE id IN ($ids_sql)";
        \DB::statement($query);

        session()->flash('success', __('messages.updated'));
        return redirect()->route('tag.station', ['tag' => $tagId]);
    }
}
