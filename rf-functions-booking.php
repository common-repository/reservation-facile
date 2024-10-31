<?php
defined( 'ABSPATH' ) or die();
function rf_checkBookingFormPost($rf_idSpace, $majBdd=false, $beforeTodayOk=false, $forEmail=false){
	global $wpdb;
	global $SAFE_DATA;
	$msg = '';
	$warning = '';
	$inputsError = array();
	$id_reservation = '';
	$ok = true;
	if ($SAFE_DATA == []){
		$isDataFormSafe = rf_secureDataForm();
		if (!$isDataFormSafe[0]){
			$ok = false;
			$msg .= ' '. $isDataFormSafe[1];
			error_log('DEBUG19:------------------- '. $isDataFormSafe[1]);
			return json_encode(array('ok'=> $ok, 'msg' => $msg));
		}else{
			$SAFE_DATA = $isDataFormSafe[2];
		}
	}
	(!isset($SAFE_DATA['nb_de_place']))? $SAFE_DATA['nb_de_place'] = 1 : '';
	($SAFE_DATA['nb_de_place'] == 0)? $SAFE_DATA['nb_de_place'] = 1 : '';	
	$rowEmp = $wpdb->get_row($wpdb->prepare("SELECT emp.*, lieu.nom AS localisation FROM {$wpdb->prefix}rf_spaces emp LEFT JOIN {$wpdb->prefix}rf_locations lieu ON emp.id_lieu = lieu.id WHERE emp.id = %s", $rf_idSpace));
	$rf_OT = json_decode(stripslashes($rowEmp->openingtimes));
	if ((isset($SAFE_DATA["form_heure_debut"]))&&(rf_isNotEmpty($SAFE_DATA["form_heure_debut"]))){
		if (!rf_checkDateInOT($rf_OT,$SAFE_DATA["form_date_debut"]." ".$SAFE_DATA["form_heure_debut"],1,$rowEmp->exceptionalclosure)){
			$msg .= __('This arrival time is not allowed', 'reservation-facile').": " . $SAFE_DATA["form_date_debut"]." ".$SAFE_DATA["form_heure_debut"] . "<br>";
			$ok = false;
			$inputsError[] = "form_date_debut";
		}
	}else{
		$SAFE_DATA["form_heure_debut"] = rf_getReferenceTimeInOT($rf_OT,$SAFE_DATA["form_date_debut"]." 12:00",1,1,"",1,$rowEmp->exceptionalclosure);
	}

	if ((isset($SAFE_DATA["form_heure_fin"]))&&(rf_isNotEmpty($SAFE_DATA["form_heure_fin"]))){
		$dd = date('Y-m-d H:i:s', strtotime($SAFE_DATA["form_date_fin"] . ' ' . $SAFE_DATA["form_heure_fin"] . ' -1 second'));
		if ((!rf_checkDateInOT($rf_OT,$dd,2,$rowEmp->exceptionalclosure)) && (!rf_checkDateInOT($rf_OT,$SAFE_DATA["form_date_fin"] . ' ' . $SAFE_DATA["form_heure_fin"],2,$rowEmp->exceptionalclosure))){
			$msg .= __('This departure time is not allowed', 'reservation-facile').": " . $SAFE_DATA["form_date_fin"]." ".$SAFE_DATA["form_heure_fin"] . "<br>";
			$ok = false;
			$inputsError[] = "form_date_fin";
		}
	}else{
		$SAFE_DATA["form_heure_fin"] = rf_getReferenceTimeInOT($rf_OT,$SAFE_DATA["form_date_fin"]." 00:00",2,$rowEmp->timeUnit,$SAFE_DATA["form_date_debut"]." ".$SAFE_DATA["form_heure_debut"],$rowEmp->user_minutes_interval,$rowEmp->exceptionalclosure);
	}
	$ts_date_debut = rf_getTimeStamp($SAFE_DATA["form_date_debut"]." ".$SAFE_DATA["form_heure_debut"]);
	$form_date_debut = date("Y-m-d H:i:s", $ts_date_debut);
	$ts_date_fin = rf_getTimeStamp($SAFE_DATA["form_date_fin"]." ".$SAFE_DATA["form_heure_fin"]);
	$form_date_fin = date("Y-m-d H:i:s", $ts_date_fin);
	if (isset($form_date_debut)){
		if (strtotime($form_date_debut) < strtotime($rowEmp->date_debut_reservation)){
			$msg .= __('Booking only possible from', 'reservation-facile')." " . $rowEmp->date_debut_reservation . "<br>";
			$ok = false;
			$inputsError[] = "form_date_debut";
		}
		if (strtotime($form_date_debut) > strtotime($rowEmp->date_fin_reservation)){
			$msg .= __('Booking only possible until', 'reservation-facile')." " . $rowEmp->date_fin_reservation . "<br>";
			$ok = false;
			$inputsError[] = "form_date_debut";
		}
		if ($form_date_debut < date("Y-m-d H:i:s")){
			if ($beforeTodayOk){
				$warning .= __('Booking not possible before today.', 'reservation-facile')."<br>";
			}else{
				$msg .= __('Booking not possible before today.', 'reservation-facile')."<br>";
				$ok = false;
			}
			$inputsError[] = "form_date_debut";
		}
	}
	if (isset($form_date_fin)){
		if (strtotime($form_date_fin) < strtotime($rowEmp->date_debut_reservation)){
			$msg .= __('Booking only possible from', 'reservation-facile')." " . $rowEmp->date_debut_reservation . "<br>";
			$ok = false;
			$inputsError[] = "form_date_fin";
		}
		if (strtotime($form_date_fin) > strtotime($rowEmp->date_fin_reservation)){
			$msg .= __('Booking only possible until', 'reservation-facile')." " . $rowEmp->date_fin_reservation . "<br>";
			$ok = false;
			$inputsError[] = "form_date_fin";
		}
		if ($form_date_fin < date("Y-m-d H:i:s")){
			if ($beforeTodayOk){
				$warning .= __('Booking not possible before today.', 'reservation-facile')."<br>";
			}else{
				$msg .= __('Booking not possible before today.', 'reservation-facile')."<br>";
				$ok = false;
			}
			$inputsError[] = "form_date_fin";
		}
	}
	$duree_reservation_heures = 0;
	$joursSemaine = [__('Sunday', 'reservation-facile'),__('Monday', 'reservation-facile'),__('Tuesday', 'reservation-facile'),__('Wednesday', 'reservation-facile'),__('Thursday', 'reservation-facile'),__('Friday', 'reservation-facile'),__('Saturday', 'reservation-facile')];
	if (isset($form_date_debut) && isset($form_date_fin)){
		$dateDeb = new DateTime($form_date_debut);
		$dateFin = new DateTime($form_date_fin);		
		$duree_reservation_heures = round(($dateFin->format('U') - $dateDeb->format('U')) / 3600,3);
		if (($rowEmp->minBookingDuration > 0)&&($duree_reservation_heures < $rowEmp->minBookingDuration)){
			$ok = false;
			$msg .= __('Duration of the booking insufficient. Minimum duration', 'reservation-facile')." " . ($rowEmp->timeUnit+0) . __('hrs', 'reservation-facile') . "<br>";
			$inputsError[] = "form_date_debut";
			$inputsError[] = "form_date_fin";
		}
		if (($rowEmp->tps_reservation_max_heure > 0)&&($duree_reservation_heures > $rowEmp->tps_reservation_max_heure)){
			$ok = false;
			$msg .= __('Duration of the reservation is too long. Maximum duration', 'reservation-facile')." " . ($rowEmp->tps_reservation_max_heure+0) . __('hrs', 'reservation-facile') . "<br>";
			$inputsError[] = "form_date_debut";
			$inputsError[] = "form_date_fin";
		}
		if ($duree_reservation_heures < 0){
			$ok = false;
			$msg .= __('The arrival date is after the departure date', 'reservation-facile') . "<br>";
			$inputsError[] = "form_date_debut";
			$inputsError[] = "form_date_fin";
		}
		$dateParcours = $form_date_debut;
		$ok2 = true;
		$spaceInterval = $rowEmp->user_minutes_interval;
		if ($spaceInterval < 1){$spaceInterval = 1;}
		while (($dateParcours < $form_date_fin) && $ok2){
			$ts_date_parcours = mktime((int)substr($dateParcours,11,2),(int)substr($dateParcours,14,2),0,(int)substr($dateParcours,5,2),(int)substr($dateParcours,8,2),(int)substr($dateParcours,0,4));
			if (!rf_checkDateInOT($rf_OT,$dateParcours,0,"")){
				$ok2 = false;
				$ok = false;
				$msg .= __('Booking is not possible on the whole day of', 'reservation-facile')." " . $joursSemaine[getdate($ts_date_parcours)["wday"]] . "<br>";
				$inputsError[] = "form_date_debut";
				$inputsError[] = "form_date_fin";
			}
			$dateParcours = date("Y-m-d H:i:s",$ts_date_parcours + $spaceInterval * 60);
		}
	}
	if ((isset($form_date_debut)) && (!isset($form_date_fin))){
		if (!rf_checkDateInOT($rf_OT,$SAFE_DATA["form_date_debut"]." ".$SAFE_DATA["form_heure_debut"],0,"")){			
			$ok = false;
			$msg .= __('Booking is not possible on the whole day of', 'reservation-facile')." " . $joursSemaine[getdate($ts_date_debut)["wday"]] . "<br>";
			$inputsError[] = "form_date_debut";
			$inputsError[] = "form_date_fin";
		}
	}
	if (isset($form_date_debut)){
		if (!rf_checkDateInOT($rf_OT,$SAFE_DATA["form_date_debut"]." ".$SAFE_DATA["form_heure_debut"],1,"")){	
			$ok = false;
			$msg .= __('Arrival is not possible on', 'reservation-facile')." " . $joursSemaine[getdate($ts_date_debut)["wday"]] . "<br>";
			$inputsError[] = "form_date_debut";
		}
	}
	if (isset($form_date_fin)){
		$dd = date('Y-m-d H:i:s', strtotime($SAFE_DATA["form_date_fin"] . ' ' . $SAFE_DATA["form_heure_fin"] . ' -1 second'));
		if ((!rf_checkDateInOT($rf_OT,$dd,2,"")) && (!rf_checkDateInOT($rf_OT,$SAFE_DATA["form_date_fin"] . ' ' . $SAFE_DATA["form_heure_fin"],2,""))){
			$ok = false;
			$msg .= __('Departure is not possible on', 'reservation-facile')." " . $joursSemaine[getdate($ts_date_fin)["wday"]] . "<br>";
			$inputsError[] = "form_date_fin";
		}
	}
/*	if ($ok){
		if ((isset($form_date_debut)) && (isset($form_date_fin))){
			$date_debut_recherche = $form_date_debut;
			$date_fin_recherche = $form_date_fin;
			$row2 = $wpdb->get_row($wpdb->prepare("SELECT SUM(nb_de_place) AS nb_emplacements_pris FROM {$wpdb->prefix}rf_bookings WHERE rf_idSpace = %s AND date_depart > %s AND date_arrivee < %s AND statut <> 'canceled'", $rf_idSpace, $date_debut_recherche, $form_date_fin));
		}else{
			$row2 = $wpdb->get_row($wpdb->prepare("SELECT SUM(nb_de_place) AS nb_emplacements_pris FROM {$wpdb->prefix}rf_bookings WHERE rf_idSpace = %s", $rf_idSpace));
		}
		if (($rowEmp->nb_de_place > 0) && (($SAFE_DATA['nb_de_place'] > $rowEmp->nb_de_place)||($row2->nb_emplacements_pris > ($rowEmp->nb_de_place - $SAFE_DATA['nb_de_place'])))){
*/
			
			
			
			
	if ($ok){
		if ((isset($form_date_debut)) && (isset($form_date_fin))){
			$date_debut_recherche = $form_date_debut;
			$date_fin_recherche = $form_date_fin;
			//$row2 = $wpdb->get_row($wpdb->prepare("SELECT SUM(nb_de_place) AS nb_emplacements_pris FROM {$wpdb->prefix}rf_bookings WHERE rf_idSpace = %s AND date_depart > %s AND date_arrivee < %s AND statut <> 'canceled' AND trashed = 0", $rf_idSpace, $date_debut_recherche, $form_date_fin));
			$row2 = $wpdb->get_results($wpdb->prepare("SELECT nb_de_place, date_arrivee, date_depart FROM {$wpdb->prefix}rf_bookings WHERE rf_idSpace = %s AND date_depart > %s AND date_arrivee < %s AND statut <> 'canceled'", $rf_idSpace, $date_debut_recherche, $form_date_fin));
			$busySpace = array_fill(0,$rowEmp->nb_de_place,[]);
			$bk = new stdClass();
			$bk->nb_de_place = $SAFE_DATA['nb_de_place'];
			$bk->date_arrivee = $date_debut_recherche;
			$bk->date_depart = $form_date_fin;
			$row2[] = $bk;
			foreach ($row2 as $bk) {
				$nbplace = $bk->nb_de_place;
				foreach($busySpace as $position => $bs){
					if ($nbplace > 0){
						$findPlace = false;
						$newbkok = [];
						$lastEndDate = $bk->date_arrivee;
						foreach ($bs as $bkok) {
							if ($nbplace > 0){
								if (($bk->date_arrivee < $bkok['debut']) && ($bk->date_depart <= $bkok['debut'])){
									$newbkok[] = ['debut' => $bk->date_arrivee, 'fin' => $bk->date_depart];
									$newbkok[] = $bkok;
									$lastEndDate = $bkok['fin'];
									$nbplace--;
									$findPlace = true;
									break;
								}else{
									$newbkok[] = $bkok;
									$lastEndDate = $bkok['fin'];
								}
							}
						}
						if (!$findPlace){
							if ($lastEndDate <= $bk->date_arrivee){
								$newbkok[] = ['debut' => $bk->date_arrivee, 'fin' => $bk->date_depart];
								$nbplace--;
							}
						}
						$busySpace[$position] = $newbkok;
					}
				}
				
				
				
				while ($nbplace > 0){
					$busySpace[] = [['debut' => $bk->date_arrivee, 'fin' => $bk->date_depart]];
					$nbplace--;
				}
				
			}
			$nb_emplacements_pris = sizeof($busySpace);
		}else{
			$row2 = $wpdb->get_row($wpdb->prepare("SELECT SUM(nb_de_place) AS nb_emplacements_pris FROM {$wpdb->prefix}rf_bookings WHERE rf_idSpace = %s AND statut <> 'canceled' AND trashed = 0", $rf_idSpace));
			$nb_emplacements_pris = $row2->nb_emplacements_pris + $SAFE_DATA['nb_de_place'];
		}
		//if (($rowEmp->nb_de_place > 0) && (($SAFE_DATA['nb_de_place'] > $rowEmp->nb_de_place)||($nb_emplacements_pris > ($rowEmp->nb_de_place - $SAFE_DATA['nb_de_place'])))){
		if (($rowEmp->nb_de_place > 0) && (($SAFE_DATA['nb_de_place'] > $rowEmp->nb_de_place)||($nb_emplacements_pris > $rowEmp->nb_de_place))){
			
			
			
			$ok = false;
			$msg .= __('We are sorry, there are not enough places available for the requested period.', 'reservation-facile');
			$inputsError[] = "form_date_debut";
			$inputsError[] = "form_date_fin";
			$inputsError[] = "nb_de_place";
		}
	}
	$todo = array();
	if (isset($form_date_debut)){
		$arrivalDate = substr($form_date_debut,0,10);
	}else{
		$arrivalDate = date('Y-m-d');
	}
	if ((isset($SAFE_DATA['form_reduction']))&&(rf_isNotEmpty($SAFE_DATA['form_reduction']))){$reductionAsked = true;}else{$reductionAsked = false;}
	if ((isset($SAFE_DATA['form_coupon']))&&(rf_isNotEmpty($SAFE_DATA['form_coupon']))){$couponAsked = true;}else{$couponAsked = false;}
	$couponCodeOk = false; $reductionCodeOk = false;
	$resultatsMP = $wpdb->get_results($wpdb->prepare("SELECT mp.* FROM {$wpdb->prefix}rf_modifyprice_spaces mpe LEFT JOIN {$wpdb->prefix}rf_modifyprice mp ON mpe.id_modificationprix = mp.id WHERE mpe.rf_idSpace=%s AND mp.date_debut <= %s AND mp.date_fin >= %s AND (mp.quantite > 0 OR mp.quantite_initiale = 0) ORDER BY mp.type", $rf_idSpace,$arrivalDate,$arrivalDate));
	foreach($resultatsMP as $mp){
		if ($mp->type == 'option'){
			$todo[] = $mp->id;
		}
		if ($mp->type == 'taxe'){
			$todo[] = $mp->id;
		}
		if (($mp->type == 'reduction') && ($reductionAsked)){
			if (strtolower($SAFE_DATA['form_reduction']) == strtolower($mp->code)){
				$reductionCodeOk = true;
				if (($mp->quantite > 0)||($mp->quantite_initiale == 0)){
					$todo[] = $mp->id;
				}else{
					$msg .= __('We are sorry, this discount is no longer available.', 'reservation-facile');
					$ok = false;
					$inputsError[] = "form_reduction";
				}
			}
		}
		if (($mp->type == 'coupon') && ($couponAsked)){
			if (strtolower($SAFE_DATA['form_coupon']) == strtolower($mp->code)){
				$couponCodeOk = true;
				if (($mp->quantite > 0)||($mp->quantite_initiale == 0)){
					$todo[] = $mp->id;
				}else{
					$msg .= __('We are sorry, this coupon is no longer available.', 'reservation-facile');
					$ok = false;
					$inputsError[] = "form_coupon";
				}
			}
		}
	}
	if ($reductionAsked && !$reductionCodeOk){
		$msg .= __('Discount code is unknown', 'reservation-facile') . '<br>';
		$ok = false; 
		$inputsError[] = "form_reduction";
	}
	if ($couponAsked && !$couponCodeOk){
		$msg .= __('Coupon code is unknown', 'reservation-facile') . '<br>';
		$ok = false; 
		$inputsError[] = "form_coupon";
	}
	include 'rf-functions-booking-preview-save.php';
	return json_encode(array('ok' => $ok, 'msg' => $msg, 'inputsError' => $inputsError, 'rowEmp' => $rowEmp, 'resultatsMP' => $resultatsMP, 'todo' => $todo, 'previsualisation' => $previsualisation, 'id_reservation' => $id_reservation, 'warning' => $warning));
}

function rf_bookingPrices($idBooking,$nb_de_place,$prix_de_la_place,$date_arrivee,$date_depart,$devise,$rf_idSpace,$timeUnit,$periodesprices,$dayprice){
	global $wpdb;
	$results = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}rf_modifyprice_bookings WHERE id_reservation = %s ORDER BY type", $idBooking));
	$type = '';
	$echo = '';
	$prixtotal = rf_getSpaceTotalPrice($date_arrivee,$date_depart,$periodesprices,$prix_de_la_place,$nb_de_place,$timeUnit,$dayprice);
	$prixoriginal = $prixtotal;
	$prixsansoptionsanstaxe = $prixtotal;
	$dateDeb = new DateTime($date_arrivee);
	$dateFin = new DateTime($date_depart);
	$duree_reservation_heures = ($dateFin->format('U') - $dateDeb->format('U')) / 3600;
	$tabMP = [	'option' =>   [__('Options added', 'reservation-facile'), __('Add option', 'reservation-facile')],
				'taxe' => 	  [__('Taxes applied', 'reservation-facile'), __('Add tax', 'reservation-facile')],
				'coupon' =>	  [__('Coupons used', 'reservation-facile'), __('Add the coupon', 'reservation-facile')],
				'reduction'=> [__('Discounts used', 'reservation-facile'), __('Add the discount', 'reservation-facile')]
	];
	//$arrivalDate = substr($date_arrivee,0,10);
	//$resultatsMP = $wpdb->get_results($wpdb->prepare("SELECT mp.id, mp.label, mp.type FROM {$wpdb->prefix}rf_modifyprice_spaces mpe LEFT JOIN {$wpdb->prefix}rf_modifyprice mp ON mpe.id_modificationprix = mp.id WHERE mpe.rf_idSpace=%s AND mp.date_debut <= %s AND mp.date_fin >= %s AND (mp.quantite > 0 OR mp.quantite_initiale = 0)", $rf_idSpace,$arrivalDate,$arrivalDate));
	$resultatsMP = $wpdb->get_results($wpdb->prepare("SELECT mp.id, mp.label, mp.type FROM {$wpdb->prefix}rf_modifyprice_spaces mpe LEFT JOIN {$wpdb->prefix}rf_modifyprice mp ON mpe.id_modificationprix = mp.id WHERE mpe.rf_idSpace=%s AND (mp.quantite > 0 OR mp.quantite_initiale = 0)", $rf_idSpace));
	$echo .= rf_get_wp_nonce_field('getMP','rf_get_MP');
	foreach($tabMP as $typeMP => $textMP){
		$echo .= '<button class="rf_accordion rf_isactive">'.$textMP[0].'</button><div class="rf_panel">';
		foreach($results as $mp){
			if ($mp->type == $typeMP){
				$echo .= '<br>';
				$echo .='
					<div class="rf_bloc"><label><b>'.__('Label', 'reservation-facile').'</b></label><br><input type="text" name="label'.$typeMP.$mp->id.'" value="'.rf_removeslashes($mp->label).'"></div>
					<div class="rf_bloc"><label><b>'.__('Quantity', 'reservation-facile').'</b></label><br><input type="number" step="any" min="0" name="quantite'.$typeMP.$mp->id.'" value="'.rf_removeslashes($mp->quantite+0).'"></div>
					<div class="rf_bloc"><label><b>'.__('Amount', 'reservation-facile').'</b></label><br><input type="number" step="any" min="0" name="montant'.$typeMP.$mp->id.'" value="'.rf_removeslashes($mp->montant+0).'"> '.$devise.'</div>
					<div class="rf_bloc"><label><b>'.__('Percentage', 'reservation-facile').'</b></label><br><input type="number" step="any" min="0" name="pourcentage'.$typeMP.$mp->id.'" value="'.rf_removeslashes($mp->pourcentage+0).'"> %</div>
					<div class="rf_bloc"><label><b>'.__('Periodicity', 'reservation-facile').'</b></label><br><input type="number" step="any" min="0" name="periode_heure'.$typeMP.$mp->id.'" value="'.rf_removeslashes($mp->periode_heure+0).'"> '.__('hrs', 'reservation-facile').'</div>
					';
					if ($typeMP == 'option'){
						
						$echo .= '<div class="rf_bloc"><label><b>'.__('Automatic quantity', 'reservation-facile').'</b></label><br><select name="codeoption'.$mp->id.'">
						<option value="userchoice" '.(($mp->code == 'userchoice')? 'selected' : '' ).'>'.__('User choice','reservation-facile').'</option> 
						<option value="oneperhour" '.(($mp->code == 'oneperhour')? 'selected' : '' ).'>'.__('Per booked hour','reservation-facile').'</option> 
						<option value="oneperday" '.(($mp->code == 'oneperday')? 'selected' : '' ).'>'.__('Per booked day','reservation-facile').'</option> 
						<option value="onepernight" '.(($mp->code == 'onepernight')? 'selected' : '' ).'>'.__('Per booked night','reservation-facile').'</option> 
						<option value="oneperweek" '.(($mp->code == 'oneperweek')? 'selected' : '' ).'>'.__('Per booked week','reservation-facile').'</option> 
						<option value="onepermonth" '.(($mp->code == 'onepermonth')? 'selected' : '' ).'>'.__('Per booked month','reservation-facile').'</option> 
						</select>';
					}else{
						$echo .= '<div class="rf_bloc"><label><b>'.__('Code', 'reservation-facile').'</b></label><br><input type="text" name="code'.$typeMP.$mp->id.'" value="'.rf_removeslashes($mp->code).'">';
					}	
					$echo .= '</div>
					<div class="rf_bloc"><label><b>'.__('Description / Details', 'reservation-facile').'</b></label><br><input type="text" name="description'.$typeMP.$mp->id.'" value="'.rf_removeslashes($mp->description).'"></div>';
				if ($typeMP == 'option'){
					$echo .= '<div class="rf_bloc"><label><b>'.__('Your choice', 'reservation-facile').'</b></label><br><input type="text" name="details_texte'.$typeMP.$mp->id.'" value="'.rf_removeslashes($mp->details_texte).'"></div>';
				}
				$echo .= '<br><br>';
			}
		}
		$echo .= '<br><h2>'.$textMP[1].'</h2>';
		$echo .= '<div class="rf_bloc"><label><b>* '.__('Label', 'reservation-facile').'</b></label><br>';
		//$echo .= '<input type="text" oninput="rf_getMP(this,\'list'.$typeMP.'\')" name="label'.$typeMP.'-1" list="list'.$typeMP.'"></div><datalist id="list'.$typeMP.'">';
		$echo .= '<select name="label'.$typeMP.'-1" onchange="rf_getMP(this,\'label'.$typeMP.'-1\',\''.$typeMP.'\')"><option>'.__('Select space first','reservation-facile').'</option>';
		foreach($resultatsMP as $mp){
			if ($mp->type == $typeMP){
				$echo .= '<option mpid="'.$mp->id.'" value="'.$mp->label.'"></option>';
			}
		}
		//$echo .= '</datalist>
		$echo .= '</select></div>';
		
		$echo .= '
			<div class="rf_bloc"><label><b>* '.__('Quantity', 'reservation-facile').'</b></label><br><input type="number" step="any" min="0" name="quantite'.$typeMP.'-1"></div>
			<div class="rf_bloc"><label><b>'.__('Amount', 'reservation-facile').'</b></label><br><input type="number" step="any" min="0" name="montant'.$typeMP.'-1"> '.$devise.'</div>
			<div class="rf_bloc"><label><b>'.__('Percentage', 'reservation-facile').'</b></label><br><input type="number" step="any" min="0" name="pourcentage'.$typeMP.'-1"> %</div>
			<div class="rf_bloc"><label><b>'.__('Periodicity', 'reservation-facile').'</b></label><br><input type="number" step="any" min="0" name="periode_heure'.$typeMP.'-1"> '.__('hrs', 'reservation-facile').'</div>
			';
			
			if ($typeMP == 'option'){
				$echo .= '<div class="rf_bloc"><label><b>'.__('Automatic quantity', 'reservation-facile').'</b></label><br><select name="code'.$typeMP.'-1">
				<option value="userchoice">'.__('User choice','reservation-facile').'</option> 
				<option value="oneperhour">'.__('Per booked hour','reservation-facile').'</option> 
				<option value="oneperday">'.__('Per booked day','reservation-facile').'</option> 
				<option value="onepernight">'.__('Per booked night','reservation-facile').'</option> 
				<option value="oneperweek">'.__('Per booked week','reservation-facile').'</option> 
				<option value="onepermonth">'.__('Per booked month','reservation-facile').'</option> 
				</select>';
			}else{
				$echo .= '<div class="rf_bloc"><label><b>'.__('Code', 'reservation-facile').'</b></label><br><input type="text" name="code'.$typeMP.'-1">';
			}
			
			$echo .= '</div>
			<div class="rf_bloc"><label><b>'.__('Description / Details', 'reservation-facile').'</b></label><br><input type="text" name="description'.$typeMP.'-1"></div>';
		if ($typeMP == 'option'){
			$echo .= '<div class="rf_bloc"><label><b>'.__('Your choice', 'reservation-facile').'</b></label><br><input type="text" name="details_texte'.$typeMP.'-1"></div>';
		}
		$echo .= '<br><br>
			</div>
		';

	}
	$labelOptions = '';
	$totalOptions = 0;
	foreach($results as $mp){
		if ($mp->type == 'option'){
			$heure_restante = $duree_reservation_heures;
			if (($heure_restante >= $mp->periode_heure)||($mp->periode_heure == 0)){
				if ($labelOptions != ''){$labelOptions .= ' | ';}
				$labelOptions .= $mp->label;
				do{
					$prixtotal += ($mp->montant*$mp->quantite);
					$totalOptions += ($mp->montant*$mp->quantite);
					$prixtotal += $prixoriginal * ($mp->pourcentage * $mp->quantite / 100);
					$totalOptions += $prixoriginal * ($mp->pourcentage * $mp->quantite / 100);
					$heure_restante -= abs($mp->periode_heure);
				}while(($heure_restante >= $mp->periode_heure)&&($mp->periode_heure > 0));
			}
		}
	}
	$prixoriginal = $prixtotal;
	$prixsanstaxe = $prixtotal;
	$labelTaxes = '';
	$totalTaxes = 0;
	foreach($results as $mp){
		if ($mp->type == 'taxe'){
			$heure_restante = $duree_reservation_heures;
			if (($heure_restante >= $mp->periode_heure)||($mp->periode_heure == 0)){
				if ($labelTaxes != ''){$labelTaxes .= ' | ';}
				$labelTaxes .= $mp->label;
				do{
					$prixtotal += $mp->montant;
					$totalTaxes += $mp->montant;
					$prixtotal += $prixoriginal * ($mp->pourcentage / 100);
					$totalTaxes += $prixoriginal * ($mp->pourcentage / 100);
					$heure_restante -= abs($mp->periode_heure);
				}while(($heure_restante >= $mp->periode_heure)&&($mp->periode_heure > 0));
			}
		}
	}
	$prixoriginal = $prixtotal;
	$prixsansremise = $prixtotal;
	$labelReductions = '';
	$labelCoupons = '';
	$totalReductions = 0;
	$totalCoupons = 0;
	foreach($results as $mp){
		if ($mp->type == 'reduction'){
			$heure_restante = $duree_reservation_heures;
			if (($heure_restante >= $mp->periode_heure)||($mp->periode_heure == 0)){
				if ($labelReductions != ''){$labelReductions .= ' | ';}
				$labelReductions .= $mp->label;
				do{
					$prixtotal -= $mp->montant;
					$totalReductions += $mp->montant;
					$prixtotal -= $prixoriginal * ($mp->pourcentage / 100);
					$totalReductions += $prixoriginal * ($mp->pourcentage / 100);
					$heure_restante -= abs($mp->periode_heure);
				}while(($heure_restante >= $mp->periode_heure)&&($mp->periode_heure > 0));
			}
		}
		if ($mp->type == 'coupon'){
			$heure_restante = $duree_reservation_heures;
			if (($heure_restante >= $mp->periode_heure)||($mp->periode_heure == 0)){
				if ($labelCoupons != ''){$labelCoupons .= ' | ';}
				$labelCoupons .= $mp->label;
				do{
					$prixtotal -= $mp->montant;
					$totalCoupons += $mp->montant;
					$prixtotal -= $prixoriginal * ($mp->pourcentage / 100);
					$totalCoupons += $prixoriginal * ($mp->pourcentage / 100);
					$heure_restante -= abs($mp->periode_heure);
				}while(($heure_restante >= $mp->periode_heure)&&($mp->periode_heure > 0));
			}
		}
	}
	$prixavecremise = $prixtotal;
	$prixfinal = $prixtotal;
	return array(	'prixsansoptionsanstaxe' => $prixsansoptionsanstaxe,
					'prixsanstaxe' => $prixsanstaxe,
					'prixsansremise' => $prixsansremise,
					'prixavecremise' => $prixavecremise,
					'prixfinal' => $prixfinal,
					'labelOptions' => $labelOptions,
					'totalOptions' => $totalOptions,
					'labelTaxes' => $labelTaxes,
					'totalTaxes' => $totalTaxes,
					'labelReductions' => $labelReductions,
					'totalReductions' => $totalReductions,
					'labelCoupons' => $labelCoupons,
					'totalCoupons' => $totalCoupons,
					'echo' => $echo
	);
}
