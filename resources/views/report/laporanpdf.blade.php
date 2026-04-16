<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Transaksi Penjualan - SMK Informatika Utama</title>
    <style>
        body {
            font-family: "Inter", "Segoe UI", Tahoma, sans-serif;
            font-size: 12px;
            color: #2e2e2e;
            margin: 30px;
        }

        /* HEADER */
        .header {
            text-align: center;
            margin-bottom: 35px;
            padding-bottom: 15px;
            border-bottom: 1.8px solid #1f3f70;
        }

        .header h1 {
            font-size: 22px;
            margin: 0 0 6px 0;
            color: #1f3f70;
            font-weight: 700;
        }

        .header p {
            margin: 2px 0;
            color: #555;
            font-size: 12px;
        }

        /* INFO SECTION */
        .report-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 25px;
            background: #f4f6f8;
            padding: 12px 16px;
            border-left: 4px solid #1f3f70;
            border-radius: 4px;
        }

        .info-label {
            font-weight: 600;
            color: #1f3f70;
            font-size: 13px;
        }

        .info-value {
            font-size: 13px;
        }

        /* TABLE */
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        th {
            background-color: #1f3f70;
            color: #fff;
            padding: 9px 6px;
            font-weight: 600;
            border: 1px solid #173256;
        }

        td {
            border: 1px solid #ddd;
            padding: 7px 6px;
        }

        tbody tr:nth-child(even) {
            background-color: #fafafa;
        }

        tbody tr:hover {
            background-color: #eef3fa;
        }

        tfoot td {
            background-color: #1f3f70;
            color: #fff;
            padding: 10px 6px;
            font-weight: 600;
        }

        .currency {
            text-align: right;
            font-family: "Courier New", monospace;
        }

        .product-list {
            padding-left: 12px;
            margin: 0;
            list-style-type: none;
        }

        .product-item {
            margin-bottom: 4px;
            padding-bottom: 4px;
            border-bottom: 1px dashed #e0e0e0;
        }

        .product-name {
            font-weight: 600;
            color: #1f3f70;
        }

        .product-details {
            font-size: 11px;
            color: #555;
        }

        .discount-amount {
            color: #c62828;
            font-weight: 600;
        }

        /* SUMMARY */
        .summary-section {
            margin-top: 25px;
            padding: 15px;
            background-color: #f4f6f8;
            border-left: 4px solid #2e7d32;
            border-radius: 4px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 6px;
        }

        .summary-value {
            font-family: "Courier New";
            font-weight: 700;
            color: #1f3f70;
        }

        /* FOOTER */
        .footer {
            margin-top: 30px;
            text-align: center;
            color: #777;
            font-size: 11px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
        }

        .no-data {
            padding: 25px;
            background-color: #f4f6f8;
            text-align: center;
            font-size: 13px;
            color: #999;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>LAPORAN TRANSAKSI PENJUALAN</h1>
        <p>SMK INFORMATIKA UTAMA</p>
        <p>Jl. JCC Komplek PT PLN P3B Jawa Bali No.61 Krukut — Telp: (021) 753-0843</p>
    </div>

    <div class="report-info">
        <div>
            <div class="info-label">Periode Laporan</div>
            <div class="info-value">{{ $tanggalBulanTahunawal }} s/d {{ $tanggalBulanTahunakhir }}</div>
        </div>
        <div>
            <div class="info-label">Tanggal Cetak</div>
            <div class="info-value">{{ date('d-m-Y H:i') }}</div>
        </div>
        <div>
            <div class="info-label">Total Transaksi</div>
            <div class="info-value">{{ $sales->count() }} Transaksi</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>ID Transaksi</th>
                <th>Cabang</th>
                <th>Tanggal</th>
                <th>Pelanggan</th>
                <th>Metode</th>
                <th>Total Pembelian</th>
                <th>Diskon</th>
                <th>Total Bayar</th>
                <th>Kembalian</th>
                <th>Kasir</th>
                <th>Detail Produk</th>
            </tr>
        </thead>

        <tbody>
            @php 
                $grandTotal = 0;
                $totalDiscount = 0;
                $totalPayment = 0;
            @endphp

            @forelse ($sales as $trans)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td><strong>{{ $trans->sales_id }}</strong></td>
                <td>{{ $trans->branch->nama_cabang ?? '-' }}</td>
                <td>{{ $trans->created_at?->format('d-m-Y') }}</td>
                <td>{{ $trans->customer->customer_name ?? 'Umum' }}</td>
                <td>{{ $trans->payment->payment_method ?? '-' }}</td>
                <td class="currency">Rp {{ number_format($trans->total_amount ?? 0,0,',','.') }}</td>
                <td class="currency discount-amount">Rp {{ number_format($trans->discount ?? 0,0,',','.') }}</td>
                <td class="currency">Rp {{ number_format($trans->payment->amount ?? 0,0,',','.') }}</td>
                <td class="currency">Rp {{ number_format($trans->payment->change ?? 0,0,',','.') }}</td>
                <td>{{ $trans->user->user_name ?? '-' }}</td>
                <td>
                    @if($trans->items->count())
                    <ul class="product-list">
                        @foreach ($trans->items as $item)
                            <li class="product-item">
                                <div class="product-name">
                                    {{ $item->variant->product->product_name ?? 'Produk tidak ditemukan' }}
                                </div>
                                <div class="product-details">
                                    Qty: {{ $item->quantity }} × Rp {{ number_format($item->variant->product_sale ?? 0,0,',','.') }}  
                                    = Rp {{ number_format($item->total ?? 0,0,',','.') }}
                                </div>
                            </li>
                        @endforeach
                    </ul>
                    @else
                        <span style="color:#aaa; font-size:11px;">Tidak ada produk</span>
                    @endif
                </td>
            </tr>

            @php
                $grandTotal += $trans->total_amount ?? 0;
                $totalDiscount += $trans->discount ?? 0;
                $totalPayment += $trans->payment->amount ?? 0;
            @endphp

            @empty
            <tr><td colspan="12" class="no-data">Tidak ada data transaksi untuk periode ini.</td></tr>
            @endforelse
        </tbody>

        <tfoot>
            <tr>
                <td colspan="6" style="text-align:center;">TOTAL KESELURUHAN</td>
                <td class="currency">Rp {{ number_format($grandTotal,0,',','.') }}</td>
                <td class="currency">Rp {{ number_format($totalDiscount,0,',','.') }}</td>
                <td class="currency">Rp {{ number_format($totalPayment,0,',','.') }}</td>
                <td colspan="3"></td>
            </tr>
        </tfoot>
    </table>

    @if($sales->count())
    <div class="summary-section">
        <div class="summary-row">
            <span>Total Nilai Transaksi:</span>
            <span class="summary-value">Rp {{ number_format($grandTotal,0,',','.') }}</span>
        </div>
        <div class="summary-row">
            <span>Total Diskon:</span>
            <span class="summary-value">Rp {{ number_format($totalDiscount,0,',','.') }}</span>
        </div>
        <div class="summary-row">
            <span>Total Penerimaan Kas:</span>
            <span class="summary-value">Rp {{ number_format($totalPayment,0,',','.') }}</span>
        </div>
        <div class="summary-row">
            <span>Jumlah Transaksi:</span>
            <span class="summary-value">{{ $sales->count() }} Transaksi</span>
        </div>
    </div>
    @endif

    <div class="footer">
        Laporan ini dihasilkan otomatis oleh Sistem Informasi SMK Informatika Utama · {{ date('d-m-Y H:i') }}
    </div>

</body>
</html>
