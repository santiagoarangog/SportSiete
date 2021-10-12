<?php
//  include_once("/var/www/componentes_ganadeportes_multi/configuracion.inc");
  include_once("../componentes/configuracion.inc");

  if (! f_renovar_id(f_variable("ID"))){
	die("No tiene una sesion valida");
  }


  $codigo = f_variable("codigo");

  error_log("Imprimiendo tirilla : " . $codigo);

  if ($codigo == "")
  {
    die("No se solicito ningun documento");
  }

  $result = pg_prepare($dbconn, "obtenerApuesta",
				" SELECT a.*"
			.		" ,u.codigo as codigousuario"
			.		" ,u.login"
			.	" FROM   apuesta a"
			.		" ,usuario u"
			.	" WHERE  a.codigo = $1"
			.		" AND a.usuario = u.codigo");

  if ( ! ($result = pg_execute($dbconn, "obtenerApuesta", array($codigo))) )
    die("-Error : no se pudo consultar el documento");

  if ( ! ($apuesta = pg_fetch_array($result)) )
    die("-Error : No se encuentra el documento");

  $result = pg_prepare($dbconn, "obtenerApuestaDetalle",
				" SELECT *"
			.	" FROM   apuesta_detalle ad"
			.		" ,encuentro enc"
			.	" WHERE  ad.apuesta = $1"
			.		" AND enc.codigo = ad.encuentro");

  if ( ! ($result = pg_execute($dbconn, "obtenerApuestaDetalle", array($codigo))) )
    die("-Error : no se pudo consultar el detalle del documento");

  $apuesta["Detalle"] = array();

  while ( ($row = pg_fetch_array($result)) )
  {
    array_push($apuesta["Detalle"]
               ,$row);
  }

$elHTML = "<!DOCTYPE html>\n"
	.	"<html data-app-name='GanaDeportes' data-app-subname='GanaDeportes_ma' id='html'>\n"
	.	" <body>\n"
	.	"  <table border=1 width=100% style='border-collapse:collapse;'>\n"
	.	"   <tr>\n"
	.	"    <th colspan=2 style='border-top:0;border-left:0;border-right:0;padding:0;'>\n"
//	.	"     <img src='imagenes/Logo apuestas online.png' style='height:110px;'>\n"
	.	"     <img src='https://sportsiete.com/imagenes/Logo apuestas online.png' style='height:110px;'>\n"
	.	"    </th>\n"
	.	"   </tr>\n"
	.	"   <tr>\n"
	.	"    <td colspan=2padding:0;'>\n"
	.	"     Usuario : " . $apuesta['codigousuario'] . " - " . $apuesta['login'] . "<br>\n"
	.	"     Codigo : " . $apuesta['codigo'] . "<br>\n"
	.	"     Verificacion : " . $apuesta['verificacion'] . "<br>\n"
	.	"    </td>\n"
	.	"   </tr>";
foreach ($apuesta["Detalle"] as $encuentro)
{
	$elHTML .= "<tr>\n"
		.	"<td colspan=2>\n"
		.		$encuentro["local"] . " vs " . $encuentro["visitante"] . "<br>\n"
		.		"juegan : " . substr($encuentro["instante"],0,16) . "<br>\n"
		.		f_Traducir("prediccion") . "   : " . f_TipoResultadoTexto($encuentro["resultado"]) . "<br>\n"
		.		"paga : " . substr($encuentro["factor_pagado"],0,16) . "\n"
//          . "paga   : " . var_dump($encuentro["resultado"]) . substr($encuentro["factor_pagado"],0,16)
		.	"</td>\n"
		. "</tr>\n";
}

$elHTML .= "<tr><td align=right width=20% style='border-bottom:0;'>Instante :</td><td>" . substr($apuesta["instante_apuesta"],0,16) . "</td></tr>\n"
	.  "<tr><td align=right style='border-top:0;border-bottom:0;'>Factor Pagado :</td><td>" . ROUND($apuesta["factor_pagado"],2) . "</td></tr>\n"
	.  "<tr><td align=right style='border-top:0;border-bottom:0;'>Total apostado :</td><td>$ " . $apuesta["monto"] . "</td></tr>\n"
	.  "<tr><td align=right style='border-top:0;'>Ganaria :</td><td>$ " . ROUND((($apuesta["monto"]*$apuesta["factor_pagado"] > 15000000)?15000000:$apuesta["monto"]*$apuesta["factor_pagado"])) . "</td></tr>\n"
	.  "<tr><td colspan=2>\n"
	.  "*Apuesta sin ticket no es válida.<br>\n"
	.  "*Expira a los (10) días.<br>\n"
	.  "*Al confirmar la apuesta se estan\n"
	.  " aceptando los términos y condiciones.<br>\n"
	.  "*Futbol vale hasta los 90 minutos\n"
	.  " más tiempo de reposición.<br>\n"
	.  "*Apuesta realizada con algún evento que\n"
	.  " ya inicio, será anulada sin previo aviso.\n"
	.  "</td></tr>\n"
	;
/*
$elHTML .= "    <script language=javascript>\n"
	.  "      window.print();\n"
//      setTimeout(function(){ window.close(); },1000);
	.  "    </script>\n"
	;
*/
$elHTML .=  "  </body>\n"
	.  "</html>\n"
	;

//echo $elHTML;
//die($elHTML);

file_put_contents('filename.txt', $elHTML);

$descriptorspec = array(
   0 => array("pipe", "r"),
   1 => array("pipe", "w")
);

$process = proc_open("wkhtmltopdf - -", $descriptorspec, $pipes);

if (is_resource($process)) {

    //row2xfdf is made-up function that turns HTML-form data to XFDF
    fwrite($pipes[0], $elHTML);
    fclose($pipes[0]);

    $pdf_content = stream_get_contents($pipes[1]);
    fclose($pipes[1]);

    $return_value = proc_close($process);

    header('Content-type: application/pdf');
//    header('Content-Disposition: attachment; filename="output.pdf"');
    header('Content-Disposition: attachment; filename="SportSiete_' . $apuesta['codigo'] . '.pdf"');
    die($pdf_content);
}
echo "Error";
?>
