<?php
/*
* Plugin Name: Réservation Facile
* Plugin URI: https://wordpress.org/plugins/reservation-facile/
* Description: EN/FR/... Manage your bookings with this plugin that easily adapts to hotels, restaurants, camping, gites, trips, events, shows ...
* Version: 1.6.14
* Author: CréaLion.NET
* Author URI: https://crealion.net
* Text Domain: reservation-facile
* Domain Path: /languages
*/
defined( 'ABSPATH' ) or die();
define('WPrf_VERSION','17'); //DB version
$rf_custom_texts = array();
$rf_display_menu = false;
$rf_help = '';
$rf_linkCSV = '';
$rf_act ='';
$SAFE_DATA = [];
$rf_globalDescription = '';
$rf_spaceName = '';
include 'rf-functions.php';
if (rf_getParameter('timezone') != ''){
	date_default_timezone_set(rf_getParameter('timezone'));
}

if (is_admin() === true) {
	register_activation_hook(__FILE__, 'rf_install');
	register_deactivation_hook(__FILE__, 'rf_deactivation');
	register_uninstall_hook(__FILE__, 'rf_uninstall');
	add_action('admin_init','rf_checkUpgrade');
	include 'rf-installation.php';
	include 'rf-admin-ui.php';
	include 'rf-admin-bookings.php';
	include 'rf-admin-shortcodes.php';
	include 'rf-admin-pricechanges.php';
	include 'rf-admin-parameters.php';
	include 'rf-wpajax.php';
}else{
	include 'rf-shortcode.php';
}


function rf_load_front_css(){
	wp_enqueue_style('reservation-facile-front-css', plugins_url('css/rf_front.css', __FILE__));
}

function rf_load_front_js(){
	if ( ! function_exists( 'get_plugins' ) ) {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
	}
	wp_enqueue_script('jquery');
	wp_enqueue_script('jquery-ui');
	wp_enqueue_script('reservation-facile-front-js', plugins_url( 'js/rf_front.js', __FILE__ ), array('jquery'),'4.3.1');
	wp_localize_script('reservation-facile-front-js', 'WPJS', array(
		'pluginsUrl' => plugins_url('',__FILE__),
		'adminAjaxUrl' => admin_url('admin-ajax.php'),
		'rf_GetTexte37' => __('This arrival time is not allowed', 'reservation-facile'),
		'rf_GetTexte38' => __('Thank you for planning your arrival before', 'reservation-facile'),
		'rf_GetTexte39' => __('This departure time is not allowed', 'reservation-facile'),
		'rf_GetTexte40' => __('Thank you for planning your departure before', 'reservation-facile'),
		'rf_GetTexte41' => __('Booking only possible from', 'reservation-facile'),
		'rf_GetTexte42' => __('Booking only possible until', 'reservation-facile'),
		'rf_GetTexte43' => __('Booking not possible before today.', 'reservation-facile'),
		'rf_GetTexte44' => __('Duration of the booking insufficient. Minimum duration', 'reservation-facile'),
		'rf_GetTexte45' => __('Duration of the booking too long. Maximum duration', 'reservation-facile'),
		'rf_GetTexte46' => __('Booking is not possible from', 'reservation-facile'),
		'rf_GetTexte51' => __('hrs', 'reservation-facile'),
		'rf_GetTexte52' => __('Monday', 'reservation-facile'),
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
		'rf_GetTexte75' => __('Arrival is not possible on', 'reservation-facile'),
		'rf_GetTexte76' => __('Departure is not possible on', 'reservation-facile'),
		'rf_TArrivalDateAfterDepartureDate' => __('The arrival date is after the departure date', 'reservation-facile'),
		'rf_PluginData' => get_plugins()['reservation-facile/rf.php']['Name'],
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
