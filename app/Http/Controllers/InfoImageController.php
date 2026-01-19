<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\InfoImage;
use Illuminate\Http\Request;

class InfoImageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $infoImages = InfoImage::orderBy('type', 'ASC')->get();

        return view('pages.infoImage.index', [
            'title' => 'Route Map/Time Table',
            'infoImages' => $infoImages,
            'breadcrumbs' => [
                'Info Image' => route('infoImage.index'),
                'List' => ''
            ]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //

        return view('pages.infoImage.create',[
            'title' => 'Upload New Route Map/Time Table'
        ] );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'type' => 'required|string|in:route_map,time_table',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
        ]);

        // Upload file
        $file = $request->file('image');
        $extension = $file->getClientOriginalExtension();
        $filename = uniqid() . '_' . time() . '.' . $extension;
        $path = $file->storeAs('info_images', $filename, 'public');
        $imagePath = 'storage/' . $path;

        // Create InfoImage record
        InfoImage::create([
            'name' => $request->name,
            'type' => $request->type,
            'description' => $request->description,
            'image_path' => $imagePath,
        ]);

        session()->flash('success', __('messages.created'));
        return redirect()->route('infoImage.index');
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
    public function edit(InfoImage $infoImage)
    {
        return view('pages.infoImage.edit', [
            'title' => 'Edit Route Map/Time Table',
            'infoImage' => $infoImage,
            'breadcrumbs' => [
                'Route Map/Time Table' => route('infoImage.index'),
                'Edit' => ''
            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, InfoImage $infoImage)
    {
        $request->validate([
            'name' => 'required|string',
            'type' => 'required|string|in:route_map,time_table',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
        ]);

        $updateData = [
            'name' => $request->name,
            'type' => $request->type,
            'description' => $request->description,
        ];

        // Update file if new image is uploaded
        if ($request->hasFile('image')) {
            // Delete old file if exists
            if ($infoImage->image_path && file_exists(public_path($infoImage->image_path))) {
                unlink(public_path($infoImage->image_path));
            }

            // Upload new file
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = uniqid() . '_' . time() . '.' . $extension;
            $path = $file->storeAs('info_images', $filename, 'public');
            $updateData['image_path'] = 'storage/' . $path;
        }

        // Update InfoImage record
        $infoImage->update($updateData);

        session()->flash('success', __('messages.updated'));
        return redirect()->route('infoImage.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
