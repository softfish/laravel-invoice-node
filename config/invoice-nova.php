<?php

return [
    'trading_as' => env('INVOICENOVA_TRADING_AS', ''),
    'abn' => env('INVOICENOVA_ABN', ''),
    'email' => env('INVOICENOVA_TRADER_EMAIL', ''),
    'phone' => env('INVOICENOVA_TRADER_PHONE', ''),
    'bank_name' => env('INVOICENOVA_BANK_NAME', ''),
    'bsb' => env('INVOICENOVA_BSB', ''),
    'bank_account_number' => env('INVOICENOVA_BANK_ACCOUNT_NUMBER', ''),
    'invoice_prefix' => env('INVOICENOVE_INVOICE_PREFIX', 'INV'),
    'logo_file_name' => env('INVOICENOVA_INVOICE_LOGO_FILENAME', 'invoice_logo.png'),

    'payment_gateway' => [
        'stripe' => [
            'api_key' => env('INVOICENOVA_STRIPE_PUBKEY', ''),
            'pkey' => env('INVOICENOVA_STRIPE_PKEY', ''),
        ],
        'braintree' => [
            'merchant_id' => env('INVOICENOVA_BRAINTREE_MERCHANT_ID', ''),
            'public_key' => env('INVOICENOVA_BRAINTREE_PUBKEY', ''),
            'private_key' => env('INVOICENOVA_BRAINTREE_PRIKEY', ''),
        ]
    ],

    'invoice_comms' => [
        'admin_email' => env('INVOICENOVA_ADMIN_EMAIL', env('INVOICENOVA_TRADER_EMAIL')),
        'brand' => env('INVOICENOVA_BRAND_NAME', env('APP_NAME')),
        'invoice_receipt_template' => file_exists(resource_path().'/views/email/invoice/invoice-receipt.blade.php')? 'email.invoice.invoice-receipt': 'invoice-nova::email.invoice-receipt',
        'customer_invoice_template' => file_exists(resource_path().'/views/email/invoice/customer-invoice.blade.php')? 'email.invoice.customer-invoice': 'invoice-nova::email.customer-invoice',
    ],
];