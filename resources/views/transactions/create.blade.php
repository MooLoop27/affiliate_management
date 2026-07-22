@extends('layouts.app')

@section('title', 'Buat Transaksi')
@section('header', 'Buat Transaksi')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-10">
        <div class="table-container">
            <form action="{{ route('transactions.store') }}" method="POST" id="transactionForm">
                @csrf

                <div class="row g-3 mb-4">
                    <h5 class="fw-bold border-bottom pb-2">Informasi Transaksi</h5>
                    <div class="col-md-4">
                        <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                        <input type="date" name="date" class="form-control @error('date') is-invalid @enderror" value="{{ old('date', now()->format('Y-m-d')) }}" required>
                        @error('date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Partner Singapore <span class="text-danger">*</span></label>
                        <select name="singapore_partner_id" class="form-select @error('singapore_partner_id') is-invalid @enderror" required>
                            <option value="">Pilih Partner</option>
                            @foreach($singaporePartners as $partner)
                            <option value="{{ $partner->id }}" {{ old('singapore_partner_id') == $partner->id ? 'selected' : '' }}>{{ $partner->sg_code }} - {{ $partner->partner_name }}</option>
                            @endforeach
                        </select>
                        @error('singapore_partner_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Leader <span class="text-danger">*</span></label>
                        <select name="leader_id" class="form-select @error('leader_id') is-invalid @enderror" required>
                            <option value="">Pilih Leader</option>
                            @foreach($leaders as $leader)
                            <option value="{{ $leader->id }}" {{ old('leader_id') == $leader->id ? 'selected' : '' }}>{{ $leader->leader_code }} - {{ $leader->leader_name }}</option>
                            @endforeach
                        </select>
                        @error('leader_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Jumlah Saldo Perusahaan <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" name="company_balance_amount" id="company_balance_amount" class="form-control @error('company_balance_amount') is-invalid @enderror" value="{{ old('company_balance_amount') }}" step="0.01" required oninput="calculateCommissions()">
                        </div>
                        @error('company_balance_amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Komisi SG (%)</label>
                        <div class="input-group">
                            <input type="number" name="sg_commission_percentage" id="sg_commission_percentage" class="form-control" value="{{ old('sg_commission_percentage', $sgPercentage) }}" step="0.01" oninput="calculateCommissions()">
                            <span class="input-group-text">%</span>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Komisi Leader (%)</label>
                        <div class="input-group">
                            <input type="number" name="leader_commission_percentage" id="leader_commission_percentage" class="form-control" value="{{ old('leader_commission_percentage', $leaderPercentage) }}" step="0.01" oninput="calculateCommissions()">
                            <span class="input-group-text">%</span>
                        </div>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Catatan</label>
                        <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="2">{{ old('notes') }}</textarea>
                        @error('notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <!-- Commission Preview -->
                <div class="row g-3 mb-4">
                    <h5 class="fw-bold border-bottom pb-2">Pratinjau Komisi</h5>
                    <div class="col-md-3">
                        <div class="p-3 bg-light rounded">
                            <div class="small text-muted">Komisi SG</div>
                            <div class="fw-bold" id="sg_commission_display">Rp 0</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p-3 bg-light rounded">
                            <div class="small text-muted">Komisi Leader</div>
                            <div class="fw-bold" id="leader_commission_display">Rp 0</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p-3 bg-light rounded">
                            <div class="small text-muted">Total Komisi</div>
                            <div class="fw-bold" id="total_commission_display">Rp 0</div>
                        </div>
                    </div>
                </div>

                <!-- Commission Recipients -->
                <div class="row g-3 mb-4">
                    <h5 class="fw-bold border-bottom pb-2">
                        Penerima Komisi
                        <button type="button" class="btn btn-sm btn-outline-primary ms-2" onclick="addRecipient()">
                            <i class="bi bi-plus-lg"></i> Tambah Penerima
                        </button>
                    </h5>

                    <div id="recipients-container">
                        @if(old('recipients'))
                            @foreach(old('recipients') as $index => $recipient)
                            <div class="recipient-row row g-2 mb-2 p-3 border rounded">
                                <div class="col-md-5">
                                    <label class="form-label small">Penerima <span class="text-danger">*</span></label>
                                    <select name="recipients[{{ $index }}][recipient_id]" class="form-select form-select-sm" required>
                                        <option value="">Pilih Penerima</option>
                                        @foreach($recipients as $rcp)
                                        <option value="{{ $rcp->id }}" {{ $recipient['recipient_id'] == $rcp->id ? 'selected' : '' }}>{{ $rcp->recipient_code }} - {{ $rcp->recipient_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small">Komisi (%) <span class="text-danger">*</span></label>
                                    <div class="input-group input-group-sm">
                                        <input type="number" name="recipients[{{ $index }}][commission_percentage]" class="form-control commission-percentage" value="{{ $recipient['commission_percentage'] }}" step="0.01" required oninput="calculateCommissions()">
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small">Jumlah</label>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">Rp</span>
                                        <input type="text" class="form-control commission-amount" value="0" readonly>
                                    </div>
                                </div>
                                <div class="col-md-1 d-flex align-items-end">
                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="this.closest('.recipient-row').remove(); calculateCommissions();">
                                        <i class="bi bi-x-lg"></i>
                                    </button>
                                </div>
                            </div>
                            @endforeach
                        @else
                        <div class="recipient-row row g-2 mb-2 p-3 border rounded">
                            <div class="col-md-5">
                                <label class="form-label small">Penerima <span class="text-danger">*</span></label>
                                <select name="recipients[0][recipient_id]" class="form-select form-select-sm" required>
                                    <option value="">Pilih Penerima</option>
                                    @foreach($recipients as $rcp)
                                    <option value="{{ $rcp->id }}">{{ $rcp->recipient_code }} - {{ $rcp->recipient_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small">Komisi (%) <span class="text-danger">*</span></label>
                                <div class="input-group input-group-sm">
                                    <input type="number" name="recipients[0][commission_percentage]" class="form-control commission-percentage" value="{{ $defaultRecipientPercentage }}" step="0.01" required oninput="calculateCommissions()">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small">Jumlah</label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text">Rp</span>
                                    <input type="text" class="form-control commission-amount" value="0" readonly>
                                </div>
                            </div>
                            <div class="col-md-1 d-flex align-items-end">
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="this.closest('.recipient-row').remove(); calculateCommissions();">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                            </div>
                        </div>
                        @endif
                    </div>

                    <div id="recipients-error" class="text-danger small d-none">Tambahkan setidaknya satu penerima.</div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('transactions.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-1"></i> Buat Transaksi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let recipientIndex = {{ old('recipients') ? count(old('recipients')) : 1 }};

function calculateCommissions() {
    const balance = parseFloat(document.getElementById('company_balance_amount').value) || 0;
    const sgPercent = parseFloat(document.getElementById('sg_commission_percentage').value) || 0;
    const leaderPercent = parseFloat(document.getElementById('leader_commission_percentage').value) || 0;

    const sgAmount = balance * sgPercent / 100;
    const leaderAmount = balance * leaderPercent / 100;
    const totalCommission = sgAmount + leaderAmount;

    document.getElementById('sg_commission_display').textContent = 'Rp ' + formatNumber(sgAmount);
    document.getElementById('leader_commission_display').textContent = 'Rp ' + formatNumber(leaderAmount);
    document.getElementById('total_commission_display').textContent = 'Rp ' + formatNumber(totalCommission);

    // Update recipient amounts
    document.querySelectorAll('.recipient-row').forEach(row => {
        const percent = parseFloat(row.querySelector('.commission-percentage').value) || 0;
        const amount = balance * percent / 100;
        row.querySelector('.commission-amount').value = 'Rp ' + formatNumber(amount);
    });
}

function addRecipient() {
    const container = document.getElementById('recipients-container');
    const template = container.querySelector('.recipient-row').cloneNode(true);

    // Update names
    template.querySelectorAll('[name]').forEach(el => {
        const name = el.getAttribute('name');
        if (name) {
            el.setAttribute('name', name.replace(/\[\d+\]/, `[${recipientIndex}]`));
        }
        if (el.tagName === 'SELECT') {
            el.selectedIndex = 0;
        } else if (el.tagName === 'INPUT' && el.type === 'number') {
            el.value = '{{ $defaultRecipientPercentage }}';
        }
    });

    template.querySelector('.commission-amount').value = 'Rp 0';
    container.appendChild(template);
    recipientIndex++;
    calculateCommissions();
}

function formatNumber(num) {
    return num.toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 0 });
}

// Initial calculation
calculateCommissions();
</script>
@endpush

