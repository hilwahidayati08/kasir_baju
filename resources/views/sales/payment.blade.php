<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Payment Page</title>
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('midtrans.clientKey') }}"></script>
</head>

<body>
    <h2>Pembayaran</h2>
    <p><strong>Invoice:</strong> {{ $sales->sales_id ?? session('salesId') }}</p>
    <p><strong>Total:</strong> Rp {{ number_format($sales->total_amount ?? 0, 0, ',', '.') }}</p>

    <button id="pay-button">Bayar Sekarang</button>

    <script>
        const payButton = document.getElementById('pay-button');
        const snapToken = "{{ session('snapToken') }}";
        const orderId = "{{ session('orderId') }}";
        const salesId = "{{ session('salesId') }}";
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        payButton.addEventListener('click', function() {
            window.snap.pay(snapToken, {
                onSuccess: function(result) {
                    alert("Pembayaran berhasil!");

                    // Kirim ke server untuk update status pembayaran
                    fetch("{{ route('sales.payment.callback') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": csrfToken
                        },
                        body: JSON.stringify({
                            sales_id: salesId,
                            order_id: result.order_id,
                            transaction_status: result.transaction_status,
                            payment_type: result.payment_type,
                            gross_amount: result.gross_amount
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.log("Server response:", data);
                        window.location.href = "{{ route('sales.index') }}";
                    })
                    .catch(err => console.error("Gagal update status:", err));
                },
                onPending: function(result) {
                    alert("Menunggu pembayaran...");
                    console.log(result);
                },
                onError: function(result) {
                    alert("Terjadi kesalahan pembayaran!");
                    console.error(result);
                },
                onClose: function() {
                    alert("Anda menutup jendela pembayaran tanpa menyelesaikan transaksi.");
                }
            });
        });
    </script>
</body>

</html>
