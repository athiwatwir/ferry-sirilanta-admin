<?php

namespace App\Http\Controllers;

use App\Models\SettingFee;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SettingFeeController extends Controller
{
    public function index()
    {
        $fees = SettingFee::query()->orderBy('name')->get();

        return view('pages.setting-fee.index', [
            'title' => 'Fee Settings',
            'breadcrumbs' => [
                'Setting' => '',
                'Fee' => '',
            ],
            'fees' => $fees,
            'feeTypes' => SettingFee::feeTypes(),
        ]);
    }

    public function create()
    {
        return view('pages.setting-fee.create', [
            'title' => 'Create Fee Setting',
            'breadcrumbs' => [
                'Fee Settings' => route('settingFee.index'),
                'Create' => '',
            ],
            'feeTypes' => SettingFee::feeTypes(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateFee($request);

        SettingFee::create($validated);

        return redirect()
            ->route('settingFee.index')
            ->with('success', 'Fee setting created successfully.');
    }

    public function show(SettingFee $settingFee)
    {
        return redirect()->route('settingFee.edit', $settingFee);
    }

    public function edit(SettingFee $settingFee)
    {
        return view('pages.setting-fee.edit', [
            'title' => 'Edit Fee Setting',
            'breadcrumbs' => [
                'Fee Settings' => route('settingFee.index'),
                $settingFee->name => '',
            ],
            'fee' => $settingFee,
            'feeTypes' => SettingFee::feeTypes(),
        ]);
    }

    public function update(Request $request, SettingFee $settingFee)
    {
        $validated = $this->validateFee($request, $settingFee->id);

        $settingFee->update($validated);

        return redirect()
            ->route('settingFee.index')
            ->with('success', 'Fee setting updated successfully.');
    }

    public function destroy(SettingFee $settingFee)
    {
        $settingFee->delete();

        return redirect()
            ->route('settingFee.index')
            ->with('success', 'Fee setting deleted successfully.');
    }

    private function validateFee(Request $request, ?int $ignoreId = null): array
    {
        $feeTypeKeys = array_keys(SettingFee::feeTypes());

        return $request->validate([
            'name' => 'required|string|max:255',
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('setting_fees', 'code')->ignore($ignoreId),
            ],
            'credit_card_fee_type' => ['required', 'string', Rule::in($feeTypeKeys)],
            'credit_card_fee' => 'required|numeric|min:0',
            'thai_qr_fee_type' => ['required', 'string', Rule::in($feeTypeKeys)],
            'thai_qr_fee' => 'required|numeric|min:0',
        ]);
    }
}
