<?php
defined( 'ABSPATH' ) or die();
if (is_admin() === true) {
	add_action('admin_menu', 'rf_add_admin_menu');
}

function rf_add_admin_menu(){
    add_menu_page('Réservation Facile', 'Réservation Facile', 'manage_options', 'reservation-facile', 'rf_menu_html',plugins_url( 'img/logo24.png', __FILE__ ));
	add_submenu_page( 'reservation-facile', __('Bookings', 'reservation-facile'), __('Bookings', 'reservation-facile'), 'manage_options', 'reservation-facile','rf_process_action_reservations');
	add_submenu_page( 'reservation-facile', __('Shortcodes', 'reservation-facile'), __('Shortcodes', 'reservation-facile'), 'manage_options', 'reservation-facile-shortcodes','rf_process_action_shortcodes');
	add_submenu_page( 'reservation-facile', __('Price changes', 'reservation-facile'), __('Price changes', 'reservation-facile'), 'manage_options', 'reservation-facile-price-changes','rf_process_action_price_changes');
	add_submenu_page( 'reservation-facile', __('Parameters', 'reservation-facile'), __('Parameters', 'reservation-facile'), 'manage_options', 'reservation-facile-parameters','rf_process_action_parameters');
	rf_load_front_js();
	rf_load_front_css();
	rf_load_admin_js();
	rf_load_admin_css();
}

function rf_load_admin_js(){
	if (!current_user_can('administrator')){return;}
	wp_enqueue_script('jquery');
	wp_enqueue_script('jquery-ui');
	wp_enqueue_script('reservation-facile-admin-js', plugins_url( 'js/rf_admin.js', __FILE__ ), array('jquery'),'4.3.1');
	wp_localize_script('reservation-facile-admin-js', 'WPJS', array(
		'pluginsUrl' => plugins_url('',__FILE__),
		'adminAjaxUrl' => admin_url('admin-ajax.php'),
		'rf_GetTexte51' => __('hrs', 'reservation-facile'),
		'rf_GetTexte52' => __('Monday'), 'reservation-facile',
		'rf_GetTexte53' => __('Tuesday', 'reservation-facile'),
		'rf_GetTexte54' => __('Wednesday', 'reservation-facile'),
		'rf_GetTexte55' => __('Thursday', 'reservation-facile'),
		'rf_GetTexte56' => __('Friday', 'reservation-facile'),
		'rf_GetTexte57' => __('Saturday', 'reservation-facile'),
		'rf_GetTexte58' => __('Sunday', 'reservation-facile'),
		'rf_GetTexte59' => __('January', 'reservation-facile'),
		'rf_GetTexte60' => __('February', 'reservation-facile'),
		'rf_GetTexte61' => __('March', 'reservation-facile'),
		'rf_GetTexte62' => __('April', 'reservation-facile'),
		'rf_GetTexte63' => __('May', 'reservation-facile'),
		'rf_GetTexte64' => __('June', 'reservation-facile'),
		'rf_GetTexte65' => __('July', 'reservation-facile'),
		'rf_GetTexte66' => __('August', 'reservation-facile'),
		'rf_GetTexte67' => __('September', 'reservation-facile'),
		'rf_GetTexte68' => __('October', 'reservation-facile'),
		'rf_GetTexte69' => __('November', 'reservation-facile'),
		'rf_GetTexte70' => __('December', 'reservation-facile'),
		'rf_TDelete' => __('Delete', 'reservation-facile'),
		'rf_TConfirmDeleteItem' => __('Are you sure you want to delete this item?', 'reservation-facile'),
		'rf_TPleaseFillLabel' => __('Please fill in the label', 'reservation-facile'),
		'rf_TPleaseFillMaxQty' => __('Please fill in the maximum quantity', 'reservation-facile'),
		'rf_TPleaseFillEndDate' => __('Please fill in the end date', 'reservation-facile'),
		'rf_TFreeSpaces' => __('free place(s)', 'reservation-facile'),
		'rf_TAvailable' => __('Available on', 'reservation-facile'),
		'rf_TUntil' => __('Until', 'reservation-facile'),
		'rf_TRemainingQuantity' => __('Remaining quantity', 'reservation-facile'),
		'rf_TMax' => __('Max. qty', 'reservation-facile'),
		'rf_TAmount' => __('Amount', 'reservation-facile'),
		'rf_TPercentage' => __('Percentage', 'reservation-facile'),
		'rf_TPeriodicity' => __('Periodicity', 'reservation-facile'),
		'rf_TCode' => __('Code', 'reservation-facile'),
		'rf_TDescriptionDetails' => __('Description / Details', 'reservation-facile'),
		'rf_tOptions' => __('Options', 'reservation-facile'),
		'rf_PluginData' => get_plugins()['reservation-facile/rf.php']['Name'],
		'rf_TConfirmSaveChanges' => __('Unsaved changes! Do you want to stay on the page to save them?', 'reservation-facile'),
		'rf_TFillAllFieldsInPosition' => __('Please fill in all fields in position', 'reservation-facile'),
		'rf_TAutomaticQuantity' => __('Automatic quantity', 'reservation-facile'),
		'rf_tUserChoice' => __('User choice', 'reservation-facile'),
		'rf_TOnePerHour' => __('Per booked hour', 'reservation-facile'),
		'rf_TOnePerDay' => __('Per booked day', 'reservation-facile'),
		'rf_TOnePerNight' => __('Per booked night', 'reservation-facile'),
		'rf_TOnePerWeek' => __('Per booked week', 'reservation-facile'),
		'rf_TOnePerMonth' => __('Per booked month', 'reservation-facile'),
		'rf_Closed' => __('Closed', 'reservation-facile'),
		'rf_AllAvailable' => __('All available', 'reservation-facile'),
		'rf_Available' => __('Available', 'reservation-facile'),
		'rf_Unavailable' => __('Unavailable', 'reservation-facile'),
		'rf_Arrival' => __('Arrival', 'reservation-facile'),
		'rf_Departure' => __('Departure', 'reservation-facile'),
		'rf_BookingUnavailable' => __('Booking unavailable', 'reservation-facile'),
		'rf_spaces' => __('spaces', 'reservation-facile'),
		'rf_space' => __('space', 'reservation-facile'),
		'rf_OpeningTime' => __('Opening time', 'reservation-facile'),
		'rf_AllowedArrivalTime' => __('Allowed arrival time', 'reservation-facile'),
		'rf_RequestedDepartureTime' => __('Requested departure time', 'reservation-facile'),
		));
}

function rf_load_admin_css(){
	if (!current_user_can('administrator')){return;}
	wp_enqueue_style('reservation-facile-admin-css', plugins_url('css/rf_admin.css', __FILE__));
}

function rf_menu_html(){
	global $rf_display_menu;
	global $rf_help;
	global $rf_act;
	global $rf_linkCSV;
	global $SAFE_DATA;
	global $rf_spaceName;
	global $echo;
	global $wpdb;
	if (!$rf_display_menu){return;}
	//Security check made in rf_process_action_reservations()
	if (!isset($SAFE_DATA['rf_idSpace'])){$SAFE_DATA['rf_idSpace'] = -1;}

	echo '<div id="rf_content"><div id="rf_content1"><h1 id="rf_mainPluginTitle">';
	echo '<a href="'.admin_url('admin.php?page=reservation-facile').'"><img src="'.plugins_url( 'img/logo55.png', __FILE__ ).'"></a><span>';
	if ($rf_act != ''){
		if (isset($SAFE_DATA["rf_prevAct"])){
			echo '<form action="" method="post" name="retourEmplacement">
				<input type="hidden" name="rf_act" value="'.$SAFE_DATA["rf_prevAct"].'">';
			wp_nonce_field($SAFE_DATA["rf_prevAct"]);
			echo '<input type="hidden" name="rf_idSpace" value="'.$SAFE_DATA["rf_idSpace"].'">
				<input type="hidden" name="id_lieu" value="'.$SAFE_DATA["id_lieu"].'">
				<input type="hidden" name="calendarCurrentYearMonth" value="'.$SAFE_DATA["calendarCurrentYearMonth"].'">
				</form>';
			echo '<a href="#" onclick="document.forms[\'retourEmplacement\'].submit()"><img class="emoji" src="'.plugins_url( 'img/arrow.svg', __FILE__ ).'"></a> ';
		}else{
			$url = admin_url('admin.php?page=reservation-facile');
			echo ' <a href="'.$url.'"><img class="emoji" src="'.plugins_url( 'img/arrow.svg', __FILE__ ).'"></a> ';
		}
	}
	
	if ($rf_spaceName != ''){
		echo '<span>'.rf_removeslashes($rf_spaceName).'</span></span>';
	}else{
		echo '<span>'.get_admin_page_title().'</span></span>';
	}
	echo '</h1>';
	echo '<div class="rf_wrap">'.rf_displayAdminNotice(1).'</div>';
	
	$emplacements = $wpdb->get_results($wpdb->prepare("SELECT emp.id,emp.label,lieu.nom,emp.nb_de_place,emp.prix_de_la_place,emp.email_notification,%s FROM {$wpdb->prefix}rf_spaces emp INNER JOIN {$wpdb->prefix}rf_locations lieu ON lieu.id=emp.id_lieu ORDER BY emp.id DESC",'none'));
	if (sizeof($emplacements) == 0){	
		$rf_help = 'add_location';
		echo '<div id="rf_welcome">';
		echo '<div id="rf_welcome_en"><div>
			<h3>'.__('Welcome to the Réservation Facile plugin','reservation-facile').'</h3><br>
			- '.__('To start, follow the green blocks. ','reservation-facile').'<br><br>
			- '.__('If you are new to this plugin, it is advisable to consult the documentation available on the right side of this page or after this message. ','reservation-facile').'<br><br>
			- '.__('Do not hesitate to consult our YouTube channel to view the different possibilities of the plugin depending on your activity.','reservation-facile').'<br><br>
			</div></div>';
		echo '</div>';
	}

	echo '<div class="rf_wrap">';
	$class = 'rf_headBarEdit'; 
	if (sizeof($emplacements) == 1){
		if (($emplacements[0]->prix_de_la_place == 0) && ($emplacements[0]->email_notification == '')){
			$rf_help = 'edit_space';
		}
	}
	echo '<div class="rf_headBar">
			<div><form action="" method="POST"><input type="hidden" name="rf_act" value="newBooking">';
	wp_nonce_field('newBooking');
	echo get_submit_button(__('Add booking', 'reservation-facile')).'</form></div>';
	
	echo '<input type="button" id="rf_btnAddSpace" class="button button-primary button-large '.(($rf_help == 'add_location')? 'rf_help_add_location' : '').'" value="'.__('Add place', 'reservation-facile').'">';
	echo '<input type="button" id="rf_btnEditSpace" class="button button-primary button-large '.(($rf_help == 'edit_space')? 'rf_help_add_location' : '').'" value="'.__('Manage place', 'reservation-facile').'"></div>';
	
	echo '<div id="rf_addSpaceForm"><div class="'.$class.'">';
	
	
	$class = 'rf_addLocationSpace';
	echo '<div class="'.$class.'"><form method="post" action="">';
	echo '<input type="hidden" name="rf_act" value="insertSpace">';
	wp_nonce_field('insertSpace');
	echo '<h2>'.__('Add place','reservation-facile').'</h2>';
	echo '<input type="text" name="nouveau_lieu" required placeholder="'.__('Location', 'reservation-facile').'" value="" list="locationList">
		<input type="text" name="nouveau_emplacement" required placeholder="'.__('Place', 'reservation-facile').'" value="">';
	echo '<div class="rf_buttons">';
	echo get_submit_button(__('Add', 'reservation-facile'));
	echo '<p class="rf_delete submit"><input type="button" id="rf_btnCancelAddSpace" class="button" value="'.__('Cancel', 'reservation-facile').'"></p></div>';
	echo '<datalist id="locationList">';
	$result = $wpdb->get_results($wpdb->prepare("SELECT %s, nom FROM {$wpdb->prefix}rf_locations", 'id'));
	foreach($result as $location){
		echo '<option value="'.$location->nom.'">'.$location->nom.'</option>';
	}
	echo '</datalist></form></div>';
	
	if (($rf_help == 'add_location')){
		echo '<div class="rf_help_add_location">';
		echo __('In "Location", enter where the places you want to propose for the reservation are.', 'reservation-facile') . '<br>';
		echo __('In "Place", enter the kind of place you propose. For example: Rooms, House, Restaurant table...', 'reservation-facile') . '<br>';
		echo '</div>';
	}
	
	echo '</div></div>';

	echo '<div id="rf_editSpaceForm"><div class="rf_headBarEdit">';
		
	$echo3 = ''; $selected = 'selected';
	foreach ($emplacements as $emplacement) {
		$echo3 .= '<option value="'.$emplacement->id.'" '.$selected.'>#'.$emplacement->id.' - '.rf_removeslashes($emplacement->nom).' - '.rf_removeslashes($emplacement->label).'</option>';
		$selected = '';
	}
	
	echo '<div class="rf_addLocationSpace"><form method="post" action="">
	<input type="hidden" name="rf_act" value="displaySpace">			
	<input type="hidden" name="id_lieu" value="-1">';
	wp_nonce_field('displaySpace');
	echo '<h2>'.__('Manage place','reservation-facile').'</h2>';
	echo '<select name="rf_idSpace" class="rf_form rf_selectOverflow" size="10">'.$echo3.'</select>';
	
	echo '<div class="rf_buttons">';
	if (sizeof($emplacements) > 0){	
		echo get_submit_button(__('Manage', 'reservation-facile'));
	}
	echo '<p class="rf_delete submit"><input type="button" id="rf_btnCancelEditSpace" class="button" value="'.__('Cancel', 'reservation-facile').'"></p></div>';
	echo '</form></div>';
	echo '</div></div>';

	echo '<script>rf_initDefaultActionButton()</script>';
	if (rf_isNotEmpty($echo)){
		global $rf_globalDescription;
		echo '<div id="rf_hiddenWPEditor">';
		wp_editor(stripcslashes($rf_globalDescription),'description');
		
		echo '</div>';
		echo $echo;
		echo '<script>if (document.getElementById("rf_wpeditor")){
				var content = document.getElementById("rf_wpeditor").innerHTML;
				document.getElementById("rf_parentWPEditor").innerHTML = "";
				document.getElementById("rf_parentWPEditor").appendChild(document.getElementById("wp-description-wrap"));
			}</script>';
	}else{			
		$month = date("m");
		$year = date("Y");
		$nb_jours = rf_cal_days_in_month($month, $year);
		$filterArrivalDate = "$year-$month-$nb_jours";
		$filterDepartureDate = "$year-$month-01";
		$filterBookingStatus = '';
		echo '<div class="rf_recentBookings">
				
				<div class="rf_headBarEdit">';
		echo '<div><input type="hidden" name="nospace" value="1">';
		wp_nonce_field('chargeCalendrier','rf_mainCalendar');
		echo '<form method="post" action="" name="rf_filterBookings"><input type="hidden" name="rf_act" value="filterBookings"/>';
		echo '<input type="date" placeholder="'.__('Min. departure date', 'reservation-facile').'" name="filterDepartureDate" value="'.$filterDepartureDate.'"> 
			  <input type="date" placeholder="'.__('Max. arrival date', 'reservation-facile').'" name="filterArrivalDate" value="'.$filterArrivalDate.'"> 
			  <select name="filterBookingStatus">
		<option value="">'.__('All booking status', 'reservation-facile').'</option>
		<option value="validationinprogress" '.(($filterBookingStatus == 'validationinprogress')? 'selected':'').'>'.rf_getBookingStatus('validationinprogress').'</option>
		<option value="pendingpayment" '.(($filterBookingStatus == 'pendingpayment')? 'selected':'').'>'.rf_getBookingStatus('pendingpayment').'</option>
		<option value="confirmed" '.(($filterBookingStatus == 'confirmed')? 'selected':'').'>'.rf_getBookingStatus('confirmed').'</option>
		<option value="paid" '.(($filterBookingStatus == 'paid')? 'selected':'').'>'.rf_getBookingStatus('paid').'</option>
		<option value="canceled" '.(($filterBookingStatus == 'canceled')? 'selected':'').'>'.rf_getBookingStatus('canceled').'</option>
		</select>';
		submit_button(__('Show', 'reservation-facile'));
		echo '</form>';
		echo '<script>rf_listenerFilterBookings();</script>';
		echo '<form method="post" action="" name="rf_exportCSV"><input type="hidden" name="rf_act" value="exportCSV">';
		wp_nonce_field('exportCSV');
		echo '<input type="hidden" name="rf_ead" value="1">';
		echo '<input type="hidden" name="rf_edd" value="1">';
		echo '<input type="hidden" name="rf_ebs" value="1">';
		submit_button(__('Export in CSV', 'reservation-facile'));
		echo '</form>';
		echo '<script>rf_listenerExportCSV();</script>';
		echo '</div></div></div>';
		if ($rf_linkCSV != ''){
			echo '<script>window.open("'.plugins_url('exports/' . $rf_linkCSV, __FILE__).'")</script>';
		}		
		echo '<br><br><div class="rf_wrap" id="rf_contentReservations">';
		echo '</div>';	
		echo '<script>rf_initCalendar();</script>';
	}

	
	echo '</div>';
	echo '</div><div id="rf_content2"><div>';
	echo rf_getTutorialsLinks();
	echo '</div></div>';
}
