<?php
if (!isset($language)) {
	echo _('Paramètres URL insuffisants');
	exit();
}
?><div id="page">
	<div id="sidebar">
		<h3><?php echo _('Compiler tous les fichiers'); ?></h3>
		<p>À venir</p>
	</div>
	<div id="contents" class="with_sidebar">
		<div class="link right">
			<a class="delete" href="index.php?page=language-delete&project=<?php echo $project->get('project_id'); ?>&language=<?php echo $language->getCode(); ?>"><?php echo _('Supprimer'); ?></a>
		</div>
		<h1><a href="index.php?page=project&project=<?php echo $project->get('project_id'); ?>"><?php echo $project->get('project_name'); ?></a> &raquo; <?php echo $language->getName(); ?> &raquo; 
		<?php echo _('Compiler tous les fichiers'); ?></h1>
		<?php 
		if (isset($_POST['files'])) {
			foreach ($_POST['files'] as $file => $type) {
				var_dump('n', $file, $type);
				
				try {
					$language_file = new Project_Language_File($language, $file);
					$language_file->compile($type, (isset($_POST['with-fuzzy'])));
					
					echo '<div class="box success">'.
						'<p>'.sprintf(_('Fichier compilé: <strong>%s</strong>'), $output_file_path).' - <a href="index.php?page=language-file&project='.$project->get('project_id').'&language='.$language->getCode().'&file='.$language_file->getName().'">'._('Continuer').'</a></p>'.
						'</div>';
				} catch (Exception $e) {
					echo '<div class="box success">'.
						'<p>'.$e->getMessage().'</p>'.
						'</div>';
				}
			}
		}
		?>
		<div class="box">
			<p><?php echo _('Sélectionnez les fichiers que vous souhaitez compiler, et précisez le format de compilation.'); ?></p>
			<?php 
			$files = $language->getFiles();
			if (!empty($files)) {
			?><form action="" method="POST">
				<ul>
					<?php
					foreach ($files as $file) {
						$file_warnings = $file->getWarnings();
						
						echo '<li class="'.(empty($file_warnings) ? 'valid' : 'invalid').'">'.$file->getName().'<br />Compiler en ';
						
						echo '<label class="'.
							(in_array(Project_Language_File::W_COMPILE, $warnings) ? 'invalid' : 'valid')
							.'"><input type="checkbox" name="files['.$file->getName().'][normal]" value="yes" /> <code>.mo</code></label> ';
						echo '<label class="'.
							(in_array(Project_Language_File::W_COMPILE_JSON, $warnings) ? 'invalid' : 'valid')
							.'"><input type="checkbox" name="files['.$file->getName().'][json]" value="yes" /> JSON</label> ';
						echo '</li>';
					}
					?>
				</ul>
				<p><label><input type="checkbox" name="with-fuzzy" value="yes" /> <?php echo _('Inclure les valeurs qualifiées de <code>fuzzy</code>'); ?></label></p>
				<p><input type="submit" name="compile" value="<?php echo _('Compiler le fichier .po'); ?>" /></p>
			</form>
			<?php
			} else {
				echo '<ul><li>'._('Aucun fichier').'</li></ul>';
			}
			?>
		</div>
	</div>
</div>