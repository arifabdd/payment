# KapitalBank Payment API with Laravel

The KapitalBank Payment Integration library is a Laravel module that facilitates seamless integration with KapitalBank's payment gateway. This library allows you to create orders, complete transactions, reverse transactions, and retrieve payment status and information with ease.

## Table of Contents

1. [Introduction](#introduction)
2. [Prerequisites](#prerequisites)
3. [Installation](#installation)
4. [Usage](#usage)
    - [Initialize](#initialize)
    - [Create an Order](#create-an-order)
    - [Complete an Order](#complete-an-order)
    - [Reverse a Transaction](#reverse-a-transaction)
    - [Get Order Status](#get-order-status)
    - [Get Order Information](#get-order-information)
5. [License](#license)
6. [Disclaimer](#disclaimer)

## Introduction

The KapitalBank Payment Integration library simplifies the integration of KapitalBank's payment gateway into Laravel applications. It provides methods to interact with the payment gateway, enabling the creation and management of payment orders.

## Prerequisites

Before using this library, ensure you have the following prerequisites:

1. Composer installed on your server or development environment.
2. Valid KapitalBank merchant credentials.
3. SSL certificate and private key files for secure communication.

## Installation

To use the KapitalBank Payment Integration library in your Laravel project, you need to install it via composer. Open a terminal and run the following command:

```bash
composer require arifabdd/payment
```

## Usage

### Initialize

Import the `KapitalBank` class and initialize it with the required parameters:

```php
use Arifabdd\PaymentApi\KapitalBank\Kapitalbank;

$kb = new Kapitalbank(
        'YOUR_MERCHANT_ID',
        'APPROVE_URL',
        'CANCEL_URL',
        'DECLINE_URL',
        'CERT_FILE_PATH',
        'KEY_FILE_PATH',
    );
```

### Configuration parameters

The `KapitalBank` class can be configured by passing appropriate values to its constructor. The available configuration options are:

- `merchantId`: Your merchant ID (Required).
- `approveUrl`: The URL where successful payments will be redirected (Required).
- `cancelUrl`: The URL where canceled payments will be redirected (Required).
- `declineUrl`: The URL where declined payments will be redirected (Required).
- `liveMode`: Set 'true' for live mode, 'false' for test mode or blank (Optional).
- `certFile`: The path to your SSL certificate file (Required).
- `keyFile`: The path to your SSL key file (Required).
- `lang`: The default language for orders (Optional, defaults to 'AZ').
- `currency`: The default currency for orders (optional, defaults to '944').

Ensure that you provide the correct values for your environment.

## Create an Order

Use the `createOrder` method to create a new payment order:

```php
$amount = 100;
$description = "ORDER_DESCRIPTION";

return $kb->createOrder($amount,$description) // $description is optional.
```

Response:

```json
{
   "success": true,
   "data": {
      "amount": 100,
      "orderId": "ORDER_ID",
      "sessionId": "SESSION_ID",
      "description": "ORDER_DESCRIPTION",
      "currency": 944,
      "lang": "EN",
      "paymentUrl": "https://tstpg.kapitalbank.az/index.jsp?ORDERID=ORDER_ID&SESSIONID=SESSION_ID",
      "orderType": "ORDER_TYPE"
   }
}
```

## Complete an Order

Complete a payment order using the `completeOrder` method:

```php
$orderId = "ORDER_ID";  // Replace with your order ID
$sessionId = "SESSION_ID";  // Replace with your session ID

return $kb->completeOrder($orderId,$sessionId,$amount,$description,$lang) // $description and $lang are optional.
```

## Reverse a Transaction

Reverse a payment transaction with the `reverseOrder` method:

```php
$orderId = "ORDER_ID";  // Replace with your order ID
$sessionId = "SESSION_ID";  // Replace with your session ID

return $kb->reverseOrder($orderId,$sessionId,$description,$lang); // $description and $lang are optional.
```

Response: 
```json
{
  "success": true,
  "data": { 
     "orderId": "ORDER_ID", 
     "respCode": "", 
     "respMessage": ""
  }
}

```

## Get Order Status

Retrieve the status of a payment order using the `getOrderStatus` method:

```PHP
$orderId = "ORDER_ID";  // Replace with your order ID
$sessionId = "SESSION_ID";  // Replace with your session ID

return $kb->getOrderStatus($orderId,$sessionId,$lang); // $lang is optional.
```

Response:
```json
{
   "success": true,
   "data": {
      "orderId": "ORDER_ID",
      "orderStatus": "ORDER_STATUS"
   }
}

```

## Get Order Information

Obtain detailed information about a payment order with the `getOrderInformation` method:

```php
$orderId = "ORDER_ID";  // Replace with your order ID
$sessionId = "SESSION_ID";  // Replace with your session ID

return $kb->getOrderInformation($orderId,$sessionId); // $lang is optional.
```

Response: 
```json
{
   "success": true,
   "data": {
      "orderId": "ORDER_ID",
      "orderStatus": "ORDER_STATUS",
      "sessionId": "SESSION_ID",
      "createDate": "2023-09-17T09:39:05.000Z",
      "lastUpdateDate": null,
      "payDate": null,
      "amount": 100,
      "currency": "944",
      "orderLanguage": "EN",
      "description": "ORDER_DESCRIPTION",
      "approveUrl": "APPROVE_URL",
      "cancelUrl": "CANCEL_URL",
      "declineUrl": "DECLINE_URL",
      "receipt": "",
      "twoId": "",
      "refundAmount": "",
      "refundCurrency": null,
      "refundDate": null,
      "extSystemProcess": "0",
      "orderType": "Purchase",
      "orderSubType": "",
      "fee": 0,
      "TWODate": "",
      "TWOTime": ""
   }
}

```

## License

This library is released under the MIT License. See the [LICENSE](LICENSE) file for details.

## Disclaimer

This library is not officially maintained or endorsed by KapitalBank. Use it at your own risk.
