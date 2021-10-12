var cliente_http = f_http_inicializar_cliente();
//var el_timer = setInterval("f_timer()",300000);
//var el_timer = setInterval("f_timer()",10000);
var v_cwf_id = false;
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

var TodosLosPaises = new Array(
					new Array("Todos","","Todos los Paises")
				,	new Array("COLOMBIA","Colombia.png","Colombia")				
        , new Array("SPAIN","Spain.png","Espana")
				,	new Array("GERMANY","Germany.png","Alemania")
        , new Array("ITALY","Italy.png","Italia")
        , new Array("FRANCE","France.png","Francia")
        , new Array("PORTUGAL","Portugal.png","Portugal")
        , new Array("NETHERLANDS","Netherlands.png","Holanda")
        , new Array("ARGENTINA","Argentina.png","Argentina")
				,	new Array("BRAZIL","Brazil.png","Brasil")
        , new Array("BOLIVIA","Bolivia.png","Bolivia")
				,	new Array("AUSTRALIA","Australia.png","Australia")
				,	new Array("AUSTRIA","Austria.png","Austria")
				,	new Array("BELGIUM","Belgium.png","Belgica")
        , new Array("BULGARY","Bulgaria.png","Bulgaria")
				,	new Array("CHILE","Chile.png","Chile")
				,	new Array("CHINA","China.png","China")
        , new Array("COSTA RICA","Costa-Rica.png","Costa Rica")
        , new Array("CROATIA","Croatia.png","Croacia")
				,	new Array("DENMARK","Denmark.png","Dinamarca")
				,	new Array("ECUADOR","Ecuador.png","Ecuador")
				,	new Array("SLOVAKIA","Slovakia.png","Eslovakia")				
        , new Array("EL SALVADOR","El-Salvador.png","El Salvador")				
				,	new Array("GREECE","Grecee.png","Grecia")
        , new Array("HONDURAS","Honduras.png","Honduras")
				,	new Array("HUNGARY","Hungary.png","Hungria")
				,	new Array("ENGLAND","United-Kingdom.png","Inglaterra")
				,	new Array("IRELAND","Ireland.png","Irlanda")
        , new Array("IRAN","Iran.png","Iran")				
        , new Array("JAMAICA","Jamaica.png","Jamaica")
				,	new Array("JAPAN","Japan.png","Japon")
				,	new Array("MEXICO","Mexico.png","Mexico")				
        , new Array("NORWAY","Norway.png","Noruega")
        , new Array("PANAMA","Panama.png","Panama")
				,	new Array("PARAGUAY","Paraguay.png","Paraguay")
				,	new Array("PERU","Peru.png","Peru")
				,	new Array("POLAND","Poland.png","Polonia")				
				,	new Array("ROMANIA","Romania.png","Rumania")
				,	new Array("RUSSIA","Russia.png","Rusia")
				,	new Array("SWEDEN","Sweden.png","Suecia")
				,	new Array("SWITZERLAND","Switzerland.png","Suiza")
				,	new Array("TURKEY","Turkey.png","Turkia")
        , new Array("URUGUAY","Uruguay.png","Uruguay")
				,	new Array("USA","United-States-of-America.png","USA")
        , new Array("VENEZUELA","Venezuela.png","Venezuela")

//				,	new Array("","")
				);

var TodasLasLigas_viejo = new Array(
					new Array("Colombia Liga Aguila","liga_aguila.png","Liga Aguila")
				,	new Array("Copa Libertadores","copalibertadores.png","Copa Libertadores")
				,	new Array("Germany Bundesliga","bundesliga.png","Bundesliga Alemana")
				,	new Array("Belgium Jupiler Pro League","3535531_249px.jpg","Jupiler Liga Pro Belga")
				,	new Array("Netherlands Eredivisie","3535536_249px.jpg","Eredivisie Netherlands")
				,	new Array("France Ligue 1","3535541_249px.jpg","Liga 1 Francesa")
				,	new Array("Portugal Primeira Liga","3535543_249px.jpg","Primer Liga Portuguesa")
				,	new Array("Spain La Liga","9476.png","La Liga Espanola")
				,	new Array("Ireland Premier Division","9648.png","Primera Division de Irlanda")
				,	new Array("Spain La Liga 2","9478.png","La Liga Espanola 2")
				,	new Array("Germany Bundesliga 2","9481.png","Bundesliga Alemana 2")
//				,	new Array("","")
				);

var TodasLasLigas_futbol = new Array(
					new Array("1537","1537.png","Colombia - Apertura")
				,	new Array("9440","9440.png","Colombia 2 - Apertura")
				,	new Array("83","83.png","Copa Libertadores")
				,	new Array("63","63.png","Copa Sudamericana")
				,	new Array("60","60.png","Uefa - Champions League")
				,	new Array("1080","1080.png","Uefa - Europa League")
				,	new Array("88","88.png","Brazil - Carioca")
				,	new Array("3648","3648.png","Brazil Paulista")
				,	new Array("6141","6141.png","Brazil  -  Cup  Nordeste")
				,	new Array("21","21.png","Germany - 1. Bundesliga")
				,	new Array("39","39.png","Germany - 2. Bundesliga")
				,	new Array("20","20.png","England - Premier")
				,	new Array("66","66.png","England - League Two")
				,	new Array("1157","1157.png","England Conf. North")
				,	new Array("51","51.png","England - Conference National")
				,	new Array("34","34.png","Netherlands - Eredivisie")
				,	new Array("578","578.png","Uefa U19 Championship Qualifying")
				);

var TodasLasLigas_tennis = new Array();
var TodasLasLigas_beisbol = new Array();
var TodasLasLigas_basketball = new Array();
var TodasLasLigas_usa = new Array();

var TodasLasLigas = TodasLasLigas_futbol;

var los_tipos = new Array(
				new Array("1","Result. Final")
				,new Array("2","Double chance")
				,new Array("4","Primer Tiempo")
				,new Array("19","Segundo Tiempo")
			);

var los_grupos = new Array(
				new Array("1","Directo")
				,new Array("2","Double chance")
				,new Array("4","Primer Tiempo")
				,new Array("19","Segundo Tiempo")
			);

var nCargas = 0;

var encuentros = false;

function el_id(id)
{
  if (document.getElementById)
    return document.getElementById(id);
  else if (window[id])
    return window[id];
  return null;
}

function NumeroComoDinero(numero,n) {
    var re = '\\d(?=(\\d{3})+' + (n > 0 ? '\\.' : '$') + ')';
    return parseFloat(numero).toFixed(Math.max(0, ~~n)).replace(new RegExp(re, 'g'), '$&,');
};

function f_js_CargarPagina(pURL)
{
	window.location = pURL;
}

function f_js_Informe(pI)
{
	document.frm_informe.i.value = pI;
	document.frm_informe.submit();
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

function f_timer()
{
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
      el_id("BotonAbrirAutenticacion").style.display = "none";
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
    }
    else
    {
      alert("La respuesta no contiene datos del usuario");
    }
    f_js_Cargar();
  }
}

function f_js_TituloResultado(pTipoResultado)
{
	var i;
//console.log(pTipoResultado);
	for (i = 0; i < los_tipos.length;i++)
	{
		if (pTipoResultado == los_tipos[i][0])
		{
			return los_tipos[i][1];
		}
	}
	return pTipoResultado;
}

function f_js_GrupoNombre(pGrupo)
{
	var i;
//console.log(pTipoResultado);
	for (i = 0; i < los_grupos.length;i++)
	{
		if (pGrupo == los_grupos[i][0])
		{
			return los_grupos[i][1];
		}
	}
	return pGrupo;
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
//		+ "&Sesion=" + v_cwf_id
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
      f_js_ListarEncuentros();
      for (j = 0; j < apostadoGlobal.length; j++)
        apostadoGlobal[j][3] = false;
      setTimeout("f_js_dibujarApostado()",300);
      return;
    }
    else
    {
      console.log(json_resp);
      alert("No se pueden descargar los encuentros");
    }
  }
}

function f_js_CheckBoxApuesta_enVentana(pRegistro,pResultado,pPos,pApostado)
{
  var i;
  for (i = 0; i < pRegistro.Resultados.length && pRegistro.Resultados[i].resultado != pResultado; i++)
    ;
  if (i >= pRegistro.Resultados.length)
    return "<div style='width:70px;height:40px;margin-top:15px;float:left;'>--</div>";
  var titulo = f_js_TipoResultadoTexto(pRegistro.Resultados[i].resultado,pRegistro.Resultados[i].extra);
  return "<div style='position:relative;width:90px;height:40px;margin-top:30px;float:left;'><div class='"
			 + ((pApostado && pApostado == pRegistro.Resultados[i].resultado) ? "CheckBoxEncendido" : "CheckBoxApagado")
			+ "' style='float:left;'"
		+ " id='resultado_" + v_js_deporte + "_" + pRegistro.codigo + "_" + i + "'"
		+ " onClick='f_js_apuestaClick(this"
						+ ",\"" + pRegistro.codigo + "\""
						+ ",\"" + pRegistro.Resultados[i].resultado + "\""
						+ "," + pRegistro.Resultados[i].factor_pagado
						+ "," + i
						+ ",\"" + pRegistro.local + "-" + pRegistro.visitante + "\""
						+ ",\"" + pRegistro.instante + "\""
					+ ")'"
		+ " title='" + titulo
				+ "\nPor 1000 paga " + Math.round((parseFloat(pRegistro.Resultados[i].factor_pagado) * 1000))
	+ "'>"
		+ "<span style='position:relative;margin-right:-45px;margin-top:5px;top:0;font-size:0.8em;'>" + pRegistro.Resultados[i].factor_pagado + "</span>"
		+ ((
			   (pRegistro.deporte_id == "1" && pRegistro.Resultados[i].resultado.substr(0,3) != "(1,")
			|| (pRegistro.deporte_id == "2" && pRegistro.Resultados[i].resultado.substr(0,4) != "(17,")
			|| (pRegistro.deporte_id == "5" && pRegistro.Resultados[i].resultado.substr(0,4) != "(17,")
			|| (pRegistro.deporte_id == "8" && pRegistro.Resultados[i].resultado.substr(0,4) != "(17,")
		    )
//		+ ((pRegistro.Resultados[i].resultado == "(2,1)")
				?"<span style='position:absolute;margin-left:0px;margin-top:-25px;top:0;left:0;font-size:0.8em;width:85px;text-align:center;'>" + ((titulo.length > 30)?titulo.substr(0,30) + " ...":titulo) + "</span>"
				:"")
	+ "</div></div>";
}

function f_js_CheckBoxApuesta(pRegistro,pResultado,pPos)
{
  var i;
  var posicion = "";
  var posicion_y = "";
  for (i = 0; i < pRegistro.Resultados.length && pRegistro.Resultados[i].resultado != pResultado; i++)
    ;
  if (pPos)
  {
    posicion = "" + pPos + "px";
    posicion_y = "0px";
  }
  else
  {
    switch(pResultado)
    {
      case "(17,1)":
      case "(1,1)": posicion = "25px"; break;  /*Empieza linea 1*/
      case "(1,2)": posicion = "115px"; break;
      case "(17,3)":
      case "(1,3)": posicion = "205px"; break;/*Termina linea 1*/

      case "(3,4)": posicion = "15px";    posicion_y = "12px"; break;/*Empieza linea 2*/
      case "(3,2)": posicion = "105px";   posicion_y = "12px"; break;/*Under*/
      case "(4,1)": posicion = "195px";  posicion_y = "12px"; break;
      case "(4,2)": posicion = "285px";  posicion_y = "12px"; break;
      case "(3,12)": posicion = "375px"; posicion_y = "12px"; break;
      //case "(4,3)": posicion = "375px";  posicion_y = "12px"; break;
      case "(3,5)": posicion = "465px"; posicion_y = "12px"; break;
      //case "(2,1)": posicion = "465px";  posicion_y = "12px"; break;
      //case "(2,3)": posicion = "555px";  posicion_y = "12px"; break;
      case "(3,13)": posicion = "555px"; posicion_y = "12px"; break;
      //case "(2,2)": posicion = "645px";  posicion_y = "12px"; break;
      case "(3,26)": posicion = "645px"; posicion_y = "12px"; break;
      case "(4,10)": posicion = "735px"; posicion_y = "12px"; break;
      case "(4,11)": posicion = "825px"; posicion_y = "12px"; break;
      case "(3,24)": posicion = "915px"; posicion_y = "12px"; break;
      //case "(130,1)": posicion = "915px"; posicion_y = "12px"; break;
      case "(3,20)": posicion = "1005px"; posicion_y = "12px"; break;
      //case "(130,2)": posicion = "1005px"; posicion_y = "12px"; break;/*Termina linea 2*/



      case "(4,3)": posicion = "15px";  posicion_y = "55px"; break;
      case "(2,1)": posicion = "105px";  posicion_y = "55px"; break;
      case "(2,3)": posicion = "195px";  posicion_y = "55px"; break;
      //case "(3,12)": posicion = "15px"; posicion_y = "55px"; break;
      //case "(3,5)": posicion = "105px"; posicion_y = "55px"; break;
      //case "(3,13)": posicion = "195px"; posicion_y = "55px"; break;
      case "(3,3)": posicion = "285px"; posicion_y = "55px"; break;
      case "(3,18)": posicion = "375px"; posicion_y = "55px"; break;
      case "(3,21)": posicion = "465px"; posicion_y = "55px"; break;
      case "(3,7)": posicion = "555px"; posicion_y = "55px"; break;
      case "(3,17)": posicion = "645px"; posicion_y = "55px"; break;
      case "(3,8)": posicion = "735px"; posicion_y = "55px"; break;
      case "(3,1)": posicion = "825px"; posicion_y = "55px"; break;
      case "(3,29)": posicion = "915px"; posicion_y = "55px"; break;
      case "(3,30)": posicion = "1005px"; posicion_y = "55px"; break;

      case "(2,2)": posicion = "15px";  posicion_y = "98px"; break;
      case "(130,1)": posicion = "105px"; posicion_y = "98px"; break;
      case "(130,2)": posicion = "195px"; posicion_y = "98px"; break;
      //case "(3,26)": posicion = "15px"; posicion_y = "98px"; break;
      //case "(3,24)": posicion = "105px"; posicion_y = "98px"; break;
//      case "(3,20)": posicion = "195px"; posicion_y = "98px"; break;
      case "(3,27)": posicion = "285px"; posicion_y = "98px"; break;
      case "(3,34)": posicion = "375px"; posicion_y = "98px"; break;
      case "(3,14)": posicion = "465px"; posicion_y = "98px"; break;
      case "(3,19)": posicion = "555px"; posicion_y = "98px"; break;
      case "(3,22)": posicion = "645px"; posicion_y = "98px"; break;
      case "(3,28)": posicion = "735px"; posicion_y = "98px"; break;
      case "(3,25)": posicion = "825px"; posicion_y = "98px"; break;
      case "(19,1)": posicion = "915px"; posicion_y = "98px"; break;
      case "(19,2)": posicion = "1005px"; posicion_y = "98px"; break;

      case "(3,6)": posicion = "15px"; posicion_y = "141px"; break;
      case "(3,31)": posicion = "105px"; posicion_y = "141px"; break;
      case "(3,15)": posicion = "195px"; posicion_y = "141px"; break;
      case "(3,16)": posicion = "285px"; posicion_y = "141px"; break;
      case "(3,32)": posicion = "375px"; posicion_y = "141px"; break;
      case "(3,33)": posicion = "465px"; posicion_y = "141px"; break;
      case "(3,35)": posicion = "555px"; posicion_y = "141px"; break;
      case "(3,36)": posicion = "645px"; posicion_y = "141px"; break;
      case "(3,37)": posicion = "735px"; posicion_y = "141px"; break;
      case "(3,38)": posicion = "825px"; posicion_y = "141px"; break;
      case "(19,3)": posicion = "915px"; posicion_y = "141px"; break;

      default : alert("Falta ubicacion para " + pResultado); return "";
    }
  }
  if (i >= pRegistro.Resultados.length)
    return "<div style='position:absolute;bottom:0;left:" + posicion + ";'>--</div>";
  var titulo = f_js_TipoResultadoTexto(pRegistro.Resultados[i].resultado,pRegistro.Resultados[i].extra);
  return "<div class='CheckBoxApagado' style='position:absolute;top:" + posicion_y + ";left:" + posicion + ";'"
		+ " id='resultado_" + v_js_deporte + "_" + pRegistro.codigo + "_" + i + "'"
		+ " onClick='f_js_apuestaClick(this"
						+ ",\"" + pRegistro.codigo + "\""
						+ ",\"" + pRegistro.Resultados[i].resultado + "\""
						+ "," + pRegistro.Resultados[i].factor_pagado
						+ "," + i
						+ ",\"" + pRegistro.local + "-" + pRegistro.visitante + "\""
						+ ",\"" + pRegistro.instante + "\""
					+ ")'"
		+ " title='" + titulo
				+ "\nPor 1000 paga " + Math.round((parseFloat(pRegistro.Resultados[i].factor_pagado) * 1000))
	+ "'>"
		+ "<span style='position:relative;margin-right:-45px;margin-top:5px;top:0;font-size:0.8em;'>" + pRegistro.Resultados[i].factor_pagado + "</span>"
		+ ((
			   (pRegistro.Resultados[i].deporte_id == "1" && pRegistro.Resultados[i].resultado.substr(0,3) != "(1,")
			|| (pRegistro.Resultados[i].deporte_id == "2" && pRegistro.Resultados[i].resultado.substr(0,4) != "(17,")
			|| (pRegistro.Resultados[i].deporte_id == "5" && pRegistro.Resultados[i].resultado.substr(0,4) != "(17,")
			|| (pRegistro.Resultados[i].deporte_id == "8" && pRegistro.Resultados[i].resultado.substr(0,4) != "(17,")
		    )
//		+ ((pRegistro.Resultados[i].resultado == "(2,1)")
				?"<span style='position:absolute;margin-left:-18px;margin-top:-15px;top:0;left:0;font-size:0.5em;width:85px;text-align:center;'>" + ((titulo.length > 30)?titulo.substr(0,30) + " ...":titulo) + "</span>"
				:"")
	+ "</div>";
//	+ "'>"+ pRegistro.Resultados[j].factor_pagado +"</div>";

}

function f_js_TipoResultadoTexto(pTipoResultado,pExtra)
{
	var j;
	for ( j = 0; j < UltimoListadoTipos.length;j++)
	{
		if (UltimoListadoTipos[j].tipo == pTipoResultado)
			return UltimoListadoTipos[j].descripcion;
	}
	return "pTipoResultado";
}

function f_js_TipoResultadoTexto2(pTipoResultado,pExtra)
{
	switch (pTipoResultado)
	{
		case "(1,1)": return "Gana Local";
		case "(1,2)": return "Termina Empatado";
		case "(1,3)": return "Gana Visitante";

		case "(2,1)": return "Empate o Gana el Local";
		case "(2,2)": return "Gana uno de ellos";
		case "(2,3)": return "Empate o Gana el Visitante";

		case "(3,2)": return "Menos de " + pExtra + " goles";
		case "(3,4)": return "Mas de " + pExtra + " goles";

		case "(4,1)": return "Primer tiempo Gana Local";
		case "(4,2)": return "Primer tiempo Empatan";
		case "(4,3)": return "Primer tiempo Gana Visitante";

		case "(19,1)": return "Segundo tiempo Gana Local";
		case "(19,2)": return "Segundo tiempo Empatan";
		case "(19,3)": return "Segundo tiempo Gana Visitante";

		case "(130,1)": return "Ambos equipos marcan";
		case "(130,2)": return "Solo 1 marca";

		default:
			return pTipoResultado;
	}
}

function f_js_dibujarApostado()
{
  var i;
  var el_div = el_id("apostado");
  var article = el_id("ticket-tabs");
  var nDirectos = 0;
  var nApuestas = 0;
  apostadoFactor = 1;
  el_div.innerHTML = "";
  if (apostadoGlobal.length == 0)
  {
//    el_div.innerHTML = "<table width=100% height=100% border=0 style='font-size:2em;'><tr><th></th></tr><tr><th>seleccione</th></tr><tr><th>sus encuentros</th></tr><tr><th></th></tr></table>";
//    el_div.innerHTML = "<div class='LineaApuesta'>seleccione<br>sus encuentros</div>";
    el_div.innerHTML = "<div>seleccione<br>sus encuentros</div>";
  }
  else
  {
    for (i = 0; i < apostadoGlobal.length; i++)
    {
//      factor *= apostadoGlobal[i][2];
//      apostadoFactor += parseFloat(apostadoGlobal[i][2]) - 1;
      nApuestas ++;
      if ( (apostadoGlobal[i][7] == "1" && apostadoGlobal[i][1].substr(0,3) == "(1,")
           || (apostadoGlobal[i][7] == "2" && apostadoGlobal[i][1].substr(0,4) == "(17,")
           || (apostadoGlobal[i][7] == "5" && apostadoGlobal[i][1].substr(0,4) == "(17,")
           || (apostadoGlobal[i][7] == "8" && apostadoGlobal[i][1].substr(0,4) == "(17,")
         )
        nDirectos++;
else
	console.log("No es directo " + apostadoGlobal[i][7] + " - " + apostadoGlobal[i][1].substr(0,4));
      apostadoFactor *= parseFloat(apostadoGlobal[i][2]);
      if ( ! apostadoGlobal[i][3] )
      {
        apostadoGlobal[i][3] = el_id("resultado_" + apostadoGlobal[i][7] + "_" + apostadoGlobal[i][0] + "_" + apostadoGlobal[i][4] );
        if (apostadoGlobal[i][3] == null)
          console.log("No encontro : " + "resultado_" + apostadoGlobal[i][7] + "_" + apostadoGlobal[i][0] + "_" + apostadoGlobal[i][4]);
//          alert("No encontro : " + "resultado_" + apostadoGlobal[i][0] + "_" + apostadoGlobal[i][4]);
      }
      el_div.innerHTML += "<div class='LineaApuesta " + (((i % 2) == 0)?"LineaPar":"LineaImpar") + "'>"
				+ "<div style='width:14px;height:12px;padding-top:0;margin-top:0;margin-left:0;Color:#ffd300;' class='BotonGris BotonNormal' onclick='f_js_QuitarApuesta(" + i + ")'>X</div>"
				+ "<span class='Factor'>" + apostadoGlobal[i][2] + "</span>"
				+ "<span class='Descripcion'>" + apostadoGlobal[i][5] + " " + f_js_TipoResultadoTexto(apostadoGlobal[i][1],"")
								 + ((apostadoGlobal[i][7] == "1")
									?"<br>futbol"
									:((apostadoGlobal[i][7] == "2")
										?"<br>Basketball"
										:""
									)
								) + "</span>"
			+ "</div>";

      if (apostadoGlobal[i][3] && apostadoGlobal[i][3] != null)
        apostadoGlobal[i][3].className = "CheckBoxEncendido";
    }
  }
  // minimo 1 directo por cada 2 indirectos
//  GlobalFaltanDirectos = (((nApuestas - nDirectos) * 2) > nDirectos);
  // minimo 1 directo por cada indirecto
  GlobalFaltanDirectos = ((nApuestas - nDirectos) > nDirectos);
//  if (apostadoGlobal.length < 3)
  if (apostadoGlobal.length < 1)
  {
    //el_div.innerHTML += "<div style='width:100%;float:left;'>Minimo 3 apuestas</div>";
  }
  else
  {
    el_div.innerHTML += "<div class='LineaApuesta'><span>Eventos:</span><span>" + apostadoGlobal.length + "</span>";
    el_div.innerHTML += "<hr>";
    if (DoubleChance)
      el_div.innerHTML += "<div class='LineaApuesta'><span>Total Factor :</span><span>" + (((apostadoFactor - 1) / 2) + 1).toFixed(2) + "</span>";
    else
      el_div.innerHTML += "<div class='LineaApuesta'><span>Total Factor :</span><span>" + apostadoFactor.toFixed(2) + "</span>";
    el_div.innerHTML += "<hr>";
    el_div.innerHTML += "<div class='LineaApuesta'><span>Monto Apostado :</span>"
				+ "<span>" 
					+ "<input type='text' class='input' value='" + apostadoMonto + "' onkeyup='return f_js_apostadoCambio(this,true)'>"
				+ "</span>";
/*
    el_div.innerHTML += "<div class='LineaVerdeClaro'>"
				+ "<div>"
					+ "<span>Eventos:</span>"
					+ "<span class='number-of-matches'>" + apostadoGlobal.length + "</span>"
				+ "</div>"
				+ "<hr>"
				+ "<div>"
					+ "<span>Total Factor : </span>"
					+ "<span id='odd_sum'>" + apostadoFactor.toFixed(2) + "</span>"
				+ "</div>"
				+ "<hr>"
				+ "<div>"
					+ "<span>Monto Apostado : </span>"
//					+ "<input type='text' class='input' value='" + apostadoMonto + "' onChange='f_js_apostadoCambio(this)' onkeypress='f_js_apostadoCambio(this,true)'>"
//					+ "<input type='text' class='input' value='" + apostadoMonto + "' onChange='return f_js_apostadoCambio(this)' onkeyup='return f_js_apostadoCambio(this,true)'>"
					+ "<input type='text' class='input' value='" + apostadoMonto + "' onkeyup='return f_js_apostadoCambio(this,true)'>"
				+ "</div>"
			+ "</div>";
*/
	if (DoubleChance){
		var factordc = ((apostadoFactor - 1) / 2) + 1;
		el_div.innerHTML += "<div class='LineaApuesta'>"
					+"<span>Ganancia $ </span>"
					+"<span id=Ganancia>" + NumeroComoDinero((Math.round(factordc * apostadoMonto) > 15000000)
									? 15000000
									: Math.round(factordc * apostadoMonto),0) + "</span>"
				+"</div>"
				+"<div class='LineaApuesta' style='height:30px;'>"
					+"<span id=DoubleChanceRecupera>Recupera $ " + NumeroComoDinero(apostadoMonto,0) + " por ultima<br>cifra de loteria</span>"
				+"</div>";
	}
	else{
		el_div.innerHTML += "<div class='LineaApuesta'>"
					+"<span>Ganancia $ </span>"
					+"<span id=Ganancia>" + NumeroComoDinero((Math.round(apostadoFactor * apostadoMonto) > 15000000)
									? 15000000
									: Math.round(apostadoFactor * apostadoMonto),0) + "</span>"
				+"</div>";
	}

	if (EsAdministrador && apostadoFactor >= 1.5)
	{
		tmptxt = "<div class='LineaApuesta' style='height:38px;'>";
		if (DoubleChance)
			tmptxt += "<div style='float:left;' class='BotonGris BotonNormal' id=BotonDoubleChance onclick='f_js_DoubleChance(false);'>Quitar Segunda Oportunidad</div>";
		else
			tmptxt += "<div style='float:left;' class='BotonGris BotonNormal' id=BotonDoubleChance onclick='f_js_DoubleChance(true);' title='En caso de NO ganar puede recuperar el dinero\napostado si la ultima cifra de su tirilla\nes igual a la ultima cifra del premio mayor\nde la loteria de Medellin el viernes posterior\nal dia de su apuesta'>Activar Segunda Oportunidad</div>";
		tmptxt += "</div>";
		el_div.innerHTML += tmptxt;
	}

// se permiten canzas sin directos
//    if ( ! GlobalFaltanDirectos)
      el_div.innerHTML += "<div class='LineaApuesta'>"
//				+ "<span style='float:right;' class='botonAzul Color1' onclick='f_js_GrabarApuesta()'>Grabar Apuesta</span>"
				+ "<div style='float:left;' class='BotonGris BotonNormal' id=BotonGrabarApuesta onclick='f_js_GrabarApuesta()'>Grabar Apuesta</div>"
//				+ "<div style='float:left;' class='BotonGris BotonNormal' id=BotonGrabarApuesta onclick='alert(\"Plataforma suspendida\")'>Grabar Apuesta</div>"
			+ "</div>";

  }
}

function f_js_DoubleChance(pActivar){
	DoubleChance = pActivar;
	f_js_dibujarApostado();
}

function f_js_apuestaClick(pDiv,pEncuentro,pResultado,pFactorPagado,pJ,pJugadores,pInstante)
{
  var i;

  if (EsAdministrador)
  {
	alert("Cambiando Factor");
  }

  if (parseFloat(pFactorPagado) <= 0)
  {
    alert("Esta opcion no se puede seleccionar");
    return;
  }
  for (i = 0; i < apostadoGlobal.length && (apostadoGlobal[i][7] != v_js_deporte || apostadoGlobal[i][0] != pEncuentro);i++)
    ;
  if (i >= apostadoGlobal.length)
  {
    apostadoGlobal.push(new Array(pEncuentro,pResultado,pFactorPagado,pDiv,pJ,pJugadores,pInstante,v_js_deporte));
//    pDiv.style.background = "rgb(70,150,150)";
//	pDiv.className += " selected";
	pDiv.className = "CheckBoxEncendido";
  }
  else
  {
console.log(apostadoGlobal[i]);
//    if ( ! apostadoGlobal[i][3] ) // debido a la generacion dinamica de los codigos, ya no se puede confiar en este valor
    apostadoGlobal[i][3] = el_id("resultado_" + apostadoGlobal[i][7] + "_" + apostadoGlobal[i][0] + "_" + apostadoGlobal[i][4] );
//    apostadoGlobal[i][3].style.background = "";
//console.log(apostadoGlobal[i][3]);
    if (apostadoGlobal[i][3] && apostadoGlobal[i][3] != null)
    {
      apostadoGlobal[i][3].className = "CheckBoxApagado";
//    apostadoGlobal[i][3].className = "odd tooltip";
    }
    if (apostadoGlobal[i][1] == pResultado)
      apostadoGlobal.splice(i,1);
    else
    {
      apostadoGlobal[i][1] = pResultado;
      apostadoGlobal[i][2] = pFactorPagado;
      apostadoGlobal[i][3] = pDiv;
      apostadoGlobal[i][4] = pJ;
      apostadoGlobal[i][5] = pJugadores;
      apostadoGlobal[i][6] = pInstante;
      apostadoGlobal[i][7] = v_js_deporte;
      pDiv.className = "CheckBoxEncendido";
//      pDiv.className += " selected";
//      pDiv.style.background = "rgb(70,150,150)";
    }
  }
  f_js_dibujarApostado();
//  alert (pEncuentro + " " + pResultado + " " + pFactorPagado);
}

function menu() 
{
    var x = document.getElementById("main");
    var y = document.getElementById("sprite-bg filter-icon");

    if (y.className === "sprite-bg filter-icon")
    {           
        document.getElementById("main").setAttribute("style","max-height: 214px; left: 32px; position: fixed;");
        y.className = "sprite-bg filter-icon active";
    } else {
        document.getElementById("main").setAttribute("style","max-height: 214px; left: -650px; position: fixed; top: 90px;");
        y.className = "sprite-bg filter-icon"
    }
} 

function f_js_apostadoCambio(pInput,pTemporal)
{
  if (apostadoMaximo < parseFloat(pInput.value))
  {
    if (pTemporal)
    {
      alert("Cupo superado");
      return false;
    }
    pInput.value = apostadoMaximo;
    alert("Su cupo máximo de apuesta es : $" + apostadoMaximo);
  }
  if (1000000 < parseFloat(pInput.value))
  {
    if (pTemporal)
    {
      alert("Max superado");
      return false;
    }
    pInput.value = 1000000;
    alert("El limite maximo permitido es : $1'000.000");
  }
  apostadoMonto = pInput.value;
  if (pTemporal)
  {
//    alert("Actualizando");
    if (DoubleChance){
      var factordc = ((apostadoFactor - 1) / 2) + 1;
      el_id("Ganancia").innerHTML = NumeroComoDinero((Math.round(factordc * apostadoMonto) > 15000000)
								? 15000000
								: Math.round(factordc * apostadoMonto),0);
      el_id("DoubleChanceRecupera").innerHTML = "Recupera $ " + NumeroComoDinero(Math.round(apostadoMonto)) + " por ultima<br>cifra de loteria";
    } else {
//      el_id("Ganancia").innerHTML = Math.round(apostadoFactor * apostadoMonto);
      el_id("Ganancia").innerHTML = NumeroComoDinero((Math.round(apostadoFactor * apostadoMonto) > 15000000)
								? 15000000
								: Math.round(apostadoFactor * apostadoMonto),0);
    }
    return true;
  }
  f_js_dibujarApostado();
  return true;
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
        document.frmContinuar.ID.value=json_resp.Token;
        document.frmContinuar.submit();
      }
      else
      {
console.log(json_resp);
        alert("Error no identificado");
      }
      return;
    }
  }
}

function f_js_Registrar(pUsuario,pClave,pClave2)
{

  if (pUsuario.trim().length < 3)
  {
    alert("La nombre de usuario debe tener minimo 3 caracteres");
    return false;
  }
  if (pClave.trim().length < 8)
  {
    alert("La clave debe tener minimo 8 caracteres");
    return false;
  }
  if (pClave != pClave2)
  {
    alert("Ambas claves deben ser iguales");
    return false;
  }

  f_http_post(cliente_http
             ,"SportSiete.php"
             ,"method=UsuarioRegistrar"
		+ "&Login=" + pUsuario.trim()
		+ "&Clave=" + pClave.trim()
             ,f_js_Registrar_Respuesta,5000);
}

function f_js_Registrar_Respuesta()
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
      if (json_resp.UsuarioCodigo)
      {
        alert("Su codigo de usuario es : " + json_resp.UsuarioCodigo);
        window.location="login.php";
      }
      else
      {
console.log(json_resp);
        alert("Error no identificado");
      }
      return;
    }
  }
}

function f_js_GrabarApuesta()
{
  var i;
  var datos=new Array();
  for (i = 0; i < apostadoGlobal.length; i++)
  {
//     datos.push(new Array(v_cwf_id,apostadoMonto,));
      datos.push(new Array(apostadoGlobal[i][0],apostadoGlobal[i][1],apostadoGlobal[i][7]));
  }

  el_id("BotonGrabarApuesta").style.display="none";

  f_http_post(cliente_http
             ,"SportSiete.php"
             ,"method=GrabarApuesta"
		+ "&Sesion=" + v_cwf_id
		+ "&Monto=" + apostadoMonto
		+ "&datos=" + JSON.stringify(datos)
		+ (DoubleChance?"&DoubleChance=1":"")
             ,f_js_GrabarApuesta_Respuesta,5000);
}

function f_js_GrabarApuesta_Respuesta()
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
      if (json_resp.Resultado)
      {
        if (json_resp.Resultado != "1")
        {
          alert("Su apuesta no pudo ser almacenada!");
        }
        else
        {
          var el_div;
          apostadoGlobal = new Array();
          apostadoMaximo -= apostadoMonto;
          apostadoMonto = 0;
          if ((el_div = el_id("SaldoDisponible")) != null)
            el_div.innerHTML = apostadoMaximo;
          f_js_dibujarApostado();
          if (json_resp.Pendiente){
            alert("Codigo Apuesta : " + json_resp.codigoApuesta + "\nPendiente : " + json_resp.Pendiente);
            // aqui falta mostrar la pantalla y esperar a que se apruebe.
            setTimeout(function() { f_js_CargarPagina('./'); }, 200);
            return;
          }
console.log(json_resp);
          alert("Apuesta tomada!");
          document.frm_imprimir.codigo.value=json_resp.codigoApuesta;
          document.frm_imprimir.submit();
          setTimeout(function() { f_js_CargarPagina('./'); }, 200);
        }
      }
      else
      {
console.log(json_resp);
        alert("Error no identificado");
      }
      return;
    }
  }
}

function f_js_ApuestaAnular(pApuesta)
{
  f_http_post(cliente_http
             ,"SportSiete.php"
             ,"method=ApuestaAnular"
		+ "&Sesion=" + v_cwf_id
		+ "&Apuesta=" + pApuesta
             ,f_js_ApuestaAnular_Respuesta,5000);
}

function f_js_ApuestaAnular_Respuesta()
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
      if (json_resp.Resultado)
      {
        if (json_resp.Resultado != "1")
        {
          alert("Su apuesta no pudo ser anulada!");
        }
        else
        {
          alert("Apuesta Anulada!");
          document.frm_recargar.submit();
        }
      }
      else
      {
console.log(json_resp);
        alert("Error no identificado");
      }
      return;
    }
  }
}

function f_js_toggleClass(pObj,pClass1,pClass2)
{
  pObj.className = ((pObj.className != pClass1)?pClass1:pClass2);
}

function f_js_id_toggleClass(pID,pClass1,pClass2)
{
  var el_div = el_id(pID);
  if (el_div == null)
  {
    console.log("Error : No se pudo encontrar el id '" + pID + "'");
    return;
  }
  el_div.className = ((el_div.className != pClass1)?pClass1:pClass2);
}

function f_js_CargarSaldo()
{
  var el_div = el_id("telon");
  el_div.style.display="block";
  el_div.className="telon telonAparecer";
  el_id("telonTitulo").innerHTML = "Cargar Saldo";
  el_id("telonContenedor").className = "contenedorAngosto";
  el_div = el_id("telonContenido");
  el_div.innerHTML = "<div class='TelonTextoItem'>Codigo de Cliente :</div><input type=text class='input' name=CargasCodigoUsuario><br>"
  			+ "<div class='TelonTextoItem'>Monto a Cargar :</div><input type=text class='input' name=CargasMonto><br>"
  			+ "<input type=button value='Cargar Monto' onClick='f_js_cargarMonto()' style='clear:both;float:right;'><br>";
//  el_id('menuAdministracion').className = 'menuAdministracion menuAdministracionOculto';
}

function f_js_PagarPremio()
{
  var el_div = el_id("telon");
  el_div.style.display="block";
  el_div.className="telon telonAparecer";
  el_id("telonTitulo").innerHTML = "Pagar Premios";
  el_div = el_id("telonContenido");
  el_div.innerHTML = "<table border=0>"
				+ "<tr><td>Codigo de Tirilla :</td><td><input type=text name=PagosCodigo></td></tr>"
  				+ "<tr><td>Verificacion :</td><td><input type=text name=PagosClave></td></tr>"
  				+ "<tr><td colspan=2><span id='PagarSpanMonto' style='Display:none;'>Monto a pagar : 0</span></td></tr>"
  				+ "<tr><td colspan=2><input type=button value='Consultar' onClick='f_js_PremioConsultar()' id='PagarBotonConsultar'></td></tr>"
	  			+ "<tr><td colspan=2><input type=button value='Pagar Premio' onClick='f_js_PremioPagar()' id='PagarBotonPagar' style='display:none;'></td></tr>"
			+ "</table>";
//  el_id('menuAdministracion').className = 'menuAdministracion menuAdministracionOculto';
}

function f_js_TirillaConsultar()
{
  var el_div = el_id("telon");
  el_div.style.display="block";
  el_div.className="telon telonAparecer";
  el_id("telonTitulo").innerHTML = "Consultar Tirilla";
  el_div = el_id("telonContenido");
  el_div.innerHTML = "<table border=0>"
				+ "<tr><td>Codigo de Tirilla :</td><td><input type=text name=PagosCodigo></td></tr>"
  				+ "<tr><td>Verificacion :</td><td><input type=text name=PagosClave></td></tr>"
  				+ "<tr><td colspan=2><span id='PagarSpanMonto' style='Display:none;'>Monto a pagar : 0</span></td></tr>"
  				+ "<tr><td colspan=2><input type=button value='Consultar' onClick='f_js_TirillaDatosConsultar()' id='PagarBotonConsultar'></td></tr>"
			+ "</table>";
//  el_id('menuAdministracion').className = 'menuAdministracion menuAdministracionOculto';
}

function f_js_UsuarioCrear()
{
  var el_div = el_id("telon");
  el_div.style.display="block";
  el_div.className="telon telonAparecer";
  el_id("telonTitulo").innerHTML = "Crear Usuario";
  el_div = el_id("telonContenido");
  el_div.innerHTML = 	"<table border=0>"
				+ "<tr><td align=right>Nombre de Usuario :</td><td><input type=text name=UsuarioCrearNombre class=input></td></tr>"
  				+ "<tr><td align=right>Clave :</td><td><input type=text name=UsuarioCrearClave></td></tr>"
  				+ "<tr><td align=right>Aprobador :</td><td><input type=text name=UsuarioCrearAprobador></td></tr>"
	  			+ "<tr><th colspan=2><input type=button value='Crear Usuario' onClick='f_js_UsuarioCrearConsumir()' class=input></td></tr>"
			+ "</table>";
//  el_id('menuAdministracion').className = 'menuAdministracion menuAdministracionOculto';
}

function f_js_UsuarioClaveCambiar()
{
  var el_div = el_id("telon");
  el_div.style.display="block";
  el_div.className="telon telonAparecer";
  el_id("telonTitulo").innerHTML = "Cambio de Clave";
  el_div = el_id("telonContenido");
  el_div.innerHTML =	"<table border=0>"
				+ "<tr><td align=right>Clave anterior :</td><td><input type=password name=UsuarioClaveAnterior></td></tr>"
				+ "<tr><td align=right>Nueva Clave :</td><td><input type=password name=UsuarioClaveNueva></td></tr>"
				+ "<tr><td align=right>Repetir Clave :</td><td><input type=password name=UsuarioClaveNueva2></td></tr>"
				+ "<tr><td align=center colspan=2><input type=button value='Cambiar Clave' onClick='f_js_UsuarioClaveCambiarGrabar()'></td></tr>"
			+ "</table>";
//  el_id('menuAdministracion').className = 'menuAdministracion menuAdministracionOculto';
}

function f_js_Recargas()
{
  var el_div = el_id("telon");
  el_div.style.display="block";
  el_div.className="telon telonAparecer";
  el_id("telonTitulo").innerHTML = "Recargas";
  el_div = el_id("telonContenido");
  el_div.innerHTML = "<table border=1>"
			+ "<tr>"
				+ "<td align=right>Codigo de Usuario Destino :</td>"
				+ "<td><input type=text name=DestinoCodigo></td>"
			+ "</tr>"
			+ "<tr>"
				+ "<td align=right>Monto :</td>"
				+ "<td><input type=text name=Monto></td>"
			+ "</tr>"
			+ "<tr>"
				+ "<td align=right>Clave :</td>"
				+ "<td><input type=password name=RecClave></td>"
			+ "</tr>"
			+ "<tr>"
				+ "<td colspan=2 align=center><input type=button value='Cargar Monto' onClick='f_js_RecargasGrabar()' style='float:none;'></td>"
			+ "</tr>"
		+ "</table>";
//  el_id('menuAdministracion').className = 'menuAdministracion menuAdministracionOculto';
}

function f_js_ocultarTelon()
{
  el_id("telon").className="telon telonOcultar";
  setTimeout(function(){ el_id("telon").style.display="none";el_id("telonContenedor").className = "contenedor";},900);
}

function f_js_cargarMonto()
{
//  alert("cargando " + document.frm.CargasCodigoUsuario.value + " " + document.frm.CargasMonto.value);
  nCargas = -100;
  f_http_post(cliente_http
             ,"SportSiete.php"
             ,"method=cargarMonto"
		+ "&Sesion=" + v_cwf_id
		+ "&Cliente=" + document.frm.CargasCodigoUsuario.value
		+ "&Monto=" + document.frm.CargasMonto.value
             ,f_js_cargarMonto_Respuesta,5000);

//cargarMonto($Sesion,$Cliente,$Monto)
}

function f_js_cargarMonto_Respuesta()
{
  var json_resp = f_js_procesar_respuesta_generica(cliente_http,true);
  if (json_resp)
  {
//console.log(json_resp);
    if (json_resp.ErrorDescripcion)
    {
      alert(json_resp.ErrorDescripcion);
    }
    else if (json_resp.NuevoSaldo)
    {
      alert("Nuevo Saldo : " + json_resp.NuevoSaldo);
      f_js_ocultarTelon();
      nCargas=0;
//      f_js_Cargar();

    }
    else
    {
      alert("La respuesta no contiene datos de la transaccion");
    }
  }
}

function f_js_PremioConsultar()
{
  nCargas = -500;
  f_http_post(cliente_http
             ,"SportSiete.php"
             ,"method=PremioConsultar"
		+ "&Sesion=" + v_cwf_id
		+ "&Codigo=" + document.frm.PagosCodigo.value
		+ "&Clave=" + document.frm.PagosClave.value
             ,f_js_PremioConsultar_Respuesta,5000);

}

function f_js_PremioConsultar_Respuesta()
{
  var el_obj;
  var json_resp = f_js_procesar_respuesta_generica(cliente_http,true);
  if (json_resp)
  {
//console.log(json_resp);
    if (json_resp.ErrorDescripcion)
    {
      alert(json_resp.ErrorDescripcion);
    }
    else if (json_resp.PremioMonto)
    {
      if (parseFloat(json_resp.PremioMonto) < 0)
      {
        alert("Este premio ya fue cobrado");
        return;
      }
      else if (parseFloat(json_resp.PremioMonto) == 0)
      {
        alert("Esta tirilla NO ES ganadora");
        return;
      }

      el_obj = el_id('PagarSpanMonto');
      el_obj.innerHTML = "Monto a pagar : " + json_resp.PremioMonto;
      el_obj.style.display = '';

      el_id('PagarBotonConsultar').style.display = "none";

      el_id('PagarBotonPagar').style.display = "";
      nCargas = 1;
    }
    else
    {
      alert("La respuesta no contiene datos de la transaccion");
    }
  }
}

function f_js_PremioPagar()
{
  nCargas = -500;
  f_http_post(cliente_http
             ,"SportSiete.php"
             ,"method=PremioPagar"
		+ "&Sesion=" + v_cwf_id
		+ "&Codigo=" + document.frm.PagosCodigo.value
		+ "&Clave=" + document.frm.PagosClave.value
             ,f_js_PremioPagar_Respuesta,5000);

}

function f_js_PremioPagar_Respuesta()
{
  var el_obj;
  var json_resp = f_js_procesar_respuesta_generica(cliente_http,true);
  if (json_resp)
  {
//console.log(json_resp);
    if (json_resp.ErrorDescripcion)
    {
      alert(json_resp.ErrorDescripcion);
    }
    else if (json_resp.Respuesta)
    {
      if (json_resp.Respuesta == "1")
      {
        alert("Premio pagado exitosamente");
        f_js_ocultarTelon();
        nCargas = 0;
        return;
      }
      else
      {
        alert("Respuesta no esperada '" + json_resp.Respuesta + "'");
        f_js_ocultarTelon();
        nCargas = 0;
        return;
      }
    }
    else
    {
      alert("La respuesta no contiene datos de la transaccion");
    }
  }
}

function f_js_TirillaDatosConsultar()
{
  nCargas = -500;
  f_http_post(cliente_http
             ,"SportSiete.php"
             ,"method=TirillaConsultar"
		+ "&Sesion=" + v_cwf_id
		+ "&Codigo=" + document.frm.PagosCodigo.value
		+ "&Clave=" + document.frm.PagosClave.value
             ,f_js_TirillaDatosConsultar_Respuesta,5000);

}

function f_js_TirillaDatosConsultar_Respuesta()
{
  var el_obj;
  var json_resp = f_js_procesar_respuesta_generica(cliente_http,true);
console.log(json_resp);
  if (json_resp)
  {
//console.log(json_resp);
    if (json_resp.ErrorDescripcion)
    {
      alert(json_resp.ErrorDescripcion);
    }
    else if (json_resp.Resultado)
    {
      var i;
      var tmp_txt = "";
      tmp_txt += "<table border=0 width='100%'><tr><th>Encuentro</th><th>Fecha</th><th>Marcador</th><th>Apuesta</th><th>Resultado</th></tr>";
      for (i = 0; i  < json_resp.Resultado.length; i++)
      {
        tmp_txt += "<tr><td>" + json_resp.Resultado[i].local + " vs " + json_resp.Resultado[i].visitante + "</td>"
			+ "<td>" + json_resp.Resultado[i].instante.substr(0,16) + "</td>"
			+ "<td>" + json_resp.Resultado[i].info_resultados + "</td>"
			+ "<td>" + json_resp.Resultado[i].descripcion + "</td>"
			+ "<td>" + json_resp.Resultado[i].resultado + "</td>"
		+ "</tr>";
      }
      tmp_txt += "</table>";
      el_obj = el_id('PagarSpanMonto');
      el_obj.innerHTML = tmp_txt;
      el_obj.style.display = '';

      nCargas = 1;
    }
    else
    {
      alert("La respuesta no contiene datos de la transaccion");
    }
  }
}

function f_js_MarcarPartidos()
{
  var el_div = el_id("telon");
  el_div.style.display="block";
  el_div.className="telon telonAparecer";
  el_id("telonTitulo").innerHTML = "Marcar Partidos";
  el_div = el_id("telonContenido");
  el_div.innerHTML = "Cargando Partidos...";
//  el_id('menuAdministracion').className = 'menuAdministracion menuAdministracionOculto';
  nCargas = -500;
  f_http_post(cliente_http
             ,"SportSiete.php"
             ,"method=EncuentrosPorMarcar"
		+ "&Sesion=" + v_cwf_id
             ,f_js_MarcarPartidos_Respuesta,5000);

}

function f_js_MarcarPartidos_Respuesta()
{
  var el_obj;
  var json_resp = f_js_procesar_respuesta_generica(cliente_http,true);
  if (json_resp)
  {
//console.log(json_resp);
    if (json_resp.ErrorDescripcion)
    {
      alert(json_resp.ErrorDescripcion);
    }
    else if (json_resp.Encuentros)
    {
      var i;
      var el_div = el_id("telonContenido");
      var tmp_txt;
      tmp_txt = "<div style='max-height:70vh;overflow:auto;'>";
      tmp_txt += "<table border=0><tr><th>instante</th><th>equipos</th><th># Apuestas</th><th>Liga</th></tr>";
      for (i = 0; i < json_resp.Encuentros.length; i++)
      {
        tmp_txt += "<tr style='cursor:pointer;' onClick='f_js_MarcadorPartido(\"" + json_resp.Encuentros[i].deporte_id + "\",\"" + json_resp.Encuentros[i].codigo + "\",\"" + json_resp.Encuentros[i].local + " - " + json_resp.Encuentros[i].visitante + "\",\"" + json_resp.Encuentros[i].instante + "\")'>"
			+ "<td>" + json_resp.Encuentros[i].instante + "</td>"
			+ "<td>" +	((json_resp.Encuentros[i].deporte_id == "1")
						?"Futbol : "
						:((json_resp.Encuentros[i].deporte_id == "2")
							?"Basket : "
							:((json_resp.Encuentros[i].deporte_id == "5")
								?"Tennis : "
								:((json_resp.Encuentros[i].deporte_id == "8")
									?"Beisbol : "
									:""
								)
							)
						)
					)
				+ json_resp.Encuentros[i].local + " - " + json_resp.Encuentros[i].visitante + "</td>"
			+ "<td>" + json_resp.Encuentros[i].n + "</td>"
			+ "<td>" + json_resp.Encuentros[i].liga + "</td>"
		+ "</tr>";
      }
      tmp_txt += "</table>";
      tmp_txt += "</div>";
      el_div.innerHTML = tmp_txt;
//      f_js_ocultarTelon();
      nCargas = 1;
      return;
    }
    else
    {
console.log(json_resp);
      alert("La respuesta no contiene datos de la transaccion");
    }
  }
}

function f_js_MarcadorPartido(pDeporte,pCodigo,pDescripcion,pInstante)
{
  var el_div = el_id("telon");
  el_div.style.display="block";
  el_div.className="telon telonAparecer";
  el_div = el_id("telonContenido");
  var tmp_txt;
  tmp_txt = "<div style='max-height:70vh;overflow:auto;'>"
		+ "<input type=hidden name=Deporte value='" + pDeporte +  "'>"
		+ "<input type=hidden name=Encuentro value='" + pCodigo +  "'>"
		+ "<table border=0 width=100% height=100%>"
			+ "<tr><th colspan=4></th></tr>";
  switch (pDeporte)
  {
	case 2:
	case "2":
		tmp_txt	+= "<tr><th></th><th colspan=2 align=center>Basketball : </th><th></th></tr>"
			+ "<tr><th></th><th colspan=2 align=center>" + pDescripcion + "</th><th></th></tr>"
			+ "<tr><th></th><th colspan=2 align=center>" + pInstante + "</th><th></th></tr>"
			+ "<tr><th></th><th width=30% align=right>Tiempo 1</th><th width=30%><input type=text size=3 name=T1_1><input type=text size=3 name=T1_2></th><th></th></tr>"
			+ "<tr><th></th><th width=30% align=right>Tiempo 2</th><th width=30%><input type=text size=3 name=T2_1><input type=text size=3 name=T2_2></th><th></th></tr>"
			+ "<tr><th></th><th width=30% align=right>Tiempo 3</th><th width=30%><input type=text size=3 name=T3_1><input type=text size=3 name=T3_2></th><th></th></tr>"
			+ "<tr><th></th><th width=30% align=right>Tiempo 4</th><th width=30%><input type=text size=3 name=T4_1><input type=text size=3 name=T4_2></th><th></th></tr>"
			+ "<tr><th></th><th width=30% align=right>Tiempo 5</th><th width=30%><input type=text size=3 name=T5_1><input type=text size=3 name=T5_2></th><th></th></tr>"
			+ "<tr><th></th><th width=30% align=right>Tiempo 6</th><th width=30%><input type=text size=3 name=T6_1><input type=text size=3 name=T6_2></th><th></th></tr>"
			+ "<tr><th></th><th colspan=2 align=center><input type=button value='Grabar' style='float:none;' onClick='f_js_MarcadorPartidoGrabarBasketball()'></th><th></th></tr>";
		break;
	case 5:
	case "5":
		tmp_txt	+= "<tr><th></th><th colspan=2 align=center>Tenis : </th><th></th></tr>"
			+ "<tr><th></th><th colspan=2 align=center>" + pDescripcion + "</th><th></th></tr>"
			+ "<tr><th></th><th colspan=2 align=center>" + pInstante + "</th><th></th></tr>"
			+ "<tr><th></th><th width=30% align=right>Set 1</th><th width=30%><input type=text size=3 name=S1_1><input type=text size=3 name=S1_2></th><th></th></tr>"
			+ "<tr><th></th><th width=30% align=right>Set 2</th><th width=30%><input type=text size=3 name=S2_1><input type=text size=3 name=S2_2></th><th></th></tr>"
			+ "<tr><th></th><th width=30% align=right>Set 3</th><th width=30%><input type=text size=3 name=S3_1><input type=text size=3 name=S3_2></th><th></th></tr>"
			+ "<tr><th></th><th width=30% align=right>Set 4</th><th width=30%><input type=text size=3 name=S4_1><input type=text size=3 name=S4_2></th><th></th></tr>"
			+ "<tr><th></th><th width=30% align=right>Set 5</th><th width=30%><input type=text size=3 name=S5_1><input type=text size=3 name=S5_2></th><th></th></tr>"
			+ "<tr><th></th><th width=30% align=right>Set 6</th><th width=30%><input type=text size=3 name=S6_1><input type=text size=3 name=S6_2></th><th></th></tr>"
			+ "<tr><th></th><th width=30% align=right>Set 7</th><th width=30%><input type=text size=3 name=S7_1><input type=text size=3 name=S7_2></th><th></th></tr>"
			+ "<tr><th></th><th width=30% align=right>Set 8</th><th width=30%><input type=text size=3 name=S8_1><input type=text size=3 name=S8_2></th><th></th></tr>"
			+ "<tr><th></th><th width=30% align=right>Set 9</th><th width=30%><input type=text size=3 name=S9_1><input type=text size=3 name=S9_2></th><th></th></tr>"
			+ "<tr><th></th><th width=30% align=right>Set 10</th><th width=30%><input type=text size=3 name=S10_1><input type=text size=3 name=S10_2></th><th></th></tr>"
			+ "<tr><th></th><th colspan=2 align=center><input type=button value='Grabar' style='float:none;' onClick='f_js_MarcadorPartidoGrabarTenis()'></th><th></th></tr>";
		break;
	case 8:
	case "8":
		tmp_txt	+= "<tr><th></th><th colspan=2 align=center>Beisbol : </th><th></th></tr>"
			+ "<tr><th></th><th colspan=2 align=center>" + pDescripcion + "</th><th></th></tr>"
			+ "<tr><th></th><th colspan=2 align=center>" + pInstante + "</th><th></th></tr>"
			+ "<tr><th></th><th width=30% align=right>Entrada 1</th><th width=30%><input type=text size=3 name=E1_1><input type=text size=3 name=E1_2></th><th></th></tr>"
			+ "<tr><th></th><th width=30% align=right>Entrada 2</th><th width=30%><input type=text size=3 name=E2_1><input type=text size=3 name=E2_2></th><th></th></tr>"
			+ "<tr><th></th><th width=30% align=right>Entrada 3</th><th width=30%><input type=text size=3 name=E3_1><input type=text size=3 name=E3_2></th><th></th></tr>"
			+ "<tr><th></th><th width=30% align=right>Entrada 4</th><th width=30%><input type=text size=3 name=E4_1><input type=text size=3 name=E4_2></th><th></th></tr>"
			+ "<tr><th></th><th width=30% align=right>Entrada 5</th><th width=30%><input type=text size=3 name=E5_1><input type=text size=3 name=E5_2></th><th></th></tr>"
			+ "<tr><th></th><th width=30% align=right>Entrada 6</th><th width=30%><input type=text size=3 name=E6_1><input type=text size=3 name=E6_2></th><th></th></tr>"
			+ "<tr><th></th><th width=30% align=right>Entrada 7</th><th width=30%><input type=text size=3 name=E7_1><input type=text size=3 name=E7_2></th><th></th></tr>"
			+ "<tr><th></th><th width=30% align=right>Entrada 8</th><th width=30%><input type=text size=3 name=E8_1><input type=text size=3 name=E8_2></th><th></th></tr>"
			+ "<tr><th></th><th width=30% align=right>Entrada 9</th><th width=30%><input type=text size=3 name=E9_1><input type=text size=3 name=E9_2></th><th></th></tr>"
			+ "<tr><th></th><th width=30% align=right>Entrada Extra</th><th width=30%><input type=text size=3 name=EE_1><input type=text size=3 name=EE_2></th><th></th></tr>"
			+ "<tr><th></th><th colspan=2 align=center><input type=button value='Grabar' style='float:none;' onClick='f_js_MarcadorPartidoGrabarBeisbol()'></th><th></th></tr>";
		break;
	default:
		tmp_txt	+= "<tr><th></th><th colspan=2 align=center>Futbol : </th><th></th></tr>"
			+ "<tr><th></th><th colspan=2 align=center>" + pDescripcion + "</th><th></th></tr>"
			+ "<tr><th></th><th colspan=2 align=center>" + pInstante + "</th><th></th></tr>"
			+ "<tr><th></th><th width=30% align=right>Primer Tiempo</th><th width=30%><input type=text size=3 name=PTL><input type=text size=3 name=PTV></th><th></th></tr>"
			+ "<tr><th></th><th width=30% align=right>Resultado Final</th><th width=30%><input type=text size=3 name=STL><input type=text size=3 name=STV></th><th></th></tr>"
			+ "<tr><th></th><th colspan=2 align=center><input type=button value='Grabar' style='float:none;' onClick='f_js_MarcadorPartidoGrabarFutbol()'></th><th></th></tr>";
		break;
  }
  tmp_txt +=		"<tr><th colspan=4></th></tr>"
		+ "</table>";
	+ "</div>";
  el_div.innerHTML = tmp_txt;
}

function f_js_MarcadorPartidoGrabarFutbol()
{

  if (document.frm.PTL.value == 'A' && document.frm.PTV.value == 'A' && document.frm.STL.value == 'A' && document.frm.STV.value == 'A'){
    alert("Anulando partido");
  }
  else if (isNaN(parseInt(document.frm.PTL.value)))
  {
    alert("Corrija el marcador de Local-Primer Tiempo");
    return;
  }
  else if (isNaN(parseInt(document.frm.PTV.value)))
  {
    alert("Corrija el marcador de Visitante-Primer Tiempo");
    return;
  }
  else if (isNaN(parseInt(document.frm.STL.value)))
  {
    alert("Corrija el marcador de Local-Resultado Final");
    return;
  }
  else if (isNaN(parseInt(document.frm.STV.value)))
  {
    alert("Corrija el marcador de Visitante-Resultado Final");
    return;
  }
  else if ( parseInt(document.frm.PTL.value) > parseInt(document.frm.STL.value) )
  {
    alert("El Resultado Final del Local no puede ser menor que el Primer Tiempo");
    return;
  }
  else if ( parseInt(document.frm.PTV.value) > parseInt(document.frm.STV.value) )
  {
    alert("El Resultado Final del Visitante no puede ser menor que el Primer Tiempo");
    return;
  }

  f_http_post(cliente_http
             ,"SportSiete.php"
             ,"method=EncuentroMarcadorGrabar"
		+ "&Sesion=" + v_cwf_id
		+ "&Deporte=" + document.frm.Deporte.value
		+ "&Encuentro=" + document.frm.Encuentro.value
		+ "&PTL=" + document.frm.PTL.value
		+ "&PTV=" + document.frm.PTV.value
		+ "&STL=" + document.frm.STL.value
		+ "&STV=" + document.frm.STV.value
             ,f_js_MarcadorPartidoGrabar_Respuesta,5000);
  el_id("telonContenido").innerHTML = "Grabando...";
}

function f_js_MarcadorPartidoGrabarTenis()
{

  if (isNaN(parseInt(document.frm.S1_1.value))
	|| isNaN(parseInt(document.frm.S1_2.value))
	|| isNaN(parseInt(document.frm.S2_1.value))
	|| isNaN(parseInt(document.frm.S2_2.value))
	|| ( document.frm.S3_1.value != "" && isNaN(parseInt(document.frm.S3_1.value)))
	|| ( document.frm.S3_2.value != "" && isNaN(parseInt(document.frm.S3_2.value)))
	|| ( document.frm.S4_1.value != "" && isNaN(parseInt(document.frm.S4_1.value)))
	|| ( document.frm.S4_2.value != "" && isNaN(parseInt(document.frm.S4_2.value)))
	|| ( document.frm.S5_1.value != "" && isNaN(parseInt(document.frm.S5_1.value)))
	|| ( document.frm.S5_2.value != "" && isNaN(parseInt(document.frm.S5_2.value)))
	|| ( document.frm.S6_1.value != "" && isNaN(parseInt(document.frm.S6_1.value)))
	|| ( document.frm.S6_2.value != "" && isNaN(parseInt(document.frm.S6_2.value)))
	|| ( document.frm.S7_1.value != "" && isNaN(parseInt(document.frm.S7_1.value)))
	|| ( document.frm.S7_2.value != "" && isNaN(parseInt(document.frm.S7_2.value)))
	|| ( document.frm.S8_1.value != "" && isNaN(parseInt(document.frm.S8_1.value)))
	|| ( document.frm.S8_2.value != "" && isNaN(parseInt(document.frm.S8_2.value)))
	|| ( document.frm.S9_1.value != "" && isNaN(parseInt(document.frm.S9_1.value)))
	|| ( document.frm.S9_2.value != "" && isNaN(parseInt(document.frm.S9_2.value)))
	|| ( document.frm.S10_1.value != "" && isNaN(parseInt(document.frm.S10_1.value)))
	|| ( document.frm.S10_2.value != "" && isNaN(parseInt(document.frm.S10_2.value)))
	)
  {
    alert("Corrija los valores... tiene un error");
    return;
  }

  f_http_post(cliente_http
             ,"SportSiete.php"
             ,"method=EncuentroMarcadorGrabar"
		+ "&Sesion=" + v_cwf_id
		+ "&Deporte=" + document.frm.Deporte.value
		+ "&Encuentro=" + document.frm.Encuentro.value
		+ "&S1_1=" + document.frm.S1_1.value
		+ "&S1_2=" + document.frm.S1_2.value
		+ "&S2_1=" + document.frm.S2_1.value
		+ "&S2_2=" + document.frm.S2_2.value
		+ "&S3_1=" + document.frm.S3_1.value
		+ "&S3_2=" + document.frm.S3_2.value
		+ "&S4_1=" + document.frm.S4_1.value
		+ "&S4_2=" + document.frm.S4_2.value
		+ "&S5_1=" + document.frm.S5_1.value
		+ "&S5_2=" + document.frm.S5_2.value
		+ "&S6_1=" + document.frm.S6_1.value
		+ "&S6_2=" + document.frm.S6_2.value
		+ "&S7_1=" + document.frm.S7_1.value
		+ "&S7_2=" + document.frm.S7_2.value
		+ "&S8_1=" + document.frm.S8_1.value
		+ "&S8_2=" + document.frm.S8_2.value
		+ "&S9_1=" + document.frm.S9_1.value
		+ "&S9_2=" + document.frm.S9_2.value
		+ "&S10_1=" + document.frm.S10_1.value
		+ "&S10_2=" + document.frm.S10_2.value
             ,f_js_MarcadorPartidoGrabar_Respuesta,5000);
  el_id("telonContenido").innerHTML = "Grabando...";
}

function f_js_MarcadorPartidoGrabarBasketball()
{

  if (isNaN(parseInt(document.frm.T1_1.value))
	|| isNaN(parseInt(document.frm.T1_2.value))
	|| isNaN(parseInt(document.frm.T2_1.value))
	|| isNaN(parseInt(document.frm.T2_2.value))
	|| isNaN(parseInt(document.frm.T3_1.value))
	|| isNaN(parseInt(document.frm.T3_2.value))
	|| isNaN(parseInt(document.frm.T4_1.value))
	|| isNaN(parseInt(document.frm.T4_2.value))
	|| ( document.frm.T5_1.value != "" && isNaN(parseInt(document.frm.T5_1.value)))
	|| ( document.frm.T5_2.value != "" && isNaN(parseInt(document.frm.T5_2.value)))
	|| ( document.frm.T6_1.value != "" && isNaN(parseInt(document.frm.T6_1.value)))
	|| ( document.frm.T6_2.value != "" && isNaN(parseInt(document.frm.T6_2.value)))
	)
  {
    alert("Corrija los valores... tiene un error");
    return;
  }

  f_http_post(cliente_http
             ,"SportSiete.php"
             ,"method=EncuentroMarcadorGrabar"
		+ "&Sesion=" + v_cwf_id
		+ "&Deporte=" + document.frm.Deporte.value
		+ "&Encuentro=" + document.frm.Encuentro.value
		+ "&S1_1=" + document.frm.T1_1.value
		+ "&S1_2=" + document.frm.T1_2.value
		+ "&S2_1=" + document.frm.T2_1.value
		+ "&S2_2=" + document.frm.T2_2.value
		+ "&S3_1=" + document.frm.T3_1.value
		+ "&S3_2=" + document.frm.T3_2.value
		+ "&S4_1=" + document.frm.T4_1.value
		+ "&S4_2=" + document.frm.T4_2.value
		+ "&S5_1=" + document.frm.T5_1.value
		+ "&S5_2=" + document.frm.T5_2.value
		+ "&S6_1=" + document.frm.T6_1.value
		+ "&S6_2=" + document.frm.T6_2.value
             ,f_js_MarcadorPartidoGrabar_Respuesta,5000);
  el_id("telonContenido").innerHTML = "Grabando...";
}

function f_js_MarcadorPartidoGrabarBeisbol()
{

  if (isNaN(parseInt(document.frm.E1_1.value))
	|| isNaN(parseInt(document.frm.E1_2.value))
	|| ( document.frm.E2_1.value != "" && isNaN(parseInt(document.frm.E2_1.value)))
	|| ( document.frm.E2_2.value != "" && isNaN(parseInt(document.frm.E2_2.value)))
	|| ( document.frm.E3_1.value != "" && isNaN(parseInt(document.frm.E3_1.value)))
	|| ( document.frm.E3_2.value != "" && isNaN(parseInt(document.frm.E3_2.value)))
	|| ( document.frm.E4_1.value != "" && isNaN(parseInt(document.frm.E4_1.value)))
	|| ( document.frm.E4_2.value != "" && isNaN(parseInt(document.frm.E4_2.value)))
	|| ( document.frm.E5_1.value != "" && isNaN(parseInt(document.frm.E5_1.value)))
	|| ( document.frm.E5_2.value != "" && isNaN(parseInt(document.frm.E5_2.value)))
	|| ( document.frm.E6_1.value != "" && isNaN(parseInt(document.frm.E6_1.value)))
	|| ( document.frm.E6_2.value != "" && isNaN(parseInt(document.frm.E6_2.value)))
	|| ( document.frm.E7_1.value != "" && isNaN(parseInt(document.frm.E7_1.value)))
	|| ( document.frm.E7_2.value != "" && isNaN(parseInt(document.frm.E7_2.value)))
	|| ( document.frm.E8_1.value != "" && isNaN(parseInt(document.frm.E8_1.value)))
	|| ( document.frm.E8_2.value != "" && isNaN(parseInt(document.frm.E8_2.value)))
	|| ( document.frm.E9_1.value != "" && isNaN(parseInt(document.frm.E9_1.value)))
	|| ( document.frm.E9_2.value != "" && isNaN(parseInt(document.frm.E9_2.value)))
	|| ( document.frm.EE_1.value != "" && isNaN(parseInt(document.frm.EE_1.value)))
	|| ( document.frm.EE_2.value != "" && isNaN(parseInt(document.frm.EE_2.value)))
	)
  {
    alert("Corrija los valores... tiene un error");
    return;
  }

  f_http_post(cliente_http
             ,"SportSiete.php"
             ,"method=EncuentroMarcadorGrabar"
		+ "&Sesion=" + v_cwf_id
		+ "&Deporte=" + document.frm.Deporte.value
		+ "&Encuentro=" + document.frm.Encuentro.value
		+ "&S1_1=" + document.frm.E1_1.value
		+ "&S1_2=" + document.frm.E1_2.value
		+ "&S2_1=" + document.frm.E2_1.value
		+ "&S2_2=" + document.frm.E2_2.value
		+ "&S3_1=" + document.frm.E3_1.value
		+ "&S3_2=" + document.frm.E3_2.value
		+ "&S4_1=" + document.frm.E4_1.value
		+ "&S4_2=" + document.frm.E4_2.value
		+ "&S5_1=" + document.frm.E5_1.value
		+ "&S5_2=" + document.frm.E5_2.value
		+ "&S6_1=" + document.frm.E6_1.value
		+ "&S6_2=" + document.frm.E6_2.value
		+ "&S6_1=" + document.frm.E7_1.value
		+ "&S6_2=" + document.frm.E7_2.value
		+ "&S6_1=" + document.frm.E8_1.value
		+ "&S6_2=" + document.frm.E8_2.value
		+ "&S6_1=" + document.frm.E9_1.value
		+ "&S6_2=" + document.frm.E9_2.value
		+ "&S6_1=" + document.frm.EE_1.value
		+ "&S6_2=" + document.frm.EE_2.value
             ,f_js_MarcadorPartidoGrabar_Respuesta,5000);
  el_id("telonContenido").innerHTML = "Grabando...";
}

function f_js_MarcadorPartidoGrabar_Respuesta()
{
  var json_resp = f_js_procesar_respuesta_generica(cliente_http,true);
  if (json_resp)
  {
//console.log(json_resp);
    if (json_resp.ErrorDescripcion)
    {
      alert(json_resp.ErrorDescripcion);
    }
    else if (json_resp.Respuesta )
    {
      if (json_resp.Respuesta == "1")
        alert("Marcador Grabado");
      else
        alert("No se pudo grabar el marcador");
      f_js_MarcarPartidos();
    }
    else
    {
console.log(json_resp);
      alert("La respuesta no contiene datos de la transaccion");
    }
  }
}

function f_js_UsuarioCrearConsumir()
{
//  alert("cargando " + document.frm.CargasCodigoUsuario.value + " " + document.frm.CargasMonto.value);
  nCargas = -100;
  f_http_post(cliente_http
             ,"SportSiete.php"
             ,"method=UsuarioCrear"
		+ "&Sesion=" + v_cwf_id
		+ "&UsuarioNombre=" + document.frm.UsuarioCrearNombre.value
		+ "&UsuarioClave=" + document.frm.UsuarioCrearClave.value
		+ "&UsuarioAprobador=" + document.frm.UsuarioCrearAprobador.value
             ,f_js_UsuarioCrearConsumir_Respuesta,5000);

//cargarMonto($Sesion,$Cliente,$Monto)
}

function f_js_UsuarioCrearConsumir_Respuesta()
{
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
      alert("Codigo del Usuario : " + json_resp.UsuarioCodigo);
      f_js_ocultarTelon();
      nCargas = 1;
    }
    else
    {
      alert("La respuesta no contiene datos de la transaccion");
    }
  }
}

function f_js_RecargasGrabar()
{
  nCargas = -500;
  f_http_post(cliente_http
             ,"SportSiete.php"
             ,"method=Recargar"
		+ "&Sesion=" + v_cwf_id
		+ "&DestinoCodigo=" + document.frm.DestinoCodigo.value
		+ "&Monto=" + document.frm.Monto.value
		+ "&Clave=" + document.frm.RecClave.value
             ,f_js_RecargasGrabar_Respuesta,5000);

}

function f_js_RecargasGrabar_Respuesta()
{
  var el_obj;
  var json_resp = f_js_procesar_respuesta_generica(cliente_http,true);
console.log(json_resp);
  if (json_resp)
  {
//console.log(json_resp);
    if (json_resp.ErrorDescripcion)
    {
      alert(json_resp.ErrorDescripcion);
    }
    else if (json_resp.Respuesta)
    {
      if (json_resp.Respuesta == "1")
      {
        alert("Recarga exitosa");
        f_js_ocultarTelon();
//        f_js_CargarDatosUsuario();
        document.frm_imprimir_recarga.codigo.value=json_resp.codigoRecarga;
        document.frm_imprimir_recarga.submit();
        setTimeout(function() { f_js_CargarPagina('./'); }, 200);
        nCargas = -1;
        return;
      }
      else
      {
        alert("Respuesta no esperada '" + json_resp.Respuesta + "'");
        f_js_ocultarTelon();
        nCargas = 0;
        return;
      }
    }
    else
    {
      alert("La respuesta no contiene datos de la transaccion");
      nCargas = 0;
    }
  }
}

function f_js_UsuarioClaveCambiarGrabar()
{
  if (document.frm.UsuarioClaveNueva.value.trim().length < 4)
  {
    alert("La clave es muy corta");
    return ;
  }
  if (document.frm.UsuarioClaveNueva.value.trim() != document.frm.UsuarioClaveNueva2.value.trim())
  {
    alert("Las dos escrituras de la nueva clave deben coincidir");
    return ;
  }

  nCargas = -100;
  f_http_post(cliente_http
             ,"SportSiete.php"
             ,"method=UsuarioClaveCambiar"
		+ "&Sesion=" + v_cwf_id
		+ "&ClaveAnterior=" + document.frm.UsuarioClaveAnterior.value.trim()
		+ "&ClaveNueva=" + document.frm.UsuarioClaveNueva.value.trim()
             ,f_js_UsuarioClaveCambiarGrabar_Respuesta,5000);
}

function f_js_UsuarioClaveCambiarGrabar_Respuesta()
{
  var json_resp = f_js_procesar_respuesta_generica(cliente_http,true);
  if (json_resp)
  {
//console.log(json_resp);
    if (json_resp.ErrorDescripcion)
    {
      alert(json_resp.ErrorDescripcion);
    }
    else if (json_resp.Respuesta && json_resp.Respuesta == "1")
    {
      alert("Clave cambiada con exito");
      f_js_ocultarTelon();
      nCargas = 1;
    }
    else
    {
      alert("La respuesta no contiene datos de la transaccion");
    }
  }
}

function f_js_CargarSaldo_Informe()
{
  var el_div = el_id("telon");
  el_div.style.display="block";
  el_div.className="telon telonAparecer";
  el_id("telonTitulo").innerHTML = "Informe de Cargas";
  el_div = el_id("telonContenido");
  el_div.innerHTML = "Cargando Informe...";
//  el_id('menuAdministracion').className = 'menuAdministracion menuAdministracionOculto';
  f_http_post(cliente_http
             ,"SportSiete.php"
             ,"method=InformeCargas"
		+ "&Sesion=" + v_cwf_id
             ,f_js_CargarSaldo_Respuesta,5000);
}

function f_js_CargarSaldo_Respuesta()
{
  var i;
  var monto_total;
  var json_resp = f_js_procesar_respuesta_generica(cliente_http,true);
  if (json_resp)
  {
//console.log(json_resp);
    if (json_resp.ErrorDescripcion)
    {
      alert(json_resp.ErrorDescripcion);
    }
    else if (json_resp.Cargas)
    {
      var el_div = el_id("telonContenido");
      monto_total = 0;
      var tmp_txt = 
		"<div style='max-height:65vh;padding:7px;'>"
		+	"<table border=0 width=100%>"
		+		"<tr>"
		+			"<td>"
		+				"A"
		+			"</td>"
		+			"<td>"
		+				"Instante"
		+			"</td>"
		+			"<td>"
		+				"Monto"
		+			"</td>"
		+		"</tr>";
      for (i = 0;i < json_resp.Cargas.length; i++)
      {
        tmp_txt +=		"<tr>"
		+			"<td>"
		+				json_resp.Cargas[i].usuario
		+			"</td>"
		+			"<td>"
		+				json_resp.Cargas[i].instante
		+			"</td>"
		+			"<td>"
		+				json_resp.Cargas[i].monto
		+			"</td>"
		+		"</tr>";
        monto_total += parseInt(json_resp.Cargas[i].monto);
      }
      tmp_txt +=		"<tr>"
		+			"<td colspan=2 align=right>"
		+				"Cantidad : " + i
		+			"</td>"
		+		"</tr>";
      tmp_txt +=		"<tr>"
		+			"<td colspan=2 align=right>"
		+				"Total : $" + monto_total
		+			"</td>"
		+		"</tr>";
      tmp_txt +=	"</table>"
		+ "</div>";
      el_div.innerHTML = tmp_txt;
    }
    else
    {
      alert("La respuesta no contiene datos de la transaccion");
    }
  }
}

var MostrarCuotas_div_previo = null;
function f_js_MostrarCuotas(pMatchId,pTipo)
{
  var el_div = el_id("cuotas_" + pMatchId + "_" + pTipo);
  if (MostrarCuotas_div_previo != null)
    MostrarCuotas_div_previo.style.display="none";
  el_div.style.display = "block";
  MostrarCuotas_div_previo = el_div;
}



function BuscarResultado(pMatrizDeJSON,pParejaResultado)
{
	var i,j;
	for (i = 0; i < pMatrizDeJSON.lenght;i++)
	{
		for (j = 0; j < pMatrizDeJSON[i].Resultados.lenght;j++)
		{
			if (pMatrizDeJSON[i].Resultados[j].resultado == pParejaResultado)
				return pMatrizDeJSON[i].Resultados[j];
		}
	}
}

/*
var ElQueBusco = BuscarResultado(json_resp,"(1,1)");

alert("Factor Pagado : " + ElQueBusco.factor_pagado);
*/

function f_js_PaisFiltrar(pPais)
{
	FiltroLiga = "";
	if (pPais == "Todos")
		FiltroPais = "";
	else
		FiltroPais = pPais;
//	f_js_Cargar();
	f_js_ListarEncuentros();
}

function f_js_DibujarTodosLosPaises(PaisBusqueda)
{
	var i;
	var el_div = el_id("ListaPaises");
	var tmp_txt = "";
	for(i = 0; i < TodosLosPaises.length;i++)
	{
		if (!PaisBusqueda || PaisBusqueda == "" || TodosLosPaises[i][2].toUpperCase().indexOf(PaisBusqueda.toUpperCase()) >= 0)
		{
			tmp_txt += "<div class='Pais' onClick='f_js_PaisFiltrar(\"" + TodosLosPaises[i][0] + "\")'>"
				+ ((TodosLosPaises[i][1] == "")
					?       ""
					:       "<div class='BanderaPais' style='background:url(imagenes/Banderas/" + TodosLosPaises[i][1] + ");background-size:100% 100%;'></div>"
				  )
				+ "<span>" + TodosLosPaises[i][2] + "</span>"
			+ "</div>";
		}
        }
	el_div.innerHTML = tmp_txt;
}

function f_js_PaisesActivarBuscar()
{
	var el_div = el_id("TituloPaises");
	el_div.innerHTML = "<input type=text name=TituloPaisesBuscar class=input style='width:145px;margin-top:4px;' onKeyUp='f_js_DibujarTodosLosPaises(this.value);'>";
	setTimeout(function(){ document.frm.TituloPaisesBuscar.focus(); },200);
}

function f_js_MarcadorDirecto(pValorLocal,pValorVisitante,pEncuentro,pResultado,pFactorPagado,pI,pEncuentroDescripcion,pEncuentroInstante){
	if (pValorLocal.value != "" && pValorVisitante.value == ""){
		pValorLocal.style.backgroundColor = "";
		pValorVisitante.style.backgroundColor = "red";
	} else if (pValorLocal.value == "" && pValorVisitante.value != ""){
		pValorLocal.style.backgroundColor = "red";
		pValorVisitante.style.backgroundColor = "";
	} else {
		pValorLocal.style.backgroundColor = "";
		pValorVisitante.style.backgroundColor = "";
	}
	alert("El partido es : " + pEncuentroDescripcion);
	alert("El partido del i es : " + UltimoListadoEncuentros[pI].local);
	var j;
	var NGoles = parseInt(pValorLocal.value) + parseInt(pValorVisitante.value);
	var tipoParaNGoles = "(=,=)";
	switch (NGoles){
/*
 1       | (3,31)  | 1 gol en el partido
 1       | (3,15)  | 2 goles en el partido
 1       | (3,16)  | 3 goles en el partido
 1       | (3,32)  | 4 goles en el partido
 1       | (3,33)  | 5 goles en el partido
 1       | (3,17)  | 6 o mas goles en el partido
 1       | (3,1)   | 1 o menos goles en el partido
*/
		case 0:tipoParaNGoles = "(3,1)"; // requiere restarle la probabilidad de 1 gol
			break;
		case 1:tipoParaNGoles = "(3,31)";
			break;
		case 2:tipoParaNGoles = "(3,15)";
			break;
		case 3:tipoParaNGoles = "(3,16)";
			break;
		case 4:tipoParaNGoles = "(3,32)";
			break;
		case 5:tipoParaNGoles = "(3,33)";
			break;
	}
	var tipoParaSuperioridad = "(3,15)";
	for (j = 0; j < UltimoListadoEncuentros[pI].Resultados.length && UltimoListadoEncuentros[pI].Resultados[j].resultado != tipoParaNGoles; j++)
		;
}

function f_js_ListarEncuentros()
{
/*
  <div class="AreaEncuentros">
   <div class="AreaEncuentrosNombres">
    <div class="TablaTitulos" id="TablaEncuentrosNombresTitulos">
    </div>
    <div class="TablaEncuentros" id=TablaEncuentrosNombres>
     <!-- En este lugar se agregan los encuentros por javascript -->
    </div>
   </div>
   <div class="AreaEncuentrosResultados">
    <div class="TablaTitulos" id="TablaEncuentrosResultadosTitulos">
    </div>
    <div class="TablaEncuentros" id=TablaEncuentrosResultados>
     <!-- En este lugar se agregan los encuentros por javascript -->
    </div>
   </div>
  </div>
*/

      var el_div_nom = el_id("TablaEncuentrosNombres");
      var el_div_res = el_id("TablaEncuentrosResultados");
      var el_div_tit_nom = el_id("TablaEncuentrosNombresTitulos");
      var el_div_tit_res = el_id("TablaEncuentrosResultadosTitulos");
      var el_div_tabs = el_id("AreaTabsEncuentros");
      el_div_nom.innerHTML="";
      el_div_res.innerHTML="";
      var i,j,k;
      var deporte_actual="";
      var liga_actual="";
      var tmp_txt_nom =  "";
      var tmp_txt_res =  "";
	el_div_tabs.innerHTML =   "<div class='Tab" + ((TabSeleccionado == 0)?" TabSeleccionado":"") + "' onClick='f_js_CambiarListaEncuentros(0)'>Resultado Directo</div>"
//				+ "<div class='Tab" + ((TabSeleccionado == 1)?" TabSeleccionado":"") + "' onClick='f_js_CambiarListaEncuentros(1)'>Mega Chansas</div>"
				+ "<div class='Tab" + ((TabSeleccionado == 2)?" TabSeleccionado":"") + "' onClick='alert(\"Esta funcion sera activada proximamente\")'>Mano a Mano</div>"
				+ "<div style='position:absolute;left:230px;'><input type=checkbox id=checkboxSoloHoy onChange='v_js_soloDeHoy = this.checked;f_js_ListarEncuentros();'" + (v_js_soloDeHoy?" checked":"") + "><label for=checkboxSoloHoy>Solo Encuentros de Hoy</label>"
				+ (EsAdministrador?"<input style='margin-left:15px;'type=checkbox id=checkboxMarcadorDirecto onChange='v_js_soloMarcadorDirecto = this.checked;f_js_ListarEncuentros();'" + (v_js_soloMarcadorDirecto?" checked":"") + "><label for=checkboxMarcadorDirecto>Marcador Directo</label>":"")
				+ "</div>";


	if (TabSeleccionado == 0)
	{
		el_div_tit_nom.innerHTML = 	  "<span style='left: 15px;'>Hora y fecha</span>"
						+ "<span style='left: 125px;'>Local/Visitante</span>";
		switch (v_js_deporte)
		{
			case 5:
				el_div_tit_res.innerHTML =	 "<span style='left: 20px;font-size:0.8em;'>Gana 1er J.</span>"
								+ "<span style='left: 195px;font-size:0.8em;'>Gana 2do J.</span>";
				break;
			case 2:
			case 8:
				el_div_tit_res.innerHTML =	 "<span style='left: 20px;font-size:0.8em;'>Gana Local</span>"
								+ "<span style='left: 195px;font-size:0.8em;'>Gana Visitante</span>";
				break;
			default:
				el_div_tit_res.innerHTML =	 "<span style='left: 20px;font-size:0.8em;'>Gana Local</span>"
								+ "<span style='left: 110px;font-size:0.8em;'>Empate</span>"
								+ "<span style='left: 195px;font-size:0.8em;'>Gana Visitante</span>";
				break;
		}
	}
	else if (TabSeleccionado == 1)
	{
		el_div_tit_nom.innerHTML = 	  "<span style='left: 15px;'>Hora y fecha</span>"
						+ "<span style='left: 125px;'>Local/Visitante</span>";

		el_div_tit_res.innerHTML = "";
		p = 1;
		for ( j = 0; j < UltimoListadoTipos.length;j++)
		{
			if (UltimoListadoTipos[j].tipo.substr(0,3) != "(1,")
			{
				el_div_tit_res.innerHTML += "<span style='left: " + p + "px;font-size:0.8em;width: 70px;'>" + UltimoListadoTipos[j].descripcion + "</span>";
				p += 80;
			}
		}
/*
		el_div_tit_res.innerHTML =	 "<span style='left: 10px;font-size:0.8em;'>Over</span>"
						+ "<span style='left: 75px;font-size:0.8em;'>Under</span>"
						+ "<span style='left: 130px;font-size:0.8em;width: 70px;'>Primer Tiempo Gana Local</span>"
						+ "<span style='left: 215px;font-size:0.8em;width: 70px;'>Primer Tiempo Empatan</span>"
						+ "<span style='left: 300px;font-size:0.8em;width: 70px;'>Primer Tiempo Gana Visitante</span>"
						+ "<span style='left: 395px;font-size:0.8em;width: 70px;'>Gana Local o Empata</span>"
						+ "<span style='left: 480px;font-size:0.8em;width: 70px;'>Gana Visitante o Empata</span>"
						+ "<span style='left: 565px;font-size:0.8em;width: 70px;'>Alguno Gana</span>"
						+ "<span style='left: 655px;font-size:0.8em;width: 70px;'>Primer Tiempo OVER</span>"
						+ "<span style='left: 745px;font-size:0.8em;width: 70px;'>Primer Tiempo UNDER</span>";
*/
	}

      var filtroEncuentro = "";
      if (document.frm && document.frm.InputBuscarEncuentro && document.frm.InputBuscarEncuentro)
        filtroEncuentro = document.frm.InputBuscarEncuentro.value.toLowerCase();
      k = 0;
      var diaDeHoy = new Date();
      for(i=0; i < UltimoListadoEncuentros.length;i++)
      {
        if (filtroEncuentro == "" || UltimoListadoEncuentros[i].local.toLowerCase().includes(filtroEncuentro) || UltimoListadoEncuentros[i].visitante.toLowerCase().includes(filtroEncuentro) || UltimoListadoEncuentros[i].liga.toLowerCase().includes(filtroEncuentro)){
        if (v_js_soloMarcadorDirecto){
          if (liga_actual != UltimoListadoEncuentros[i].liga_id)
          {
            liga_actual = UltimoListadoEncuentros[i].liga_id;
            k = 0;
            tmp_txt_nom += "<div class='Linea LineaLiga'>"
				+ "<span style='left: 15px;width:400px;'>" + UltimoListadoEncuentros[i].liga + "</span>"
			+ "</div>";
            tmp_txt_res += "<div class='Linea LineaLiga'>"
				+ "<span style='left: 15px;width:400px;'></span>"
			+ "</div>";
          }
          tmp_txt_nom += "<div class='Linea " + (((k % 2) == 0)?"LineaPar":"LineaImpar") + "' id='LineaChanzaDirNom_" + i + "'>"
			+ "<span style='left: 15px;font-size:0.7em;'>" + UltimoListadoEncuentros[i].instante.substr(0,16) + "</span>"
			+ "<div class='SeparadorVertical'></div>"
			+ "<span class='TextoEquipos' title='Codigo : " + UltimoListadoEncuentros[i].codigo + "'>" + UltimoListadoEncuentros[i].local + " vs "  + UltimoListadoEncuentros[i].visitante + "</span>";
          tmp_txt_res += "<div class='Linea " + (((k % 2) == 0)?"LineaPar":"LineaImpar") + "' id='LineaChanzaDirRes_" + i + "' style='text-align:left;font-size:0.8em;'>"
          tmp_txt_res +="Local : <input type=text style='width:2em;margin-right:1.5em;'"
				+ " id='MarcadorDirecto_Local_" + v_js_deporte + "_" + UltimoListadoEncuentros[i].codigo + "_" + i + "'"
				+ " onChange='f_js_MarcadorDirecto(this,el_id(\"MarcadorDirecto_Visitante_" + v_js_deporte + "_" + UltimoListadoEncuentros[i].codigo + "_" + i + "\")"
						+ ",\"" + UltimoListadoEncuentros[i].codigo + "\""
						+ ",\"(=,=)\""
						+ "," + 3
						+ "," + i
						+ ",\"" + UltimoListadoEncuentros[i].local + "-" + UltimoListadoEncuentros[i].visitante + "\""
						+ ",\"" + UltimoListadoEncuentros[i].instante + "\""
					+ ")'"
			+ "> Visitante : <input type=text style='width:2em;'"
				+ " onChange='f_js_MarcadorDirecto(el_id(\"MarcadorDirecto_Local_" + v_js_deporte + "_" + UltimoListadoEncuentros[i].codigo + "_" + i + "\"),this"
						+ ",\"" + UltimoListadoEncuentros[i].codigo + "\""
						+ ",\"(=,=)\""
						+ "," + 3
						+ "," + i
						+ ",\"" + UltimoListadoEncuentros[i].local + "-" + UltimoListadoEncuentros[i].visitante + "\""
						+ ",\"" + UltimoListadoEncuentros[i].instante + "\""
					+ ")'"
				+ " id='MarcadorDirecto_Visitante_" + v_js_deporte + "_" + UltimoListadoEncuentros[i].codigo + "_" + i + "'"
			+ ">";
	  tmp_txt_res += "</div>";
	  tmp_txt_nom += "</div>";
          k++;
        }
        else if (
		( ! v_js_soloDeHoy || UltimoListadoEncuentros[i].instante.substr(0,10) === ((diaDeHoy.getYear() + 1900) + "-" + String("0" + (diaDeHoy.getMonth()+1)).slice(-2) + "-" + String("0" + diaDeHoy.getDate()).slice(-2)))
             && (FiltroLiga == "" || FiltroLiga == UltimoListadoEncuentros[i].liga_id.substr(0,FiltroLiga.length).toUpperCase())
//             && (FiltroPais == "" || FiltroPais == UltimoListadoEncuentros[i].liga_id.substr(0,FiltroPais.length).toUpperCase())
             && (FiltroPais == "" || FiltroPais == UltimoListadoEncuentros[i].liga.substr(0,FiltroPais.length).toUpperCase())
           )
        {

          if (liga_actual != UltimoListadoEncuentros[i].liga_id)
          {
            liga_actual = UltimoListadoEncuentros[i].liga_id;
            k = 0;
            tmp_txt_nom += "<div class='Linea LineaLiga'>"
				+ "<span style='left: 15px;width:400px;'>" + UltimoListadoEncuentros[i].liga + "</span>"
			+ "</div>";
            tmp_txt_res += "<div class='Linea LineaLiga'>"
				+ "<span style='left: 15px;width:400px;'></span>"
			+ "</div>";
          }
          tmp_txt_nom += "<div class='Linea " + (((k % 2) == 0)?"LineaPar":"LineaImpar") + "' id='LineaChanzaDirNom_" + i + "'>"
			+ "<span style='left: 15px;font-size:0.7em;'>" + UltimoListadoEncuentros[i].instante.substr(0,16) + "</span>"
			+ "<div class='SeparadorVertical'></div>"
			+ "<span class='TextoEquipos' title='Codigo : " + UltimoListadoEncuentros[i].codigo + "'>" + UltimoListadoEncuentros[i].local + " vs "  + UltimoListadoEncuentros[i].visitante + "</span>";
          tmp_txt_res += "<div class='Linea " + (((k % 2) == 0)?"LineaPar":"LineaImpar") + "' id='LineaChanzaDirRes_" + i + "'>"

          if (TabSeleccionado == 0)
          {
            switch (UltimoListadoEncuentros[i].deporte_id)
            {
              case "1":
                  tmp_txt_res += f_js_CheckBoxApuesta(UltimoListadoEncuentros[i],"(1,1)");
                  tmp_txt_res += f_js_CheckBoxApuesta(UltimoListadoEncuentros[i],"(1,2)");
                  tmp_txt_res += f_js_CheckBoxApuesta(UltimoListadoEncuentros[i],"(1,3)");
                  break;
              case "2":
              case "5":
              case "8":
                  tmp_txt_res += f_js_CheckBoxApuesta(UltimoListadoEncuentros[i],"(17,1)");
                  tmp_txt_res += f_js_CheckBoxApuesta(UltimoListadoEncuentros[i],"(17,3)");
                  break;
              default:
                  console.log("ouch '" + UltimoListadoEncuentros[i].deporte_id + "'");
            }
//            tmp_txt_res += "<div class='ExpandirChanzas' onClick='f_js_ExpandirChanzas(" + i + ")'>+</div>";
            tmp_txt_res += "<div class='ExpandirChanzas' onClick='f_js_ExpandirChanzas_Ventana(" + i + ")'>+</div>";
          }
          else if (TabSeleccionado == 1)
          {
            var p = 15;

            for ( j = 3; j < UltimoListadoTipos.length;j++)
            {
              tmp_txt_res += f_js_CheckBoxApuesta(UltimoListadoEncuentros[i],UltimoListadoTipos[j].tipo,p);
              p += 80;
            }

/*
            tmp_txt_res += f_js_CheckBoxApuesta(UltimoListadoEncuentros[i],"(3,2)");
            tmp_txt_res += f_js_CheckBoxApuesta(UltimoListadoEncuentros[i],"(3,4)");
            tmp_txt_res += f_js_CheckBoxApuesta(UltimoListadoEncuentros[i],"(4,1)");
            tmp_txt_res += f_js_CheckBoxApuesta(UltimoListadoEncuentros[i],"(4,2)");
            tmp_txt_res += f_js_CheckBoxApuesta(UltimoListadoEncuentros[i],"(4,3)");
            tmp_txt_res += f_js_CheckBoxApuesta(UltimoListadoEncuentros[i],"(2,1)");
            tmp_txt_res += f_js_CheckBoxApuesta(UltimoListadoEncuentros[i],"(2,3)");
            tmp_txt_res += f_js_CheckBoxApuesta(UltimoListadoEncuentros[i],"(2,2)");
            tmp_txt_res += f_js_CheckBoxApuesta(UltimoListadoEncuentros[i],"(4,10)");
            tmp_txt_res += f_js_CheckBoxApuesta(UltimoListadoEncuentros[i],"(4,11)");
*/
          }
	  tmp_txt_nom += "</div>";
	  tmp_txt_res += "</div>";
          k++;
        }
        } // filtroEncuentro


      }

      el_div_nom.innerHTML += tmp_txt_nom;
      el_div_res.innerHTML += tmp_txt_res;
}

function f_js_CambiarDeporte(pDeporte)
{
	var classdiv1 = "";
	var imagendiv2 = "";
	var imagenTamdiv2 = "";
	var textoLigas = "<div class='Titulo'></div>";
	el_id("TablaEncuentrosNombres").innerHTML="Cargando...";
	el_id("TablaEncuentrosResultados").innerHTML="";;

	var el_div=el_id("DeporteEncabezadoImagen");
	var el_div2=el_id("divEncabezadoBanner");
	var el_div3=el_id("SelectorLigas");
	switch(pDeporte)
	{
		case 1:
			TodasLasLigas = TodasLasLigas_futbol;
			classdiv1 = "DeporteFutbol";
			imagendiv2 = "url(imagenes/BanerDeporte.png)";
			imagenTamdiv2 = "100% 100%";
			break;
		case 2:
			TodasLasLigas = TodasLasLigas_basketball;
			classdiv1 = "DeporteBasketball";
			imagendiv2 = "url(imagenes/BannerBasketbol.jpg)";
			imagenTamdiv2 = "100% 260%";
			break;
		case 5:
			TodasLasLigas = TodasLasLigas_tennis;
			classdiv1 = "DeporteTenis";
			imagendiv2 = "url(imagenes/BannerTenis.jpg)";
			imagenTamdiv2 = "100% 240%";
			break;
		case 8:
			TodasLasLigas = TodasLasLigas_beisbol;
			classdiv1 = "DeporteBeisbol";
//			imagendiv2 = "url(imagenes/BannerBeisbol.jpg)";
//			imagenTamdiv2 = "80% 180%";
			imagendiv2 = "url(imagenes/BannerUSA.jpg)";
			imagenTamdiv2 = "33% 100%";
			break;
/*
		case XX:
			TodasLasLigas = TodasLasLigas_usa;
			classdiv1 = "DeporteUSA";
			imagendiv2 = "url(imagenes/BannerUSA.jpg)";
			imagenTamdiv2 = "80% 180%";
			break;
*/
		default:
			TodasLasLigas = TodasLasLigas_futbol;
			classdiv1 = "DeporteFutbol";
			break;
	}
	if (el_div != null)
		el_div.className=classdiv1;
	if (el_div2 != null){
		el_div2.style.backgroundImage=imagendiv2;
		el_div2.style.backgroundSize=imagenTamdiv2;
	}
	if (el_div3 != null){
		el_div3.innerHTML=textoLigas;
	}
	v_js_deporte = pDeporte;
	f_js_Cargar();
	f_js_DibujarSelectorLigas();
}

function f_js_ListarBasquet()
{
	v_js_deporte = 2;
	f_js_Cargar();
}

function f_js_ListarTenis()
{
/*
  <div class="AreaEncuentros">
   <div class="AreaEncuentrosNombres">
    <div class="TablaTitulos" id="TablaEncuentrosNombresTitulos">
    </div>
    <div class="TablaEncuentros" id=TablaEncuentrosNombres>
     <!-- En este lugar se agregan los encuentros por javascript -->
    </div>
   </div>
   <div class="AreaEncuentrosResultados">
    <div class="TablaTitulos" id="TablaEncuentrosResultadosTitulos">
    </div>
    <div class="TablaEncuentros" id=TablaEncuentrosResultados>
     <!-- En este lugar se agregan los encuentros por javascript -->
    </div>
   </div>
  </div>
*/

      var el_div_nom = el_id("TablaEncuentrosNombres");
      var el_div_res = el_id("TablaEncuentrosResultados");
      var el_div_tit_nom = el_id("TablaEncuentrosNombresTitulos");
      var el_div_tit_res = el_id("TablaEncuentrosResultadosTitulos");
      var el_div_tabs = el_id("AreaTabsEncuentros");
      el_div_nom.innerHTML="";
      el_div_res.innerHTML="";
      var i,j,k;
      var deporte_actual="";
      var liga_actual="";
      var tmp_txt_nom =  "";
      var tmp_txt_res =  "";
  el_div_tabs.innerHTML =   "<div class='Tab" + ((TabSeleccionado == 0)?" TabSeleccionado":"") + "' onClick='f_js_CambiarListaEncuentros(0)'>Resultado Directo</div>";
//        + "<div class='Tab" + ((TabSeleccionado == 1)?" TabSeleccionado":"") + "' onClick='f_js_CambiarListaEncuentros(1)'>Mega Chansas</div>"
        //+ "<div class='Tab" + ((TabSeleccionado == 2)?" TabSeleccionado":"") + "' onClick='alert(\"Esta funcion sera activada proximamente\")'>Mano a Mano</div>";

  if (TabSeleccionado == 0)
  {
    el_div_tit_nom.innerHTML =    "<span style='left: 15px;'>Hora y fecha</span>"
            + "<span style='left: 125px;'>Local/Visitante</span>";
    el_div_tit_res.innerHTML =   "<span style='left: 20px;font-size:0.8em;'>Gana Local</span>"
           // + "<span style='left: 110px;font-size:0.8em;'>Empate</span>"
            + "<span style='left: 195px;font-size:0.8em;'>Gana Visitante</span>";
  }
  else if (TabSeleccionado == 1)
  {
    el_div_tit_nom.innerHTML =    "<span style='left: 15px;'>Hora y fecha</span>"
            + "<span style='left: 125px;'>Local/Visitante</span>";

    el_div_tit_res.innerHTML = "";
    p = 1;
    for ( j = 0; j < UltimoListadoTipos.length;j++)
    {
      if (UltimoListadoTipos[j].tipo.substr(0,3) != "(1,")
      {
        el_div_tit_res.innerHTML += "<span style='left: " + p + "px;font-size:0.8em;width: 70px;'>" + UltimoListadoTipos[j].descripcion + "</span>";
        p += 80;
      }
    }
/*
    el_div_tit_res.innerHTML =   "<span style='left: 10px;font-size:0.8em;'>Over</span>"
            + "<span style='left: 75px;font-size:0.8em;'>Under</span>"
            + "<span style='left: 130px;font-size:0.8em;width: 70px;'>Primer Tiempo Gana Local</span>"
            + "<span style='left: 215px;font-size:0.8em;width: 70px;'>Primer Tiempo Empatan</span>"
            + "<span style='left: 300px;font-size:0.8em;width: 70px;'>Primer Tiempo Gana Visitante</span>"
            + "<span style='left: 395px;font-size:0.8em;width: 70px;'>Gana Local o Empata</span>"
            + "<span style='left: 480px;font-size:0.8em;width: 70px;'>Gana Visitante o Empata</span>"
            + "<span style='left: 565px;font-size:0.8em;width: 70px;'>Alguno Gana</span>"
            + "<span style='left: 655px;font-size:0.8em;width: 70px;'>Primer Tiempo OVER</span>"
            + "<span style='left: 745px;font-size:0.8em;width: 70px;'>Primer Tiempo UNDER</span>";
*/
  }

      k = 0;
      for(i=0; i < UltimoListadoEncuentros.length;i++)
      {
        if (
                (FiltroLiga == "" || FiltroLiga == UltimoListadoEncuentros[i].liga_id.substr(0,FiltroLiga.length).toUpperCase())
//             && (FiltroPais == "" || FiltroPais == UltimoListadoEncuentros[i].liga_id.substr(0,FiltroPais.length).toUpperCase())
             && (FiltroPais == "" || FiltroPais == UltimoListadoEncuentros[i].liga.substr(0,FiltroPais.length).toUpperCase())
           )
        {

          if (liga_actual != UltimoListadoEncuentros[i].liga_id)
          {
            liga_actual = UltimoListadoEncuentros[i].liga_id;
            k = 0;
            tmp_txt_nom += "<div class='Linea LineaLiga'>"
        + "<span style='left: 15px;width:400px;'>" + UltimoListadoEncuentros[i].liga + "</span>"
      + "</div>";
            tmp_txt_res += "<div class='Linea LineaLiga'>"
        + "<span style='left: 15px;width:400px;'></span>"
      + "</div>";
          }
          tmp_txt_nom += "<div class='Linea " + (((k % 2) == 0)?"LineaPar":"LineaImpar") + "' id='LineaChanzaDirNom_" + i + "'>"
      + "<span style='left: 15px;font-size:0.7em;'>" + UltimoListadoEncuentros[i].instante.substr(0,16) + "</span>"
      + "<div class='SeparadorVertical'></div>"
      + "<span class='TextoEquipos' title='Codigo : " + UltimoListadoEncuentros[i].codigo + "'>" + UltimoListadoEncuentros[i].local + " vs "  + UltimoListadoEncuentros[i].visitante + "</span>";
          tmp_txt_res += "<div class='Linea " + (((k % 2) == 0)?"LineaPar":"LineaImpar") + "' id='LineaChanzaDirRes_" + i + "'>"

          if (TabSeleccionado == 0)
          {
            tmp_txt_res += f_js_CheckBoxApuesta(UltimoListadoEncuentros[i],"(1,1)");
            //tmp_txt_res += f_js_CheckBoxApuesta(UltimoListadoEncuentros[i],"(1,2)");
            tmp_txt_res += f_js_CheckBoxApuesta(UltimoListadoEncuentros[i],"(1,3)");
            //tmp_txt_res += "<div class='ExpandirChanzas' onClick='f_js_ExpandirChanzas(" + i + ")'>+</div>";
          }
          else if (TabSeleccionado == 1)
          {
            var p = 15;

            for ( j = 3; j < UltimoListadoTipos.length;j++)
            {
              tmp_txt_res += f_js_CheckBoxApuesta(UltimoListadoEncuentros[i],UltimoListadoTipos[j].tipo,p);
              p += 80;
            }

/*
            tmp_txt_res += f_js_CheckBoxApuesta(UltimoListadoEncuentros[i],"(3,2)");
            tmp_txt_res += f_js_CheckBoxApuesta(UltimoListadoEncuentros[i],"(3,4)");
            tmp_txt_res += f_js_CheckBoxApuesta(UltimoListadoEncuentros[i],"(4,1)");
            tmp_txt_res += f_js_CheckBoxApuesta(UltimoListadoEncuentros[i],"(4,2)");
            tmp_txt_res += f_js_CheckBoxApuesta(UltimoListadoEncuentros[i],"(4,3)");
            tmp_txt_res += f_js_CheckBoxApuesta(UltimoListadoEncuentros[i],"(2,1)");
            tmp_txt_res += f_js_CheckBoxApuesta(UltimoListadoEncuentros[i],"(2,3)");
            tmp_txt_res += f_js_CheckBoxApuesta(UltimoListadoEncuentros[i],"(2,2)");
            tmp_txt_res += f_js_CheckBoxApuesta(UltimoListadoEncuentros[i],"(4,10)");
            tmp_txt_res += f_js_CheckBoxApuesta(UltimoListadoEncuentros[i],"(4,11)");
*/
          }
    tmp_txt_nom += "</div>";
    tmp_txt_res += "</div>";
          k++;
        }


      }

      el_div_nom.innerHTML += tmp_txt_nom;
      el_div_res.innerHTML += tmp_txt_res;
}

function f_js_ListarBeisbol()
{
/*
  <div class="AreaEncuentros">
   <div class="AreaEncuentrosNombres">
    <div class="TablaTitulos" id="TablaEncuentrosNombresTitulos">
    </div>
    <div class="TablaEncuentros" id=TablaEncuentrosNombres>
     <!-- En este lugar se agregan los encuentros por javascript -->
    </div>
   </div>
   <div class="AreaEncuentrosResultados">
    <div class="TablaTitulos" id="TablaEncuentrosResultadosTitulos">
    </div>
    <div class="TablaEncuentros" id=TablaEncuentrosResultados>
     <!-- En este lugar se agregan los encuentros por javascript -->
    </div>
   </div>
  </div>
*/

      var el_div_nom = el_id("TablaEncuentrosNombres");
      var el_div_res = el_id("TablaEncuentrosResultados");
      var el_div_tit_nom = el_id("TablaEncuentrosNombresTitulos");
      var el_div_tit_res = el_id("TablaEncuentrosResultadosTitulos");
      var el_div_tabs = el_id("AreaTabsEncuentros");
      el_div_nom.innerHTML="";
      el_div_res.innerHTML="";
      var i,j,k;
      var deporte_actual="";
      var liga_actual="";
      var tmp_txt_nom =  "";
      var tmp_txt_res =  "";
  el_div_tabs.innerHTML =   "<div class='Tab" + ((TabSeleccionado == 0)?" TabSeleccionado":"") + "' onClick='f_js_CambiarListaEncuentros(0)'>Resultado Directo</div>";
//        + "<div class='Tab" + ((TabSeleccionado == 1)?" TabSeleccionado":"") + "' onClick='f_js_CambiarListaEncuentros(1)'>Mega Chansas</div>"
        //+ "<div class='Tab" + ((TabSeleccionado == 2)?" TabSeleccionado":"") + "' onClick='alert(\"Esta funcion sera activada proximamente\")'>Mano a Mano</div>";

  if (TabSeleccionado == 0)
  {
    el_div_tit_nom.innerHTML =    "<span style='left: 15px;'>Hora y fecha</span>"
            + "<span style='left: 125px;'>Local/Visitante</span>";
    el_div_tit_res.innerHTML =   "<span style='left: 20px;font-size:0.8em;'>Gana Local</span>"
           // + "<span style='left: 110px;font-size:0.8em;'>Empate</span>"
            + "<span style='left: 195px;font-size:0.8em;'>Gana Visitante</span>";
  }
  else if (TabSeleccionado == 1)
  {
    el_div_tit_nom.innerHTML =    "<span style='left: 15px;'>Hora y fecha</span>"
            + "<span style='left: 125px;'>Local/Visitante</span>";

    el_div_tit_res.innerHTML = "";
    p = 1;
    for ( j = 0; j < UltimoListadoTipos.length;j++)
    {
      if (UltimoListadoTipos[j].tipo.substr(0,3) != "(1,")
      {
        el_div_tit_res.innerHTML += "<span style='left: " + p + "px;font-size:0.8em;width: 70px;'>" + UltimoListadoTipos[j].descripcion + "</span>";
        p += 80;
      }
    }
/*
    el_div_tit_res.innerHTML =   "<span style='left: 10px;font-size:0.8em;'>Over</span>"
            + "<span style='left: 75px;font-size:0.8em;'>Under</span>"
            + "<span style='left: 130px;font-size:0.8em;width: 70px;'>Primer Tiempo Gana Local</span>"
            + "<span style='left: 215px;font-size:0.8em;width: 70px;'>Primer Tiempo Empatan</span>"
            + "<span style='left: 300px;font-size:0.8em;width: 70px;'>Primer Tiempo Gana Visitante</span>"
            + "<span style='left: 395px;font-size:0.8em;width: 70px;'>Gana Local o Empata</span>"
            + "<span style='left: 480px;font-size:0.8em;width: 70px;'>Gana Visitante o Empata</span>"
            + "<span style='left: 565px;font-size:0.8em;width: 70px;'>Alguno Gana</span>"
            + "<span style='left: 655px;font-size:0.8em;width: 70px;'>Primer Tiempo OVER</span>"
            + "<span style='left: 745px;font-size:0.8em;width: 70px;'>Primer Tiempo UNDER</span>";
*/
  }

      k = 0;
      for(i=0; i < UltimoListadoEncuentros.length;i++)
      {
        if (
                (FiltroLiga == "" || FiltroLiga == UltimoListadoEncuentros[i].liga_id.substr(0,FiltroLiga.length).toUpperCase())
//             && (FiltroPais == "" || FiltroPais == UltimoListadoEncuentros[i].liga_id.substr(0,FiltroPais.length).toUpperCase())
             && (FiltroPais == "" || FiltroPais == UltimoListadoEncuentros[i].liga.substr(0,FiltroPais.length).toUpperCase())
           )
        {

          if (liga_actual != UltimoListadoEncuentros[i].liga_id)
          {
            liga_actual = UltimoListadoEncuentros[i].liga_id;
            k = 0;
            tmp_txt_nom += "<div class='Linea LineaLiga'>"
        + "<span style='left: 15px;width:400px;'>" + UltimoListadoEncuentros[i].liga + "</span>"
      + "</div>";
            tmp_txt_res += "<div class='Linea LineaLiga'>"
        + "<span style='left: 15px;width:400px;'></span>"
      + "</div>";
          }
          tmp_txt_nom += "<div class='Linea " + (((k % 2) == 0)?"LineaPar":"LineaImpar") + "' id='LineaChanzaDirNom_" + i + "'>"
      + "<span style='left: 15px;font-size:0.7em;'>" + UltimoListadoEncuentros[i].instante.substr(0,16) + "</span>"
      + "<div class='SeparadorVertical'></div>"
      + "<span class='TextoEquipos' title='Codigo : " + UltimoListadoEncuentros[i].codigo + "'>" + UltimoListadoEncuentros[i].local + " vs "  + UltimoListadoEncuentros[i].visitante + "</span>";
          tmp_txt_res += "<div class='Linea " + (((k % 2) == 0)?"LineaPar":"LineaImpar") + "' id='LineaChanzaDirRes_" + i + "'>"

          if (TabSeleccionado == 0)
          {
            tmp_txt_res += f_js_CheckBoxApuesta(UltimoListadoEncuentros[i],"(1,1)");
            //tmp_txt_res += f_js_CheckBoxApuesta(UltimoListadoEncuentros[i],"(1,2)");
            tmp_txt_res += f_js_CheckBoxApuesta(UltimoListadoEncuentros[i],"(1,3)");
            //tmp_txt_res += "<div class='ExpandirChanzas' onClick='f_js_ExpandirChanzas(" + i + ")'>+</div>";
          }
          else if (TabSeleccionado == 1)
          {
            var p = 15;

            for ( j = 3; j < UltimoListadoTipos.length;j++)
            {
              tmp_txt_res += f_js_CheckBoxApuesta(UltimoListadoEncuentros[i],UltimoListadoTipos[j].tipo,p);
              p += 80;
            }

/*
            tmp_txt_res += f_js_CheckBoxApuesta(UltimoListadoEncuentros[i],"(3,2)");
            tmp_txt_res += f_js_CheckBoxApuesta(UltimoListadoEncuentros[i],"(3,4)");
            tmp_txt_res += f_js_CheckBoxApuesta(UltimoListadoEncuentros[i],"(4,1)");
            tmp_txt_res += f_js_CheckBoxApuesta(UltimoListadoEncuentros[i],"(4,2)");
            tmp_txt_res += f_js_CheckBoxApuesta(UltimoListadoEncuentros[i],"(4,3)");
            tmp_txt_res += f_js_CheckBoxApuesta(UltimoListadoEncuentros[i],"(2,1)");
            tmp_txt_res += f_js_CheckBoxApuesta(UltimoListadoEncuentros[i],"(2,3)");
            tmp_txt_res += f_js_CheckBoxApuesta(UltimoListadoEncuentros[i],"(2,2)");
            tmp_txt_res += f_js_CheckBoxApuesta(UltimoListadoEncuentros[i],"(4,10)");
            tmp_txt_res += f_js_CheckBoxApuesta(UltimoListadoEncuentros[i],"(4,11)");
*/
          }
    tmp_txt_nom += "</div>";
    tmp_txt_res += "</div>";
          k++;
        }


      }

      el_div_nom.innerHTML += tmp_txt_nom;
      el_div_res.innerHTML += tmp_txt_res;
}

function f_js_ListarAmerican()
{
/*
  <div class="AreaEncuentros">
   <div class="AreaEncuentrosNombres">
    <div class="TablaTitulos" id="TablaEncuentrosNombresTitulos">
    </div>
    <div class="TablaEncuentros" id=TablaEncuentrosNombres>
     <!-- En este lugar se agregan los encuentros por javascript -->
    </div>
   </div>
   <div class="AreaEncuentrosResultados">
    <div class="TablaTitulos" id="TablaEncuentrosResultadosTitulos">
    </div>
    <div class="TablaEncuentros" id=TablaEncuentrosResultados>
     <!-- En este lugar se agregan los encuentros por javascript -->
    </div>
   </div>
  </div>
*/

      var el_div_nom = el_id("TablaEncuentrosNombres");
      var el_div_res = el_id("TablaEncuentrosResultados");
      var el_div_tit_nom = el_id("TablaEncuentrosNombresTitulos");
      var el_div_tit_res = el_id("TablaEncuentrosResultadosTitulos");
      var el_div_tabs = el_id("AreaTabsEncuentros");
      el_div_nom.innerHTML="";
      el_div_res.innerHTML="";
      var i,j,k;
      var deporte_actual="";
      var liga_actual="";
      var tmp_txt_nom =  "";
      var tmp_txt_res =  "";
  el_div_tabs.innerHTML =   "<div class='Tab" + ((TabSeleccionado == 0)?" TabSeleccionado":"") + "' onClick='f_js_CambiarListaEncuentros(0)'>Resultado Directo</div>";
//        + "<div class='Tab" + ((TabSeleccionado == 1)?" TabSeleccionado":"") + "' onClick='f_js_CambiarListaEncuentros(1)'>Mega Chansas</div>"
        //+ "<div class='Tab" + ((TabSeleccionado == 2)?" TabSeleccionado":"") + "' onClick='alert(\"Esta funcion sera activada proximamente\")'>Mano a Mano</div>";

  if (TabSeleccionado == 0)
  {
    el_div_tit_nom.innerHTML =    "<span style='left: 15px;'>Hora y fecha</span>"
            + "<span style='left: 125px;'>Local/Visitante</span>";
    el_div_tit_res.innerHTML =   "<span style='left: 20px;font-size:0.8em;'>Gana Local</span>"
           // + "<span style='left: 110px;font-size:0.8em;'>Empate</span>"
            + "<span style='left: 195px;font-size:0.8em;'>Gana Visitante</span>";
  }
  else if (TabSeleccionado == 1)
  {
    el_div_tit_nom.innerHTML =    "<span style='left: 15px;'>Hora y fecha</span>"
            + "<span style='left: 125px;'>Local/Visitante</span>";

    el_div_tit_res.innerHTML = "";
    p = 1;
    for ( j = 0; j < UltimoListadoTipos.length;j++)
    {
      if (UltimoListadoTipos[j].tipo.substr(0,3) != "(1,")
      {
        el_div_tit_res.innerHTML += "<span style='left: " + p + "px;font-size:0.8em;width: 70px;'>" + UltimoListadoTipos[j].descripcion + "</span>";
        p += 80;
      }
    }
/*
    el_div_tit_res.innerHTML =   "<span style='left: 10px;font-size:0.8em;'>Over</span>"
            + "<span style='left: 75px;font-size:0.8em;'>Under</span>"
            + "<span style='left: 130px;font-size:0.8em;width: 70px;'>Primer Tiempo Gana Local</span>"
            + "<span style='left: 215px;font-size:0.8em;width: 70px;'>Primer Tiempo Empatan</span>"
            + "<span style='left: 300px;font-size:0.8em;width: 70px;'>Primer Tiempo Gana Visitante</span>"
            + "<span style='left: 395px;font-size:0.8em;width: 70px;'>Gana Local o Empata</span>"
            + "<span style='left: 480px;font-size:0.8em;width: 70px;'>Gana Visitante o Empata</span>"
            + "<span style='left: 565px;font-size:0.8em;width: 70px;'>Alguno Gana</span>"
            + "<span style='left: 655px;font-size:0.8em;width: 70px;'>Primer Tiempo OVER</span>"
            + "<span style='left: 745px;font-size:0.8em;width: 70px;'>Primer Tiempo UNDER</span>";
*/
  }

      k = 0;
      for(i=0; i < UltimoListadoEncuentros.length;i++)
      {
        if (
                (FiltroLiga == "" || FiltroLiga == UltimoListadoEncuentros[i].liga_id.substr(0,FiltroLiga.length).toUpperCase())
//             && (FiltroPais == "" || FiltroPais == UltimoListadoEncuentros[i].liga_id.substr(0,FiltroPais.length).toUpperCase())
             && (FiltroPais == "" || FiltroPais == UltimoListadoEncuentros[i].liga.substr(0,FiltroPais.length).toUpperCase())
           )
        {

          if (liga_actual != UltimoListadoEncuentros[i].liga_id)
          {
            liga_actual = UltimoListadoEncuentros[i].liga_id;
            k = 0;
            tmp_txt_nom += "<div class='Linea LineaLiga'>"
        + "<span style='left: 15px;width:400px;'>" + UltimoListadoEncuentros[i].liga + "</span>"
      + "</div>";
            tmp_txt_res += "<div class='Linea LineaLiga'>"
        + "<span style='left: 15px;width:400px;'></span>"
      + "</div>";
          }
          tmp_txt_nom += "<div class='Linea " + (((k % 2) == 0)?"LineaPar":"LineaImpar") + "' id='LineaChanzaDirNom_" + i + "'>"
      + "<span style='left: 15px;font-size:0.7em;'>" + UltimoListadoEncuentros[i].instante.substr(0,16) + "</span>"
      + "<div class='SeparadorVertical'></div>"
      + "<span class='TextoEquipos' title='Codigo : " + UltimoListadoEncuentros[i].codigo + "'>" + UltimoListadoEncuentros[i].local + " vs "  + UltimoListadoEncuentros[i].visitante + "</span>";
          tmp_txt_res += "<div class='Linea " + (((k % 2) == 0)?"LineaPar":"LineaImpar") + "' id='LineaChanzaDirRes_" + i + "'>"

          if (TabSeleccionado == 0)
          {
            tmp_txt_res += f_js_CheckBoxApuesta(UltimoListadoEncuentros[i],"(1,1)");
            //tmp_txt_res += f_js_CheckBoxApuesta(UltimoListadoEncuentros[i],"(1,2)");
            tmp_txt_res += f_js_CheckBoxApuesta(UltimoListadoEncuentros[i],"(1,3)");
            //tmp_txt_res += "<div class='ExpandirChanzas' onClick='f_js_ExpandirChanzas(" + i + ")'>+</div>";
          }
          else if (TabSeleccionado == 1)
          {
            var p = 15;

            for ( j = 3; j < UltimoListadoTipos.length;j++)
            {
              tmp_txt_res += f_js_CheckBoxApuesta(UltimoListadoEncuentros[i],UltimoListadoTipos[j].tipo,p);
              p += 80;
            }

/*
            tmp_txt_res += f_js_CheckBoxApuesta(UltimoListadoEncuentros[i],"(3,2)");
            tmp_txt_res += f_js_CheckBoxApuesta(UltimoListadoEncuentros[i],"(3,4)");
            tmp_txt_res += f_js_CheckBoxApuesta(UltimoListadoEncuentros[i],"(4,1)");
            tmp_txt_res += f_js_CheckBoxApuesta(UltimoListadoEncuentros[i],"(4,2)");
            tmp_txt_res += f_js_CheckBoxApuesta(UltimoListadoEncuentros[i],"(4,3)");
            tmp_txt_res += f_js_CheckBoxApuesta(UltimoListadoEncuentros[i],"(2,1)");
            tmp_txt_res += f_js_CheckBoxApuesta(UltimoListadoEncuentros[i],"(2,3)");
            tmp_txt_res += f_js_CheckBoxApuesta(UltimoListadoEncuentros[i],"(2,2)");
            tmp_txt_res += f_js_CheckBoxApuesta(UltimoListadoEncuentros[i],"(4,10)");
            tmp_txt_res += f_js_CheckBoxApuesta(UltimoListadoEncuentros[i],"(4,11)");
*/
          }
    tmp_txt_nom += "</div>";
    tmp_txt_res += "</div>";
          k++;
        }


      }

      el_div_nom.innerHTML += tmp_txt_nom;
      el_div_res.innerHTML += tmp_txt_res;
}

function f_js_CambiarListaEncuentros(pLista)
{
	TabSeleccionado = pLista;
	f_js_ListarEncuentros();
}

function f_js_CargarOpcionesAdmin()
{
	var el_div = el_id("ZonaPublicidad");
	el_div.innerHTML = OpcionesAdmin;
}

function f_js_CoberturaFecha(pCodUsuario,pFecha)
{
	var monto = prompt("Monto");
	if (!monto)
	{
		alert("Cancelado");
		return;
	}
	document.frm_cobertura.CodUsuario.value = pCodUsuario;
	document.frm_cobertura.Fecha.value = pFecha;
	document.frm_cobertura.Monto.value = monto;
	document.frm_cobertura.submit();
}

function f_js_CargarPublicidad()
{
	var el_div = el_id("ZonaPublicidad");
	el_div.innerHTML = "<div class='Publicidad1'></div>"
				+ "<div class='Publicidad2'></div>";
}

function f_js_DibujarSelectorLigas(pPagina)
{
	var el_div = el_id("SelectorLigas");
	var i;
	var tmp_txt;
	var pagina = 0;
	if (pPagina)
		pagina=pPagina;
	tmp_txt =	  "<div class='Titulo'></div>";
//console.log("dibujando");
	for (i = pagina * 9; i < TodasLasLigas.length && i < (pagina + 1) * 9; i++)
	{
		tmp_txt += "<div class='BanderaCirculo' onClick='f_js_LigaFiltrar(\"" + TodasLasLigas[i][0] + "\")' style='"
					+ "background:url(imagenes/BanderasLigas2/" + TodasLasLigas[i][1] + ");"
					+ "background-size: auto 80%;"
					+ "background-position: center;"
					+ "background-repeat: no-repeat;"
					+ "background-color: white;"
				+ "' title='" + TodasLasLigas[i][2] + "'>"
			+ "</div>";
	}
	tmp_txt += "</div>";
	tmp_txt += "<div class='Paginador'>"
			+ "<table width=100% height=100% border=0>"
				+ "<tr>"
					+ "<td align=center>";
	for (i = 0; i < (TodasLasLigas.length / 9); i++)
	{
		if (i == pagina)
			tmp_txt += "<div class='CheckBoxEncendido' id='PagLigas_" + i + "' onClick='f_js_DibujarSelectorLigas(" + i + ")'></div>";
		else
			tmp_txt += "<div class='CheckBoxApagado' id='PagLigas_" + i + "' onClick='f_js_DibujarSelectorLigas(" + i + ")'></div>";
	}

	tmp_txt +=			"</td>"
				+ "</tr>"
			+ "</table>"
		+ "</div>";
	el_div.innerHTML = tmp_txt;
/*
 <div class="Titulo">
 </div>
 <div class="BanderaCirculo">
  <div class="EncuentroBandera BanderaRealMadrid"></div>
 </div>
 <div class="BanderaCirculo">
  <div class="EncuentroBandera BanderaBarcelona"></div>
 </div>
 <div class="BanderaCirculo">
 </div>
*/

}

function f_js_LigaFiltrar(pLiga)
{
	FiltroPais = "";
	if (pLiga == "Todos")
		FiltroLiga = "";
	else
		FiltroLiga = pLiga;
//	f_js_Cargar();
	f_js_ListarEncuentros();
}

function f_js_ScrollResultados()
{
	var el_div_nom = el_id("TablaEncuentrosNombres");
	var el_div_res = el_id("AreaEncuentrosResultadosScroll");
	var el_div_res_tit = el_id("TablaEncuentrosResultadosTitulos");
	var el_div_nom_tit = el_id("TablaEncuentrosNombresTitulos");
//	alert(el_div_res.scrollTop);
	el_div_nom.style.marginTop= "-" + el_div_res.scrollTop + "px";
	el_div_res_tit.style.top= el_div_res.scrollTop + "px";
//	el_div_nom_tit.style.top= el_div_res.scrollTop + "px";
	el_div_nom_tit.style.top= "0px";
//	alert("scroll");
}

function f_js_QuitarApuesta(pI)
{
	apostadoGlobal.splice(pI,1);
	f_js_ListarEncuentros();
	for (j = 0; j < apostadoGlobal.length; j++)
		apostadoGlobal[j][3] = false;
	setTimeout("f_js_dibujarApostado()",300);
}

function f_js_ExpandirChanzas_Ventana(pPos)
{
	var j;
	var el_div_Nom;
	var el_div_Res;
	var encuentroEnApostado = false;
  var el_div = el_id("telon");
  el_div.style.display="block";
  el_div.className="telon telonAparecer";
  el_id("telonTitulo").innerHTML = "MegaChanzas";
  el_div = el_id("telonContenido");
  el_div.style.height="70vh";
  el_div.style.overflowY="auto";
var tmp_txt;

console.log(UltimoListadoEncuentros[pPos]);
console.log(apostadoGlobal);
		encuentroEnApostado=false;
		for ( j = 0; j < apostadoGlobal.length; j++)
		{
			if (apostadoGlobal[j][0] == UltimoListadoEncuentros[pPos].codigo)
			{
//				encuentroEnApostado = apostadoGlobal[j];
				encuentroEnApostado = apostadoGlobal[j][1];
			}
		}
		for ( j = 3; j < UltimoListadoTipos.length; j++)
		{
/*
			if (encuentroEnApostado)
				el_div_Res.innerHTML += f_js_CheckBoxApuesta(UltimoListadoEncuentros[pPos],UltimoListadoTipos[j].tipo,UltimoListadoTipos[j].tipo == encuentroEnApostado[1]);
			else
*/
				tmp_txt += f_js_CheckBoxApuesta_enVentana(UltimoListadoEncuentros[pPos],UltimoListadoTipos[j].tipo,pPos,encuentroEnApostado);
//				el_div_Res.innerHTML += f_js_CheckBoxApuesta(UltimoListadoEncuentros[pPos],UltimoListadoTipos[j].tipo);
		}
  el_div.innerHTML = tmp_txt;
}

function f_js_ExpandirChanzas(pPos)
{
	var j;
	var el_div_Nom;
	var el_div_Res;
	if (EsAdministrador)
	{
		f_js_ExpandirChanzas_Ventana(pPos)
		return;
	}
	var encuentroEnApostado = false;
	if (EncuentroExpandido
		&& EncuentroExpandido != pPos
		&& (el_div_Nom = el_id("LineaChanzaDirNom_" + EncuentroExpandido))
		&& (el_div_Res = el_id("LineaChanzaDirRes_" + EncuentroExpandido))
		)
	{
		// Contraer EncuentroExpandido
		EncuentroExpandido=pPos;
	}
	if ( (el_div_Nom = el_id("LineaChanzaDirNom_" + pPos))
		&& (el_div_Res = el_id("LineaChanzaDirRes_" + pPos))
		)
	{
		el_div_Nom.style.height = "180px";
		el_div_Res.style.height = "180px";
		el_div_Res.innerHTML = "";
//console.log(UltimoListadoEncuentros[pPos]);
//console.log(apostadoGlobal);
		for ( j = 0; j < apostadoGlobal.length; j++)
		{
			if (apostadoGlobal[j][0] == UltimoListadoEncuentros[pPos].codigo)
				encuentroEnApostado = apostadoGlobal[j];
//				console.log("encontrado");
		}
		for ( j = 3; j < UltimoListadoTipos.length; j++)
		{
/*
			if (encuentroEnApostado)
				el_div_Res.innerHTML += f_js_CheckBoxApuesta(UltimoListadoEncuentros[pPos],UltimoListadoTipos[j].tipo,UltimoListadoTipos[j].tipo == encuentroEnApostado[1]);
			else
*/
				el_div_Res.innerHTML += f_js_CheckBoxApuesta(UltimoListadoEncuentros[pPos],UltimoListadoTipos[j].tipo);
		}
	}
	else
		alert("(" + pPos + "): no se pueden expandir las chanzas");
}

function f_js_RevisarAprobacionesPendientes()
{
  f_http_post(cliente_http
             ,"SportSiete.php"
             ,"method=ApuestasPendientes"
		+ "&Sesion=" + v_cwf_id
             ,f_js_RevisarAprobacionesPendientes_Respuesta,5000);
}

function f_js_RevisarAprobacionesPendientes_Respuesta()
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
      if (json_resp.Resultado)
      {
        if (json_resp.Resultado != "1" || !json_resp.Apuestas)
        {
          alert("Su apuesta no pudo ser almacenada!");
        }
        else
        {
          var el_div = el_id("telon");
          var tmp_txt = "";
          el_div.style.display="block";
          el_div.className="telon telonAparecer";
          el_id("telonTitulo").innerHTML = "Aprobar Apuestas";
          el_div = el_id("telonContenido");
          tmp_txt = "<table border=1>"
			+ "<tr><th>Codigo Apuesta</th><th>Monto Apostado</th><th>Factor Pagado</th><th>Instante</th></tr>";
          for (i = 0; i < json_resp.Apuestas.length; i++)
          {
            tmp_txt += "<tr>"
				+ "<td>" + json_resp.Apuestas[i].codigo + "</td>"
				+ "<td>" + json_resp.Apuestas[i].monto + "</td>"
				+ "<td>" + json_resp.Apuestas[i].factorPagado + "</td>"
				+ "<td>" + json_resp.Apuestas[i].instanteApuesta + "</td>"
				+ "<td>"
					+ "<input type=button value='Ok' onClick='f_js_ApuestaAprobar(" + json_resp.Apuestas[i].codigo + ",true);'>"
					+ "<input type=button value='X' onClick='f_js_ApuestaAprobar(" + json_resp.Apuestas[i].codigo + ",false);'>"
				+ "</td>"
			+ "</tr>";
//            alert("Apuesta pendiente : " + json_resp.Apuestas[i].codigo);
          }
          tmp_txt += "</table>";
          el_div.innerHTML = tmp_txt;
        }
      }
      else
      {
console.log(json_resp);
        alert("Error no identificado");
      }
      return;
    }
  }
}

function f_js_ApuestaAprobar(pCodigoApuesta,pBuleano)
{
  f_http_post(cliente_http
             ,"SportSiete.php"
             ,"method=ApuestaAprobar"
		+ "&Sesion=" + v_cwf_id
		+ "&Codigo=" + pCodigoApuesta
		+ "&Decision=" + (pBuleano?1:0)
             ,f_js_ApuestaAprobar_Respuesta,5000);
}

function f_js_ApuestaAprobar_Respuesta()
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
      if (json_resp.Resultado)
      {
        if (json_resp.Resultado != "1")
        {
          alert("Su decision no pudo ser almacenada!");
        }
        else
        {
          f_js_ocultarTelon();
          if (json_resp.codigoApuesta){
	          document.frm_imprimir.codigo.value = json_resp.codigoApuesta;
        	  document.frm_imprimir.submit();
          }
          setTimeout(function() { f_js_CargarPagina('./'); }, 200);
        }
      }
      else
      {
console.log(json_resp);
        alert("Error no identificado");
      }
      return;
    }
  }
}
