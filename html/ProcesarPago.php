<?php

// documentacion de :
// https://developer.paypal.com/docs/api/get-an-access-token-curl/

/*
export ClientID="ActdsKEty4lVLysmoGQLFY9v2MsddpT_uSyeIDuNENWXC5uO8Ye3gPknu7OUyXNTWRhgLKSokJhFOKLW"
export ClientSecret="EPQXniV5-bVeukg7q9PbKb5SM8NicaraohRkGnGmuTJwLKyNpwLEodxHzcTEN5GozOgnfs23Wg_yKFyj"

export Respuesta=`curl --request POST "https://api.sandbox.paypal.com/v1/oauth2/token" \
  -H "Accept: application/json" \
  -H "Accept-Language: en_US" \
  -u "${ClientID}:${ClientSecret}" \
  -d "grant_type=client_credentials"`
*/

$URLToken = "https://api.sandbox.paypal.com/v1/oauth2/token";
// $URLPago = "https://api-m.sandbox.paypal.com/v1/payments/payment";
// $URLPago = "https://api.sandbox.paypal.com/v2/payments/payment";
$URLPago = "https://api-m.sandbox.paypal.com/v1/payments/payment";
$URLCapture = "https://api.sandbox.paypal.com/v2/checkout/orders";

$ClientID="ActdsKEty4lVLysmoGQLFY9v2MsddpT_uSyeIDuNENWXC5uO8Ye3gPknu7OUyXNTWRhgLKSokJhFOKLW";
$ClientSecret="EPQXniV5-bVeukg7q9PbKb5SM8NicaraohRkGnGmuTJwLKyNpwLEodxHzcTEN5GozOgnfs23Wg_yKFyj";

$lasOpciones = Array(
			CURLOPT_URL => $URLToken
			,CURLOPT_TIMEOUT => 60
			,CURLOPT_HTTPHEADER => Array(
							"Content-type: text/xml;charset=\"utf-8\""
							,"Accept: application/json"
							,"Authorization: Basic " . base64_encode($ClientID . ":" . $ClientSecret)
							)
			,CURLOPT_POST => 1
			,CURLOPT_POSTFIELDS => http_build_query(Array(
									"grant_type"=>"client_credentials"
									)
								)
			,CURLOPT_RETURNTRANSFER => 1
			);

error_log(var_export($lasOpciones,true));

$ch = curl_init();
curl_setopt_array($ch, $lasOpciones);

$RespuestaTXT = curl_exec($ch);

if (curl_errno($ch)) {
	error_log("Error: " . curl_error($ch));
} else {
	// Show me the result
	$Respuesta= json_decode($RespuestaTXT);
	if (isset($Respuesta->access_token)){
		$elToken = $Respuesta->access_token;
		error_log("El token recibido es : " . $elToken);
		error_log("Tipo : " . $Respuesta->token_type);
	} else {
		error_log("\nNo se recibe el token\n\n");
		die(var_export($Respuesta,true));
	}
	curl_close($ch);
}

//die(var_export($Respuesta,true));

/*
curl -v -X POST https://api.sandbox.paypal.com/v2/checkout/orders\
   -H "Content-Type: application/json"\
   -H "Authorization: Bearer A2A21AAIKXAD_iREfNywD0WfVOrryOcXnQJl2GMwEVbhSIEp6Wk2CNCfbAJn4kvSkbw2JQnbAhYj6AUBoVA8P9QYBzZCx29ISJQ"\
 -d '{
  "intent": "CAPTURE",
  "purchase_units": [
    {
      "amount": {
        "currency_code": "USD",
        "value": "100.00"
      }
    }
  ]
}'
curl -v -X POST https://api.sandbox.paypal.com/v2/checkout/orders
   -H "Content-Type: application/json"
   -H "Authorization: Bearer A21AAIsiVI9rPO7WlJerB_JUJpx1H3VqzPduKNyA962-CVrwF_eq6A6YyO86svJzo1N98UyXPDdwtTTY45985azM6cjLL2RTw"
 -d '{ "intent": "CAPTURE", "purchase_units": [ { "amount": { "currency_code": "USD", "value": "100.00" } } ] }'
*/
/*
$lasOpciones[CURLOPT_URL] = $URLCapture;
$lasOpciones[CURLOPT_HTTPHEADER] = Array(
					"Content-type: application/json"
					,"Accept: application/json"
					,"Authorization: Bearer " . $elToken
					);
$lasOpciones[CURLOPT_POSTFIELDS] = json_encode(Array(
							"intent" => "CAPTURE"
							,"purchase_units" => Array(
										Array(
											"amount" => Array(
													"currency_code" => "USD"
													,"value" => "100.00"
													)
											)
										)
							)
						);

error_log(var_export($lasOpciones,true));

$ch = curl_init();
curl_setopt_array($ch, $lasOpciones);

$RespuestaTXT = curl_exec($ch);

$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
error_log(var_export($httpcode,true));

if (curl_errno($ch)) {
	error_log("Error: " . curl_error($ch));
} else {
	// Show me the result
	$Respuesta= json_decode($RespuestaTXT);
	if (isset($Respuesta->access_token)){
		$elToken = $Respuesta->access_token;
		error_log("El token recibido es : " . $elToken);
	} else {
		error_log("\nNo se recibe el token\n\n");
		error_log($RespuestaTXT);
		die(var_export($Respuesta,true));
	}
	curl_close($ch);
}

die("fin rapido");
*/

$lasOpciones[CURLOPT_URL] = $URLPago;
$lasOpciones[CURLOPT_HTTPHEADER] = Array(
					"Content-type: application/json"
					,"Accept: application/json"
					,"Authorization: Bearer " . $elToken
					);
$NumeroFactura = "Fact00001";
$MontoBase = 1.0;
$MontoImpuestos = 0.1;
$MontoEnvio = 0.1;
$MontoManejo = 0.1;
$MontoSeguro = 0;
$MontoDescuento = 0.1;
$Moneda = "USD";

$MontoAPagar = ($MontoBase + $MontoImpuestos + $MontoEnvio + $MontoManejo + $MontoSeguro) - $MontoDescuento;
//$lasOpciones[CURLOPT_POSTFIELDS] = http_build_query(Array(
$lasOpciones[CURLOPT_POSTFIELDS] = json_encode(Array(
							"intent" => "sale"
							,"payer" => Array(
									"payment_method" => "paypal"
									)
							,"transactions" => Array(
										Array(
											"amount" => Array(
													"total" => $MontoAPagar
													,"currency" => $Moneda
													,"details" => Array(
															"subtotal" => $MontoBase
															,"tax" => $MontoImpuestos
															,"shipping" => $MontoEnvio
															,"handling_fee" => $MontoManejo
															,"shipping_discount" => - $MontoDescuento
															,"insurance" => $MontoSeguro
															)
													)
											,"description" => "SportSiete Payment"
											,"custom" => $NumeroFactura
											,"invoice_number" => $NumeroFactura
											,"payment_options" => Array(
														"allowed_payment_method" => "INSTANT_FUNDING_SOURCE"
														)
											,"soft_descriptor" => "ECHI5786786"
											,"item_list" => Array(
													"items" => Array(
															Array(
																"name" => "CB0001"
																,"description" => "Carga Saldo"
																,"quantity" => "1"
																,"price" => $MontoBase
																,"tax" => $MontoImpuestos
																,"sku" => "CB0001"
																,"currency" => $Moneda
																)
															)
													,"shipping_address" => Array(
																"recipient_name" => "Hello World"
																,"line1" => "4thFloor"
																,"line2" => "unit#34"
																,"city" => "SAn Jose"
																,"country_code" => "CO"
																,"postal_code" => "00000"
																,"phone" => "+573334445566"
																,"state" => "AN"
																)
													)
											)
										)
							,"note_to_payer" => "Contact us for any questions on your order."
							,"redirect_urls" => Array(
										"return_url" => "https://sportsiete.com/FelicitacionesPorSuPago.php"
										,"cancel_url" => "https://sportsiete.com/ErrorEnPago.php"
										)
							)
						);


error_log(var_export($lasOpciones,true));

$ch = curl_init();
curl_setopt_array($ch, $lasOpciones);

$RespuestaTXT = curl_exec($ch);

$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
error_log(var_export($httpcode,true));

if (curl_errno($ch)) {
	error_log("Error: " . curl_error($ch));
} else {
	// Show me the result
	$Respuesta= json_decode($RespuestaTXT);
	if ( ! isset($Respuesta->state) ){
		error_log("\nNo se recibe un estado para la transaccion\n\n");
		error_log($RespuestaTXT . "\n\n");
		die(var_export($Respuesta,true));
	} else {
		switch(strtolower($Respuesta->state)){
			case "created":
				if ( ! isset($Respuesta->id)){
					error_log("\nLa transaccion parece bien, pero no se recibe un ID para la transaccion\n\n");
					error_log($RespuestaTXT . "\n\n");
					die(var_export($Respuesta,true));
				}
				error_log("Se creo la transaccion.");
				break;
			default:
				error_log("\nError : Estado de la transaccion " . $Respuesta->state . "\n\n");
				error_log($RespuestaTXT . "\n\n");
				die(var_export($Respuesta,true));
				break;
		}
		$TransaccionID = $Respuesta->id;
	}
	curl_close($ch);
}
error_log("El ID es " . $Respuesta->id);
error_log("ahora hay que esperar a que se apruebe...");

error_log(var_export($Respuesta->links,true));

?>
