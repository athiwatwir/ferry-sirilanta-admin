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
            env("168_URL") . '/images/icon-station/1.png',
            env("168_URL") . '/images/icon-station/2.png',
            env("168_URL") . '/images/icon-station/3.png',
            env("168_URL") . '/images/icon-station/4.png',
            env("168_URL") . '/images/icon-station/5.png',
            env("168_URL") . '/images/icon-station/6.png',
            env("168_URL") . '/images/icon-station/7.png',
            env("168_URL") . '/images/icon-station/8.png',
            env("168_URL") . '/images/icon-station/9.png',
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
        Tag::whereId($id)->update(['icon_1' => $request->icon_1]);
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
}
