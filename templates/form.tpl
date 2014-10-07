{*smarty*}
{foreach from=$resultadosMapeo item=resultado}
	{$resultado} <br>
{/foreach}
<form {$maperForm.attributes}>
	{foreach from=$maperForm item=element key=elemKey}	
	{if is_array($element) && isset($element.html)}
		{if !empty($element.label)}{$element.label} :{/if} {$element.html}<br/>
	{/if}	
	
	{if $elemKey == 'tablas'}
	<br>
	<div><input type="checkbox" id="toggletodos"/> <label for="toggletodos">Todos</label> </div>
	<br>
	<div id="tablas">
		{foreach from=$element item=chkbox key=tabla}
		<div>{$chkbox.html}</div>
		{/foreach}
	</div>
	{/if}		
	
	{/foreach}
	
{$maperForm.hidden}
</form>
