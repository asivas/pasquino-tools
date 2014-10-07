{*smarty*}
{foreach from=$listaProyecto item=proyecto}
	{$proyecto->getNombre()}<br>
{/foreach}