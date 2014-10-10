{*smarty*}
<div id="infoProyecto">
<h3>{$proyecto->getNombre()}</h3>
<div> <label>Directorio</label> {$proyecto->getRuta()}</div>

{if $proyecto->getDbConfig() ==null}
	<br>El proyecto no tiene definida la conexión de base de datos<br>
{else}
	{$dbConf = $proyecto->getDbConfig()}
<div id="dbConfig">
	<div> <label>Host</label> {$dbConf->host}</div>
	<div> <label>Usuario</label> {$dbConf->usuario}</div>
	<div> <label>Password</label> {if !empty($dbConf->password)}Definido (oculto){else}Vacio{/if}</div>
	<div> <label>Nombre DB</label> {$dbConf->nombreBase}</div>
	{if !empty($dbConf->puerto)}	
	<div> <label>Puerto</label> {$dbConf->puerto}</div>
	{/if}
</div>
{/if}
<div> <label>Proyecto Ecplise</label> {if $proyecto->getTieneProyectoEclipse()}Tiene{else}NO tiene{/if}</div>
</div>