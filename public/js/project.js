$(function(){
	var optionsInit = {
			onLoad: function() {
				if($('#fileManager').length==0)
					$('input[name=ruta]').parent().append("<div id=\"fileManager\"></div>");
				var fmOpts = {ajaxPath:'fileManager.php',						
						events:{
					         click: function() {
					            var data = $(this).data();
					            $('input[name=ruta]').val(data.item.fullPath);
					         }
					      }
				};
				if($('input[name=ruta]').val()!='')
					fmOpts.path = $('input[name=ruta]').val(); 
				$('#fileManager').fileManager(fmOpts);				
			}
	}
	initModulo('dlgProyecto','Proyecto','Proyecto','filtroNombre','botonAlta','articles','proyecto',optionsInit);
	$('body').on('change','input#toggletodos',function(e){
		var setChecked = $(this).prop('checked');
		$("#tablas input[type=checkbox]").each(function() {
				$(this).prop('checked',setChecked);
				});			
	});
	
	
});