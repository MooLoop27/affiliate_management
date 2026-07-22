@extends('layouts.app')

@section('title', 'Partner Singapore')
@section('header', 'Partner Singapore')
@section('header-actions')
<a href="{{ route('singapore-partners.create') }}" class="btn btn-primary">
    <i class="bi bi-plus-lg me-1"></i> Tambah Partner
</a>
@endsection

@section('content')
<div class="table-container">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>Kode SG</th>
                    <th>Nama Partner</th>
                    <th>WhatsApp</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Transaksi</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($partners as $partner)
                <tr>
                    <td><span class="badge bg-primary">{{ $partner->sg_code }}</span></td>
                    <td class="fw-semibold">{{ $partner->partner_name }}</td>
                    <td>{{ $partner->whatsapp ?? '-' }}</td>
                    <td>{{ $partner->email ?? '-' }}</td>
                    <td>
                        @if($partner->status === 'active')
                            <span class="badge bg-success">Aktif</span>
                        @else
                            <span class="badge bg-secondary">Nonaktif</span>
                        @endif
                    </td>
                    <td>{{ number_format($partner->transactions_count) }}</td>
                    <td class="text-end">
                        <a href="{{ route('singapore-partners.show', $partner) }}" class="btn btn-sm btn-outline-info" title="Detail">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('singapore-partners.edit', $partner) }}" class="btn btn-sm btn-outline-primary" title="Ubah">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <button onclick="confirmAction('Hapus {{ $partner->partner_name }}?', () => document.getElementById('delete-{{ $partner->id }}').submit())" class="btn btn-sm btn-outline-danger" title="Hapus">
                            <i class="bi bi-trash"></i>
                        </button>
                        <form id="delete-{{ $partner->id }}" action="{{ route('singapore-partners.destroy', $partner) }}" method="POST" class="d-none">
                            @csrf @method('DELETE')
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            <i class="bi bi-building"></i>
                            <h5>Belum Ada Partner Singapore</h5>
                            <p class="text-muted">Mulai dengan menambahkan partner pertama Anda.</p>
                            <a href="{{ route('singapore-partners.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-lg me-1"></i> Tambah Partner
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-end">
        {{ $partners->links() }}
    </div>
</div>
@endsection

