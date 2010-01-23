<?php
if (!isset($project)) {
	echo _('Paramètres URL insuffisants');
	exit();
}
?><div id="page">
	<div id="sidebar">
		<?php 
		include PAGE_DIR.'specifics/sidebar/users.php';
		?>
	</div>
	<div id="contents" class="with_sidebar">
		<h1><a href="index.php?page=project&project=<?php echo $project->get('project_id'); ?>"><?php echo $project->get('project_name'); ?></a> &raquo; <?php 
		echo _('Utilisateurs'); ?></h1>
		<table id="users_datagrid" class="datagrid"></table>
		<div class="clear"></div>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function() {
	var gridWidth = $('div#contents').width() - 20;
	var colWidth = (gridWidth - 65) / 3;
	
	$("#users_datagrid").flexigrid({
		url: '<?php echo LOCAL_PATH; ?>engines/get-users.php',
		dataType: 'json',
		colModel: [
			{display: '<?php echo _('ID'); ?>', name : 'id', width : 15, sortable : false, align: 'left'},
			{display: '<?php echo str_replace('\'', '\\\'', _('Nom d\'utilisateur')); ?>', name : 'username', width: colWidth, sortable : false, align: 'left'},
			{display: '<?php echo _('Groupes'); ?>', name : 'groups', width : colWidth, sortable : false, align: 'left'},
			{display: '<?php echo _('Droits supplémentaires'); ?>', name : 'rights', width : colWidth, sortable : false, align: 'left'}
		],
		buttons: [
			{name: '<?php echo _('Ajouter'); ?>', bclass: 'add', position: 'left', onpress : function (a,grid){
				
			}},
			{name: '<?php echo _('Supprimer'); ?>', bclass: 'delete', position: 'left', onpress : function (a,grid){
				if ($('.trSelected',grid).length <= 0) {
					alert('<?php echo _('Vous devez sélectionner au moins un utilisateur'); ?>');
				} else {
					if ($('.trSelected',grid).length > 1) {
						var string = '<?php echo _('Êtes-vous sur de vouloir supprimer ces %d utilisateurs ?'); ?>';
					} else {
						var string = '<?php echo _('Êtes-vous sur de vouloir supprimer cet utilisateur ?'); ?>';
					}
					
					if (confirm(string.replace(/%d/, $('.trSelected',grid).length))) {
						$("#users_datagrid").editRemove($('.trSelected',grid));
					}
				}
			}},
			{separator: true, position: 'left'},
			{name: '<?php echo _('Éditer'); ?> ', bclass: 'edit', position: 'left', onpress: function (a,grid){
				if ($('.trSelected',grid).length == 0) {
					alert('<?php echo _('Vous devez sélectionner un utilisateur'); ?>');
				} else {
					$('.trSelected:first',grid).dblclick();
				}
			}}
		],
		searchitems: [
			{display: '<?php echo str_replace('\'', '\\\'', _('Chaine d\'origine')); ?>', name : 'msgid', isdefault: true},
			{display: '<?php echo _('Traduction'); ?>', name : 'msgstr'}
		],
		params:[
			{name: 'project', value: '<?php echo $project->get('project_id'); ?>'}
		],
		usepager: false,
		title: '<?php echo _('Utilisateurs'); ?>',
		useRp: false,
		showTableToggleBtn: false,
		width: gridWidth,
		height: 250,
		dblclickCallback: function (object) {
			alert('Edit');
		}
	});
});
</script>