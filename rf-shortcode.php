<?php
defined( 'ABSPATH' ) or die();
add_shortcode('rf_shortcode', 'rf_shortcode');
add_shortcode('rf_shortcodeList', 'rf_shortcodeList');
add_action( 'wp_enqueue_scripts', 'rf_load_front_js' );
add_action( 'wp_enqueue_scripts', 'rf_load_front_css' );

function rf_shortcodeList($atts){
	global $wpdb;
	global $SAFE_DATA;
	global $rf_act;
	$isDataFormSafe = rf_secureDataForm();
	if (!$isDataFormSafe[0]){
		echo 'DEBUG5:------------------- '. $isDataFormSafe[1].'<br>';
		error_log('DEBUG5:------------------- '. $isDataFormSafe[1]);
	}else{
		$SAFE_DATA = $isDataFormSafe[2];
	}
	if (isset($SAFE_DATA['rf_act'])){
		$rf_act = $SAFE_DATA['rf_act'];
		check_admin_referer($rf_act);
	}
	$rf_idSpace = (isset($SAFE_DATA['rf_idSpace']))? $SAFE_DATA['rf_idSpace'] : -1;
	$msg = "";
	$inputsError = [];
	$result = rf_getCSS();
	if (!isset($atts['id'])){
		return __('"id" attribute missing when calling shortcode', 'reservation-facile');
	}
	$rf_idShortcode = $atts['id'];
	$row = $wpdb->get_row($wpdb->prepare("SELECT affichage, tabEmplacements FROM {$wpdb->prefix}rf_shortcodes WHERE id = %d", $rf_idShortcode));
	if ($row){
		$result .= '<span id="rf_mainDiv"><span id="rf_displayShortcode">';
		if ($row->affichage == 'dropdownlist'){
			$result .= '<select onchange="document.location.href=this.value"><option>'.__('Select location','reservation-facile').'</option>';
		}
		$tabEmplacements = json_decode(stripslashes($row->tabEmplacements));
		$listId = [];
		$listPos = [];
		foreach($tabEmplacements as $emplacements){
			$listId[] = $emplacements[0];
			$listPos[$emplacements[1]] = ['id'=>$emplacements[0],'posttype'=>$emplacements[2],'postlink'=>$emplacements[3],'description'=>$emplacements[4]];
		}
		$where = '';
		for($i=0;$i<sizeof($listId);$i++){
			if ($i > 0){
				$where .= ' OR ';
			}
			$where .= 'emp.id = %d';
		}
		$results = $wpdb->get_results($wpdb->prepare("SELECT emp.id, emp.label, loc.nom FROM {$wpdb->prefix}rf_spaces emp LEFT JOIN {$wpdb->prefix}rf_locations loc ON emp.id_lieu = loc.id WHERE $where", $listId));
		$listId = [];
		if (sizeof($results)>0){
			foreach($results as $space){
				$listId[$space->id] = $space->nom . ' - ' . $space->label . '<br>';
			}
			$maxListPos = sizeof($listPos);
			for($i = 1; $i <= $maxListPos; $i++){
				if (isset($listPos[$i])){
					if ($listPos[$i]['posttype'] == 'link'){
						$redirect = $listPos[$i]['postlink'];
						$redirectName = $listPos[$i]['postlink'];
					}else{
						$redirect = get_permalink($listPos[$i]['postlink']);
						$redirectName = get_the_title($listPos[$i]['postlink']);
					}
					if ($row->affichage == 'dropdownlist'){
						$result .= '<option value="'.$redirect.'">'.$redirectName.'</option>';
					}else{
						$result .= '<div class="rf_spaceDescription"><h3>'.$listId[$listPos[$i]['id']].'</h3>';
						$result .= $listPos[$i]['description'] . '<a href="'.$redirect.'">'.__('More details','reservation-facile').'</a></div>';
					}
				}else{
					$maxListPos++;
				}
			}
		}
		if ($row->affichage == 'dropdownlist'){
			$result .= '</select>';
		}
		$result .= '</span></span>';
	}else{
		$result = __('We are sorry, this list of locations is no longer available.', 'reservation-facile');
	}
	return $result;
}

function rf_shortcode($atts){
	global $wpdb;
	global $SAFE_DATA;
	global $rf_act;
	$isDataFormSafe = rf_secureDataForm();
	if (!$isDataFormSafe[0]){
		echo 'DEBUG6:------------------- '. $isDataFormSafe[1].'<br>';
		error_log('DEBUG6:------------------- '. $isDataFormSafe[1]);
	}else{
		$SAFE_DATA = $isDataFormSafe[2];
	}
	if (isset($SAFE_DATA['rf_act'])){
		$rf_act = $SAFE_DATA['rf_act'];
		check_admin_referer($rf_act);
	}
	$rf_idSpace = (isset($SAFE_DATA['rf_idSpace']))? $SAFE_DATA['rf_idSpace'] : -1;
	$msg = "";
	$inputsError = [];
	$result = rf_getCSS();
	if (!isset($atts['id'])){
		return __('"id" attribute missing when calling shortcode', 'reservation-facile');
	}
	if ((isset($atts['lang'])) && (trim($atts['lang']) != '')){
		global $l10n;
		if (isset($l10n['reservation-facile'])){
			unset($l10n['reservation-facile']);
		}
		global $wp_scripts;
		for($i=0;$i<sizeof($wp_scripts->done);$i++){
			if ($wp_scripts->done[$i] == 'reservation-facile-front-js'){
				unset($wp_scripts->done[$i]);
			}
		}
		
		load_textdomain( 'reservation-facile', dirname( __FILE__ )  .'/languages/reservation-facile-'.$atts['lang'].'.mo' );
		rf_load_front_js();
	}
	$joursSemaine = [__('Sunday', 'reservation-facile'),__('Monday', 'reservation-facile'),__('Tuesday', 'reservation-facile'),__('Wednesday', 'reservation-facile'),__('Thursday', 'reservation-facile'),__('Friday', 'reservation-facile'),__('Saturday', 'reservation-facile')];
	if ($rf_act == 'rf_previsualisation_reservation'){
		if ($atts['id'] == $rf_idSpace){
			$errors = json_decode(rf_checkBookingFormPost($rf_idSpace));
			$previsualisation = $errors->previsualisation;
			if ($previsualisation != ''){
				return $result.$previsualisation;
			}
			$msg = $errors->msg;
			$inputsError = $errors->inputsError;	
		}
	}	
	$rf_idSpace = $atts['id'];
	$row = $wpdb->get_row($wpdb->prepare("SELECT emp.*, lieu.nom AS localisation FROM {$wpdb->prefix}rf_spaces emp LEFT JOIN {$wpdb->prefix}rf_locations lieu ON emp.id_lieu = lieu.id WHERE emp.id = %s", $rf_idSpace));
	if ($row){
		$result .= '<div id="rf_mainDiv"><noscript>'.__('This page require Javascript to be enabled.','reservation-facile').'</noscript>';
		if ($msg != ''){
			$result .= '<div class="rf_errorMsg">'.$msg.'</div><br>';
		}
		$result2 = '<b>'.rf_removeslashes($row->label).' - '.rf_removeslashes($row->localisation).'</b>';	
		$info = false;
		if ((($row->info_date_debut_reservation == 1)&&($row->date_debut_reservation > date('Y-m-d')))||($row->info_date_debut_reservation == 2)){
			$result2 .= '<div class="rf_formRow"><div>'.__('Available on', 'reservation-facile').': </div><div><input type="date" disabled value="'.$row->date_debut_reservation.'"></div></div>';
		}
		if ((($row->info_date_fin_reservation == 1)&&($row->date_fin_reservation > date('Y-m-d')))||($row->info_date_fin_reservation == 2)){
			$result2 .= '<div class="rf_formRow"><div>'.__('Until', 'reservation-facile').': </div><div><input type="date" disabled value="'.$row->date_fin_reservation.'"></div></div>';
		}
		if ((($row->info_prix_de_la_place == 1)&&($row->prix_de_la_place > 0))||($row->info_prix_de_la_place == 2)){
			$result2 .= '<div class="rf_formRow"><div>'.__('Price of the place', 'reservation-facile').': </div><div><span id="rf_spacePrice">'.($row->prix_de_la_place+0).$row->devise.'</span>'.
				rf_displayPrice($row->prix_de_la_place, $row->devise, 'rf_spacePrice').'</div></div>';
		}
		if ((($row->info_acompte_prix == 1)&&($row->acompte_prix > 0))||($row->info_acompte_prix == 2)){
			$result2 .= '<div class="rf_formRow"><div>'.__('Deposit requested (amount)', 'reservation-facile').': </div><div><span id="rf_depositPrice">'.($row->acompte_prix+0).$row->devise.'</span>'.
				rf_displayPrice($row->acompte_prix, $row->devise, 'rf_depositPrice').'</div></div>';
		}
		if ((($row->info_acompte_pourcentage == 1)&&($row->acompte_pourcentage > 0))||($row->info_acompte_pourcentage == 2)){
			$result2 .= '<div class="rf_formRow"><div>'.__('Deposit requested (percentage)', 'reservation-facile').': </div><div>'.($row->acompte_pourcentage+0).'%</div></div>';
		}
		if ((($row->info_timeUnit == 1)&&($row->timeUnit > 0))||($row->info_timeUnit == 2)){
			$result2 .= '<div class="rf_formRow"><div>'.__('Time unit', 'reservation-facile').': </div><div>'.rf_heure2duree($row->timeUnit,false).'</div></div>';
		}
		if ((($row->info_minBookingDuration == 1)&&($row->minBookingDuration > 0))||($row->info_minBookingDuration == 2)){
			$result2 .= '<div class="rf_formRow"><div>'.__('Min. booking duration', 'reservation-facile').': </div><div>'.rf_heure2duree($row->minBookingDuration,false).'</div></div>';
		}
		if ((($row->info_tps_reservation_max_heure == 1)&&($row->tps_reservation_max_heure > 0))||($row->info_tps_reservation_max_heure == 2)){
			$result2 .= '<div class="rf_formRow"><div>'.__('Max. booking duration', 'reservation-facile').': </div><div>'.rf_heure2duree($row->tps_reservation_max_heure,false).'</div></div>';
		}
		$result2 .= '<script>var rf_openingtimes = '.stripslashes($row->openingtimes).'</script>';
		$tabOpeningTimes = json_decode(stripslashes($row->openingtimes));
		$minHourArrival = '23:59';
		$maxHourArrival = '00:00';
		$minHourDeparture = '23:59';
		$maxHourDeparture = '00:00';
		foreach($tabOpeningTimes as $day => $OT){
			for($type = 0; $type < 3; $type++){
				$open = false;
				foreach($OT[$type] as $t => $times){
					if ($t == 0){
						if ($times == "1"){
							$open = true;
						}
					}else{
						if ($type < 2){
							if ($times[0] < $minHourArrival){
								$minHourArrival = $times[0];
							}
							if ($times[1] > $maxHourArrival){
								$maxHourArrival = $times[1];
							}
						}
						if ($type != 1){
							if ($times[0] < $minHourDeparture){
								$minHourDeparture = $times[0];
							}
							if ($times[1] > $maxHourDeparture){
								$maxHourDeparture = $times[1];
							}
						}
						
					}
				}
			}
		}
		if ($minHourArrival == '23:59'){$minHourArrival = '00:00';}
		if ($minHourDeparture == '23:59'){$minHourDeparture = '00:00';}
		if ((($row->info_description == 1)&&($row->description != ''))||($row->info_description == 2)){
			$result2 .= '<div class=""><br><br>'.nl2br(stripcslashes($row->description)).'</div>';
		}
		$tabDayprice = explode('--o--',$row->dayprice);
		if (strlen($tabDayprice[0]) > 0){
			$result2 .= '<div id="rf_specialprices"><span></span><h2>'.__('Special prices','reservation-facile').'</h2>';
			
			for($i = 0; $i < strlen($tabDayprice[0]); $i++){
				$numDay = substr($tabDayprice[0],$i,1);
				$price = explode(';',$tabDayprice[1])[$numDay]+0;
				$result2 .= '<div class="rf_formRow"><div>'.$joursSemaine[$numDay].'</div><div><span id="rf_dayprice'.$numDay.'">'.$price.$row->devise.'</span>'.rf_displayPrice($price, $row->devise, 'rf_dayprice'.$numDay).'</div></div>';
			}
			$result2 .= '</div>';
		}	
		if ($row->periodesprices != ''){
			$result2 .= '<div id="rf_specialperiods"><span></span><h2>'.__('Special periods','reservation-facile').'</h2>';
			$tabPeriod = explode('--o--',$row->periodesprices);
			foreach($tabPeriod as $index => $period){
				if ($period != ''){
					$pp = explode(';',$period);
					if ($pp[0] >= date('Y-m-d H:i')){
						$pp[0] = explode(' ',$pp[0]);
						$pp[1] = explode(' ',$pp[1]);
					
						$result2 .= '<div class="rf_periodList rf_formRow" id="rf_period'.$index.'">
								<div>
									<div>
										<span id="rf_periodeStart'.$index.'">'.$pp[0][0].' '.$pp[0][1].'</span>'.
										rf_displayDate($pp[0][0].' '.$pp[0][1],'rf_periodeStart'.$index).'
									</div>
									<div>
										<span id="rf_periodeFinish'.$index.'">'.$pp[1][0].' '.$pp[1][1].'</span>'.
										rf_displayDate($pp[1][0].' '.$pp[1][1],'rf_periodeFinish'.$index).'
									</div>
								</div>
								<div>
									<div><span id="rf_periodesprice'.$index.'">' . $pp[2].$row->devise . '</span></div>'.
									rf_displayPrice($pp[2], $row->devise, 'rf_periodesprice'.$index).'
								</div>
							</div>';
					}
				}
			}
			$result2 .= '</div>';
		}
		if ($result2 != ''){
			$result .= '<div><h2>'.__('Information', 'reservation-facile').'</h2><div id="rf_information_front">'.$result2.'</div></div>';
		}	
		if ($row->info_calendrier > 0){
			$result .= '<br><div><span></span><h2>'.__('Availability calendar', 'reservation-facile').'</h2>';
			$result .= '
				<div id="rf_navBar">
					<form name="when">';
			$result .= rf_get_wp_nonce_field('chargeCalendrier','rf_mainCalendar'); 
			$result .= '<div class="rf_calendarRow">
							<div class="rf_arrowSelectMonth"><span class="rf_norotate_arrow_calendar">
										<img class="emoji" src="'.plugins_url( 'img/arrow.svg', __FILE__ ).'" onClick="rf_calendarSkip(\'-\')">
								</span>
							</div>
							<div class="rf_selectMonth"><select name="month" onChange="rf_calendarOnMonth()">';
								   $result .= rf_getMonthsOption(date('Y'),date('m'));
								   $result .= '</select>
							</div>
							<div class="rf_arrowSelectMonth"><span class="rf_rotate_arrow_calendar">
									<img class="emoji" src="'.plugins_url( 'img/arrow.svg', __FILE__ ).'" onClick="rf_calendarSkip(\'+\')">
								</span>
							</div>
						</div>';
			$result .= '</form>
				</div>
				<div id="rf_calendar"></div>
			</div><script>document.addEventListener("DOMContentLoaded",function(e){rf_calendarDefaults();})</script>';	
		}
		$result .= '<br><div>';
		$result .= '<h2>'.__('Book', 'reservation-facile').'</h2><form name="rf_reservation_form" action="" method="post" onsubmit="return rf_checkForm(\''.($row->timeUnit+0).'\',\''.($row->minBookingDuration+0).'\',\''.($row->tps_reservation_max_heure+0).'\',\''.$row->date_debut_reservation.'\',\''.$row->date_fin_reservation.'\',\''.$row->user_minutes_interval.'\',\''.trim(str_replace(array("\n", "\r"), '', str_replace('"',"",$row->exceptionalclosure))).'\')"><input type="hidden" name="rf_act" value="rf_previsualisation_reservation"><input type="hidden" id="rf_idSpace" name="rf_idSpace" value="'.$rf_idSpace.'">';
		$result .= rf_get_wp_nonce_field('rf_previsualisation_reservation'); 
		(isset($SAFE_DATA["nb_de_place"]))? $nb_de_place = $SAFE_DATA["nb_de_place"]: $nb_de_place = "";
		(isset($SAFE_DATA["form_date_debut"]))? $form_date_debut = $SAFE_DATA["form_date_debut"]: $form_date_debut = "";
		(isset($SAFE_DATA["form_heure_debut"]))? $form_heure_debut = $SAFE_DATA["form_heure_debut"]: $form_heure_debut = "";
		(isset($SAFE_DATA["form_date_fin"]))? $form_date_fin = $SAFE_DATA["form_date_fin"]: $form_date_fin = "";
		(isset($SAFE_DATA["form_heure_fin"]))? $form_heure_fin = $SAFE_DATA["form_heure_fin"]: $form_heure_fin = "";
		(isset($SAFE_DATA["form_personnes"]))? $form_personnes = $SAFE_DATA["form_personnes"]: $form_personnes = "";
		(isset($SAFE_DATA["form_nom"]))? $form_nom = $SAFE_DATA["form_nom"]: $form_nom = "";
		(isset($SAFE_DATA["form_prenom"]))? $form_prenom = $SAFE_DATA["form_prenom"]: $form_prenom = "";
		(isset($SAFE_DATA["form_adresse"]))? $form_adresse = $SAFE_DATA["form_adresse"]: $form_adresse = "";
		(isset($SAFE_DATA["form_code_postal"]))? $form_code_postal = $SAFE_DATA["form_code_postal"]: $form_code_postal = "";
		(isset($SAFE_DATA["form_ville"]))? $form_ville = $SAFE_DATA["form_ville"]: $form_ville = "";
		(isset($SAFE_DATA["form_pays"]))? $form_pays = $SAFE_DATA["form_pays"]: $form_pays = "";
		(isset($SAFE_DATA["form_email"]))? $form_email = $SAFE_DATA["form_email"]: $form_email = "";
		(isset($SAFE_DATA["form_telephone"]))? $form_telephone = $SAFE_DATA["form_telephone"]: $form_telephone = "";
		(isset($SAFE_DATA["form_remarques"]))? $form_remarques = $SAFE_DATA["form_remarques"]: $form_remarques = "";
		(isset($SAFE_DATA["form_coupon"]))? $form_coupon = rf_removeslashes($SAFE_DATA["form_coupon"]): $form_coupon = "";
		(isset($SAFE_DATA["form_reduction"]))? $form_reduction = rf_removeslashes($SAFE_DATA["form_reduction"]): $form_reduction = "";
		(isset($SAFE_DATA["form_booking_duration"]))? $form_booking_duration = $SAFE_DATA["form_booking_duration"]: $form_booking_duration = "";
		$result .= '<span><span class="rf_required">*</span> '.__('indicates required fields', 'reservation-facile').'</span>';
		$result .= '<div id="rf_form_front">';
		if ($row->nb_de_place == 1){
			$result .= '<input type="hidden" name="nb_de_place" id="rf_nb_de_place" value="'.($row->nb_de_place+0).'">';
		}else if ($row->form_nb_de_place > 0){$result .= '<div class="rf_formRow"><div>'.(($row->form_nb_de_place == '2')?'<span class="rf_required">*</span>':'&nbsp;').' '.__('Number of places', 'reservation-facile').': </div><div><input type="number" min="0" max="'.$row->nb_de_place.'" step="any" value="'.$nb_de_place.'" name="nb_de_place" id="rf_nb_de_place" '.(($row->form_nb_de_place == '2')?'required':'').'></div></div>';}
		
		if ($row->form_date_debut > 0){
			$result .= '<div class="rf_formRow"><div>'.(($row->form_date_debut == '2')?'<span class="rf_required">*</span>':'&nbsp;').' '.__('Arrival date', 'reservation-facile').': </div><div><input type="date" value="'.$form_date_debut.'" name="form_date_debut" id="rf_form_date_debut" autocomplete="off" '.(($row->form_date_debut == '2')?'required':'').'></div></div>';
		}		
		if ($row->form_heure_debut > 0){
			$result .= '<div class="rf_formRow"><div>'.(($row->form_heure_debut == '2')?'<span class="rf_required">*</span>':'&nbsp;').' '.__('Arrival time', 'reservation-facile').': </div><div>
			<input type="time" value="'.$form_heure_debut.'" name="form_heure_debut" id="rf_form_heure_debut" '.(($row->form_heure_debut == '-1')?'required':'').'>
			<select id="rf_form_heure_debutp1" disabled title="'.__('Please select the arrival date first', 'reservation-facile').'">'.rf_formListHours(substr($form_heure_debut,0,2),substr($minHourArrival,0,2),substr($maxHourArrival,0,2)).'</select> : 
			<select id="rf_form_heure_debutp2" disabled title="'.__('Please select the arrival date first', 'reservation-facile').'">'.rf_formListMinutes(substr($form_heure_debut,3,2),$row->user_minutes_interval).'</select>
			</div></div>';
		}
		if (($row->tps_reservation_max_heure > 0) && ($row->tps_reservation_max_heure <= 24) && ($row->form_date_debut > 0) && ($row->form_heure_debut > 0)){
			$result .= '<div class="rf_formRow"><div><span class="rf_required">*</span> ' .__('Duration', 'reservation-facile').': </div><div>';
			$result .= '<input type="hidden" value="'.$form_date_fin.'" name="form_date_fin" id="rf_form_date_fin">';
			$result .= '<input type="hidden" value="'.$form_heure_fin.'" name="form_heure_fin" id="rf_form_heure_fin">';
			$result .= '<input type="hidden" name="form_heure_finp1" id="rf_form_heure_finp1">';
			$result .= '<input type="hidden" name="form_heure_finp2" id="rf_form_heure_finp2">';
			$result .= '<select id="rf_booking_duration" name="form_booking_duration">'.rf_formListDuration(max($row->timeUnit*60,$form_booking_duration),$row->timeUnit*60,$row->tps_reservation_max_heure*60,$row->user_minutes_interval).'</select>';
			$result .= '</div></div>';
		}else{
			if ($row->form_date_fin > 0){
				$result .= '<div class="rf_formRow"><div>'.(($row->form_date_fin == '2')?'<span class="rf_required">*</span>':'&nbsp;').' '.__('Departure date', 'reservation-facile').': </div><div><input type="date" value="'.$form_date_fin.'" name="form_date_fin" id="rf_form_date_fin" autocomplete="off" '.(($row->form_date_fin == '2')?'required':'').'></div></div>';
			}
			if ($row->form_heure_fin > 0){
				$result .= '<div class="rf_formRow"><div>'.(($row->form_heure_fin == '2')?'<span class="rf_required">*</span>':'&nbsp;').' '.__('Departure time', 'reservation-facile').': </div><div>
				<input type="time" value="'.$form_heure_fin.'" name="form_heure_fin" id="rf_form_heure_fin" '.(($row->form_heure_fin == '-1')?'required':'').'>
				<select id="rf_form_heure_finp1" disabled title="'.__('Please select the departure date first', 'reservation-facile').'">'.rf_formListHours(substr($form_heure_fin,0,2),substr($minHourDeparture,0,2),substr($maxHourDeparture,0,2)).'</select> : 
				<select id="rf_form_heure_finp2" disabled title="'.__('Please select the departure date first', 'reservation-facile').'">'.rf_formListMinutes(substr($form_heure_fin,3,2),$row->user_minutes_interval).'</select>
				</div></div>';
			}
		}
		if ($row->form_personnes > 0){$result .= '<div class="rf_formRow"><div>'.(($row->form_personnes == '2')?'<span class="rf_required">*</span>':'&nbsp;').' '.__('Number of people', 'reservation-facile').': </div><div><input type="number" min="0" value="'.$form_personnes.'" name="form_personnes" id="rf_form_personnes" '.(($row->form_personnes == '2')?'required':'').'></div></div>';}
		if ($row->form_nom > 0){$result .= '<div class="rf_formRow"><div>'.(($row->form_nom == '2')?'<span class="rf_required">*</span>':'&nbsp;').' '.__('Last name', 'reservation-facile').': </div><div><input type="text" value="'.rf_removeslashes($form_nom).'" name="form_nom" id="rf_form_nom" '.(($row->form_nom == '2')?'required':'').'></div></div>';}
		if ($row->form_prenom > 0){$result .= '<div class="rf_formRow"><div>'.(($row->form_prenom == '2')?'<span class="rf_required">*</span>':'&nbsp;').' '.__('First name', 'reservation-facile').': </div><div><input type="text" value="'.rf_removeslashes($form_prenom).'" name="form_prenom" id="rf_form_prenom" '.(($row->form_prenom == '2')?'required':'').'></div></div>';}
		if ($row->form_adresse > 0){$result .= '<div class="rf_formRow"><div>'.(($row->form_adresse == '2')?'<span class="rf_required">*</span>':'&nbsp;').' '.__('Address', 'reservation-facile').': </div><div><input type="text" value="'.rf_removeslashes($form_adresse).'" name="form_adresse" id="rf_form_adresse" '.(($row->form_adresse == '2')?'required':'').'></div></div>';}
		if ($row->form_code_postal > 0){$result .= '<div class="rf_formRow"><div>'.(($row->form_code_postal == '2')?'<span class="rf_required">*</span>':'&nbsp;').' '.__('Zip code', 'reservation-facile').': </div><div><input type="text" value="'.rf_removeslashes($form_code_postal).'" name="form_code_postal" id="rf_form_code_postal" '.(($row->form_code_postal == '2')?'required':'').'></div></div>';}
		if ($row->form_ville > 0){$result .= '<div class="rf_formRow"><div>'.(($row->form_ville == '2')?'<span class="rf_required">*</span>':'&nbsp;').' '.__('City', 'reservation-facile').': </div><div><input type="text" value="'.rf_removeslashes($form_ville).'" name="form_ville" id="rf_form_ville" '.(($row->form_ville == '2')?'required':'').'></div></div>';}
		if ($row->form_pays > 0){$result .= '<div class="rf_formRow"><div>'.(($row->form_pays == '2')?'<span class="rf_required">*</span>':'&nbsp;').' '.__('Country', 'reservation-facile').': </div><div><input type="text" value="'.rf_removeslashes($form_pays).'" name="form_pays" id="rf_form_pays" '.(($row->form_pays == '2')?'required':'').'></div></div>';}
		if ($row->form_email > 0){$result .= '<div class="rf_formRow"><div>'.(($row->form_email == '2')?'<span class="rf_required">*</span>':'&nbsp;').' '.__('Email', 'reservation-facile').': </div><div><input type="email" value="'.$form_email.'" name="form_email" id="rf_form_email" '.(($row->form_email == '2')?'required':'').'></div></div>';}
		if ($row->form_telephone > 0){$result .= '<div class="rf_formRow"><div>'.(($row->form_telephone == '2')?'<span class="rf_required">*</span>':'&nbsp;').' '.__('Tel.', 'reservation-facile').': </div><div><input type="text" value="'.rf_removeslashes($form_telephone).'" name="form_telephone" id="rf_form_telephone" '.(($row->form_telephone == '2')?'required':'').'></div></div>';}
		if ($row->form_remarques > 0){$result .= '<div class="rf_formRow"><div>'.(($row->form_remarques == '2')?'<span class="rf_required">*</span>':'&nbsp;').' '.__('Comments', 'reservation-facile').': </div><div><textarea name="form_remarques" id="rf_form_remarques" '.(($row->form_remarques == '2')?'required':'').'>'.rf_removeslashes($form_remarques).'</textarea></div></div>';}
		
		$result .= rf_get_wp_nonce_field('getAvailableOptions','rf_getAvailableOptions'); 
		$result .= '<div id="rf_displayOptions">'. __('Please select your arrival date to see the options available','reservation-facile') .'</div>';
		$result .= '<div id="rf_optionsBackup">';
		foreach ($SAFE_DATA as $key => $data){
			if (substr($key, 0,12) == 'form_option_'){
				$result .= substr($key,12) . '++' . $data . '**';
			}
		}
		$result .= '</div>';
		if (isset($SAFE_DATA['form_coupon'])){
			$result .= '<div id="rf_couponBackup">'.$SAFE_DATA['form_coupon'].'</div>';
		}
		if (isset($SAFE_DATA['form_reduction'])){
			$result .= '<div id="rf_reductionBackup">'.$SAFE_DATA['form_reduction'].'</div>';
		}
		
		/*
		$today = date('Y-m-d');
		$results = $wpdb->get_results($wpdb->prepare("SELECT mp.id, mp.type, mp.quantite_initiale, mp.label, mp.montant, mp.pourcentage, mp.description, mp.details_texte, mp.code FROM {$wpdb->prefix}rf_modifyprice_spaces mpe LEFT JOIN {$wpdb->prefix}rf_modifyprice mp ON mpe.id_modificationprix = mp.id WHERE mpe.rf_idSpace=%s AND mp.date_debut <= %s AND mp.date_fin >= %s AND (mp.quantite > 0 OR mp.quantite_initiale = 0)", $rf_idSpace,$today,$today));
		if (sizeof($results)>0){
			$resultCoupon = '';
			$resultReduction = '';
			$resultOption = '';
			$convert = array("coupon" => __('coupon', 'reservation-facile'),"reduction" => __('discount', 'reservation-facile'),"taxe" => __('tax', 'reservation-facile'));
			$couponsAff = false;
			$reductionsAff = false;
			foreach($results as $mp){
				if (($mp->type == "coupon")&&(!$couponsAff)){
					$resultCoupon .= '<div class="rf_formRow"><div>&nbsp;'.ucfirst($convert[$mp->type]).' ('.__('Code', 'reservation-facile').'): </div><div><input type="text" name="form_coupon" value="'.$form_coupon.'"></div></div>';
					$couponsAff = true;
				}
				if (($mp->type == "reduction")&&(!$reductionsAff)){
					$resultReduction .= '<div class="rf_formRow"><div>&nbsp;'.ucfirst($convert[$mp->type]).' ('.__('Code', 'reservation-facile').'): </div><div><input type="text" name="form_reduction" value="'.$form_reduction.'"></div></div>';
					$reductionsAff = true;
				}
				if ($mp->type == "option"){
					if (($mp->code != 'userchoice') || ($mp->quantite_initiale > 0)){
						$resultOption .= '<div class="rf_formRow"><div><b>'.ucfirst(rf_removeslashes($mp->label)).'</b>:</div><div><div>';
						$displaySelect = false;
						if (($mp->code == 'userchoice') && ($mp->quantite_initiale > 0)){
							$displaySelect = true;
							$resultOption .= '<select name="form_option_'.$mp->id.'">';
							(isset($SAFE_DATA["form_option_".$mp->id]))? $form_option = $SAFE_DATA["form_option_".$mp->id]: $form_option = "";
							for($i = 0; $i <= $mp->quantite_initiale; $i++){
								($form_option == $i)? $selected = 'selected' : $selected = '';
								$resultOption .= '<option value="'.$i.'" '.$selected.'>'.$i.'</option>';
							}
							$resultOption .= '</select>';
						}
						if ($mp->montant != 0){
							if ($displaySelect){$resultOption .= ' x ';}
							$resultOption .= '<span id="rf_option'.$mp->id.'">'.($mp->montant+0) . ' '.$row->devise.'</span>';
							$resultOption .= rf_displayPrice($mp->montant, $row->devise, 'rf_option'.$mp->id);
						}
						if ($mp->pourcentage != 0){
							if ($displaySelect){$resultOption .= ' x ';}
							$resultOption .= ($mp->pourcentage+0).'%'; 
						}
						
						if ($mp->code != 'userchoice'){
							if ($mp->code  == 'oneperhour'){ $resultOption .= ' (' . __('Per booked hour','reservation-facile') . ')'; }
							if ($mp->code  == 'oneperday'){ $resultOption .= ' (' . __('Per booked day','reservation-facile') . ')'; }
							if ($mp->code  == 'onepernight'){ $resultOption .= ' (' . __('Per booked night','reservation-facile') . ')'; }
							if ($mp->code  == 'oneperweek'){ $resultOption .= ' (' . __('Per booked week','reservation-facile') . ')'; }
							if ($mp->code  == 'onepermonth'){ $resultOption .= ' (' . __('Per booked month','reservation-facile') . ')'; }
						}
						
						$displayDetails = explode("<br />",nl2br(rf_removeslashes($mp->details_texte)));
						if ((sizeof($displayDetails) > 0) && ($displayDetails[0] != '')){
							$resultOption .= '<br><span><select name="form_option_details_texte_'.$mp->id.'">';
							(isset($SAFE_DATA["form_option_details_texte_".$mp->id]))? $form_option_details_texte = $SAFE_DATA["form_option_details_texte_".$mp->id]: $form_option_details_texte = "";
							$resultOption .= '<option>'.__('Your choice', 'reservation-facile').'</option>';
							for($i = 0; $i < sizeof($displayDetails); $i++){
								if (trim($displayDetails[$i]) != ''){
									($form_option_details_texte == $displayDetails[$i])? $selected = 'selected' : $selected = '';
									$resultOption .= '<option value="'.$displayDetails[$i].'" '.$selected.'>'.$displayDetails[$i].'</option>';
								}
							}
							$resultOption .= '</select>';
							$resultOption .= '</span>';
						}
						if ($mp->description != ''){
							$resultOption .= '<div class="rf_formRow">' . nl2br(ucfirst(rf_removeslashes($mp->description))) . '</div>';
						}
						$resultOption .= '</div><br>';
						$resultOption .= '</div></div>';
					}
				}
			}
			$result .= $resultCoupon . $resultReduction;
			if ($resultOption != ''){
				$result .= $resultOption;
			}
		}
		*/
		
		
		
		
		
		
		
		
		
		if ($row->lien_CGU != ''){$result .= '<div><div><span class="rf_required">*</span> <input type="checkbox" name="lien_CGU" required><a href="'.$row->lien_CGU.'">'.__('Accept the Terms', 'reservation-facile').'</a></div></div>';}
		$result .= '</div><br><br><input type="submit" value="'.__('Book', 'reservation-facile').'"></form></div>';
		$result .= '</div>';
		$result .= "<script>document.addEventListener('DOMContentLoaded',function(e){rf_show_inputs_error('".json_encode($inputsError)."');})</script>";
	}else{
		$result = __('We are sorry, this location is no longer available.', 'reservation-facile');
	}

	return $result;
}
