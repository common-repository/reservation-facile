<?php
defined( 'ABSPATH' ) or die();
function rf_process_action_parameters(){
	global $wpdb;
	global $SAFE_DATA;
	global $rf_linkCSV;
	global $listOfCSVFiles;
	global $rf_act;
	if (!current_user_can('administrator')){return;}
	$isDataFormSafe = rf_secureDataForm();
	if (!$isDataFormSafe[0]){
		echo 'DEBUG2:------------------- '. $isDataFormSafe[1].'<br>';
		error_log('DEBUG2:------------------- '. $isDataFormSafe[1]);
	}else{
		$SAFE_DATA = $isDataFormSafe[2];
	}
	if (isset($SAFE_DATA['rf_act'])){
		$rf_act = $SAFE_DATA['rf_act'];
		check_admin_referer($rf_act);
	}
	if ($rf_act == 'saveOptionsGeneral'){
		$customcss = $SAFE_DATA['customcss'];
		$roundnumber = abs($SAFE_DATA['roundnumber']);
		$decimalseparator = $SAFE_DATA['decimalseparator'];
		$timezone = $SAFE_DATA['timezone'];
		$wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}rf_parameters SET val=%s WHERE nom='customcss'",$customcss));
		$wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}rf_parameters SET val=%s WHERE nom='roundnumber'",$roundnumber));
		$wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}rf_parameters SET val=%s WHERE nom='decimalseparator'",$decimalseparator));
		$wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}rf_parameters SET val=%s WHERE nom='timezone'",$timezone));
		global $rf_parameters;
		$rf_parameters = array();
		rf_addAdminNotice(__('General parameters updated', 'reservation-facile'),2);
	}
	echo '<div id="rf_content"><div id="rf_content1"><h1 id="rf_mainPluginTitle">';
	echo '<a href="'.admin_url('admin.php?page=reservation-facile').'"><img src="'.plugins_url( 'img/logo55.png', __FILE__ ).'"></a>';
	echo '<span>'.get_admin_page_title().'</span></h1>';
	echo '<div class="rf_wrap">';	  
	echo rf_displayAdminNotice(2);

	echo '<div id="rf_plugin_onglets_optionsMenu"><div class="rf_general_panel"><div>';
	echo '<form action="" method="post" id="rf_parameters_form"><input type="hidden" name="rf_act" value="saveOptionsGeneral">';
	wp_nonce_field('saveOptionsGeneral');
	echo '<button class="rf_accordion">'.__('General', 'reservation-facile').'</button><div class="rf_panel"><br>';
	echo '<label for="rf_roundnumber"><b>'.__('Rounded to', 'reservation-facile').':</b></label><br>';
	echo '<input type="number" min="0" max="6" step="any" name="roundnumber" id="rf_roundnumber" value="'.rf_getParameter('roundnumber').'">';
	echo '<br>'.__('Number of digits to be kept after the decimal separator during rounding', 'reservation-facile');
	echo '<br><br><label for="rf_decimalseparator"><b>'.__('Decimal separator for CSV export', 'reservation-facile').':</b></label><br>';
	echo '<input type="text" name="decimalseparator" id="rf_decimalseparator" value="'.rf_getParameter('decimalseparator').'">';
	echo '<br>'.__('Character to use as a decimal separator for numeric values ​​when exporting CSV files', 'reservation-facile');
	echo '<br><br><label for="rf_timezone"><b>'.__('Time zone', 'reservation-facile').':</b></label><br>';
	echo '<select name="timezone1" id="timezone1" onchange="showTimezone(this.value,\'\')">
			<option value=""></option>
			<option value="1" '.((explode('/',rf_getParameter('timezone'))[0] == 'Africa')? 'selected' : '').'>Africa</option>
			<option value="2" '.((explode('/',rf_getParameter('timezone'))[0] == 'America')? 'selected' : '').'>America</option>
			<option value="3" '.((explode('/',rf_getParameter('timezone'))[0] == 'Antarctica')? 'selected' : '').'>Antarctica</option>
			<option value="4" '.((explode('/',rf_getParameter('timezone'))[0] == 'Arctic')? 'selected' : '').'>Arctic</option>
			<option value="5" '.((explode('/',rf_getParameter('timezone'))[0] == 'Asia')? 'selected' : '').'>Asia</option>
			<option value="6" '.((explode('/',rf_getParameter('timezone'))[0] == 'Atlantic')? 'selected' : '').'>Atlantic</option>
			<option value="7" '.((explode('/',rf_getParameter('timezone'))[0] == 'Australia')? 'selected' : '').'>Australia</option>
			<option value="8" '.((explode('/',rf_getParameter('timezone'))[0] == 'Europe')? 'selected' : '').'>Europe</option>
			<option value="9" '.((explode('/',rf_getParameter('timezone'))[0] == 'Indian')? 'selected' : '').'>Indian</option>
			<option value="10" '.((explode('/',rf_getParameter('timezone'))[0] == 'Pacific')? 'selected' : '').'>Pacific</option>
			<option value="11" '.((rf_getParameter('timezone') == 'UTC')? 'selected' : '').'>Others</option>
		</select>';
	//echo '<input type="text" name="timezone" id="rf_timezone" list="tzlist" value="'.rf_getParameter('timezone').'">';
	echo '<select name="timezone" id="rf_timezone"></select>';
	echo '<script>showTimezone(document.getElementById("timezone1").value,"'.rf_getParameter('timezone').'")</script>';
	echo '<br>'.__('Time zone to use for database', 'reservation-facile');
	//echo '<datalist id="tzlist"></datalist>';
	echo '<br><br><label for="rf_customcss"><b>'.__('Custom CSS', 'reservation-facile').':</b></label>';
	echo '<textarea rows="10" name="customcss" id="rf_customcss">'.rf_getParameter('customcss').'</textarea>';
	echo '<br>'.__('This setting allows you, for example, to change the visual appearance of the elements, such as colors, size and layout of the booking form via your own CSS rules.', 'reservation-facile');
	echo '<br><br>';
	echo ''.get_submit_button(__('Save', 'reservation-facile')).'<br><br></div></form></div>';

	
	echo '<div>';
	echo '<button class="rf_accordion">'.__('Data Import / Export', 'reservation-facile').'</button><div class="rf_panel"><br>';
	echo '<label><b>'.__('Export', 'reservation-facile').':</b></label><br>';
	echo '<form action="" method="post" id="rf_parameters_form"><input type="hidden" name="rf_act" value="exportCSV">';
	wp_nonce_field('exportCSV');
	$month = date("m");
	$year = date("Y");
	$nb_jours = rf_cal_days_in_month($month, $year);
	$filterArrivalDate = "$year-$month-$nb_jours";
	$filterDepartureDate = "$year-$month-01";
	echo '<input type="date" " name="filterDepartureDate" value="'.$filterDepartureDate.'">';
	echo '<input type="date" " name="filterArrivalDate" value="'.$filterArrivalDate.'">';
	echo '<select name="tableschoice"><option value="reservation">' . __('Bookings only','reservation-facile') . '</option>';
	echo '<option value="all">' . __('All tables (any date)','reservation-facile') . '</option></select>';
	echo ''.get_submit_button(__('Export', 'reservation-facile')).'</form><br>'.__('"All tables" includes bookings, places, shortcodes, price changes and parameters.', 'reservation-facile');
	
	if ($rf_linkCSV != ''){
		echo '<script>window.open("'.plugins_url('exports/' . $rf_linkCSV, __FILE__).'")</script>';
	}
	$listOfCSVFiles = scandir(plugin_dir_path( __FILE__ ) . '/exports/');
	echo '<br><br><label><b>'.__('Exported Files', 'reservation-facile').':</b></label><br>';
	foreach($listOfCSVFiles as $file){
		if (substr($file,0,1) != '.'){
			$tabFile = explode('/',$file);
			echo '<a href="'.plugins_url('exports/' . $file, __FILE__).'">'.$tabFile[sizeof($tabFile)-1].'</a> ('.date ("Y-m-d H:i:s", filemtime(plugin_dir_path( __FILE__ ) . '/exports/' . $file)).')<br>';
		}
	}
	
	echo '<br><br><label><b>'.__('Import', 'reservation-facile').':</b></label><br>';
	echo '<form action="" method="post" id="rf_parameters_form" enctype="multipart/form-data"><input type="hidden" name="rf_act" value="importCSV">';
	wp_nonce_field('importCSV');
	echo '<input type="file" name="importfile" >'.get_submit_button(__('Import', 'reservation-facile')).'';
	echo '<br>'.__('Always make a backup before importing data. In the import file, the first field of the first line must be the name of the table : "bookings", "places", "shortcodes", "pricechanges" or "parameters". If an id of data already exists, the data will be replaced. Don\'t fill id field data in the file in order to add data in all cases (except for the parameters table).', 'reservation-facile');
	echo '<br><br>';
	
	if ($rf_act == 'importCSV'){
		if (isset($_FILES['importfile'])){
			$filename = $_FILES['importfile']['tmp_name'];
			if ($handle = fopen( $filename, 'r' )){
				if ($data = fgetcsv($handle, 1000, ";")){
					$table = str_replace("\xEF\xBB\xBF",'',$data[0]); 
					switch($table){
						case 'bookings':
							$nbNewBookings = 0;
							$nbUpdatedBookings = 0;
							while (($d = fgetcsv($handle, 1000, ";")) !== FALSE) {
								$sqlVar = [$d[1],$d[21],$d[3],$d[4],$d[5],$d[7],$d[8],$d[9],$d[10],$d[11],$d[12],$d[13],$d[14],$d[15],$d[16],$d[17],$d[18],$d[19],$d[24],$d[20],$d[22],$d[23],$d[6],$d[2],str_replace('++o++',';',$d[26]),str_replace('++o++',';',$d[27]),$d[28],$d[1]];
								$wpdb->query($wpdb->prepare("INSERT INTO {$wpdb->prefix}rf_bookings(id,rf_idSpace,date,nb_de_place,prix_de_la_place,acompte_prix,acompte_pourcentage,date_arrivee,date_depart,nb_de_personnes,nom,prenom,adresse,code_postal,ville,pays,email,telephone,remarques,statut,emplacement,lieu,devise,reference_interne,periodesprices,dayprice,timeUnit) VALUES (%d,%d,%s,%d,%d,%d,%d,%s,%s,%d,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%d) ON DUPLICATE KEY UPDATE ID = %d",$sqlVar));
								$newBooking = false;
								if ($wpdb->insert_id != 0){
									$booking_id = $wpdb->insert_id;
									$nbNewBookings++;
									$newBooking = true;
								}else{
									$booking_id = $d[1];
									$nbUpdatedBookings++;
								}
								//Import MP
								$tabmps = explode('--o--',$d[25]);
								foreach($tabmps as $strmp){
									if ($strmp != ''){
										$mp = explode(',',$strmp);
										if ($newBooking){$mp[0] = 'NULL';}
										$wpdb->query($wpdb->prepare("INSERT INTO {$wpdb->prefix}rf_modifyprice_bookings(id,id_reservation,label,description,quantite,montant,pourcentage,periode_heure,code,type,details_texte) VALUES (%d,%d,%s,%s,%d,%d,%d,%d,%s,%s,%s) ON DUPLICATE KEY UPDATE ID = %d",$mp[0],$booking_id,$mp[1],$mp[2],$mp[3],$mp[4],$mp[5],$mp[6],$mp[7],$mp[8],$mp[9],$mp[0]));
									}
								}
							}
							echo __('Bookings created','reservation-facile') . ': ' . $nbNewBookings . '<br>';
							echo __('Bookings updated','reservation-facile') . ': ' . $nbUpdatedBookings . '<br>';
							break;
						case 'places':
							$nbNewSpaces = 0;
							$nbUpdatedSpaces = 0;
							while (($d = fgetcsv($handle, 1000, ";")) !== FALSE) {
								$wpdb->query($wpdb->prepare("INSERT INTO {$wpdb->prefix}rf_locations(id, nom) VALUES (%d,%s) ON DUPLICATE KEY UPDATE ID = %d",[$d[2],$d[3],$d[2]]));
								$sqlVar = [$d[1],$d[2],$d[4],$d[5],$d[6],$d[7],$d[8],$d[9],$d[10],$d[11],$d[12],$d[13],$d[14],$d[15],$d[16],$d[17],$d[18],$d[19],$d[20],$d[21],str_replace('++o++',';',$d[22]),str_replace('++o++',';',$d[23]),$d[24],$d[25],$d[26],$d[27],$d[28],$d[29],$d[30],$d[31],$d[32],$d[33],$d[34],$d[35],$d[36],$d[37],$d[38],$d[39],$d[40],$d[41],$d[42],$d[43],$d[44],$d[45],$d[46],str_replace('&quot;','"',$d[47]),str_replace('++o++',';',$d[48]),$d[1]];
								$wpdb->query($wpdb->prepare("INSERT INTO {$wpdb->prefix}rf_spaces(
								id,
								id_lieu,
								label,
								nb_de_place,
								prix_de_la_place,
								devise,
								timeUnit,
								minBookingDuration,
								tps_reservation_max_heure,
								acompte_prix,
								acompte_pourcentage,
								lien_CGU,
								date_debut_reservation,
								date_fin_reservation,
								description,
								statut_par_defaut_reservation,
								notification_email,
								email_notification,
								payment_instructions,
								user_minutes_interval,
								dayprice,
								periodesprices,
								form_heure_debut,
								form_heure_fin,
								form_personnes,
								form_nom,
								form_prenom,
								form_adresse,
								form_code_postal,
								form_ville,
								form_pays,
								form_email,
								form_telephone,
								form_remarques,
								form_nb_de_place,
								info_date_debut_reservation,
								info_date_fin_reservation,
								info_prix_de_la_place,
								info_acompte_prix,
								info_acompte_pourcentage,
								info_timeUnit,
								info_minBookingDuration,
								info_tps_reservation_max_heure,
								info_description,
								info_calendrier,
								openingtimes,
								exceptionalclosure,
								form_date_debut,
								form_date_fin
								) VALUES (%d,%d,%s,%d,%d,%s,%d,%d,%d,%d,%d,%s,%s,%s,%s,%s,%s,%s,%s,%d,%s,%s,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%s,%s,'2','2') ON DUPLICATE KEY UPDATE ID = %d",$sqlVar));
								if ($wpdb->insert_id != 0){
									$space_id = $wpdb->insert_id;
									$nbNewSpaces++;
								}else{
									$space_id = $d[1];
									$nbUpdatedSpaces++;
								}
								//Update MP
								$wpdb->query($wpdb->prepare("DELETE FROM {$wpdb->prefix}rf_modifyprice_spaces WHERE rf_idSpace = %d",$space_id));
								$tabmps = explode(',',$d[49]);
								foreach($tabmps as $mp){
									if ($mp != ''){
										$wpdb->query($wpdb->prepare("INSERT INTO {$wpdb->prefix}rf_modifyprice_spaces(id,rf_idSpace,id_modificationprix) VALUES (%d,%d,%d)",['',$space_id,$mp]));
									}
								}
							}
							echo __('Places created','reservation-facile') . ': ' . $nbNewSpaces . '<br>';
							echo __('Places updated','reservation-facile') . ': ' . $nbUpdatedSpaces . '<br>';						
							break;
						case 'shortcodes':
							$nbNewShortcodes = 0;
							$nbUpdatedShortcodes = 0;
							while (($d = fgetcsv($handle, 1000, ";")) !== FALSE) {
								$sqlVar = [$d[1],$d[2],$d[3],$d[4],$d[1]];
								$wpdb->query($wpdb->prepare("INSERT INTO {$wpdb->prefix}rf_shortcodes(id,nom,affichage,tabEmplacements) VALUES (%d,%s,%s,%s) ON DUPLICATE KEY UPDATE ID = %d",$sqlVar));
								if ($wpdb->insert_id != 0){
									$nbNewShortcodes++;
								}else{
									$nbUpdatedShortcodes++;
								}
							}
							echo __('Shortcodes created','reservation-facile') . ': ' . $nbNewShortcodes . '<br>';
							echo __('Shortcodes updated','reservation-facile') . ': ' . $nbUpdatedShortcodes . '<br>';
							break;
						case 'pricechanges':
							$nbNewMP = 0;
							$nbUpdatedMP = 0;
							while (($d = fgetcsv($handle, 1000, ";")) !== FALSE) {
								$sqlVar = [$d[1],$d[2],$d[3],$d[4],$d[5],$d[6],$d[7],$d[8],$d[9],$d[10],$d[11],$d[12],$d[13],$d[1]];
								$wpdb->query($wpdb->prepare("INSERT INTO {$wpdb->prefix}rf_modifyprice(id,label,description,date_debut,date_fin,quantite,quantite_initiale,montant,pourcentage,periode_heure,code,type,details_texte) VALUES (%d,%s,%s,%s,%s,%d,%d,%d,%d,%d,%s,%s,%s) ON DUPLICATE KEY UPDATE ID = %d",$sqlVar));
								if ($wpdb->insert_id != 0){
									$nbNewMP++;
								}else{
									$nbUpdatedMP++;
								}
							}
							echo __('Price changes created','reservation-facile') . ': ' . $nbNewMP . '<br>';
							echo __('Price changes updated','reservation-facile') . ': ' . $nbUpdatedMP . '<br>';
							break;
						case 'parameters':
							while (($d = fgetcsv($handle, 1000, ";")) !== FALSE) {
								$sqlVar = [$d[1],$d[2],$d[3]];
								if ($d[1] != ''){
									$wpdb->query($wpdb->prepare("REPLACE INTO {$wpdb->prefix}rf_parameters(id,nom,val) VALUES (%d,%s,%s)",$sqlVar));
								}
							}
							echo __('Parameters updated','reservation-facile') . '<br>';
						break;
						default:
							echo __('Import Error: First field is not a valid table name:','reservation-facile') . ' ' . $table;
					}
				}else{
					echo __('Import Error: Unable to read first line','reservation-facile');
				}
			}else{
				echo __('Import Error: Unable to open file','reservation-facile');
			}
			fclose($handle);
		}else{
			echo __('Import Error: File not found','reservation-facile');
		}
	}
	
	echo '<br><br></div></form></div></div>';
	echo '</div><div id="rf_content2"><div>';
	echo rf_getTutorialsLinks();
	echo '</div></div></div></div>';
}
