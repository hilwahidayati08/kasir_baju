@extends('admin.admin')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-lg border-0 rounded-4">
                    {{-- Pesan sukses --}}
                    @if (session('success'))
                        <div id="success-alert" class="alert alert-primary alert-dismissible fade show m-3">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Header -->
                    <div
                        class="card-header bg-primary text-white d-flex justify-content-between align-items-center rounded-top-4 py-3">
                        <h5 class="card-title mb-0 text-white fw-bold">Form Create Transactions</h5>
                        <div class="text-end small">
                            <span class="badge bg-light text-dark ms-1" id="current-date-time"></span>
                        </div>
                    </div>

                    <!-- Form -->
                    <form id="transaction-form" action="{{ route('sales.store') }}" method="POST">
                        @csrf
                        <!-- Input hidden untuk QRIS data -->
                        <input type="hidden" name="qris_data" id="qris_data">
                        <input type="hidden" name="qris_code" id="qris_code">
                        
                        <div class="card-body bg-light p-4">
                            <div class="row g-4">
                                <!-- Kiri -->
                                <div class="col-md-6 border-end pe-4">
                                    <h6 class="mb-3 fw-semibold text-primary border-bottom pb-2">Input Barang</h6>

                                    <!-- CUSTOMER -->
                                    <div class="form-group mb-4 position-relative">
                                        <label for="customer_search" class="form-label fw-semibold">Customer</label>
                                        <div class="input-group">
                                            <input type="text" id="customer_search"
                                                class="form-control border-primary rounded-3 shadow-sm"
                                                placeholder="Ketik nama customer..." autocomplete="off"
                                                value="{{ optional($customers->firstWhere('customer_id', old('customer_id')))->customer_name }}">
                                            <input type="hidden" name="customer_id" id="customer_id">
                                        </div>
                                        <div id="customer_list" class="autocomplete-suggestions"></div>
                                        <button type="button" id="add-customer"
                                            class="btn btn-outline-primary mt-2 shadow-sm w-100" data-bs-toggle="modal"
                                            data-bs-target="#modalAddCustomer">
                                            <i class="fas fa-plus me-2"></i>Tambah Customer
                                        </button>
                                    </div>

                                    <!-- METODE PEMBAYARAN -->
                                    <div class="form-group mb-4">
                                        <label for="payment_method" class="form-label fw-semibold">Metode Pembayaran</label>
                                        <select name="payment_method" id="payment_method"
                                            class="form-select border-primary shadow-sm rounded-3" required>
                                            <option value="">Pilih Metode Pembayaran</option>
                                            <option value="cash">Tunai</option>
                                            <option value="QRIS">QRIS</option>
                                        </select>
                                    </div>

                                    <!-- SCANNER SECTION YANG DIMODIFIKASI (TANPA KAMERA) -->
                                    <div class="scanner-section mb-4">

                                        <!-- Input Produk untuk Scanner USB dan Manual -->
                                        <div class="manual-input">
                                            <div class="form-group position-relative">
                                                <label for="product_search" class="form-label fw-semibold">Scan atau Cari Produk</label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-light border-end-0">
                                                        <i class="fas fa-barcode text-primary"></i>
                                                    </span>
                                                    <input type="text" id="product_search"
                                                        class="form-control border-primary rounded-3 shadow-sm"
                                                        placeholder="Scan barcode dengan USB scanner atau ketik nama produk..." 
                                                        autocomplete="off" autofocus>
                                                    <input type="hidden" name="variant_id" id="variant_id">
                                                    <button type="button" id="add-product"
                                                        class="btn btn-primary">
                                                        <i class="fas fa-cart-plus me-2"></i>Tambah
                                                    </button>
                                                </div>
                                                <div id="product_list" class="autocomplete-suggestions"></div>

                                            </div>
                                        </div>

                                        <!-- Scanner Status Messages -->
                                        <div id="scanner-messages" class="scanner-messages mt-2">
                                            <!-- Messages will appear here -->
                                        </div>
                                    </div>

                                    <!-- TABEL PRODUK -->
                                    <div class="table-responsive mt-3">
                                        <table
                                            class="table table-sm table-striped table-hover border rounded-3 overflow-hidden"
                                            id="product-table">
                                            <thead class="table-primary">
                                                <tr>
                                                    <th width="45%">Nama Produk</th>
                                                    <th width="15%">Harga</th>
                                                    <th width="20%">Jumlah</th>
                                                    <th width="15%">Total</th>
                                                    <th width="10%">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- Kanan -->
                                <div class="col-md-6 ps-4">
                                    <h6 class="mb-3 fw-semibold text-primary border-bottom pb-2">Rincian Pembayaran</h6>

                                    <!-- Informasi Customer -->
                                    <div class="card mb-4 shadow-sm border-0 bg-light">
                                        <div class="card-body py-3">
                                            <div class="d-flex justify-content-between align-items-center flex-wrap">
                                                <div>
                                                    <label class="form-label fw-bold mb-0 me-2">Kepada:</label>
                                                    <span id="customer-display" class="text-dark">Pelanggan Umum</span>
                                                </div>
                                                <div class="text-end">
                                                    <label class="form-label fw-bold mb-0 me-2">Status:</label>
                                                    <span id="member-status" class="badge bg-secondary">Non-Member</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Rincian Pembayaran -->
                                    <div class="card shadow-sm border-0">
                                        <div class="card-body p-3">
                                            <div class="d-flex justify-content-between mb-3">
                                                <label class="form-label mb-0">Total Amount</label>
                                                <span id="total-amount-display" class="fw-semibold">Rp 0</span>
                                                <input type="hidden" name="total_amount" id="total_amount">
                                            </div>

                                            <div class="d-flex justify-content-between mb-3">
                                                <div>
                                                    <label class="form-label mb-0">Diskon</label><br>
                                                    <small class="text-muted">Diskon 5% untuk member</small>
                                                </div>
                                                <span id="discount-display" class="fw-semibold text-success">Rp 0</span>
                                                <input type="hidden" name="discount" id="discount" value="0">
                                            </div>

                                            <hr class="my-3">

                                            <div class="d-flex justify-content-between mb-4">
                                                <label class="form-label fw-bold fs-6">Total Bayar</label>
                                                <span id="total-due-display" class="fw-bold text-primary fs-5">Rp 0</span>
                                                <input type="hidden" name="total_due" id="total_due">
                                            </div>

                                            <!-- QRIS Container -->
                                            <div id="qris-section" class="mb-4" style="display: none;">
                                                <div class="card border-warning">
                                                    <div class="card-header bg-warning text-dark">
                                                        <i class="fas fa-qrcode me-2"></i>Pembayaran QRIS
                                                    </div>
                                                    <div class="card-body text-center">
                                                        <div id="qrContainer"></div>
                                                        <small class="text-muted d-block mt-2">
                                                            Scan QR code di atas untuk melakukan pembayaran
                                                        </small>
                                                        <button id="confirm-qris" class="btn btn-success w-100 mt-3" style="display:none;">
                                                            Konfirmasi QRIS Sudah Dibayar
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group mb-4">
                                                <label for="payment" class="form-label fw-bold">Pembayaran</label>
                                                <input type="number" name="payment" id="payment"
                                                    class="form-control shadow-sm rounded-3" required>
                                            </div>

                                            <div class="form-group">
                                                <label for="change" class="form-label fw-bold">Kembalian</label>
                                                <input type="number" name="change" id="change"
                                                    class="form-control shadow-sm rounded-3" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="card-footer text-end bg-white border-top py-3">
                            <button type="submit" class="btn btn-success shadow-sm px-4 py-2 rounded-3">
                                <i class="fas fa-save me-2"></i>Simpan Transaksi
                            </button>

                            <a href="{{ route('sales.index') }}"
                                class="btn btn-outline-secondary px-4 py-2 rounded-3 ms-2">
                                <i class="fas fa-times me-2"></i>Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL CREATE CUSTOMER -->
    <div class="modal fade" id="modalAddCustomer" tabindex="-1" aria-labelledby="modalAddCustomerLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content rounded-4 shadow-lg border-0">
                <div class="modal-header bg-primary text-white rounded-top-4 py-3">
                    <h5 class="modal-title" id="modalAddCustomerLabel">Tambah Customer Baru</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <form id="formAddCustomer" action="{{ route('customers.store') }}" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <!-- Customer Name -->
                        <div class="form-group mb-3">
                            <label for="customer_name" class="fw-semibold">Nama Customer</label>
                            <input type="text" name="customer_name" id="customer_name"
                                class="form-control shadow-sm rounded-3" required>
                        </div>

                        <!-- Customer Phone -->
                        <div class="form-group mb-3">
                            <label for="customer_phone" class="fw-semibold">Nomor Telepon</label>
                            <input type="number" name="customer_phone" id="customer_phone"
                                class="form-control shadow-sm rounded-3">
                        </div>

                        <!-- Member Status -->
                        <div class="form-group mb-3">
                            <label class="fw-semibold d-block">Status Member</label>
                            <div class="form-check form-check-inline">
                                <input type="radio" id="status_yes" name="member_status" value="1"
                                    class="form-check-input">
                                <label class="form-check-label" for="status_yes">Ya</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input type="radio" id="status_no" name="member_status" value="0"
                                    class="form-check-input" checked>
                                <label class="form-check-label" for="status_no">Tidak</label>
                            </div>
                        </div>

                        <!-- Customer Address -->
                        <div class="form-group mb-3">
                            <label for="address" class="fw-semibold">Alamat</label>
                            <textarea name="address" id="address" class="form-control shadow-sm rounded-3" rows="3"></textarea>
                        </div>


                        <!-- Province -->
                        <div class="form-group mb-3">
                            <label for="province_id">Provinsi</label>
                            <select name="province_id" id="province_id" class="form-control">
                                <option value="">Pilih Provinsi</option>
                                @foreach ($provinces as $code => $name)
                                    <option value="{{ $code }}"
                                        {{ old('province_id') == $code ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                            @error('province_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- City -->
                        <div class="form-group mb-3">
                            <label for="city_id">Kota / Kabupaten</label>
                            <select name="city_id" id="city_id" class="form-control">
                                <option value="">Pilih Kota / Kabupaten</option>
                            </select>
                            @error('city_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- District -->
                        <div class="form-group mb-3">
                            <label for="district_id">Kecamatan</label>
                            <select name="district_id" id="district_id" class="form-control">
                                <option value="">Pilih Kecamatan</option>
                            </select>
                            @error('district_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Village -->
                        <div class="form-group mb-3">
                            <label for="village_id">Kelurahan / Desa</label>
                            <select name="village_id" id="village_id" class="form-control">
                                <option value="">Pilih Kelurahan / Desa</option>
                            </select>
                            @error('village_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                    </div>

                    <div class="modal-footer bg-light border-0 rounded-bottom-4 py-3">
                        <button type="button" class="btn btn-secondary rounded-3 px-4"
                            data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary rounded-3 px-4">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- STYLE -->
    <style>
        .autocomplete-suggestions {
            position: absolute;
            background: #fff;
            border: 1px solid #dcdcdc;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            width: 100%;
            z-index: 1050;
            margin-top: 4px;
            max-height: 200px;
            overflow-y: auto;
            animation: fadeIn 0.2s ease-in-out;
        }

        .autocomplete-suggestion {
            padding: 10px 14px;
            cursor: pointer;
            font-size: 14px;
            color: #333;
            transition: background 0.2s ease;
            border-bottom: 1px solid #f5f5f5;
        }

        .autocomplete-suggestion:last-child {
            border-bottom: none;
        }

        .autocomplete-suggestion:hover,
        .autocomplete-suggestion.selected {
            background-color: #f1f5ff;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-5px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
        }

        .btn {
            transition: all 0.2s ease-in-out;
        }

        .btn:hover {
            transform: translateY(-1px);
        }

        .table th {
            font-weight: 600;
            font-size: 0.875rem;
        }

        #qrContainer img {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 10px;
            background: white;
        }

        /* Scanner Styles */
        .scan-status {
            font-size: 0.8rem;
            padding: 8px 12px;
            border-radius: 6px;
            text-align: center;
            margin-top: 8px;
            font-weight: 500;
        }
        
        .status-scanning {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }
        
        .status-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .status-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .status-info {
            background-color: #cce7ff;
            color: #004085;
            border: 1px solid #b3d7ff;
        }

        /* Input Group Styles */
        #add-product {
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
        }

        .scanner-section {
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 15px;
            background: #f8f9fa;
        }

        /* Scanner USB Indicator */
        .scanner-indicator {
            display: flex;
            align-items: center;
        }

        .scanner-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: #28a745;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.5; }
            100% { opacity: 1; }
        }

        /* Manual Input Styles */
        .manual-input .input-group-text {
            background-color: #f8f9fa;
            border-color: #dee2e6;
        }

        /* Scanner Messages */
        .scanner-messages {
            min-height: 20px;
        }

        .temp-scanner-message {
            animation: slideInUp 0.3s ease-out;
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Success state for manual input */
        .input-success {
            border-color: #198754 !important;
            box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.25) !important;
        }

        /* Focus state for scanner input */
        .scanner-focus {
            border-color: #ffc107 !important;
            box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25) !important;
        }
    </style>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcode-generator/1.4.4/qrcode.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
const customers = @json($customers);
const products = @json($variants);

//variabel global
let productListData = [];
let discountPercent = 5;
let isMember = false;
const STATIC_QRIS = "00020101021126670016COM.NOBUBANK.WWW01189360050300000879140214844519767362640303UMI51440014ID.CO.QRIS.WWW0215ID20243345184510303UMI5204541153033605802ID5920YANTO SHOP OK18846346005DEPOK61051641162070703A0163046879";

// Variabel untuk product autocomplete
let currentProductSuggestions = [];
let selectedSuggestionIndex = -1;

// ===== UTILITY FUNCTIONS =====
function updateDateTime() {
    const now = new Date();
    const options = {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit'
    };
    $('#current-date-time').text(now.toLocaleDateString('id-ID', options));
}

function ConvertCRC16(str) {
    let crc = 0xFFFF;
    for (let c = 0; c < str.length; c++) {
        crc ^= str.charCodeAt(c) << 8;
        for (let i = 0; i < 8; i++) {
            crc = (crc & 0x8000) ? (crc << 1) ^ 0x1021 : crc << 1;
        }
    }
    let hex = (crc & 0xFFFF).toString(16).toUpperCase();
    return hex.length === 3 ? '0' + hex : hex.padStart(4, '0');
}

function showTemporaryMessage(message, type) {
    const scannerMessages = $('#scanner-messages');
    
    let statusClass = 'status-info';
    if (type === 'success') statusClass = 'status-success';
    else if (type === 'error') statusClass = 'status-error';
    else if (type === 'info') statusClass = 'status-scanning';

    const messageElement = $(`
        <div class="temp-scanner-message scan-status ${statusClass}">
            ${message}
        </div>
    `);
    
    scannerMessages.html(messageElement);
    
    setTimeout(() => {
        messageElement.fadeOut(300, function() {
            $(this).remove();
        });
    }, 3000);
}

// ===== CUSTOMER AUTOCOMPLETE =====
function initializeCustomerAutocomplete() {
    const customerSearch = document.getElementById('customer_search');
    const customerList = document.getElementById('customer_list');
    const customerIdInput = document.getElementById('customer_id');

    if (!customerSearch) return;

    customerSearch.addEventListener('input', function() {
        const query = this.value.toLowerCase().trim();
        customerList.innerHTML = '';
        if (query.length < 2) return;

        const filtered = customers.filter(c => c.customer_name.toLowerCase().includes(query));
        if (filtered.length === 0) {
            customerList.innerHTML = '<div class="autocomplete-suggestion text-muted">Tidak ditemukan</div>';
            return;
        }

        filtered.slice(0, 10).forEach(customer => {
            const item = document.createElement('div');
            item.classList.add('autocomplete-suggestion');
            item.textContent = customer.customer_name;
            item.addEventListener('click', function() {
                customerSearch.value = customer.customer_name;
                customerIdInput.value = customer.customer_id;
                customerList.innerHTML = '';

                document.getElementById('customer-display').textContent = customer.customer_name;
                const memberStatus = document.getElementById('member-status');
                if (customer.member_status == 1) {
                    memberStatus.textContent = 'Member';
                    memberStatus.classList.replace('bg-secondary', 'bg-success');
                    customerIdInput.dataset.member = 1;
                } else {
                    memberStatus.textContent = 'Non-Member';
                    memberStatus.classList.replace('bg-success', 'bg-secondary');
                    customerIdInput.dataset.member = 0;
                }
                $('#customer_id').trigger('change');
            });
            customerList.appendChild(item);
        });
    });

    document.addEventListener('click', e => {
        if (!customerSearch.contains(e.target) && !customerList.contains(e.target)) customerList.innerHTML = '';
    });
}

// ===== PRODUCT AUTOCOMPLETE DENGAN KEYBOARD NAVIGATION =====
function initializeProductAutocomplete() {
    const productSearch = document.getElementById('product_search');
    const productList = document.getElementById('product_list');
    const variantIdInput = document.getElementById('variant_id');

    if (!productSearch) return;

    productSearch.addEventListener('input', function() {
        const query = this.value.toLowerCase().trim();
        productList.innerHTML = '';
        currentProductSuggestions = [];
        selectedSuggestionIndex = -1;

        if (query.length < 2) return;

        const filtered = products.filter(p =>
            (p.barcode && p.barcode.toLowerCase().includes(query)) ||
            (p.product?.product_name && p.product.product_name.toLowerCase().includes(query))
        );

        if (filtered.length === 0) {
            productList.innerHTML = '<div class="autocomplete-suggestion text-muted">Produk tidak ditemukan</div>';
            return;
        }

        filtered.slice(0, 10).forEach((product, index) => {
            const item = document.createElement('div');
            item.classList.add('autocomplete-suggestion');
            item.dataset.index = index;
            item.innerHTML = `
                <strong>${product.product.product_name} | ${product.warna} - ${product.ukuran}</strong><br>
                <small class="text-muted">
                    Barcode: ${product.barcode || '-'} | Harga: Rp. ${product.product_sale.toLocaleString('id-ID')}
                </small>
            `;
            item.addEventListener('click', function() {
                selectProduct(product);
            });
            productList.appendChild(item);
            currentProductSuggestions.push(product);
        });

        // Auto scroll ke product list
        setTimeout(() => {
            productList.scrollIntoView({ 
                behavior: 'smooth', 
                block: 'nearest' 
            });
        }, 100);
    });

    // Keyboard navigation untuk autocomplete
    productSearch.addEventListener('keydown', function(e) {
        const suggestions = productList.querySelectorAll('.autocomplete-suggestion');
        
        if (e.key === 'ArrowDown') {
            e.preventDefault();
            if (suggestions.length > 0) {
                selectedSuggestionIndex = (selectedSuggestionIndex + 1) % suggestions.length;
                updateSelectedSuggestion(suggestions);
            }
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            if (suggestions.length > 0) {
                selectedSuggestionIndex = (selectedSuggestionIndex - 1 + suggestions.length) % suggestions.length;
                updateSelectedSuggestion(suggestions);
            }
        } else if (e.key === 'Enter') {
            e.preventDefault();
            if (selectedSuggestionIndex >= 0 && currentProductSuggestions[selectedSuggestionIndex]) {
                // Jika ada suggestion yang dipilih, pilih produk tersebut
                selectProduct(currentProductSuggestions[selectedSuggestionIndex]);
            } else {
                // Jika tidak ada suggestion yang dipilih, coba tambah produk berdasarkan variant_id atau barcode
                const variantId = $('#variant_id').val();
                if (variantId) {
                    addProductToCart(variantId);
                } else {
                    // Coba cari berdasarkan barcode jika tidak ada variant_id
                    const searchText = $('#product_search').val().trim();
                    if (searchText.length >= 3) {
                        const productData = products.find(p => p.barcode == searchText);
                        if (productData) {
                            addProductToCart(productData.variant_id);
                        } else {
                            Swal.fire('Produk tidak ditemukan.', '', 'warning');
                        }
                    } else {
                        Swal.fire('Pilih produk dari daftar atau scan barcode.', '', 'warning');
                    }
                }
            }
        } else if (e.key === 'Escape') {
            productList.innerHTML = '';
            currentProductSuggestions = [];
            selectedSuggestionIndex = -1;
        }
    });

    function updateSelectedSuggestion(suggestions) {
        suggestions.forEach((suggestion, index) => {
            if (index === selectedSuggestionIndex) {
                suggestion.classList.add('selected');
                suggestion.scrollIntoView({ block: 'nearest' });
                
                // Set variant_id untuk yang dipilih
                const selectedProduct = currentProductSuggestions[index];
                if (selectedProduct) {
                    $('#variant_id').val(selectedProduct.variant_id);
                }
            } else {
                suggestion.classList.remove('selected');
            }
        });
    }

    function selectProduct(product) {
        const name = `${product.product.product_name} (${product.warna || '-'} / ${product.ukuran || '-'})`;
        productSearch.value = name;
        variantIdInput.value = product.variant_id;
        productList.innerHTML = '';
        currentProductSuggestions = [];
        selectedSuggestionIndex = -1;
        
        addProductToCart(product.variant_id);
    }

    document.addEventListener('click', e => {
        if (!productSearch.contains(e.target) && !productList.contains(e.target)) {
            productList.innerHTML = '';
            currentProductSuggestions = [];
            selectedSuggestionIndex = -1;
        }
    });
}

// ===== TRANSACTION FUNCTIONS =====
function addProductToCart(variantId) {
    const productData = products.find(p => p.variant_id == variantId);
    if (!productData) {
        showTemporaryMessage('❌ Produk tidak ditemukan', 'error');
        return;
    }

    const stock = productData.stocks?.[0]?.stock || 0;
    const name = `${productData.product.product_name} (${productData.warna || '-'} / ${productData.ukuran || '-'})`;
    const price = productData.product_sale;

    const existingProduct = productListData.find(p => p.id == variantId);
    
    if (existingProduct) {
        if (existingProduct.qty < stock) {
            existingProduct.qty++;
            showTemporaryMessage('✅ ' + name + ' (Qty: ' + existingProduct.qty + ')', 'success');
        } else {
            showTemporaryMessage('❌ Stok tidak cukup: ' + name, 'error');
            return;
        }
    } else {
        if (stock <= 0) {
            showTemporaryMessage('❌ Stok produk habis: ' + name, 'error');
            return;
        }
        productListData.push({
            id: variantId,
            name: name,
            price: price,
            qty: 1
        });
        showTemporaryMessage('✅ ' + name, 'success');
    }

    updateProductTable();
    calculateTotal();
    
    // Reset input
    $('#product_search').val('');
    $('#variant_id').val('');
    $('#product_search').focus();
}

function updateProductTable() {
    const tbody = $('#product-table tbody');
    tbody.empty();
    productListData.forEach((p, i) => {
        const total = p.price * p.qty;
        tbody.append(`
            <tr>
                <td class="small">${p.name}</td>
                <td class="small">Rp ${p.price.toLocaleString('id-ID')}</td>
                <td>
                    <div class="d-flex align-items-center">
                        <button type="button" class="btn btn-sm btn-outline-secondary decrease" data-id="${p.id}">-</button>
                        <span class="mx-2">${p.qty}</span>
                        <button type="button" class="btn btn-sm btn-outline-secondary increase" data-id="${p.id}">+</button>
                    </div>
                </td>
                <td class="small">Rp ${total.toLocaleString('id-ID')}</td>
                <td><button type="button" class="btn btn-danger btn-sm remove" data-id="${p.id}"><i class="fas fa-trash"></i></button></td>
            </tr>
            <input type="hidden" name="products[${i}][variant_id]" value="${p.id}">
            <input type="hidden" name="products[${i}][qty]" value="${p.qty}">
        `);
    });
}

function calculateTotal() {
    const total = productListData.reduce((sum, p) => sum + (p.price * p.qty), 0);
    $('#total_amount').val(total);
    $('#total-amount-display').text('Rp ' + total.toLocaleString('id-ID'));
    const discount = isMember ? (total * discountPercent / 100) : 0;
    $('#discount').val(discount);
    $('#discount-display').text('Rp ' + discount.toLocaleString('id-ID'));
    const totalDue = total - discount;
    $('#total_due').val(totalDue);
    $('#total-due-display').text('Rp ' + totalDue.toLocaleString('id-ID'));
    
    if ($('#payment_method').val() === 'QRIS' && totalDue > 0) {
        generateQRIS(totalDue);
    }
    
    calculateChange();
}

function generateQRIS(amount) {
    if (isNaN(amount) || amount <= 0) return;
    
    let qris = STATIC_QRIS.slice(0, -4);
    let step1 = qris.replace("010211", "010212");
    let step2 = step1.split("5802ID");
    let uang = "54" + amount.toString().length.toString().padStart(2, '0') + amount.toString();
    uang += "5802ID";
    const fix = step2[0].trim() + uang + step2[1].trim();
    const finalQR = fix + ConvertCRC16(fix);
    
    $('#qris_data').val(finalQR);
    $('#qris_code').val('QRIS-' + Date.now());
    
    const qr = qrcode(0, 'M');
    qr.addData(finalQR);
    qr.make();

    const qrContainer = document.getElementById('qrContainer');
    qrContainer.innerHTML = `
        <img src="${qr.createDataURL(8)}" style="max-width:200px;">
        <p class="mt-2"><b>Rp ${amount.toLocaleString('id-ID')}</b></p>
        <small class="text-muted">Kode: ${$('#qris_code').val()}</small>
    `;
}

function calculateChange() {
    const total = parseFloat($('#total_amount').val()) || 0;
    const discount = parseFloat($('#discount').val()) || 0;
    const totalAfterDiscount = total - discount;
    const payment = parseFloat($('#payment').val()) || 0;
    const change = payment - totalAfterDiscount;
    $('#change').val(change < 0 ? 0 : change);
}

// ===== SCANNER USB YANG DIPERBAIKI =====
function initializeScannerUSB() {
    let scanBuffer = '';
    let scanTimeout = null;
    let isScanning = false;
    const SCAN_DELAY = 500; // 500ms delay antara scan

    $(document).on('keypress', function(e) {
        // HANYA deteksi jika tidak sedang fokus di input manapun
        if ($(e.target).is('input') || $(e.target).is('textarea') || $(e.target).is('select')) {
            return;
        }

        // Skip jika sedang dalam proses scan
        if (isScanning) {
            return;
        }

        // Hanya proses karakter alfanumerik
        if (e.key.length === 1 && /[a-zA-Z0-9]/.test(e.key)) {
            scanBuffer += e.key;
            
            clearTimeout(scanTimeout);
            scanTimeout = setTimeout(function() {
                if (scanBuffer.length >= 6) {
                    isScanning = true;
                    console.log('USB Scanner detected:', scanBuffer);
                    
                    const productData = products.find(p => p.barcode == scanBuffer);
                    if (productData) {
                        addProductToCart(productData.variant_id);
                        showTemporaryMessage('✅ ' + productData.product.product_name + ' ditambahkan via scanner', 'success');
                    } else {
                        showTemporaryMessage('❌ Produk tidak ditemukan untuk barcode: ' + scanBuffer, 'error');
                    }
                    
                    scanBuffer = '';
                    
                    // Reset scanning flag setelah delay
                    setTimeout(() => {
                        isScanning = false;
                    }, SCAN_DELAY);
                }
            }, 100);
        }
    });

    // Handle Enter key untuk scanner USB (hanya ketika tidak fokus di input)
    $(document).on('keydown', function(e) {
        if (e.key === 'Enter' && !$(e.target).is('input') && !$(e.target).is('textarea') && !$(e.target).is('select')) {
            e.preventDefault();
            
            if (isScanning) {
                return;
            }
            
            if (scanBuffer.length >= 6) {
                isScanning = true;
                console.log('USB Scanner Enter detected:', scanBuffer);
                
                const productData = products.find(p => p.barcode == scanBuffer);
                if (productData) {
                    addProductToCart(productData.variant_id);
                    showTemporaryMessage('✅ ' + productData.product.product_name + ' ditambahkan via scanner', 'success');
                } else {
                    showTemporaryMessage('❌ Produk tidak ditemukan untuk barcode: ' + scanBuffer, 'error');
                }
                
                scanBuffer = '';
                
                // Reset scanning flag setelah delay
                setTimeout(() => {
                    isScanning = false;
                }, SCAN_DELAY);
            }
        }
    });

    // Reset buffer jika user mulai mengetik manual di product_search
    $('#product_search').on('focus', function() {
        scanBuffer = '';
        clearTimeout(scanTimeout);
    });
}

// ===== MAIN INITIALIZATION =====
$(document).ready(function() {
    console.log('🚀 Initializing transaction system...');
    
    // Initialize date time
    updateDateTime();
    setInterval(updateDateTime, 1000);

    // Initialize scanner USB
    initializeScannerUSB();

    // Initialize autocomplete
    initializeCustomerAutocomplete();
    initializeProductAutocomplete();

    // ===== MODAL CUSTOMER =====
    $('#formAddCustomer').on('submit', function(e) {
        e.preventDefault();

        $('#formAddCustomer .is-invalid').removeClass('is-invalid');
        $('#formAddCustomer .invalid-feedback').remove();

        const form = $(this);
        const url = form.attr('action');
        const data = form.serialize();

        $.ajax({
            url: url,
            method: 'POST',
            data: data,
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Customer berhasil ditambahkan!',
                    showConfirmButton: false,
                    timer: 1200
                });

                customers.push(response.customer);

                $('#customer_search').val(response.customer.customer_name);
                $('#customer_id').val(response.customer.customer_id);
                $('#customer-display').text(response.customer.customer_name);

                if (response.customer.member_status == 1) {
                    $('#member-status').text('Member')
                        .removeClass('bg-secondary')
                        .addClass('bg-success');
                    $('#customer_id').data('member', 1);
                } else {
                    $('#member-status').text('Non-Member')
                        .removeClass('bg-success')
                        .addClass('bg-secondary');
                    $('#customer_id').data('member', 0);
                }

                $('#modalAddCustomer').modal('hide');
                form[0].reset();
                
                $('#city_id').html('<option value="">Pilih Kota / Kabupaten</option>');
                $('#district_id').html('<option value="">Pilih Kecamatan</option>');
                $('#village_id').html('<option value="">Pilih Kelurahan / Desa</option>');
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors || {};
                    $.each(errors, function(field, messages) {
                        const input = $('[name="' + field + '"]', '#formAddCustomer');
                        input.addClass('is-invalid');
                        input.after('<div class="invalid-feedback d-block">' + messages[0] + '</div>');
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal menambahkan customer!',
                        text: xhr.responseJSON?.message || 'Terjadi kesalahan, coba lagi.'
                    });
                }
            }
        });
    });

    // Region dropdown handlers
    $('#province_id').on('change', function() {
        const provinceCode = $(this).val();
        $('#city_id').html('<option value="">Memuat...</option>');
        $('#district_id').html('<option value="">Pilih Kecamatan</option>');
        $('#village_id').html('<option value="">Pilih Kelurahan / Desa</option>');

        if (provinceCode) {
            $.get(`/api/cities/${provinceCode}`, function(data) {
                $('#city_id').empty().append('<option value="">Pilih Kota / Kabupaten</option>');
                $.each(data, function(code, name) {
                    $('#city_id').append(`<option value="${code}">${name}</option>`);
                });
            }).fail(function() {
                $('#city_id').html('<option value="">Gagal memuat data</option>');
            });
        }
    });

    $('#city_id').on('change', function() {
        const cityCode = $(this).val();
        $('#district_id').html('<option value="">Memuat...</option>');
        $('#village_id').html('<option value="">Pilih Kelurahan / Desa</option>');

        if (cityCode) {
            $.get(`/api/districts/${cityCode}`, function(data) {
                $('#district_id').empty().append('<option value="">Pilih Kecamatan</option>');
                $.each(data, function(code, name) {
                    $('#district_id').append(`<option value="${code}">${name}</option>`);
                });
            });
        }
    });

    $('#district_id').on('change', function() {
        const districtCode = $(this).val();
        $('#village_id').html('<option value="">Memuat...</option>');

        if (districtCode) {
            $.get(`/api/villages/${districtCode}`, function(data) {
                $('#village_id').empty().append('<option value="">Pilih Kelurahan / Desa</option>');
                $.each(data, function(code, name) {
                    $('#village_id').append(`<option value="${code}">${name}</option>`);
                });
            });
        }
    });

    // ===== TRANSACTION EVENT HANDLERS =====
    
    $('#payment_method').on('change', function() {
        const method = $(this).val();
        if (method === 'QRIS') {
            $('#qris-section').show();
            calculateTotal();
            $('#confirm-qris').show();
            $('#payment').prop('required', false);
        } else {
            $('#qris-section').hide();
            $('#qrContainer').html('');
            $('#qris_data').val('');
            $('#qris_code').val('');
            $('#confirm-qris').hide();
            $('#payment').prop('required', true);
        }
        $('#payment').val('');
        $('#change').val('');
    });

    $('#customer_id').on('change', function() {
        const memberData = $(this).data('member');
        isMember = memberData == 1;
        calculateTotal();
    });

    $('#add-product').on('click', function() {
        const variantId = $('#variant_id').val();
        if (variantId) {
            addProductToCart(variantId);
        } else {
            const searchText = $('#product_search').val().trim();
            if (searchText.length >= 3) {
                const productData = products.find(p => p.barcode == searchText);
                if (productData) {
                    addProductToCart(productData.variant_id);
                } else {
                    Swal.fire('Produk tidak ditemukan.', '', 'warning');
                }
            } else {
                Swal.fire('Pilih produk dari daftar atau scan barcode.', '', 'warning');
            }
        }
    });

    $('#payment').on('input', calculateChange);

    $(document).on('click', '.increase', function() {
        const id = $(this).data('id');
        const p = productListData.find(x => x.id == id);
        const stock = products.find(v => v.variant_id == id)?.stocks?.[0]?.stock || Infinity;
        if (p && p.qty < stock) {
            p.qty++;
            updateProductTable();
            calculateTotal();
        } else {
            Swal.fire(`Stok hanya tersisa ${stock}`, '', 'info');
        }
    });

    $(document).on('click', '.decrease', function() {
        const id = $(this).data('id');
        const p = productListData.find(x => x.id == id);
        if (p && p.qty > 1) {
            p.qty--;
            updateProductTable();
            calculateTotal();
        }
    });

    $(document).on('click', '.remove', function() {
        const id = $(this).data('id');
        productListData = productListData.filter(x => x.id != id);
        updateProductTable();
        calculateTotal();
        $('#payment').val('');
        $('#change').val('');
    });

    $('#confirm-qris').on('click', function() {
        const totalDue = parseFloat($('#total_due').val()) || 0;
        $('#payment').val(totalDue);
        calculateChange();
        $('#transaction-form').submit();
    });

    $('#transaction-form').on('submit', function(e) {
        const paymentMethod = $('#payment_method').val();
        const totalDue = parseFloat($('#total_due').val()) || 0;
        const payment = parseFloat($('#payment').val()) || 0;
        
        if (paymentMethod === 'QRIS' && payment !== totalDue) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Pembayaran QRIS Tidak Sesuai',
                text: 'Untuk pembayaran QRIS, nominal harus sama dengan total yang harus dibayar!'
            });
            return;
        }
        
        if (paymentMethod === 'cash' && payment < totalDue) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Pembayaran Kurang',
                text: 'Nominal pembayaran kurang dari total yang harus dibayar!'
            });
            return;
        }
        
        if (productListData.length === 0) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Tidak Ada Produk',
                text: 'Tambahkan minimal satu produk untuk melakukan transaksi!'
            });
        }
    });

    // Auto focus ke input produk
    setTimeout(() => {
        $('#product_search').focus();
        showTemporaryMessage('🎯 Scanner USB siap digunakan. Ketik nama produk atau scan barcode.', 'info');
    }, 1000);

    console.log('✅ Transaction system initialized successfully');
});
</script>
@endsection