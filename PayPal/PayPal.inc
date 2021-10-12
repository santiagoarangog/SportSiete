<?php

// con el comando:
//    curl -v POST https://api.sandbox.paypal.com/v1/oauth2/token   -H "Accept: application/json"   -H "Accept-Language: en_US"   -u "ActdsKEty4lVLysmoGQLFY9v2MsddpT_uSyeIDuNENWXC5uO8Ye3gPknu7OUyXNTWRhgLKSokJhFOKLW:EPQXniV5-bVeukg7q9PbKb5SM8NicaraohRkGnGmuTJwLKyNpwLEodxHzcTEN5GozOgnfs23Wg_yKFyj"   -d "grant_type=client_credentials"
// obtengo el siguiente JSON :
//    {
//       "scope":"https://uri.paypal.com/services/invoicing https://uri.paypal.com/services/disputes/read-buyer https://uri.paypal.com/services/payments/realtimepayment https://uri.paypal.com/services/disputes/update-seller https://uri.paypal.com/services/payments/payment/authcapture openid https://uri.paypal.com/services/disputes/read-seller https://uri.paypal.com/services/payments/refund https://api.paypal.com/v1/vault/credit-card https://api.paypal.com/v1/payments/.* https://uri.paypal.com/payments/payouts https://api.paypal.com/v1/vault/credit-card/.* https://uri.paypal.com/services/subscriptions https://uri.paypal.com/services/applications/webhooks"
//      ,"access_token":"A21AAFGQDU5ivOjZlXFbMWe5cSImbu3cTWJFD6rQ4H9o3lZeM4N-LfnJXJsl8r9d-RMFD2BGWybF878wwP1-Wi4OuqH4s_0mA"
//      ,"token_type":"Bearer"
//      ,"app_id":"APP-80W284485P519543T"
//      ,"expires_in":32400
//      ,"nonce":"2020-07-20T02:37:26ZhGfn25581TSqmOahEF1hzgDox0ajMYossc0JumR5j-I"
//    }


$account="sb-m7g4i2602147@business.example.com";
$ClientID= "ActdsKEty4lVLysmoGQLFY9v2MsddpT_uSyeIDuNENWXC5uO8Ye3gPknu7OUyXNTWRhgLKSokJhFOKLW";
$Secret="EPQXniV5-bVeukg7q9PbKb5SM8NicaraohRkGnGmuTJwLKyNpwLEodxHzcTEN5GozOgnfs23Wg_yKFyj";

class _PayPal{

	public static $ClientID="ActdsKEty4lVLysmoGQLFY9v2MsddpT_uSyeIDuNENWXC5uO8Ye3gPknu7OUyXNTWRhgLKSokJhFOKLW";
	public static $ClientSecret="EPQXniV5-bVeukg7q9PbKb5SM8NicaraohRkGnGmuTJwLKyNpwLEodxHzcTEN5GozOgnfs23Wg_yKFyj";

	public static $URLToken = "https://api.sandbox.paypal.com/v1/oauth2/token";
	public static $URLPago = "https://api-m.sandbox.paypal.com/v1/payments/payment";
	public static $URLOrden = "https://api.sandbox.paypal.com/v2/checkout/orders";

	public static $MiToken = false;
	public static $MiTransaccionID = false;
	public static $MiURLConfirmacion = false;
	// $pParamAAA debe ser la autenticacion tipo basic o el token Bearer
	// $pParamPost debe ser el array de los parametros post
	function CrearArrayOpciones($pParamAAA){
		return Array(
			CURLOPT_URL => _PayPal::$URLToken
			,CURLOPT_TIMEOUT => 60
			,CURLOPT_HTTPHEADER => Array(
							"Content-type: application/json"
							,"Accept: application/json"
							// ,"Authorization: Basic " . base64_encode($ClientID . ":" . $ClientSecret)
							,$pParamAAA
							)
			,CURLOPT_POST => 1
                        ,CURLOPT_RETURNTRANSFER => 1
                        );
	}
	function ConsumirServicio($pLasOpc){
		$ch = curl_init();
		curl_setopt_array($ch, $pLasOpc);
		$RespuestaTXT = curl_exec($ch);
		if (curl_errno($ch)) {
			error_log("Error: " . curl_error($ch));
			curl_close($ch);
			return false;
		} else {
			curl_close($ch);
			return json_decode($RespuestaTXT);
		}
	}
	function SolicitarToken(){
		$lasOpc = $this->CrearArrayOpciones(
					"Authorization: Basic " . base64_encode(_PayPal::$ClientID . ":" . _PayPal::$ClientSecret)
					);
		$lasOpc[CURLOPT_HTTPHEADER][0] = "Content-type: text/xml;charset=\"utf-8\""; // no soporta autenticacion en JSON
		$lasOpc[CURLOPT_URL] = _PayPal::$URLToken;
		$lasOpc[CURLOPT_POSTFIELDS] = http_build_query(
								Array(
									"grant_type"=>"client_credentials"
									)
								);
		$resp = $this->ConsumirServicio($lasOpc);
		if ( $resp && isset($resp->access_token) ){
			error_log("Token : " . $resp->access_token);
			_PayPal::$MiToken = $resp->access_token;
			return _PayPal::$MiToken;
		}
		error_log("Respuesta a solicitud de token " . var_export($resp,true));
		return false;
	}

/*
$NumeroFactura = "Fact00001";
$MontoBase = 1.0;
$MontoImpuestos = 0.1;
$MontoEnvio = 0.1;
$MontoManejo = 0.1;
$MontoSeguro = 0;
$MontoDescuento = 0.1;
$Moneda = "USD";
       'field' => 'transactions[0].item_list.items[0].tax',\n
       'field' => 'transactions[0].amount.details.handling_fee',\n
       'field' => 'transactions[0].amount.details.shipping_discount',\n
       'field' => 'transactions[0].amount.details.tax',\n
       'field' => 'transactions[0].amount.details.shipping',\n
       'field' => 'payer',\n
       'issue' => 'Credit_card payment method must use credit_card or cardit_card_token funding instrument.',\n
*/
	function IniciarPago($NumeroFactura,$MontoBase,$MontoImpuestos,$MontoEnvio,$MontoManejo,$MontoSeguro,$MontoDescuento,$Moneda){
		if (!_PayPal::$MiToken){
			error_log("_PayPal::IniciarPago() Error : Primero debe obetener un Token");
			return false;
		}
		$MontoAPagar = ($MontoBase + $MontoImpuestos + $MontoEnvio + $MontoManejo + $MontoSeguro) - $MontoDescuento;
		$lasOpc = $this->CrearArrayOpciones(
					"Authorization: Bearer " . _PayPal::$MiToken
					);
		$lasOpc[CURLOPT_URL] = _PayPal::$URLPago;
		$lasOpc[CURLOPT_POSTFIELDS] = json_encode(Array(
							"intent" => "sale"
							,"payer" => Array(
									// "payment_method" => "paypal"
									"payment_method" => "CREDIT_CARD"
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
error_log($lasOpc[CURLOPT_POSTFIELDS]);
		$resp = $this->ConsumirServicio($lasOpc);
		if ( $resp && isset($resp->state) ){
			switch(strtolower($resp->state)){
				case "created":
					if ( ! $resp->id ){
						error_log("_PayPal::IniciarPago() Error : No contiene un ID");
						return false;
					}
					_PayPal::$MiTransaccionID = $resp->id;
					if ( ! $resp->links ){
						error_log("PayPal : No se encontraron Links en la respuesta " . var_export($resp,true));
					} else {
						error_log("PayPal : Links encontrados " . var_export($resp->links,true));
						foreach($resp->links as $unLink){
							if ($unLink->method == "REDIRECT"){
								error_log("PayPal : Se encontro la URL " . $unLink->href);
								_PayPal::$MiURLConfirmacion = $unLink->href;
							} else
								error_log("PayPal : Se ignora URL porque es para metodo " . $unLink->method);
						}
					}
					return _PayPal::$MiTransaccionID;
				default:
					error_log("_PayPal::IniciarPago() Error : Estado de respuesta no es aceptable " . $resp->state);
					error_log(var_export($resp,true));
					return false;
			}
		}
		error_log("_PayPal::IniciarPago() Error : Estado de respuesta no es aceptable " . var_export($resp,true));
		return false;

	}
	function CrearNuevaOrden($Monto,$Moneda){
		if (!_PayPal::$MiToken){
			error_log("_PayPal::IniciarPago() Error : Primero debe obetener un Token");
			return false;
		}
		$lasOpc = $this->CrearArrayOpciones(
					"Authorization: Bearer " . _PayPal::$MiToken
					);
		$lasOpc[CURLOPT_URL] = _PayPal::$URLOrden;
		$lasOpc[CURLOPT_POSTFIELDS] = json_encode(
							Array(
								"intent" => "CAPTURE"
								,"purchase_units" => Array(
											Array(
												"amount" => Array(
														"currency_code" => $Moneda
														,"value" => $Monto
														)
												)
											)
								)
							);
		$resp = $this->ConsumirServicio($lasOpc);
		if ( $resp && isset($resp->status) ){
			switch(strtolower($resp->status)){
				case "created":
					if ( ! $resp->id ){
						error_log("_PayPal::IniciarPago() Error : No contiene un ID");
						return false;
					}
					_PayPal::$MiTransaccionID = $resp->id;
					if ( ! $resp->links ){
						error_log("PayPal : No se encontraron Links en la respuesta " . var_export($resp,true));
					} else {
						error_log("PayPal : Links encontrados " . var_export($resp->links,true));
						foreach($resp->links as $unLink){
							if ($unLink->method == "POST"){
								error_log("PayPal : Se encontro la URL " . $unLink->href);
								_PayPal::$MiURLConfirmacion = $unLink->href;
							} else
								error_log("PayPal : Se ignora URL porque es para metodo " . $unLink->method);
						}
					}
					return _PayPal::$MiTransaccionID;
				default:
					error_log("_PayPal::IniciarPago() Error : Estado de respuesta no es aceptable " . $resp->state);
					error_log(var_export($resp,true));
					return false;
			}
		}
		error_log(var_export($resp,true));
		return true;
	}
}


?>
