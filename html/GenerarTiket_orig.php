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


?>
<!DOCTYPE html>
<html data-app-name="GanaDeportes" data-app-subname="GanaDeportes_ma" id="html">
  <body>
<?php
	echo "
<table border=1 width=100% style='border-collapse:collapse;'>
 <tr>
  <th colspan=2 style='border-top:0;border-left:0;border-right:0;padding:0;'>
   <img src='imagenes/Logo apuestas online.png' style='height:110px;'>
  </th>
 </tr>
 <tr>
  <td colspan=2padding:0;'>
   Usuario : " . $apuesta['codigousuario'] . " - " . $apuesta['login'] . "<br>
   Codigo : " . $apuesta['codigo'] . "<br>
   Verificacion : " . $apuesta['verificacion'] . "<br>
  </td>
 </tr>";
/*
 <tr>
  <th colspan=2>
   GANA DEPORTES
  </th>
 </tr>
";
*/
foreach ($apuesta["Detalle"] as $encuentro)
{

  echo "<tr>"
        ."<td colspan=2>"
          . $encuentro["local"] . " vs " . $encuentro["visitante"] . "<br>"
          . "juegan : " . substr($encuentro["instante"],0,16) . "<br>"
          . "prediccion   : " . f_TipoResultadoTexto($encuentro["resultado"]) . "<br>"
          . "paga : " . substr($encuentro["factor_pagado"],0,16)
//          . "paga   : " . var_dump($encuentro["resultado"]) . substr($encuentro["factor_pagado"],0,16)
        ."</td>"
      ."</td>";
}

echo "
<tr><td align=right width=20% style='border-bottom:0;'>Instante :</td><td>" . substr($apuesta["instante_apuesta"],0,16) . "</td></tr>
<tr><td align=right style='border-top:0;border-bottom:0;'>Factor Pagado :</td><td>" . ROUND($apuesta["factor_pagado"],2) . "</td></tr>
<tr><td align=right style='border-top:0;border-bottom:0;'>Total apostado :</td><td>$ " . $apuesta["monto"] . "</td></tr>
<tr><td align=right style='border-top:0;'>Ganaria :</td><td>$ " . ROUND((($apuesta["monto"]*$apuesta["factor_pagado"] > 15000000)?15000000:$apuesta["monto"]*$apuesta["factor_pagado"])) . "</td></tr>
<tr><td colspan=2>
*Apuesta sin ticket no es válida.<br>
*Expira a los (10) días.<br>
*Al confirmar la apuesta se estan
 aceptando los términos y condiciones.<br>
*Futbol vale hasta los 90 minutos
 más tiempo de reposición.<br>
*Apuesta realizada con algún evento que
 ya inicio, será anulada sin previo aviso.
</td></tr>
";
?>
    <script language=javascript>
      window.print();
//      setTimeout(function(){ window.close(); },1000);
    </script>
  </body>
</html>
