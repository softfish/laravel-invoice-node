<?php

return [
    'trading_as' => env('INVOICENODE_TRADING_AS', ''),
    'abn' => env('INVOICENODE_ABN', ''),
    'email' => env('INVOICENODE_TRADER_EMAIL', ''),
    'phone' => env('INVOICENODE_TRADER_PHONE', ''),
    'bank_name' => env('INVOICENODE_BANK_NAME', ''),
    'bsb' => env('INVOICENODE_BSB', ''),
    'bank_account_number' => env('INVOICENODE_BANK_ACCOUNT_NUMBER', ''),
    'invoice_prefix' => env('INVOICENODE_INVOICE_PREFIX', 'INV'),
    'logo_file_name' => env('INVOICENODE_INVOICE_LOGO_FILENAME', 'invoice_logo.png'),

    'payment_gateway' => [
        'stripe' => [
            'api_key' => env('INVOICENODE_STRIPE_PUBKEY', ''),
            'pkey' => env('INVOICENODE_STRIPE_PKEY', ''),
        ],
        'braintree' => [
            'merchant_id' => env('INVOICENODE_BRAINTREE_MERCHANT_ID', ''),
            'public_key' => env('INVOICENODE_BRAINTREE_PUBKEY', ''),
            'private_key' => env('INVOICENODE_BRAINTREE_PRIKEY', ''),
        ]
    ],

    'invoice_comms' => [
        'admin_email' => env('INVOICENODE_ADMIN_EMAIL', env('INVOICENODE_TRADER_EMAIL')),
        'brand' => env('INVOICENODE_BRAND_NAME', env('APP_NAME')),
        'invoice_receipt_template' => file_exists(resource_path().'/views/email/invoice/invoice-receipt.blade.php')? 'email.invoice.invoice-receipt': 'invoice-node::email.invoice-receipt',
        'customer_invoice_template' => file_exists(resource_path().'/views/email/invoice/customer-invoice.blade.php')? 'email.invoice.customer-invoice': 'invoice-node::email.customer-invoice',
    ],
    // This is restricted for 22 characters
    'statement_descriptor' => env('INVOICENODE_STATEMENT_DESCRIPTOR', null),

    'notification' => [
        'slack' => [
            'billing_webhook' => env('INVOICENODE_SLACK_NOTIFICATION_BILLING_WEBHOOK_URL', null),
            'error_webhook' => env('INVOICENODE_SLACK_NOTIFICATION_ERROR_WEBHOOK_URL', null),
        ]
    ]
];