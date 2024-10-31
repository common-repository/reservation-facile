<?php
defined( 'ABSPATH' ) or die();
function rf_process_action_reservations(){
	global $echo;
	global $rf_act;
	global $SAFE_DATA;
	global $rf_spaceName;
	global $rf_help;
	global $wpdb;
	global $rf_display_menu;
	if (!current_user_can('administrator')){return;}
	$isDataFormSafe = rf_secureDataForm();
	if (!$isDataFormSafe[0]){
		echo 'DEBUG1:------------------- '. $isDataFormSafe[1].'<br>';
		error_log('DEBUG1:------------------- '. $isDataFormSafe[1]);
	}else{
		$SAFE_DATA = $isDataFormSafe[2];
	}
	if (isset($SAFE_DATA['rf_act'])){
		$rf_act = $SAFE_DATA['rf_act'];
		check_admin_referer($rf_act);
	}
	
	if (in_array($rf_act,['insertSpace','updateSpace','updateInfoShowSpace','deleteSpace'])){
		include 'rf-admin-space-crud.php';
	}
	
	if (in_array($rf_act,['insertBooking','updateBooking','deleteBooking','displayBooking','newBooking'])){
		include 'rf-admin-booking-crud.php';
	}
	
	if ($rf_act == 'displaySpace'){
		$rf_idSpace = $SAFE_DATA['rf_idSpace'];
		$emplacement = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}rf_spaces WHERE id = %d", $rf_idSpace));
		$id_lieu = $emplacement->id_lieu;
		$lieu = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}rf_locations WHERE id = %d", $id_lieu));
		$echo .= '<div class="rf_wrap">';
		$rf_idSpace = $emplacement->id;
		$label = $emplacement->label;
		$rf_spaceName = '#' . $rf_idSpace . ' - ' . $lieu->nom . ' - ' . $label;
		$nb_de_place = $emplacement->nb_de_place;
		$prix_de_la_place = $emplacement->prix_de_la_place;
		$devise = $emplacement->devise;
		$user_minutes_interval = $emplacement->user_minutes_interval;
		$timeUnit = $emplacement->timeUnit;
		$minBookingDuration = $emplacement->minBookingDuration;
		$tps_reservation_max_heure = $emplacement->tps_reservation_max_heure;
		$acompte_prix = $emplacement->acompte_prix;
		$acompte_pourcentage = $emplacement->acompte_pourcentage;
		$lien_CGU = $emplacement->lien_CGU;
		$notification_email = $emplacement->notification_email;
		$email_notification = $emplacement->email_notification;
		$date_debut_reservation = $emplacement->date_debut_reservation;
		$date_fin_reservation = $emplacement->date_fin_reservation;
		$statut_par_defaut_reservation = $emplacement->statut_par_defaut_reservation;
		$description = $emplacement->description;
		$payment_instructions = $emplacement->payment_instructions;
		$periodesprices = $emplacement->periodesprices;
		$exceptionalclosure = $emplacement->exceptionalclosure;
		$dayprice = $emplacement->dayprice;
		$openingtimes = $emplacement->openingtimes;
		$form_date_debut = $emplacement->form_date_debut;
		$form_heure_debut = $emplacement->form_heure_debut;
		$form_date_fin = $emplacement->form_date_fin;
		$form_heure_fin = $emplacement->form_heure_fin;
		$form_personnes = $emplacement->form_personnes;
		$form_nom = $emplacement->form_nom;
		$form_prenom = $emplacement->form_prenom;
		$form_adresse = $emplacement->form_adresse;
		$form_code_postal = $emplacement->form_code_postal;
		$form_ville = $emplacement->form_ville;
		$form_pays = $emplacement->form_pays;
		$form_email = $emplacement->form_email;
		$form_telephone = $emplacement->form_telephone;
		$form_remarques = $emplacement->form_remarques;
		$form_nb_de_place = $emplacement->form_nb_de_place;
		$info_date_debut_reservation = $emplacement->info_date_debut_reservation;
		$info_date_fin_reservation = $emplacement->info_date_fin_reservation;
		$info_prix_de_la_place = $emplacement->info_prix_de_la_place;
		$info_acompte_prix = $emplacement->info_acompte_prix;
		$info_acompte_pourcentage = $emplacement->info_acompte_pourcentage;
		$info_timeUnit = $emplacement->info_timeUnit;
		$info_minBookingDuration = $emplacement->info_minBookingDuration;
		$info_tps_reservation_max_heure = $emplacement->info_tps_reservation_max_heure;
		$info_description = $emplacement->info_description;
		$info_calendrier = $emplacement->info_calendrier;		
		$class = '';
		if (($emplacement->prix_de_la_place == 0) && ($emplacement->email_notification == '') && ($emplacement->id == 1)){
			$class = 'class="rf_help_edit_space_onglet"';
			$rf_help = 'edit_space';
		}
		
		if ($rf_help == 'edit_space'){
			$echo .= '<div class="rf_help_add_location">';
			$echo .= __('Enter the main information about your place in the "General" tab and click on the save button.', 'reservation-facile'). '<br>';
			$echo .= __('Other tabs allow you to set options, taxes, coupons, discounts and the way the form has to be displayed.', 'reservation-facile') . '<br>';
			$echo .= __('Finally, get the shortcode and paste it where you want to display the booking form.', 'reservation-facile') . '<br>';
			$echo .= '</div><br>';
		}
		
		$echo .= '
		<ul class="rf_plugin_onglets">
			<li class="rf_isactive" id="rf_plugin_onglets_calendrier_li"><a href="#" onclick="rf_showOnglet(\'rf_plugin_onglets_calendrier\')">'.__('Bookings', 'reservation-facile').'</a></li>
			<li id="rf_plugin_onglets_principal_li" '.$class.'><a href="#" onclick="rf_showOnglet(\'rf_plugin_onglets_principal\')">'.__('General', 'reservation-facile').'</a></li>
			<li id="rf_plugin_onglets_options_li"><a href="#" onclick="rf_showOnglet(\'rf_plugin_onglets_options\')">'.__('Options', 'reservation-facile').'</a></li>
			<li id="rf_plugin_onglets_taxes_li"><a href="#" onclick="rf_showOnglet(\'rf_plugin_onglets_taxes\')">'.__('Taxes', 'reservation-facile').'</a></li>
			<li id="rf_plugin_onglets_coupons_li"><a href="#" onclick="rf_showOnglet(\'rf_plugin_onglets_coupons\')">'.__('Coupons', 'reservation-facile').'</a></li>
			<li id="rf_plugin_onglets_reductions_li"><a href="#" onclick="rf_showOnglet(\'rf_plugin_onglets_reductions\')">'.__('Discounts', 'reservation-facile').'</a></li>
			<li id="rf_plugin_onglets_formulaire_li"><a href="#" onclick="rf_showOnglet(\'rf_plugin_onglets_formulaire\')">'.__('Display', 'reservation-facile').'</a></li>
		</ul>';
		
		
		
		$echo .= rf_displayAdminNotice(2);
		include 'rf-admin-bookings-calendar.php';
		include 'rf-admin-bookings-general.php';
		$resultats4 = $wpdb->get_results("SELECT mp.id as id_modificationprix, mp.label, mp.date_debut, mp.date_fin, mp.quantite, mp.quantite_initiale, mp.montant, mp.pourcentage, mp.periode_heure, mp.code, mp.description, mp.type, mp.details_texte, GROUP_CONCAT(mpe.rf_idSpace) as rf_idSpace FROM {$wpdb->prefix}rf_modifyprice mp LEFT JOIN {$wpdb->prefix}rf_modifyprice_spaces mpe ON mp.id = mpe.id_modificationprix GROUP BY mp.id ORDER BY mp.label");
		include 'rf-admin-bookings-options.php';
		include 'rf-admin-bookings-taxes.php';
		include 'rf-admin-bookings-coupons.php';
		include 'rf-admin-bookings-discounts.php';
		include 'rf-admin-bookings-display.php';
		$echo .= '</div>';
    }
	$rf_display_menu = true;
	rf_menu_html();
}