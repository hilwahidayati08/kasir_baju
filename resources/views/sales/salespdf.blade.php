        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Struk Belanja</title>
            <style>
                body {
                    font-family: 'Courier New', Courier, monospace;
                    font-size: 12px;
                    max-width: 280px;
                    margin: auto;
                    padding: 10px;
                }

                .title {
                    text-align: center;
                    font-size: 14px;
                    font-weight: bold;
                    margin-bottom: 10px;
                }

                .store-info,
                .info {
                    text-align: left;
                    text-align: center;
                    margin-bottom: 8px;
                }

                .store-info {
                    font-size: 10px;
                    border-bottom: 1px dashed #000;
                    padding-bottom: 5px;
                }

                .info div {
                    margin: 3px 0;
                }

                .info .total,
                .info .footer {
                    font-size: 12px;
                }

                .info .right-align {
                    text-align: right;
                }

                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-bottom: 12px;
                }

                th,
                td {
                    font-size: 12px;
                    padding: 5px 0;
                    text-align: left;
                }

                td.right-align {
                    text-align: right;
                }

                tfoot tr td {
                    border-top: 1px dashed #000;
                    padding-top: 5px;
                }

                .footer {
                    text-align: center;
                    margin-top: 12px;
                    font-size: 10px;
                    border-top: 1px dashed #000;
                    padding-top: 8px;
                }

                .footer p {
                    margin: 0;
                }

                .separator {
                    border-top: 1px dashed #000;
                    margin: 10px 0;
                }
            </style>
        </head>
        <body>
            <div class="title">Struk Belanja</div>

<div class="store-info">
    <div><strong>{{ $sales->branch->nama_cabang ?? 'Cabang Tidak Ditemukan' }}</strong></div>
    <div>{{ $sales->branch->alamat ?? '-' }}</div>

    <div>
        {{ $sales->branch->province_id ?? '' }},
        {{ $sales->branch->city_id ?? '' }},
        {{ $sales->branch->district_id ?? '' }},
        {{ $sales->branch->village_id ?? '' }}
    </div>

    <div>Telp: {{ $sales->branch->kontak ?? '-' }}</div>
</div>


            <div class="info">
                <div><strong>ID Transaksi:</strong> {{ $sales->sales_id }}</div>
                <div><strong>Tanggal:</strong> {{ $sales->created_at->format('d-m-Y H:i') }}</div>
                <div><strong>Nama Pelanggan:</strong> {{ $sales->customer->customer_name ?? 'Umum' }}</div>
                <div><strong>Kasir:</strong> {{ $sales->user->user_name ?? '-' }}</div>
                <div><strong>Metode Pembayaran:</strong> {{ $sales->payment->payment_method ?? '-' }}</div>
            </div>

            <div class="separator"></div>

            <table>
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Qty</th>
                        <th>Price</th>
                        <th class="right-align">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @php $total = 0; @endphp
                    @foreach ($sales->items as $detail)
                        <tr>
                            <td>{{ $detail->variant->product->product_name ?? 'Produk tidak ditemukan' }} {{$detail->variant->warna}} {{$detail->variant->ukuran}}</td>
                            <td>{{ $detail->quantity }}</td>
                            <td>Rp {{ number_format($detail->variant->product_sale ?? 0, 0, ',', '.') }}</td>
                            <td class="right-align">Rp {{ number_format($detail->total ?? 0, 0, ',', '.') }}</td>
                        </tr>
                        @php $total += $detail->total ?? 0; @endphp
                    @endforeach
                </tbody>
            </table>

            <div class="separator"></div>

            <div class="info total">
                <div class="right-align">
                    <div><strong>Total</strong> : Rp {{ number_format($total, 0, ',', '.') }}</div>
                    <div>Diskon : Rp {{ number_format($sales->discount ?? 0, 0, ',', '.') }}</div>
                    <div><strong>Total Pembayaran</strong> : Rp {{ number_format($total - ($sales->discount ?? 0), 0, ',', '.') }}</div>
                    <div>Dibayar : Rp {{ number_format($sales->payment->amount ?? 0, 0, ',', '.') }}</div>
                    <div><strong>Kembalian</strong> : Rp {{ number_format($sales->payment->change ?? 0, 0, ',', '.') }}</div>
                </div>
            </div>

            <div class="separator"></div>

            <div class="footer">
                <p>Terima Kasih Telah Berbelanja!</p>
            </div>
        </body>
        </html>
