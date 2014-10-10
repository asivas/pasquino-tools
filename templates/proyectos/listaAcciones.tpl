{*smarty*}
{include file=$pQnListaAccionesTpl}
<div class="button">
		<a href="?mod={$modName}&accion=mapear&idProyecto={$entidad->getId()}"  title="Mappear tablas"><span class="ui-icon ui-icon-transfer-e-w"></span></a>
</div>
<div class="button">
		<a href="?mod={$modName}&accion=info&idProyecto={$entidad->getId()}"  title="Información del proyecto"><span class="ui-icon ui-icon-info"></span></a>
</div>