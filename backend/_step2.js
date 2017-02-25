$(document).ready(function() {
	
	reload = $(".reload").val();
	json = $.parseJSON('<?php echo getConfigValue('netatmo_config'); ?>');
	$(".image-picker").imagepicker();
	$(".sortable ul").sortable();

	$.each(json.icons, function(index, el) {
		$(".image-picker[mac='"+index+"']").val(json.icons[index]).data('picker').sync_picker_with_select();
	});

	$("#station").change(function(event) {
		$(".types").hide();
		$(".types[base='"+$("#station").val()+"']").show();
		$(".sortable").hide();
		$(".sortable[base='"+$("#station").val()+"']").show();
	});

	$(".netatmo_save_step2--button").click(function() {
		json.id = $("#station").val();

		$(".image-picker").each(function(){
			json.icons[$(this).attr("mac")] = $(this).val();
		});
		
		json.modules = new Array();

		$.each($(".sortable[base='"+json.id+"']:first li"), function(index, module) {
			json.modules.push( {id: $(module).attr("value"), type: $(module).attr("type")} );
		});
		
		$.post('setConfigValueAjax.php', {'key': 'netatmo_config', 'value': JSON.stringify(json)});

		$('#ok').show(30, function() {
			$(this).hide('slow');
			if (reload == "true") {
				location.reload();
			}
		});
	});
});
