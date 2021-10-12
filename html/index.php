<?php
  include_once("../componentes/configuracion.inc");
  include_once("../componentes/traduccion.inc");
?>
<html>
 <head>
  <link rel="stylesheet" type="text/css" href="css/general.css">
  <link href="https://fonts.googleapis.com/css?family=Roboto:normal,bold,italic&display=swap" rel="stylesheet">
  <link rel="shortcut icon" href="https://sportsiete.com/imagenes/Logo apuestas online.png">
  <script src="js/funciones_http.js"></script>
  <script src="js/ControlAnimacion.js"></script>
  <?php
	if (isset($_POST["idioma"]))
		echo "<script src='js/SportSiete.php?idioma=" . $_POST["idioma"] . "'></script>";
	else
		echo "<script src='js/SportSiete.php'></script>";
  ?>
  <meta name="viewport" content="width=device-width, user-scalable=no">
 </head>
 <body>
  <script src="https://www.paypal.com/sdk/js?client-id=ActdsKEty4lVLysmoGQLFY9v2MsddpT_uSyeIDuNENWXC5uO8Ye3gPknu7OUyXNTWRhgLKSokJhFOKLW&currency=USD&intent=capture&commit=true&integration-date=2020-07-18&debug=true"></script>
  <form name=frm_global method=post onSubmit="return false;">
	<?php
		if (isset($_POST["idioma"]))
			echo "<input type=hidden name=idioma value='" . $_POST["idioma"] . "'>";
	?>
  <div id=AreaGeneral>
   <div id=AreaEncabezado>
       <div class="ContenedorCoinsInterno">
<!--
imagenes/animacion_balon.jpg     imagenes/animacion_nfl.jpg
imagenes/animacion_beisbol2.jpg  imagenes/animacion_rugby1.jpg
imagenes/animacion_nba4.jpg      imagenes/animacion_tenis5.jpg
-->
           <div class=Coin id="Coin1" style="background-image:url(imagenes/animacion_balon.jpg);" onClick="v_js_deporte=1;f_js_Cargar();"></div>
           <div class=Coin id="Coin2" style="background-image:url(imagenes/animacion_beisbol2.jpg);" onClick="v_js_deporte=2;f_js_Cargar();"></div>
           <div class=Coin id="Coin3" style="background-image:url(imagenes/animacion_nba4.jpg);" onClick="v_js_deporte=3;f_js_Cargar();"></div>
           <div class=Coin id="Coin4" style="background-image:url(imagenes/animacion_nfl.jpg);" onClick="v_js_deporte=4;f_js_Cargar();"></div>
           <div class=Coin id="Coin5" style="background-image:url(imagenes/animacion_rugby1.jpg);" onClick="v_js_deporte=5;f_js_Cargar();"></div>
           <div class=Coin id="Coin6" style="background-image:url(imagenes/animacion_tenis5.jpg);" onClick="v_js_deporte=6;f_js_Cargar();"></div>
       </div>
    <table border=0 style='width:100%;height:100%;border-collapse:collapse;font-size:1em;'>
     <tr>
      <td class="BackgroundLogo" style="width:12em;"></td>
      <td class="BackgroundCoins" id="BackgroundCoins">
       <div class="ContenedorSuperScroll" id="ContenedorSuperScroll" onScroll="f_js_ScrollCoins(event);">
         <div class="SuperScroll">
         </div>
       </div>
      </td>
     </tr>
    </table>
   </div>
   <div id=AreaSelectores>
     <div id=AreaSelectorDeportes>
      <table border=0 style="width:100%;border-collapse:collapse;font-size:1em;color:white;">
       <tr style="height:2em;"><td align=center style="font-weight:700;"><?php echo f_Traducir("DEPORTES"); ?></td></tr>
       <tr style="height:3em;">
        <td class="TDDeporte">
         <div style="background-image:url('imagenes/Sports SportSiete-4-26.png');" onClick="v_js_deporte=1;f_js_Cargar();"></div>
         <div style="background-image:url('imagenes/Sports SportSiete-4-25.png');" onClick="v_js_deporte=2;f_js_Cargar();"></div>
         <div style="background-image:url('imagenes/Sports SportSiete-4-24.png');" onClick="v_js_deporte=5;f_js_Cargar();"></div>
         <div style="background-image:url('imagenes/Sports SportSiete-4-23.png');" onClick="v_js_deporte=8;f_js_Cargar();"></div>
         <div style="background-image:url('imagenes/Sports SportSiete-4-22.png');" onClick="v_js_deporte=10;f_js_Cargar();"></div>
        </td>
       </tr>
<!--
       <tr style="height:3em;"><td class="TDDeporte"><div style="background-image:url('imagenes/Sports SportSiete-4-26.png');"></div></td></tr>
       <tr style="height:3em;"><td class="TDDeporte"><div style="background-image:url('imagenes/Sports SportSiete-4-25.png');"></div></td></tr>
       <tr style="height:3em;"><td class="TDDeporte"><div style="background-image:url('imagenes/Sports SportSiete-4-24.png');"></div></td></tr>
       <tr style="height:3em;"><td class="TDDeporte"><div style="background-image:url('imagenes/Sports SportSiete-4-23.png');"></div></td></tr>
       <tr style="height:3em;"><td class="TDDeporte"><div style="background-image:url('imagenes/Sports SportSiete-4-22.png');"></div></td></tr>
-->
      </table>
     </div>
     <div id=AreaSelectorLigas>
      <?php echo f_Traducir("Ligas"); ?>
     </div>
   </div>
   <div id=AreaTrabajoExterna>
    <div class="BackgroundSuavizador"></div>
    <div id=AreaTrabajo>
     <div class="AreaPaginaCompleta">
       <?php echo f_Traducir("Cargando Partidos"); ?>...
     </div>
<!--
     <div class="AreaPaginaCompleta">
      <div class="AreaCuartoDePagina">
       Area equivalente a cuarto de pagina completa
      </div>
      <div class="AreaCuartoDePagina">
       segundo cuarto
      </div>
      <div class="AreaCuartoDePagina">
       tercer cuarto
      </div>
      <div class="AreaCuartoDePagina">
       cuarto cuarto
      </div>
     </div>
     <div class="AreaPaginaCompleta">
      <div class="AreaTercioDePagina">
       Area equivalente a un tercio de pagina completa
      </div>
      <div class="AreaTercioDePagina">
       segundo tercio
      </div>
      <div class="AreaTercioDePagina">
       tercer tercio
      </div>
     </div>
     <div class="AreaPaginaCompleta">
      <div class="AreaMitadDePagina">
       Area equivalente a la mitad de la pagina competa
      </div>
      <div class="AreaMitadDePagina">
       Segunda mitad
      </div>
     </div>
-->
    </div>
   </div>
   <div id=AreaApuesta>
    <table id="TableInfoPerfil" border=0 style="border-collapse:collapse;">
     <tr>
      <td>
      </td>
     </tr>
    </table>
    <table class="TableInput" border=0>
     <tr>
      <td valign=middle align=center style="position:relative;">
       <input type=text placeholder="<?php f_Traducir('Buscar'); ?>">
       <div class="IconoLupa"></div>
      </td>
     </tr>
    </table>
   </div>
   <div id=AreaBanerVertical>
    <?php echo f_Traducir("Baner"); ?>
   </div>
  </div>

  <div id=Idiomas>
   <?php
	if (isset($_POST["idioma"]) && $_POST["idioma"] == "ingles"){
		echo	"<div class=Bandera style='background-image:url(imagenes/en.gif);'></div>"
		.	"<div class=Bandera style='background-image:url(imagenes/es.gif);' onClick=\"document.frm_idioma.idioma.value='';document.frm_idioma.submit();\"></div>"
		;
	} else {
		echo	"<div class=Bandera style='background-image:url(imagenes/es.gif);'></div>"
		.	"<div class=Bandera style='background-image:url(imagenes/en.gif);' onClick=\"document.frm_idioma.idioma.value='ingles';document.frm_idioma.submit();\"></div>"
		;
	}
   ?>
  </div>

  <script language=javascript>

	<?php
		error_log("ID : " . $_POST["ID"]);
		if (isset($_POST["ID"]) && $_POST["ID"] != "" && f_renovar_id($_POST["ID"])){
			echo "v_cwf_id = '" . $_POST["ID"] . "';\n";
		}
	?>

    var tmpImg = new Image();
    var LosFondos = Array("imagenes/MLS.png","imagenes/Baseball.png");
    var FondoActual = 0;
    function f_js_CambiarFondo(){
      el_id("AreaTrabajoExterna").style.backgroundSize="0.1% 0.1%";
      eval("setTimeout(function(){el_id('AreaTrabajoExterna').style.backgroundImage='url(\"" + this.src + "\")';},1000);");
      eval("setTimeout(function(){el_id('AreaTrabajoExterna').style.backgroundSize='100% 100%';},1100);");
//      el_id("AreaTrabajoExterna").style.backgroundImage="url('" + this.src + "')";
//      console.log("Cambio " + this.src);
    }
    function f_js_PreCargarFondo(){
      tmpImg.onload = f_js_CambiarFondo;
      tmpImg.src = LosFondos[FondoActual];
      FondoActual++;
      if (FondoActual >= LosFondos.length)
        FondoActual = 0;
//      console.log("preload");
    }
    setTimeout(f_timer,50);
    setTimeout(f_js_PreCargarFondo,100);
    setInterval(f_js_PreCargarFondo,10000);

    var el_div_coins = el_id("BackgroundCoins");

	function f_js_DibujarObjetos(pPos){
		var n = Math.abs((pPos - 50000) % 600);
		var i;
		var losDivs = Array(
				el_id("Coin1")
				,el_id("Coin2")
				,el_id("Coin3")
				,el_id("Coin4")
				,el_id("Coin5")
				,el_id("Coin6")
			);
		var puntoCentral = (n/100) ;
		var desplazamiento = 400;
		for (i = 0; i < 6; i++){
			var reduccionUbicacion = Math.round(Math.abs(2.5 - i)*10);
			var reduccionTamano = Math.round(Math.abs(puntoCentral - i)*10);
			losDivs[i].style.top = Math.round(reduccionTamano / 2) + 15;
			losDivs[i].style.left = (i*55 - Math.floor(n / 2.5)) + Math.round(reduccionUbicacion / 2.5) + desplazamiento;
			losDivs[i].style.width = losDivs[i].style.height = 50 - reduccionTamano;
			console.log("" + i + " : " + n + " " + losDivs[i].style.left + " " + reduccionUbicacion + " " + reduccionTamano + " " + losDivs[i].style.width);
		}
	}

	function f_js_ScrollCoins(e){
//		console.log(el_id("ContenedorSuperScroll").scrollLeft);
//		console.log(e);
		f_js_DibujarObjetos(el_id("ContenedorSuperScroll").scrollLeft)
	}

	f_js_DibujarObjetos(50000);

    setTimeout(function(){el_id("ContenedorSuperScroll").scrollTo(50000,0);},5000);

  </script>
  </form>
  <form name=frm_imprimir method=post action=GenerarTiket.php target="_blank">
    <input type="hidden" name="codigo">
    <input type="hidden" name="ID">
  </form>
  <form name=frm_idioma method=post>
    <input type="hidden" name="idioma">
    <input type="hidden" name="ID" value="<?php isset($_POST["ID"])?$_POST["ID"]:""; ?>">
  </form>
 </body>
<html>
