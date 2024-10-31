<?php
defined( 'ABSPATH' ) or die();
//----TEXTS AND LANGUAGE

function rf_load_plugin_textdomain() {
    load_plugin_textdomain( 'reservation-facile', NULL, 'reservation-facile/languages' );
}
add_action( 'plugins_loaded', 'rf_load_plugin_textdomain' );