@extends('layouts.app')

@section('title', 'Kelola Pengguna')
@section('header', 'Kelola Pengguna')
@section('header-actions')
<a href="{{ route('user-management.create') }}" class="btn btn-primary">
    <i class="bi bi-plus-lg me-1"></i> Tambah Pengguna
</a>
@endsection

@section('content')
<div class="table-container">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Telepon</th>
                    <th>Status</th>
                    <th>Bergabung</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="bg-{{ $user->role === 'owner' ? 'danger' : ($user->role === 'admin' ? 'primary' : 'info') }} text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; font-size: 0.8rem; font-weight: 600;">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                            <div>
                                <div class="fw-semibold">{{ $user->name }}</div>
                                <small class="text-muted">{{ $user->email }}</small>
                            </div>
                        </div>
                    </td>
                    <td>{{ $user->email }}</td>
                    <td>
                        @if($user->role === 'owner')
                            <span class="badge bg-danger">Owner</span>
                        @elseif($user->role === 'admin')
                            <span class="badge bg-primary">Admin</span>
                        @else
                            <span class="badge bg-info text-white">Finance</span>
                        @endif
                    </td>
                    <td>{{ $user->phone ?? '-' }}</td>
                    <td>
                        @if($user->is_active)
                            <span class="badge bg-success">Aktif</span>
                        @else
                            <span class="badge bg-secondary">Nonaktif</span>
                        @endif
                    </td>
                    <td>{{ $user->created_at->format('d/m/Y') }}</td>
                    <td class="text-end">
                        <a href="{{ route('user-management.edit', $user) }}" class="btn btn-sm btn-outline-primary" title="Ubah">
                            <i class="bi bi-pencil"></i>
                        </a>
                        @if(!$user->isOwner())
                        <button onclick="confirmAction('Hapus {{ $user->name }}?', () => document.getElementById('delete-{{ $user->id }}').submit())" class="btn btn-sm btn-outline-danger" title="Hapus">
                            <i class="bi bi-trash"></i>
                        </button>
                        <form id="delete-{{ $user->id }}" action="{{ route('user-management.destroy', $user) }}" method="POST" class="d-none">
                            @csrf @method('DELETE')
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            <i class="bi bi-shield-lock-fill"></i>
                            <h5>Tidak Ada Pengguna</h5>
                            <p class="text-muted">Tidak ada pengguna dalam sistem.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-end">
        {{ $users->links() }}
    </div>
</div>
@endsection

