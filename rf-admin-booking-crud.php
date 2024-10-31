<?php
defined( 'ABSPATH' ) or die();
if (!current_user_can('manage_options')){return;}
if (is_admin() !== true) {return;}

if (($rf_act == 'insertBooking') || ($rf_act == 'updateBooking')) {
	$date = $SAFE_DATA["date"]." ".$SAFE_DATA["date_heure"].":00";
	$nb_de_place = $SAFE_DATA["nb_de_place"];
	$prix_de_la_place = $SAFE_DATA["prix_de_la_place"];
	$acompte_prix = $SAFE_DATA["acompte_prix"];
	$acompte_pourcentage = $SAFE_DATA["acompte_pourcentage"];
	$getOT = $wpdb->get_row($wpdb->prepare("SELECT openingtimes, timeUnit, user_minutes_interval, exceptionalclosure FROM {$wpdb->prefix}rf_spaces WHERE id=%d",$SAFE_DATA['rf_idSpace']));
	$rf_OT = json_decode(stripslashes($getOT->openingtimes));
	if (!rf_isNotEmpty($SAFE_DATA["form_heure_debut"])){
		$SAFE_DATA["form_heure_debut"] = rf_getReferenceTimeInOT($rf_OT,$SAFE_DATA["form_date_debut"]." 12:00",1,1,"",1,$getOT->exceptionalclosure);
	}

	if (!rf_isNotEmpty($SAFE_DATA["form_heure_fin"])){
		$SAFE_DATA["form_heure_fin"] = rf_getReferenceTimeInOT($rf_OT,$SAFE_DATA["form_date_fin"]." 00:00",2,$getOT->timeUnit,$SAFE_DATA["form_date_debut"]." ".$SAFE_DATA["form_heure_debut"],$getOT->user_minutes_interval,$getOT->exceptionalclosure);
		
	}
	$date_arrivee = $SAFE_DATA["form_date_debut"]." ".$SAFE_DATA["form_heure_debut"].":00";
	$date_depart = $SAFE_DATA["form_date_fin"]." ".$SAFE_DATA["form_heure_fin"].":00";
	$nb_de_personnes = $SAFE_DATA["form_personnes"];
	$nom = $SAFE_DATA["form_nom"];
	$prenom = $SAFE_DATA["form_prenom"];
	$adresse = $SAFE_DATA["form_adresse"];
	$code_postal = $SAFE_DATA["form_code_postal"];
	$ville = $SAFE_DATA["form_ville"];
	$pays = $SAFE_DATA["form_pays"];
	$email = $SAFE_DATA["form_email"];
	$telephone = $SAFE_DATA["form_telephone"];
	$remarques = $SAFE_DATA["remarques"];
	$statut = $SAFE_DATA["statut"];
	$devise = $SAFE_DATA["devise"];
	$reference_interne = $SAFE_DATA["reference_interne"];
	$timeUnit = $SAFE_DATA["timeUnit"];
	$periodesprices = $SAFE_DATA["periodesprices"];
	$dayprice = $SAFE_DATA["dayprice"];
	if ($rf_act == 'updateBooking'){
		$id_reservation = $SAFE_DATA["id_reservation"];
		$getNbPlace = $wpdb->get_row($wpdb->prepare("SELECT nb_de_place FROM {$wpdb->prefix}rf_bookings WHERE id=%d",$id_reservation));
		$nb_de_place_orig = $getNbPlace->nb_de_place;
		$wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}rf_bookings SET nb_de_place='0' WHERE id=%d",$id_reservation));
	}
	if ($SAFE_DATA['rf_idSpace'] != -1){
		$errors = json_decode(rf_checkBookingFormPost($SAFE_DATA['rf_idSpace'],false,true));
		$msg = $errors->msg;
	}else{
		$msg = __('Please select the place', 'reservation-facile').'<br>';
	}
	if ($msg != ''){
		if ($rf_act == 'insertBooking'){
			rf_addAdminNotice(str_replace('<br>','. ',$msg),1,'error');
			$rf_act = 'newBooking';
			$reservationRetry = new stdClass();
			$reservationRetry->id = -1;
			$reservationRetry->rf_idSpace = $SAFE_DATA['rf_idSpace'];
			$reservationRetry->date = date('Y-m-d H:i:s');
			$reservationRetry->nb_de_place = $nb_de_place;
			$reservationRetry->prix_de_la_place = $prix_de_la_place;
			$reservationRetry->acompte_prix = $acompte_prix;
			$reservationRetry->acompte_pourcentage = $acompte_pourcentage;
			$reservationRetry->date_arrivee = $date_arrivee;
			$reservationRetry->date_depart = $date_depart;
			$reservationRetry->nb_de_personnes = $nb_de_personnes;
			$reservationRetry->nom = $nom;
			$reservationRetry->prenom = $prenom;
			$reservationRetry->adresse = $adresse;
			$reservationRetry->code_postal = $code_postal;
			$reservationRetry->ville = $ville;
			$reservationRetry->pays = $pays;
			$reservationRetry->email = $email;
			$reservationRetry->telephone = $telephone;
			$reservationRetry->remarques = $remarques;
			$reservationRetry->statut = $statut;
			$reservationRetry->devise = $devise;
			$reservationRetry->reference_interne = $reference_interne;
			$reservationRetry->timeUnit = $timeUnit;
			$reservationRetry->periodesprices = $periodesprices;
			$reservationRetry->dayprice = $dayprice;
		}
		if ($rf_act == 'updateBooking'){
			rf_addAdminNotice(str_replace('<br>','. ',$msg),1,'error');
			$wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}rf_bookings SET nb_de_place=%s WHERE id=%d",$nb_de_place_orig,$id_reservation));
			$rf_act = 'displayBooking';
		}
	}else{
		$warning = $errors->warning;
		$rowEmp = $errors->rowEmp;
		if ($rf_act == 'insertBooking'){
			if ($warning != ''){
				rf_addAdminNotice(str_replace('<br>','. ',$warning),1,'warning');
			}
			$location = $rowEmp->localisation;
			$space = $rowEmp->label;
			$wpdb->query($wpdb->prepare("INSERT INTO {$wpdb->prefix}rf_bookings(rf_idSpace,date,nb_de_place,prix_de_la_place,acompte_prix,acompte_pourcentage,date_arrivee,date_depart,nb_de_personnes,nom,prenom,adresse,code_postal,ville,pays,email,telephone,statut,remarques,reference_interne,devise,emplacement,lieu,timeUnit,periodesprices,dayprice) VALUES(%d,%s,%s,%s,%s,%s,%s,%s,%d,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%d,%s,%s)",$SAFE_DATA['rf_idSpace'],$date,$nb_de_place,$prix_de_la_place,$acompte_prix,$acompte_pourcentage,$date_arrivee,$date_depart,$nb_de_personnes,$nom,$prenom,$adresse,$code_postal,$ville,$pays,$email,$telephone,$statut,$remarques,$reference_interne,$devise,$space,$location,$timeUnit,$periodesprices,$dayprice));
			rf_addAdminNotice(__('The booking has been added', 'reservation-facile'),1);
			$id_reservation = $wpdb->insert_id;
			$SAFE_DATA['id_reservation'] = $id_reservation;
			$rf_act = 'displayBooking';
		}
		if ($rf_act == 'updateBooking'){
			if ($warning != ''){
				rf_addAdminNotice(str_replace('<br>','. ',$warning),1,'warning');
			}
			$location = $rowEmp->localisation;
			$space = $rowEmp->label;
			$wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}rf_bookings SET rf_idSpace=%d,date=%s,nb_de_place=%s,prix_de_la_place=%s,acompte_prix=%s,acompte_pourcentage=%s,date_arrivee=%s,date_depart=%s,nb_de_personnes=%d,nom=%s,prenom=%s,adresse=%s,code_postal=%s,ville=%s,pays=%s,email=%s,telephone=%s,statut=%s,remarques=%s,reference_interne=%s,devise=%s,emplacement=%s,lieu=%s,timeUnit=%d,periodesprices=%s,dayprice=%s WHERE id=%d",$SAFE_DATA['rf_idSpace'],$date,$nb_de_place,$prix_de_la_place,$acompte_prix,$acompte_pourcentage,$date_arrivee,$date_depart,$nb_de_personnes,$nom,$prenom,$adresse,$code_postal,$ville,$pays,$email,$telephone,$statut,$remarques,$reference_interne,$devise,$space,$location,$timeUnit,$periodesprices,$dayprice,$id_reservation));
			rf_addAdminNotice(__('The booking has been updated', 'reservation-facile'),1);
			$rf_act = 'displayBooking';
		}
		$tabMP = ['option','taxe','coupon','reduction'];
		foreach ($SAFE_DATA as $key => $data){
			foreach ($tabMP as $typeMP){
				$keyMP = 'label'.$typeMP;
				$keyMPLength = strlen($keyMP);
				if (substr($key,0,$keyMPLength) == $keyMP){
					$idMP = substr($key,$keyMPLength);
					$labelMP = $SAFE_DATA["label".$typeMP.$idMP];
					$descriptionMP = $SAFE_DATA["description".$typeMP.$idMP];
					$quantiteMP = $SAFE_DATA["quantite".$typeMP.$idMP];
					$montantMP = $SAFE_DATA["montant".$typeMP.$idMP];
					$pourcentageMP = $SAFE_DATA["pourcentage".$typeMP.$idMP];
					$periodeMP = $SAFE_DATA["periode_heure".$typeMP.$idMP];
					$codeMP = $SAFE_DATA["code".$typeMP.$idMP];
					(isset($SAFE_DATA["details_texte".$typeMP.$idMP])) ? $details_texteMP = $SAFE_DATA["details_texte".$typeMP.$idMP] : $details_texteMP = '';
					if ($idMP == -1){
						if (($labelMP != '') && ($quantiteMP > 0)){
							$wpdb->query($wpdb->prepare("INSERT INTO {$wpdb->prefix}rf_modifyprice_bookings(id_reservation,label,description,quantite,montant,pourcentage,periode_heure,code,type,details_texte) VALUES(%d,%s,%s,%s,%s,%s,%s,%s,%s,%s)",$id_reservation,$labelMP,$descriptionMP,$quantiteMP,$montantMP,$pourcentageMP,$periodeMP,$codeMP,$typeMP,$details_texteMP));
							rf_addAdminNotice($quantiteMP . ' ' . $labelMP . ' ' .__('added', 'reservation-facile'),1);
						}
					}else{
						if (($labelMP == '') || ($quantiteMP == 0)){
							$wpdb->query($wpdb->prepare("DELETE FROM {$wpdb->prefix}rf_modifyprice_bookings WHERE id=%d",$idMP));
						}else{
							$wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}rf_modifyprice_bookings SET label=%s,description=%s,quantite=%s,montant=%s,pourcentage=%s,periode_heure=%s,code=%s,details_texte=%s WHERE id=%d",$labelMP,$descriptionMP,$quantiteMP,$montantMP,$pourcentageMP,$periodeMP,$codeMP,$details_texteMP,$idMP));
						}
					}
				}
			}
		}
	}
}

if ($rf_act == "deleteBooking"){
	$id_reservation = $SAFE_DATA["id_reservation"];
	$wpdb->query($wpdb->prepare("DELETE FROM {$wpdb->prefix}rf_bookings WHERE id=%d",$id_reservation ));
	rf_addAdminNotice(__('The booking has been removed', 'reservation-facile'),1);
}

if (($rf_act == 'displayBooking') || ($rf_act == 'newBooking')){
	if ($rf_act == 'displayBooking'){
		$id_reservation = $SAFE_DATA['id_reservation'];
		$reservation = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}rf_bookings WHERE id = %d", $id_reservation));
		$action = 'updateBooking';
	}
	if ($rf_act == 'newBooking'){
		$id_reservation = -1;
		if (isset($reservationRetry)){
			$reservation = $reservationRetry;
		}else{
			$reservation = new stdClass();
			$reservation->id = $id_reservation;
			$reservation->rf_idSpace = '';
			$reservation->nom = '';
			$reservation->date = date('Y-m-d H:i:s');
			$reservation->nb_de_place = '';
			$reservation->prix_de_la_place = '';
			$reservation->acompte_prix = '';
			$reservation->acompte_pourcentage = '';
			$reservation->date_arrivee = '';
			$reservation->date_depart = '';
			$reservation->nb_de_personnes = '';
			$reservation->nom = '';
			$reservation->prenom = '';
			$reservation->adresse = '';
			$reservation->code_postal = '';
			$reservation->ville = '';
			$reservation->pays = '';
			$reservation->email = '';
			$reservation->telephone = '';
			$reservation->remarques = '';
			$reservation->statut = '';
			$reservation->devise = '';
			$reservation->reference_interne = '';
			$reservation->timeUnit = '';
			$reservation->periodesprices = '';
			$reservation->dayprice = '';
		}
		$action = 'insertBooking';
	}
	$echo .= '
	<div class="rf_wrap">
		<div>';
			if (isset($SAFE_DATA["calendarCurrentYearMonth"])){
				$echo .= '<input type="hidden" id="rf_currentMonth" value="'.(substr($SAFE_DATA["calendarCurrentYearMonth"],5,2)-1).'">';
				$echo .= '<input type="hidden" id="rf_currentYear" value="'.substr($SAFE_DATA["calendarCurrentYearMonth"],0,4).'">';
			}
			$echo .= '
			<div id="rf_navBar">
				<form name="when">
					<input type="hidden" id="rf_nbDePlace" value="0">';
			$echo .= rf_get_wp_nonce_field('getEmplacementVal','rf_mainGetEmplacementVal');
			$echo .= rf_get_wp_nonce_field('chargeCalendrier','rf_mainCalendar');
			$echo .= '
					<table>
						<tr>
						   <td><span class="rf_norotate_arrow_calendar">
									<img class="emoji" src="'.plugins_url( 'img/arrow.svg', __FILE__ ).'" onClick="rf_calendarSkip(\'-\')">
								</span>
							</td>
						   <td> </td>
						   <td><select name="month" onChange="rf_calendarOnMonth()">';
						   $echo .= rf_getMonthsOption(date('Y'),date('m'));
						   $echo .= '</select>
						   </td>
						   <td colspan="2"><input class="rf_calendarYear" type="text" name="year" size=4 maxlength=4 onKeyPress="return rf_calendarCheckNums()" onKeyUp="rf_calendarOnYear()"></td>
						   <td><span class="rf_rotate_arrow_calendar">
									<img class="emoji" src="'.plugins_url( 'img/arrow.svg', __FILE__ ).'" onClick="rf_calendarSkip(\'+\')">
								</span>
							</td>
						</tr>
					</table>
				</form>
			</div>
			<div id="rf_calendar"></div><script>rf_getEmplacementVal("'.$reservation->rf_idSpace.'",true)</script>';
			$echo .= '<form action="" method="post" name="rf_formAddEditBookingSpace">';
			$echo .= '<input type="hidden" name="rf_act" value="'.$action.'">';
			$echo .= '<input type="hidden" name="id_reservation" value="'.$reservation->id.'">';
			$echo .= '<input type="hidden" name="periodesprices" value="'.$reservation->periodesprices.'">';
			$echo .= '<input type="hidden" name="dayprice" value="'.$reservation->dayprice.'">';
			$echo .= rf_get_wp_nonce_field($action);
			if (isset($SAFE_DATA["rf_prevAct"])){
				$echo .= '<input type="hidden" name="rf_prevAct" value="'.$SAFE_DATA["rf_prevAct"].'"><input type="hidden" name="id_lieu" value="'.$SAFE_DATA["id_lieu"].'"><input type="hidden" name="calendarCurrentYearMonth" value="'.$SAFE_DATA['calendarCurrentYearMonth'].'">';
			}
			$echo .= '<button class="rf_accordion">';
			if ($id_reservation != -1){
				$echo .= __('Booking', 'reservation-facile').' #'.$reservation->id;
			}
			$echo .= '</button>
						<div class="rf_panel">';		
			$echo .= '<div class="rf_booking_column"><br>';
			$echo3 = '<option></option>';
			$emplacements = $wpdb->get_results($wpdb->prepare("SELECT emp.id,emp.label,lieu.nom,%s FROM {$wpdb->prefix}rf_spaces emp INNER JOIN {$wpdb->prefix}rf_locations lieu ON lieu.id=emp.id_lieu ORDER BY emp.id DESC",'none'));
			foreach ($emplacements as $emplacement) {
				$echo3 .= '<option value="'.$emplacement->id.'" '.(($emplacement->id == $reservation->rf_idSpace)? 'selected':'').'>#'.$emplacement->id.' - '.rf_removeslashes($emplacement->nom).' - '.rf_removeslashes($emplacement->label).'</option>';
			}
			$echo .= '
			<label><b>'.__('Place', 'reservation-facile').'</b></label><br><select name="rf_idSpace" id="rf_idSpace" onchange="rf_getEmplacementVal(this.value)" required>'.$echo3.'</select><br><br>
			<label><b>'.__('Price', 'reservation-facile').'</b></label><br><input type="number" min="0" step="any" name="prix_de_la_place" value="'.($reservation->prix_de_la_place+0).'"><br><br>
			<label><b>'.__('Currency', 'reservation-facile').'</b></label><br><select name="devise">'.rf_getAllCurrencies($reservation->devise).'</select><br><br>
			<label><b>'.__('Time unit (in hours)', 'reservation-facile').'</b></label><br><input type="number" name="timeUnit" min="0" value="'.($reservation->timeUnit+0).'"><br><br>
			<label><b>'.__('Deposit requested (amount)', 'reservation-facile').'</b></label><br><input type="number" min="0" step="any" name="acompte_prix" value="'.($reservation->acompte_prix+0).'"><br><br>
			<label><b>'.__('Deposit requested (percentage)', 'reservation-facile').'</b></label><br><input type="number" min="0" step="any" name="acompte_pourcentage" value="'.($reservation->acompte_pourcentage+0).'"><br><br>
			</div><div class="rf_booking_column"><br>
			<label><b>'.__('Number of places', 'reservation-facile').'</b></label><br><input type="number" min="0" step="any" name="nb_de_place" value="'.($reservation->nb_de_place+0).'"><br><br>
			<label><b>'.__('Arrival date', 'reservation-facile').'</b></label><br><input type="date" name="form_date_debut" required value="'.substr($reservation->date_arrivee,0,10).'"><br><br>
			<label><b>'.__('Arrival time', 'reservation-facile').'</b></label><br><input type="time" name="form_heure_debut" value="'.substr($reservation->date_arrivee,11,5).'"><br><br>
			<label><b>'.__('Departure date', 'reservation-facile').'</b></label><br><input type="date" name="form_date_fin" required value="'.substr($reservation->date_depart,0,10).'"><br><br>
			<label><b>'.__('Departure time', 'reservation-facile').'</b></label><br><input type="time" name="form_heure_fin" value="'.substr($reservation->date_depart,11,5).'"><br><br>
			<label><b>'.__('Number of people', 'reservation-facile').'</b></label><br><input type="number" name="form_personnes" min="0" value="'.($reservation->nb_de_personnes+0).'"><br><br>
			</div><div class="rf_booking_column"><br>
			<label><b>'.__('Last name', 'reservation-facile').'</b></label><br><input type="text" name="form_nom" value="'.rf_removeslashes($reservation->nom).'"><br><br>
			<label><b>'.__('First name', 'reservation-facile').'</b></label><br><input type="text" name="form_prenom" value="'.rf_removeslashes($reservation->prenom).'"><br><br>
			<label><b>'.__('Address', 'reservation-facile').'</b></label><br><input type="text" name="form_adresse" value="'.rf_removeslashes($reservation->adresse).'"><br><br>
			<label><b>'.__('Zip code', 'reservation-facile').'</b></label><br><input type="text" name="form_code_postal" value="'.rf_removeslashes($reservation->code_postal).'"><br><br>
			<label><b>'.__('City', 'reservation-facile').'</b></label><br><input type="text" name="form_ville" value="'.rf_removeslashes($reservation->ville).'"><br><br>
			<label><b>'.__('Country', 'reservation-facile').'</b></label><br><input type="text" name="form_pays" value="'.rf_removeslashes($reservation->pays).'"><br><br>
			<label><b>'.__('Email', 'reservation-facile').'</b></label><br><input type="email" name="form_email" value="'.$reservation->email.'"><br><a href="mailto:'.$reservation->email.'">'.__('Send an email', 'reservation-facile').'</a><br><br>
			<label><b>'.__('Tel.', 'reservation-facile').'</b></label><br><input type="text" name="form_telephone" value="'.rf_removeslashes($reservation->telephone).'"><br><br>
			</div><div class="rf_booking_column"><br>
			<label><b>'.__('Status', 'reservation-facile').'</b></label><br>
			<select name="statut">
				<option value="validationinprogress" '.(($reservation->statut == 'validationinprogress')? 'selected':'').'>'.rf_getBookingStatus('validationinprogress').'</option>
				<option value="pendingpayment" '.(($reservation->statut == 'pendingpayment')? 'selected':'').'>'.rf_getBookingStatus('pendingpayment').'</option>
				<option value="confirmed" '.(($reservation->statut == 'confirmed')? 'selected':'').'>'.rf_getBookingStatus('confirmed').'</option>
				<option value="paid" '.(($reservation->statut == 'paid')? 'selected':'').'>'.rf_getBookingStatus('paid').'</option>
				<option value="canceled" '.(($reservation->statut == 'canceled')? 'selected':'').'>'.rf_getBookingStatus('canceled').'</option>
			</select><br><br>
			<label><b>'.__('Internal reference', 'reservation-facile').'</b></label><br><input type="text" name="reference_interne" value="'.$reservation->reference_interne.'"><br><br>
			<label><b>'.__('Registration date', 'reservation-facile').'</b></label><br><input type="date" name="date" required value="'.substr($reservation->date,0,10).'"><br><br>
			<label><b>'.__('Registration time', 'reservation-facile').'</b></label><br><input type="time" name="date_heure" required value="'.substr($reservation->date,11,5).'"><br><br>
			<label><b>'.__('Comments').'</b></label><br><textarea name="remarques" rows="10">'.rf_removeslashes($reservation->remarques).'</textarea><br><br>
			</div><br></div>';
			$tabPrices = rf_bookingPrices($reservation->id,$reservation->nb_de_place,$reservation->prix_de_la_place,$reservation->date_arrivee,$reservation->date_depart,$reservation->devise,$reservation->rf_idSpace,$reservation->timeUnit,$reservation->periodesprices,$reservation->dayprice);
			$echo .= $tabPrices["echo"];
			$echo .= '<button class="rf_accordion">'.__('Total', 'reservation-facile').'</button><div class="rf_panel"><br>';
			$echo .= "<div class='rf_bloc'><label><b>".__('Initial price', 'reservation-facile')."</b></label><br><input id='rf_initialPrice' disabled type='text' value='".round($tabPrices['prixsansoptionsanstaxe'],sprintf( '%.'.rf_getParameter('roundnumber').'f', rf_getParameter('roundnumber')))." ".$reservation->devise."'></div>";
			$echo .= rf_displayPrice($tabPrices['prixsansoptionsanstaxe'], $reservation->devise, 'rf_initialPrice', 'value');
			$echo .= "<div class='rf_bloc'><label><b>".__('Price with options', 'reservation-facile')."</b></label><br><input id='rf_priceWithOptions' disabled type='text' value='".round($tabPrices['prixsanstaxe'],sprintf( '%.'.rf_getParameter('roundnumber').'f', rf_getParameter('roundnumber'))).' '.$reservation->devise."'></div>";
			$echo .= rf_displayPrice($tabPrices['prixsanstaxe'], $reservation->devise, 'rf_priceWithOptions', 'value');
			$echo .= "<div class='rf_bloc'><label><b>".__('Price with taxes', 'reservation-facile')."</b></label><br><input id='rf_priceWithTaxes' disabled type='text' value='".round($tabPrices['prixsansremise'],sprintf( '%.'.rf_getParameter('roundnumber').'f', rf_getParameter('roundnumber'))).' '.$reservation->devise."'></div>";
			$echo .= rf_displayPrice($tabPrices['prixsansremise'], $reservation->devise, 'rf_priceWithTaxes', 'value');
			$echo .= "<div class='rf_bloc'><label><b>".__('Price with deductions', 'reservation-facile')."</b></label><br><input id='rf_priceWithDeductions' disabled type='text' value='".round($tabPrices['prixavecremise'],sprintf( '%.'.rf_getParameter('roundnumber').'f', rf_getParameter('roundnumber'))).' '.$reservation->devise."'></div>";
			$echo .= rf_displayPrice($tabPrices['prixavecremise'], $reservation->devise, 'rf_priceWithDeductions', 'value');
			$echo .= "<div class='rf_bloc'><div><label><b>".__('Total to pay', 'reservation-facile')."</b></label><br><input id='rf_totalToPay' disabled type='text' value='".sprintf( '%.'.rf_getParameter('roundnumber').'f', round($tabPrices['prixfinal'],rf_getParameter('roundnumber'))).' '.$reservation->devise."'></div></div>";
			$echo .= rf_displayPrice($tabPrices['prixfinal'], $reservation->devise, 'rf_totalToPay', 'value');
			$echo .= "<br><br></div>";	
			$echo .= '<div class="rf_buttons"><div class="rf_pad10">';
			$echo .= get_submit_button(__('Save', 'reservation-facile')).'
			</form>
			<form action="" method="post" class="rf_delete" onsubmit="return confirm(\''.__('Are you sure you want to delete this booking?', 'reservation-facile').'\');">
				<input type="hidden" name="rf_act" value="deleteBooking">
				<input type="hidden" name="id_reservation" value="'.$id_reservation.'">';
			$echo .= rf_get_wp_nonce_field('deleteBooking');
			if (isset($SAFE_DATA["rf_prevAct"])){
				$echo .= '<input type="hidden" name="rf_prevAct" value="'.$SAFE_DATA["rf_prevAct"].'"><input type="hidden" name="id_lieu" value="'.$SAFE_DATA["id_lieu"].'"><input type="hidden" name="rf_idSpace" value="'.$SAFE_DATA["rf_idSpace"].'"><input type="hidden" name="calendarCurrentYearMonth" value="'.$SAFE_DATA['calendarCurrentYearMonth'].'">';
			}
			$echo .= get_submit_button(__('Delete', 'reservation-facile'),'delete').'
			</form>
			</div></div>
		</div></div>';
}
