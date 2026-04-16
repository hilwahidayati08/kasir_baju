@extends('admin.admin')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <h5 class="card-header">Laporan Pelatihan</h5>
        <div class="card-body">

            <div class="d-flex align-items-center gap-2 mb-3">

                <!-- FILTER (GET) -->
                <form action="{{ route('report.filter') }}" method="GET" class="d-flex align-items-center" id="formFilter">
                    <input type="text" id="date_range" name="date_range" class="form-control" placeholder="Select date range"
                        value="{{ old('date_range', $date_range ?? request('date_range') ?? ($startDate . ' - ' . $endDate)) }}">
                    
                    @if(Auth::user()->role === 'Admin')
                        <select name="branch_id" id="branchSelect" class="form-select ms-2" style="width:200px;">
                            <option value="all" {{ (isset($branchId) && $branchId=='all') || !isset($branchId) ? 'selected' : '' }}>Semua Cabang</option>
                            @foreach($branches as $b)
                                <option value="{{ $b->branch_id }}" {{ (isset($branchId) && $branchId == $b->branch_id) ? 'selected' : '' }}>
                                    {{ $b->nama_cabang }}
                                </option>
                            @endforeach
                        </select>
                    @endif

                    <button class="btn btn-secondary ms-2" type="submit">Filter</button>
                    <button type="button" class="btn btn-danger ms-2" id="resetButton">Reset</button>
                </form>

                <!-- CETAK (POST) -->
                <form action="{{ route('report.generate') }}" method="POST" class="d-inline ms-2" target="_blank" id="formPrint">
                    @csrf
                    <input type="hidden" name="start_date" id="start_date">
                    <input type="hidden" name="end_date" id="end_date">
                    <input type="hidden" name="branch_id" id="branch_id" value="{{ $branchId ?? 'all' }}">
                    <button type="submit" class="btn btn-dark">Cetak</button>
                </form>

            </div>

            <!-- TABLE -->
            <div class="table-responsive text-nowrap">
                <table id="example1" class="table">
                    <thead>
                        <tr class="text-nowrap">
                            <th>No</th>
                            <th>Transaksi ID</th>
                            <th>Pelanggan</th>
                            <th>Total</th>
                            <th>Diskon</th>
                            <th>Total Bayar</th>
                            <th>Kasir</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $grandTotal = 0; @endphp

                        @forelse ($sales as $v)
                            <tr data-branch="{{ $v->branch_id }}">
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $v->sales_id }}</td>
                                <td>{{ $v->customer->customer_name ?? 'Umum' }}</td>
                                <td>Rp. {{ number_format($v->total_amount) }}</td>
                                <td>Rp. {{ number_format($v->discount) }}</td>
                                <td>Rp. {{ number_format($v->payment->amount ?? 0) }}</td>
                                <td>{{ $v->user->user_name }}</td>
                                <td>{{ $v->created_at->format('d-m-Y') }}</td>
                            </tr>
                            @php $grandTotal += $v->total_amount; @endphp
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">Empty</td>
                            </tr>
                        @endforelse
                    </tbody>

                    <tfoot style="font-weight:bold;">
                        <tr>
                            <td colspan="3" class="text-center">Total Keseluruhan</td>
                            <td colspan="5" id="footerTotal">Rp {{ number_format($grandTotal, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker@3.1.0/daterangepicker.min.js"></script>

<script>
$(document).ready(function() {

    /* ----------------------------- */
    /*  FUNGSI UPDATE GRAND TOTAL    */
    /* ----------------------------- */
    function updateGrandTotal() {
        let total = 0;

        $("tbody tr:visible").each(function () {
            let text = $(this).find("td:eq(3)").text(); // kolom TOTAL
            let amount = text.replace(/[^0-9,-]/g, "").replace(",", ".");
            total += parseFloat(amount) || 0;
        });

        $("#footerTotal").text("Rp " + total.toLocaleString("id-ID"));
    }

    /* ----------------------------- */
    /*  DATE RANGE PICKER            */
    /* ----------------------------- */
    let start = moment("{{ $startDate }}");
    let end   = moment("{{ $endDate }}");

    $('#date_range').daterangepicker({
        locale: { format: 'YYYY-MM-DD' },
        startDate: start,
        endDate: end,
    }, function(s, e) {
        $('#start_date').val(s.format('YYYY-MM-DD'));
        $('#end_date').val(e.format('YYYY-MM-DD'));

        updateGrandTotal(); // update setelah pilih tanggal
    });

    $('#start_date').val(start.format('YYYY-MM-DD'));
    $('#end_date').val(end.format('YYYY-MM-DD'));

    /* ----------------------------- */
    /*  RESET BUTTON                 */
    /* ----------------------------- */
    $('#resetButton').on('click', function() {
        window.location.href = "{{ route('report.index') }}";
    });

    /* ----------------------------- */
    /*  FILTER CABANG                */
    /* ----------------------------- */
    $('#branchSelect').on('change', function() {
        let selected = $(this).val();
        $('#branch_id').val(selected);

        $("tbody tr").each(function () {
            let rowBranch = $(this).data('branch');
            if (selected === "all" || rowBranch == selected) $(this).show();
            else $(this).hide();
        });

        updateGrandTotal(); // WAJIB
    });

    /* ----------------------------- */
    /*  FORM PRINT                   */
    /* ----------------------------- */
    $('#formPrint').on('submit', function() {
        let range = $('#date_range').val().split(" - ");
        $('#start_date').val(range[0]);
        $('#end_date').val(range[1]);

        @if(Auth::user()->role === 'Admin')
            $('#branch_id').val($('#branchSelect').val() || 'all');
        @else
            $('#branch_id').val("{{ Auth::user()->branch_id }}");
        @endif
    });

});
</script>
@endpush
