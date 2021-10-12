<?php
  include_once("../../componentes/traduccion.inc");
?>
var cliente_http = f_http_inicializar_cliente();
var el_timer = setInterval("f_timer()",300000);
//var el_timer = setInterval("f_timer()",60000);
// Sesion
var v_cwf_id = false;
var EncuentroSeleccionado = false;
// Que pagina se esta dibujando en el area de trabajo
//     Encuentros : Se dibujan los encuentros
//     EncuentroDetalle : Se dibujan todas las opciones para un encuentro especifico, el codigo debe estar en EncuentroSeleccionado
//     Registro : Se dibuja el formulario de registro
var v_PaginaActual = "Encuentros";
// Contador de sincronizaciones
var nCargas = 0;
var v_js_deporte = 1;
var v_js_soloDeHoy = false;
var v_js_soloMarcadorDirecto = false;
var apostadoGlobal = new Array();
var apostadoMonto = 0;
var apostadoMaximo = 0;
var apostadoFactor = 1;
var infoUsuario = false;
var GlobalFaltanDirectos = true;
var FiltroPais = "";
var FiltroLiga = "";

var UltimoListadoEncuentros = false;
var UltimoListadoTipos = false;

var OpcionesAdmin="";

var TabSeleccionado = 0;

var EncuentroExpandido = false;

function el_id(id){
	if (document.getElementById)
		return document.getElementById(id);
	else if (window[id])
		return window[id];
	return null;
}

function f_timer()
{
console.log("f_timer()");
  if (nCargas < 0) // deshabilitado por un tiempo
  {
    nCargas++;
    return;
  }
  if ((nCargas % 10) == 0)
    f_js_CargarDatosUsuario();
  else
    f_js_Cargar();
  nCargas++;
}

function f_js_CargarDatosUsuario()
{
/*
  if (el_id("encuentros") == null)
    return;
*/
  if ( ! v_cwf_id )
  {
    f_js_Cargar();
    return;
  }
  f_http_post(cliente_http
             ,"SportSiete.php"
             ,"method=datosUsuario"
                + "&Sesion=" + v_cwf_id
             ,f_js_CargarDatosUsuario_Respuesta,5000);
}

function f_js_CargarDatosUsuario_Respuesta()
{
  var el_div = el_id("encuentros");
  var json_resp = f_js_procesar_respuesta_generica(cliente_http,true);
  if (json_resp)
  {
//console.log(json_resp);
    if (json_resp.ErrorDescripcion)
    {
      alert(json_resp.ErrorDescripcion);
    }
    else if (json_resp.Usuario)
    {
      infoUsuario = json_resp;
      if (infoUsuario.Usuario && infoUsuario.Usuario["disponible"])
        apostadoMaximo = infoUsuario.Usuario["disponible"];
      var elBoton = el_id("BotonAbrirAutenticacion");
      if (elBoton != null)
        el_id("BotonAbrirAutenticacion").style.display = "none";
      f_js_dibujarAreaApuesta();
/*
      apostadoMaximo = parseFloat(json_resp.Usuario["disponible"]);
      if (parseFloat(apostadoMonto) > apostadoMaximo || apostadoMonto == 0)
      {
        apostadoMonto = 5000;
        f_js_dibujarApostado();
      }
      if ( (el_div = el_id("ZonaPerfil")) == null)
        alert("No se puede dibujar el Perfil");
      else
      {
        el_div.style.display = "block";
        if ( (el_div = el_id("PerfilNombre")) == null)
          alert("No se puede dibujar el nombre del usuario");
        else
          el_div.innerHTML=json_resp.Usuario["login"];
        if ( (el_div = el_id("PerfilNickName")) == null)
          alert("No se puede dibujar el codigo del usuario");
        else
          el_div.innerHTML="Cod.:" + json_resp.Usuario["codigo"];
        if ( (el_div = el_id("PerfilUsuario")) == null)
          alert("No se puede dibujar el saldo del usuario");
        else
        {
          el_div.innerHTML="$ " + json_resp.Usuario["disponible"];
        }
      }
      if ((el_div = el_id("SaldoDisponible")) != null)
      {
        if (json_resp.Usuario.disponible != null)
        {
          el_div.innerHTML = apostadoMaximo;
        }
        else
          el_div.innerHTML = "0";
      }
      if ((el_div = el_id("CodigoCliente")) != null)
      {
        if (json_resp.Usuario.codigo != null)
          el_div.innerHTML = json_resp.Usuario["codigo"] + "-" + json_resp.Usuario["login"];
        else
          el_div.innerHTML = "X";
      }
*/
    }
    else
    {
    alert("<?php echo f_Traducir("La respuesta no contiene datos del usuario"); ?>");
    }
    f_js_Cargar();
  }
}

function f_js_dibujarAreaApuesta(){
  var i,j;
  var tmp_txt;
  // apostadoMaximo = parseFloat(infoUsuario.Usuario["disponible"]);
  if (parseFloat(apostadoMonto) > apostadoMaximo || apostadoMonto == 0 )
  {
    if (parseFloat(apostadoMonto) != 5000){
      apostadoMonto = 5000;
      f_js_dibujarApostado();
      return;
    }
  }
  if ( (el_div = el_id("ZonaPerfil")) == null)
    alert("<?php echo f_Traducir("No se puede dibujar el Perfil"); ?>");
  else
  {
    el_div.style.display = "block";
    if ( (el_div = el_id("AreaApuesta")) != null)
      el_div.style.width = "18em";
    if ( (el_div = el_id("TableInfoPerfil")) != null)
      el_div.style.width = "14em";
    if ( (el_div = el_id("PerfilNombre")) == null)
      alert("<?php echo f_Traducir("No se puede dibujar el nombre del usuario"); ?>");
    else
      el_div.innerHTML=infoUsuario.Usuario["login"];
    if ( (el_div = el_id("PerfilNickName")) == null)
      alert("<?php echo f_Traducir("No se puede dibujar el codigo del usuario"); ?>");
    else
      el_div.innerHTML="Cod.:" + infoUsuario.Usuario["codigo"];
    if ( (el_div = el_id("PerfilUsuario")) == null)
      alert("<?php echo f_Traducir("No se puede dibujar el saldo del usuario"); ?>");
    else
    {
//      el_div.innerHTML="$ " + infoUsuario.Usuario["disponible"];
//      el_div.innerHTML="Disponible $ " + apostadoMaximo;
      el_div.innerHTML="<?php echo f_Traducir("Disponible"); ?> $ " + infoUsuario.Usuario["disponible"];
    }
  }
  if ((el_div = el_id("SaldoDisponible")) != null)
  {
    if (infoUsuario.Usuario.disponible != null)
    {
      el_div.innerHTML = apostadoMaximo;
    }
    else
      el_div.innerHTML = "0";
  }
  if ((el_div = el_id("CodigoCliente")) != null)
  {
    if (infoUsuario.Usuario.codigo != null)
      el_div.innerHTML = infoUsuario.Usuario["codigo"] + "-" + infoUsuario.Usuario["login"];
    else
      el_div.innerHTML = "X";
  }
  if ((el_div = el_id("DetalleItemsApostados")) != null)
  {
    var totalPagado = 1;
    tmp_txt = "";
    for (i = 0; i < apostadoGlobal.length; i++){
		for (j=UltimoListadoEncuentros.length - 1;j >= 0 && UltimoListadoEncuentros[j].codigo != apostadoGlobal[i][0];j--)
			;
		if (j >= 0){
			tmp_txt += UltimoListadoEncuentros[j].local + " vs " + UltimoListadoEncuentros[j].visitante + "<br>" + apostadoGlobal[i][2];
			totalPagado *= parseFloat(apostadoGlobal[i][2]);
		} else {
			tmp_txt += apostadoGlobal[i][0];
		}
		tmp_txt += "<br>";
	}
    tmp_txt += "<?php echo f_Traducir("Paga"); ?> : " + (Math.floor(totalPagado * 100)/100);
    el_div.innerHTML = tmp_txt;
//    if (infoUsuario.Usuario.codigo != null)
//      el_div.innerHTML = infoUsuario.Usuario["codigo"] + "-" + infoUsuario.Usuario["login"];
//    else
//      el_div.innerHTML = "X";
  }
  if ((el_div = el_id("ZonaInputMontoApostado")) != null){
    if (apostadoGlobal.length > 0){
      el_div.style.display="";
    }else{
      el_div.style.display="none";
    }
    if (document.frm_global.InputMontoApostado.value == ""){
      if ( ! isNaN(apostadoMonto) )
        document.frm_global.InputMontoApostado.value = apostadoMonto;
    }
  }
  if ((el_div = el_id("ZonaTextoMontoPagado")) != null){
    if (apostadoGlobal.length > 0){
      el_div.style.display="";
      if ((el_div = el_id("TextoMontoPagado")) != null){
        if ( ! isNaN(apostadoMonto) )
          el_div.innerHTML = Math.floor((apostadoMonto * (Math.floor(totalPagado * 100)/100))*100)/100;
        else
          el_div.innerHTML = "-";
      }
    }else{
      el_div.style.display="none";
    }
  }
}

function f_js_Cargar()
{
/*
  if (el_id("encuentros") == null)
    return;
*/
  f_http_post(cliente_http
             ,"SportSiete.php"
             ,"method=datos3"
                + "&Deporte=" + v_js_deporte
//              + "&Sesion=" + v_cwf_id
             ,f_js_Cargar_Respuesta,5000);
}

function f_js_Cargar_Respuesta()
{
  var json_resp = f_js_procesar_respuesta_generica(cliente_http,true);
  if (json_resp)
  {
    if (json_resp.ErrorDescripcion)
    {
      alert(json_resp.ErrorDescripcion);
    }
    else if (json_resp.Encuentros)
    {
      UltimoListadoEncuentros = json_resp.Encuentros;
      UltimoListadoTipos = json_resp.Tipos;
      f_js_Redibujar();
/*
      f_js_ListarEncuentros();
      for (j = 0; j < apostadoGlobal.length; j++)
        apostadoGlobal[j][3] = false;
      setTimeout("f_js_dibujarApostado()",300);
*/
      return;
    }
    else
    {
      console.log(json_resp);
      alert("<?php echo f_Traducir("No se pueden descargar los encuentros"); ?>");
    }
  }
}

function f_js_procesar_respuesta_generica(pClienteHttp,pMostrarMensajes)
{
  if (pClienteHttp)
  {
    switch (pClienteHttp.readyState)
    {
      case 4 :
        var respuesta = pClienteHttp.responseText;
        if (respuesta != "")
        {
          var json_resp = JSON.parse(respuesta);
          if(! json_resp)
          {
            if (pMostrarMensajes)
              alert("Hubo un problema guardando su busqueda.\n" + respuesta);
            return false;
          }
          if(json_resp.ErrorDescripcion)
          {
            if (pMostrarMensajes)
              alert("Error : " + json_resp.ErrorDescripcion);
            return false;
          }
          return json_resp;
        }
        if (pMostrarMensajes)
        {
          console.log("Error : No se reconoce la respuesta '" + respuesta + "'");
//          f_js_CargarPagina("./");
//          alert("No se reconoce la respuesta '" + respuesta + "'");
        }
        break;

      case 1:
      case 2:
      case 3:
        return false;

      default:
        alert("estado : " + pClienteHttp.readyState);
        break;
    }
  }
  return false;
}

function f_js_ListarEncuentrosDeporte(pdeporte){
	var txtEncuentros = "";
	var tmpLiga = "";
	for (i = 0; i < UltimoListadoEncuentros.length; i++){
		if (pdeporte == UltimoListadoEncuentros[i].deporte_id){
			if (tmpLiga != UltimoListadoEncuentros[i].liga_id){
				tmpLiga = UltimoListadoEncuentros[i].liga_id;
				txtEncuentros += "<div class='AreaPaginaCompleta' style='font-size:1.7em;color:#fcc;background-color:rgba(23,115,58,0.7);'>"
				+			UltimoListadoEncuentros[i].liga
				+		"</div>"
				;
			}
			txtEncuentros += "<div class='AreaPaginaCompleta' style='background-color:rgba(84,164,65,0.3);cursor:pointer;'"
			+			((!infoUsuario)?"":" onClick='f_js_DetalleEncuentro(\"" + UltimoListadoEncuentros[i].codigo + "\")'")
			+			">"
			+			UltimoListadoEncuentros[i].local
			+			" vs "
			+			UltimoListadoEncuentros[i].visitante
			+			" "
			+			UltimoListadoEncuentros[i].instante
			+		"</div>"
			;
		}
	}
	return txtEncuentros;
}

function f_js_Redibujar(){
	switch(v_PaginaActual){
		case "Encuentros":
			el_id("AreaTrabajo").innerHTML="Recargando...";
			f_js_ListarEncuentros();
			break;
		case "EncuentroDetalle":
			el_id("AreaTrabajo").innerHTML="Recargando...";
			f_js_DibujarEncuentroDetalle();
			break;
		case "Registro":
			f_js_DibujarRegistro();
			break;
		case "RegistroGrabar":
			f_js_RegistroCrear();
			break;
		case "Recargar":
			f_js_DibujarRecarga();
			break;
		case "RecargarGrabar":
			alert("<?php echo f_Traducir("Grabando recarga"); ?>");
			break;
		default:
			el_id("AreaTrabajo").innerHTML="Recargando (2) ...";
			break;
	}
	setTimeout("f_js_dibujarApostado()",300);
}

function f_js_ListarEncuentros(){
	var divTrabajo = el_id("AreaTrabajo");
	var nombreDeporte = v_js_deporte;

	if (v_PaginaActual != "Encuentros"){
		alert("f_js_ListarEncuentros() : No deberia ejecutarse, pagina actual : " + v_PaginaActual);
		return;
	}

	switch(v_js_deporte){
		case 1 : nombreDeporte = "FUTBOL";break;
		default : nombreDeporte = "Deporte : " + v_js_deporte; break;
	}

	var txtEncuentros = "<div class='AreaPaginaCompleta'>"
	+			"<div class='AreaMitadDePagina BackgroundBotonAmarilloDegrade'>algo</div>"
	+		"</div>"
	;
	txtEncuentros += "<div class='AreaPaginaCompleta BackgroundBotonAmarilloDegrade' style='letter-spacing:1em;font-style:normal;font-weight:700;vertical-align:middle;'>"
	+			nombreDeporte
	+		"</div>"
	;
	txtEncuentros += "<div class='AreaPaginaCompleta'>";
	txtEncuentros += f_js_ListarEncuentrosDeporte(v_js_deporte);
	txtEncuentros += "</div>";
	divTrabajo.innerHTML = txtEncuentros;
}

function f_js_dibujarApostado(){
	var tabApuesta = el_id("TableInfoPerfil");
	if ( ! v_cwf_id ){
		TableInfoPerfil.innerHTML =
							"<tr><td colspan=2><?php echo f_Traducir('Iniciar Sesi&oacute;n'); ?></td></tr>"
		+					"<tr><td colspan=2><input name=SSUsuario type=text placeHolder='<?php echo f_Traducir('Usuario'); ?>'></td></tr>"
		+					"<tr><td colspan=2><input name=SSClave type=password placeHolder='<?php echo f_Traducir('Clave'); ?>'></td></tr>"
		+					"<tr><td colspan=2 style='font-size:0.7em;'><?php echo f_Traducir('Olvid&oacute; su clave'); ?></td></tr>"
		+					"<tr>"
		+						"<td style='cursor:pointer;' onClick='f_js_Autenticar(document.frm_global.SSUsuario.value,document.frm_global.SSClave.value)' title='<?php echo f_Traducir('Ingresar a la plataforma'); ?>'><?php echo f_Traducir('Entrar'); ?></td>"
		+						"<td style='cursor:pointer;' onClick='f_js_CambiarPaginaActual(\"Registro\")' title='<?php echo f_Traducir('Registrar nuevo usuario'); ?>'><?php echo f_Traducir('Registro'); ?></td>"
		+					"</tr>"
		;
		document.frm_global.SSUsuario.focus();
		return;
	}
	TableInfoPerfil.innerHTML =	"<tr>"
	+					"<td align=center id='ZonaPerfil'>"
	+						"<div id=PerfilNombre></div>"
	+						"<div id=PerfilNickName></div>"
	+						"<div id=PerfilUsuario></div>"
	+					"</td>"
	+				"</tr>"
	+				"<tr>"
	+					"<td align=center><div id=DetalleItemsApostados></div></td>"
	+				"</tr>"
	+				"<tr>"
	+					"<td align=center id='ZonaMontoApostado'>"
	+						"<div id=ZonaInputMontoApostado style='display:none;font-size:0.8em;'>"
	+							"<?php echo f_Traducir("Apuesta"); ?> : <input type=text name=InputMontoApostado onKeyUp='apostadoMonto=parseFloat(this.value);f_js_dibujarAreaApuesta()' style='width:3.5em;'>"
	+						"</div>"
	+						"<div id=ZonaTextoMontoPagado style='display:none;font-size:0.8em;'>"
	+							"<?php echo f_Traducir("A Pagar"); ?> : <div id=TextoMontoPagado style='display:inline;'></div>"
	+						"</div>"
	+					"</td>"
	+				"</tr>"
	+				"<tr>"
	+					"<td align=center id='ZonaBotonesApostado'>"
	+						"<input type=button value='<?php echo f_Traducir("Guardar"); ?>' onClick='f_js_ApuestaGuardar()'>"
	+					"</td>"
	+				"</tr>";
	f_js_dibujarAreaApuesta();
}

function f_js_CambiarPaginaActual(pNuevaPagina){
	v_PaginaActual = pNuevaPagina;
	f_js_Redibujar();
}

function f_js_DibujarRegistro(){
	var divTrabajo = el_id("AreaTrabajo");
	var txtEncuentros =	"<div class='AreaPaginaCompleta BackgroundBotonAmarilloDegrade' style='letter-spacing:1em;font-style:normal;font-weight:700;vertical-align:middle;'>"
	+				"<?php echo f_Traducir("REGISTRO"); ?>"
	+			"</div>"
	+			"<div class='AreaPaginaCompleta BackgroundBarraGris' style='font-style:normal;font-weight:700;vertical-align:middle;background-color:rgba(188,198,195,0.7);'>"
	+				"<?php echo f_Traducir("Paso 1"); ?>"
	+			"</div>"
	+			"<div class='AreaPaginaCompleta' style='font-style:normal;font-weight:700;vertical-align:middle;height:auto;color:black;background-color:rgba(255,255,255,0.7);padding-top:0.4em;padding-bottom:0.4em;'>"
	+				"<div class='AreaCuartoDePagina'>"
	+					"<div class='AreaMitadDePagina' style='font-size:0.7em;color:black;'>"
	+						"<?php echo f_Traducir("PRIMER NOMBRE"); ?>"
	+					"</div>"
	+					"<div class='AreaMitadDePagina'>"
	+						"<input name=PrimerNombre type=text class=InputFormulario>"
	+					"</div>"
	+				"</div>"
	+				"<div class='AreaCuartoDePagina'>"
	+					"<div class='AreaMitadDePagina' style='font-size:0.7em;'>"
	+						"<?php echo f_Traducir("SEGUNDO NOMBRE"); ?>"
	+					"</div>"
	+					"<div class='AreaMitadDePagina'>"
	+						"<input name=SegundoNombre type=text class=InputFormulario>"
	+					"</div>"
	+				"</div>"
	+				"<div class='AreaCuartoDePagina'>"
	+					"<div class='AreaMitadDePagina' style='font-size:0.7em;'>"
	+						"<?php echo f_Traducir("PRIMER APELLIDO"); ?>"
	+					"</div>"
	+					"<div class='AreaMitadDePagina'>"
	+						"<input name=PrimerApellido type=text class=InputFormulario>"
	+					"</div>"
	+				"</div>"
	+				"<div class='AreaCuartoDePagina'>"
	+					"<div class='AreaMitadDePagina' style='font-size:0.7em;'>"
	+						"<?php echo f_Traducir("SEGUNDO APELLIDO"); ?>"
	+					"</div>"
	+					"<div class='AreaMitadDePagina'>"
	+						"<input name=SegundoApellido type=text class=InputFormulario>"
	+					"</div>"
	+				"</div>"
	+			"</div>"
	+			"<div class='AreaPaginaCompleta BackgroundBarraGris' style='font-style:normal;font-weight:700;vertical-align:middle;background-color:rgba(188,198,195,0.7);'>"
	+				"<?php echo f_Traducir("Paso 2"); ?>"
	+			"</div>"
	+			"<div class='AreaPaginaCompleta' style='font-style:normal;font-weight:700;vertical-align:middle;height:auto;color:black;background-color:rgba(255,255,255,0.7);padding-top:0.4em;padding-bottom:0.4em;'>"
	+				"<div class='AreaPaginaCompleta' style='padding-top:0.3em;padding-bottom:0.3em;'>"
	+					"<div class='AreaTercioDePagina' style='font-size:0.7em;color:black;width:auto;'>"
	+						"<?php echo f_Traducir("NOMBRE DE USUARIO"); ?>"
	+					"</div>"
	+					"<div class='AreaMitadDePagina'>"
	+						"<input name=Login type=text class=InputFormulario>"
	+					"</div>"
	+				"</div>"
	+				"<div class='AreaMitadDePagina' style='padding-top:0.3em;padding-bottom:0.3em;'>"
	+					"<div class='AreaMitadDePagina' style='font-size:0.7em;'>"
	+						"<?php echo f_Traducir("CONTRASE&Ntilde;A"); ?>"
	+					"</div>"
	+					"<div class='AreaMitadDePagina'>"
	+						"<input name=Clave type=password class=InputFormulario>"
	+					"</div>"
	+				"</div>"
	+				"<div class='AreaMitadDePagina' style='padding-top:0.3em;padding-bottom:0.3em;'>"
	+					"<div class='AreaMitadDePagina' style='font-size:0.7em;'>"
	+						"<?php echo f_Traducir("CONFIRMAR CONTRASE&Ntilde;A"); ?>"
	+					"</div>"
	+					"<div class='AreaMitadDePagina'>"
	+						"<input name=Clave2 type=password class=InputFormulario>"
	+					"</div>"
	+				"</div>"
	+				"<div class='AreaMitadDePagina' style='padding-top:0.3em;padding-bottom:0.3em;'>"
	+					"<div class='AreaMitadDePagina' style='font-size:0.7em;'>"
	+						"<?php echo f_Traducir("CORREO ELECTR&Oacute;NICO"); ?>"
	+					"</div>"
	+					"<div class='AreaMitadDePagina'>"
	+						"<input name=EMail type=text class=InputFormulario>"
	+					"</div>"
	+				"</div>"
	+				"<div class='AreaMitadDePagina' style='padding-top:0.3em;padding-bottom:0.3em;'>"
	+					"<div class='AreaMitadDePagina' style='font-size:0.7em;'>"
	+						"<?php echo f_Traducir("CONFIRMAR CORREO ELECTR&Oacute;NICO"); ?>"
	+					"</div>"
	+					"<div class='AreaMitadDePagina'>"
	+						"<input name=EMail2 type=text class=InputFormulario>"
	+					"</div>"
	+				"</div>"
	+			"</div>"
	+			"<div class='AreaPaginaCompleta BackgroundBarraGris' style='font-style:normal;font-weight:700;vertical-align:middle;background-color:rgba(188,198,195,0.7);'>"
	+				"<?php echo f_Traducir("Paso 3"); ?>"
	+			"</div>"
	+			"<div class='AreaPaginaCompleta' style='font-style:normal;font-weight:700;vertical-align:middle;height:auto;color:black;background-color:rgba(255,255,255,0.7);padding-top:0.4em;padding-bottom:0.4em;'>"
	+				"<div class='AreaPaginaCompleta' style='padding-top:0.3em;padding-bottom:0.3em;'>"
	+					"<div class='AreaCuartoDePagina' style='font-size:0.7em;'>"
	+						"<?php echo f_Traducir("L&Iacute;MITE DIARIO"); ?>"
	+					"</div>"
	+					"<div class='AreaMitadDePagina'>"
	+						"<input name=LimiteDiario type=text class=InputFormulario>"
	+					"</div>"
	+				"</div>"
	+				"<div class='AreaPaginaCompleta' style='padding-top:0.3em;padding-bottom:0.3em;'>"
	+					"<div class='AreaCuartoDePagina' style='font-size:0.7em;'>"
	+						"<?php echo f_Traducir("L&Iacute;MITE SEMANAL"); ?>"
	+					"</div>"
	+					"<div class='AreaMitadDePagina'>"
	+						"<input name=LimiteSemanal type=text class=InputFormulario>"
	+					"</div>"
	+				"</div>"
	+				"<div class='AreaPaginaCompleta' style='padding-top:0.3em;padding-bottom:0.3em;'>"
	+					"<div class='AreaCuartoDePagina' style='font-size:0.7em;'>"
	+						"<?php echo f_Traducir("L&Iacute;MITE MENSUAL"); ?>"
	+					"</div>"
	+					"<div class='AreaMitadDePagina'>"
	+						"<input name=LimiteMensual type=text class=InputFormulario>"
	+					"</div>"
	+				"</div>"
	+			"</div>"
	+			"<div class='AreaPaginaCompleta BackgroundBarraGris' style='text-align:right;height:auto;font-style:normal;font-weight:700;vertical-align:middle;background-color:rgba(188,198,195,0.7);'>"
	+				"<input type=button value='<?php echo f_Traducir("Cancelar"); ?>' onClick='f_js_CambiarPaginaActual(\"Encuentros\")' style='border-radius:14px;padding:7px 15px 7px 15px;outline:none;'>"
	+				"<input type=button value='<?php echo f_Traducir("Crear Cuenta"); ?>' onClick='f_js_CambiarPaginaActual(\"RegistroGrabar\")' style='border-radius:14px;padding:7px 15px 7px 15px;outline:none;'>"
	+			"</div>"
	;
	divTrabajo.innerHTML = txtEncuentros;
}

function f_js_DibujarEncuentroDetalle(){
	var i;
	var j;
	var n;
	var divTrabajo = el_id("AreaTrabajo");
	for (i = 0; i < UltimoListadoEncuentros.length && UltimoListadoEncuentros[i].codigo != EncuentroSeleccionado; i++)
		;
	n = i;
	console.log(UltimoListadoEncuentros[n]);
	var txtEncuentros =	"<div class='AreaPaginaCompleta BackgroundBotonAmarilloDegrade' style='letter-spacing:1em;font-style:normal;font-weight:700;vertical-align:middle;'>"
//	+				"<?php echo f_Traducir("Encuentro"); ?>:" + EncuentroSeleccionado
	+				UltimoListadoEncuentros[n].local + " - " + UltimoListadoEncuentros[n].visitante
	+			"</div>"
	+			"<div class='AreaPaginaCompleta BackgroundBarraGris' style='height:3em;font-style:normal;font-weight:700;vertical-align:middle;background-color:rgba(188,198,195,0.7);'>"
	+				"<?php echo f_Traducir("Liga : "); ?>" + UltimoListadoEncuentros[n].liga
	+				"<br>" + UltimoListadoEncuentros[n].instante
	+			"</div>"
	;
	j = f_js_PosicionApuestaEncuentro(EncuentroSeleccionado);
	if (j >= 0){
		txtEncuentros += "<div class='AreaPaginaCompleta'>Encuentro pre seleccionado</div>";
	}else{
//		console.log("Encuentro no seleccionado " + EncuentroSeleccionado);
	}
	for (i = 0; i < 5 && i < UltimoListadoEncuentros[n].Resultados.length; i++){
		txtEncuentros += ""
		+			"<div class='"
		+					"AreaPaginaCompleta"
		+					((j >= 0 && apostadoGlobal[j][1] == UltimoListadoEncuentros[n].Resultados[i].resultado)?" LineaTipoResultadoPreseleccionado":" LineaTipoResultadoResaltar")
		+					"'"
		+				" onClick=f_js_AgregarApuesta('" + EncuentroSeleccionado + "','" + UltimoListadoEncuentros[n].Resultados[i].resultado + "','" + UltimoListadoEncuentros[n].Resultados[i].factor_pagado + "','" + UltimoListadoEncuentros[n].deporte_id + "')"
		+				" style='font-style:normal;font-weight:700;vertical-align:middle;height:auto;color:black;background-color:rgba(255,255,255,0.7);padding-top:0.4em;padding-bottom:0.4em;'"
		+				">"
		+				"<div class='AreaPaginaCompleta'>"
		+					"<div class='AreaMitadDePagina' style='font-size:0.7em;color:black;'>"
		+						f_js_DecodificarTipoResultado(UltimoListadoEncuentros[n].deporte_id,UltimoListadoEncuentros[n].Resultados[i].resultado)
		+					"</div>"
		+					"<div class='AreaMitadDePagina'>"
		+						UltimoListadoEncuentros[n].Resultados[i].factor_pagado
//		+						"<input name=PrimerNombre type=text class=InputFormulario>"
		+					"</div>"
		+				"</div>"
		+			"</div>"
		;
	}
	txtEncuentros += ""
/*
	+			"<div class='AreaPaginaCompleta' style='font-style:normal;font-weight:700;vertical-align:middle;height:auto;color:black;background-color:rgba(255,255,255,0.7);padding-top:0.4em;padding-bottom:0.4em;'>"
	+				"<div class='AreaCuartoDePagina'>"
	+					"<div class='AreaMitadDePagina' style='font-size:0.7em;color:black;'>"
	+						"PRIMER NOMBRE"
	+					"</div>"
	+					"<div class='AreaMitadDePagina'>"
	+						"<input name=PrimerNombre type=text class=InputFormulario>"
	+					"</div>"
	+				"</div>"
	+				"<div class='AreaCuartoDePagina'>"
	+					"<div class='AreaMitadDePagina' style='font-size:0.7em;'>"
	+						"SEGUNDO NOMBRE"
	+					"</div>"
	+					"<div class='AreaMitadDePagina'>"
	+						"<input name=SegundoNombre type=text class=InputFormulario>"
	+					"</div>"
	+				"</div>"
	+				"<div class='AreaCuartoDePagina'>"
	+					"<div class='AreaMitadDePagina' style='font-size:0.7em;'>"
	+						"PRIMER APELLIDO"
	+					"</div>"
	+					"<div class='AreaMitadDePagina'>"
	+						"<input name=PrimerApellido type=text class=InputFormulario>"
	+					"</div>"
	+				"</div>"
	+				"<div class='AreaCuartoDePagina'>"
	+					"<div class='AreaMitadDePagina' style='font-size:0.7em;'>"
	+						"SEGUNDO APELLIDO"
	+					"</div>"
	+					"<div class='AreaMitadDePagina'>"
	+						"<input name=SegundoApellido type=text class=InputFormulario>"
	+					"</div>"
	+				"</div>"
	+			"</div>"
	+			"<div class='AreaPaginaCompleta BackgroundBarraGris' style='font-style:normal;font-weight:700;vertical-align:middle;background-color:rgba(188,198,195,0.7);'>"
	+				"Paso 2"
	+			"</div>"
	+			"<div class='AreaPaginaCompleta' style='font-style:normal;font-weight:700;vertical-align:middle;height:auto;color:black;background-color:rgba(255,255,255,0.7);padding-top:0.4em;padding-bottom:0.4em;'>"
	+				"<div class='AreaPaginaCompleta' style='padding-top:0.3em;padding-bottom:0.3em;'>"
	+					"<div class='AreaTercioDePagina' style='font-size:0.7em;color:black;width:auto;'>"
	+						"NOMBRE DE USUARIO"
	+					"</div>"
	+					"<div class='AreaMitadDePagina'>"
	+						"<input name=Login type=text class=InputFormulario>"
	+					"</div>"
	+				"</div>"
	+				"<div class='AreaMitadDePagina' style='padding-top:0.3em;padding-bottom:0.3em;'>"
	+					"<div class='AreaMitadDePagina' style='font-size:0.7em;'>"
	+						"CONTRASE&Ntilde;A"
	+					"</div>"
	+					"<div class='AreaMitadDePagina'>"
	+						"<input name=Clave type=password class=InputFormulario>"
	+					"</div>"
	+				"</div>"
	+				"<div class='AreaMitadDePagina' style='padding-top:0.3em;padding-bottom:0.3em;'>"
	+					"<div class='AreaMitadDePagina' style='font-size:0.7em;'>"
	+						"CONFIRMAR CONTRASE&Ntilde;A"
	+					"</div>"
	+					"<div class='AreaMitadDePagina'>"
	+						"<input name=Clave2 type=password class=InputFormulario>"
	+					"</div>"
	+				"</div>"
	+				"<div class='AreaMitadDePagina' style='padding-top:0.3em;padding-bottom:0.3em;'>"
	+					"<div class='AreaMitadDePagina' style='font-size:0.7em;'>"
	+						"CORREO ELECTR&Oacute;NICO"
	+					"</div>"
	+					"<div class='AreaMitadDePagina'>"
	+						"<input name=EMail type=text class=InputFormulario>"
	+					"</div>"
	+				"</div>"
	+				"<div class='AreaMitadDePagina' style='padding-top:0.3em;padding-bottom:0.3em;'>"
	+					"<div class='AreaMitadDePagina' style='font-size:0.7em;'>"
	+						"CONFIRMAR CORREO ELECTR&Oacute;NICO"
	+					"</div>"
	+					"<div class='AreaMitadDePagina'>"
	+						"<input name=EMail2 type=text class=InputFormulario>"
	+					"</div>"
	+				"</div>"
	+			"</div>"
	+			"<div class='AreaPaginaCompleta BackgroundBarraGris' style='font-style:normal;font-weight:700;vertical-align:middle;background-color:rgba(188,198,195,0.7);'>"
	+				"Paso 3"
	+			"</div>"
	+			"<div class='AreaPaginaCompleta' style='font-style:normal;font-weight:700;vertical-align:middle;height:auto;color:black;background-color:rgba(255,255,255,0.7);padding-top:0.4em;padding-bottom:0.4em;'>"
	+				"<div class='AreaPaginaCompleta' style='padding-top:0.3em;padding-bottom:0.3em;'>"
	+					"<div class='AreaCuartoDePagina' style='font-size:0.7em;'>"
	+						"L&Iacute;MITE DIARIO"
	+					"</div>"
	+					"<div class='AreaMitadDePagina'>"
	+						"<input name=LimiteDiario type=text class=InputFormulario>"
	+					"</div>"
	+				"</div>"
	+				"<div class='AreaPaginaCompleta' style='padding-top:0.3em;padding-bottom:0.3em;'>"
	+					"<div class='AreaCuartoDePagina' style='font-size:0.7em;'>"
	+						"L&Iacute;MITE SEMANAL"
	+					"</div>"
	+					"<div class='AreaMitadDePagina'>"
	+						"<input name=LimiteSemanal type=text class=InputFormulario>"
	+					"</div>"
	+				"</div>"
	+				"<div class='AreaPaginaCompleta' style='padding-top:0.3em;padding-bottom:0.3em;'>"
	+					"<div class='AreaCuartoDePagina' style='font-size:0.7em;'>"
	+						"L&Iacute;MITE MENSUAL"
	+					"</div>"
	+					"<div class='AreaMitadDePagina'>"
	+						"<input name=LimiteMensual type=text class=InputFormulario>"
	+					"</div>"
	+				"</div>"
	+			"</div>"
*/
	+			"<div class='AreaPaginaCompleta BackgroundBarraGris' style='text-align:right;height:auto;font-style:normal;font-weight:700;vertical-align:middle;background-color:rgba(188,198,195,0.7);'>"
	+				"<input type=button value='<?php echo f_Traducir("Regresar"); ?>' onClick='f_js_CambiarPaginaActual(\"Encuentros\")' style='border-radius:14px;padding:7px 15px 7px 15px;outline:none;'>"
	+			"</div>"
	;
	divTrabajo.innerHTML = txtEncuentros;
}

function f_js_RegistroCrear(){
  if (document.frm_global.Login.value.trim() == ""){
    alert("<?php echo f_Traducir("Debe ingresar una Nombre de usuario"); ?>");
    return;
  }
  if (document.frm_global.Clave.value.trim() == ""){
    alert("<?php echo f_Traducir("Debe ingresar una contraseña"); ?>");
    return;
  }
  if (document.frm_global.Clave.value.trim() != document.frm_global.Clave2.value.trim()){
    alert("<?php echo f_Traducir("Las dos contraseñas deben ser iguales"); ?>");
    return;
  }
  if (document.frm_global.EMail.value.trim() == ""){
    alert("<?php echo f_Traducir("Debe ingresar su correo electrónico"); ?>");
    return;
  }
  if (document.frm_global.EMail.value.trim() != document.frm_global.EMail.value.trim()){
    alert("<?php echo f_Traducir("Los dos correos electrónicos deben ser iguales"); ?>");
    return;
  }

  f_http_post(cliente_http
             ,"SportSiete.php"
             ,"method=UsuarioRegistrar"
		+ "&PrimerNombre=" + document.frm_global.PrimerNombre.value
		+ "&SegundoNombre=" + document.frm_global.SegundoNombre.value
		+ "&PrimerApellido=" + document.frm_global.PrimerApellido.value
		+ "&SegundoApellido=" + document.frm_global.SegundoApellido.value
		+ "&Login=" + document.frm_global.Login.value.trim()
		+ "&Clave=" + document.frm_global.Clave.value.trim()
		// + "&Clave2=" + document.frm_global.Clave2.value
		+ "&EMail=" + document.frm_global.EMail.value.trim()
		// + "&EMail2=" + document.frm_global.EMail2.value
		+ "&LimiteDiario=" + document.frm_global.LimiteDiario.value
		+ "&LimiteSemanal=" + document.frm_global.LimiteSemanal.value
		+ "&LimiteMensual=" + document.frm_global.LimiteMensual.value
             ,f_js_RegistroCrear_Respuesta,5000);

}

function f_js_RegistroCrear_Respuesta(){
  var el_div = el_id("encuentros");
  var json_resp = f_js_procesar_respuesta_generica(cliente_http,true);
  if (json_resp)
  {
//console.log(json_resp);
    if (json_resp.ErrorDescripcion)
    {
      alert(json_resp.ErrorDescripcion);
    }
    else if (json_resp.UsuarioCodigo)
    {
//      el_id("BotonAbrirAutenticacion").style.display = "none";
      // alert("Usuario registrado con codigo : " + json_resp.UsuarioCodigo);
      setTimeout(function(){alert("Usuario registrado");f_js_CambiarPaginaActual("Encuentros");},300);
      // apostadoMaximo = parseFloat(json_resp.Usuario["disponible"]);
      apostadoMaximo = parseFloat(document.frm_global.LimiteMensual.value);
      return;
    }
    else
    {
	console.log(json_resp);
      alert("<?php echo f_Traducir("La respuesta no es coherente"); ?>");
    }
    f_js_Cargar();
  }
}

function f_js_PagoIniciar(){
  f_http_post(cliente_http
             ,"SportSiete.php"
             ,"method=IniciarPagoPayPal"
                + "&Monto=" + 50
             ,f_js_PagoIniciar_Respuesta,5000);
}

function f_js_PagoIniciar_Respuesta(){
  var el_div = el_id("AreaTrabajo");
  var json_resp = f_js_procesar_respuesta_generica(cliente_http,true);
  if (json_resp)
  {
//console.log(json_resp);
    if (json_resp.ErrorDescripcion)
    {
      alert(json_resp.ErrorDescripcion);
    }
    else if (json_resp.URL)
    {
el_div.innerHTML = "<div align='center'>"+"<h1>Ingresa a nuestro sitio especializado para hacer tu transacción</h1>"+"<hr>"+"<h4>Por favor ingrese al siguiente link para confirmar la transaccion</h4>"+
    "<hr>"+"<a class='button-recharge' href='https://www.sportsiete.com/recharge.html'>Sitio Web Recargas Online <strong>SportSiete.com</strong>"+"</a>"+"</div>";
      return;
    }
    else
    {
        console.log(json_resp);
      alert("La respuesta no es coherente");
    }
    f_js_Cargar();
  }
}

function f_js_DibujarRecarga(){
	var divTrabajo = el_id("AreaTrabajo");
/*
	var PayPalScripts = document.createElement('script');
	PayPalScripts.setAttribute('src','https://paypal.com/sdk/js?client-id=ActdsKEty4lVLysmoGQLFY9v2MsddpT_uSyeIDuNENWXC5uO8Ye3gPknu7OUyXNTWRhgLKSokJhFOKLW&currency=USD&intent=capture&commit=true&integration-date=2020-07-18');
	PayPalScripts.setAttribute('language','javascript');
        document.head.appendChild(PayPalScripts);
*/
        divTrabajo.innerHTML = "IniciandoPago...";
        f_js_PagoIniciar();
        return;

alert("<?php echo f_Traducir("dibujando recarga"); ?>");
	var txtEncuentros =	"<div class='AreaPaginaCompleta BackgroundBotonAmarilloDegrade' style='letter-spacing:1em;font-style:normal;font-weight:700;vertical-align:middle;'>"
	+				"<?php echo f_Traducir("RECARGA"); ?>"
	+			"</div>"
/*
	+			"<div class='AreaPaginaCompleta BackgroundBarraGris' style='font-style:normal;font-weight:700;vertical-align:middle;background-color:rgba(188,198,195,0.7);'>"
	+				"Paso 1"
	+			"</div>"
	+			"<div class='AreaPaginaCompleta' style='font-style:normal;font-weight:700;vertical-align:middle;height:auto;color:black;background-color:rgba(255,255,255,0.7);padding-top:0.4em;padding-bottom:0.4em;'>"
	+				"<div class='AreaCuartoDePagina'>"
	+					"<div class='AreaMitadDePagina' style='font-size:0.7em;color:black;'>"
	+						"PRIMER NOMBRE"
	+					"</div>"
	+					"<div class='AreaMitadDePagina'>"
	+						"<input name=PrimerNombre type=text class=InputFormulario>"
	+					"</div>"
	+				"</div>"
	+				"<div class='AreaCuartoDePagina'>"
	+					"<div class='AreaMitadDePagina' style='font-size:0.7em;'>"
	+						"SEGUNDO NOMBRE"
	+					"</div>"
	+					"<div class='AreaMitadDePagina'>"
	+						"<input name=SegundoNombre type=text class=InputFormulario>"
	+					"</div>"
	+				"</div>"
	+				"<div class='AreaCuartoDePagina'>"
	+					"<div class='AreaMitadDePagina' style='font-size:0.7em;'>"
	+						"PRIMER APELLIDO"
	+					"</div>"
	+					"<div class='AreaMitadDePagina'>"
	+						"<input name=PrimerApellido type=text class=InputFormulario>"
	+					"</div>"
	+				"</div>"
	+				"<div class='AreaCuartoDePagina'>"
	+					"<div class='AreaMitadDePagina' style='font-size:0.7em;'>"
	+						"SEGUNDO APELLIDO"
	+					"</div>"
	+					"<div class='AreaMitadDePagina'>"
	+						"<input name=SegundoApellido type=text class=InputFormulario>"
	+					"</div>"
	+				"</div>"
	+			"</div>"
	+			"<div class='AreaPaginaCompleta BackgroundBarraGris' style='font-style:normal;font-weight:700;vertical-align:middle;background-color:rgba(188,198,195,0.7);'>"
	+				"Paso 2"
	+			"</div>"
	+			"<div class='AreaPaginaCompleta' style='font-style:normal;font-weight:700;vertical-align:middle;height:auto;color:black;background-color:rgba(255,255,255,0.7);padding-top:0.4em;padding-bottom:0.4em;'>"
	+				"<div class='AreaPaginaCompleta' style='padding-top:0.3em;padding-bottom:0.3em;'>"
	+					"<div class='AreaTercioDePagina' style='font-size:0.7em;color:black;width:auto;'>"
	+						"NOMBRE DE USUARIO"
	+					"</div>"
	+					"<div class='AreaMitadDePagina'>"
	+						"<input name=Login type=text class=InputFormulario>"
	+					"</div>"
	+				"</div>"
	+				"<div class='AreaMitadDePagina' style='padding-top:0.3em;padding-bottom:0.3em;'>"
	+					"<div class='AreaMitadDePagina' style='font-size:0.7em;'>"
	+						"CONTRASE&Ntilde;A"
	+					"</div>"
	+					"<div class='AreaMitadDePagina'>"
	+						"<input name=Clave type=password class=InputFormulario>"
	+					"</div>"
	+				"</div>"
	+				"<div class='AreaMitadDePagina' style='padding-top:0.3em;padding-bottom:0.3em;'>"
	+					"<div class='AreaMitadDePagina' style='font-size:0.7em;'>"
	+						"CONFIRMAR CONTRASE&Ntilde;A"
	+					"</div>"
	+					"<div class='AreaMitadDePagina'>"
	+						"<input name=Clave2 type=password class=InputFormulario>"
	+					"</div>"
	+				"</div>"
	+				"<div class='AreaMitadDePagina' style='padding-top:0.3em;padding-bottom:0.3em;'>"
	+					"<div class='AreaMitadDePagina' style='font-size:0.7em;'>"
	+						"CORREO ELECTR&Oacute;NICO"
	+					"</div>"
	+					"<div class='AreaMitadDePagina'>"
	+						"<input name=EMail type=text class=InputFormulario>"
	+					"</div>"
	+				"</div>"
	+				"<div class='AreaMitadDePagina' style='padding-top:0.3em;padding-bottom:0.3em;'>"
	+					"<div class='AreaMitadDePagina' style='font-size:0.7em;'>"
	+						"CONFIRMAR CORREO ELECTR&Oacute;NICO"
	+					"</div>"
	+					"<div class='AreaMitadDePagina'>"
	+						"<input name=EMail2 type=text class=InputFormulario>"
	+					"</div>"
	+				"</div>"
	+			"</div>"
	+			"<div class='AreaPaginaCompleta BackgroundBarraGris' style='font-style:normal;font-weight:700;vertical-align:middle;background-color:rgba(188,198,195,0.7);'>"
	+				"Paso 3"
	+			"</div>"
	+			"<div class='AreaPaginaCompleta' style='font-style:normal;font-weight:700;vertical-align:middle;height:auto;color:black;background-color:rgba(255,255,255,0.7);padding-top:0.4em;padding-bottom:0.4em;'>"
	+				"<div class='AreaPaginaCompleta' style='padding-top:0.3em;padding-bottom:0.3em;'>"
	+					"<div class='AreaCuartoDePagina' style='font-size:0.7em;'>"
	+						"L&Iacute;MITE DIARIO"
	+					"</div>"
	+					"<div class='AreaMitadDePagina'>"
	+						"<input name=LimiteDiario type=text class=InputFormulario>"
	+					"</div>"
	+				"</div>"
	+				"<div class='AreaPaginaCompleta' style='padding-top:0.3em;padding-bottom:0.3em;'>"
	+					"<div class='AreaCuartoDePagina' style='font-size:0.7em;'>"
	+						"L&Iacute;MITE SEMANAL"
	+					"</div>"
	+					"<div class='AreaMitadDePagina'>"
	+						"<input name=LimiteSemanal type=text class=InputFormulario>"
	+					"</div>"
	+				"</div>"
	+				"<div class='AreaPaginaCompleta' style='padding-top:0.3em;padding-bottom:0.3em;'>"
	+					"<div class='AreaCuartoDePagina' style='font-size:0.7em;'>"
	+						"L&Iacute;MITE MENSUAL"
	+					"</div>"
	+					"<div class='AreaMitadDePagina'>"
	+						"<input name=LimiteMensual type=text class=InputFormulario>"
	+					"</div>"
	+				"</div>"
	+			"</div>"
*/
	;

	txtEncuentros += "<div id='paypal-button-container'></div>"
/*
	+		"<script>"
	+			"paypal.Buttons("
	+				"{"
	+					"createOrder: function(data, actions)"
	+						"{"
	+							"return actions.order.create("
	+								"{"
	+									"purchase_units:"
	+										"[{"
	+											"amount:"
	+												"{"
	+													"value: '0.01'"
	+												"}"
	+										"}]"
	+								"});"
	+						"}"
	+					",onApprove: function(data, actions)"
	+						"{"
	+							"return actions.order.capture().then(function(details)"
	+								"{"
	+									"alert('Transaction completed by ' + details.payer.name.given_name);"
	+								"});"
	+						"}"
	+				"}).render('#paypal-button-container');" // Display payment options on your web page
	+		"</script>"
*/
	;

	txtEncuentros +=	"<div class='AreaPaginaCompleta BackgroundBarraGris' style='text-align:right;height:auto;font-style:normal;font-weight:700;vertical-align:middle;background-color:rgba(188,198,195,0.7);'>"
//	+				"<input type=button value='Cancelar' onClick='f_js_CambiarPaginaActual(\"Encuentros\")' style='border-radius:14px;padding:7px 15px 7px 15px;outline:none;'>"
//	+				"<input type=button value='Crear Cuenta' onClick='f_js_CambiarPaginaActual(\"RegistroGrabar\")' style='border-radius:14px;padding:7px 15px 7px 15px;outline:none;'>"
	+				"<input type=button value='<?php echo f_Traducir("Carga Temporal"); ?>' onClick='f_js_GrabarRecarga(5000)' style='border-radius:14px;padding:7px 15px 7px 15px;outline:none;'>"
	+			"</div>"
	;
	divTrabajo.innerHTML = txtEncuentros;
	paypal.Buttons(
		{
			createOrder: function(data, actions)
				{
					return actions.order.create(
						{
							purchase_units:
								[{
									amount:
										{
											value: '0.01'
										}
								}]
						});
				}
			,onApprove: function(data, actions)
				{
					return actions.order.capture().then(function(details)
						{
							alert('Transaction completed by ' + details.payer.name.given_name);
						});
				}
		}).render('#paypal-button-container'); // Display payment options on your web page
}

function f_js_Autenticar(pUsuario,pClave)
{
  f_http_post(cliente_http
             ,"SportSiete.php"
             ,"method=SeguridadSesionCrear"
                + "&Login=" + pUsuario
                + "&Clave=" + pClave
             ,f_js_Autenticar_Respuesta,5000);
}

function f_js_Autenticar_Respuesta()
{
  var json_resp = f_js_procesar_respuesta_generica(cliente_http,true);
  if (json_resp)
  {
//console.log(json_resp);
    if (json_resp.ErrorDescripcion)
    {
      alert(json_resp.ErrorDescripcion);
    }
    else
    {
      if (json_resp.Token)
      {
        if (document.frm_global.ID){
          document.frm_global.ID.value=json_resp.Token;
        } else {
          var input = document.createElement("input");
          input.setAttribute("type", "hidden");
          input.setAttribute("name", "ID");
          input.setAttribute("value", json_resp.Token);
          document.frm_global.appendChild(input);
        }
        setTimeout(function(){document.frm_global.submit();},300);
      }
      else
      {
console.log(json_resp);
        alert("<?php echo f_Traducir("Error no identificado"); ?>");
      }
      return;
    }
  }
}

function f_js_GrabarRecarga(pMonto)
{
  f_http_post(cliente_http
             ,"SportSiete.php"
             ,"method=RecargaTemporal"
                + "&Monto=" + pMonto
             ,f_js_GrabarRecarga_Respuesta,5000);
}

function f_js_GrabarRecarga_Respuesta()
{
  var json_resp = f_js_procesar_respuesta_generica(cliente_http,true);
  if (json_resp)
  {
//console.log(json_resp);
    if (json_resp.ErrorDescripcion)
    {
      alert(json_resp.ErrorDescripcion);
    }
    else
    {
      if (json_resp.Token)
      {
        if (document.frm_global.ID){
          document.frm_global.ID.value=json_resp.Token;
        } else {
          var input = document.createElement("input");
          input.setAttribute("type", "hidden");
          input.setAttribute("name", "ID");
          input.setAttribute("value", json_resp.Token);
          document.frm_global.appendChild(input);
        }
        setTimeout(function(){alert("Recarga exitosa");document.frm_global.submit();},300);
      }
      else
      {
console.log(json_resp);
        alert("<?php echo f_Traducir("Error no identificado"); ?>");
      }
      return;
    }
  }
}

function f_js_DetalleEncuentro(pCodEncuentro){
//	alert(pCodEncuentro);
	EncuentroSeleccionado = pCodEncuentro;
	f_js_CambiarPaginaActual("EncuentroDetalle");
}

function f_js_DecodificarTipoResultado(pDepo,pRes){
	switch(pDepo){
		case "1":
//			console.log("deporte : futbol");
			switch (pRes){
				case "(1,1)" :
					return "<?php echo f_Traducir("Gana Local"); ?>";
				case "(1,2)" :
					return "<?php echo f_Traducir("Empate"); ?>";
				case "(1,3)" :
					return "<?php echo f_Traducir("Gana Visitante"); ?>";
			}
			return pRes;
		default:
			console.log("<?php echo f_Traducir("deporte desconocido : "); ?>" + pDepo);

	}
	return pRes;
}

function f_js_PosicionApuestaEncuentro(pCodEncuentro){
	var i;
	for (i = apostadoGlobal.length - 1; i >= 0; i--){
//		console.log("Comparando " + apostadoGlobal[i][0] + " y " + pCodEncuentro);
		if (apostadoGlobal[i][0] == pCodEncuentro)
			return i;
//		console.log(apostadoGlobal[i][0] + " != " + pCodEncuentro);
	}
//	console.log("no encontrado " + pCodEncuentro);
	return -1;
}

function f_js_AgregarApuesta(pCodEncuentro,pTipoResultado,pFactorPagado,pDeporte){
	var n = f_js_PosicionApuestaEncuentro(pCodEncuentro);
	if (n >= 0)
		apostadoGlobal.splice(n,1);
	apostadoGlobal.push(Array(pCodEncuentro,pTipoResultado,pFactorPagado,pDeporte));
	f_js_dibujarApostado();
	f_js_CambiarPaginaActual("Encuentros");
}

function f_js_ApuestaGuardar(){
	if (apostadoMaximo < apostadoMonto){
		alert("<?php echo f_Traducir("Saldo Insuficiente, debe recargar"); ?>");
		f_js_CambiarPaginaActual("Recargar");
		return;
	}
	var datos = Array();
	for (i = 0; i < apostadoGlobal.length; i++){
		datos.push(new Array(apostadoGlobal[i][0],apostadoGlobal[i][1],apostadoGlobal[i][2],apostadoGlobal[i][3]));
	}
	f_http_post(cliente_http
			,"SportSiete.php"
			,"method=GrabarApuesta"
			+ "&Monto=" + apostadoMonto
			+ "&Datos=" + JSON.stringify(datos)
			,f_js_ApuestaGuardar_Respuesta,5000
			);
}
function f_js_ApuestaGuardar_Respuesta(){
	var el_div;
	var json_resp = f_js_procesar_respuesta_generica(cliente_http,true);
	if (json_resp) {
//console.log(json_resp);
		if (json_resp.ErrorDescripcion) {
			alert(json_resp.ErrorDescripcion);
		} else {
			if (json_resp.Resultado) {
				if (json_resp.Resultado != "1") {
					alert("<?php echo f_Traducir("Su apuesta no pudo ser almacenada!"); ?>");
				} else {
					apostadoGlobal = new Array();
					apostadoMaximo -= apostadoMonto;
					apostadoMonto = 0;
					if ((el_div = el_id("SaldoDisponible")) != null){
						el_div.innerHTML = apostadoMaximo;
					}
					f_js_dibujarApostado();
					if (json_resp.Pendiente){
						alert("Codigo Apuesta : " + json_resp.codigoApuesta + "\nPendiente : " + json_resp.Pendiente);
						// aqui falta mostrar la pantalla y esperar a que se apruebe.
						setTimeout(function() { f_js_CargarPagina('./'); }, 200);
						return;
					}
console.log(json_resp);
					alert("<?php echo f_Traducir("Apuesta tomada!"); ?>");
					document.frm_imprimir.codigo.value=json_resp.codigoApuesta;
					document.frm_imprimir.ID.value=v_cwf_id;
					document.frm_imprimir.submit();
					setTimeout(function() { f_js_CargarPagina('./'); }, 200);
				}
			} else {
console.log(json_resp);
				alert("<?php echo f_Traducir("Error no identificado"); ?>");
			}
			return;
		}
	}
}
