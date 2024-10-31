<?php
defined( 'ABSPATH' ) or die();
$echo .= '<div id="rf_plugin_onglets_taxes" class="rf_masked"><div id="rf_taxes_associes" class="rf_general_panel"><div>
				<h3>'.__('Taxes associated with the place', 'reservation-facile').'</h3>';
		foreach ($resultats4 as $coupon) {
			if ($coupon->type == 'taxe'){
				$tab_rf_idSpace = explode(",",$coupon->rf_idSpace);
				if (!in_array($rf_idSpace,$tab_rf_idSpace)){$display = "rf_masked";}else{$display = '';}
				$echo .= '
					<button class="rf_accordion '.$display.'" id="rf_taxeSpace'.$coupon->id_modificationprix.'"><a href="#" onclick="rf_desassocieCoupon(this.getAttribute(\'idcoupon\'),\'taxe\')" idcoupon="'.$coupon->id_modificationprix.'"><span class="rf_rotate_arrow_price"><img class="emoji" src="'.plugins_url( 'img/arrow.svg', __FILE__ ).'"></span></a><b>'.ucfirst(rf_removeslashes($coupon->label)).'</b></button>
					<div class="rf_panel" '.''.' id="rf_taxePanelSpace'.$coupon->id_modificationprix.'"><br>';		
				$echo .= '<table>';
				if (rf_isNotEmpty($coupon->date_debut)){ $echo .= '<tr><td><b>'.__('Available on', 'reservation-facile').' </b></td><td><input type="date" disabled value="'.$coupon->date_debut.'"></td></tr>';}
				if (rf_isNotEmpty($coupon->date_fin)){ $echo .= '<tr><td><b>'.__('Until', 'reservation-facile').' </b></td><td><input type="date" disabled value="'.$coupon->date_fin.'"></td></tr>';}
				if (rf_isNotEmpty($coupon->quantite_initiale)){ $echo .= '<tr><td><b>'.__('Remaining quantity', 'reservation-facile').' </b></td><td>'.($coupon->quantite+0).'/'.($coupon->quantite_initiale+0).'</td></tr>';}
				if (rf_isNotEmpty($coupon->montant)){ $echo .= '<tr><td><b>'.__('Amount', 'reservation-facile').' </b></td><td class="rf_displayLocalPrice">'.($coupon->montant+0).'</td></tr>';}
				if (rf_isNotEmpty($coupon->pourcentage)){ $echo .= '<tr><td><b>'.__('Percentage', 'reservation-facile').' </b></td><td>'.($coupon->pourcentage+0).'%</td></tr>';}
				if (rf_isNotEmpty($coupon->periode_heure)){ $echo .= '<tr><td><b>'.__('Periodicity', 'reservation-facile').' </b></td><td>'.($coupon->periode_heure+0).'h</td></tr>';}
				if (rf_isNotEmpty($coupon->code)){ $echo .= '<tr><td><b>'.__('Code', 'reservation-facile').' </b></td><td>'.rf_removeslashes($coupon->code).'</td></tr>';}
				if (rf_isNotEmpty($coupon->description)){ $echo .= '<tr><td colspan="2"><br><b>'.__('Description / Details', 'reservation-facile').': </b><br>'.rf_removeslashes($coupon->description).'</td></tr>';}
				$echo .= '</table><br></div>';
			}
		}
		$echo .= '</div></div><div class="rf_general_panel"><div><h3>'.__('List of all taxes', 'reservation-facile').'</h3>';
		foreach ($resultats4 as $coupon) {
			if ($coupon->type == 'taxe'){
				$tab_rf_idSpace = explode(",",$coupon->rf_idSpace);
				if (in_array($rf_idSpace,$tab_rf_idSpace)){$display = "rf_masked";}else{$display = '';}
				$echo .= '
				<button class="rf_accordion '.$display.'" id="rf_taxe'.$coupon->id_modificationprix.'"><a href="#" onclick="rf_associeCoupon(this.getAttribute(\'idcoupon\'),\'taxe\')" idcoupon="'.$coupon->id_modificationprix.'"><span class="rf_no_rotate_arrow_price"><img class="emoji" src="'.plugins_url( 'img/arrow.svg', __FILE__ ).'"></span></a><b>'.ucfirst(rf_removeslashes($coupon->label)).'</b></button>
				<div class="rf_panel" '.''.' id="rf_taxePanel'.$coupon->id_modificationprix.'"><br>';
				$echo .= '<table>';
					
				if (rf_isNotEmpty($coupon->date_debut)){ $echo .= '<tr><td><b>'.__('Available on', 'reservation-facile').' </b></td><td><input type="date" disabled value="'.$coupon->date_debut.'"></td></tr>';}
				if (rf_isNotEmpty($coupon->date_fin)){ $echo .= '<tr><td><b>'.__('Until', 'reservation-facile').' </b></td><td><input type="date" disabled value="'.$coupon->date_fin.'"></td></tr>';}
				if (rf_isNotEmpty($coupon->quantite_initiale)){ $echo .= '<tr><td><b>'.__('Remaining quantity', 'reservation-facile').' </b></td><td>'.($coupon->quantite+0).'/'.($coupon->quantite_initiale+0).'</td></tr>';}
				if (rf_isNotEmpty($coupon->montant)){ $echo .= '<tr><td><b>'.__('Amount', 'reservation-facile').' </b></td><td>'.($coupon->montant+0).'</td></tr>';}
				if (rf_isNotEmpty($coupon->pourcentage)){ $echo .= '<tr><td><b>'.__('Percentage', 'reservation-facile').' </b></td><td>'.($coupon->pourcentage+0).'%</td></tr>';}
				if (rf_isNotEmpty($coupon->periode_heure)){ $echo .= '<tr><td><b>'.__('Periodicity', 'reservation-facile').' </b></td><td>'.($coupon->periode_heure+0).'h</td></tr>';}
				if (rf_isNotEmpty($coupon->code)){ $echo .= '<tr><td><b>'.__('Code', 'reservation-facile').' </b></td><td>'.rf_removeslashes($coupon->code).'</td></tr>';}
				if (rf_isNotEmpty($coupon->description)){ $echo .= '<tr><td colspan="2"><br><b>'.__('Description / Details', 'reservation-facile').': </b><br>'.rf_removeslashes($coupon->description).'</td></tr>';}
				$echo .= '</table><br></div>';
			}
		}
		$echo .= '<span id="rf_btnAjouttaxe">
		<button class="rf_accordion"  id="rf_btnAjouttaxebtn"><span><img class="emoji" src="'.plugins_url( 'img/add.svg', __FILE__ ).'"></span>'.__('New tax', 'reservation-facile').'</button>
		<div class="rf_panel"><br>
			<form action="" method="post">
				<label><b>'.__('Label', 'reservation-facile').':</b></label><br><input type="text" name="label_taxe" required><br>'.__('The name of the tax, visible to customers.', 'reservation-facile').'<br><br>
				<label><b>'.__('Start date', 'reservation-facile').':</b></label><br><input type="date" name="date_debut_taxe" required value="'.date('Y-m-d').'"><br>'.__('Start date for the tax.', 'reservation-facile').'<br><br>
				<label><b>'.__('End date', 'reservation-facile').':</b></label><br><input type="date" name="date_fin_taxe" required value=""><br>'.__('End date for the tax.', 'reservation-facile').'<br><br>
				<div class="rf_masked"><label><b>'.__('Quantity', 'reservation-facile').':</b></label><br><input type="number" min="0" name="quantite_taxe"><br>'.__('Total number of times the tax can be used.', 'reservation-facile').'<br><br></div>
				<label><b>'.__('Amount', 'reservation-facile').':</b></label><br><input type="number" step="any" min="0" name="montant_taxe" onblur="rf_emptyPourcentage(\'taxe\')"><br>'.__('Fixed amount of tax.', 'reservation-facile').'<br><br>
				<label><b>'.__('Percentage', 'reservation-facile').':</b></label><br><input type="number" step="any" min="0" name="pourcentage_taxe" onblur="rf_emptyMontant(\'taxe\')"><br>'.__('Percentage of tax.', 'reservation-facile').'<br><br>
				<label><b>'.__('Periodicity of the tax (in hours)', 'reservation-facile').':</b></label><br><input type="number" step="any" min="0" name="periode_heure_taxe"><br>'.__('Set 0 for a fixed total amount / percentage tax or, for example, set "24" for a re-applied tax for each day booked.', 'reservation-facile').'<br><br>
				<div class="rf_masked"><label><b>'.__('Code', 'reservation-facile').':</b></label><br><input type="text" name="code_taxe"><br>'.__('Code to enter to benefit from the tax.', 'reservation-facile').'<br><br></div>
				<label><b>'.__('Description / Details', 'reservation-facile').':</b></label><br><textarea name="description_taxe"></textarea><br><br>
				<button onclick="rf_ajouterCoupon(\'taxe\');return false;" class="button button-primary button-large">'.__('Add tax', 'reservation-facile').'</button><br><br>
			</form>
		</div></span>';
		$echo .= '</div></div></div>';