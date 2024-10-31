<?php
defined( 'ABSPATH' ) or die();
$echo .= '<div id="rf_plugin_onglets_coupons" class="rf_masked"><div id="rf_coupons_associes" class="rf_general_panel"><div>
			<h3>'.__('Coupons associated with the place', 'reservation-facile').'</h3>';
$echo .= rf_get_wp_nonce_field('desassocieCoupon','rf_mainDesassocieCoupon');
foreach ($resultats4 as $coupon) {
	if ($coupon->type == 'coupon'){
		$tab_rf_idSpace = explode(",",$coupon->rf_idSpace);
		if (!in_array($rf_idSpace,$tab_rf_idSpace)){$display = "rf_masked";}else{$display = '';}
		$echo .= '
			<button class="rf_accordion '.$display.'" id="rf_couponSpace'.$coupon->id_modificationprix.'"><a href="#" onclick="rf_desassocieCoupon(this.getAttribute(\'idcoupon\'),\'coupon\')" idcoupon="'.$coupon->id_modificationprix.'"><span class="rf_rotate_arrow_price"><img class="emoji" src="'.plugins_url( 'img/arrow.svg', __FILE__ ).'"></span></a><b>'.ucfirst(rf_removeslashes($coupon->label)).'</b></button>
			<div class="rf_panel" id="rf_couponPanelSpace'.$coupon->id_modificationprix.'"><br>';
		$echo .= '<table>';	
		if (rf_isNotEmpty($coupon->date_debut)){ $echo .= '<tr><td><b>'.__('Available on', 'reservation-facile').' </b></td><td><input type="date" disabled value="'.$coupon->date_debut.'"></td></tr>';}
		if (rf_isNotEmpty($coupon->date_fin)){ $echo .= '<tr><td><b>'.__('Until', 'reservation-facile').' </b></td><td><input type="date" disabled value="'.$coupon->date_fin.'"></td></tr>';}
		if (rf_isNotEmpty($coupon->quantite_initiale)){ $echo .= '<tr><td><b>'.__('Remaining quantity', 'reservation-facile').' </b></td><td>'.($coupon->quantite+0).'/'.($coupon->quantite_initiale+0).'</td></tr>';}
		if (rf_isNotEmpty($coupon->montant)){ $echo .= '<tr><td><b>'.__('Amount of the coupon.', 'reservation-facile').' </b></td><td class="rf_displayLocalPrice">'.($coupon->montant+0).'</td></tr>';}
		if (rf_isNotEmpty($coupon->pourcentage)){ $echo .= '<tr><td><b>'.__('Percentage of the coupon.', 'reservation-facile').' </b></td><td>'.($coupon->pourcentage+0).'%</td></tr>';}
		if (rf_isNotEmpty($coupon->periode_heure)){ $echo .= '<tr><td><b>'.__('Periodicity', 'reservation-facile').' </b></td><td>'.($coupon->periode_heure+0).'h</td></tr>';}
		if (rf_isNotEmpty($coupon->code)){ $echo .= '<tr><td><b>'.__('Code', 'reservation-facile').' </b></td><td>'.rf_removeslashes($coupon->code).'</td></tr>';}
		if (rf_isNotEmpty($coupon->description)){ $echo .= '<tr><td colspan="2"><br><b>'.__('Description / Details', 'reservation-facile').': </b><br>'.rf_removeslashes($coupon->description).'</td></tr>';}
		$echo .= '</table><br></div>';
	}
}		
$echo .= '</div></div><div class="rf_general_panel"><div><h3>'.__('List of all coupons', 'reservation-facile').'</h3>';
$echo .= rf_get_wp_nonce_field('associeCoupon','rf_mainAssocieCoupon');
foreach ($resultats4 as $coupon) {
	if ($coupon->type == 'coupon'){
		$tab_rf_idSpace = explode(",",$coupon->rf_idSpace);
		if (in_array($rf_idSpace,$tab_rf_idSpace)){$display = "rf_masked";}else{$display = '';}
		$echo .= '
		<button class="rf_accordion '.$display.'" id="rf_coupon'.$coupon->id_modificationprix.'"><a href="#" onclick="rf_associeCoupon(this.getAttribute(\'idcoupon\'),\'coupon\')" idcoupon="'.$coupon->id_modificationprix.'"><span class="rf_no_rotate_arrow_price"><img class="emoji" src="'.plugins_url( 'img/arrow.svg', __FILE__ ).'"></span></a><b>'.ucfirst(rf_removeslashes($coupon->label)).'</b></button>
		<div class="rf_panel" '.''.' id="rf_couponPanel'.$coupon->id_modificationprix.'"><br>';
		$echo .= '<table>';
		if (rf_isNotEmpty($coupon->date_debut)){ $echo .= '<tr><td><b>'.__('Available on', 'reservation-facile').' </b></td><td><input type="date" disabled value="'.$coupon->date_debut.'"></td></tr>';}
		if (rf_isNotEmpty($coupon->date_fin)){ $echo .= '<tr><td><b>'.__('Until', 'reservation-facile').' </b></td><td><input type="date" disabled value="'.$coupon->date_fin.'"></td></tr>';}
		if (rf_isNotEmpty($coupon->quantite_initiale)){ $echo .= '<tr><td><b>'.__('Remaining quantity', 'reservation-facile').' </b></td><td>'.($coupon->quantite+0).'/'.($coupon->quantite_initiale+0).'</td></tr>';}
		if (rf_isNotEmpty($coupon->montant)){ $echo .= '<tr><td><b>'.__('Amount of the coupon.', 'reservation-facile').' </b></td><td>'.($coupon->montant+0).'</td></tr>';}
		if (rf_isNotEmpty($coupon->pourcentage)){ $echo .= '<tr><td><b>'.__('Percentage of the coupon.', 'reservation-facile').' </b></td><td>'.($coupon->pourcentage+0).'%</td></tr>';}
		if (rf_isNotEmpty($coupon->periode_heure)){ $echo .= '<tr><td><b>'.__('Periodicity', 'reservation-facile').' </b></td><td>'.($coupon->periode_heure+0).'h</td></tr>';}
		if (rf_isNotEmpty($coupon->code)){ $echo .= '<tr><td><b>'.__('Code', 'reservation-facile').' </b></td><td>'.rf_removeslashes($coupon->code).'</td></tr>';}
		if (rf_isNotEmpty($coupon->description)){ $echo .= '<tr><td colspan="2"><br><b>'.__('Description / Details', 'reservation-facile').': </b><br>'.rf_removeslashes($coupon->description).'</td></tr>';}
		$echo .= '</table><br></div>';
	}
}
$echo .= '<span id="rf_btnAjoutcoupon">
<button class="rf_accordion" id="rf_btnAjoutcouponbtn"><span><img class="emoji" src="'.plugins_url( 'img/add.svg', __FILE__ ).'"></span>'.__('New Coupon', 'reservation-facile').'</button>
<div class="rf_panel"><br>
	<form action="" method="post">';
$echo .= rf_get_wp_nonce_field('ajouterCoupon','rf_mainAjouterCoupon');
$echo .= '<label><b>'.__('Label', 'reservation-facile').':</b></label><br><input type="text" name="label_coupon" required><br>'.__('The name of the coupon.', 'reservation-facile').'<br><br>
		<label><b>'.__('Start date', 'reservation-facile').':</b></label><br><input type="date" name="date_debut_coupon" required value="'.date('Y-m-d').'"><br>'.__('Availability date of the coupon.', 'reservation-facile').'<br><br>
		<label><b>'.__('End date', 'reservation-facile').':</b></label><br><input type="date" name="date_fin_coupon" required value=""><br>'.__('End of availability date of the coupon.', 'reservation-facile').'<br><br>
		<label><b>'.__('Quantity', 'reservation-facile').':</b></label><br><input type="number" min="0" name="quantite_coupon"><br>'.__('Total number of times the coupon can be used. Put 0 for an unlimited quantity.', 'reservation-facile').'<br><br>
		<label><b>'.__('Amount', 'reservation-facile').':</b></label><br><input type="number" step="any" min="0" name="montant_coupon" onblur="rf_emptyPourcentage(\'coupon\')"><br>'.__('Amount of the coupon.', 'reservation-facile').'<br><br>
		<label><b>'.__('Percentage', 'reservation-facile').':</b></label><br><input type="number" step="any" min="0" name="pourcentage_coupon" onblur="rf_emptyMontant(\'coupon\')"><br>'.__('Percentage of the coupon.', 'reservation-facile').'<br><br>
		<label><b>'.__('Periodicity of the coupon (in hours)', 'reservation-facile').':</b></label><br><input type="number" step="any" min="0" name="periode_heure_coupon"><br>'.__('Set 0 for a fixed amount / percentage coupon or, for example, set "24" for a coupon applied for each day booked.', 'reservation-facile').'<br><br>
		<label><b>'.__('Code', 'reservation-facile').':</b></label><br><input type="text" required name="code_coupon"><br>'.__('Code to enter to benefit from the coupon.', 'reservation-facile').'<br><br>
		<label><b>'.__('Description / Details', 'reservation-facile').':</b></label><br><textarea name="description_coupon"></textarea><br><br>
		<button onclick="rf_ajouterCoupon(\'coupon\');return false;" class="button button-primary button-large">'.__('Add the coupon', 'reservation-facile').'</button><br><br>
	</form>
</div></span>';	
$echo .= '</div></div></div>';