{*smarty*}
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		{include file="decorators/head.tpl"}
	</head>
	<body>
<div id="todo">
<table id="main" align="center" cellpadding=0 cellspacing=0>
<!--	<tr>
        <td colspan="3" id="superior" >
			{include file="decorators/header.tpl"}
		</td>
	</tr> -->
	{**}
	<tr>
		<td colspan="3" class="style_bienvenido">
			{include file="decorators/menu.tpl"}
			{if $errores neq ''}
				{foreach item=errorMsg from=$errores}
				<p class="errorMsg">{$errorMsg}</p>
				{/foreach}
			{/if}
		</td>
	</tr>
	{**}
	{if $menuMod neq ''}
	<tr class="centro">
        <td colspan="3" >
			{include file=$menuMod}
		</td>
	</tr>
	{/if}
	<tr class="centro">
		<td colspan="3" height="100%">
		<div style="text-align:center">
			<div id="contenido">
				<div id="logo"></div>
				<div id="cabeceraUsuario">
					<h3>Bienvenido</h3>
				</div>
				{include file=$pantalla}
			</div>
		</div>
		</td>
	</tr>
	<tr>
		<td colspan="3" id="inferior">
			{include file="decorators/footer.tpl"} 
		</td>
	</tr>
</table>
</div>
	</body>
</html>		