<?php
defined( 'ABSPATH' ) or die();
$echo .= '<div id="rf_plugin_onglets_reductions" class="rf_masked"><div id="rf_reductions_associes" class="rf_general_panel"><div>
		<h3>'.__('Discounts associated with the place', 'reservation-facile').'</h3>';
foreach ($resultats4 as $coupon) {
	if ($coupon->type == 'reduction'){
		$tab_rf_idSpace = explode(",",$coupon->rf_idSpace);
		if (!in_array($rf_idSpace,$tab_rf_idSpace)){$display = "rf_masked";}else{$display = '';}
		$echo .= '
			<button class="rf_accordion '.$display.'" id="rf_reductionSpace'.$coupon->id_modificationprix.'"><a href="#" onclick="rf_desassocieCoupon(this.getAttribute(\'idcoupon\'),\'reduction\')" idcoupon="'.$coupon->id_modificationprix.'"><span class="rf_rotate_arrow_price"><img class="emoji" src="'.plugins_url( 'img/arrow.svg', __FILE__ ).'"></span></a><b>'.ucfirst(rf_removeslashes($coupon->label)).'</b></button>
			<div class="rf_panel" '.''.' id="rf_reductionPanelSpace'.$coupon->id_modificationprix.'"><br>';
		$echo .= '<table>';
		if (rf_isNotEmpty($coupon->date_debut)){ $echo .= '<tr><td><b>'.__('Available on', 'reservation-facile').' </b></td><td><input type="date" disabled value="'.$coupon->date_debut.'"></td></tr>';}
		if (rf_isNotEmpty($coupon->date_fin)){ $echo .= '<tr><td><b>'.__('Until', 'reservation-facile').' </b></td><td><input type="date" disabled value="'.$coupon->date_fin.'"></td></tr>';}
		if (rf_isNotEmpty($coupon->quantite_initiale)){ $echo .= '<tr><td><b>'.__('Remaining quantity', 'reservation-facile').' </b></td><td>'.($coupon->quantite+0).'/'.($coupon->quantite_initiale+0).'</td></tr>';}
		if (rf_isNotEmpty($coupon->montant)){ $echo .= '<tr><td><b>'.__('Discount amount', 'reservation-facile').' </b></td><td class="rf_displayLocalPrice">'.($coupon->montant+0).'</td></tr>';}
		if (rf_isNotEmpty($coupon->pourcentage)){ $echo .= '<tr><td><b>'.__('Discount percentage', 'reservation-facile').' </b></td><td>'.($coupon->pourcentage+0).'%</td></tr>';}
		if (rf_isNotEmpty($coupon->periode_heure)){ $echo .= '<tr><td><b>'.__('Periodicity', 'reservation-facile').' </b></td><td>'.($coupon->periode_heure+0).'h</td></tr>';}
		if (rf_isNotEmpty($coupon->code)){ $echo .= '<tr><td><b>'.__('Code', 'reservation-facile').' </b></td><td>'.rf_removeslashes($coupon->code).'</td></tr>';}
		if (rf_isNotEmpty($coupon->description)){ $echo .= '<tr><td colspan="2"><br><b>'.__('Description / Details', 'reservation-facile').': </b><br>'.rf_removeslashes($coupon->description).'</td></tr>';}
		$echo .= '</table><br></div>';
	}
}	
$echo .= '</div></div><div class="rf_general_panel"><div><h3>'.__('List of all discounts', 'reservation-facile').'</h3>';
foreach ($resultats4 as $coupon) {
	if ($coupon->type == 'reduction'){
		$tab_rf_idSpace = explode(",",$coupon->rf_idSpace);
		if (in_array($rf_idSpace,$tab_rf_idSpace)){$display = "rf_masked";}else{$display = '';}
		$echo .= '
		<button class="rf_accordion '.$display.'" id="rf_reduction'.$coupon->id_modificationprix.'"><a href="#" onclick="rf_associeCoupon(this.getAttribute(\'idcoupon\'),\'reduction\')" idcoupon="'.$coupon->id_modificationprix.'"><span class="rf_no_rotate_arrow_price"><img class="emoji" src="'.plugins_url( 'img/arrow.svg', __FILE__ ).'"></span></a><b>'.ucfirst(rf_removeslashes($coupon->label)).'</b></button>
		<div class="rf_panel" '.''.' id="rf_reductionPanel'.$coupon->id_modificationprix.'"><br>';
		$echo .= '<table>';
		if (rf_isNotEmpty($coupon->date_debut)){ $echo .= '<tr><td><b>'.__('Available on', 'reservation-facile').' </b></td><td><input type="date" disabled value="'.$coupon->date_debut.'"></td></tr>';}
		if (rf_isNotEmpty($coupon->date_fin)){ $echo .= '<tr><td><b>'.__('Until', 'reservation-facile').' </b></td><td><input type="date" disabled value="'.$coupon->date_fin.'"></td></tr>';}
		if (rf_isNotEmpty($coupon->quantite_initiale)){ $echo .= '<tr><td><b>'.__('Remaining quantity', 'reservation-facile').' </b></td><td>'.($coupon->quantite+0).'/'.($coupon->quantite_initiale+0).'</td></tr>';}
		if (rf_isNotEmpty($coupon->montant)){ $echo .= '<tr><td><b>'.__('Discount amount', 'reservation-facile').' </b></td><td>'.($coupon->montant+0).'</td></tr>';}
		if (rf_isNotEmpty($coupon->pourcentage)){ $echo .= '<tr><td><b>'.__('Discount percentage', 'reservation-facile').' </b></td><td>'.($coupon->pourcentage+0).'%</td></tr>';}
		if (rf_isNotEmpty($coupon->periode_heure)){ $echo .= '<tr><td><b>'.__('Periodicity', 'reservation-facile').' </b></td><td>'.($coupon->periode_heure+0).'h</td></tr>';}
		if (rf_isNotEmpty($coupon->code)){ $echo .= '<tr><td><b>'.__('Code', 'reservation-facile').' </b></td><td>'.rf_removeslashes($coupon->code).'</td></tr>';}
		if (rf_isNotEmpty($coupon->description)){ $echo .= '<tr><td colspan="2"><br><b>'.__('Description / Details', 'reservation-facile').': </b><br>'.rf_removeslashes($coupon->description).'</td></tr>';}
		$echo .= '</table><br></div>';
	}
}
$echo .= '<span id="rf_btnAjoutreduction">
<button class="rf_accordion"  id="rf_btnAjoutreductionbtn"><span><img class="emoji" src="'.plugins_url( 'img/add.svg', __FILE__ ).'"></span>'.__('New discount', 'reservation-facile').'</button>
<div class="rf_panel"><br>
	<form action="" method="post">
		<label><b>'.__('Label', 'reservation-facile').':</b></label><br><input type="text" name="label_reduction" required><br>'.__('The name of the discount.', 'reservation-facile').'<br><br>
		<label><b>'.__('Start date', 'reservation-facile').':</b></label><br><input type="date" name="date_debut_reduction" required value="'.date('Y-m-d').'"><br>'.__('Availability date of the discount.', 'reservation-facile').'<br><br>
		<label><b>'.__('End date', 'reservation-facile').':</b></label><br><input type="date" name="date_fin_reduction" required value=""><br>'.__('End date of availability of the discount.', 'reservation-facile').'<br><br>
		<label><b>'.__('Quantity', 'reservation-facile').':</b></label><br><input type="number" step="any" min="0" name="quantite_reduction"><br>'.__('Total number of times the discount can be used. Put 0 for an unlimited quantity.', 'reservation-facile').'<br><br>
		<label><b>'.__('Amount', 'reservation-facile').':</b></label><br><input type="number" step="any" min="0" name="montant_reduction" onblur="rf_emptyPourcentage(\'reduction\')"><br>'.__('Discount amount', 'reservation-facile').'<br><br>
		<label><b>'.__('Percentage', 'reservation-facile').':</b></label><br><input type="number" step="any" min="0" name="pourcentage_reduction" onblur="rf_emptyMontant(\'reduction\')"><br>'.__('Discount percentage', 'reservation-facile').'<br><br>
		<label><b>'.__('Periodicity of the discount (in hours)', 'reservation-facile').':</b></label><br><input type="number" step="any" min="0" name="periode_heure_reduction"><br>'.__('Set 0 for a fixed amount / percentage discount or, for example, set "24" for a discount applied for each day booked.', 'reservation-facile').'<br><br>
		<label><b>'.__('Code', 'reservation-facile').':</b></label><br><input type="text" name="code_reduction"><br>'.__('Code to enter to benefit from the discount. Leave empty for an automatic discount.', 'reservation-facile').'<br><br>
		<label><b>'.__('Description / Details', 'reservation-facile').':</b></label><br><textarea name="description_reduction"></textarea><br><br>
		<button onclick="rf_ajouterCoupon(\'reduction\');return false;" class="button button-primary button-large">'.__('Add discount', 'reservation-facile').'</button><br><br>
	</form>
</div></span>';
$echo .= '</div></div></div>';