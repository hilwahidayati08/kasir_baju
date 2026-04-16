<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Barcode - {{ $variant->product->product_name }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <style>
        body {
            font-family: "Poppins", sans-serif;
            background-color: #f5f7fa;
            padding: 20px;
        }
        .print-controls {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
            margin-bottom: 20px;
        }
        .counter-box {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .counter-btn {
            width: 35px;
            height: 35px;
            font-size: 18px;
        }
        .counter-input {
            width: 60px;
            text-align: center;
            font-size: 18px;
            font-weight: 600;
        }

        .barcode-container {
            background: #fff;
            border-radius: 10px;
            padding: 10px;
            text-align: center;
        }

        .barcode-image {
            width: 100%;
            max-width: 180px;
        }

        /* GRID KETIKA PRINT BANYAK */
        #multipleBarcodes {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 18px;
        }

        @media print {
            .no-print { display: none !important; }
            body { background: #fff !important; }
            .barcode-container {
                page-break-inside: avoid;
                box-shadow: none;
                border: 1px solid #ccc;
            }
        }
    </style>
</head>
<body>

@php
use Picqer\Barcode\BarcodeGeneratorPNG;
$g = new BarcodeGeneratorPNG();
$barcode = base64_encode($g->getBarcode($variant->barcode, $g::TYPE_CODE_128));
@endphp

<div class="container-fluid">

    <!-- Kontrol -->
    <div class="print-controls no-print">
        <h5 class="text-primary fw-semibold mb-2">
            <i class="fa fa-barcode me-2"></i>Print Barcode Variant
        </h5>

        <div class="counter-box mb-3">
            <button class="btn btn-outline-secondary counter-btn" onclick="decrease()">−</button>
            <input id="count" class="form-control counter-input" type="number" value="1" min="1" max="999">
            <button class="btn btn-outline-secondary counter-btn" onclick="increase()">+</button>
        </div>

        <!-- Checkbox untuk menampilkan harga & detail -->
        <div class="form-check mb-1">
            <input class="form-check-input" type="checkbox" id="showPrice" checked>
            <label class="form-check-label" for="showPrice">Tampilkan Harga</label>
        </div>

        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" id="showDetail" checked>
            <label class="form-check-label" for="showDetail">Tampilkan Detail (warna & ukuran)</label>
        </div>

        <button onclick="startPrint()" class="btn btn-primary btn-sm">
            <i class="fa fa-print me-1"></i> Print
        </button>

        <button onclick="window.close()" class="btn btn-secondary btn-sm ms-3">
            <i class="fa fa-times me-1"></i> Tutup
        </button>

        <hr>
    </div>

    <!-- SATUAN -->
    <div id="singleBarcode">
        <div class="barcode-container mx-auto mb-3">

            <div class="fw-bold small">
                {{ Str::limit($variant->product->product_name, 28) }}
                - {{ $variant->warna }} {{ $variant->ukuran }}
            </div>

            <img src="data:image/png;base64,{{ $barcode }}" class="barcode-image">

            <div class="barcode-number">{{ $variant->barcode }}</div>

            <div class="price fw-semibold text-danger">
                Rp {{ number_format($variant->product_sale, 0, ',', '.') }}
            </div>

            <div class="info small">
                <div>Warna: {{ $variant->warna }}</div>
                <div>Ukuran: {{ $variant->ukuran }}</div>
            </div>
        </div>
    </div>

    <!-- BANYAK -->
    <div id="multipleBarcodes" style="display:none;"></div>
</div>

<script>
function increase() {
    const input = document.getElementById("count");
    if (parseInt(input.value) < 999) input.value++;
}

function decrease() {
    const input = document.getElementById("count");
    if (parseInt(input.value) > 1) input.value--;
}

function startPrint() {
    const n = parseInt(document.getElementById("count").value);

    const multi = document.getElementById("multipleBarcodes");
    const single = document.getElementById("singleBarcode");

    const block = `
        <div class='barcode-container'>
            <div class='fw-bold small'>{{ Str::limit($variant->product->product_name, 28) }}</div>
            <img src="data:image/png;base64,{{ $barcode }}" class="barcode-image">
            <div class="barcode-number">{{ $variant->barcode }}</div>
            <div class="price fw-semibold text-danger">Rp {{ number_format($variant->product_sale) }}</div>
            <div class="info small">
                <div>Warna: {{ $variant->warna }}</div>
                <div>Ukuran: {{ $variant->ukuran }}</div>
            </div>
        </div>
    `;

    multi.innerHTML = block.repeat(n);

    // tampilkan sesuai opsi
    toggleElements();

    document.querySelector(".no-print").style.display = "none";
    single.style.display = "none";
    multi.style.display = "grid";

    window.print();

    setTimeout(() => {
        document.querySelector(".no-print").style.display = "block";
        multi.style.display = "none";
        single.style.display = "block";
    }, 300);
}

function toggleElements() {
    const showPrice = document.getElementById("showPrice").checked;
    const showDetail = document.getElementById("showDetail").checked;

    document.querySelectorAll(".price").forEach(p => p.style.display = showPrice ? "block" : "none");
    document.querySelectorAll(".info").forEach(i => i.style.display = showDetail ? "block" : "none");
}

// real-time toggle
document.getElementById("showPrice").addEventListener("change", toggleElements);
document.getElementById("showDetail").addEventListener("change", toggleElements);
</script>

</body>
</html>
