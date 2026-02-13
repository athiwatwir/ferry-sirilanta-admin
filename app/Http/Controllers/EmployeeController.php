<?php

namespace App\Http\Controllers;

use App\Models\SalesPartner;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $brokers = SalesPartner::with('brokerPoint')->where('type', 'employee')->where('agent_id', env('AGENT_ID'))->get();
        return view('pages.employee.index', [
            'title' => 'Employee',
            'brokers' => $brokers
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //

        return view('pages.employee.create', [
            'title' => 'Create Employee',
            'breadcrumbs' => [
                'All Employee' => route('employee.index'),
                'Create' => ''
            ],
            'type' => 'employee'
        ]);
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
        $employee = SalesPartner::with('brokerPoint', 'user')->find($id);

        return view('pages.employee.show', [
            'title' => 'Employee > ' . $employee->name,
            'breadcrumbs' => [
                'All Employee' => route('employee.index'),
                'View' => ''
            ],
            'employee' => $employee
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
