function f_js_cambiar_campo(pCodigoUsuario,pLogin,pNombreCampo,pValorActual)
{
	var tmps = prompt(pNombreCampo + " para " + pLogin,pValorActual);
	if (!tmps || tmps == null)
	{
		alert("Cancelado");
		return;
	}
	document.frm_cambiar_campo.codigousuario.value = pCodigoUsuario;
	document.frm_cambiar_campo.nombrecampo.value=pNombreCampo;
	document.frm_cambiar_campo.nuevovalor.value = tmps;
	document.frm_cambiar_campo.submit();
}
