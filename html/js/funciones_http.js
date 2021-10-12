
function f_http_inicializar_cliente()
{
  var xmlhttp=false;
  /*@cc_on @*/
  /*@if (@_jscript_version >= 5)
  // JScript gives us Conditional compilation, we can cope with old IE versions.
  // and security blocked creation of the objects.
  try
  {
    xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
  }
  catch (e)
  {
    try
    {
      xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    catch (E)
    {
      xmlhttp = false;
    }
  }
  @end @*/
  if (!xmlhttp && typeof XMLHttpRequest!='undefined')
  {
    try
    {
      xmlhttp = new XMLHttpRequest();
    }
    catch (e)
    {
      xmlhttp=false;
    }
  }
  if (!xmlhttp && window.createRequest)
  {
    try
    {
      xmlhttp = window.createRequest();
    }
    catch (e)
    {
      xmlhttp=false;
    }
  }
  return xmlhttp;
}

function el_id(id)
{
  if (document.getElementById)
    return document.getElementById(id);
  else if (window[id])
    return window[id];
  return null;
}

function f_http_post_2(pxmlhttp,purl,pparametros,p_funcion)
{
  pxmlhttp.open("POST", purl, true);

  //Send the proper header information along with the request
  pxmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
//  pxmlhttp.setRequestHeader("Content-length", pparametros.length);
//  pxmlhttp.setRequestHeader("Connection", "close");

  pxmlhttp.onreadystatechange = p_funcion;
  pxmlhttp.send(pparametros);
}

function f_http_get_2(pxmlhttp,purl,p_funcion)
{
  pxmlhttp.open("GET", purl,true);
  pxmlhttp.onreadystatechange = p_funcion;
  pxmlhttp.send(null);
}

function f_http_avisar(pfuncion,pxmlhttp,ptimeout)
{
  var estado = 0;
  eval ("estado = "+pxmlhttp+".readyState;");
  if (estado=='4')
  {
    eval(pfuncion+"(true);");
    return;
  }
  ptimeout -= 400;
  if (ptimeout < 0)
    eval(pfuncion+"(false);");
  setTimeout("f_http_avisar('"+pfuncion+"','"+pxmlhttp+"',"+ptimeout+");",400);
}

function f_http_extraer(pxmlhttp)
{
  if (pxmlhttp.readyState==4)
  {
    return pxmlhttp.responseText;
  }
}

function f_http_post(pxmlhttp,ppagina,pvars,pfuncion_ok)
{
  if (!pxmlhttp)
    return false;
  f_http_post_2(pxmlhttp,ppagina,'ID=' + v_cwf_id + '&' + pvars,pfuncion_ok);
}


