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
				$("#dlgProyecto").dialog({ position: 'center' });
			}
	}
	initModulo('dlgProyecto','Proyecto','Proyecto','filtroNombre','botonAlta','articles','creadorProyectos',optionsInit);
	$('body').on('change','input#toggletodos',function(e){
		var setChecked = $(this).prop('checked');
		$("#tablas input[type=checkbox]").each(function() {
				$(this).prop('checked',setChecked);
				});			
	});
	
	//tuchada de meter los botones de gridAccionesItem en el div .lista-acciones
	$("li.gridAccionesItem div.lista-acciones").each(function(){
			$(this).append($(this).parent().find("div.button"));
		});
});