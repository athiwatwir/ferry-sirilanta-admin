<?php

namespace App\Http\Controllers;

use App\Models\AgentUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = AgentUser::where('agent_id',env("AGENT_ID"))->orderBy('fullname')->get();

        return view('pages.user.index', [
            'title' => 'User Management',
            'users' => $users
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        return view('pages.user.create', [
            'title' => 'Create User',

        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string',
        ]);

        $user = User::create($request->all());

        if ($user) {
            session()->flash('success', __('messages.saved'));
        }

        return redirect()->route('user.index');
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
        $user = User::whereId($id)->first();
        return view('pages.user.edit', [
            'title' => 'Edit User',
            'user' => $user
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::whereId($id)->first();
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',
        ]);



        $user->name = $request->name;
        $user->email = $request->email;
        $user->station_id = $request->station_id ?? null;
        $user->update();

        session()->flash('success', __('messages.updated'));
        return redirect()->route('user.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        User::where('id', $id)->delete();
        session()->flash('success', __('messages.deleted'));
        return redirect()->route('user.index');
    }
}
