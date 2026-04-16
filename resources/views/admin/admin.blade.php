    <!DOCTYPE html>
    <html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="../assets/" data-template="vertical-menu-template-free">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
        <title>House Of Hilwa</title>
        <meta name="description" content="" />

        <!-- Favicon -->
        <link rel="icon" type="image/x-icon" href="../assets/img/favicon/favicon.ico" />

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />

        <!-- Icons -->
        <link rel="stylesheet" href="{{asset('sneat/assets/vendor/fonts/boxicons.css')}}" />

        <!-- Core CSS -->
        <link rel="stylesheet" href="{{asset('sneat/assets/vendor/css/core.css')}}" class="template-customizer-core-css" />
        <link rel="stylesheet" href="{{asset('sneat/assets/vendor/css/theme-default.css')}}" class="template-customizer-theme-css" />
        <link rel="stylesheet" href="{{asset('sneat/assets/css/demo.css')}}" />

        <!-- Vendors CSS -->
        <link rel="stylesheet" href="{{asset('sneat/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css')}}" />

        <!-- DataTables CSS -->
        <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css" />
        <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap4.min.css" />

        <!-- Helpers -->
        <script src="{{asset('sneat/assets/vendor/js/helpers.js')}}"></script>

        <!-- Config -->
        <script src="{{asset('sneat/assets/js/config.js')}}"></script>
    </head>
    <style>
        #total_due_field {
            display: none;
        }
    </style>
    <body>
        <!-- Layout wrapper -->
        <div class="layout-wrapper layout-content-navbar">
            <div class="layout-container">
                <!-- Menu -->
                @include('admin.sidebar')
                <!-- / Menu -->

                <!-- Layout container -->
                <div class="layout-page">
                    <!-- Navbar -->
                    @include('admin.header')
                    <!-- / Navbar -->

                    <!-- Content wrapper -->
                    <div class="content-wrapper">
                        <!-- Content -->
                        @yield('content')
                        <!-- / Content -->

                        <!-- Footer -->
                        @include('admin.footer')
                        <!-- / Footer -->

                        <div class="content-backdrop fade"></div>
                    </div>
                    <!-- Content wrapper -->
                </div>
                <!-- / Layout page -->
            </div>

            <!-- Overlay -->
            <div class="layout-overlay layout-menu-toggle"></div>
        </div>
        <!-- / Layout wrapper -->

<!-- Core JS -->
<script src="{{ asset('sneat/assets/vendor/libs/jquery/jquery.js')}}"></script>
<script src="{{ asset('sneat/assets/vendor/libs/popper/popper.js')}}"></script>
<script src="{{ asset('sneat/assets/vendor/js/bootstrap.js')}}"></script>
<script src="{{ asset('sneat/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js')}}"></script>
<script src="{{ asset('sneat/assets/vendor/js/menu.js')}}"></script>


        <!-- Vendors JS -->
        <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
        <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
        <script src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap4.min.js"></script>

        <!-- Main JS -->
        <script src="{{ asset('sneat/assets/js/main.js')}}"></script>

        <!-- Page JS -->
        <script async defer src="https://buttons.github.io/buttons.js"></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
        <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

        <script>
            $(document).ready(function() {
                // Default date range (start and end of the current month)
                let start = moment().startOf('month');
                let end = moment().endOf('month');

                // Check if there's a previous selection from the server (e.g., after form submission)
                @if(request('date_range'))
                    const dates = "{{ request('date_range') }}".split(" - ");
                    start = moment(dates[0], 'YYYY-MM-DD');
                    end = moment(dates[1], 'YYYY-MM-DD');
                @endif

                // Initialize date range picker
                $('#date_range').daterangepicker({
                    locale: { format: 'YYYY-MM-DD' },
                    startDate: start,
                    endDate: end,
                }, function(start, end) {
                    $('#date_range').val(start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD'));
                    $('#start_date').val(start.format('YYYY-MM-DD'));
                    $('#end_date').val(end.format('YYYY-MM-DD'));
                });

                $('#start_date').val(start.format('YYYY-MM-DD'));
                $('#end_date').val(end.format('YYYY-MM-DD'));
                $('#date_range').val(start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD'));

                $('#resetButton').click(function() {
                    window.location.href = "";
                });

                // Initialize DataTable
                $("#example1").DataTable({
                    "responsive": true,
                    "autoWidth": false,
                    "paging": true,
                    "searching": true,
                    "language": {
                        "emptyTable": "Tidak ada data yang tersedia",A
                        "info": "Menampilkan _START_ hingga _END_ dari _TOTAL_ data",
                        "infoEmpty": "Menampilkan 0 hingga 0 dari 0 data",
                        "infoFiltered": "(disaring dari _MAX_ total data)",
                        "lengthMenu": "Tampilkan _MENU_ data",
                        "search": "Cari;",
                        "zeroRecords": "Tidak ada hasil yang ditemukan",
                        "paginate": {
                            "first": "Pertama",
                            "last": "Terakhir",
                            "next": "Selanjutnya",
                            "previous": "Sebelumnya"
                        }
                    }
                });
            });
        </script>

        @stack('scripts')
        <script>
    document.addEventListener("DOMContentLoaded", function(){
        // For Sneat / Bootstrap 5 Tooltip
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        tooltipTriggerList.forEach((tooltipTriggerEl) => {
            new bootstrap.Tooltip(tooltipTriggerEl, {
                container: 'body'
            });
        });
    });
</script>

    </body>
    </html>