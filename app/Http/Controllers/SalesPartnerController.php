<?php

namespace App\Http\Controllers;

use App\Models\AgentAccount;
use App\Models\BrokerPoint;
use App\Models\SalesPartner;
use App\Models\User;
use Illuminate\Http\Request;

class SalesPartnerController extends Controller
{

    public static function getSalesPartneTypes()
    {
        return [
            'agent' => 'Agent',
            'broker' => 'Broker',
            'employee' => 'Employee',
        ];
    }

    public static function getDiscountTypes()
    {
        return [
            'per_ticket' => 'Per Ticket',
            'per_seat' => 'Per Seat',
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function agent()
    {
        return view('pages.sales-partner.agent', [
            'title' => 'Agent',

        ]);
    }

    public function broker()
    {
        $brokers = SalesPartner::with('brokerPoint')->where('type', 'broker')->where('agent_id', env('AGENT_ID'))->get();
        return view('pages.sales-partner.broker', [
            'title' => 'Broker',
            'brokers' => $brokers
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $type = request()->type;
        if ($type == 'agent') {
            $title = 'Create Agent';
        } else {
            $title = 'Create Broker';
        }
        return view('pages.sales-partner.create', [
            'title' => $title,
            'breadcrumbs' => [
                'Sales Partner' => route('salesPartner.index'),
                'Create' => ''
            ],
            'type' => $type
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'user.name' => 'required|string',
            'user.email' => 'required|email|unique:users,email',
            'user.password' => 'required|string',
            'discount' => 'nullable|numeric|min:0',
            'discount_type' => 'nullable|string|in:per_ticket,per_seat',
        ], [
            'name.required' => 'กรุณาระบุชื่อ',
            'user.name.required' => 'กรุณาระบุชื่อผู้ใช้งาน',
            'user.email.required' => 'กรุณาระบุอีเมล',
            'user.email.email' => 'รูปแบบอีเมลไม่ถูกต้อง',
            'user.email.unique' => 'อีเมลนี้ถูกใช้งานแล้ว กรุณาใช้อีเมลอื่น',
            'user.password.required' => 'กรุณาระบุรหัสผ่าน',
        ]);

        $data = $request->all();
        $data['agent_id'] = env('AGENT_ID');

        $salesPartner = SalesPartner::create($data);
        if ($salesPartner) {
            $user = User::create([
                'name' => $request->user['name'],
                'email' => $request->user['email'],
                'password' => $request->user['password'],
                'sales_partner_id' => $salesPartner->id,
                'agent_id' => $data['agent_id'],
                'role' => $salesPartner->type,
                'code' => $request->code,
            ]);

            if ($salesPartner->type == 'employee') {
                BrokerPoint::create([
                    'sales_partner_id' => $salesPartner->id,
                    'balance' => 0,
                ]);
                return redirect()->route('employee.index');
            } else if ($salesPartner->type == 'broker') {
                AgentAccount::create([
                    'sales_partner_id' => $salesPartner->id,
                    'type' => 'POST',
                ]);

                return redirect()->route('broker.index');
            } else if ($salesPartner->type == 'agent') {
                $agent = AgentAccount::create([
                    'sales_partner_id' => $salesPartner->id,
                    'type' => 'PRE',
                ]);

                return redirect()->route('agent.show', ['agent' => $salesPartner]);
            }
        } else {
            session()->flash('error', __('messages.error'));
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $broker = SalesPartner::with('brokerPoint', 'user')->find($id);

        if ($broker->type == 'broker') {
            return view('pages.sales-partner.show-broker', [
                'title' => 'Broker > ' . $broker->name,
                'breadcrumbs' => [
                    'Broker' => route('salesPartner.broker'),
                    'View' => ''
                ],
                'broker' => $broker
            ]);
        } else {
        }
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
