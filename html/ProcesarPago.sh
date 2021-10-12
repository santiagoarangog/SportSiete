#!/bin/bash

export ClientID="ActdsKEty4lVLysmoGQLFY9v2MsddpT_uSyeIDuNENWXC5uO8Ye3gPknu7OUyXNTWRhgLKSokJhFOKLW"
export ClientSecret="EPQXniV5-bVeukg7q9PbKb5SM8NicaraohRkGnGmuTJwLKyNpwLEodxHzcTEN5GozOgnfs23Wg_yKFyj"

export Respuesta=`curl --request POST "https://api.sandbox.paypal.com/v1/oauth2/token" \
  -H "Accept: application/json" \
  -H "Accept-Language: en_US" \
  -u "${ClientID}:${ClientSecret}" \
  -d "grant_type=client_credentials"`

echo "${Respuesta}" | cut -d ""

exit

set AccessToken=""

curl -v -X POST https://api-m.sandbox.paypal.com/v1/payments/payment \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer ${AccessToken}" \
  -d '{
  "intent": "sale",
  "payer": {
    "payment_method": "paypal"
  },
  "transactions": [{
    "amount": {
      "total": "1.2",
      "currency": "USD",
      "details": {
        "subtotal": "1.00",
        "tax": "0.1",
        "shipping": "0.1",
        "handling_fee": "0.1",
        "shipping_discount": "-0.1",
        "insurance": "0.00"
      }
    },
    "description": "This is the payment transaction description.",
    "custom": "EBAY_EMS_90048630024435",
    "invoice_number": "F00001",
    "payment_options": {
      "allowed_payment_method": "INSTANT_FUNDING_SOURCE"
    },
    "soft_descriptor": "ECHI5786786",
    "item_list": {
      "items": [
        {
          "name": "CB0001",
          "description": "Producto 1",
          "quantity": "1",
          "price": "1.00",
          "tax": "0.1",
          "sku": "CB0001",
          "currency": "USD"
        }
      ],
      "shipping_address": {
        "recipient_name": "Hello World",
        "line1": "4thFloor",
        "line2": "unit#34",
        "city": "SAn Jose",
        "country_code": "CO",
        "postal_code": "00000",
        "phone": "+573334445566",
        "state": "AN"
      }
    }
  }],
  "note_to_payer": "Contact us for any questions on your order.",
  "redirect_urls": {
    "return_url": "https://sportsiete.com/FelicitacionesPorSuPago.php",
    "cancel_url": "https://sportsiete.com/ErrorEnPago.php"
  }
}'
