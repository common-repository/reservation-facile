<?php
	defined( 'ABSPATH' ) or die();
	$display = 'class="rf_masked"';
	$echo .= '<div id="rf_plugin_onglets_principal" '.$display.'><form method="post" action="" name="rf_formAddEditBookingSpace">';	
	$echo .= '<div class="rf_general_panel"><div>
			<input type="hidden" name="rf_act" value="updateSpace"/><input type="hidden" name="id_lieu" value="'.$lieu->id.'"/>';
	
	$echo .= rf_get_wp_nonce_field('updateSpace');
	$echo .= '<input type="hidden" name="rf_idSpace" value="'.$rf_idSpace.'"/>
			<button class="rf_accordion">'.__('Information', 'reservation-facile').'</button>
			<div class="rf_panel"><br>';
	$echo .= '<label><b>'.__('Shortcode', 'reservation-facile').': </b></label><label id="rf_labelShortcode">[rf_shortcode id="'.$rf_idSpace.'"]</label>
				<br><b>'.__('Copy-paste this shortcode into a post or page to display the booking form.', 'reservation-facile').'</b><br><br>';
	global $rf_globalDescription;
	$rf_globalDescription = $description;
	$echo .= '
				<label><b>'.__('Label', 'reservation-facile').': </b><br></label><input type="text" name="label" value="'.rf_removeslashes($label).'" required/>
				<br>'.__('The name displayed to visitors (e.g. "Classic houses", "Mobil-homes", "Guest houses", "Menus XL", "Holiday Home", "Departure for Paris", ...)', 'reservation-facile').'<br><br>
				<label><b>'.__('Number of places', 'reservation-facile').': </b><br></label><input type="number" min="0" step="any" name="nb_de_place" value="'.($nb_de_place+0).'"/>
				<br>'.__('The total number of available places which can be booked at the same time. Put for example "20" if you have 20 places. Leave to zero if you do not want to set a limit. The number of people can be specified by the tenants on the form or in the "Options", for example to differentiate between adults and children or to define a maximum number of people per place.', 'reservation-facile').'<br><br>
				<label><b>'.__('Time unit (in hours)', 'reservation-facile').': </b><br></label><input type="number" step="any" min="0" name="timeUnit" value="'.($timeUnit+0).'"/>
				<br>'.__('For example, if the unit price includes 24hrs rental, set "24". For a day or overnight package, use the "Options". Set "0" for a fixed amount regardless of the booking duration.', 'reservation-facile').'<br><br>
				<label><b>'.__('Min. booking duration (in hours)', 'reservation-facile').': </b><br></label><input type="number" step="any" min="0" name="minBookingDuration" value="'.($minBookingDuration+0).'"/>
				<br>'.__('For example if you rent two hours minimum, in this case put "2". Leave at zero if there is no minimum booking duration.', 'reservation-facile').'<br><br>
				<label><b>'.__('Max. booking duration (in hours)', 'reservation-facile').': </b><br></label><input type="number" step="any" min="0" name="tps_reservation_max_heure" value="'.($tps_reservation_max_heure+0).'"/>
				<br>'.__('For example if you rent one week maximum, in this case put "168". Leave at zero if there is no maximum booking duration.', 'reservation-facile').'<br><br>
				<label><b>'.__('Link to the general Terms of Use', 'reservation-facile').': </b><br></label><input type="text" name="lien_CGU" value="'.rf_removeslashes($lien_CGU).'"/>
				<br>'.__('Link to an internal or external web page presenting your GCU. Leave blank if you do not want to see a link to the general terms of use.', 'reservation-facile').'<br><br>
				<label><b>'.__('Description / Details', 'reservation-facile').': </b><br></label><span id="rf_parentWPEditor"><textarea name="description" rows="7" id="rf_wpeditor">'.rf_removeslashes($description).'</textarea></span>
				<br>'.__('Use this field to describe your property, for example.', 'reservation-facile').'<br><br><br><br><br>
			</div>

			<button class="rf_accordion">'.__('Prices', 'reservation-facile').'</button>
			<div class="rf_panel"><br>
				<label><b>'.__('Unit price of the place', 'reservation-facile').': </b><br></label><input type="number" min="0" step="any" name="prix_de_la_place" value="'.($prix_de_la_place+0).'"/>
				<br>'.__('Unit price of the place. Leave to zero if you do not want to set a price.', 'reservation-facile').'<br><br>
				<label><b>'.__('Currency', 'reservation-facile').': </b><br></label><select name="devise">'.rf_getAllCurrencies($devise).'</select>
				<br>'.__('Currency used to display prices.', 'reservation-facile').'<br><br>
				<label><b>'.__('Deposit requested (amount)', 'reservation-facile').': </b><br></label><input type="number" step="any" min="0" name="acompte_prix" value="'.($acompte_prix+0).'"/>
				<br>'.__('The amount you request for the booking to be confirmed. Leave to zero if you do not ask for a fixed deposit.', 'reservation-facile').'<br><br>
				<label><b>'.__('Deposit requested (percentage)', 'reservation-facile').': </b><br></label><input type="number" step="any" min="0" name="acompte_pourcentage" value="'.($acompte_pourcentage+0).'"/>
				<br>'.__('The percentage of the price you request for the booking to be confirmed. Leave to zero if you do not ask for a percentage deposit.', 'reservation-facile').'<br><br>
				<label><b>'.__('Payment instructions', 'reservation-facile').': </b><br></label><textarea name="payment_instructions" rows="3">'.rf_removeslashes($payment_instructions).'</textarea>
				<br>'.__('Tell your customers the instructions to follow to make the payment: check payable to, IBAN, ...', 'reservation-facile').'<br><br>
			</div>
			</div>';	
		$dayprice = explode('--o--',$dayprice);
		if (isset($dayprice[1])){
			$dayprice[1] = explode(';',$dayprice[1]);
		}else{
			$dayprice[1] = array_fill(0,7,0);
		}
		if (!isset($dayprice[2])){
			$dayprice[2] = '';
		}
		$echo .= '<button class="rf_accordion">'.__('Prices according to days', 'reservation-facile').'</button>
		<div class="rf_panel"><br>
			'.rf_get_wp_nonce_field('addPeriodePrice','rf_mainAddPeriodePrice').'
			
			<input type="checkbox" name="dayprice[]" '.((strpos($dayprice[0],"1") !== false)? "checked" : "").' value="1" id="rf_dayprice1"> <label for="rf_dayprice1" class="rf_priceDay">'.__('Monday', 'reservation-facile').'</label><input type="number" name="dayprice1" min="0" step="any" value="'.$dayprice[1][1].'">
			<input type="checkbox" name="daypriceignoreperiods[]" '.((strpos($dayprice[2],"1") !== false)? "checked" : "").' value="1" id="rf_ignoreperiods1">
			<label for="rf_ignoreperiods1">'.__('Ignore periods','reservation-facile').'</label><br>
			
			<input type="checkbox" name="dayprice[]" '.((strpos($dayprice[0],"2") !== false)? "checked" : "").' value="2" id="rf_dayprice2"> <label for="rf_dayprice2" class="rf_priceDay">'.__('Tuesday', 'reservation-facile').'</label><input type="number" name="dayprice2" min="0" step="any" value="'.$dayprice[1][2].'">
			<input type="checkbox" name="daypriceignoreperiods[]" '.((strpos($dayprice[2],"2") !== false)? "checked" : "").' value="2" id="rf_ignoreperiods2">
			<label for="rf_ignoreperiods2">'.__('Ignore periods','reservation-facile').'</label><br>
			
			<input type="checkbox" name="dayprice[]" '.((strpos($dayprice[0],"3") !== false)? "checked" : "").' value="3" id="rf_dayprice3"> <label for="rf_dayprice3" class="rf_priceDay">'.__('Wednesday', 'reservation-facile').'</label><input type="number" name="dayprice3" min="0" step="any" value="'.$dayprice[1][3].'">
			<input type="checkbox" name="daypriceignoreperiods[]" '.((strpos($dayprice[2],"3") !== false)? "checked" : "").' value="3" id="rf_ignoreperiods3">
			<label for="rf_ignoreperiods3">'.__('Ignore periods','reservation-facile').'</label><br>
			
			<input type="checkbox" name="dayprice[]" '.((strpos($dayprice[0],"4") !== false)? "checked" : "").' value="4" id="rf_dayprice4"> <label for="rf_dayprice4" class="rf_priceDay">'.__('Thursday', 'reservation-facile').'</label><input type="number" name="dayprice4" min="0" step="any" value="'.$dayprice[1][4].'">
			<input type="checkbox" name="daypriceignoreperiods[]" '.((strpos($dayprice[2],"4") !== false)? "checked" : "").' value="4" id="rf_ignoreperiods4">
			<label for="rf_ignoreperiods4">'.__('Ignore periods','reservation-facile').'</label><br>
			
			<input type="checkbox" name="dayprice[]" '.((strpos($dayprice[0],"5") !== false)? "checked" : "").' value="5" id="rf_dayprice5"> <label for="rf_dayprice5" class="rf_priceDay">'.__('Friday', 'reservation-facile').'</label><input type="number" name="dayprice5" min="0" step="any" value="'.$dayprice[1][5].'">
			<input type="checkbox" name="daypriceignoreperiods[]" '.((strpos($dayprice[2],"5") !== false)? "checked" : "").' value="5" id="rf_ignoreperiods5">
			<label for="rf_ignoreperiods5">'.__('Ignore periods','reservation-facile').'</label><br>
			
			<input type="checkbox" name="dayprice[]" '.((strpos($dayprice[0],"6") !== false)? "checked" : "").' value="6" id="rf_dayprice6"> <label for="rf_dayprice6" class="rf_priceDay">'.__('Saturday', 'reservation-facile').'</label><input type="number" name="dayprice6" min="0" step="any" value="'.$dayprice[1][6].'">
			<input type="checkbox" name="daypriceignoreperiods[]" '.((strpos($dayprice[2],"6") !== false)? "checked" : "").' value="6" id="rf_ignoreperiods6">
			<label for="rf_ignoreperiods6">'.__('Ignore periods','reservation-facile').'</label><br>
			
			<input type="checkbox" name="dayprice[]" '.((strpos($dayprice[0],"0") !== false)? "checked" : "").' value="0" id="rf_dayprice7"> <label for="rf_dayprice7" class="rf_priceDay">'.__('Sunday', 'reservation-facile').'</label><input type="number" name="dayprice7" min="0" step="any" value="'.$dayprice[1][0].'">
			<input type="checkbox" name="daypriceignoreperiods[]" '.((strpos($dayprice[2],"0") !== false)? "checked" : "").' value="0" id="rf_ignoreperiods7">
			<label for="rf_ignoreperiods7">'.__('Ignore periods','reservation-facile').'</label><br><br>		
		</div>
		<button class="rf_accordion">'.__('Prices according to periods', 'reservation-facile').'</button>
		<div class="rf_panel"><br>
			'.rf_get_wp_nonce_field('addPeriodePrice','rf_mainAddPeriodePrice').'
			<label><b>'.__('Start date', 'reservation-facile').': </b><br></label><input type="date" id="rf_periodPriceStartDate" value=""><input type="time" id="rf_periodPriceStartTime" value="00:00"><br><br>
			<label><b>'.__('Finish date', 'reservation-facile').': </b><br></label><input type="date" id="rf_periodPriceFinishDate" value=""><input type="time" id="rf_periodPriceFinishTime" value="00:00"><br><br>
			<label><b>'.__('Unit price of the place', 'reservation-facile').': </b><br></label><input type="number" id="rf_periodPrice" min="0" step="any" value="0"><br>
			'.__('You can define new prices here according to different periods', 'reservation-facile').'<br><br>
			<button onclick="rf_ajouterPeriodePrix();return false;" class="button button-primary button-large">'.__('Add period', 'reservation-facile').'</button><br><br>
			<div id="rf_listperiodesprices">
				<label><b>'.__('List of periods','reservation-facile').'</b></label><br><br>';
				$echo .= rf_get_wp_nonce_field('deletePeriod','rf_deletePeriod');
				$tabPeriod = explode('--o--',$periodesprices);
				foreach($tabPeriod as $index => $period){
					if ($period != ''){
						$pp = explode(';',$period);
						$pp[0] = explode(' ',$pp[0]);
						$pp[1] = explode(' ',$pp[1]);
						$echo .= '<div class="rf_periodList" id="rf_period'.$index.'">
									<div>
										<div>
											<input type="date" disabled value="'.$pp[0][0].'">
											<input type="time" disabled value="'.$pp[0][1].'">
										</div>
										<div>
											<input disabled type="date" value="'.$pp[1][0].'">
											<input disabled type="time" value="'.$pp[1][1].'">
										</div>
									</div>
									<div>
										<div>'.__('Price','reservation-facile').': <span class="rf_displayLocalPrice">' . $pp[2] . '</span></div>
									</div>
									<div>
										<div class="rf_delete">
											<span class="rf_deleteAjax" onclick="rf_delete_period(\''.$index.'\',\''.$rf_idSpace.'\',\''.$pp[0][0].' '.$pp[0][1].'\',\''.$pp[1][0].' '.$pp[1][1].'\',\''.$pp[2].'\')">'.__('Delete','reservation-facile').'</span>
										</div>
									</div>
								</div>';
					}
				}
			$echo .= '<br></div>
		</div>';
	$echo .= '</div><div class="rf_general_panel"><div>
		<button class="rf_accordion">'.__('Availability', 'reservation-facile').'</button>
		<div class="rf_panel"><br>
			<label><b>'.__('Start date of availability', 'reservation-facile').': </b><br></label><input type="date" required name="date_debut_reservation" value="'.$date_debut_reservation.'"/>
			<br>'.__('When can people book?', 'reservation-facile').'<br><br>
			<label><b>'.__('End date of availability', 'reservation-facile').': </b><br></label><input type="date" required name="date_fin_reservation" value="'.$date_fin_reservation.'"/>
			<br>'.__('Until when can people book?', 'reservation-facile').'<br><br>
			<label><b>'.__('Days and hours of opening, arrival and departure','reservation-facile').':</b><br></label>
			<select size="7" class="rf_selectOverflow" id="rf_OTDays" style="font-size:13px">
				<option id="rf_OTDay1" rf_day="'.__('Monday', 'reservation-facile').'"></option>
				<option id="rf_OTDay2" rf_day="'.__('Tuesday', 'reservation-facile').'"></option>
				<option id="rf_OTDay3" rf_day="'.__('Wednesday', 'reservation-facile').'"></option>
				<option id="rf_OTDay4" rf_day="'.__('Thursday', 'reservation-facile').'"></option>
				<option id="rf_OTDay5" rf_day="'.__('Friday', 'reservation-facile').'"></option>
				<option id="rf_OTDay6" rf_day="'.__('Saturday', 'reservation-facile').'"></option>
				<option id="rf_OTDay0" rf_day="'.__('Sunday', 'reservation-facile').'"></option>
			</select>
			&#10140;
			<select size="3" class="rf_selectOverflow" id="rf_OTType" style="font-size:13px">
				<option id="rf_OTType0" rf_type="'.__('Open','reservation-facile').'"></option>
				<option id="rf_OTType1" rf_type="'.__('Arrival','reservation-facile').'"></option>
				<option id="rf_OTType2" rf_type="'.__('Departure','reservation-facile').'"></option>
			</select>
			&#10140;
			<div style="display: inline-block;background:#eaeaea;margin:10px 0;padding: 5px;vertical-align: middle;text-align:right;">
				<div style="display: -webkit-box;display: -ms-flexbox;display: -webkit-flex;display: flex;-webkit-box-orient: vertical; -webkit-box-direction: normal; -webkit-flex-direction: column; -ms-flex-direction: column; flex-direction: column;-webkit-box-pack: justify; -webkit-justify-content: space-between; -ms-flex-pack: justify; justify-content: space-between;min-width:350px;min-height: 170px;">
					<div style="display: -webkit-box;display: -ms-flexbox;display: -webkit-flex;display: flex;-webkit-box-pack: justify; -webkit-justify-content: space-between; -ms-flex-pack: justify; justify-content: space-between;">
						<div>
							<input type="checkbox" id="rf_OTOpened">
							<label for="rf_OTOpened" id="rf_OTOpenedLabel">'.__('Open','reservation-facile').'</label>
						</div>
						<div>
							<a href="#" style="text-decoration:none;font-size: 16px;" id="rf_OTBtnAddTime">&#10010;</a>
						</div>
					</div>
					<div id="rf_OTPeriods"></div>
					<input type="button" value="'.__('Duplicate on the whole week','reservation-facile').'" id="rf_OTBtnDuplicate">
				</div>
			</div>
			<br>'.__('Indicate here the days and times your establishment is open. You can also define the days and times during which you accept arrivals, as well as for requested departures. If no items are entered for arrivals / departures, then it is the opening days that determine when arrivals / departures will be made. You can also choose a reference schedule for bookings. Thus, whatever the time of arrival or departure of your customers, this is the reference that will serve as a basis for calculating the duration and price','reservation-facile').'
			<br><br>
			<textarea id="rf_OT" name="openingtimes">'.rf_removeslashes($openingtimes).'</textarea>
			<script>rf_initOpeningTime()</script>
			';
		$echo .= '	
			<label><b>'.__('Interval in minutes for the user', 'reservation-facile').': </b><br></label><input type="number" step="any" min="1" max="60" name="user_minutes_interval" value="'.($user_minutes_interval+0).'"/>
			<br>'.__('For example, put "30" if you want an interval of 30 minutes to let the user choose the start and end time.', 'reservation-facile').'<br><br>
		</div>';
		$echo .= '<button class="rf_accordion">'.__('Exceptional closure', 'reservation-facile').'</button>
		<div class="rf_panel"><br>
			'.rf_get_wp_nonce_field('addExceptionalClosure','rf_mainAddExceptionalClosure').'
			<label><b>'.__('Start date', 'reservation-facile').': </b><br></label><input type="date" id="rf_periodClosureStartDate" value=""><input type="time" id="rf_periodClosureStartTime" value="00:00"><br><br>
			<label><b>'.__('Finish date', 'reservation-facile').': </b><br></label><input type="date" id="rf_periodClosureFinishDate" value=""><input type="time" id="rf_periodClosureFinishTime" value="00:00"><br><br>
			<button onclick="rf_ajouterExceptionalClosure();return false;" class="button button-primary button-large">'.__('Add closure', 'reservation-facile').'</button><br><br>
			<div id="rf_listperiodesclosures">
				<label><b>'.__('List of closures','reservation-facile').'</b></label><br><br>';
				$echo .= rf_get_wp_nonce_field('deletePeriodClosure','rf_deletePeriodClosure');
				$tabPeriod = explode('--o--',$exceptionalclosure);
				foreach($tabPeriod as $index => $period){
					if ($period != ''){
						$pp = explode(';',$period);
						$pp[0] = explode(' ',$pp[0]);
						$pp[1] = explode(' ',$pp[1]);
						$echo .= '<div class="rf_periodListClosure" id="rf_periodclosure'.$index.'">
									<div>
										<div>
											<input type="date" disabled value="'.$pp[0][0].'">
											<input type="time" disabled value="'.$pp[0][1].'">
										</div>
										<div>
											<input disabled type="date" value="'.$pp[1][0].'">
											<input disabled type="time" value="'.$pp[1][1].'">
										</div>
									</div>
									<div>
										<div class="rf_deleteclosure">
											<span class="rf_deleteAjax" onclick="rf_delete_period_closure(\''.$index.'\',\''.$rf_idSpace.'\',\''.$pp[0][0].' '.$pp[0][1].'\',\''.$pp[1][0].' '.$pp[1][1].'\')">'.__('Delete','reservation-facile').'</span>
										</div>
									</div>
								</div>';
					}
				}
			$echo .= '<br></div>
		</div>';
		$echo .= '<button class="rf_accordion">'.__('Booking registration', 'reservation-facile').'</button>
		<div class="rf_panel"><br>
			<label><b>'.__('Default status of a new booking', 'reservation-facile').': </b><br></label><select name="statut_par_defaut_reservation">
			<option value="validationinprogress" '.(($statut_par_defaut_reservation == "validationinprogress")? "selected" : "").'>'.__('Validation in progress', 'reservation-facile').'</option>
			<option value="pendingpayment" '.(($statut_par_defaut_reservation == "pendingpayment")? "selected" : "").'>'.__('Pending payment', 'reservation-facile').'</option>
			<option value="confirmed" '.(($statut_par_defaut_reservation == "confirmed")? "selected" : "").'>'.__('Confirmed', 'reservation-facile').'</option>
			<option value="paid" '.(($statut_par_defaut_reservation == "paid")? "selected" : "").'>'.__('Paid', 'reservation-facile').'</option>
			<option value="canceled" '.(($statut_par_defaut_reservation == "canceled")? "selected" : "").'>'.__('Canceled', 'reservation-facile').'</option>
			</select>
			<br>'.__('Select the initial status of the booking when a customer makes a booking request.', 'reservation-facile').'<br><br>
			
			<label><b>'.__('Receive email', 'reservation-facile').': </b><br></label><select name="notification_email">
			<option value="1" '.(($notification_email == "1")? "selected" : "").'>'.__('Yes', 'reservation-facile').'</option>
			<option value="0" '.(($notification_email == "0")? "selected" : "").'>'.__('No', 'reservation-facile').'</option></select>
			<br>'.__('Receive notification by email of a new booking','reservation-facile').'<br><br>
			<label><b>'.__('Email notification', 'reservation-facile').': </b><br></label><input type="email" name="email_notification" value="'.$email_notification.'"/>
			<br>'.__('Email address receiving bookings requests. If not specified, the default address stored in WordPress will be used.', 'reservation-facile').'<br><br>
		</div>';	
	$echo .= '</div></div>';
	$echo .= '<div class="rf_buttons"><br><div class="rf_pad10">';
	$echo .= get_submit_button(__('Save general information', 'reservation-facile')).'</form>';
	$echo .= '<form action="" method="post" class="rf_delete" onsubmit="return confirm(\''.__('Are you sure you want to delete this place?', 'reservation-facile').'\');"><input type="hidden" name="rf_idSpace" value="'.$rf_idSpace.'"/><input type="hidden" name="rf_act" value="deleteSpace"/>';
	$echo .= rf_get_wp_nonce_field('deleteSpace');
	$echo .= get_submit_button(__('Delete place', 'reservation-facile'),"delete").'</form>';
	$echo .= '</div></div></div>';