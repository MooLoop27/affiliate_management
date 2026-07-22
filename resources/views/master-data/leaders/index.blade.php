@extends('layouts.app')

@section('title', 'Leaders')
@section('header', 'Leaders')
@section('header-actions')
<a href="{{ route('leaders.create') }}" class="btn btn-primary">
    <i class="bi bi-plus-lg me-1"></i> Add Leader
</a>
@endsection

@section('content')
<div class="table-container">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Leader Name</th>
                    <th>WhatsApp</th>
                    <th>Status</th>
                    <th>Transactions</th>
                    <th>Recipients</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($leaders as $leader)
                <tr>
                    <td><span class="badge bg-info text-white">{{ $leader->leader_code }}</span></td>
                    <td class="fw-semibold">{{ $leader->leader_name }}</td>
                    <td>{{ $leader->whatsapp ?? '-' }}</td>
                    <td>
                        @if($leader->status === 'active')
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-secondary">Inactive</span>
                        @endif
                    </td>
                    <td>{{ number_format($leader->transactions_count) }}</td>
                    <td>{{ number_format($leader->recipients_count) }}</td>
                    <td class="text-end">
                        <a href="{{ route('leaders.show', $leader) }}" class="btn btn-sm btn-outline-info" title="View">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('leaders.edit', $leader) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <button onclick="confirmAction('Delete {{ $leader->leader_name }}?', () => document.getElementById('delete-{{ $leader->id }}').submit())" class="btn btn-sm btn-outline-danger" title="Delete">
                            <i class="bi bi-trash"></i>
                        </button>
                        <form id="delete-{{ $leader->id }}" action="{{ route('leaders.destroy', $leader) }}" method="POST" class="d-none">
                            @csrf @method('DELETE')
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            <i class="bi bi-people-fill"></i>
                            <h5>No Leaders</h5>
                            <p class="text-muted">Get started by adding your first leader.</p>
                            <a href="{{ route('leaders.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-lg me-1"></i> Add Leader
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-end">
        {{ $leaders->links() }}
    </div>
</div>
@endsection

