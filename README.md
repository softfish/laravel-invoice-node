<p align="center"><img width="150px" src="https://integratednode.net/images/integratednodelogo.png"></p>


# Laravel Invoice Node


[![Latest Stable Version](https://poser.pugx.org/feikwok/laravel-invoice-node/v/stable)](https://packagist.org/packages/feikwok/laravel-invoice-node)
[![Total Downloads](https://poser.pugx.org/feikwok/laravel-invoice-node/downloads)](https://packagist.org/packages/feikwok/laravel-invoice-node)
[![License](https://poser.pugx.org/feikwok/laravel-invoice-node/license)](https://packagist.org/packages/feikwok/laravel-invoice-node)

## Overview

This package is designed for Paperplane project with laravel framework. It also can be used separately for standard Laravel application.
It allow user to create a invoice and customizing PDF template. The PDF invoice include a QR code to a payment page which it is integrated with a drop in UI checkout from Stripe API.
Customer can use the payment to make secure payment with credit card. The package would not save any credit card information and it will simply use the UI provided by Stripe Gateway and send payment 
request directly to their server.

## Features

- Generate PDF Invoice and email it through customer email
- Include QR code online payment (page)
- Preview or regenerate the PDF invoice copy
- Resend PDF email to customer
- Resend payment confirmation email to customer 

## Requirements

- Laravel: ^5.6
- laracasts/flash: ^3.0
- barryvdh/laravel-dompdf: ^0.8.2
- simplesoftwareio/simple-qrcode: ^2.0
- firebase/php-jwt: 5.0
- stripe/stripe-php: v6.12.0

## Installation

To start please install the package using the composer command:

```$xslt
composer require feikwok/laravel-invoice-node
```

In the package we are using vuejs and vue-moment and axios packages for the front-end interface. If you have not already install these packages, please run the following command.

```$xslt
npm install vue
npm install axios
npm install vuex
npm install vue-moment
```

**.env** file attributes:

```$xslt
INVOICENODE_TRADING_AS=[Your Company Name]
INVOICENODE_ABN=[Your Company Register Number]
INVOICENODE_TRADER_EMAIL=[your-trader@email.com]
INVOICENODE_TRADER_PHONE=[your contact number]
INVOICENODE_BANK_NAME=[your-bank-name]
INVOICENODE_BSB=[your-bank-bsb]
INVOICENODE_BANK_ACCOUNT_NUMBER=[your-account-number]
INVOICENODE_STRIPE_PUBKEY=[your-stripe-public-key]
INVOICENODE_STRIPE_PKEY=[your-stripe-private-key]
```

## Template Configuration (Optional)

### Adding or changing the invoice logo on default template

The default template before rendering will be checking the following folder location has the invoice-logo.png image file or not. If it finds the image logo file, it will add it to the 
invoice pdf copy.

```$xslt
/public/images/invoice/invoice-logo.png
```

### Registering custom invoice template

You can drop as many as custom invocie pdf template into the following folder. The package will check all the blade files insider this folder and register them all as available invoice template options.

```$xslt
/resources/views/innov/invoice_templates/
```

## Search and Filter

### Basic Search

The basic search will do a wide card search on the following information.

- Invoice Reference Number/Code
- Client/ Customer Name
- Client Business Name
- Client Business Name
- Client Email
- Client Contact Phone Number

## On going development

- Advance Search
- Invoice Overdue Reminder
- Schedule Billing (e.g. subscription charges)
- Admin Access Controls
- Google Address Lookup Integration