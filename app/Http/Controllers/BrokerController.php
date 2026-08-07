<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\AgentAccountTransection;
use App\Models\Booking;
use App\Models\SalesPartner;
use App\Models\User;
use App\Services\PartnerBookingListService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class BrokerController extends Controller
{
    public function __construct(
        private PartnerBookingListService $partnerBookings
    ) {}
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
        $apiAgents = Agent::where('type', 'API')
            ->orderBy('name')
            ->get()
            ->mapWithKeys(fn(Agent $item) => [
                $item->id => trim($item->name . ($item->code ? " ({$item->code})" : '')),
            ])
            ->all();

        return view('pages.broker.create', [
            'title' => 'Create Broker',
            'breadcrumbs' => [
                'All Broker' => route('broker.index'),
                'Create' => '',
            ],
            'discountTypes' => SalesPartnerController::getDiscountTypes(),
            'apiAgents' => $apiAgents,
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
    public function show(Request $request, string $id)
    {
        $broker = SalesPartner::with([
            'user',
            'users',
            'agentAccount.transections' => fn($q) => $q->orderBy('created_at', 'desc'),
        ])->findOrFail($id);

        $transactions = $broker->agentAccount
            ? $broker->agentAccount->transections
            : collect();

        $bookingResult = $this->partnerBookings->search($request, $broker->id);
        $bookings = $bookingResult['bookings'];

        if ($request->input('export') === 'excel') {
            return $this->partnerBookings->exportExcel($bookings, 'broker', 'broker-booking-report');
        }

        if ($request->input('ispdf') === 'Y') {
            return $this->partnerBookings->exportPdf(
                $bookings,
                'broker',
                $request->input('daterange'),
                $bookingResult['startDate'],
                $bookingResult['endDate'],
                $request->input('date_type'),
                'broker-booking-report'
            );
        }

        return view('pages.broker.show', array_merge($bookingResult['filters'], [
            'title' => 'Broker > ' . $broker->name,
            'broker' => $broker,
            'transactions' => $transactions,
            'bookings' => $bookings,
            'discountTypes' => SalesPartnerController::getDiscountTypes(),
            'startDate' => $bookingResult['startDate'],
            'endDate' => $bookingResult['endDate'],
            'activeTab' => $request->input('tab', 'bookings'),
            'breadcrumbs' => [
                'All Broker' => route('broker.index'),
                'View' => '',
            ],
        ]));
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

        $newLimit = (float) $request->credit_limit;
        $oldLimit = (float) ($broker->agentAccount->credit_limit ?? 0);

        DB::transaction(function () use ($broker, $newLimit, $oldLimit) {
            $account = $broker->agentAccount;
            $account->update(['credit_limit' => $newLimit]);

            AgentAccountTransection::create([
                'agent_account_id' => $account->id,
                'type' => 'credit_limit',
                'amount' => $newLimit,
                'description' => sprintf(
                    'Update Credit Limit: %s → %s THB',
                    number_format($oldLimit, 2),
                    number_format($newLimit, 2)
                ),
                'isapproved' => 'Y',
            ]);
        });

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
        $broker = SalesPartner::with('user')->findOrFail($id);

        $userId = $broker->user?->id;

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:255',
            'discount' => 'nullable|numeric|min:0',
            'discount_type' => ['nullable', 'string', Rule::in(array_keys(SalesPartnerController::getDiscountTypes()))],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($userId),
            ],
            'password' => 'nullable|string|min:8',
        ]);

        $brokerData = [
            'name' => $validated['name'],
            'code' => $validated['code'] ?? null,
            'discount' => $validated['discount'] ?? null,
            'discount_type' => $validated['discount_type'] ?? null,
        ];
        $broker->update($brokerData);


        if ($broker->user) {
            $userData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'code' => $validated['code'] ?? null,
            ];
            if (!empty($validated['password'])) {
                $userData['password'] = $validated['password'];
            }
            $broker->user->update($userData);
        }

        return redirect()->route('broker.show', $broker)->with('success', 'อัปเดตข้อมูล Broker เรียบร้อย');
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

    public function transactions()
    {
        $salesPartner = SalesPartner::with('user', 'agentAccount')->find(Auth::user()->sales_partner_id);

        $transactions = $salesPartner?->agentAccount
            ? AgentAccountTransection::where('agent_account_id', $salesPartner->agentAccount->id)
            ->orderBy('created_at', 'desc')
            ->get()
            : collect();

        return view('pages.broker.transactions', [
            'title' => 'ประวัติการทำรายการ',
            'salesPartner' => $salesPartner,
            'transactions' => $transactions,
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
        //$data['code'] = $broker->code . '-' . ($data['code'] ?? str_pad(User::where('sales_partner_id', $broker->id)->count() + 1, 4, '0', STR_PAD_LEFT));
        $data['code'] = $data['code'] ?? null;
        $broker->user()->create($data);

        return redirect()->route('broker.show', $broker);
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

    public function updateCreditUsed(Request $request, string $id)
    {
        $broker = SalesPartner::with('agentAccount')->findOrFail($id);

        if (!$broker->agentAccount) {
            return redirect()->route('broker.show', $broker)->with('error', 'ไม่พบบัญชี Agent ของ Broker นี้');
        }

        $request->validate([
            'amount' => 'required|numeric|min:0.01',
        ], [
            'amount.required' => 'กรุณาระบุจำนวนเงิน',
        ]);

        $amount = (float) $request->amount;
        if ($amount > $broker->agentAccount->credit_balance) {
            return redirect()->route('broker.show', $broker)->with('error', 'จำนวนเงินที่จะชำระไม่สามารถมากกว่าวงเงินเครดิตคงเหลือ');
        }

        DB::transaction(function () use ($broker, $amount) {
            $account = $broker->agentAccount;
            $oldBalance = (float) ($account->credit_balance ?? 0);
            $account->update(['credit_balance' => $oldBalance - $amount]);

            AgentAccountTransection::create([
                'agent_account_id' => $account->id,
                'type' => 'payment',
                'amount' => $amount,
                'description' => sprintf(
                    'Clear Credit: ชำระ %s THB (คงเหลือ %s → %s)',
                    number_format($amount, 2),
                    number_format($oldBalance, 2),
                    number_format($oldBalance - $amount, 2)
                ),
                'isapproved' => 'Y',
            ]);
        });

        return redirect()->route('broker.show', $broker)->with('success', 'Clear Credit เรียบร้อย');
    }
}
