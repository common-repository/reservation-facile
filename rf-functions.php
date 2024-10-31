<?php
defined( 'ABSPATH' ) or die();
$rf_parameters = array();
$rf_adminNotice = array();
$activating = false;

//----CONSTANTS
function rf_getTutorialsLinks(){
	return '<h3>Tutorials</h3><br>';
}

function rf_getBookingStatus($status){
	$rf_bookingStatus =  array( 'validationinprogress' => __('Validation in progress', 'reservation-facile'),
							'pendingpayment' => __('Pending payment', 'reservation-facile'),
							'confirmed' => __('Confirmed', 'reservation-facile'),
							'paid' => __('Paid', 'reservation-facile'),
							'canceled' => __('Canceled', 'reservation-facile'));
	return $rf_bookingStatus[$status];
}

function rf_getAllCurrencies($selected=""){
	return '<option value=""></option>
			<option value="AUD" '.(($selected == 'AUD')? 'selected':'').'>AUD - Australian Dollar</option>
			<option value="BRL" '.(($selected == 'BRL')? 'selected':'').'>BRL - Brazilian Real</option>
			<option value="CAD" '.(($selected == 'CAD')? 'selected':'').'>CAD - Canadian Dollar</option>
			<option value="CZK" '.(($selected == 'CZK')? 'selected':'').'>CZK - Czech Koruna</option>
			<option value="DKK" '.(($selected == 'DKK')? 'selected':'').'>DKK - Danish Krone</option>
			<option value="EUR" '.(($selected == 'EUR')? 'selected':'').'>EUR - Euro</option>
			<option value="HKD" '.(($selected == 'HKD')? 'selected':'').'>HKD - Hong Kong Dollar</option>
			<option value="HUF" '.(($selected == 'HUF')? 'selected':'').'>HUF - Hungarian Forint</option>
			<option value="ILS" '.(($selected == 'ILS')? 'selected':'').'>ILS - Israeli New Sheqel</option>
			<option value="JPY" '.(($selected == 'JPY')? 'selected':'').'>JPY - Japanese Yen</option>
			<option value="MYR" '.(($selected == 'MYR')? 'selected':'').'>MYR - Malaysian Ringgit</option>
			<option value="MXN" '.(($selected == 'MXN')? 'selected':'').'>MXN - Mexican Peso</option>
			<option value="NOK" '.(($selected == 'NOK')? 'selected':'').'>NOK - Norwegian Krone</option>
			<option value="NZD" '.(($selected == 'NZD')? 'selected':'').'>NZD - New Zealand Dollar</option>
			<option value="PHP" '.(($selected == 'PHP')? 'selected':'').'>PHP - Philippine Peso</option>
			<option value="PLN" '.(($selected == 'PLN')? 'selected':'').'>PLN - Polish Zloty</option>
			<option value="GBP" '.(($selected == 'GBP')? 'selected':'').'>GBP - Pound Sterling</option>
			<option value="RUB" '.(($selected == 'RUB')? 'selected':'').'>RUB - Russian Ruble</option>
			<option value="SGD" '.(($selected == 'SGD')? 'selected':'').'>SGD - Singapore Dollar</option>
			<option value="SEK" '.(($selected == 'SEK')? 'selected':'').'>SEK - Swedish Krona</option>
			<option value="CHF" '.(($selected == 'CHF')? 'selected':'').'>CHF - Swiss Franc</option>
			<option value="TWD" '.(($selected == 'TWD')? 'selected':'').'>TWD - Taiwan New Dollar</option>
			<option value="THB" '.(($selected == 'THB')? 'selected':'').'>THB - Thai Baht</option>
			<option value="USD" '.(($selected == 'USD')? 'selected':'').'>USD - U.S. Dollar</option>';
}

function rf_getMonthsOption($year,$month){
	$labelMonths = array(__('January', 'reservation-facile'),__('February', 'reservation-facile'),__('March', 'reservation-facile'),	__('April', 'reservation-facile'),	__('May', 'reservation-facile'),
		__('June', 'reservation-facile'), __('July', 'reservation-facile'), __('August', 'reservation-facile'), __('September', 'reservation-facile'), __('October', 'reservation-facile'),
		__('November', 'reservation-facile'), __('December', 'reservation-facile'));
	$displayed = 0;
	$months = '';
	while($displayed < 12){
		$months .= '<option value="'.$year.'-'.sprintf("%02d",$month).'">'.$labelMonths[$month-1].' '.$year.'</option>';
		$month++;
		if ($month > 12){$month = 1;$year++;}
		$displayed++;
	}
	return $months;
}

function rf_getDefaultOpeningTimes(){
	return [
		[
			[0,["08:00","12:00",false,false],["14:00","18:00",false,false]],
			[0,["08:00","12:00",false,false],["14:00","18:00",false,false]],
			[0,["08:00","12:00",false,false],["14:00","18:00",false,false]]
		],
		[
			[0,["08:00","12:00",false,false],["14:00","18:00",false,false]],
			[0,["08:00","12:00",false,false],["14:00","18:00",false,false]],
			[0,["08:00","12:00",false,false],["14:00","18:00",false,false]]
		],
		[
			[1,["08:00","12:00",false,false],["14:00","18:00",false,false]],
			[0,["08:00","12:00",false,false],["14:00","18:00",false,false]],
			[0,["08:00","12:00",false,false],["14:00","18:00",false,false]]
		],
		[
			[1,["08:00","12:00",false,false],["14:00","18:00",false,false]],
			[0,["08:00","12:00",false,false],["14:00","18:00",false,false]],
			[0,["08:00","12:00",false,false],["14:00","18:00",false,false]]
		],
		[
			[1,["08:00","12:00",false,false],["14:00","18:00",false,false]],
			[0,["08:00","12:00",false,false],["14:00","18:00",false,false]],
			[0,["08:00","12:00",false,false],["14:00","18:00",false,false]]
		],
		[
			[1,["08:00","12:00",false,false],["14:00","18:00",false,false]],
			[0,["08:00","12:00",false,false],["14:00","18:00",false,false]],
			[0,["08:00","12:00",false,false],["14:00","18:00",false,false]]
		],
		[
			[1,["08:00","12:00",false,false],["14:00","18:00",false,false]],
			[0,["08:00","12:00",false,false],["14:00","18:00",false,false]],
			[0,["08:00","12:00",false,false],["14:00","18:00",false,false]]
		]
	];

}

//----PRICE
function rf_displayPrice($price, $currency, $idSpan, $property="innerHTML"){
	if ($currency == ''){return '';}
	$script = '<script>var x = '.$price.';';
	$script .= 'x = x.toLocaleString("none",{ style: "currency", currency: "'.$currency.'"});';
	$script .= 'document.getElementById("'.$idSpan.'").'.$property.' = x;';
	$script .= '</script>';
	return $script;
}

function rf_getSpaceTotalPrice($date_arrivee,$date_depart,$periodesprices,$prix_de_la_place,$nb_de_place,$timeUnit,$dayprice){
	$total = 0;
	$tabPeriodes = explode('--o--',$periodesprices);
	$periodespricesnew = '';
	$tabDayprice = explode('--o--',$dayprice);
	$dateParcours = substr($date_arrivee,0,16);
	$dateParcoursFinish = date('Y-m-d H:i', strtotime($dateParcours . ' +1 day'));
	$indexLastPeriod = -1;
	while($dateParcours <= $date_depart){
		if ($indexLastPeriod > -1){
			$pplp = explode(';', $tabPeriodes[$indexLastPeriod]);
			if ($dateParcours > $pplp[1]){
				$periodespricesnew .= $pplp[0] . ';' . $pplp[1] . ';' . $pplp[2] . '--o--';
				$indexLastPeriod = -1;
			}
		}
		
		$start = '';
		for($i = 0; $i < strlen($tabDayprice[0]); $i++){
			$numDay = substr($tabDayprice[0],$i,1);
			if (date("w", strtotime($dateParcours)) == $numDay){
				$start = $dateParcours;
				$finish = date('Y-m-d H:i', strtotime($start . ' +1 day'));
				$tabPrices = explode(';',$tabDayprice[1]);
				$ignorePeriod = false;
				for($j = 0; $j < strlen($tabDayprice[2]); $j++){
					if (substr($tabDayprice[2],$j,1) == substr($tabDayprice[0],$i,1)){
						$ignorePeriod = true;
					}
				}
				break;
			}
		}		
		$indexPeriode = -1;
		foreach($tabPeriodes as $index => $period){
			if ($period != ''){
				$pp = explode(';',$period);
				if (($dateParcours <= $pp[0]) && ($dateParcoursFinish > $pp[0])){
					$indexPeriode = $index;
					$indexLastPeriod = $index;
					break;
				}
				if (($dateParcours >= $pp[0]) && ($dateParcours < $pp[1])){
					$indexPeriode = $index;
					$indexLastPeriod = $index;
					break;
				}
			}
		}
		if ($start != ''){
			if ($indexPeriode == -1){
				if ($indexLastPeriod > -1){
					$pplp = explode(';', $tabPeriodes[$indexLastPeriod]);
					$periodespricesnew .= $pplp[0] . ';' . $pplp[1] . ';' . $pplp[2] . '--o--';
					$indexLastPeriod = -1;
				}
				$periodespricesnew .= $start . ';' . $finish . ';' . $tabPrices[$numDay] . '--o--';
			}else{
				$pp = explode(';',$tabPeriodes[$indexPeriode]);
				if ($ignorePeriod){
					if (($start < $pp[0]) && ($finish >= $pp[0])){
						$periodespricesnew .= $start . ';' . $finish . ';' . $tabPrices[$numDay] . '--o--';
						if ($finish < $pp[1]){
							$tabPeriodes[$indexPeriode] = $finish . ';' . $pp[1] . ';' . $pp[2];
						}else{
							$indexLastPeriod = -1;
						}
					}
					if (($start >= $pp[0]) && ($start < $pp[1])){
						$periodespricesnew .= $pp[0] . ';' . $start . ';' . $pp[2] . '--o--';
						$periodespricesnew .= $start . ';' . $finish . ';' . $tabPrices[$numDay] . '--o--';
						if ($finish < $pp[1]){
							$tabPeriodes[$indexPeriode] = $finish . ';' . $pp[1] . ';' . $pp[2];
						}else{
							$indexLastPeriod = -1;
						}
						
					}
				}else{
					if (($start < $pp[0]) && ($finish >= $pp[0])){
						$periodespricesnew .= $start . ';' . $pp[0] . ';' . $tabPrices[$numDay] . '--o--';
						if ($finish > $pp[1]){
							$periodespricesnew .= $pp[0] . ';' . $pp[1] . ';' . $pp[2] . '--o--';
							$indexLastPeriod = -1;
							$periodespricesnew .= $pp[1] . ';' . $finish . ';' . $tabPrices[$numDay] . '--o--';
						}else{
							$tabPeriodes[$indexPeriode] = $finish . ';' . $pp[1] . ';' . $pp[2];
						}
					}
					if (($start >= $pp[0]) && ($start < $pp[1])){
						if ($finish > $pp[1]){
							$periodespricesnew .= $pp[1] . ';' . $finish . ';' . $tabPrices[$numDay] . '--o--';
						}
					}
				}
			}
		}
		$dateParcours = date('Y-m-d H:i', strtotime($dateParcours . ' +1 day'));
		$dateParcoursFinish = date('Y-m-d H:i', strtotime($dateParcours . ' +1 day'));
	}
	if ($indexLastPeriod > -1){
		$pplp = explode(';', $tabPeriodes[$indexLastPeriod]);
		$periodespricesnew .= $pplp[0] . ';' . $pplp[1] . ';' . $pplp[2] . '--o--';
	}
	$tabPeriodes = explode('--o--',$periodespricesnew);
	if ($timeUnit == 0){
		foreach($tabPeriodes as $period){
			if ($period != ''){
				$pp = explode(';',$period);
				if (($date_arrivee >= $pp[0]) && ($date_arrivee <= $pp[1])){
					return $pp[2];
				}
			}
		}
		return $prix_de_la_place;
	}else{
		$cursorDeb = $date_arrivee;
		$cursorFin = $date_depart;
		foreach($tabPeriodes as $period){
			if ($period != ''){
				$pp = explode(';',$period);
				if (($cursorDeb <= $pp[0]) && ($cursorFin >= $pp[0])){
					$total += rf_getTotalPeriod($cursorDeb,$pp[0],$prix_de_la_place,$timeUnit);
					if ($cursorFin <= $pp[1]){
						$total += rf_getTotalPeriod($pp[0],$cursorFin,$pp[2],$timeUnit);
						return $total;
					}else{
						$total += rf_getTotalPeriod($pp[0],$pp[1],$pp[2],$timeUnit);
						$cursorDeb = $pp[1];
					}
				}elseif (($cursorDeb >= $pp[0]) && ($cursorDeb <= $pp[1])){
					if ($cursorFin <= $pp[1]){
						$total += rf_getTotalPeriod($cursorDeb,$cursorFin,$pp[2],$timeUnit);
						return $total;
					}else{
						$total += rf_getTotalPeriod($cursorDeb,$pp[1],$pp[2],$timeUnit);
						$cursorDeb = $pp[1];
					}
				}
			}
		}
		$total += rf_getTotalPeriod($cursorDeb,$cursorFin,$prix_de_la_place,$timeUnit);
		return $total;
	}
}

function rf_getTotalPeriod($depart,$fin, $prix, $unite){
	$dateDeb = new DateTime($depart);
	$dateFin = new DateTime($fin);
	$duree = round(($dateFin->format('U') - $dateDeb->format('U')) / 3600,3);
	$total = $duree * $prix / $unite;
	return $total;
}

//----DATE & TIME

function rf_displayDate($date, $idSpan, $property="innerHTML"){
	$year = substr($date,0,4);
	$month = substr($date,5,2);
	$day = substr($date,8,2);
	if (strlen($date) > 10){
		$hour = substr($date,11,2);
		$minute = substr($date,14,2);
	}else{
		$hour = 0; $minute = 0; $second = 0;
	}
	$script = '<script>var d = new Date('.$year.', '.($month-1).', '.$day.', '.$hour.', '.$minute.', 0);';
	$script .= 'd = d.toLocaleString();';
	$script .= 'd = d.substr(0,d.length-3);';
	$script .= 'document.getElementById("'.$idSpan.'").'.$property.' = d;';
	$script .= '</script>';
	return $script;
}

function rf_formListHours($selected, $start=0, $end=23){
	$start = (int) substr($start,0,2);
	$end = (int) substr($end,0,2);
	if ($end >= 24){return '';}
	$list = '';
	$i = $start;
	do{
		$j = sprintf("%02d",$i);
		$selectedOption = ($j == $selected)? 'selected' : '';
		$list .= '<option value="'.$j.'" '.$selectedOption.'>'.$j.'</option>';
		$i++;
		if ($i == 24){
			$i = 0;
		}
	}while($i != $end);
	$j = sprintf("%02d",$i);
	$selectedOption = ($j == $selected)? 'selected' : '';
	$list .= '<option value="'.$j.'" '.$selectedOption.'>'.$j.'</option>';
	return $list;
}

function rf_formListMinutes($selected, $step){
	$list = '';
	for($i = 0; $i < 60; $i+=$step){
		$j = sprintf("%02d",$i);
		$selectedOption = ($j == $selected)? 'selected' : '';
		$list .= '<option value="'.$j.'" '.$selectedOption.'>'.$j.'</option>';
	}
	return $list;
}

function rf_formListDuration($selected, $min, $max, $step){
	$list = '';
	for($i = $min; $i <= $max; $i+=$step){
		$j = rf_heure2duree($i / 60,false);
		$selectedOption = ($i == $selected)? 'selected' : '';
		$list .= '<option value="'.$i.'" '.$selectedOption.'>'.$j.'</option>';
	}
	return $list;
}

function rf_cal_days_in_month($month,$year){
	return date('t', mktime(0, 0, 0, (int)$month, 1, (int)$year));
}

function rf_heure2duree($heures, $displayTotalHours=true){
	$val = $heures;
	if (floor($val) <= 1){
		$unit = __('hr', 'reservation-facile');
	}else{
		$unit = __('hrs', 'reservation-facile');
	}
	$coeff = 1;
	if ($val >= 24){
		$coeff = 24;
		$val = $val / 24;
		if (floor($val) <= 1){
			$unit = __('day', 'reservation-facile');
		}else{
			$unit = __('days', 'reservation-facile');
		}
		if ($val >= 7){
			$coeff = 168;
			$val = $val / 7;
			if (floor($val) <= 1){
				$unit = __('week', 'reservation-facile');
			}else{
				$unit = __('weeks', 'reservation-facile');
			}
		}
	}
	$val = floor($val);
	$reste = $heures - ($val * $coeff);
	if ($reste >= 1){
		$reste = ' ' . __('and','reservation-facile') . ' ' . rf_heure2duree($reste,false);
	}else if ($reste > 0){
		$reste = sprintf("%02d", round($reste*60)). ' ' . __('min','reservation-facile');
	}else{
		$reste = '';
	}
	if (($displayTotalHours)&&($heures >= 24)){
		$reste .=  ' ('.round(($heures+0),2). ' ' . __('hours', 'reservation-facile').')';
	}
	if (($val == 0) && ($reste != '')){
		$val = '';
		$unit = '';
		if (substr($reste,0,2) == ', '){
			$reste = substr($reste,2);
		}
	}
	return $val . ' ' . $unit. $reste;
}

function rf_getTimeStamp($date){
	$year = substr($date,0,4);
	$month = substr($date,5,2);
	$day = substr($date,8,2);
	$hour = substr($date,11,2);
	$minute = substr($date,14,2);
	$second = substr($date,17,2);
	return mktime((int)$hour,(int)$minute,(int)$second,(int)$month,(int)$day,(int)$year);
}

function rf_getAutomaticQty($code,$originalQty,$dateParcours,$finParcours,$nb_de_place){
	if ($code == 'oneperhour'){
		$nbHeures = 0;
		while($dateParcours < $finParcours){
			$nbHeures++;
			$dateParcours = date('Y-m-d H:i:s', strtotime($dateParcours . ' +1 hour'));
		}
		if ($nbHeures == 0){$nbHeures = 1;}
		return $nbHeures * $nb_de_place;
	}

	if (($code == 'oneperday') || ($code == 'onepernight')){
		$nbJours = 0;
		while($dateParcours < $finParcours){
			$nbJours++;
			$dateParcours = date('Y-m-d', strtotime($dateParcours . ' +1 day'));
		}
		if ($code == 'onepernight'){$nbJours--;}
		if ($nbJours == 0){$nbJours = 1;}
		return $nbJours * $nb_de_place;
	}
	
	if ($code == 'oneperweek'){
		$nbSemaines = 0;
		while($dateParcours < $finParcours){
			$nbSemaines++;
			$dateParcours = date('Y-m-d', strtotime($dateParcours . ' +1 week'));
		}
		if ($nbSemaines == 0){$nbSemaines = 1;}
		return $nbSemaines * $nb_de_place;
	}
	
	if ($code == 'onepermonth'){
		$nbMois = 0;
		while($dateParcours < $finParcours){
			$nbMois++;
			$dateParcours = date('Y-m-d', strtotime($dateParcours . ' +1 month'));
		}
		if ($nbMois == 0){$nbMois = 1;}
		return $nbMois * $nb_de_place;
	}
	
	
	
	return $originalQty;
}

function rf_checkDateInOT($OT,$datetime,$type,$EC){
	$ts_date_parcours = mktime((int)substr($datetime,11,2),(int)substr($datetime,14,2),0,(int)substr($datetime,5,2),(int)substr($datetime,8,2),(int)substr($datetime,0,4));
	$time = substr($datetime,11,5);
	$numDay = getdate($ts_date_parcours)["wday"];
	
	$arrivalPresent = false;
	for($i = 0; $i < sizeof($OT); $i++){
		if (($OT[$i][1][0] != "0") && (sizeof($OT[$i][1]) > 1)){
			$arrivalPresent = true;
		}
	}
	$departurePresent = false;
	for($i = 0; $i < sizeof($OT); $i++){
		if (($OT[$i][2][0] != "0") && (sizeof($OT[$i][2]) > 1)){
			$departurePresent = true;
		}
	}
	$tabClosure = explode('--o--',$EC);
	foreach($tabClosure as $closure){
		$close = explode(';',$closure);
		if (isset($close[1])){
			$debut = $close[0];
			$fin = $close[1];
			if (($datetime >= $debut) && ($datetime < $fin)){
				return false;
			}
		}
	}
	if ((sizeof($OT[$numDay][$type]) > 1) && ($OT[$numDay][$type][0] != "0")){
		for($i = 1; $i < sizeof($OT[$numDay][$type]); $i++){
			if ($OT[$numDay][$type][$i][1] == '00:00'){
				$OT[$numDay][$type][$i][1] = '24:00';
			}
			if (($time >= $OT[$numDay][$type][$i][0]) && ($time <= $OT[$numDay][$type][$i][1])){
				return true;
			}
		}
	}elseif (($type > 0) && (!$arrivalPresent) && (!$departurePresent)){
		$type = 0;
		if ($OT[$numDay][$type][0] == "0"){return false;}
		if (sizeof($OT[$numDay][$type]) > 1){
			for($i = 1; $i < sizeof($OT[$numDay][$type]); $i++){
				if ($OT[$numDay][$type][$i][1] == '00:00'){
					$OT[$numDay][$type][$i][1] = '24:00';
				}
				if (($time >= $OT[$numDay][$type][$i][0]) && ($time <= $OT[$numDay][$type][$i][1])){
					return true;
				}
			}
		}	
	}
	return false;
}

function rf_getReferenceTimeInOT($OT,$datetime,$type,$dureeMin,$dateDebut,$user_minutes_interval,$exceptionalclosure){
	$datetime_bak = $datetime;
	$datetime = mktime((int)substr($datetime,11,2),(int)substr($datetime,14,2),0,(int)substr($datetime,5,2),(int)substr($datetime,8,2),(int)substr($datetime,0,4));
	$dateDebut = mktime((int)substr($dateDebut,11,2),(int)substr($dateDebut,14,2),0,(int)substr($dateDebut,5,2),(int)substr($dateDebut,8,2),(int)substr($dateDebut,0,4));
	$numDay = getdate($datetime)["wday"];
	$refTime = '00:00';	
	if (($type == "2") && ($user_minutes_interval > 0)){
		while($datetime <= $dateDebut){
			$datetime += $user_minutes_interval * 60;
		}
		if ($dureeMin > 0){
			while(($datetime-$dateDebut) < ($dureeMin * 3600)){
				$datetime += $user_minutes_interval * 60;
			}
		}
		$nbTentative = 10080 / $user_minutes_interval;
		$compteur = 0;
		while(($compteur < $nbTentative) && (!rf_checkDateInOT($OT,date('Y-m-d H:i:s',$datetime),$type,$exceptionalclosure))){
			$datetime += $user_minutes_interval * 60;
			$compteur++;
		}
		if ($compteur < $nbTentative){
			return sprintf("%02d",getdate($datetime)["hours"]) . ":" . sprintf("%02d",getdate($datetime)["minutes"]);
		}
	}
	if ((sizeof($OT[$numDay][$type]) > 1) && ($OT[$numDay][$type][0] != "0")){
		for($i = 1; $i < sizeof($OT[$numDay][$type]); $i++){
			if ($i == 1){
				$refTime = $OT[$numDay][$type][$i][0];
			}
			if ($OT[$numDay][$type][$i][2] == "true"){
				return $OT[$numDay][$type][$i][0];
			}
			if ($OT[$numDay][$type][$i][3] == "true"){
				if ($OT[$numDay][$type][$i][1] == "00:00"){return "24:00";}
				return $OT[$numDay][$type][$i][1];
			}
		}
	}else{
		for($i = 1; $i < sizeof($OT[$numDay][0]); $i++){
			if ($i == 1){
				$refTime = $OT[$numDay][0][$i][0];
			}
			if ($OT[$numDay][0][$i][2] == "true"){
				return $OT[$numDay][0][$i][0];
			}
			if ($OT[$numDay][0][$i][3] == "true"){
				if ($OT[$numDay][0][$i][1] == "00:00"){return "24:00";}
				return $OT[$numDay][0][$i][1];
			}
		}
	}
	if (($type == "2") && ($refTime == "00:00")){$refTime = "24:00";}
	return $refTime;
}


//----TOOLS
function rf_removeslashes($text){
	$text = stripslashes($text);
	$text = htmlspecialchars($text);
	return $text;
}

function rf_isNotEmpty($val){
	if (!isset($val)){return false;}
	if (($val == null) || ($val == 'null')){return false;}
	if ($val == "0"){return false;}
	if ($val == array()){return false;}
	if ($val == ""){return false;}
	return true;
}

function rf_email($to, $subject, $message){
	$from = "no-reply@".$_SERVER['HTTP_HOST'];
	$headers = array('Content-Type: text/html; charset=UTF-8');
	$headers[] = 'From: "'.(explode('@',$from)[1]).'"<'.$from.'>';
	wp_mail( $to, $subject, $message, $headers );
}

function rf_disable_emojis() {
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
}
add_action( 'init', 'rf_disable_emojis' );

//----ADMIN NOTICES
function rf_addAdminNotice($text,$level,$type=''){
	global $rf_adminNotice;
	$rf_adminNotice[] = array("text" => $text, "level" => $level, "type" => $type);
}

function rf_displayAdminNotice($level=-1){
	global $rf_adminNotice;
	$aff = '';
	foreach($rf_adminNotice as $notice){
		$class = "rf_adminNotice";
		$img = '<img src="'.plugins_url( 'img/check.png', __FILE__ ).'"> ';
		if ($notice["type"] == "error"){
			$class = "rf_adminNoticeError";
			$img = '';
		}
		if ($notice["type"] == "warning"){
			$class = "rf_adminNoticeWarning";
			$img = '';
		}
		if (($level == -1)||($level == $notice["level"])){
			$aff .= '<div class="'.$class.'">' . $img . $notice["text"] . '<img class="rf_btnClose" onclick="rf_closeAdminNotice(this)" src="'.plugins_url( 'img/close.png', __FILE__ ).'"></div>';
			unset($notice);
		}
	}
	return $aff;
}

//----LOAD PARAMETERS
function rf_getAllParameters(){
	global $wpdb;
	global $activating;
	$options = array();
	$result = $wpdb->get_results("SHOW TABLES LIKE '{$wpdb->prefix}rf_parameters'");
	if (sizeof($result) > 0){
		$results = $wpdb->get_results("SELECT nom, val FROM {$wpdb->prefix}rf_parameters");
		foreach($results as $result){
			$options[$result->nom] = $result->val;
		}
	}else{
		$activating = true;
	}
	return $options;
}

function rf_getParameter($name){
	global $rf_parameters;
	if (!rf_isNotEmpty($rf_parameters)){
		$rf_parameters = rf_getAllParameters();
	}
	if (isset($rf_parameters[$name])){
		return rf_removeslashes($rf_parameters[$name]);
	}else{
		return '';
	}
}

function rf_getCSS($foremail=false){
	global $wpdb;
	$result = '<style>';
	if ($foremail){
		$result .= '.rf_previewBox{max-width: 600px;}
					.rf_previewRow{display: -webkit-box;display: -ms-flexbox;display: -webkit-flex;display: flex;}
					.rf_previewHead{padding: 5px; font-weight: bold;}
					.rf_previewContent{padding: 0 0 20px 30px;}
					form, input[type="text"],input[type="submit"]{display:none;}
					input[disabled]{background-color: transparent!important;border: none!important;width: 100%!important;}
					';
	}
	$rowcss = $wpdb->get_results("SELECT val FROM {$wpdb->prefix}rf_parameters WHERE nom='customcss'");
	if ((isset($rowcss[0]->val)) && (rf_isNotEmpty($rowcss[0]->val))){
		$result .= $rowcss[0]->val;
	}
	$result .= '</style>';
	return $result;
}

include 'rf-functions-security.php';
include 'rf-functions-language.php';
include 'rf-functions-booking.php';

add_action( 'init', 'rf_checkCSVExport' );

function rf_checkCSVExport(){
	global $wpdb;
	global $rf_linkCSV;
	if ((isset($_POST['rf_act'])) && ($_POST['rf_act'] == 'exportCSV')){
		check_admin_referer($_POST['rf_act']);
		function rf_fieldCSV($field,$type){
			if ($type == "s"){
				$field = rf_removeslashes(str_replace(';',',',$field));
			}
			if ($type == "d"){
				$field = str_replace('.',rf_getParameter('decimalseparator'),$field);
			}
			if ($type == "tab"){
				$field = rf_removeslashes(str_replace(';','++o++',$field));
			}
			return $field;
		}
		$exportArrivalDate = '';
		$exportDepartureDate = '';
		$exportBookingStatus = '';
		$tableschoice = '';
		if (isset($_POST['rf_ead'])){
			$exportArrivalDate = rf_secureData($_POST['rf_ead'],'date');
			if ($exportArrivalDate[0]){
				$exportArrivalDate = $exportArrivalDate[2];
			}
		}
		if (isset($_POST['rf_edd'])){
			$exportDepartureDate = rf_secureData($_POST['rf_edd'],'date');
			if ($exportDepartureDate[0]){
				$exportDepartureDate = $exportDepartureDate[2];
			}
		}
		if (isset($_POST['rf_ebs'])){
			$exportBookingStatus = rf_secureData($_POST['rf_ebs'],'text');
			if ($exportBookingStatus[0]){
				$exportBookingStatus = $exportBookingStatus[2];
			}
		}
		if (isset($_POST['tableschoice'])){
			$tableschoice = rf_secureData($_POST['tableschoice'],'text');
			if ($tableschoice[0]){
				$tableschoice = $tableschoice[2];
			}
		}
		$sqlVar = ['none'];
		$sqlWhere = '';
		if ($exportDepartureDate != ''){
			if ($sqlWhere != ''){$sqlWhere .= ' AND ';}else{$sqlWhere = 'WHERE ';}
			$sqlWhere .= 'date_depart >= %s';
			$sqlVar[] = $exportDepartureDate.' 00:00:00';
		}
		if ($exportArrivalDate != ''){
			if ($sqlWhere != ''){$sqlWhere .= ' AND ';}else{$sqlWhere = 'WHERE ';}
			$sqlWhere .= 'date_arrivee <= %s';
			$sqlVar[] = $exportArrivalDate.' 23:59:59';
		}
		if ($exportBookingStatus != ''){
			if ($sqlWhere != ''){$sqlWhere .= ' AND ';}else{$sqlWhere = 'WHERE ';}
			$sqlWhere .= 'statut = %s';
			$sqlVar[] = $exportBookingStatus;
		}
		$resultats = $wpdb->get_results($wpdb->prepare("SELECT %s, res.*, GROUP_CONCAT(CONCAT(mpb.id,',',mpb.label,',',mpb.description,',',mpb.quantite,',',mpb.montant,',',mpb.pourcentage,',',mpb.periode_heure,',',mpb.code,',',mpb.type,',',mpb.details_texte) SEPARATOR '--o--') AS tabmpb FROM {$wpdb->prefix}rf_bookings res LEFT JOIN {$wpdb->prefix}rf_modifyprice_bookings mpb ON mpb.id_reservation = res.id $sqlWhere GROUP BY res.id ORDER BY date DESC, rf_idSpace DESC",$sqlVar));		
		$domain = $_SERVER['SERVER_NAME'];
		$filename = 'reservationfacile-' . $domain . '-bookings.csv';
		if ($fh = fopen( plugin_dir_path(__FILE__) . 'exports/' . $filename, 'w+' )){
			fprintf( $fh, chr(0xEF) . chr(0xBB) . chr(0xBF) );
			fprintf( $fh, chr(0xEF) . chr(0xBB) . chr(0xBF) );
			$content_CSV = array('bookings',
				__('Id','reservation-facile'),
				__('Internal reference','reservation-facile'),
				__('Date','reservation-facile'),
				__('Number of places','reservation-facile'),
				__('Unit price','reservation-facile'),
				__('Currency','reservation-facile'),
				__('Deposit requested (amount)','reservation-facile'),
				__('Deposit requested (percentage)','reservation-facile'),
				__('Arrival date','reservation-facile'),
				__('Departure date','reservation-facile'),
				__('Number of people','reservation-facile'),
				__('Last name','reservation-facile'),
				__('First name','reservation-facile'),
				__('Address','reservation-facile'),
				__('Zip code','reservation-facile'),
				__('City','reservation-facile'),
				__('Country','reservation-facile'),
				__('Email','reservation-facile'),
				__('Tel.','reservation-facile'),
				__('Status','reservation-facile'),
				__('Id place','reservation-facile'),
				__('Location','reservation-facile'),
				__('Place','reservation-facile'),
				__('Comments','reservation-facile'),
				__('Price changes','reservation-facile'),
				__('Price according to periods','reservation-facile'),
				__('Price according to days','reservation-facile'),
				__('Time unit (in hours)','reservation-facile')
				);
			fputcsv( $fh, $content_CSV, ';');
			foreach($resultats as $r){				
				$content_CSV = array('',rf_fieldCSV($r->id,'d'), rf_fieldCSV($r->reference_interne,'s'), rf_fieldCSV($r->date,'s'), rf_fieldCSV($r->nb_de_place,'d'), rf_fieldCSV($r->prix_de_la_place,'d'), rf_fieldCSV($r->devise,'s'), rf_fieldCSV($r->acompte_prix,'d'), rf_fieldCSV($r->acompte_pourcentage,'d'), rf_fieldCSV($r->date_arrivee,'s'), rf_fieldCSV($r->date_depart,'s'), rf_fieldCSV($r->nb_de_personnes,'d'), rf_fieldCSV($r->nom,'s'), rf_fieldCSV($r->prenom,'s'), rf_fieldCSV($r->adresse,'s'), rf_fieldCSV($r->code_postal,'s'), rf_fieldCSV($r->ville,'s'), rf_fieldCSV($r->pays,'s'), rf_fieldCSV($r->email,'s'), rf_fieldCSV($r->telephone,'s'), rf_fieldCSV($r->statut,'s'), rf_fieldCSV($r->rf_idSpace,'d'), rf_fieldCSV($r->lieu,'s'), rf_fieldCSV($r->emplacement,'s'), rf_fieldCSV($r->remarques,'s'), $r->tabmpb, rf_fieldCSV($r->periodesprices,'tab'), rf_fieldCSV($r->dayprice,'tab'), rf_fieldCSV($r->timeUnit,'d'));
				fputcsv( $fh, $content_CSV, ';');
			}
			fclose( $fh );
		}
		$rf_linkCSV = $filename;		
		if ($tableschoice == 'all'){
			$rf_linkCSV = "";			
			//----------------------PLACES----------------------------//
			$resultats = $wpdb->get_results("SELECT emp.*, loc.id as id_location, loc.nom, GROUP_CONCAT(mps.id_modificationprix) AS PriceChangesListIds FROM {$wpdb->prefix}rf_spaces emp INNER JOIN {$wpdb->prefix}rf_locations loc ON loc.id=emp.id_lieu LEFT JOIN {$wpdb->prefix}rf_modifyprice_spaces mps ON mps.rf_idSpace = emp.id GROUP BY emp.id");
			$filename = 'reservationfacile-' . $domain . '-places.csv';
			if ($fh = fopen( plugin_dir_path(__FILE__) . 'exports/' . $filename, 'w+' )){
				fprintf( $fh, chr(0xEF) . chr(0xBB) . chr(0xBF) );
				fprintf( $fh, chr(0xEF) . chr(0xBB) . chr(0xBF) );
				$content_CSV = array('places',
					__('Id','reservation-facile'),
					__('Id location','reservation-facile'),
					__('Location name','reservation-facile'),
					__('Place label','reservation-facile'),
					__('Number of places','reservation-facile'),
					__('Unit price of the place','reservation-facile'),
					__('Currency','reservation-facile'),
					__('Time unit (in hours)','reservation-facile'),
					__('Min. booking duration (in hours)','reservation-facile'),
					__('Max. booking duration (in hours)','reservation-facile'),
					__('Deposit requested (amount)','reservation-facile'),
					__('Deposit requested (percentage)','reservation-facile'),
					__('Link to the general Terms of Use','reservation-facile'),
					__('Start date of availability','reservation-facile'),
					__('End date of availability','reservation-facile'),
					__('Description / Details','reservation-facile'),
					__('Default status of a new booking','reservation-facile'),
					__('Receive email','reservation-facile'),
					__('Email notification','reservation-facile'),
					__('Payment instructions','reservation-facile'),
					__('Interval in minutes for the user','reservation-facile'),
					__('Prices according to days','reservation-facile'),
					__('Prices according to periods','reservation-facile'),
					__('Form: Booking start time','reservation-facile'),
					__('Form: Booking end time','reservation-facile'),
					__('Form: Number of people','reservation-facile'),
					__('Form: Last name','reservation-facile'),
					__('Form: First name','reservation-facile'),
					__('Form: Address','reservation-facile'),
					__('Form: Zip code','reservation-facile'),
					__('Form: City','reservation-facile'),
					__('Form: Country','reservation-facile'),
					__('Form: Email','reservation-facile'),
					__('Form: Tel.','reservation-facile'),
					__('Form: Notes','reservation-facile'),
					__('Form: Number of places','reservation-facile'),
					__('Display: Start date of availability','reservation-facile'),
					__('Display: End date of availability','reservation-facile'),
					__('Display: Price of the place','reservation-facile'),
					__('Display: Deposit (Price)','reservation-facile'),
					__('Display: Deposit (Percentage)','reservation-facile'),
					__('Display: Time unit','reservation-facile'),
					__('Display: Min. booking duration','reservation-facile'),
					__('Display: Max. booking duration','reservation-facile'),
					__('Display: Description / Details','reservation-facile'),
					__('Display: Calendar','reservation-facile'),
					__('Opening Times','reservation-facile'),
					__('Exceptional closure','reservation-facile'),
					__('Price changes Ids','reservation-facile')
					);
				fputcsv( $fh, $content_CSV, ';');
				foreach($resultats as $r){
					$content_CSV = array('',
						rf_fieldCSV($r->id,'d'), 
						rf_fieldCSV($r->id_location,'d'), 
						rf_fieldCSV($r->nom,'s'), 
						rf_fieldCSV($r->label,'s'), 
						rf_fieldCSV($r->nb_de_place,'d'), 
						rf_fieldCSV($r->prix_de_la_place,'d'), 
						rf_fieldCSV($r->devise,'s'), 
						rf_fieldCSV($r->timeUnit,'d'), 
						rf_fieldCSV($r->minBookingDuration,'d'), 
						rf_fieldCSV($r->tps_reservation_max_heure,'d'), 
						rf_fieldCSV($r->acompte_prix,'d'),
						rf_fieldCSV($r->acompte_pourcentage,'d'),
						rf_fieldCSV($r->lien_CGU,'s'),
						rf_fieldCSV($r->date_debut_reservation,'s'),
						rf_fieldCSV($r->date_fin_reservation,'s'),
						rf_fieldCSV($r->description,'s'),
						rf_fieldCSV($r->statut_par_defaut_reservation,'s'),
						rf_fieldCSV($r->notification_email,'s'),
						rf_fieldCSV($r->email_notification,'s'),
						rf_fieldCSV($r->payment_instructions,'s'),
						rf_fieldCSV($r->user_minutes_interval,'d'),
						rf_fieldCSV($r->dayprice,'tab'),
						rf_fieldCSV($r->periodesprices,'tab'),
						rf_fieldCSV($r->form_heure_debut,'d'),
						rf_fieldCSV($r->form_heure_fin,'d'),
						rf_fieldCSV($r->form_personnes,'d'),
						rf_fieldCSV($r->form_nom,'d'),
						rf_fieldCSV($r->form_prenom,'d'),
						rf_fieldCSV($r->form_adresse,'d'),
						rf_fieldCSV($r->form_code_postal,'d'),
						rf_fieldCSV($r->form_ville,'d'),
						rf_fieldCSV($r->form_pays,'d'),
						rf_fieldCSV($r->form_email,'d'),
						rf_fieldCSV($r->form_telephone,'d'),
						rf_fieldCSV($r->form_remarques,'d'),
						rf_fieldCSV($r->form_nb_de_place,'d'),
						rf_fieldCSV($r->info_date_debut_reservation,'d'),
						rf_fieldCSV($r->info_date_fin_reservation,'d'),
						rf_fieldCSV($r->info_prix_de_la_place,'d'),
						rf_fieldCSV($r->info_acompte_prix,'d'),
						rf_fieldCSV($r->info_acompte_pourcentage,'d'),
						rf_fieldCSV($r->info_timeUnit,'d'),
						rf_fieldCSV($r->info_minBookingDuration,'d'),
						rf_fieldCSV($r->info_tps_reservation_max_heure,'d'),
						rf_fieldCSV($r->info_description,'d'),
						rf_fieldCSV($r->info_calendrier,'d'),
						rf_fieldCSV($r->openingtimes,'s'),
						rf_fieldCSV($r->exceptionalclosure,'tab'),
						rf_fieldCSV($r->PriceChangesListIds,'s')
					);
					fputcsv( $fh, $content_CSV, ';');
				}
				fclose( $fh );
			}
			
			//----------------------SHORTCODES----------------------------//
			$resultats = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}rf_shortcodes");
			$filename = 'reservationfacile-' . $domain . '-shortcodes.csv';
			if ($fh = fopen( plugin_dir_path(__FILE__) . 'exports/' . $filename, 'w+' )){
				fprintf( $fh, chr(0xEF) . chr(0xBB) . chr(0xBF) );
				fprintf( $fh, chr(0xEF) . chr(0xBB) . chr(0xBF) );
				$content_CSV = array('shortcodes',
					__('Id','reservation-facile'),
					__('Name','reservation-facile'),
					__('Display','reservation-facile'),
					__('Places','reservation-facile'),
					);
				fputcsv( $fh, $content_CSV, ';');
				foreach($resultats as $r){
					$content_CSV = array('',
						rf_fieldCSV($r->id,'d'), 
						rf_fieldCSV($r->nom,'s'), 
						rf_fieldCSV($r->affichage,'s'), 
						rf_fieldCSV(str_replace('"',"'",$r->tabEmplacements),'s')
					);
					fputcsv( $fh, $content_CSV, ';');
				}
				fclose( $fh );
			}
			
			//----------------------PRICE CHANGES----------------------------//
			$resultats = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}rf_modifyprice");
			$filename = 'reservationfacile-' . $domain . '-pricechanges.csv';
			if ($fh = fopen( plugin_dir_path(__FILE__) . 'exports/' . $filename, 'w+' )){
				fprintf( $fh, chr(0xEF) . chr(0xBB) . chr(0xBF) );
				fprintf( $fh, chr(0xEF) . chr(0xBB) . chr(0xBF) );
				$content_CSV = array('pricechanges',
					__('Id','reservation-facile'),
					__('Label','reservation-facile'),
					__('Description','reservation-facile'),
					__('Start date','reservation-facile'),
					__('End date','reservation-facile'),
					__('Quantity / Max. quantity','reservation-facile'),
					__('Initial quantity','reservation-facile'),
					__('Amount','reservation-facile'),
					__('Percentage','reservation-facile'),
					__('Periodicity','reservation-facile'),
					__('Code / Automatic Quantity','reservation-facile'),
					__('Type','reservation-facile'),
					__('Options details','reservation-facile'),
					);
				fputcsv( $fh, $content_CSV, ';');
				foreach($resultats as $r){
					$content_CSV = array('',
						rf_fieldCSV($r->id,'d'),
						rf_fieldCSV($r->label,'s'),
						rf_fieldCSV($r->description,'s'),
						rf_fieldCSV($r->date_debut,'s'),
						rf_fieldCSV($r->date_fin,'s'),
						rf_fieldCSV($r->quantite,'d'),
						rf_fieldCSV($r->quantite_initiale,'d'),
						rf_fieldCSV($r->montant,'d'),
						rf_fieldCSV($r->pourcentage,'d'),
						rf_fieldCSV($r->periode_heure,'d'),
						rf_fieldCSV($r->code,'s'),
						rf_fieldCSV($r->type,'s'),
						rf_fieldCSV($r->details_texte,'s'),
					);
					fputcsv( $fh, $content_CSV, ';');
				}
				fclose( $fh );
			}	
			//----------------------PARAMETERS----------------------------//
			$resultats = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}rf_parameters");
			$filename = 'reservationfacile-' . $domain . '-parameters.csv';
			if ($fh = fopen( plugin_dir_path(__FILE__) . 'exports/' . $filename, 'w+' )){
				fprintf( $fh, chr(0xEF) . chr(0xBB) . chr(0xBF) );
				fprintf( $fh, chr(0xEF) . chr(0xBB) . chr(0xBF) );
				$content_CSV = array('parameters',
					__('Id','reservation-facile'),
					__('Name','reservation-facile'),
					__('Value','reservation-facile'),
					);
				fputcsv( $fh, $content_CSV, ';');
				foreach($resultats as $r){
					$content_CSV = array('',
						rf_fieldCSV($r->id,'d'),
						rf_fieldCSV($r->nom,'s'),
						rf_fieldCSV($r->val,'s'),
					);
					fputcsv( $fh, $content_CSV, ';');
				}
				fclose( $fh );
			}
		}
	}
}