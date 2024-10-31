<?php
defined( 'ABSPATH' ) or die();
$echo .= '<div id="rf_plugin_onglets_calendrier"><div><input type="hidden" id="rf_nbDePlace" value="'.$emplacement->nb_de_place.'">';
if (isset($SAFE_DATA["calendarCurrentYearMonth"])){
	$echo .= '<input type="hidden" id="rf_currentMonth" value="'.(substr($SAFE_DATA["calendarCurrentYearMonth"],5,2)-1).'">';
	$echo .= '<input type="hidden" id="rf_currentYear" value="'.substr($SAFE_DATA["calendarCurrentYearMonth"],0,4).'">';
}
$echo .= '<script>rf_initCalendar();</script>
<div id="rf_navBar">
	<form name="when">';
$echo .= rf_get_wp_nonce_field('chargeCalendrier','rf_mainCalendar');
$echo .= '	<table>
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
<div id="rf_calendar"></div>';
$echo .= '<h2>'.__('Arrivals of the week', 'reservation-facile').'</h2>';
$echo .= '<div class="rf_wrap" id="rf_contentReservations"></div>';
$echo .= '</div></div>';