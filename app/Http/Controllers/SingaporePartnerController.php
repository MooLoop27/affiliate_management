<?php

namespace App\Http\Controllers;

use App\Models\SingaporePartner;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SingaporePartnerController extends Controller
{
    public function index(): View
    {
        $partners = SingaporePartner::withCount('transactions')
            ->latest()
            ->paginate(10);

        return view('master-data.singapore-partners.index', compact('partners'));
    }

    public function create(): View
    {
        return view('master-data.singapore-partners.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'partner_name' => 'required|string|max:255',
            'whatsapp' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'status' => 'required|in:active,inactive',
            'notes' => 'nullable|string',
        ]);

        $validated['sg_code'] = SingaporePartner::generateCode();

        SingaporePartner::create($validated);

        ActivityLog::log('Created Singapore Partner: ' . $validated['partner_name']);

        return redirect()->route('singapore-partners.index')
            ->with('success', 'Singapore Partner created successfully.');
    }

    public function show(SingaporePartner $singaporePartner): View
    {
        $singaporePartner->load('transactions.leader');
        return view('master-data.singapore-partners.show', compact('singaporePartner'));
    }

    public function edit(SingaporePartner $singaporePartner): View
    {
        return view('master-data.singapore-partners.edit', compact('singaporePartner'));
    }

    public function update(Request $request, SingaporePartner $singaporePartner): RedirectResponse
    {
        $validated = $request->validate([
            'partner_name' => 'required|string|max:255',
            'whatsapp' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'status' => 'required|in:active,inactive',
            'notes' => 'nullable|string',
        ]);

        $singaporePartner->update($validated);

        ActivityLog::log('Updated Singapore Partner: ' . $singaporePartner->partner_name);

        return redirect()->route('singapore-partners.index')
            ->with('success', 'Singapore Partner updated successfully.');
    }

    public function destroy(SingaporePartner $singaporePartner): RedirectResponse
    {
        $name = $singaporePartner->partner_name;
        $singaporePartner->delete();

        ActivityLog::log('Deleted Singapore Partner: ' . $name);

        return redirect()->route('singapore-partners.index')
            ->with('success', 'Singapore Partner deleted successfully.');
    }

    public function search(Request $request)
    {
        $query = $request->get('q');
        $partners = SingaporePartner::where('partner_name', 'like', "%{$query}%")
            ->orWhere('sg_code', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->active()
            ->get(['id', 'sg_code', 'partner_name']);

        return response()->json($partners);
    }
}

