<?php
defined( 'ABSPATH' ) or die();
function rf_process_action_shortcodes(){
	global $wpdb;
	global $SAFE_DATA;
	global $rf_act;
	
	if (!current_user_can('administrator')){return;}
	$isDataFormSafe = rf_secureDataForm();
	if (!$isDataFormSafe[0]){
		echo 'DEBUG4:------------------- '. $isDataFormSafe[1].'<br>';
		error_log('DEBUG4:------------------- '. $isDataFormSafe[1]);
	}else{
		$SAFE_DATA = $isDataFormSafe[2];
	}
	if (isset($SAFE_DATA['rf_act'])){
		$rf_act = $SAFE_DATA['rf_act'];
		check_admin_referer($rf_act);
	}
	
	$id_shortcode = -1;
	$nom_shortcode = 'Shortcode';
	$affichage_Shortcode = 'fulldisplay';
	$tabEmplacements_Shortcode = [];
	
	if ($rf_act == 'newSaveShortcode'){
		$nom_shortcode = $SAFE_DATA['shortcodename'];
		$affichage_Shortcode = $SAFE_DATA['shortcodedisplay'];
		$tabEmplacements = $SAFE_DATA['tabEmplacements'];
		$wpdb->query($wpdb->prepare("INSERT INTO {$wpdb->prefix}rf_shortcodes (id,nom,affichage,tabEmplacements) VALUES (NULL,%s,%s,%s)",$nom_shortcode,$affichage_Shortcode,$tabEmplacements));
		$SAFE_DATA['id_shortcode'] = $wpdb->insert_id;
		$rf_act = 'editShortcode';
	}
	
	if ($rf_act == 'saveShortcode'){
		$id_shortcode = $SAFE_DATA['id_shortcode'];
		$nom_shortcode = $SAFE_DATA['shortcodename'];
		$affichage_Shortcode = $SAFE_DATA['shortcodedisplay'];
		$tabEmplacements = $SAFE_DATA['tabEmplacements'];
		$wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}rf_shortcodes SET nom = %s, affichage = %s, tabEmplacements = %s WHERE id = %d",$nom_shortcode,$affichage_Shortcode,$tabEmplacements, $id_shortcode));
		$rf_act = 'editShortcode';
		$tabEmplacements_Shortcode = json_decode($tabEmplacements);
	}
	
	if ($rf_act == 'deleteShortcode'){
		$id_shortcode = $SAFE_DATA['id_shortcode'];
		$wpdb->query($wpdb->prepare("DELETE FROM {$wpdb->prefix}rf_shortcodes WHERE id = %d", $id_shortcode));
		unset($SAFE_DATA['id_shortcode']);
	}
	
	if (isset($SAFE_DATA['id_shortcode'])){
		$id_shortcode = $SAFE_DATA['id_shortcode'];
		$resultShortcode = $wpdb->get_row($wpdb->prepare("SELECT nom, affichage, tabEmplacements FROM {$wpdb->prefix}rf_shortcodes WHERE id = %d",$id_shortcode));
		$nom_shortcode = $resultShortcode->nom;
		$affichage_Shortcode = $resultShortcode->affichage;
		$tabEmplacements_Shortcode = json_decode(stripslashes($resultShortcode->tabEmplacements));
	}
	$tabEmplacements_Shortcode[] = ['',sizeof($tabEmplacements_Shortcode)+1,'','',''];
	
	echo '	<div id="rf_content">
				<div id="rf_content1"><h1 id="rf_mainPluginTitle">';
	echo '			<a href="'.admin_url('admin.php?page=reservation-facile').'"><img src="'.plugins_url( 'img/logo55.png', __FILE__ ).'"></a>';
	echo '			<span>'.get_admin_page_title().'</span></h1>';
	echo '			<div class="rf_wrap">';
						
	echo rf_displayAdminNotice(1);				
						
	if (($rf_act == 'newShortcode') || ($rf_act == 'editShortcode')){
		echo '<form method="POST" action="" name="rf_saveShortcode">';
		
		if ($rf_act == 'newShortcode'){
			echo '<input type="hidden" name="rf_act" value="newSaveShortcode">';
			wp_nonce_field('newSaveShortcode');
			echo '<h2>'.__('Add shortcode','reservation-facile').'</h2><div class="rf_headBar"><div>';
		}else{
			echo '<input type="hidden" name="rf_act" value="saveShortcode"><input type="hidden" name="id_shortcode" value="'.$id_shortcode.'">';
			wp_nonce_field('saveShortcode');
			echo '<h2>'.__('Edit shortcode','reservation-facile').' <span id="rf_spanShortcode">[rf_shortcodeList id="'.$id_shortcode.'"] </span></h2><div class="rf_headBar"><div>';
		}
		echo '		
					<label for="rf_shortcodename"><b>'.__('Name','reservation-facile').':</b></label>
					<br><input type="text" name="shortcodename" id="rf_shortcodename" value="'.$nom_shortcode.'">
					<br>'.__('The name of the shortcode, only visible for you.','reservation-facile').'
				</div>
				<div>
					<label for="rf_shortcodedisplay"><b>'.__('Display mode','reservation-facile').':</b></label>
					<br><select name="shortcodedisplay" id="rf_shortcodedisplay">
							<option value="dropdownlist" '.(($affichage_Shortcode == 'dropdownlist') ? 'selected' : '' ).'>'.__('Dropdown list','reservation-facile').'</option>
							<option value="fulldisplay" '.(($affichage_Shortcode == 'fulldisplay') ? 'selected' : '' ).'>'.__('Full display','reservation-facile').'</option>
						</select>
					<br>'.__('Choose how the shortcode should display the booking form list.','reservation-facile').'
				</div>
			</div>
			<br>';
		
		$position = 0;
		$maxPosition = sizeof($tabEmplacements_Shortcode);
		$emplacements = $wpdb->get_results("SELECT emp.id,emp.label,lieu.nom FROM {$wpdb->prefix}rf_spaces emp INNER JOIN {$wpdb->prefix}rf_locations lieu ON lieu.id=emp.id_lieu ORDER BY emp.id DESC");
		foreach($tabEmplacements_Shortcode as $emplacement_Shortcode){
			$position++;
			$title = __('Add a place in the shortcode','reservation-facile');
			foreach($emplacements as $emplacement){
				if ($emplacement->id == $emplacement_Shortcode[0]){
					$title = '#'.$emplacement->id.' - '.$emplacement->nom.' - '.$emplacement->label;
				}
			}
			$class = 'rf_hideAccordion';
			if ($position == $maxPosition){$class = '';}
			echo '<div><button class="rf_accordion '.$class.'">'.$title.'</button>
				 <div class="rf_panel " >
					<div id="rf_welcome">
						<div>
							<br><label for="rf_shortcodespace"><b>'.__('Place','reservation-facile').':</b></label>
							<br><select name="shortcodespace" id="rf_shortcodespace"><option></option>';
							
							foreach($emplacements as $emplacement){
								echo '<option value="'.$emplacement->id.'" '.(($emplacement->id == $emplacement_Shortcode[0]) ? 'selected' : '').'>#'.$emplacement->id.' - '.$emplacement->nom.' - '.$emplacement->label.'</option>';
							}
							echo '</select>
							<br>'.__('The place to display','reservation-facile').'
						</div>
						<div>
							<br><select name="shortcodespaceposition" id="rf_shortcodespaceposition">';
							for($i = 1; $i <= $maxPosition; $i++){
								echo '<option value="'.$i.'" '.(($emplacement_Shortcode[1] == $i) ? 'selected' : '' ).'>'.__('Position','reservation-facile').' '.$i.'</option>';
							}
							echo '</select>
						</div>
					</div>
					
					<br><br><label><b>'.__('Redirection','reservation-facile').':</b></label>
					
					<div class="rf_shortcodeRedirectionBlock">
					<div><input type="radio" name="shortcodespaceposttype'.$position.'" value="page" '.(($emplacement_Shortcode[2] == 'page') ? 'checked' : '').'><span class="rf_shortcodeRedirection">'.__('Pages','reservation-facile').': </span><select class="rf_shortcodeRedirectionSelect" name="shortcodeRedirectionSelectPage"><option></option>';
					$pages = get_pages();	
					foreach($pages as $page){
						echo '<option value="'.$page->ID.'" '.((($emplacement_Shortcode[2] == 'page')&&($emplacement_Shortcode[3] == $page->ID)) ? 'selected' : '').'>'.$page->post_title.'</option>';
					}
					echo '</select>
					</div>
					
					<div><input type="radio" name="shortcodespaceposttype'.$position.'" value="post" '.(($emplacement_Shortcode[2] == 'post') ? 'checked' : '').'><span class="rf_shortcodeRedirection">'.__('Post','reservation-facile').': </span><select class="rf_shortcodeRedirectionSelect" name="shortcodeRedirectionSelectPost"><option></option>';
					$posts = get_posts();	
					foreach($posts as $post){
						echo '<option value="'.$post->ID.'"'.((($emplacement_Shortcode[2] == 'post')&&($emplacement_Shortcode[3] == $post->ID)) ? 'selected' : '').'>'.$post->post_title.'</option>';
					}
					echo '</select>
					</div>
					
					<div><input type="radio" name="shortcodespaceposttype'.$position.'" value="link" '.(($emplacement_Shortcode[2] == 'link') ? 'checked' : '').'><span class="rf_shortcodeRedirection">'.__('Link','reservation-facile').': </span><input type="text" name="shortcodespacecustomlink" class="rf_shortcodeRedirectionSelect" value="'.(($emplacement_Shortcode[2] == 'link') ? $emplacement_Shortcode[2] : '').'">
					
					</div>
					</div>'.__('Which page should the user selection redirect to?','reservation-facile').'
					
					<br><br><label><b>'.__('Description','reservation-facile').':</b></label>
					<br>';
					wp_editor($emplacement_Shortcode[4], 'shortcodespacedescription'.$position );
					echo '<br>'.__('The description of the place, only visible with the full display mode.','reservation-facile').'
					<br><br>
				</div>
			</div>';
		}
			
		echo '<br><br><div class="rf_buttons"><div class="rf_pad10">'.get_submit_button(__('Save', 'reservation-facile'));
		echo '<textarea id="tabEmplacements" name="tabEmplacements"></textarea></form>';
		
		
		
		
		
	
		echo '<form action="" method="post" class="rf_delete" onsubmit="return confirm(\''.__('Are you sure you want to delete this shortcode?', 'reservation-facile').'\');">
			<input type="hidden" name="rf_act" value="deleteShortcode">
			<input type="hidden" name="id_shortcode" value="'.$id_shortcode.'">';
		echo rf_get_wp_nonce_field('deleteShortcode');
		echo get_submit_button(__('Delete', 'reservation-facile'),'delete').'</div></div>';
		
		
		
		
		
		echo '<br><br>'.__('Note: You will be able to add more places in the shortcode after each saving.','reservation-facile').'
		
		</form>';
		echo '<script>rf_listenerSaveShortcode()</script>';
	}else{
						
						
						
						
						
						
						
						
						
						
		$resultats5 = $wpdb->get_results("SELECT sp.id, sp.label, lo.nom FROM {$wpdb->prefix}rf_spaces sp INNER JOIN {$wpdb->prefix}rf_locations lo ON sp.id_lieu = lo.id ORDER BY lo.id DESC, sp.id DESC");	
		
		echo '<h2>'.__('Shortcodes for a single place (Booking form)','reservation-facile').'</h2>';
		echo __('Here you can choose a language (optional) for the display of the booking form. Then, copy the shortcode and paste it where you want (in a post or in a page) to show the form.','reservation-facile');
		echo '<br>';
		echo __('To add more languages, please open the pot file in the plugin languages directory and create a new mo file based on it, with a program like Poedit. Then share your creation with the community!','reservation-facile');
		
		if (sizeof($resultats5) == 0){
			echo '<br><b>'.__('Add a booking place before editing shortcodes', 'reservation-facile') . '</b>';
		}else{
			echo '<br><br><table class="rf_widefat" id="rf_tabShortcodes"><thead><th>#</th><th class="rf_column-primary">'.__('Shortcodes','reservation-facile').'</th><th>'.__('Languages','reservation-facile').'</th><th>'.__('Place','reservation-facile').'</th></tr></thead><tbody>';
			$dir = dirname(__FILE__) . '/languages';
			$listOfFiles = scandir($dir);
			$optionsLanguage = '';
			foreach($listOfFiles as $file){
				if (($file != '.')&&($file != '..')){
					if ((strtolower(substr($file,0,19)) == 'reservation-facile-')&&(strtolower(substr($file,-2,2) == 'mo'))){
						$code = explode('-',explode('.',$file)[0])[2];
						$optionsLanguage .= '<option value="'.$code.'">'.$code.'</option>';
					}
				}
			}
			foreach($resultats5 as $space){
				echo '	<tr><td>#'.$space->id.'</td>
							<td class="rf_column-primary"><span id="rf_shortcodeShortcode'.$space->id.'">[rf_shortcode id="'.$space->id.'" lang=""]</span></td>
							<td><select name="rf_shortcodeLanguage-'.$space->id.'" onchange="rf_shortcodeChangeLanguage(this)"><option></option>'.$optionsLanguage.'</select></td>
							<td>'.ucfirst($space->nom) .' - '.ucfirst($space->label).'</td>
							
							
						</tr>';
			}
			echo '</tbody></table>';
		}
		
		
		
		
		
		
		
		
		
		echo '<br><h2>'.__('Shortcodes for a multiple selection of place','reservation-facile').'</h2>';
		echo __('If you want to propose a list of available places for your visitors, you can add a shortcode here and choose the places it will display.','reservation-facile');
		
		echo '<br><br><table class="rf_widefat" id="rf_tabShortcodes"><thead><th>#</th><th class="rf_column-primary">'.__('Shortcodes','reservation-facile').'</th><th>'.__('Name','reservation-facile').'</th><th>'.__('Display','reservation-facile').'</th></tr></thead><tbody>';
		$resultats6 = $wpdb->get_results("SELECT id, nom, affichage FROM {$wpdb->prefix}rf_shortcodes ORDER BY id DESC");
		foreach($resultats6 as $shortcode){
			echo '	<tr><td>#'.$shortcode->id.'</td>
						<td class="rf_column-primary">[rf_shortcodeList id="'.$shortcode->id.'"]</td>
						<td><form action="" method="post"><input type="hidden" name="rf_act" value="editShortcode"><input type="hidden" name="id_shortcode" value="'.$shortcode->id.'">';
			wp_nonce_field('editShortcode');
			echo 		get_submit_button($shortcode->nom).'</form></td>
						<td>'.(($shortcode->affichage == 'dropdownlist') ? __('Dropdown list','reservation-facile') : __('Full display','reservation-facile') ).'</td>
						
						
					</tr>';
		}
		
		echo '</tbody></table>';
		echo '<br><form action="" method="POST"><input type="hidden" name="rf_act" value="newShortcode">';
		wp_nonce_field('newShortcode');
		echo get_submit_button(__('Add a new shortcode', 'reservation-facile')).'</form>';
	}	
	
	
	
	
	
	
	
	
	
	
	
	
	
	echo '			</div>';
	echo '		</div>
				<div id="rf_content2">
					<div>';
	echo 				rf_getTutorialsLinks();
	echo '			</div>
				</div>
			</div>';
}
