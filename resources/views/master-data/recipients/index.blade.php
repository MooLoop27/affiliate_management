@extends('layouts.app')

@section('title', 'Commission Recipients')
@section('header', 'Commission Recipients')
@section('header-actions')
<a href="{{ route('recipients.create') }}" class="btn btn-primary">
    <i class="bi bi-plus-lg me-1"></i> Add Recipient
</a>
@endsection

@section('content')
<div class="table-container">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Recipient Name</th>
                    <th>WhatsApp</th>
                    <th>Bank</th>
                    <th>Account</th>
                    <th>Status</th>
                    <th>Commissions</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recipients as $recipient)
                <tr>
                    <td><span class="badge bg-success">{{ $recipient->recipient_code }}</span></td>
                    <td class="fw-semibold">{{ $recipient->recipient_name }}</td>
                    <td>{{ $recipient->whatsapp ?? '-' }}</td>
                    <td>{{ $recipient->bank_name ?? '-' }}</td>
                    <td>{{ $recipient->bank_account_number ?? '-' }}</td>
                    <td>
                        @if($recipient->status === 'active')
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-secondary">Inactive</span>
                        @endif
                    </td>
                    <td>{{ number_format($recipient->commission_details_count) }}</td>
                    <td class="text-end">
                        <a href="{{ route('recipients.show', $recipient) }}" class="btn btn-sm btn-outline-info" title="View">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('recipients.edit', $recipient) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <button onclick="confirmAction('Delete {{ $recipient->recipient_name }}?', () => document.getElementById('delete-{{ $recipient->id }}').submit())" class="btn btn-sm btn-outline-danger" title="Delete">
                            <i class="bi bi-trash"></i>
                        </button>
                        <form id="delete-{{ $recipient->id }}" action="{{ route('recipients.destroy', $recipient) }}" method="POST" class="d-none">
                            @csrf @method('DELETE')
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8">
                        <div class="empty-state">
                            <i class="bi bi-person-badge"></i>
                            <h5>No Commission Recipients</h5>
                            <p class="text-muted">Get started by adding your first recipient.</p>
                            <a href="{{ route('recipients.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-lg me-1"></i> Add Recipient
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-end">
        {{ $recipients->links() }}
    </div>
</div>
@endsection

