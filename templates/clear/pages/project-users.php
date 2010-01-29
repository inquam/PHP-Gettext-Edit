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
		<?php 
		if (isset($_POST['action'])) {
			if ($_POST['action'] == 'update-user') {
				$user = (int) $_POST['user'];
				if (empty($user)) {
					echo '<div class="box error"><p>'.
						_('L\'ID utilisateur est invalide').
						'</p></div>';
				} else if (is_array($_POST['rights'])) {
					foreach ($_POST['rights'] as $right => $value) {
						if ($value == 'yes') {
							Rights_Admin::grantUserRights(
								$user,
								$right,
								$_CONTEXT
							);
						} else if ($value == 'no') {
							Rights_Admin::revokeUserRights(
								$user,
								$right,
								$_CONTEXT
							);
						}
					}
					
					echo '<div class="box success"><p>'.
						_('Droits de l\'utilisateur mis à jour').
						'</p></div>';
				}
			}
		}
		?><table id="users_datagrid" class="datagrid"></table>
		<a id="users_link"></a>
		<div class="clear"></div>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function() {
	var gridWidth = $('div#contents').width() - 20;
	var colWidth = (gridWidth - 65) / 3;
	
	$("a#users_link").fancybox({
		hideOnOverlayClick: false,
		hideOnContentClick: false,
		centerOnScroll: false,
		frameWidth: gridWidth,
		frameHeight: $(window).height() - 100
	}, $('body'));
	
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
				$('a#users_link').attr('href', 
					'<?php echo LOCAL_PATH; ?>index.php?only&page=project-users-add&project=<?php echo $project->get('project_id'); ?>'
				);
				$('a#users_link').click();
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
			var user_id = object.id.substr(3);
			
			$('a#users_link').attr('href', 
				'<?php echo LOCAL_PATH; ?>index.php?only&page=project-users-edit&project=<?php echo $project->get('project_id'); ?>&user='+user_id
			);
			$('a#users_link').click();
		},
		onSuccess: function () {
			// We'll check for each tr rows when we will add them additionnal data
			$('#users_datagrid tbody tr').each(function(){
				if (this.id.substr(0, 3) != 'row') {
					return;
				}
				var userId = this.id.substr(3);
				var tr = this;
				var p = $('#users_datagrid')[0].p;

				var param = [
					{name: 'user', value: userId},
					{name: 'query', value: 'select-more'}
				];

				for (var pi = 0; pi < p.params.length; pi++) {
					param[param.length] = p.params[pi];
				}
									
				$.ajax({
					type: p.method,
					url: p.url,
					data: param,
					dataType: p.dataType,
					success: function(data) {
						$($('div', $('td', tr)[2])[0]).text(data.groups.join(', '));
						$($('div', $('td', tr)[3])[0]).text(data.rights.join(', '));
					},
					error: function(XMLHttpRequest, textStatus, errorThrown) {}
				});
			});
		}
	});
});
</script>