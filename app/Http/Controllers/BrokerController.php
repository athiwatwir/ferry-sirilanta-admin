<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\SalesPartner;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BrokerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $brokers = SalesPartner::with('user', 'agentAccount')->where('type', 'broker')->where('agent_id', env('AGENT_ID'))->get();
        return view('pages.broker.index', [
            'title' => 'Broker',
            'brokers' => $brokers
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('pages.broker.create', [
            'title' => 'Create Broker'
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
        //
        $broker = SalesPartner::with('user', 'agentAccount')->find($id);
        return view('pages.broker.show', [
            'title' => 'Broker > ' . $broker->name,
            'broker' => $broker,
            'breadcrumbs' => [
                'All Broker' => route('broker.index'),
                'View' => ''
            ],
        ]);
    }

    /**
     * แก้ไข credit_limit ของ broker (AgentAccount)
     */
    public function updateCreditLimit(Request $request, string $id)
    {
        $request->validate([
            'credit_limit' => 'required|numeric|min:0',
        ], [
            'credit_limit.required' => 'กรุณาระบุวงเงินเครดิต',
        ]);

        $broker = SalesPartner::with('agentAccount')->findOrFail($id);
        if (!$broker->agentAccount) {
            return redirect()->route('broker.show', $broker)->with('error', 'ไม่พบบัญชี Agent ของ Broker นี้');
        }

        $broker->agentAccount->update(['credit_limit' => $request->credit_limit]);

        return redirect()->route('broker.show', $broker)->with('success', 'อัพเดทวงเงินเครดิตเรียบร้อย');
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

    public function credit(Request $request)
    {
        $salesPartner = SalesPartner::with('user', 'agentAccount')->find(Auth::user()->sales_partner_id);

        $month = $request->get('month'); // YYYY-MM from input type="month"
        if ($month && preg_match('/^\d{4}-\d{2}$/', $month)) {
            $calendarDate = $month . '-01';
        } else {
            $calendarDate = now()->format('Y-m-01');
        }

        $startOfMonth = Carbon::parse($calendarDate)->startOfMonth();
        $endOfMonth = Carbon::parse($calendarDate)->endOfMonth();

        $dailyTotals = Booking::where('sales_partner_id', $salesPartner->id)
            ->whereBetween('departdate', [$startOfMonth, $endOfMonth])
            ->whereNotIn('status', ['delete', 'void', 'VO', 'EXPIRED'])
            ->selectRaw('DATE(departdate) as date, COALESCE(SUM(totalamt), 0) as total')
            ->groupBy('date')
            ->get()
            ->keyBy('date')
            ->map(fn($row) => (float) $row->total);

        return view('pages.broker.credit', [
            'title' => 'Credit',
            'salesPartner' => $salesPartner,
            'calendarDate' => $calendarDate,
            'dailyTotals' => $dailyTotals,
        ]);
    }

    public function user(string $id)
    {
        $broker = SalesPartner::with('users', 'agentAccount')->find($id);
        return view('pages.broker.user.user', [
            'title' => 'Broker > ' . $broker->name . ' > Users',
            'broker' => $broker,

        ]);
    }

    public function createUser(string $id)
    {
        $broker = SalesPartner::find($id);
        return view('pages.broker.user.create', [
            'title' => 'Broker > ' . $broker->name . ' > Create User',
            'broker' => $broker,
            'breadcrumbs' => [
                'All Broker' => route('broker.index'),
                $broker->name => route('broker.show', $broker),
                'รายชื่อพนักงาน' => route('broker.user', $broker),
                'Create' => ''
            ],
        ]);
    }

    public function storeUser(Request $request, string $id)
    {
        $broker = SalesPartner::find($id);
        $data  = $request->all();
        $data['sales_partner_id'] = $broker->id;
        $data['isdefault'] = 'N';
        $data['role'] = 'broker_employee';
        $data['agent_id'] = env('AGENT_ID');
        $broker->user()->create($data);

        return redirect()->route('broker.user', $broker);
    }

    public function editUser(string $id)
    {
        $user = User::find($id);
        return view('pages.broker.user.edit', [
            'title' => 'Broker > ' . $user->name . ' > Edit User',
            'user' => $user,
        ]);
    }

    public function updateUser(Request $request, string $id)
    {
        $user = User::find($id);
        $data = $request->all();

        if (empty($data['password'])) {
            unset($data['password']);
        }

        $user->update($data);
        //dd($data);
        return redirect()->route('broker.user', $user->sales_partner_id);
    }

    public function destroyUser(string $id)
    {
        $user = User::find($id);

        $broker = SalesPartner::find($user->sales_partner_id);
        $user->name = $user->name . '_deleted_' . now()->format('YmdHis');
        $user->email = $user->email . '_deleted_' . now()->format('YmdHis');
        $user->save();
        $user->delete();
        return redirect()->route('broker.user', $broker);
    }
}
