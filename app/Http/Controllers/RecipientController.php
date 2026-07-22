<?php

namespace App\Http\Controllers;

use App\Models\Recipient;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RecipientController extends Controller
{
    public function index(): View
    {
        $recipients = Recipient::withCount('commissionDetails')
            ->latest()
            ->paginate(10);

        return view('master-data.recipients.index', compact('recipients'));
    }

    public function create(): View
    {
        return view('master-data.recipients.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'recipient_name' => 'required|string|max:255',
            'whatsapp' => 'nullable|string|max:20',
            'bank_name' => 'nullable|string|max:255',
            'bank_account_number' => 'nullable|string|max:50',
            'status' => 'required|in:active,inactive',
            'notes' => 'nullable|string',
        ]);

        $validated['recipient_code'] = Recipient::generateCode();

        Recipient::create($validated);

        ActivityLog::log('Created Recipient: ' . $validated['recipient_name']);

        return redirect()->route('recipients.index')
            ->with('success', 'Recipient created successfully.');
    }

    public function show(Recipient $recipient): View
    {
        $recipient->load('commissionDetails.transaction');
        return view('master-data.recipients.show', compact('recipient'));
    }

    public function edit(Recipient $recipient): View
    {
        return view('master-data.recipients.edit', compact('recipient'));
    }

    public function update(Request $request, Recipient $recipient): RedirectResponse
    {
        $validated = $request->validate([
            'recipient_name' => 'required|string|max:255',
            'whatsapp' => 'nullable|string|max:20',
            'bank_name' => 'nullable|string|max:255',
            'bank_account_number' => 'nullable|string|max:50',
            'status' => 'required|in:active,inactive',
            'notes' => 'nullable|string',
        ]);

        $recipient->update($validated);

        ActivityLog::log('Updated Recipient: ' . $recipient->recipient_name);

        return redirect()->route('recipients.index')
            ->with('success', 'Recipient updated successfully.');
    }

    public function destroy(Recipient $recipient): RedirectResponse
    {
        $name = $recipient->recipient_name;
        $recipient->delete();

        ActivityLog::log('Deleted Recipient: ' . $name);

        return redirect()->route('recipients.index')
            ->with('success', 'Recipient deleted successfully.');
    }

    public function search(Request $request)
    {
        $query = $request->get('q');
        $recipients = Recipient::where('recipient_name', 'like', "%{$query}%")
            ->orWhere('recipient_code', 'like', "%{$query}%")
            ->active()
            ->get(['id', 'recipient_code', 'recipient_name', 'bank_name', 'bank_account_number']);

        return response()->json($recipients);
    }
}

