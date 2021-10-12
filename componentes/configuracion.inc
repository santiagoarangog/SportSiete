<?php
  $dbconn = pg_connect("dbname=sportsiete user=root");

  $_duracion_sesion_ = 25;

  function f_variable($pnombre)
  {
    if (isset($_POST[$pnombre]) && $_POST[$pnombre] != "")
      return $_POST[$pnombre];
    else if (isset($_GET[$pnombre]) && $_GET[$pnombre] != "")
      return $_GET[$pnombre];
    else
      return "";
  }

function f_renovar_id($pid)
{
  // Para deshabilitar la opcion de sesiones a largo plazo se le agrega una letra 'p' extra al parametro pcookie
  //     y se crea una variable pcookie con valor true siempre
  global $dbconn;
  global $_duracion_sesion_;
  global $_id_global_;
  if (!isset($_id_global_) || $_id_global_ != $pid)
  {
    $result = pg_prepare($dbconn, "renovar_id",
                  " UPDATE sesion" .
                  " SET instante_fin = now() + ($2 || ' minutes')::interval" .
                     " ,instante_fin_cookie = now() + ($2 || ' minutes')::interval" .
                  " WHERE id = $1" .
                     " AND instante_fin BETWEEN now() AND now() + ($2 || ' minutes')::interval" .
                " RETURNING sesion.usuario");
  }
  $result = pg_execute($dbconn, "renovar_id", array($pid,$_duracion_sesion_));

  if(!$result)
    die("-Error : no se pudo renovar el ID");

  if ( ! ( $row = pg_fetch_array($result) ) )
    return false;

  error_log("Usuario " . $row["usuario"] . " Renovando sesion " . $pid);
  $_id_global_ = $pid;
  return true;
}

function f_extraer_usuario($pid)
{
  global $dbconn;

  $result = pg_prepare($dbconn, "obtener_usuario",
                " SELECT usuario FROM id WHERE id = $1 AND instante_fin >= now()");

  $result = pg_execute($dbconn, "obtener_usuario", array($pid));

  if(!$result)
    die("-Error : no se pudo extraer el usuario para esa sesion");

  $numrows = pg_num_rows($result);
  if ($numrows < 1)
    die("-Error : no tiene una sesion valida (f_extraer_usuario)");

  $row = pg_fetch_array($result);
  return $row["usuario"];
}

function f_ip_usuario()
{
  return getenv('HTTP_CLIENT_IP')?:
         getenv('HTTP_X_FORWARDED_FOR')?:
         getenv('HTTP_X_FORWARDED')?:
         getenv('HTTP_FORWARDED_FOR')?:
         getenv('HTTP_FORWARDED')?:
         getenv('REMOTE_ADDR')?:
         '?.?.?.?';

}

function f_tiene_permiso($pCodigoPermiso)
{
        global $dbconn;
        global $_id_global_;
        global $permisosGlobales;
//error_log("aqui va");
        if ( ! isset($permisosGlobales) )
        {
                $result = pg_prepare($dbconn, "consultaPermisos",
                                " SELECT up.permiso"
                        .       " FROM   usuario_permiso up"
                        .               " ,sesion"
                        .       " WHERE sesion.id = $1"
                        .               " AND sesion.usuario = up.usuario"
                        );
                $result = pg_execute($dbconn, "consultaPermisos", array($_id_global_));
                if(!$result)
                        die("-Error : no se pueden consultar los permisos");
                $permisosGlobales = array();
                while ($row = pg_fetch_array($result))
                {
                        array_push($permisosGlobales
                                ,$row);
                }
        }
        foreach($permisosGlobales as $permiso)
        {
                if ($permiso["permiso"] == $pCodigoPermiso)
                        return true;
        }
        return false;
}

function f_ConsultarDisponible($pSesion)
{
        global $dbconn;
        $result = pg_prepare($dbconn, "consultarDisponible",
                                " SELECT COALESCE(u.aprobador,u.codigo) as codigo"
                        .               " ,SUM(uc.monto_restante) AS disponible"
                        .       " FROM   sesion s"
                        .               " ,usuario u"
                        .                       " LEFT OUTER JOIN usuario_carga uc"
                        .                               " ON COALESCE(u.aprobador,u.codigo) = uc.usuario"
                        .                                       " AND uc.monto_restante > 0"
                        .       " WHERE  s.id = $1"
                        .               " AND  s.usuario = u.codigo"
                        .       " GROUP BY u.codigo");

        $result = pg_execute($dbconn, "consultarDisponible", array($pSesion));

        if (!$result)
        {
//              return json_encode(array("Error" => "-1", "ErrorDescripcion" => "Error consultando disponible"));
                return false;
        }

        $row = pg_fetch_array($result);
        return $row["disponible"];
}

function f_TipoResultadoTexto($pTipoResultado)
{
	switch ($pTipoResultado)
	{
		case "(1,1)": return "Gana Local";
		case "(1,2)": return "Termina Empatado";
		case "(1,3)": return "Gana Visitante";

		case "(2,1)": return "Empate o Gana el Local";
		case "(2,2)": return "Gana uno de ellos";
		case "(2,3)": return "Empate o Gana el Visitante";

		case "(3,2)": return "Menos de 3 goles";
		case "(3,4)": return "Mas de 3 goles";
		case "(3,15)": return "2 goles en el partido";
		case "(3,16)": return "3 goles en el partido";
		case "(3,31)": return "1 gol en el partido";
		case "(3,32)": return "4 goles en el partido";
		case "(3,33)": return "5 goles en el partido";

		case "(4,1)": return "Primer tiempo Gana Local";
		case "(4,2)": return "Primer tiempo Empatan";
		case "(4,3)": return "Primer tiempo Gana Visitante";

		case "(19,1)": return "Segundo tiempo Gana Local";
		case "(19,2)": return "Segundo tiempo Empatan";
		case "(19,3)": return "Segundo tiempo Gana Visitante";

		case "(130,1)": return "Ambos equipos marcan";
		case "(130,2)": return "Solo 1 marca";

		default:
			return $pTipoResultado;
	}
}

?>
