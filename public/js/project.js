$(function(){
	$('body').on('change','input#toggletodos',function(e){
		var setChecked = $(this).prop('checked');
		$("#tablas input[type=checkbox]").each(function() {
				$(this).prop('checked',setChecked);
				});			
	});
});