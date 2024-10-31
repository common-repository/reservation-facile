<?php
defined( 'ABSPATH' ) or die();
$echo .= '<form method="post" action="">';
$echo .= '<input type="hidden" name="rf_act" value="updateInfoShowSpace"/>';
$echo .= rf_get_wp_nonce_field('updateInfoShowSpace'); 
$echo .= '<input type="hidden" name="rf_idSpace" value="'.$rf_idSpace.'"/>
	<div id="rf_plugin_onglets_formulaire" class="rf_masked">
		<div style="width:100%">
		<div class="rf_general_panel">
			<div>
				<button class="rf_accordion">'.__('Information', 'reservation-facile').'</button>
				<div class="rf_panel"><br>
					<table>
						<tr><td>
							<input type="checkbox" name="info_date_debut_reservation" id="rf_info_date_debut_reservation" '.(($info_date_debut_reservation > 0)?'checked':'').'><label for="rf_info_date_debut_reservation">'.__('Start date of availability', 'reservation-facile').'</label>
							</td><td>
							<input type="checkbox" name="info_date_debut_reservation_obligatoire" id="rf_info_date_debut_reservation_obligatoire" '.(($info_date_debut_reservation > 1)?'checked':'').'><label for="rf_info_date_debut_reservation_obligatoire">'.__('Required', 'reservation-facile').'</label>
						</td></tr>
						<tr><td>
							<input type="checkbox" name="info_date_fin_reservation" id="rf_info_date_fin_reservation" '.(($info_date_fin_reservation > 0)?'checked':'').'><label for="rf_info_date_fin_reservation">'.__('End date of availability', 'reservation-facile').'</label>
							</td><td>
							<input type="checkbox" name="info_date_fin_reservation_obligatoire" id="rf_info_date_fin_reservation_obligatoire" '.(($info_date_fin_reservation > 1)?'checked':'').'><label for="rf_info_date_fin_reservation_obligatoire">'.__('Required', 'reservation-facile').'</label>
						</td></tr>
						<tr><td>
							<input type="checkbox" name="info_prix_de_la_place" id="rf_info_prix_de_la_place" '.(($info_prix_de_la_place > 0)?'checked':'').'><label for="rf_info_prix_de_la_place">'.__('Price of the place', 'reservation-facile').'</label>
							</td><td>
							<input type="checkbox" name="info_prix_de_la_place_obligatoire" id="rf_info_prix_de_la_place_obligatoire" '.(($info_prix_de_la_place > 1)?'checked':'').'><label for="rf_info_prix_de_la_place_obligatoire">'.__('Required', 'reservation-facile').'</label>
						</td></tr>
						<tr><td>
							<input type="checkbox" name="info_acompte_prix" id="rf_info_acompte_prix" '.(($info_acompte_prix > 0)?'checked':'').'><label for="rf_info_acompte_prix">'.__('Deposit (Price)', 'reservation-facile').'</label>
							</td><td>
							<input type="checkbox" name="info_acompte_prix_obligatoire" id="rf_info_acompte_prix_obligatoire" '.(($info_acompte_prix > 1)?'checked':'').'><label for="rf_info_acompte_prix_obligatoire">'.__('Required', 'reservation-facile').'</label>
						</td></tr>
						<tr><td>
							<input type="checkbox" name="info_acompte_pourcentage" id="rf_info_acompte_pourcentage" '.(($info_acompte_pourcentage > 0)?'checked':'').'><label for="rf_info_acompte_pourcentage">'.__('Deposit (Percentage)', 'reservation-facile').'</label>
							</td><td>
							<input type="checkbox" name="info_acompte_pourcentage_obligatoire" id="rf_info_acompte_pourcentage_obligatoire" '.(($info_acompte_pourcentage > 1)?'checked':'').'><label for="rf_info_acompte_pourcentage_obligatoire">'.__('Required', 'reservation-facile').'</label>
						</td></tr>
						<tr><td>
							<input type="checkbox" name="info_timeUnit" id="rf_info_timeUnit" '.(($info_timeUnit > 0)?'checked':'').'><label for="rf_info_timeUnit">'.__('Time unit', 'reservation-facile').'</label>
							</td><td>
							<input type="checkbox" name="info_timeUnit_obligatoire" id="rf_info_timeUnit_obligatoire" '.(($info_timeUnit > 1)?'checked':'').'><label for="rf_info_timeUnit_obligatoire">'.__('Required', 'reservation-facile').'</label>
						</td></tr>
						<tr><td>
							<input type="checkbox" name="info_minBookingDuration" id="rf_info_minBookingDuration" '.(($info_minBookingDuration > 0)?'checked':'').'><label for="rf_info_minBookingDuration">'.__('Min. booking duration', 'reservation-facile').'</label>
							</td><td>
							<input type="checkbox" name="info_minBookingDuration_obligatoire" id="rf_info_minBookingDuration_obligatoire" '.(($info_minBookingDuration > 1)?'checked':'').'><label for="rf_info_minBookingDuration_obligatoire">'.__('Required', 'reservation-facile').'</label>
						</td></tr>
						<tr><td>
							<input type="checkbox" name="info_tps_reservation_max_heure" id="rf_info_tps_reservation_max_heure" '.(($info_tps_reservation_max_heure > 0)?'checked':'').'><label for="rf_info_tps_reservation_max_heure">'.__('Max. booking duration', 'reservation-facile').'</label>
							</td><td>
							<input type="checkbox" name="info_tps_reservation_max_heure_obligatoire" id="rf_info_tps_reservation_max_heure_obligatoire" '.(($info_tps_reservation_max_heure > 1)?'checked':'').'><label for="rf_info_tps_reservation_max_heure_obligatoire">'.__('Required', 'reservation-facile').'</label>
						</td></tr>
						<tr><td>
							<input type="checkbox" name="info_description" id="rf_info_description" '.(($info_description > 0)?'checked':'').'><label for="rf_info_description">'.__('Description / Details', 'reservation-facile').'</label>
							</td><td>
							<input type="checkbox" name="info_description_obligatoire" id="rf_info_description_obligatoire" '.(($info_description > 1)?'checked':'').'><label for="rf_info_description_obligatoire">'.__('Required', 'reservation-facile').'</label>
						</td></tr>
						<tr><td>
							<input type="checkbox" name="info_calendrier" id="rf_info_calendrier" '.(($info_calendrier > 0)?'checked':'').'><label for="rf_info_calendrier">'.__('Calendar', 'reservation-facile').'</label>
							</td><td>
							<input type="checkbox" name="info_calendrier_obligatoire" id="rf_info_calendrier_obligatoire" '.(($info_calendrier > 1)?'checked':'').'><label for="rf_info_calendrier_obligatoire">'.__('Required', 'reservation-facile').'</label>
						</td></tr>
						<tr><td colspan="2"><br>'.__('If an information is checked and "Required" is not checked, then the information is displayed only if it is relevant (different from zero, date not exceeded). To display the information in all cases, check "Required".', 'reservation-facile').'</td></tr>
					</table>
					<br>
				</div>
			</div>
		</div>
		<div class="rf_general_panel">
			<div>
				<button class="rf_accordion">'.__('Form', 'reservation-facile').'</button>
				<div class="rf_panel"><br>
					<table>
						<tr><td>
							<input type="checkbox" name="show_form_nb_de_place" id="rf_form_nb_de_place" '.(($form_nb_de_place > 0)?'checked':'').'><label for="rf_form_nb_de_place">'.__('Number of places', 'reservation-facile').'</label>
							</td><td>
							<input type="checkbox" name="show_form_nb_de_place_obligatoire" id="rf_form_nb_de_place_obligatoire" '.(($form_nb_de_place > 1)?'checked':'').'><label for="rf_form_nb_de_place_obligatoire">'.__('Required', 'reservation-facile').'</label>
						</td></tr>
						<tr class="rf_masked"><td>
							<input type="checkbox" name="show_form_date_debut" id="rf_form_date_debut" '.(($form_date_debut > 0)?'checked':'').'><label for="rf_form_date_debut">'.__('Booking start date', 'reservation-facile').'</label>
							</td><td>
							<input type="checkbox" name="show_form_date_debut_obligatoire" id="rf_form_date_debut_obligatoire" '.(($form_date_debut > 1)?'checked':'').'><label for="rf_form_date_debut_obligatoire">'.__('Required', 'reservation-facile').'</label>
						</td></tr>
						<tr><td>
							<input type="checkbox" name="show_form_heure_debut" id="rf_form_heure_debut" '.(($form_heure_debut > 0)?'checked':'').'><label for="rf_form_heure_debut">'.__('Booking start time', 'reservation-facile').'</label>
							</td><td>
							<input type="checkbox" name="show_form_heure_debut_obligatoire" id="rf_form_heure_debut_obligatoire" '.(($form_heure_debut > 1)?'checked':'').'><label for="rf_form_heure_debut_obligatoire">'.__('Required', 'reservation-facile').'</label>
						</td></tr>
						<tr class="rf_masked"><td>
							<input type="checkbox" name="show_form_date_fin" id="rf_form_date_fin" '.(($form_date_fin > 0)?'checked':'').'><label for="rf_form_date_fin">'.__('Booking end date', 'reservation-facile').'</label>
							</td><td>
							<input type="checkbox" name="show_form_date_fin_obligatoire" id="rf_form_date_fin_obligatoire" '.(($form_date_fin > 1)?'checked':'').'><label for="rf_form_date_fin_obligatoire">'.__('Required', 'reservation-facile').'</label>
						</td></tr>
						<tr><td>
							<input type="checkbox" name="show_form_heure_fin" id="rf_form_heure_fin" '.(($form_heure_fin > 0)?'checked':'').'><label for="rf_form_heure_fin">'.__('Booking end time', 'reservation-facile').'</label>
							</td><td>
							<input type="checkbox" name="show_form_heure_fin_obligatoire" id="rf_form_heure_fin_obligatoire" '.(($form_heure_fin > 1)?'checked':'').'><label for="rf_form_heure_fin_obligatoire">'.__('Required', 'reservation-facile').'</label>
						</td></tr>
						<tr><td>
							<input type="checkbox" name="show_form_personnes" id="rf_form_personnes" '.(($form_personnes > 0)?'checked':'').'><label for="rf_form_personnes">'.__('Number of people', 'reservation-facile').'</label>
							</td><td>
							<input type="checkbox" name="show_form_personnes_obligatoire" id="rf_form_personnes_obligatoire" '.(($form_personnes > 1)?'checked':'').'><label for="rf_form_personnes_obligatoire">'.__('Required', 'reservation-facile').'</label>
						</td></tr>
						<tr><td>
							<input type="checkbox" name="show_form_nom" id="rf_form_nom" '.(($form_nom > 0)?'checked':'').'><label for="rf_form_nom">'.__('Last name', 'reservation-facile').'</label>
							</td><td>
							<input type="checkbox" name="show_form_nom_obligatoire" id="rf_form_nom_obligatoire" '.(($form_nom > 1)?'checked':'').'><label for="rf_form_nom_obligatoire">'.__('Required', 'reservation-facile').'</label>
						</td></tr>
						<tr><td>
							<input type="checkbox" name="show_form_prenom" id="rf_form_prenom" '.(($form_prenom > 0)?'checked':'').'><label for="rf_form_prenom">'.__('First name', 'reservation-facile').'</label>
							</td><td>
							<input type="checkbox" name="show_form_prenom_obligatoire" id="rf_form_prenom_obligatoire" '.(($form_prenom > 1)?'checked':'').'><label for="rf_form_prenom_obligatoire">'.__('Required', 'reservation-facile').'</label>
						</td></tr>
						<tr><td>
							<input type="checkbox" name="show_form_adresse" id="rf_form_adresse" '.(($form_adresse > 0)?'checked':'').'><label for="rf_form_adresse">'.__('Address', 'reservation-facile').'</label>
							</td><td>
							<input type="checkbox" name="show_form_adresse_obligatoire" id="rf_form_adresse_obligatoire" '.(($form_adresse > 1)?'checked':'').'><label for="rf_form_adresse_obligatoire">'.__('Required', 'reservation-facile').'</label>
						</td></tr>
						<tr><td>
							<input type="checkbox" name="show_form_code_postal" id="rf_form_code_postal" '.(($form_code_postal > 0)?'checked':'').'><label for="rf_form_code_postal">'.__('Zip code', 'reservation-facile').'</label>
							</td><td>
							<input type="checkbox" name="show_form_code_postal_obligatoire" id="rf_form_code_postal_obligatoire" '.(($form_code_postal > 1)?'checked':'').'><label for="rf_form_code_postal_obligatoire">'.__('Required', 'reservation-facile').'</label>
						</td></tr>
						<tr><td>
							<input type="checkbox" name="show_form_ville" id="rf_form_ville" '.(($form_ville > 0)?'checked':'').'><label for="rf_form_ville">'.__('City', 'reservation-facile').'</label>
							</td><td>
							<input type="checkbox" name="show_form_ville_obligatoire" id="rf_form_ville_obligatoire" '.(($form_ville > 1)?'checked':'').'><label for="rf_form_ville_obligatoire">'.__('Required', 'reservation-facile').'</label>
						</td></tr>
						<tr><td>
							<input type="checkbox" name="show_form_pays" id="rf_form_pays" '.(($form_pays > 0)?'checked':'').'><label for="rf_form_pays">'.__('Country', 'reservation-facile').'</label>
							</td><td>
							<input type="checkbox" name="show_form_pays_obligatoire" id="rf_form_pays_obligatoire" '.(($form_pays > 1)?'checked':'').'><label for="rf_form_pays_obligatoire">'.__('Required', 'reservation-facile').'</label>
						</td></tr>
						<tr><td>
							<input type="checkbox" name="show_form_email" id="rf_form_email" '.(($form_email > 0)?'checked':'').'><label for="rf_form_email">'.__('Email', 'reservation-facile').'</label>
							</td><td>
							<input type="checkbox" name="show_form_email_obligatoire" id="rf_form_email_obligatoire" '.(($form_email > 1)?'checked':'').'><label for="rf_form_email_obligatoire">'.__('Required', 'reservation-facile').'</label>
						</td></tr>
						<tr><td>
							<input type="checkbox" name="show_form_telephone" id="rf_form_telephone" '.(($form_telephone > 0)?'checked':'').'><label for="rf_form_telephone">'.__('Phone', 'reservation-facile').'</label>
							</td><td>
							<input type="checkbox" name="show_form_telephone_obligatoire" id="rf_form_telephone_obligatoire" '.(($form_telephone > 1)?'checked':'').'><label for="rf_form_telephone_obligatoire">'.__('Required', 'reservation-facile').'</label>
						</td></tr>
						<tr><td>
							<input type="checkbox" name="show_form_remarques" id="rf_form_remarques" '.(($form_remarques > 0)?'checked':'').'><label for="rf_form_remarques">'.__('Notes', 'reservation-facile').'</label>
							</td><td>
							<input type="checkbox" name="show_form_remarques_obligatoire" id="rf_form_remarques_obligatoire" '.(($form_remarques > 1)?'checked':'').'><label for="rf_form_remarques_obligatoire">'.__('Required', 'reservation-facile').'</label>
						</td></tr>
						<tr><td colspan="2"><br>'.__('If "Required" is checked, then the field must be filled in to validate the booking.', 'reservation-facile').'</td></tr>
					</table>
					<br>
				</div>
			</div>	
		</div>
		</div>
		<div class="rf_buttons"><div class="rf_pad10">'.get_submit_button(__('Save display', 'reservation-facile')).'</div></div>
		</form>
	</div>	
';