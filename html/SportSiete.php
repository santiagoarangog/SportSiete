<?php

  include_once("../componentes/configuracion.inc");
  include_once("../componentes/RestServer.inc");
  include_once("../PayPal/PayPal.inc");

  class Apuestas
  {
     public static function AyudaMetodosListar()
     {
  	return json_encode(array(array("Metodo" => "AyudaMetodosListar"
                                      ,"Descripcion" => "Lista los servicios expuestos y sus caracteristicas"
                                      )
                                ,array("Metodo" => "Ejemplo"
                                      ,"Descripcion" => "Esto es un ejemplo"
                                      ,"Retorno" => "1 OK, 0 Error"
                                      ,"Parametros" => array(array("Nombre" => "ParametroEjemplo1"
                                                                  ,"Tipo" => "texto"
                                                                  ,"Descripcion" => "Parametro 1"
                                                                  )
                                                            ,array(
                                                                   "Nombre" => "ParametroEjemplo2"
                                                                  ,"Tipo" => "texto"
                                                                  ,"Descripcion" => "Parametro 2"
                                                                  )
                                                            )
                                      )


                          ,array("Metodo" => "ReporteImagenSubir"
                                      ,"Descripcion" => "Almacena una imagen y la anexa a un reporte de un objeto encontrado"
                                      ,"Retorno" => "1 si se almacena con exito o 0 si hubo errores"
                                      ,"Parametros" => array(array("Nombre" => "Token"
                                                                  ,"Tipo" => "texto"
                                                                  ,"Descripcion" => "Token de la sesion"
                                                                  )
                                                            ,array(
                                                                   "Nombre" => "Reporte"
                                                                  ,"Tipo" => "EnteroLargo"
                                                                  ,"Descripcion" => "Codigo retornado por el metodo ReporteGrabar"
                                                                  )
                                                            ,array(
                                                                   "Nombre" => "ImagenFormato"
                                                                  ,"Tipo" => "texto"
                                                                  ,"Descripcion" => "Formato de la imagen JPG GIF PNG"
                                                                  )
                                                            ,array(
                                                                   "Nombre" => "ImagenB64"
                                                                  ,"Tipo" => "texto"
                                                                  ,"Descripcion" => "Binario de la imagen codificada en Base64"
                                                                  )
                                                            )
                                      )


				)
                           );
     }


     public static function datos3($Deporte)
     {
 	global $dbconn;
        global $max_pixels_imagen;
        global $objetos_imagenes_directorio;

	error_log(var_export($_POST,true));

	if (isset($_POST["ID"])){
		// La sesion se renueva, pero en realidad no es importante saber si
		// era valida o no
		f_renovar_id($_POST["ID"]);
	}
/*
       if (!f_renovar_id($Sesion))
          return json_encode(array("Error" => "-1000", "ErrorDescripcion" => "No se pudo Renovar la sesion"));
*/


  	$result = pg_prepare($dbconn, "tipos",
			" SELECT * FROM tipo_resultado WHERE deporte = $1");

  	$result = pg_execute($dbconn, "tipos", array($Deporte));

	if (!$result)
		return json_encode(array("Error" => "-1", "ErrorDescripcion" => "Error cargando tipos de Resultados"));

	$arraytipos = array();
	while ($row = pg_fetch_array($result))
	{
	            array_push($arraytipos
				,array("deporte" => $row["deporte"]
					,"tipo" => $row["tipo"]
					,"descripcion" => $row["descripcion"]
					)
				);
	}

  	$result = pg_prepare($dbconn, "dep",
			" SELECT CASE enc.liga_id"
		.			" WHEN '395'                        THEN 2"
		.			" WHEN '17'                         THEN 2"
		.			" WHEN '1537'                       THEN 5"
		.			" WHEN '3836'                       THEN 5"
		.			" WHEN '1748'                       THEN 5"
		.			" WHEN '1438'                       THEN 5"
		.			" WHEN '196'                       THEN 5"
		.			" WHEN '6391'                       THEN 8"
		.			" WHEN '8570'                       THEN 8"
		.			" WHEN '6535'                       THEN 8"
		.			" WHEN '5429'                       THEN 8"
		.			" WHEN '1080'                       THEN 8"
		.			" WHEN '9440'                       THEN 10"
		.			" WHEN '63'                         THEN 10"
		.			" WHEN '83'                         THEN 15"
		.			" WHEN '3177'                       THEN 15"
		.			" WHEN '9691'                       THEN 15"
		.			" WHEN '21'                         THEN 18"
		.			" WHEN '39'                         THEN 20"
		.			" WHEN '22'                         THEN 20"
		.			" WHEN '2826'                       THEN 20"
		.			" ELSE 1000"
		.		" END AS orden"
		.		" ,enc.codigo      "
		.		" ,enc.instante   "
		.		" ,enc.deporte    "
		.		" ,enc.deporte_id "
		.		" ,enc.local      "
		.		" ,enc.local_id   "
		.		" ,enc.visitante  "
		.		" ,enc.visitante_id "
		.		" ,enc.liga	    "
		.		" ,enc.liga_id	    "
		.		" ,tr.tipo as resultado"
		.		" ,COALESCE(enc_r.factor_pagado,0) * CASE WHEN COALESCE(enc_r.factor_pagado,0) < 0 THEN -1 ELSE 1 END AS factor_pagado "
		.		" ,COALESCE(enc_r.extra,'') AS extra "
		.		" ,1 as grupo "
		.	" FROM encuentro enc"
		.		" LEFT OUTER JOIN tipo_resultado tr ON tr.deporte = $1"
/*
		.		" LEFT OUTER JOIN (VALUES         ('(1,1)',1)"
		.						" ,('(1,2)',1)"
		.						" ,('(1,3)',1)"
		.						" ,('(2,1)',1)"
		.						" ,('(2,2)',1)"
		.						" ,('(2,3)',1)"
		.						" ,('(3,2)',1)"
		.						" ,('(3,4)',1)"
		.						" ,('(4,1)',1)"
		.						" ,('(4,2)',1)"
		.						" ,('(4,3)',1)"
//		.						" ,('(4,10)',1)"
//		.						" ,('(4,11)',1)"
		.						" ,('(130,1)',1)"
		.						" ,('(130,2)',1)"
		.			") t(a,g) ON 1=1"
		.		" LEFT OUTER JOIN encuentro_resultados enc_r ON (enc_r.encuentro,enc_r.resultado) = (enc.codigo,t.a)"
*/
		.		" LEFT OUTER JOIN encuentro_resultados enc_r ON (enc_r.deporte,enc_r.encuentro,enc_r.resultado) = (enc.deporte_id,enc.codigo,tr.tipo)"
		.	" WHERE enc.deporte_id = $1"
		.		" AND enc.instante >= now() - '1 minute'::interval"
		.		" AND enc.contador > 0"
		.		" AND enc.visitante IS NOT NULL"
		.	" ORDER BY enc.deporte_id,orden,enc.liga,enc.instante,enc.codigo,split_part(tr.tipo,',',1),split_part(tr.tipo,',',2)");
  	$result = pg_execute($dbconn, "dep", array($Deporte));


  	$numrows = pg_num_rows($result);


	 $arrayrespuesta = array();
         $row2=null;
         $codigo=-1;
        if (!$result)
          return json_encode(array("Error" => "-1", "ErrorDescripcion" => "Error cargando encuentros"));
       	else
	{
 	    while ($row = pg_fetch_array($result))
		{
             	  if($codigo!= $row["codigo"])
		   {
	            $codigo=$row["codigo"];
                    $arrayresultado=array();
		     array_push($arrayrespuesta
                        ,array("codigo" => $row["codigo"]
                              ,"instante" => $row["instante"]
                             ,"deporte" => $row["deporte"]
            		     ,"deporte_id" => $row["deporte_id"]
			     ,"local" => $row["local"]
			     ,"local_id" => $row["local_id"]
                             ,"visitante" => $row["visitante"]
                             ,"visitante_id" => $row["visitante_id"]
                             ,"liga" => $row["liga"]
                             ,"liga_id" => $row["liga_id"]
		             ,"Resultados" => $arrayresultado));
		  }
	            array_push($arrayrespuesta[count($arrayrespuesta)-1]["Resultados"]
				,array("resultado" => $row["resultado"]
					,"factor_pagado" => $row["factor_pagado"]
					,"grupo" => $row["grupo"]
					,"extra" => $row["extra"]
					)
				);

		}
		return json_encode(array("Encuentros" => $arrayrespuesta,"Tipos" => $arraytipos));
         }  
	 return json_encode(array("Error" => "-1", "ErrorDescripcion" => "Error guardando2"));
	//return json_encode($arrayrespuesta);



     }

     public static function datosUsuario($Sesion)
     {
	global $dbconn;

	if (!f_renovar_id($Sesion))
		return json_encode(array("Error" => "-1000", "ErrorDescripcion" => "No se pudo Renovar la sesion"));

	$result = pg_prepare($dbconn, "dato",
				" SELECT u.codigo"
			.		" ,u.login"
			.		" ,SUM(uc.monto_restante) AS disponible"
			.	" FROM   sesion s"
			.		" ,usuario u"
			.			" LEFT OUTER JOIN usuario_carga uc"
			.				" ON COALESCE(u.aprobador,u.codigo) = uc.usuario"
			.					" AND uc.monto_restante > 0"
			.	" WHERE  s.id = $1"
			.		" AND  s.usuario = u.codigo"
			.	" GROUP BY u.codigo");
  	$result = pg_execute($dbconn, "dato", array($Sesion));

        if (!$result)
          return json_encode(array("Error" => "-1000", "ErrorDescripcion" => "No se pudo Consultar informacion del usuario"));

	if ( ! ($row = pg_fetch_array($result)) ){
		error_log("Intento de extraer datos de usuario : id " . $Sesion);
		return json_encode(array("Error" => "-1000", "ErrorDescripcion" => "No se Encontro informacion del usuario"));
	}

	return json_encode(array("Usuario" => $row));
   }
     public static function cargarMonto($Sesion,$Cliente,$Monto)
     {
	global $dbconn;

	if (!f_renovar_id($Sesion))
		return json_encode(array("Error" => "-1000", "ErrorDescripcion" => "No se pudo Renovar la sesion"));

	$result = pg_prepare($dbconn, "carga",
				" INSERT INTO usuario_carga"
			.		" (vendedor,usuario,instante,monto_original,monto_restante)"
			.	" SELECT s.usuario"
			.		" ,$2"
			.		" ,now()"
			.		" ,$3"
			.		" ,$3"
			.	" FROM sesion s"
			.	" WHERE s.id = $1");

  	$result = pg_execute($dbconn, "carga", array($Sesion,$Cliente,$Monto));

        if (!$result)
          return json_encode(array("Error" => "-1000", "ErrorDescripcion" => "No se pudo Cargar el Monto"));

	$result = pg_prepare($dbconn, "Disponible",
				" SELECT SUM(uc.monto_restante) AS disponible"
			.	" FROM   usuario u"
			.			" LEFT OUTER JOIN usuario_carga uc"
			.				" ON u.codigo = uc.usuario"
			.					" AND uc.monto_restante > 0"
			.	" WHERE  u.codigo = $1");
  	$result = pg_execute($dbconn, "Disponible", array($Cliente));

        if (!$result)
          return json_encode(array("Error" => "-1000", "ErrorDescripcion" => "No se pudo Consultar informacion del usuario"));

	if ( ! ($row = pg_fetch_array($result)) )
		return json_encode(array("Error" => "-1000", "ErrorDescripcion" => "No se Encontro informacion del usuario"));

	return json_encode(array("NuevoSaldo" => $row["disponible"]));
     }
     public static function PremioConsultar($Sesion,$Codigo,$Clave)
     {
	global $dbconn;

	if (!f_renovar_id($Sesion))
		return json_encode(array("Error" => "-1000", "ErrorDescripcion" => "No se pudo Renovar la sesion"));

	$result = pg_prepare($dbconn, "consulta",
				" SELECT a.estado"
			.		" ,CASE WHEN round(a.monto * CASE WHEN a.doublechance THEN ((a.factor_pagado - 1)/2)+1 ELSE a.factor_pagado END) > 15000000"
			.				" THEN 15000000"
			.				" ELSE round(a.monto * CASE WHEN a.doublechance THEN ((a.factor_pagado - 1)/2)+1 ELSE a.factor_pagado END)"
			.			" END as monto"
			.		" ,date('today') - date(instante_apuesta) as diastranscurridos"
			.		" ,CASE WHEN a.doublechance THEN 1 ELSE 0 END as doublechance"
			.	" FROM apuesta a"
			.	" WHERE a.codigo = $1"
			.		" AND a.verificacion = $2");

  	$result = pg_execute($dbconn, "consulta", array($Codigo,$Clave));

        if (!$result)
          return json_encode(array("Error" => "-1000", "ErrorDescripcion" => "No se pudo Consultar la tirilla"));

	if ( ! ($row = pg_fetch_array($result)) )
		return json_encode(array("Error" => "-1000", "ErrorDescripcion" => "No se Encontro la tirilla"));

        if ($row["estado"] == "2") // No fue ganador
          return json_encode(array("PremioMonto" => "0"));

        if ($row["estado"] == "3") // No fue ganador
          return json_encode(array("PremioMonto" => $row["monto"],"DiasTranscurridos" => $row["diastranscurridos"]));

        if ($row["estado"] == "4") // ya fue cobrado
          return json_encode(array("PremioMonto" => "-1"));

	return json_encode(array("Error" => "-1000", "ErrorDescripcion" => "Error : No hay premio (V:" . $row["monto"] . ",E:" . $row["estado"] . ")"));
     }

     public static function TirillaConsultar($Sesion,$Codigo,$Clave)
     {
	global $dbconn;

	if ($Sesion != "" && !f_renovar_id($Sesion))
		return json_encode(array("Error" => "-1000", "ErrorDescripcion" => "No se pudo Renovar la sesion"));

	$result = pg_prepare($dbconn, "consulta",
				" SELECT a.estado"
			.		" ,CASE WHEN round(a.monto * a.factor_pagado) > 15000000 THEN 15000000 ELSE round(a.monto * a.factor_pagado) END as monto"
			.		" ,date('today') - date(instante_apuesta) as diastranscurridos"
			.		" ,CASE WHEN e.info_resultados IS NULL THEN '--' ELSE e.info_resultados END AS info_resultados"
			.		" ,CASE WHEN e.info_resultados IS NULL THEN 'Pendiente' WHEN ad.acerto THEN 'OK' ELSE 'Perdedor' END AS resultado"
			.		" ,e.deporte_id"
			.		" ,e.local"
			.		" ,e.visitante"
			.		" ,e.instante"
			.		" ,tr.descripcion"
			.	" FROM apuesta a"
			.		" ,apuesta_detalle ad"
			.		" ,encuentro e"
			.		" ,tipo_resultado tr"
			.	" WHERE a.codigo = $1"
			.		" AND a.verificacion = $2"
			.		" AND a.codigo = ad.apuesta"
			.		" AND ad.encuentro = e.codigo"
			.		" AND (ad.deporte,ad.resultado) = (tr.deporte,tr.tipo)");

  	$result = pg_execute($dbconn, "consulta", array($Codigo,$Clave));

        if (!$result)
          return json_encode(array("Error" => "-1000", "ErrorDescripcion" => "No se pudo Consultar la tirilla"));

	$arrayrespuesta=array();
	while ( ($row = pg_fetch_array($result)) )
	{
		array_push($arrayrespuesta,$row);
	}
//error_log(var_export($arrayrespuesta,true));
        return json_encode(array("Resultado" => $arrayrespuesta));
     }
     public static function PremioPagar($Sesion,$Codigo,$Clave)
     {
	global $dbconn;
	global $_max_duracion_premio_;

	if (!f_renovar_id($Sesion))
		return json_encode(array("Error" => "-1000", "ErrorDescripcion" => "No se pudo Renovar la sesion"));

	$result = pg_prepare($dbconn, "cambiarEstado",
				" UPDATE apuesta"
			.	" SET    estado = '4'"
//			.	" SET    estado = CASE WHEN doublechance THEN '5' ELSE '4' END"
			.	" WHERE  (codigo,verificacion,estado) = ($1::bigint,$2::text,'3')"
			.		" AND date(instante_apuesta) + $3::int4 >= date('today')"
			.	" RETURNING codigo");

  	$result = pg_execute($dbconn, "cambiarEstado", array($Codigo,$Clave,$_max_duracion_premio_));

        if (!$result)
          return json_encode(array("Error" => "-1000", "ErrorDescripcion" => "No se pudo Actualizar el estado"));

	if ( ! ($row = pg_fetch_array($result)) )
		return json_encode(array("Error" => "-1000", "ErrorDescripcion" => "No se Encontro el premio"));

        $codigoActualizado = $row["codigo"];

	$result = pg_prepare($dbconn, "AgregarPago",
				" INSERT INTO usuario_pago"
			.	" SELECT nextval('cons_pago')"
			.		" ,s.usuario as pagador"
			.		" ,a.usuario as cliente"
			.		" ,now()"
			.		" ,CASE WHEN round(a.monto * a.factor_pagado) > 15000000 THEN 15000000 ELSE round(a.monto * a.factor_pagado) END AS monto"
/*
			.		" ,CASE WHEN round(a.monto * CASE WHEN a.doublechance THEN ((a.factor_pagado - 1)/2)+1 ELSE a.factor_pagado END) > 15000000"
			.				" THEN 15000000"
			.				" ELSE round(a.monto * CASE WHEN a.doublechance THEN ((a.factor_pagado - 1)/2)+1 ELSE a.factor_pagado END)"
			.			" END AS monto"
*/
			.		" ,a.codigo AS premio_origen"
			.	" FROM   sesion s"
			.		" ,apuesta a"
			.	" WHERE s.id = $1"
			.		" AND a.codigo = ($2)" // ya no se revisa el estado ni la clave porque fue cambiado previamente
			);

  	$result = pg_execute($dbconn, "AgregarPago", array($Sesion,$codigoActualizado));

        if (!$result)
        {
          error_log("Esto es grave. No se pudo Grabar el Pago Realizado en la apuesta '" . $codigo . "'");
          return json_encode(array("Error" => "-1000", "ErrorDescripcion" => "No se pudo Grabar el Pago Realizado"));
        }

	return json_encode(array("Respuesta" => "1"));
     }
     public static function UsuarioCrear($Sesion,$UsuarioNombre,$UsuarioClave,$UsuarioAprobador)
     {
	global $dbconn;

	if (!f_renovar_id($Sesion))
		return json_encode(array("Error" => "-1000", "ErrorDescripcion" => "No se pudo Renovar la sesion"));

        if (!f_tiene_permiso(-3))
		return json_encode(array("Error" => "-1000", "ErrorDescripcion" => "No se tiene permiso para esta operación"));

/*
           Table "public.usuario"
 Column |          Type          | Modifiers
--------+------------------------+-----------
 codigo | bigint                 | not null
 login  | character varying(100) |
 clave  | character varying(100) |
                                  Table "public.usuario_carga"
     Column     |            Type             |                    Modifiers
----------------+-----------------------------+--------------------------------------------------
 codigo         | bigint                      | not null default nextval('cons_carga'::regclass)
 vendedor       | bigint                      |
 usuario        | bigint                      |
 instante       | timestamp without time zone |
 monto_original | double precision            |
 monto_restante | double precision            |
 premio_origen  | bigint                      |
*/
	$MinNombreUsuario = trim(strtolower($UsuarioNombre));
	$result = pg_prepare($dbconn, "UsuarioCrear",
					" INSERT INTO usuario"
				.		" (codigo,login,clave,aprobador)"
				.	" SELECT nextval('cons_usuario')"
				.		" ,$1::text"
				.		" ,md5($1::text||$2)"
				.		" ,$3::bigint"
				.	" WHERE NOT EXISTS (SELECT 1 FROM usuario WHERE login = $1::text)"
				.	" RETURNING codigo"
				);
  	if ( ! ($result = pg_execute($dbconn, "UsuarioCrear", array($MinNombreUsuario,$UsuarioClave,(($UsuarioAprobador == "")?null:$UsuarioAprobador))))
		|| ! ($row = pg_fetch_array($result)))
          return json_encode(array("Error" => "-1000", "ErrorDescripcion" => "No se pudo Crear el Usuario"));

	$codigo = $row["codigo"];

	$result = pg_prepare($dbconn, "UsuarioPermisoCrear",
					" INSERT INTO usuario_permiso"
				.	" SELECT $2"
				.		" ,t.p"
				.		" ,s.usuario"
				.		" ,now()"
				.	" FROM sesion s,(VALUES (-2),(-6)) t(p)"
				.	" WHERE s.id = $1"
				);
  	if ( ! ($result = pg_execute($dbconn, "UsuarioPermisoCrear", array($Sesion,$codigo))) )
          return json_encode(array("Error" => "-1000", "ErrorDescripcion" => "No se pudo asignar permisos"));

	return json_encode(array("UsuarioCodigo" => $codigo));
     }

	public static function Recargar($Sesion,$DestinoCodigo,$Monto,$Clave)
	{
		global $dbconn;

		if (!f_renovar_id($Sesion))
			return json_encode(array("Error" => "-1000", "ErrorDescripcion" => "No se pudo Renovar la sesion"));

		if (!f_tiene_permiso(-6))
			return json_encode(array("Error" => "-1000", "ErrorDescripcion" => "No tiene permiso para esta operación"));

		$disponible = f_ConsultarDisponible($Sesion);

		if (!$disponible){
			error_log("No se pudo consultar disponible");
			return json_encode(array("Error" => "-1000", "ErrorDescripcion" => "No se pudo consultar disponible"));
		}

		if ($disponible < $Monto){
			error_log("No tiene saldo suficiente para esta recarga");
			return json_encode(array("Error" => "-1000", "ErrorDescripcion" => "No tiene saldo suficiente para esta recarga"));
		}

		$result = pg_prepare($dbconn, "validarClave",
							" SELECT u.codigo"
						.	" FROM   usuario u"
						.		" ,sesion s"
						.	" WHERE  s.id = $1"
						.		" AND s.usuario = u.codigo"
						.		" AND u.clave = md5(u.login || $2)"
						.		" AND u.aprobador IS NULL");

		if (!$result)
			return json_encode(array("Error" => "-1", "ErrorDescripcion" => "No se pudo validar el usuario"));

		$result = pg_execute($dbconn, "validarClave", array($Sesion, $Clave));

		if ($$result || ! ($row = pg_fetch_array($result)) )
		{
			error_log("Error de clave : " . $Clave);
			return json_encode(array("Error" => "-1", "ErrorDescripcion" => "No se pudo validar el usuario Etapa2"));
		}


		if (!f_descontarDisponible($Sesion,$Monto))
			return json_encode(array("Error" => "-1000", "ErrorDescripcion" => "No se pudo descontar monto"));

		$result = pg_prepare($dbconn, "carga",
					" INSERT INTO usuario_carga"
				.		" (vendedor,usuario,instante,monto_original,monto_restante)"
				.	" SELECT s.usuario"
				.		" ,$2"
				.		" ,now()"
				.		" ,$3"
				.		" ,$3"
				.	" FROM sesion s"
				.	" WHERE s.id = $1"
				.	" RETURNING instante,codigo");

		$result = pg_execute($dbconn, "carga", array($Sesion,$DestinoCodigo,$Monto));

		if (!$result)
		{
			error_log("Monto a devolver... $" . $Monto);
			return json_encode(array("Error" => "-1000", "ErrorDescripcion" => "No se pudo Cargar el Monto, solicite la devolucion"));
		}

		if ( ! ($row = pg_fetch_array($result)) )
			return json_encode(array("Error" => "-1000", "ErrorDescripcion" => "No se pudo hacer la recarga"));

		return json_encode(array("Respuesta" => "1","codigoRecarga" => $row["codigo"]));
	}
     public static function UsuarioClaveCambiar($Sesion,$ClaveAnterior,$ClaveNueva)
     {
	global $dbconn;

	if (!f_renovar_id($Sesion))
		return json_encode(array("Error" => "-1000", "ErrorDescripcion" => "No se pudo Renovar la sesion"));

	$result = pg_prepare($dbconn, "UsuarioCambioClave",
					" UPDATE usuario"
				.	" SET    clave=md5(login||$3)"
				.	" FROM   sesion s"
				.	" WHERE  s.id = $1"
				.		" AND s.usuario = usuario.codigo"
				.		" AND usuario.clave = md5(login||$2)"
				.	" RETURNING codigo"
				);
	if ( ! ($result = pg_execute($dbconn, "UsuarioCambioClave", array($Sesion,$ClaveAnterior,$ClaveNueva))) )
		return json_encode(array("Error" => "-1000", "ErrorDescripcion" => "No se pudo preparar cambio de clave"));

	if ( ! ($row = pg_fetch_array($result)) )
		return json_encode(array("Error" => "-1000", "ErrorDescripcion" => "No se pudo cambiar la clave"));

	return json_encode(array("Respuesta" => "1"));
     }
     public static function InformeCargas($Sesion)
     {
	global $dbconn;

	if (!f_renovar_id($Sesion))
		return json_encode(array("Error" => "-1000", "ErrorDescripcion" => "No se pudo Renovar la sesion"));

	$result = pg_prepare($dbconn, "cargas",
				" SELECT uc.instante"
			.		" ,uc.monto_original as monto"
			.		" ,u.codigo || '-' || u.login AS usuario"
			.	" FROM usuario_carga uc"
			.		" ,sesion s"
			.		" ,usuario u"
			.	" WHERE s.id = $1"
			.		" AND s.usuario = uc.vendedor"
			.		" AND uc.instante >= date('yesterday')"
			.		" AND uc.usuario = u.codigo"
			.	" ORDER BY uc.instante DESC");

  	$result = pg_execute($dbconn, "cargas", array($Sesion));

        if (!$result)
          return json_encode(array("Error" => "-1000", "ErrorDescripcion" => "No se pudo consultar las Cargas"));

	$arrayrespuesta=array();
	while ( ($row = pg_fetch_array($result)) )
	{
		array_push($arrayrespuesta,$row);
	}

	return json_encode(array("Cargas" => $arrayrespuesta));
   }

     public static function EncuentrosPorMarcar($Sesion)
     {
	global $dbconn;

	if (!f_renovar_id($Sesion))
		return json_encode(array("Error" => "-1000", "ErrorDescripcion" => "No se pudo Renovar la sesion"));

/*
	$result = pg_prepare($dbconn, "encuentrosPorMarcar",
				" SELECT e.*"
			.               " ,count(1) as n"
			.       " FROM   encuentro e"
			.               " ,encuentro_resultados er"
			.               " ,tipo_resultado tr"
			.       " WHERE  e.codigo = er.encuentro"
			.               " AND er.resultado = tr.tipo"
			.               " AND er.ganador IS NULL"
			.		" AND e.info_resultados IS NULL"
			.       " GROUP BY e.codigo"
			.       " ORDER BY e.instante");

*/
	$result = pg_prepare($dbconn, "encuentrosPorMarcar",
				" SELECT e.*"
			.		" ,d.descripcion as deporte_desc"
			.               " ,COUNT(1) || ' apuestas' as n"
			.       " FROM   encuentro e"
			.		" ,deporte d"
			.		" ,apuesta_detalle ad"
			.       " WHERE  e.deporte_id = d.codigo"
			.		" AND d.codigo = '1'"
			.		" AND e.codigo = ad.encuentro"
			.		" AND e.info_resultados IS NULL"
			.		" AND e.instante < now() - '90 minutes'::interval"
			.		" AND e.instante >= '2017-04-01'"
			.	" GROUP BY e.deporte_id,e.codigo,d.descripcion"
			.       " ORDER BY COUNT(1) DESC"
			.	" LIMIT 40");
	$result = pg_execute($dbconn, "encuentrosPorMarcar", array());

	if (!$result)
		return json_encode(array("Error" => "-1000", "ErrorDescripcion" => "No se pudo Consultar los Encuentros Pendientes"));

	$arrayrespuesta=array();
	while ( ($row = pg_fetch_array($result)) )
	{
		array_push($arrayrespuesta,$row);
	}

	return json_encode(array("Encuentros" => $arrayrespuesta));
   }

//     public static function EncuentroMarcadorGrabar($Sesion,$Deporte,$Encuentro,$PTL,$PTV,$STL,$STV)
     public static function EncuentroMarcadorGrabar($Sesion,$Deporte,$Encuentro)
     {
	global $dbconn;

	if (!f_renovar_id($Sesion))
		return json_encode(array("Error" => "-1000", "ErrorDescripcion" => "No se pudo Renovar la sesion"));

        if (!f_tiene_permiso(-5)){
		error_log("EncuentroMarcadorGrabar() : No tiene permiso para esta operación");
		return json_encode(array("Error" => "-1000", "ErrorDescripcion" => "No tiene permiso para esta operación"));
	}

	if ($Deporte == 1
		&& isset($_POST["PTL"]) && $_POST["PTL"] == 'A'
		&& isset($_POST["PTV"]) && $_POST["PTV"] == 'A'
		&& isset($_POST["STL"]) && $_POST["STL"] == 'A'
		&& isset($_POST["STV"]) && $_POST["STV"] == 'A'
		){
		$result = pg_prepare($dbconn, "",
					" SELECT 1"
				.	" FROM  apuesta_detalle ad, apuesta a"
				.	" WHERE (ad.deporte,ad.encuentro) = ($1,$2)"
				.		" AND a.codigo = ad.apuesta"
				.		" AND a.estado not in ('1','-1')"
				);
		if ( ! ($result = pg_execute($dbconn, "", array($Deporte,$Encuentro))) )
			return json_encode(array("Error" => "-1000", "ErrorDescripcion" => "Error anulando encuentro(1)"));
		if ( ($row = pg_fetch_array($result)) ){
			error_log("EncuentroMarcadorGrabar() : No puede anular el encuentro (" . $Deporte . "," . $Encuentro . ") porque tiene apuestas en estados extranos");
			return json_encode(array("Error" => "-1000", "ErrorDescripcion" => "Ese encuentro no puede ser anulado"));
		}
		$result = pg_prepare($dbconn, "",
					" UPDATE apuesta"
				.	" SET factor_pagado = apuesta.factor_pagado / ad.factor_pagado"
				.	" FROM  apuesta_detalle ad"
				.	" WHERE encuentro = $1 AND apuesta.codigo = ad.apuesta"
				);
		if ( ! ($result = pg_execute($dbconn, "", array($Encuentro))) )
			return json_encode(array("Error" => "-1000", "ErrorDescripcion" => "Error anulando encuentro(2)"));
		$result = pg_prepare($dbconn, "",
					" DELETE FROM apuesta_detalle"
				.	" WHERE encuentro = $1"
				);
		if ( ! ($result = pg_execute($dbconn, "", array($Encuentro))) )
			return json_encode(array("Error" => "-1000", "ErrorDescripcion" => "Error anulando encuentro (3)"));

		error_log("Se anulo el encuentro (" . $Deporte . "," . $Encuentro . ")");

		return json_encode(array("Respuesta" => 1));
	}
	switch ($Deporte){
		case 2:
			if ( ! isset($_POST["T1_1"])
				|| ! isset($_POST["T1_2"])
				|| ! isset($_POST["T2_1"])
				|| ! isset($_POST["T2_2"])
				|| ! isset($_POST["T3_1"])
				|| ! isset($_POST["T3_2"])
				|| ! isset($_POST["T4_1"])
				|| ! isset($_POST["T4_2"])
				|| ! isset($_POST["T5_1"])
				|| ! isset($_POST["T5_2"])
				|| ! isset($_POST["T6_1"])
				|| ! isset($_POST["T6_2"])
				)
				return json_encode(array("Error" => "-1000", "ErrorDescripcion" => "No se Encontraron las variables necesarias para Basketball"));
			$T1_1 = $_POST["T1_1"]; $T1_2 = $_POST["T1_2"];
			$T2_1 = $_POST["T2_1"]; $T2_2 = $_POST["T2_2"];
			$T3_1 = $_POST["T3_1"]; $T3_2 = $_POST["T3_2"];
			$T4_1 = $_POST["T4_1"]; $T4_2 = $_POST["T4_2"];
			$T5_1 = $_POST["T5_1"]; $T5_2 = $_POST["T5_2"];
			$T6_1 = $_POST["T6_1"]; $T6_2 = $_POST["T6_2"];
			$result = pg_prepare($dbconn, "encuentroInfoResGrabar",
						" UPDATE encuentro"
					.	" SET info_resultados = 'T1(' || $4 || ',' || $5 || ')"
					.				",T2(' || $6 || ',' || $7 || ')"
					.				",T3(' || $8 || ',' || $9 || ')"
					.				",T4(' || $10 || ',' || $11 || ')"
					.				",T5(' || $12 || ',' || $13 || ')"
					.				",T6(' || $14 || ',' || $15 || ')"
					.				"'"
					.		" ,usuario_resultado = s.usuario"
					.	" FROM sesion s"
					.	" WHERE encuentro.codigo = $3"
					.		" AND encuentro.deporte_id = $2"
					.		" AND s.id = $1"
					);
			$result = pg_execute($dbconn, "encuentroInfoResGrabar", array($Sesion,$Deporte,$Encuentro
											,$_POST["T1_1"]
											,$_POST["T1_2"]
											,$_POST["T2_1"]
											,$_POST["T2_2"]
											,$_POST["T3_1"]
											,$_POST["T3_2"]
											,$_POST["T4_1"]
											,$_POST["T4_2"]
											,$_POST["T5_1"]
											,$_POST["T5_2"]
											,$_POST["T6_1"]
											,$_POST["T6_2"]
										));
			break;
		case 5:
			if ( ! isset($_POST["S1_1"])
				|| ! isset($_POST["S1_2"])
				|| ! isset($_POST["S2_1"])
				|| ! isset($_POST["S2_2"])
				|| ! isset($_POST["S3_1"])
				|| ! isset($_POST["S3_2"])
				|| ! isset($_POST["S4_1"])
				|| ! isset($_POST["S4_2"])
				|| ! isset($_POST["S5_1"])
				|| ! isset($_POST["S5_2"])
				|| ! isset($_POST["S6_1"])
				|| ! isset($_POST["S6_2"])
				|| ! isset($_POST["S7_1"])
				|| ! isset($_POST["S7_2"])
				|| ! isset($_POST["S8_1"])
				|| ! isset($_POST["S8_2"])
				|| ! isset($_POST["S9_1"])
				|| ! isset($_POST["S9_2"])
				|| ! isset($_POST["S10_1"])
				|| ! isset($_POST["S10_2"])
				)
				return json_encode(array("Error" => "-1000", "ErrorDescripcion" => "No se Encontraron las variables necesarias para Tennis"));
			$result = pg_prepare($dbconn, "encuentroInfoResGrabar",
						" UPDATE encuentro"
					.	" SET info_resultados = 'S1(' || $4 || ',' || $5 || ')"
					.				",S2(' || $6 || ',' || $7 || ')"
					.				",S3(' || $8 || ',' || $9 || ')"
					.				",S4(' || $10 || ',' || $11 || ')"
					.				",S5(' || $12 || ',' || $13 || ')"
					.				",S6(' || $14 || ',' || $15 || ')"
					.				",S7(' || $16 || ',' || $17 || ')"
					.				",S8(' || $18 || ',' || $19 || ')"
					.				",S9(' || $20 || ',' || $21 || ')"
					.				",S10(' || $22 || ',' || $23 || ')"
					.				"'"
					.		" ,usuario_resultado = s.usuario"
					.	" FROM sesion s"
					.	" WHERE encuentro.codigo = $3"
					.		" AND encuentro.deporte_id = $2"
					.		" AND s.id = $1"
					);
			$result = pg_execute($dbconn, "encuentroInfoResGrabar", array($Sesion,$Deporte,$Encuentro
											,$_POST["S1_1"],$_POST["S1_2"]
											,$_POST["S2_1"],$_POST["S2_2"]
											,$_POST["S3_1"],$_POST["S3_2"]
											,$_POST["S4_1"],$_POST["S4_2"]
											,$_POST["S5_1"],$_POST["S5_2"]
											,$_POST["S6_1"],$_POST["S6_2"]
											,$_POST["S7_1"],$_POST["S7_2"]
											,$_POST["S8_1"],$_POST["S8_2"]
											,$_POST["S9_1"],$_POST["S9_2"]
											,$_POST["S10_1"],$_POST["S10_2"]
										));
			break;
		default:
			if ( ! isset($_POST["PTL"])
				|| ! isset($_POST["PTV"])
				|| ! isset($_POST["STL"])
				|| ! isset($_POST["STV"])
				)
				return json_encode(array("Error" => "-1000", "ErrorDescripcion" => "No se Encontraron las variables necesarias para Futbol"));

			$result = pg_prepare($dbconn, "encuentroInfoResGrabar",
						" UPDATE encuentro"
					.	" SET info_resultados = 'PT(' || $4 || ',' || $5 || '),ST(' || $6 || ',' || $7 || ')'"
					.		" ,usuario_resultado = s.usuario"
					.	" FROM sesion s"
					.	" WHERE encuentro.codigo = $3"
					.		" AND encuentro.deporte_id = $2"
					.		" AND s.id = $1"
					);
			$result = pg_execute($dbconn, "encuentroInfoResGrabar", array($Sesion,$Deporte,$Encuentro
											,$_POST["PTL"]
											,$_POST["PTV"]
											,$_POST["STL"]
											,$_POST["STV"]
										));

			break;
	}

	if (!$result)
		return json_encode(array("Error" => "-1000", "ErrorDescripcion" => "No se pudo Consultar los Encuentros Pendientes"));
/*
	$result = pg_prepare($dbconn, "encuentrosMarcadorGrabar",
				" UPDATE encuentro_resultados"
			.	" SET ganador = t.b"
			.	" FROM (VALUES"
			.		" (  '(1,1)'," . ((((int)$STL >  (int)$STV)         ) ?"true":"false") . ")"
			.		",(  '(1,2)'," . ((((int)$STL == (int)$STV)         ) ?"true":"false") . ")"
			.		",(  '(1,3)'," . ((((int)$STL <  (int)$STV)         ) ?"true":"false") . ")"
			.		",(  '(2,1)'," . ((((int)$STL >= (int)$STV)         ) ?"true":"false") . ")"
			.		",(  '(2,2)'," . ((((int)$STL != (int)$STV)         ) ?"true":"false") . ")"
			.		",(  '(2,3)'," . ((((int)$STL <= (int)$STV)         ) ?"true":"false") . ")"
			.		",(  '(3,2)'," . ((((int)$STL +  (int)$STV) <  3    ) ?"true":"false") . ")"
			.		",(  '(3,4)'," . ((((int)$STL +  (int)$STV) >= 3    ) ?"true":"false") . ")"
			.		",(  '(4,1)'," . ((((int)$PTL >  (int)$PTV)         ) ?"true":"false") . ")"
			.		",(  '(4,2)'," . ((((int)$PTL == (int)$PTV)         ) ?"true":"false") . ")"
			.		",(  '(4,3)'," . ((((int)$PTL <  (int)$PTV)         ) ?"true":"false") . ")"
			.		",('(130,1)'," . ((((int)$STL > 0 && (int)$STV > 0) )?"true":"false") . ")"
			.		",('(130,2)'," . (((((int)$STL > 0 xor (int)$STV > 0) || ((int)$STL + (int)$STV)) == 0 )?"true":"false") . ")"

//			.		",('(130,)'," . (((int)STL < (int)STV)?"true":"false") . ")"
			.		") t(r,b)"
			.	" WHERE encuentro_resultados.encuentro = $1"
			.		" AND encuentro_resultados.resultado = t.r"
			);

	$result = pg_execute($dbconn, "encuentrosMarcadorGrabar", array($Encuentro));

	if (!$result)
		return json_encode(array("Error" => "-1000", "ErrorDescripcion" => "No se pudo Grabar los ganadores"));
*/
	return json_encode(array("Respuesta" => 1));
   }

   //public static function grabarapuesta($usuario,$monto,$apuesta,$encuentro)
   public static function grabarapuesta($ID,$monto,$datos)
	{
        global $dbconn;

//        return json_encode(array("Error" => "-9999", "ErrorDescripcion" => "Error : Plataforma suspendida"));
        $datos_d = json_decode($datos, true);

       if ($monto < 1)
          return json_encode(array("Error" => "-1000", "ErrorDescripcion" => "Error : el Monto debe ser mayor que cero"));

       if (!f_renovar_id($ID))
          return json_encode(array("Error" => "-1000", "ErrorDescripcion" => "No se pudo Renovar la sesion"));

	$disponible = f_ConsultarDisponible($ID);

        if ($disponible == null || $disponible < $monto){
		error_log("Error : Disponible es menor que el monto de apuesta");
		return json_encode(array("Error" => "-1", "ErrorDescripcion" => "Error : Disponible es menor que el monto"));
	}

        $result = pg_prepare($dbconn, "consultarCerrados",
                                " SELECT 1"
                        .       " FROM   encuentro e"
                        .       " WHERE  e.codigo = $1"
			.		" AND e.deporte_id = $2"
                        .               " AND e.instante < now()");

//   	foreach($result as $dato)
        foreach($datos_d as $dato)
	{
		$encuentro=$dato[0];
		$deporte=$dato[3];
		$result = pg_execute($dbconn, "consultarCerrados", array($encuentro,$deporte));
		if (!$result)
			return json_encode(array("Error" => "-1", "ErrorDescripcion" => "Error consultando horarios"));

		if ( ($row = pg_fetch_array($result)) ) // No deberia retornar nada
			return json_encode(array("Error" => "-1", "ErrorDescripcion" => "Error : Encuentro ya inicio"));
	}

        $result = pg_prepare($dbconn, "descontarDisponible",
                                " UPDATE usuario_carga"
			.	" SET    monto_restante = monto_restante - t.descontar"
			.	" FROM ("
			.		" SELECT uc1.codigo"
			.			" , CASE WHEN SUM(COALESCE(uc2.monto_restante,0)) >= $2 THEN 0"
			.					" WHEN $2 - SUM(COALESCE(uc2.monto_restante,0)) < uc1.monto_restante THEN $2 - SUM(COALESCE(uc2.monto_restante,0))"
			.				" ELSE uc1.monto_restante"
			.			" END AS descontar"
			.		" FROM sesion s"
			.			" LEFT OUTER JOIN usuario u ON s.usuario = u.codigo"
			.			" LEFT OUTER JOIN usuario_carga uc1 ON COALESCE(u.aprobador,u.codigo) = uc1.usuario"
			.			" LEFT OUTER JOIN usuario_carga uc2 ON uc1.usuario = uc2.usuario AND uc1.codigo > uc2.codigo"
                        .               " WHERE s.id = $1"
			.		" GROUP BY uc1.codigo,uc1.monto_restante"
			.		" HAVING SUM(COALESCE(uc2.monto_restante,0)) < $2"
			.		" ) t"
			.	" WHERE usuario_carga.codigo = t.codigo");

        $result = pg_execute($dbconn, "descontarDisponible", array($ID,$monto));

        if (!$result)
             return json_encode(array("Error" => "-1", "ErrorDescripcion" => "Error descontando el monto"));
        $result = pg_prepare($dbconn, "grabarapuesta",
                                      "insert into apuesta(codigo,usuario,monto,factor_pagado,instante_apuesta,estado,verificacion,doublechance) select nextval('conse_apuesta'), s.usuario,$2,0,now(),-10,substr(md5(now()::text || random()::text),1,4),false" .
                                                          " from sesion s where s.id=$1".
                                      " returning codigo");
        $result = pg_execute($dbconn, "grabarapuesta", array($ID,$monto));
/*
        $result = pg_prepare($dbconn, "grabarapuesta",
                                      "insert into apuesta(codigo,usuario,monto,factor_pagado,instante_apuesta,estado,verificacion) select nextval('conse_apuesta'), s.usuario,$2,0,now(),-10,substr(md5(now()::text || random()::text),1,4)".
                                                          " from sesion s where s.id=$1".
                                      " returning codigo");
        $result = pg_execute($dbconn, "grabarapuesta", array($ID,$monto));
*/

        if (!$result){
		error_log("Error grabando apuesta : " . pg_last_error($result));
		return json_encode(array("Error" => "-1", "ErrorDescripcion" => "Error en apuesta"));
	}
	$row = pg_fetch_array($result);
	$codigo=$row["codigo"];

	$result = pg_prepare($dbconn, "grabarapuestadetalle",
                                      "insert into apuesta_detalle  select $1,deporte,encuentro,resultado,factor_pagado * CASE WHEN factor_pagado < 0 THEN -1 ELSE 1 END,null,extra  FROM encuentro_resultados  WHERE (deporte,encuentro,resultado)=($2,$3,$4)". 
                                      " returning factor_pagado");
        $factorglobal=1;
   	foreach($datos_d as $dato)
	{
             $encuentro=$dato[0];
             $resultado=$dato[1];
             $deporte=$dato[3];

             $result = pg_execute($dbconn, "grabarapuestadetalle", array($codigo,$deporte,$encuentro,$resultado));

             if (!$result)
                   return json_encode(array("Error" => "-1", "ErrorDescripcion" => "Error en el detalle apuesta"));

             $row = pg_fetch_array($result);
             $factorglobal*=$row["factor_pagado"];
        }

	if ($factorglobal  < 1){
		error_log("El FactorPagado (" . $factorglobal . ") no puede ser menor que 1");
        	return json_encode(array("Error" => "-1000", "ErrorDescripcion" => "Error en factor pagado"));
	}

        $result = pg_prepare($dbconn, "grabarfactor",
                                      "update apuesta set  factor_pagado=$1  WHERE codigo=$2");
        $result = pg_execute($dbconn, "grabarfactor", array($factorglobal,$codigo));

        if (!$result)
             return json_encode(array("Error" => "-1", "ErrorDescripcion" => "Error en apuesta final"));

        $result = pg_prepare($dbconn, "grabarAutoAprobacion",
						" update apuesta set  estado=1"
					.	" FROM usuario u"
					.		" ,sesion s"
					.	" WHERE s.id = $1"
					.		" AND s.usuario = u.codigo"
					.		" AND u.aprobador IS NULL"
					.		" AND apuesta.codigo=$2"
					.	" RETURNING estado");
        $result = pg_execute($dbconn, "grabarAutoAprobacion", array($ID,$codigo));

        if (!$result){
		error_log("Error en autoaprobacion de apuesta : " . $codigo);
		return json_encode(array("Error" => "-1", "ErrorDescripcion" => "Error en auto aprobacion"));
	}

	if ( ! ($row = pg_fetch_array($result)) ){
		error_log("Apuesta " . $codigo . " Requiere aprobacion");
        	return json_encode(array("Resultado" => "1","codigoApuesta" => $codigo,"Pendiente" => "Aprobacion"));
	}


	error_log("Apuesta " . $codigo . " Aprobada directamente");
	return json_encode(array("Resultado" => "1","codigoApuesta" => $codigo));
      }

   public static function ApuestaAnular($sesion,$Apuesta)
	{
        global $dbconn;

       if (!f_renovar_id($sesion))
          return json_encode(array("Error" => "-1000", "ErrorDescripcion" => "No se pudo Renovar la sesion"));

        if (!f_tiene_permiso(-4))
		return json_encode(array("Error" => "-1000", "ErrorDescripcion" => "No tiene permiso para esta operación"));

        $result = pg_prepare($dbconn, "ApuestaAnular",
                                " UPDATE apuesta"
			.	" SET  estado = -1"
			.	" WHERE  codigo = $1"
			.	" RETURNING usuario,monto"
			);

        if ( ! ($result = pg_execute($dbconn, "ApuestaAnular", array($Apuesta)))
		|| ! ($row = pg_fetch_array($result)) )
        {
          error_log("No se pudo Anular la Apuesta : '" . $sesion . "','" . $Apuesta . "'");
          return json_encode(array("Error" => "-1", "ErrorDescripcion" => "No se pudo Anular la Apuesta"));
        }

        $usuario = $row["usuario"];
	$monto = $row["monto"];

        $result = pg_prepare($dbconn, "ReponerDinero",
                                " UPDATE usuario_carga"
			.	" SET    monto_restante = monto_restante + $2"
			.	" FROM  (SELECT uc.codigo FROM usuario_carga uc,usuario u"
			.			" WHERE u.codigo = $1 AND COALESCE(u.aprobador,u.codigo) = uc.usuario"
			.			" ORDER BY instante desc LIMIT 1) t"
			.	" WHERE  usuario_carga.codigo = t.codigo"
			.	" RETURNING usuario_carga.codigo"
			);

        if ( ! ($result = pg_execute($dbconn, "ReponerDinero", array($usuario,$monto)))
		|| ! ($row = pg_fetch_array($result)) )
        {
          error_log("No se pudo Reponer el dinero : '" . $sesion . "','" . $Apuesta . "', Usuario : '" . $usuario . "', Monto : '" . $monto . "'");
          return json_encode(array("Error" => "-1", "ErrorDescripcion" => "No se pudo Reponer el dinero"));
        }
	error_log("Anulada Apuesta " . $Apuesta . " por id " . $sesion);
        return json_encode(array("Resultado" => "1","MontoReposicion" => $monto));
     }

     public static function UsuarioRegistrar($Login,$Clave,$PrimerNombre,$SegundoNombre,$PrimerApellido,$SegundoApellido,$EMail,$LimiteDiario,$LimiteSemanal,$LimiteMensual)
     {
        global $dbconn;
        global $_duracion_sesion_;

        $MinNombreUsuario = trim(strtolower($Login));
        $ClavePulida = trim(strtolower($Clave));

        if (strlen($MinNombreUsuario) < 3)
            return json_encode(array("Error" => "-1", "ErrorDescripcion" => "El Nombre de usuario debe tener minimo 3 caracteres"));

        if (strlen($ClavePulida) < 8)
            return json_encode(array("Error" => "-1", "ErrorDescripcion" => "La clave debe tener minimo 8 caracteres"));

        $result = pg_prepare($dbconn, "crear_usuario",
					" INSERT INTO usuario"
				.		" ("
				.			" codigo"
				.			" ,login"
				.			" ,clave"
				.			" ,primer_nombre"
				.			" ,segundo_nombre"
				.			" ,primer_apellido"
				.			" ,segundo_apellido"
				.			" ,correo"
				.			" ,limite_diario"
				.			" ,limite_semanal"
				.			" ,limite_mensual"
				.		" )"
				.	" SELECT NEXTVAL('cons_usuario')"
				.		" ,$1::text"
				.		" ,$1::text || $2"
				.		" ,$3"
				.		" ,$4"
				.		" ,$5"
				.		" ,$6"
				.		" ,$7"
				.		" ,$8"
				.		" ,$9"
				.		" ,$10"
				.	" FROM   (select 1) AS t"
//				.	" LEFT OUTER JOIN (SELECT MAX(codigo) as ncod FROM usuario u) AS t2 ON 1=1"
				.	" WHERE  NOT EXISTS (SELECT 1 FROM usuario WHERE login = $1)");

        $result = pg_execute($dbconn, "crear_usuario", array(
								$MinNombreUsuario
								,$ClavePulida
								,$PrimerNombre
								,$SegundoNombre
								,$PrimerApellido
								,$SegundoApellido
								,$EMail
								,$LimiteDiario
								,$LimiteSemanal
								,$LimiteMensual
								)
				);

        if (!$result)
          return json_encode(array("Error" => "-1", "ErrorDescripcion" => "No se pudo registrar el usuario"));

        if (pg_affected_rows($result) < 1){
		error_log("Posiblemente ya existe login '" . $MinNombreUsuario . "', correo '" . $EMail . "'");
          return json_encode(array("Error" => "-1", "ErrorDescripcion" => "No se pudo crear el usuario, posiblemente ya existe"));
	}

        $result = pg_prepare($dbconn, "encriptar_clave",
					" UPDATE usuario"
				.	" SET    clave = md5(clave)"
				.	" WHERE  login = $1"
				.		" AND clave like login || '%'"
				.	" RETURNING codigo");

        $result = pg_execute($dbconn, "encriptar_clave", array($MinNombreUsuario));

        if (!$result)
          return json_encode(array("Error" => "-1", "ErrorDescripcion" => "No se pudo activar la clave pre"));

        if ( ! ($row = pg_fetch_array($result)) )
          return json_encode(array("Error" => "-1", "ErrorDescripcion" => "No se pudo activar la clave"));

        return json_encode(array("Retorno" => "1","UsuarioCodigo" => $row["codigo"]));
     }

     public static function SeguridadSesionCrear($Login,$Clave)
     {
        global $dbconn;
        global $_duracion_sesion_;

        $MinNombreUsuario = strtolower($Login);
// Para poner la pagina en modo mantenimiento descomente las dos lineas siguientes
//if ($MinNombreUsuario != "lostesos")
//  return json_encode(array("Error" => "-1", "ErrorDescripcion" => "Sistema en mantenimiento... Intente mas tarde"));


        if ($MinNombreUsuario != "")
        {
          $result = pg_prepare($dbconn, "listado",
              " SELECT u.codigo" .
                    " ,md5($1 || now()) as nid" .
              " FROM   usuario u" .
              " WHERE  u.login = $1" .
                 " AND u.clave = md5(u.login || $2)");

          if (!$result)
            return json_encode(array("Error" => "-1", "ErrorDescripcion" => "No se pudo validar el usuario"));
//error_log("u : " . $MinNombreUsuario . " c : " . $Clave);

          $result = pg_execute($dbconn, "listado", array($MinNombreUsuario, $Clave));
        }
        else
        {
          $result = pg_prepare($dbconn, "listado",
              " SELECT u.codigo" .
                    " ,md5($1 || now()) as nid" .
              " FROM   usuario u" .
              " WHERE  u.clave = $1");

          if (!$result)
            return json_encode(array("Error" => "-1", "ErrorDescripcion" => "(2) No se pudo validar el usuario"));

          $result = pg_execute($dbconn, "listado", array($Clave));
        }

        if (!$result)
          return json_encode(array("Error" => "-1", "ErrorDescripcion" => "No se pudo validar el usuario"));

        $numrows = pg_num_rows($result);

        if ($numrows < 1)
          return json_encode(array("Error" => "-1001", "ErrorDescripcion" => "Usuario-Clave incorrectos"));

        $row = pg_fetch_array($result);

        $el_codigo = $row["codigo"];
        $el_id = $row["nid"];

        $result = pg_prepare($dbconn, "eliminar_id_viejos",
              " DELETE FROM sesion" .
              " WHERE  instante_fin < date('yesterday')" .
                 " AND COALESCE(instante_fin_cookie,now()) <= now()");

        $result = pg_execute($dbconn, "eliminar_id_viejos", array());

        if (!$result)
          return json_encode(array("Error" => "-1", "ErrorDescripcion" => "No se pudo limpiar el historial de sesiones"));

        $result = pg_prepare($dbconn, "crear_id",
              " INSERT INTO sesion" .
              " VALUES ($1,$2,NOW(),NOW() + ($3 || ' minutes')::interval)");

        $result = pg_execute($dbconn, "crear_id", array($el_id,$el_codigo,$_duracion_sesion_));

        if (!$result)
          return json_encode(array("Error llllllll".$el_id."lllll".$el_codigo."-lllll-".$_duracion_sesion_ => "-llllll-  -1", "ErrorDescripcion" => "No se pudo crear la sesion"));

        return json_encode(array("Token" => $el_id));
     }

     public static function SeguridadSesionMD5Crear($MD5Auth)
     {
       return self::SeguridadSesionCrear("",$MD5Auth);
     }

     public static function SeguridadSesionRenovar($Token)
     {
        if(!f_renovar_id($Token,true))
          return json_encode(array("Error" => "-1000", "ErrorDescripcion" => "No se pudo Renovar la sesion"));
     }

   public static function ApuestasPendientes($sesion)
	{
		global $dbconn;

		if (!f_renovar_id($sesion))
			return json_encode(array("Error" => "-1000", "ErrorDescripcion" => "No se pudo Renovar la sesion"));

		$result = pg_prepare($dbconn, "",
					" SELECT a.*"
				.	" FROM   sesion s"
				.		" ,usuario u"
				.		" ,apuesta a"
				.	" WHERE  s.id = $1"
				.		" AND s.usuario = u.aprobador"
				.		" AND u.codigo = a.usuario"
				.		" AND a.estado = '-10'"
				);

		if ( ! ($result = pg_execute($dbconn, "", array($sesion))) )
		{
			error_log("No se pudo consultar Apuestas pendientes");
			return json_encode(array("Error" => "-1", "ErrorDescripcion" => "No se pudo consultar Apuestas pendientes"));
		}


		$arrayapuestas = array();
		while (  ($row = pg_fetch_array($result)) ){
			array_push($arrayapuestas
					,array("codigo" => $row["codigo"]
						,"monto" => $row["monto"]
						,"factorPagado" => $row["factor_pagado"]
						,"instanteApuesta" => $row["instante_apuesta"]
						)
					);
		}
		return json_encode(array("Resultado" => "1","Apuestas" => $arrayapuestas));
	}

   public static function ApuestaAprobar($sesion,$Codigo,$Decision)
	{
		global $dbconn;

		if (!f_renovar_id($sesion))
			return json_encode(array("Error" => "-1000", "ErrorDescripcion" => "No se pudo Renovar la sesion"));

		$result = pg_prepare($dbconn, "",
					" UPDATE apuesta"
				.	" SET estado = $3::text"
				.	" FROM   sesion s"
				.		" ,usuario u"
				.	" WHERE  s.id = $1"
						// si se escribe con coalesce pierdo el uso de indices
				.		" AND (s.usuario = u.aprobador OR (u.aprobador IS NULL AND s.usuario = u.codigo))"
				.		" AND apuesta.codigo = $2"
				.		" AND apuesta.estado = '-10'"
				.		" AND apuesta.usuario = u.codigo"
				.		" AND ( $3::text = '-1' OR NOT EXISTS(SELECT 1 FROM apuesta_detalle ad,encuentro e WHERE ad.apuesta = $2 AND (ad.deporte,ad.encuentro) = (e.deporte_id,e.codigo) AND e.instante < now()))"
				.	" RETURNING apuesta.usuario,apuesta.monto"
				);

		if ( ! ($result = pg_execute($dbconn, "", array($sesion,$Codigo,(($Decision == "1")?"1":"-1")  ))) )
		{
			error_log("No se pudo actualizar la Aprobacion de Apuesta");
			return json_encode(array("Error" => "-1", "ErrorDescripcion" => "No se pudo actualizar la Aprobacion de Apuesta"));
		}

		if ( ! ($row = pg_fetch_array($result)) ){
			error_log("Intento de ROBO aprobando con partidos iniciados : Decision : " . $Decision . " apuesta " . $Codigo . " id " . $sesion);
			return json_encode(array("Error" => "-1", "ErrorDescripcion" => "La Apuesta no se puede actualizar, ya iniciaron los partidos?"));
		}


		if ($Decision == "1")
			error_log("Apuesta " . $Codigo . " fue aprobada");
		else{
			$usuario = $row["usuario"];
			$monto = $row["monto"];

	        	$result = pg_prepare($dbconn, "ReponerDinero",
        	        	                " UPDATE usuario_carga"
					.	" SET    monto_restante = monto_restante + $2"
					.	" FROM  (SELECT uc.codigo FROM usuario_carga uc,usuario u"
					.			" WHERE u.codigo = $1 AND COALESCE(u.aprobador,u.codigo) = uc.usuario"
					.			" ORDER BY instante desc LIMIT 1) t"
					.	" WHERE  usuario_carga.codigo = t.codigo"
					.	" RETURNING usuario_carga.codigo"
					);

			if ( ! ($result = pg_execute($dbconn, "ReponerDinero", array($usuario,$monto)))
				|| ! ($row = pg_fetch_array($result)) )
			{
				error_log("No se pudo Reponer el dinero : '" . $sesion . "','" . $Codigo . "', Usuario : '" . $usuario . "', Monto : '" . $monto . "'");
				return json_encode(array("Error" => "-1", "ErrorDescripcion" => "No se pudo Reponer el dinero"));
			}
			error_log("Apuesta " . $Codigo . " fue rechazada");
			return json_encode(array("Resultado" => "1"));
		}

		return json_encode(array("Resultado" => "1","codigoApuesta" => $Codigo));
	}

   public static function RecargaTemporal($ID,$Monto)
	{
		global $dbconn;

		if (!f_renovar_id($ID))
			return json_encode(array("Error" => "-1000", "ErrorDescripcion" => "No se pudo Renovar la sesion"));

		$result = pg_prepare($dbconn, "",
						" INSERT INTO usuario_carga"
					.		" (usuario,monto_original,monto_restante)"
					.	" SELECT sesion.usuario"
					.		" ,$2"
					.		" ,$2"
					.	" FROM sesion"
					.	" WHERE sesion.id = $1"
					.	" RETURNING *"
				);

		if ( ! ($result = pg_execute($dbconn, "", array($ID,$Monto))) ) {
			error_log("No se pudo grabar la recarga");
			return json_encode(array("Error" => "-1", "ErrorDescripcion" => "No se pudo grabar la recarga"));
		}

		if ( ! ($row = pg_fetch_array($result)) ){
			error_log("No se encontro la sesion para grabar recarga temporal : $" . $Monto . " - " . $ID);
			return json_encode(array("Error" => "-1", "ErrorDescripcion" => "No se pudo grabar la recarga"));
		}

		return json_encode(array("Error" => "-1", "ErrorDescripcion" => "No se pudo enviar el mensaje"));

		return json_encode(array("Resultado" => "1","codigoRecarga" => $row["codigo"]));
	}
   public static function IniciarPagoPayPal($ID,$Monto){
		$p = new _PayPal();

		error_log("PayPal : el ID " . $ID . " solicita pago de monto " . $Monto);
		if ( ! $p->SolicitarToken() ){
			error_log("PayPal Error : No se pudo conectar con PayPal");
			return json_encode(array("Error" => "-1", "ErrorDescripcion" => "No se pudo conectar con PayPal, solicite soporte"));
		}
/*
		if ( ! $p->IniciarPago("Fact00001",1.0,0.1,0.1,0.1,0,0.1,"USD") ){
			error_log("PayPal Error : No se obtiene un numero de transaccion");
			return json_encode(array("Error" => "-1", "ErrorDescripcion" => "No se pudo conectar con PayPal (2), solicite soporte"));
		}
*/
		if ( ! $p->CrearNuevaOrden(10,"USD") ){
			error_log("PayPal Error : No se obtiene un numero de transaccion");
			return json_encode(array("Error" => "-1", "ErrorDescripcion" => "No se pudo conectar con PayPal (2), solicite soporte"));
		}

		error_log("PayPal : La transaccion es " . _PayPal::$MiTransaccionID . " URL " . _PayPal::$MiURLConfirmacion);
		return json_encode(array("Resultado" => "1", "Transaccion" => _PayPal::$MiTransaccionID, "URL" => _PayPal::$MiURLConfirmacion));
	}

}

$rest = new RestServer("Apuestas");
$rest->handle();

?>
