<?php

namespace App\Http\Controllers;

use App\Models\Information;
use App\Services\InformationService;
use Illuminate\Http\Request;

class InformationTextController extends Controller
{

    public static function getPosition()
    {
        return [
            'TERM' => 'Terms & Conditions',
            'TERM_TICKET' => 'Ticket Terms & Conditions (Show on ticket PDF)',
            'BAGGAGE_POLICY' => 'Baggage Policy',
            'TERMS_OF_SERVICE' => 'Terms of Service',
            'PRIVACY_POLICY' => 'Privacy Policy',
            'Q&A' => 'Q&A',
            'PRIVATE_CHATER_BOAT' => 'Private Chater Boat',
            'announcement' => 'Announcement on Home page'
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        //$informations = app(InformationService::class)->getAll();
        $positions = $this->getPosition();
        $informations = Information::where('agent_id', env('AGENT_ID'))->orderBy('position')->get();

        return view('pages.informationText.index', [
            'title' => 'Information Text',
            'informations' => $informations,
            'positions' => $positions
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $positions = $this->getPosition();
        return view('pages.informationText.create', [
            'title' => 'Create Information Text',
            'positions' => $positions
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'position' => 'required|string',
            'body' => 'required|string',
        ]);

        $information = Information::create([
            'agent_id' => env('AGENT_ID'),
            'title' => $request->title,
            'position' => $request->position,
            'body' => $request->body,
        ]);

        return redirect()->route('informationText.index')->with('success', 'Information Text created successfully');
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
        $positions = $this->getPosition();
        $information = Information::whereId($id)->first();

        return view('pages.informationText.edit', [
            'title' => 'Edit Information Text',
            'information' => $information,
            'positions' => $positions
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $information = Information::whereId($id)->first();
        $information->update($request->all());
        return redirect()->route('informationText.index')->with('success', 'Information Text updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $information = Information::whereId($id)->first();
        $information->delete();
        return redirect()->route('informationText.index')->with('success', 'Information Text deleted successfully');
    }
}
