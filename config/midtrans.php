<?php

return [
    'isProduction' => false, // true jika nanti sudah live
    'serverKey' => env('MIDTRANS_SERVER_KEY'),
    'clientKey' => env('MIDTRANS_CLIENT_KEY'),
    'merchantId' => env('MIDTRANS_MERCHANT_ID'),
];