{*smarty*}
<form action="{$smarty.server.PHP_SELF}?{$smarty.server.QUERY_STRING}" method="POST">
	<table>
		<tr>
			<td>Nombre usuario:</td><td> <input name="username"></td>
		</tr>
		<tr>
			<td>Contrase�a:</td> <td><input name="password" type="password"></td>
		</tr>
		<tr>
			<td colspan="2"><input value="ingresar" type="submit"></td>
		</tr>
		
	</table>
</form>