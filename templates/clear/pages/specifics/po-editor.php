<form id="msgeditorform" method="POST" action="">
	<table id="po_datagrid"></table>
	<div id="msgeditor" class="flexigrid my">
		<div class="mDiv">
			<div class="title">Édition de la séléction</div>
		</div>
		<div class="my grid">
			<fieldset>
				<legend>msgid</legend>
				<textarea id="right_msgid" name="msgid"></textarea>
			</fieldset>
			<fieldset>
				<legend>msgstr</legend>
				<textarea id="right_msgstr" name="msgstr"></textarea>
			</fieldset>
			<fieldset>
				<legend>Commentaires</legend>
				<ul id="right_comments"></ul>
			</fieldset>
			<fieldset>
				<legend>Références</legend>
				<ul id="right_references"></ul>
			</fieldset>
			<div><input id="right_fuzzy" type="checkbox" name="fuzzy" value="true" /> Fuzzy</div>
			<div><input type="submit" value="Sauvegarder" /></div>
		</div>
	</div>
</form>
<script type="text/javascript">
$(document).ready(function() {
	var gridWidth = Math.round($('div#contents').width() / 2) - 20;
	var colWidth = (gridWidth - 15) / 2 - 28;
	
	$("#po_datagrid").flexigrid({
		url: '<?php echo LOCAL_PATH; ?>engines/po-edit.php',
		dataType: 'json',
		colModel: [
			{display: 'Fuzzy', name : 'fuzzy', width : 15, sortable : false, align: 'center'},
			{display: 'Chaine d\'origine', name : 'msgid', width : colWidth, sortable : true, align: 'left'},
			{display: 'Traduction', name : 'msgstr', width : colWidth, sortable : true, align: 'left'}
		],
		buttons: [
			{name: 'Ajouter', bclass: 'add', onpress : function (a,grid){
				var new_id = $("#po_datagrid").editAdd({id:0,cell:[0, '', ''],comments:[],references:[],fuzzy:false});
				$('tr#row'+new_id, grid).dblclick();
			}},
			{name: 'Supprimer', bclass: 'delete', onpress : function (a,grid){
				if (confirm('Êtes-vous sur de vouloir supprimer ces '+$('.trSelected',grid).length+' éléments ?')) {
					$("#po_datagrid").editRemove($('.trSelected',grid));
				}
			}},
			{separator: true},
			{name: 'Éditer', bclass: 'edit', onpress: function (a,grid){
				if ($('.trSelected',grid).length == 0) {
					alert('Vous devez sélectionner une élément');
				} else {
					$('.trSelected:first',grid).dblclick();
				}
			}}
		],
		searchitems: [
			{display: 'Chaine d\'origine', name : 'msgid', isdefault: true},
			{display: 'Traduction', name : 'msgstr'}
		],
		params:[
			{name: 'project', value: '<?php echo $project->get('project_id'); ?>'},
			{name: 'language', value: '<?php echo $language->getCode(); ?>'},
			{name: 'file', value: '<?php echo $language_file->getName(); ?>'}
		],
		sortname: "msgid",
		sortorder: "asc",
		usepager: false,
		title: 'Traductions',
		useRp: false,
		showTableToggleBtn: false,
		width: gridWidth,
		height: 400
	});

	$('div#msgeditor').width(gridWidth);
});
</script>
<div class="clear" />