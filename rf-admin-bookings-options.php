<?php
defined( 'ABSPATH' ) or die();
$echo .= '
			<div id="rf_plugin_onglets_options" class="rf_masked"><div id="rf_options_associes" class="rf_general_panel"><div>
				<h3>'.__('Options associated with the place', 'reservation-facile').'</h3>';	
		foreach ($resultats4 as $coupon) {
			if ($coupon->type == 'option'){
				$tab_rf_idSpace = explode(",",$coupon->rf_idSpace);
				if (!in_array($rf_idSpace,$tab_rf_idSpace)){$display = "rf_masked";}else{$display = '';}
				$echo .= '
					<button class="rf_accordion '.$display.'" id="rf_optionSpace'.$coupon->id_modificationprix.'"><a href="#" onclick="rf_desassocieCoupon(this.getAttribute(\'idcoupon\'),\'option\')" idcoupon="'.$coupon->id_modificationprix.'"><span class="rf_rotate_arrow_price"><img class="emoji" src="'.plugins_url( 'img/arrow.svg', __FILE__ ).'"></span></a><b>'.ucfirst(rf_removeslashes($coupon->label)).'</b></button>
					<div class="rf_panel" '.''.' id="rf_optionPanelSpace'.$coupon->id_modificationprix.'"><br>';
				$echo .= '<table>';
				
				if (rf_isNotEmpty($coupon->date_debut)){ $echo .= '<tr><td><b>'.__('Available on', 'reservation-facile').' </b></td><td><input type="date" disabled value="'.$coupon->date_debut.'"></td></tr>';}
				if (rf_isNotEmpty($coupon->date_fin)){ $echo .= '<tr><td><b>'.__('Until', 'reservation-facile').' </b></td><td><input type="date" disabled value="'.$coupon->date_fin.'"></td></tr>';}
				if (rf_isNotEmpty($coupon->quantite_initiale)){ $echo .= '<tr><td><b>'.__('Max. qty', 'reservation-facile').' </b></td><td>'.($coupon->quantite_initiale+0).'</td></tr>';}
				if (rf_isNotEmpty($coupon->code)){ 
					$echo .= '<tr>	<td><b>'.__('Automatic quantity', 'reservation-facile').' </b></td>
						<td><select disabled>
							<option value="userchoice" '.(($coupon->code == 'userchoice')? 'selected' : '' ).'>'.__('User choice','reservation-facile').'</option> 
							<option value="oneperhour" '.(($coupon->code == 'oneperhour')? 'selected' : '' ).'>'.__('Per booked hour','reservation-facile').'</option> 
							<option value="oneperday" '.(($coupon->code == 'oneperday')? 'selected' : '' ).'>'.__('Per booked day','reservation-facile').'</option> 
							<option value="onepernight" '.(($coupon->code == 'onepernight')? 'selected' : '' ).'>'.__('Per booked night','reservation-facile').'</option> 
							<option value="oneperweek" '.(($coupon->code == 'oneperweek')? 'selected' : '' ).'>'.__('Per booked week','reservation-facile').'</option> 
							<option value="onepermonth" '.(($coupon->code == 'onepermonth')? 'selected' : '' ).'>'.__('Per booked month','reservation-facile').'</option> 
						</select></td></tr>';
				}
				if (rf_isNotEmpty($coupon->montant)){ $echo .= '<tr><td><b>'.__('Amount', 'reservation-facile').' </b></td><td class="rf_displayLocalPrice">'.($coupon->montant+0).'</td></tr>';}
				if (rf_isNotEmpty($coupon->pourcentage)){ $echo .= '<tr><td><b>'.__('Percentage', 'reservation-facile').' </b></td><td>'.($coupon->pourcentage+0).'%</td></tr>';}
				if (rf_isNotEmpty($coupon->periode_heure)){ $echo .= '<tr><td><b>'.__('Periodicity', 'reservation-facile').' </b></td><td>'.($coupon->periode_heure+0).'h</td></tr>';}
				
				if (rf_isNotEmpty($coupon->details_texte)){
					$echo .= '<tr><td colspan="2"><b>'.__('Options', 'reservation-facile').': </b><br>';
					$displayDetails = explode("<br />",nl2br(rf_removeslashes($coupon->details_texte)));
					for($i=0;$i<sizeof($displayDetails);$i++){
						$echo .= '#'.($i+1).': ';
						$echo .= $displayDetails[$i];
						$echo .= '<br>';
					}
					$echo .= '</td></tr>';
				}
				if (rf_isNotEmpty($coupon->description)){ $echo .= '<tr><td colspan="2"><br><b>'.__('Description / Details', 'reservation-facile').': </b><br>'.rf_removeslashes($coupon->description).'</td></tr>';}
				$echo .= '</table><br></div>';
			}
		}		
		$echo .= '</div></div><div class="rf_general_panel"><div><h3>'.__('List of all options', 'reservation-facile').'</h3>';
		foreach ($resultats4 as $coupon) {
			if ($coupon->type == 'option'){
				$tab_rf_idSpace = explode(",",$coupon->rf_idSpace);
				if (in_array($rf_idSpace,$tab_rf_idSpace)){$display = "rf_masked";}else{$display = '';}
				$echo .= '
				<button class="rf_accordion '.$display.'" id="rf_option'.$coupon->id_modificationprix.'"><a href="#" onclick="rf_associeCoupon(this.getAttribute(\'idcoupon\'),\'option\')" idcoupon="'.$coupon->id_modificationprix.'"><span class="rf_no_rotate_arrow_price"><img class="emoji" src="'.plugins_url( 'img/arrow.svg', __FILE__ ).'"></span></a><b>'.ucfirst(rf_removeslashes($coupon->label)).'</b></button>
				<div class="rf_panel" '.''.' id="rf_optionPanel'.$coupon->id_modificationprix.'"><br>';
				$echo .= '<table>';
				if (rf_isNotEmpty($coupon->date_debut)){ $echo .= '<tr><td><b>'.__('Available on', 'reservation-facile').' </b></td><td><input type="date" disabled value="'.$coupon->date_debut.'"></td></tr>';}
				if (rf_isNotEmpty($coupon->date_fin)){ $echo .= '<tr><td><b>'.__('Until', 'reservation-facile').' </b></td><td><input type="date" disabled value="'.$coupon->date_fin.'"></td></tr>';}
				if (rf_isNotEmpty($coupon->quantite_initiale)){ $echo .= '<tr><td><b>'.__('Max. qty', 'reservation-facile').' </b></td><td>'.($coupon->quantite_initiale+0).'</td></tr>';}
				if (rf_isNotEmpty($coupon->code)){ 
					$echo .= '<tr>	<td><b>'.__('Automatic quantity', 'reservation-facile').' </b></td>
						<td><select disabled>
							<option value="userchoice" '.(($coupon->code == 'userchoice')? 'selected' : '' ).'>'.__('User choice','reservation-facile').'</option> 
							<option value="oneperhour" '.(($coupon->code == 'oneperhour')? 'selected' : '' ).'>'.__('Per booked hour','reservation-facile').'</option> 
							<option value="oneperday" '.(($coupon->code == 'oneperday')? 'selected' : '' ).'>'.__('Per booked day','reservation-facile').'</option> 
							<option value="onepernight" '.(($coupon->code == 'onepernight')? 'selected' : '' ).'>'.__('Per booked night','reservation-facile').'</option> 
							<option value="oneperweek" '.(($coupon->code == 'oneperweek')? 'selected' : '' ).'>'.__('Per booked week','reservation-facile').'</option> 
							<option value="onepermonth" '.(($coupon->code == 'onepermonth')? 'selected' : '' ).'>'.__('Per booked month','reservation-facile').'</option> 
						</select></td></tr>';
				}
				if (rf_isNotEmpty($coupon->montant)){ $echo .= '<tr><td><b>'.__('Amount', 'reservation-facile').' </b></td><td>'.($coupon->montant+0).'</td></tr>';}
				if (rf_isNotEmpty($coupon->pourcentage)){ $echo .= '<tr><td><b>'.__('Percentage', 'reservation-facile').' </b></td><td>'.($coupon->pourcentage+0).'%</td></tr>';}
				if (rf_isNotEmpty($coupon->periode_heure)){ $echo .= '<tr><td><b>'.__('Periodicity', 'reservation-facile').' </b></td><td>'.($coupon->periode_heure+0).'h</td></tr>';}
				if (rf_isNotEmpty($coupon->details_texte)){
					$echo .= '<tr><td colspan="2"><b>'.__('Options', 'reservation-facile').': </b><br>';
					$displayDetails = explode("<br />",nl2br(rf_removeslashes($coupon->details_texte)));
					for($i=0;$i<sizeof($displayDetails);$i++){
						$echo .= '#'.($i+1).': ';
						$echo .= $displayDetails[$i];
						$echo .= '<br>';
					}
					$echo .= '</td></tr>';
				}
				if (rf_isNotEmpty($coupon->description)){ $echo .= '<tr><td colspan="2"><br><b>'.__('Description / Details', 'reservation-facile').': </b><br>'.rf_removeslashes($coupon->description).'</td></tr>';}
				$echo .= '</table><br></div>';
			}
		}
		$echo .= '<span id="rf_btnAjoutoption">
		<button class="rf_accordion" id="rf_btnAjoutoptionbtn"><span><img class="emoji" src="'.plugins_url( 'img/add.svg', __FILE__ ).'"></span>'.__('New option', 'reservation-facile').'</button>
		<div class="rf_panel"><br>
			<form action="" method="post">
				<label><b>'.__('Label', 'reservation-facile').':</b></label><br><input type="text" name="label_option" required><br>'.__('The name of the option, visible to customers', 'reservation-facile').'<br><br>
				<label><b>'.__('Start date', 'reservation-facile').':</b></label><br><input type="date" name="date_debut_option" required value="'.date('Y-m-d').'"><br>'.__('Availability date for the option.', 'reservation-facile').'<br><br>
				<label><b>'.__('End date', 'reservation-facile').':</b></label><br><input type="date" name="date_fin_option" required value=""><br>'.__('End date of availability for the option.', 'reservation-facile').'<br><br>
				<label><b>'.__('Maximum quantity per booking', 'reservation-facile').':</b></label><br><input type="number" min="0" name="quantite_option" required><br>'.__('Number of times the option can be used per booking.', 'reservation-facile').'<br><br>
				<label><b>'.__('Automatic quantity', 'reservation-facile').':</b></label><br><select name="code_option">
							<option value="userchoice">'.__('User choice','reservation-facile').'</option> 
							<option value="oneperhour">'.__('Per booked hour','reservation-facile').'</option> 
							<option value="oneperday">'.__('Per booked day','reservation-facile').'</option> 
							<option value="onepernight">'.__('Per booked night','reservation-facile').'</option> 
							<option value="oneperweek">'.__('Per booked week','reservation-facile').'</option> 
							<option value="onepermonth">'.__('Per booked month','reservation-facile').'</option> 
						</select><br>'.__('Allows the creation of packages per day or per night. For example, if user booked for 3 days, the option will be applied 3 times.', 'reservation-facile').'<br><br>
				
				<label><b>'.__('Options details', 'reservation-facile').':</b></label><br><textarea rows="5" name="details_texte_option"></textarea><br>'.__('Optional - Enter here the label for each option (1 line per option)', 'reservation-facile').'<br><br>
				<label><b>'.__('Amount', 'reservation-facile').':</b></label><br><input type="number" step="any" min="0" name="montant_option" onblur="rf_emptyPourcentage(\'option\')"><br>'.__('Unit amount of the option.', 'reservation-facile').'<br><br>
				<label><b>'.__('Percentage', 'reservation-facile').':</b></label><br><input type="number" step="any" min="0" name="pourcentage_option" onblur="rf_emptyMontant(\'option\')"><br>'.__('Percentage of the option.', 'reservation-facile').'<br><br>
				<label><b>'.__('Periodicity of the option (in hours)', 'reservation-facile').':</b></label><br><input type="number" step="any" min="0" name="periode_heure_option"><br>'.__('Set 0 for a fixed amount/percentage option or, for example, set "24" for an option applied for each day reserved.', 'reservation-facile').'<br><br>
				
				<label><b>'.__('Description / Details', 'reservation-facile').':</b></label><br><textarea name="description_option"></textarea><br><br>
				<button onclick="rf_ajouterCoupon(\'option\');return false;" class="button button-primary button-large">'.__('Add option', 'reservation-facile').'</button><br><br>
			</form>
		</div></span>';	
		$echo .= '</div></div></div>';