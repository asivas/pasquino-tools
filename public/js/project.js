$(function(){
	initModulo('dlgProyecto','Proyecto','Proyecto','filtroNombre','botonAlta','articles','proyecto');
	$('body').on('change','input#toggletodos',function(e){
		var setChecked = $(this).prop('checked');
		$("#tablas input[type=checkbox]").each(function() {
				$(this).prop('checked',setChecked);
				});			
	});
});