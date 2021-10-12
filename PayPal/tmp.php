<?php

include_once("PayPal.inc");

$p = new _PayPal();

if ( ! $p->SolicitarToken() ){
	error_log("No se obtiene un token");
}
if ( ! ($a = $p->IniciarPago("Fact00001",1.0,0.1,0.1,0.1,0,0.1,"USD") ) )
	error_log("No se obtiene un numero de transaccion");

error_log("La transaccion es " . $a);

?>
