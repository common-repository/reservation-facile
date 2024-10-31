<?php
defined( 'ABSPATH' ) or die();
$previsualisation = '';
if ($ok){
	$nb_de_place = $SAFE_DATA['nb_de_place'];
	(isset($SAFE_DATA['acompte_prix']))? $acompte_prix = $SAFE_DATA['acompte_prix'] : $acompte_prix = 0;
	(isset($SAFE_DATA['acompte_pourcentage']))? $acompte_pourcentage = $SAFE_DATA['acompte_pourcentage'] : $acompte_pourcentage = 0;
	(isset($SAFE_DATA['form_date_debut']))? $date_arrivee = $SAFE_DATA['form_date_debut']." ".$SAFE_DATA["form_heure_debut"].':00' : $date_arrivee = "";
	if ((isset($SAFE_DATA['form_heure_fin'])) && ($SAFE_DATA['form_heure_fin'] == '24:00')){
		if (isset($SAFE_DATA['form_date_fin'])){
			$SAFE_DATA['form_heure_fin'] = '00:00';
			$SAFE_DATA["form_date_fin"] = date('Y-m-d', strtotime($SAFE_DATA["form_date_fin"] . ' +1 day'));
		}else{
			$SAFE_DATA['form_heure_fin'] = '23:59';
		}
	}
	(isset($SAFE_DATA['form_date_fin']))? $date_depart = $SAFE_DATA['form_date_fin']." ".$SAFE_DATA["form_heure_fin"].':00' : $date_depart = "";
	(isset($SAFE_DATA['form_personnes']))? $nb_de_personnes = $SAFE_DATA['form_personnes'] : $nb_de_personnes = 0;
	(isset($SAFE_DATA['form_nom']))? $nom = $SAFE_DATA['form_nom'] : $nom = "";
	(isset($SAFE_DATA['form_prenom']))? $prenom = $SAFE_DATA['form_prenom'] : $prenom = "";
	(isset($SAFE_DATA['form_adresse']))? $adresse = $SAFE_DATA['form_adresse'] : $adresse = "";
	(isset($SAFE_DATA['form_code_postal']))? $code_postal = $SAFE_DATA['form_code_postal'] : $code_postal = "";
	(isset($SAFE_DATA['form_ville']))? $ville = $SAFE_DATA['form_ville'] : $ville = "";
	(isset($SAFE_DATA['form_pays']))? $pays = $SAFE_DATA['form_pays'] : $pays = "";
	(isset($SAFE_DATA['form_email']))? $email = $SAFE_DATA['form_email'] : $email = "";
	(isset($SAFE_DATA['form_telephone']))? $telephone = $SAFE_DATA['form_telephone'] : $telephone = "";
	(isset($SAFE_DATA['form_remarques']))? $remarques = $SAFE_DATA['form_remarques'] : $remarques = "";
	if ($majBdd){
		$date = date("Y-m-d H:i:s");
		$rf_idSpace = $SAFE_DATA['rf_idSpace'];
		$statut = $rowEmp->statut_par_defaut_reservation;
		$prix_de_la_place = $rowEmp->prix_de_la_place;
		$wpdb->query($wpdb->prepare("INSERT INTO {$wpdb->prefix}rf_bookings (rf_idSpace,date,nb_de_place,prix_de_la_place,acompte_prix,acompte_pourcentage,date_arrivee,date_depart,nb_de_personnes,nom,prenom,adresse,code_postal,ville,pays,email,telephone,remarques,statut,emplacement,lieu,devise,timeUnit,periodesprices,dayprice) VALUES (%d,%s,%s,%s,%s,%s,%s,%s,%d,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%d,%s,%s)",$rf_idSpace,$date,$nb_de_place,$prix_de_la_place,$acompte_prix,$acompte_pourcentage,$date_arrivee,$date_depart,$nb_de_personnes,$nom,$prenom,$adresse,$code_postal,$ville,$pays,$email,$telephone,$remarques,$statut,$rowEmp->label,$rowEmp->localisation,$rowEmp->devise,$rowEmp->timeUnit,$rowEmp->periodesprices,$rowEmp->dayprice));
		$id_reservation = $wpdb->insert_id;
		$previsualisation = '<div id="rf_mainDiv"><h2>'.__('Booking', 'reservation-facile').' #'.$id_reservation.'</h2>';
	}else{
		$previsualisation = '<div id="rf_mainDiv"><h2>'.__('Overview of the booking', 'reservation-facile').'</h2>';
	}
	if ((rf_isNotEmpty($nom))||(rf_isNotEmpty($prenom))||(rf_isNotEmpty($adresse))||(rf_isNotEmpty($code_postal))||(rf_isNotEmpty($ville))||(rf_isNotEmpty($pays))||(rf_isNotEmpty($email))||(rf_isNotEmpty($telephone))){
		$previsualisation .= '<div class="rf_previewBox"><div class="rf_previewHead">'.__('Your contact details', 'reservation-facile').'</div><div class="rf_previewContent">';
		if ((rf_isNotEmpty($nom))||(rf_isNotEmpty($prenom))){
			if (rf_isNotEmpty($nom)){$previsualisation .= rf_removeslashes($nom);}
			if (rf_isNotEmpty($prenom)){$previsualisation .= " ".rf_removeslashes($prenom);}
			$previsualisation .= '<br>';
		}
		if (rf_isNotEmpty($adresse)){$previsualisation .= rf_removeslashes($adresse).'<br>';}
		if ((rf_isNotEmpty($code_postal))||(rf_isNotEmpty($ville))){
			if (rf_isNotEmpty($code_postal)){$previsualisation .= rf_removeslashes($code_postal);}
			if (rf_isNotEmpty($ville)){$previsualisation .= " ".rf_removeslashes($ville);}
			$previsualisation .= '<br>';
		}
		if (rf_isNotEmpty($pays)){$previsualisation .= " ".rf_removeslashes($pays).'<br>';}
		if ((rf_isNotEmpty($telephone))||(rf_isNotEmpty($email))){
			//$previsualisation .= '<br>';
			if (rf_isNotEmpty($telephone)){$previsualisation .= __('Tel.', 'reservation-facile').': '.rf_removeslashes($telephone);}
			if (rf_isNotEmpty($email)){$previsualisation .= '<br>'.__('Email', 'reservation-facile').': '.rf_removeslashes($email);}
			$previsualisation .= '<br>';
		}
		$previsualisation .= '</div></div>';
	}
	if ($forEmail){
		$previsualisation .= '<div class="rf_previewBox">
							<div class="rf_previewHead">'.__('Place', 'reservation-facile').': '.rf_removeslashes($rowEmp->label).' - '.rf_removeslashes($rowEmp->localisation).'</div>
							<div class="rf_previewContent">';
		
		$ts_date_debut = rf_getTimeStamp($SAFE_DATA["form_date_debut"]." ".$SAFE_DATA["form_heure_debut"]);
		$form_date_debut = date("d/m/Y H:i", $ts_date_debut);
		$ts_date_fin = rf_getTimeStamp($SAFE_DATA["form_date_fin"]." ".$SAFE_DATA["form_heure_fin"]);
		$form_date_fin = date("d/m/Y H:i", $ts_date_fin);
		$previsualisation .= '<div class="rf_previewResume">'.__('Booking date', 'reservation-facile').': '.$form_date_debut.' '.__('until', 'reservation-facile').' '.$form_date_fin.'</div>';
		
		
		//$previsualisation .= '<div class="rf_previewResume">'.__('Booking date', 'reservation-facile').': '.$SAFE_DATA["form_date_debut"].' '.__('until', 'reservation-facile').' '.$SAFE_DATA["form_date_fin"].'</div>';
	}else{
		$form_heure_fin_aff = $SAFE_DATA["form_heure_fin"];
		//if ($form_heure_fin_aff == "24:00"){$form_heure_fin_aff = "23:59";}
		$previsualisation .= '<div class="rf_previewBox">
							<div class="rf_previewHead">'.__('Place', 'reservation-facile').': '.rf_removeslashes($rowEmp->label).' - '.rf_removeslashes($rowEmp->localisation).'</div>
							<div class="rf_previewContent">
								<div class="rf_previewResume"><div>'.__('Booking date', 'reservation-facile').': <div class="rf_flex"><input type="date" disabled value="'.$SAFE_DATA["form_date_debut"].'"><input type="time" disabled value="'.$SAFE_DATA["form_heure_debut"].'"></div></div><div>'.__('until', 'reservation-facile');
		$previsualisation .= ' <div class="rf_flex"><input type="date" disabled value="'.$SAFE_DATA["form_date_fin"].'"><input type="time" disabled value="'.$form_heure_fin_aff.'"></div></div></div>';
	}
	$date_depart = date("Y-m-d H:i:s",strtotime($date_depart . " -1 second"));
	$prixtotal = rf_getSpaceTotalPrice($date_arrivee,$date_depart,$rowEmp->periodesprices,$rowEmp->prix_de_la_place,$SAFE_DATA['nb_de_place'],$rowEmp->timeUnit,$rowEmp->dayprice);
	$previsualisation .= '<div class="rf_previewRow"><div>'.__('Number of places', 'reservation-facile').':&nbsp;</div><div>'. $SAFE_DATA['nb_de_place'].'</div></div>';
	if ($duree_reservation_heures > 0){
		$previsualisation .= '<div class="rf_previewRow"><div>'.__('Duration of the booking', 'reservation-facile').':&nbsp;</div><div>'. rf_heure2duree($duree_reservation_heures) . '</div></div>';
	}
	$previsualisation .= '<div class="rf_previewRow rf_total"><div>'.__('Total without taxes', 'reservation-facile').':&nbsp;</div><div>';
	$previsualisation .= '<span id="rf_totalWT">' . sprintf( '%.'.rf_getParameter('roundnumber').'f', round($prixtotal,rf_getParameter('roundnumber'))) .' '. $rowEmp->devise.'</span>'.rf_displayPrice($prixtotal, $rowEmp->devise, 'rf_totalWT').'</div></div>';
	$previsualisation .= '</div></div>';
	$prixoriginal = $prixtotal;
	$previsuOption = '';
	$totalAllOptions = 0;
	foreach($resultatsMP as $mp){
		if ($mp->type == 'option'){
			foreach($todo as $id_mp){
				if ($id_mp == $mp->id){
					$finParcours = $SAFE_DATA['form_date_fin'];
					$originalQty = 0;
					if (isset($SAFE_DATA['form_option_'.$id_mp])){
						$originalQty = $SAFE_DATA['form_option_'.$id_mp];
					}
					$SAFE_DATA['form_option_'.$id_mp] = rf_getAutomaticQty($mp->code,$originalQty,$SAFE_DATA['form_date_debut'], $finParcours, $SAFE_DATA['nb_de_place']);
					if ((isset($SAFE_DATA['form_option_'.$id_mp]))&&($SAFE_DATA['form_option_'.$id_mp] > 0)){
						if (!isset($SAFE_DATA['form_option_details_texte_'.$id_mp])){
							$SAFE_DATA['form_option_details_texte_'.$id_mp] = '';
						}
						if ($majBdd){
							$wpdb->query($wpdb->prepare("INSERT INTO {$wpdb->prefix}rf_modifyprice_bookings (id_reservation,label,description,quantite,montant,pourcentage,periode_heure,code,type,details_texte) VALUES (%d,%s,%s,%s,%s,%s,%s,%s,%s,%s)",$id_reservation,$mp->label,$mp->description,$SAFE_DATA['form_option_'.$id_mp],$mp->montant,$mp->pourcentage,$mp->periode_heure,$mp->code,$mp->type,$SAFE_DATA['form_option_details_texte_'.$id_mp]));
						}
						$heure_restante = $duree_reservation_heures;
						$totalHeureOption = 0;
						$previsuMontantOption = 0;
						if (($heure_restante >= $mp->periode_heure)||($mp->periode_heure == 0)){
							do{
								$prixtotal += ($mp->montant*$SAFE_DATA['form_option_'.$id_mp]);
								$previsuMontantOption += ($mp->montant*$SAFE_DATA['form_option_'.$id_mp]);
								$prixtotal += $prixoriginal * ($mp->pourcentage * $SAFE_DATA['form_option_'.$id_mp] / 100);
								$previsuMontantOption += $prixoriginal * ($mp->pourcentage * $SAFE_DATA['form_option_'.$id_mp] / 100);
								$heure_restante -= abs($mp->periode_heure);
								$totalHeureOption += abs($mp->periode_heure);
							}while(($heure_restante >= $mp->periode_heure)&&($mp->periode_heure > 0));
							$previsuOption .= '<div class="rf_previewResume">'.ucfirst(rf_removeslashes($mp->label)).':&nbsp;</div><div class="rf_previewRow"><div>';
							if ($mp->description != ''){
								$previsuOption .= __('Description / Details', 'reservation-facile').':&nbsp;</div><div>'.rf_removeslashes($mp->description).'</div></div><div class="rf_previewRow"><div>';
							}
							
							$previsuOption .= ''.__('Quantity', 'reservation-facile').':&nbsp;</div><div>';
							if (isset($nbJours)){
								$previsuOption .= $nbJours . ' x (' . $SAFE_DATA['nb_de_place'] . ' ' . __('place(s)','reservation-facile') . ') = ';
							}
							$previsuOption .= $SAFE_DATA['form_option_'.$id_mp];
							
							$previsuOption .='</div></div>';
							if (($mp->montant > 0) || ($mp->pourcentage > 0) || ($mp->periode_heure > 0)){
								$previsuOption .= '<div class="rf_previewRow"><div>';
								if ($mp->montant > 0){
									$previsuOption .= __('Unit price', 'reservation-facile').':&nbsp;</div><div><span id="rf_optionUnitPrice'.$id_mp.'">'.sprintf( '%.'.rf_getParameter('roundnumber').'f',($mp->montant+0)).' '.$rowEmp->devise . '</span>';
									$previsuOption .= rf_displayPrice($mp->montant, $rowEmp->devise, 'rf_optionUnitPrice'.$id_mp);
								}
								if ($mp->pourcentage > 0){
									$previsuOption .= __('Percentage', 'reservation-facile').':&nbsp;</div><div>'.($mp->pourcentage+0)."%";
								}
								if ($mp->periode_heure > 0){
									$previsuOption .= ' '.__('for', 'reservation-facile').' '.rf_heure2duree($mp->periode_heure);
								}
								$previsuOption .= '</div></div>';
							}
							if ($SAFE_DATA['form_option_details_texte_'.$id_mp] != ''){
								$previsuOption .= '<div class="rf_previewRow"><div>'.__('Your choice', 'reservation-facile').':&nbsp;</div><div>'.rf_removeslashes($SAFE_DATA['form_option_details_texte_'.$id_mp]).'</div></div>';
							}
							$previsuOption .= '<div class="rf_previewRow rf_total"><div>';
							$previsuOption .= __('Total without taxes', 'reservation-facile').':&nbsp;</div><div>'.$SAFE_DATA['form_option_'.$id_mp].' x (';
							if ($mp->montant > 0){
								$previsuOption .= '<span id="rf_option2UnitPrice'.$id_mp.'">'.sprintf( '%.'.rf_getParameter('roundnumber').'f',($mp->montant+0)).' '.$rowEmp->devise . '</span>';
								$previsuOption .= rf_displayPrice($mp->montant, $rowEmp->devise, 'rf_option2UnitPrice'.$id_mp);
							}elseif ($mp->pourcentage > 0){
								$previsuOption .= ($mp->pourcentage+0).'% x <span id="rf_originalPrice'.$id_mp.'">' . sprintf( '%.'.rf_getParameter('roundnumber').'f', round($prixoriginal,rf_getParameter('roundnumber'))) .' '. $rowEmp->devise . '</span>';
								$previsuOption .= rf_displayPrice($prixoriginal, $rowEmp->devise, 'rf_originalPrice'.$id_mp);
							}else{
								$previsuOption .= '<span id="rf_option2UnitPrice'.$id_mp.'">'.sprintf( '%.'.rf_getParameter('roundnumber').'f',0).$rowEmp->devise . '</span>';
								$previsuOption .= rf_displayPrice(0, $rowEmp->devise, 'rf_option2UnitPrice'.$id_mp);
							}
							if ($mp->periode_heure > 0){
								$previsuOption .= ' x '.$totalHeureOption . __('hrs', 'reservation-facile') . ' / ' . ($mp->periode_heure+0) . __('hrs', 'reservation-facile');
							}
							$previsuOption .= ') = <span id="rf_totalOptionWT'.$id_mp.'">'.sprintf( '%.'.rf_getParameter('roundnumber').'f', round($previsuMontantOption,rf_getParameter('roundnumber'))) .' '. $rowEmp->devise.'</span>';
							$previsuOption .= rf_displayPrice($previsuMontantOption,  $rowEmp->devise, 'rf_totalOptionWT'.$id_mp);
							$previsuOption .= '</div></div>
							';
							$totalAllOptions += $previsuMontantOption;
						}
					}
				}
			}
		}
	}
	if ($previsuOption != ''){
		$previsualisation .= '<div class="rf_previewBox">
							<div class="rf_previewHead">'.__('Options', 'reservation-facile').'</div>
							<div class="rf_previewContent">';
		$previsualisation .= $previsuOption;
		$previsualisation .= '<div class="rf_previewRow rf_total2"><div>'.__('Total with options (without taxes)', 'reservation-facile').':&nbsp;</div><div><span id="rf_totalWithOptionsWT">'.sprintf( '%.'.rf_getParameter('roundnumber').'f', round($prixoriginal,rf_getParameter('roundnumber'))). ' ' . $rowEmp->devise.'</span>';
		$previsualisation .= rf_displayPrice($prixoriginal,  $rowEmp->devise, 'rf_totalWithOptionsWT');
		$previsualisation .= ' + <span id="rf_totalAllOptions">'.sprintf( '%.'.rf_getParameter('roundnumber').'f', round($totalAllOptions,rf_getParameter('roundnumber'))) .' '. $rowEmp->devise. '</span>';
		$previsualisation .= rf_displayPrice($totalAllOptions,  $rowEmp->devise, 'rf_totalAllOptions');
		$previsualisation .= ' = <span id="rf_totalPriceWithOptions">' . sprintf( '%.'.rf_getParameter('roundnumber').'f', round($prixtotal,rf_getParameter('roundnumber'))).' '.$rowEmp->devise.'</span>';
		$previsualisation .= rf_displayPrice($prixtotal,  $rowEmp->devise, 'rf_totalPriceWithOptions');
		$previsualisation .= '</div></div></div></div>';
	}
	$prixoriginal = $prixtotal;
	$previsuTaxes = '';
	$totalAllTaxes = 0;
	foreach($resultatsMP as $mp){
		if ($mp->type == 'taxe'){
			foreach($todo as $id_mp){
				if ($id_mp == $mp->id){
					if ($majBdd){
						$wpdb->query($wpdb->prepare("INSERT INTO {$wpdb->prefix}rf_modifyprice_bookings (id_reservation,label,description,quantite,montant,pourcentage,periode_heure,code,type,details_texte) VALUES (%d,%s,%s,%s,%s,%s,%s,%s,%s,'')",$id_reservation,$mp->label,$mp->description,'1',$mp->montant,$mp->pourcentage,$mp->periode_heure,$mp->code,$mp->type));
					}
					$heure_restante = $duree_reservation_heures;
					$previsuMontantTaxes = 0;
					$totalHeureTaxe = 0;
					if (($heure_restante >= $mp->periode_heure)||($mp->periode_heure == 0)){
						do{
							$prixtotal += $mp->montant;
							$previsuMontantTaxes += $mp->montant;
							$prixtotal += $prixoriginal * ($mp->pourcentage / 100);
							$previsuMontantTaxes += $prixoriginal * ($mp->pourcentage / 100);
							$heure_restante -= abs($mp->periode_heure);
							$totalHeureTaxe += abs($mp->periode_heure);
						}while(($heure_restante >= $mp->periode_heure)&&($mp->periode_heure > 0));
						$previsuTaxes .= '<div class="rf_previewResume">'.ucfirst(rf_removeslashes($mp->label)).':&nbsp;</div><div class="rf_previewRow"><div>';
						if ($mp->description != ''){
							$previsuTaxes .= __('Description / Details', 'reservation-facile').':&nbsp;</div><div>'.rf_removeslashes($mp->description).'</div></div><div class="rf_previewRow"><div>';
						}
						if ($mp->montant > 0){
							$previsuTaxes .= __('Unit price', 'reservation-facile').':&nbsp;</div><div><span id="rf_taxeUnitPrice'.$id_mp.'">'.sprintf( '%.'.rf_getParameter('roundnumber').'f',($mp->montant+0)).' '.$rowEmp->devise . '</span>';
							$previsuTaxes .= rf_displayPrice($mp->montant,  $rowEmp->devise, 'rf_taxeUnitPrice'.$id_mp);
						}
						if ($mp->pourcentage > 0){
							$previsuTaxes .= __('Percentage', 'reservation-facile').':&nbsp;</div><div>'.($mp->pourcentage+0)."%";
						}
						if ($mp->periode_heure > 0){
							$previsuTaxes .= ' '.__('for', 'reservation-facile').' '.rf_heure2duree($mp->periode_heure);
						}
						$previsuTaxes .= '</div></div><div class="rf_previewRow rf_total"><div>';
						$previsuTaxes .= __('Total', 'reservation-facile').':&nbsp;</div><div>';
						if ($mp->montant > 0){
							$previsuTaxes .= '1 x <span id="rf_taxeUnit2Price'.$id_mp.'">'.sprintf( '%.'.rf_getParameter('roundnumber').'f',($mp->montant+0)).' '.$rowEmp->devise . '</span>';
							$previsuTaxes .= rf_displayPrice($mp->montant,  $rowEmp->devise, 'rf_taxeUnit2Price'.$id_mp);
						}
						if ($mp->pourcentage > 0){
							$previsuTaxes .= '<span id="rf_original2Price'.$id_mp.'">'.sprintf( '%.'.rf_getParameter('roundnumber').'f', round($prixoriginal,rf_getParameter('roundnumber'))) .' '. $rowEmp->devise .'</span>';
							$previsuTaxes .= rf_displayPrice($prixoriginal,  $rowEmp->devise, 'rf_original2Price'.$id_mp);
							$previsuTaxes .= ' x ';
							$previsuTaxes .= ($mp->pourcentage+0).'%';
						}
						if ($mp->periode_heure > 0){
							$previsuTaxes .= ' x '.$totalHeureTaxe . __('hrs', 'reservation-facile') . ' / ' . ($mp->periode_heure+0) . __('hrs', 'reservation-facile');
						}
						$previsuTaxes .= ' = <span id="rf_totalTaxes'.$id_mp.'">'.sprintf( '%.'.rf_getParameter('roundnumber').'f', round($previsuMontantTaxes,rf_getParameter('roundnumber'))) .' '. $rowEmp->devise.'</span>';
						$previsuTaxes .= rf_displayPrice($previsuMontantTaxes,  $rowEmp->devise, 'rf_totalTaxes'.$id_mp);
						$previsuTaxes .= '</div></div>
						';
						$totalAllTaxes += $previsuMontantTaxes;
					}
				}
			}
		}
	}
	if ($previsuTaxes != ''){
		$previsualisation .= '<div class="rf_previewBox">
							<div class="rf_previewHead">'.__('Taxes', 'reservation-facile').'</div>
							<div class="rf_previewContent">';
		$previsualisation .= $previsuTaxes;
		$previsualisation .= '<div class="rf_previewRow rf_total2"><div>'.__('Total with taxes', 'reservation-facile').':&nbsp;</div><div><span id="rf_totalWithTaxes">'.sprintf( '%.'.rf_getParameter('roundnumber').'f', round($prixoriginal,rf_getParameter('roundnumber'))). ' ' . $rowEmp->devise.'</span>';
		$previsualisation .= rf_displayPrice($prixoriginal,  $rowEmp->devise, 'rf_totalWithTaxes');
		$previsualisation .= ' + <span id="rf_totalAllTaxes">'.sprintf( '%.'.rf_getParameter('roundnumber').'f', round($totalAllTaxes,rf_getParameter('roundnumber'))) .' '. $rowEmp->devise. '</span>';
		$previsualisation .= rf_displayPrice($totalAllTaxes,  $rowEmp->devise, 'rf_totalAllTaxes');
		$previsualisation .= ' = <span id="rf_totalPriceWithTaxes">'. sprintf( '%.'.rf_getParameter('roundnumber').'f', round($prixtotal,rf_getParameter('roundnumber'))).' '.$rowEmp->devise.'</span></div></div>';
		$previsualisation .= rf_displayPrice($prixtotal,  $rowEmp->devise, 'rf_totalPriceWithTaxes');
		$previsualisation .= '</div></div>';
	}
	$prixoriginal = $prixtotal;
	$previsuReduction = '';
	foreach($resultatsMP as $mp){
		if ($mp->type == 'reduction'){
			foreach($todo as $id_mp){
				if ($id_mp == $mp->id){
					if ($majBdd){
						$wpdb->query($wpdb->prepare("INSERT INTO {$wpdb->prefix}rf_modifyprice_bookings (id_reservation,label,description,quantite,montant,pourcentage,periode_heure,code,type,details_texte) VALUES (%d,%s,%s,%s,%s,%s,%s,%s,%s,'')",$id_reservation,$mp->label,$mp->description,'1',$mp->montant,$mp->pourcentage,$mp->periode_heure,$mp->code,$mp->type));
						if ($mp->quantite_initiale > 0){
							$wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}rf_modifyprice SET quantite = quantite - 1 WHERE id = %s",$id_mp));
						}
					}
					$heure_restante = $duree_reservation_heures;
					$previsuMontantReduction = 0;
					$totalHeureReduction = 0;
					if (($heure_restante >= $mp->periode_heure)||($mp->periode_heure == 0)){
						do{
							$prixtotal -= $mp->montant;
							$previsuMontantReduction += $mp->montant;
							$prixtotal -= $prixoriginal * ($mp->pourcentage / 100);
							$previsuMontantReduction += $prixoriginal * ($mp->pourcentage / 100);
							$heure_restante -= abs($mp->periode_heure);
							$totalHeureReduction += abs($mp->periode_heure);
						}while(($heure_restante >= $mp->periode_heure)&&($mp->periode_heure > 0));
						$previsuReduction .= '<div class="rf_previewRow"><div>';
						if ($mp->code != ''){
							$previsuReduction .= __('Code', 'reservation-facile').':&nbsp;</div><div>'.rf_removeslashes($SAFE_DATA['form_reduction']).'</div></div><div class="rf_previewRow"><div>';
						}
						if ($mp->description != ''){
							$previsuReduction .= __('Description / Details', 'reservation-facile').':&nbsp;</div><div>'.rf_removeslashes($mp->description).'</div></div><div class="rf_previewRow"><div>';
						}
						if ($mp->montant > 0){
							$previsuReduction .= __('Amount', 'reservation-facile').':&nbsp;</div><div>-<span id="rf_discountPrice'.$id_mp.'">'.sprintf( '%.'.rf_getParameter('roundnumber').'f',($mp->montant+0)).' '.$rowEmp->devise.'</span>';
							$previsuReduction .= rf_displayPrice($mp->montant,  $rowEmp->devise, 'rf_discountPrice'.$id_mp);
						}
						if ($mp->pourcentage > 0){
							$previsuReduction .= __('Percentage', 'reservation-facile').':&nbsp;</div><div>-'.($mp->pourcentage+0).'% x <span id="rf_original3Price'.$id_mp.'">'.sprintf( '%.'.rf_getParameter('roundnumber').'f', round($prixoriginal,rf_getParameter('roundnumber'))) .' '. $rowEmp->devise . '</span>';
							$previsuReduction .= rf_displayPrice($prixoriginal,  $rowEmp->devise, 'rf_original3Price'.$id_mp);

							$previsuReduction .= '= -<span id="rf_discountPercentagePrice'.$id_mp.'">'.sprintf( '%.'.rf_getParameter('roundnumber').'f', round($mp->pourcentage/100*$prixoriginal,rf_getParameter('roundnumber'))) .' '. $rowEmp->devise . '</span>';
							$previsuReduction .= rf_displayPrice($mp->pourcentage/100*$prixoriginal,  $rowEmp->devise, 'rf_discountPercentagePrice'.$id_mp);
						}
						if ($mp->periode_heure > 0){
							$previsuReduction .= ' '.__('for', 'reservation-facile').' '.rf_heure2duree($mp->periode_heure);
							$previsuReduction .= '<br>'.__('Total', 'reservation-facile') . ': -<span id="rf_discountAmount'.$id_mp.'">' . sprintf( '%.'.rf_getParameter('roundnumber').'f', round($previsuMontantReduction,rf_getParameter('roundnumber'))) .' '. $rowEmp->devise . '</span>';
							$previsuReduction .= rf_displayPrice($previsuMontantReduction,  $rowEmp->devise, 'rf_discountAmount'.$id_mp);
							$previsuReduction .= ' '.__('for', 'reservation-facile').' ' . $totalHeureReduction . __('hrs', 'reservation-facile');
						}
						$previsuReduction .= '</div></div>';
						$previsuReduction .= '<div class="rf_previewRow rf_total2"><div>'.__('Total with taxes and discount', 'reservation-facile').':&nbsp;</div><div><span id="rf_totalWithTaxesDiscount'.$id_mp.'">'. sprintf( '%.'.rf_getParameter('roundnumber').'f', round($prixoriginal,rf_getParameter('roundnumber'))) .' '. $rowEmp->devise . '</span>';
						$previsuReduction .= rf_displayPrice($prixoriginal,  $rowEmp->devise, 'rf_totalWithTaxesDiscount'.$id_mp);
						if ($previsuMontantReduction > 0){
							$previsuReduction .= ' - <span id="rf_totalPercentDiscount'.$id_mp.'">'.sprintf( '%.'.rf_getParameter('roundnumber').'f', round($previsuMontantReduction,rf_getParameter('roundnumber'))) .' '. $rowEmp->devise . '</span>';
							$previsuReduction .= rf_displayPrice($previsuMontantReduction,  $rowEmp->devise, 'rf_totalPercentDiscount'.$id_mp);
						}

						$previsuReduction .= ' = <span id="rf_totalDiscount'.$id_mp.'">'.sprintf( '%.'.rf_getParameter('roundnumber').'f', round($prixtotal,rf_getParameter('roundnumber'))).' '.$rowEmp->devise.'</span>';
						$previsuReduction .= rf_displayPrice($prixtotal,  $rowEmp->devise, 'rf_totalDiscount'.$id_mp);
						$previsuReduction .= '</div></div>
						';
					}
				}
			}
		}
		
	}
	if ($previsuReduction != ''){
		$previsualisation .= '<div class="rf_previewBox">
							<div class="rf_previewHead">'.__('Discount', 'reservation-facile').'</div>
							<div class="rf_previewContent">';
		$previsualisation .= $previsuReduction;
		$previsualisation .= '</div></div>';
	}		
	$prixoriginal2 = $prixtotal;
	$previsuCoupon = '';
	foreach($resultatsMP as $mp){
		if ($mp->type == 'coupon'){
			foreach($todo as $id_mp){
				if ($id_mp == $mp->id){
					if ($majBdd){
						$wpdb->query($wpdb->prepare("INSERT INTO {$wpdb->prefix}rf_modifyprice_bookings (id_reservation,label,description,quantite,montant,pourcentage,periode_heure,code,type,details_texte) VALUES (%d,%s,%s,%s,%s,%s,%s,%s,%s,'')",$id_reservation,$mp->label,$mp->description,'1',$mp->montant,$mp->pourcentage,$mp->periode_heure,$mp->code,$mp->type));
						if ($mp->quantite_initiale > 0){
							$wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}rf_modifyprice SET quantite = quantite - 1 WHERE id = %s",$id_mp));
						}
					}
					$heure_restante = $duree_reservation_heures;
					$previsuMontantCoupon = 0;
					$totalHeureCoupon = 0;
					if (($heure_restante >= $mp->periode_heure)||($mp->periode_heure == 0)){
						do{
							$prixtotal -= $mp->montant;
							$previsuMontantCoupon += $mp->montant;
							$prixtotal -= $prixoriginal * ($mp->pourcentage / 100);
							$previsuMontantCoupon += $prixoriginal * ($mp->pourcentage / 100);
							$heure_restante -= abs($mp->periode_heure);
							$totalHeureCoupon += abs($mp->periode_heure);
						}while(($heure_restante >= $mp->periode_heure)&&($mp->periode_heure > 0));
						$previsuCoupon .= '<div class="rf_previewRow"><div>';
						$previsuCoupon .= __('Code', 'reservation-facile').':&nbsp;</div><div>'.rf_removeslashes($SAFE_DATA['form_coupon']).'</div></div><div class="rf_previewRow"><div>';
						if ($mp->description != ''){
							$previsuCoupon .= __('Description / Details', 'reservation-facile').':&nbsp;</div><div>'.rf_removeslashes($mp->description).'</div></div><div class="rf_previewRow"><div>';
						}
						if ($mp->montant > 0){
							$previsuCoupon .= __('Amount', 'reservation-facile').':&nbsp;</div><div>-<span id="rf_couponAmount'.$id_mp.'">'.sprintf( '%.'.rf_getParameter('roundnumber').'f',($mp->montant+0)).' '.$rowEmp->devise . '</span>';
							$previsuCoupon .= rf_displayPrice($mp->montant,  $rowEmp->devise, 'rf_couponAmount'.$id_mp);
						}
						if ($mp->pourcentage > 0){
							$previsuCoupon .= __('Percentage', 'reservation-facile').':&nbsp;</div><div>-'.($mp->pourcentage+0).'% x <span id="rf_original4Price'.$id_mp.'">'.sprintf( '%.'.rf_getParameter('roundnumber').'f', round($prixoriginal,rf_getParameter('roundnumber'))) .' '. $rowEmp->devise . '</span>';
							$previsuCoupon .= rf_displayPrice($prixoriginal,  $rowEmp->devise, 'rf_original4Price'.$id_mp);
							$previsuCoupon .= ' = -<span id="rf_couponPercentage'.$id_mp.'">'.sprintf( '%.'.rf_getParameter('roundnumber').'f', round($mp->pourcentage/100*$prixoriginal,rf_getParameter('roundnumber'))) .' '. $rowEmp->devise . '</span>';
							$previsuCoupon .= rf_displayPrice($mp->pourcentage/100*$prixoriginal,  $rowEmp->devise, 'rf_couponPercentage'.$id_mp);
						}
						if ($mp->periode_heure > 0){
							$previsuCoupon .= ' '.__('for', 'reservation-facile').' '.rf_heure2duree($mp->periode_heure);
							$previsuCoupon .= '<br>'.__('Total', 'reservation-facile') . ': -<span id="rf_couponTotalAmount'.$id_mp.'">' . sprintf( '%.'.rf_getParameter('roundnumber').'f', round($previsuMontantCoupon,rf_getParameter('roundnumber'))) .' '. $rowEmp->devise . '</span>';
							$previsuCoupon .= rf_displayPrice($previsuMontantCoupon,  $rowEmp->devise, 'rf_couponTotalAmount'.$id_mp);
							$previsuCoupon .= ' '.__('for', 'reservation-facile').' ' . $totalHeureCoupon . __('hrs', 'reservation-facile');
						}
						$previsuCoupon .= '</div></div>';
						$previsuCoupon .= '<div class="rf_previewRow rf_total2"><div>'.__('Total with taxes and coupon', 'reservation-facile').':&nbsp;</div><div><span id="rf_totalWithTaxesCoupon'.$id_mp.'">'. sprintf( '%.'.rf_getParameter('roundnumber').'f', round($prixoriginal2,rf_getParameter('roundnumber'))) .' '. $rowEmp->devise . '</span>';
						$previsuCoupon .= rf_displayPrice($prixoriginal2,  $rowEmp->devise, 'rf_totalWithTaxesCoupon'.$id_mp);
						if ($previsuMontantCoupon > 0){
							$previsuCoupon .= ' - <span id="rf_totalCoupon'.$id_mp.'">'.sprintf( '%.'.rf_getParameter('roundnumber').'f', round($previsuMontantCoupon,rf_getParameter('roundnumber'))) .' '. $rowEmp->devise;
							$previsuCoupon .= rf_displayPrice($previsuMontantCoupon,  $rowEmp->devise, 'rf_totalCoupon'.$id_mp);
						}
						$previsuCoupon .= ' = <span id="rf_totalPriceCoupon'.$id_mp.'">'.sprintf( '%.'.rf_getParameter('roundnumber').'f', round($prixtotal,rf_getParameter('roundnumber'))).' '.$rowEmp->devise.'</span>';
						$previsuCoupon .= rf_displayPrice($prixtotal,  $rowEmp->devise, 'rf_totalPriceCoupon'.$id_mp);
						$previsuCoupon .= '</div></div>
						';
					}
				}
			}
		}
		
	}
	if ($previsuCoupon != ''){
		$previsualisation .= '<div class="rf_previewBox">
							<div class="rf_previewHead">'.__('Coupon', 'reservation-facile').'</div>
							<div class="rf_previewContent">';
		$previsualisation .= $previsuCoupon;
		$previsualisation .= '</div></div>';
	}
	$totalToPay = $prixtotal;
	$deposit = 0;
	if (($rowEmp->acompte_prix > 0)||($rowEmp->acompte_pourcentage > 0)){				
		if ($rowEmp->acompte_prix > 0){
			$deposit += $rowEmp->acompte_prix;
		}
		if ($rowEmp->acompte_pourcentage > 0){
			$deposit += $rowEmp->acompte_pourcentage / 100 * $prixtotal;
		}
		$previsualisation .= '<div class="rf_previewBox">
							<div class="rf_previewHead">'.__('Deposit requested', 'reservation-facile').'</div>
							<div class="rf_previewContent">';
		$previsualisation .= '<div class="rf_previewRow"><div></div><div><span id="rf_deposit">'.sprintf( '%.'.rf_getParameter('roundnumber').'f', round($deposit ,rf_getParameter('roundnumber'))).' '.$rowEmp->devise.'</span>';
		$previsualisation .= rf_displayPrice($deposit,  $rowEmp->devise, 'rf_deposit');
		$previsualisation .= '</div></div></div></div>';
	}
	$previsualisation .= '<div class="rf_previewBox rf_total_reservation"><div class="rf_previewRow">';
	if (($deposit > 0) && ($deposit < $prixtotal)){
		$totalToPay = $deposit;
		$previsualisation .= '<div>'.__('Remaining amount to be paid on arrival', 'reservation-facile').':&nbsp;</div><div><span id="rf_remaining">'.sprintf( '%.'.rf_getParameter('roundnumber').'f', round($prixtotal - $totalToPay,rf_getParameter('roundnumber'))).' '.$rowEmp->devise.'</span>';
		$previsualisation .= rf_displayPrice($prixtotal - $totalToPay,  $rowEmp->devise, 'rf_remaining');
		$previsualisation .= '</div></div><div class="rf_previewRow">';
	}else{
		$totalToPay = $prixtotal;
	}
	$previsualisation .= '<div>'.__('Total to pay', 'reservation-facile').':&nbsp;</div><div><span id="rf_totalToPay">'.sprintf( '%.'.rf_getParameter('roundnumber').'f', round($totalToPay,rf_getParameter('roundnumber'))).' '.$rowEmp->devise.'</span>';
	$previsualisation .= rf_displayPrice($totalToPay,  $rowEmp->devise, 'rf_totalToPay');
	$previsualisation .= '</div></div></div>';
	if ($rowEmp->payment_instructions != ''){
		$previsualisation .= '<div class="rf_previewBox">
								<div class="rf_previewHead">'.__('Payment instructions', 'reservation-facile').'</div>
								<div class="rf_previewContent">';
		$previsualisation .= '<div class="rf_previewRow"><div>'.rf_removeslashes($rowEmp->payment_instructions).'</div></div>';
		$previsualisation .= '</div></div>';
	}
	$previsualisation .= '<div class="rf_bookingPayment">';
	if (!$forEmail){
		$previsualisation .= '<div><form action="" method="POST" id="rf_formAllDataBooking">';	
		foreach($SAFE_DATA as $key => $value){
			if ($key != 'rf_act'){
				$previsualisation .= '<input type="hidden" name="'.$key.'" value="'.rf_removeslashes($value).'">';
			}
		}						
		$previsualisation .= '<input type="submit" value="'.__('Edit my entry', 'reservation-facile').'"></form></div>';
	
		$previsualisation .= '
		<div><form action="" name="rf_saveBooking" method="post" onsubmit="return false">
			<input type="hidden" name="saveBooking">';
			
			
		$previsualisation .= rf_get_wp_nonce_field('saveBooking','rf_mainSaveBooking');
			
			
		$previsualisation .= '<input type="submit" value="'.__('Confirm the booking', 'reservation-facile').'" class="rf_payBookingBtn">
		</form></div>
		';	
	
	
	}
	$previsualisation .= '<script>document.addEventListener("submit",function(e){rf_ajaxSaveBooking(e);});</script></div><div id="rf_calendarLoading"><div class="rf_loadingAnimation"></div></div></div>';
}