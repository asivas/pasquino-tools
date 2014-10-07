{*smarty*}
{foreach from=$resultadosMapeo item=resultado}
	{$resultado} <br>
{/foreach}
<form {$maperForm.attributes}>
	{foreach from=$maperForm item=element key=elemKey}
	{if isset($element.html)}
		{if !empty($element.label)}{$element.label} :{/if} {$element.html}<br/>
	{/if}
	{if $elemKey == 'tablas'}
		{foreach from=$element item=chkbox key=tabla}
		{$chkbox.label}: {$chkbox.html}<br/>
		{/foreach}
	{/if}		
	{/foreach}
{$maperForm.hidden}
</form>
