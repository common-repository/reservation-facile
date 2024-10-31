document.addEventListener("DOMContentLoaded", rf_initForm);
function rf_initForm(){
	if (document.getElementById("rf_form_date_debut")){
		document.getElementById("rf_form_date_debut").addEventListener("change",rf_getAvailableArrivalHours);
		if (document.getElementById("rf_form_date_debut").value != ""){
			var e = {target: {value: document.getElementById("rf_form_date_debut").value}};
			rf_getAvailableArrivalHours(e);
		}
	}
	if (document.getElementById("rf_form_date_fin")){
		document.getElementById("rf_form_date_fin").addEventListener("change",rf_getAvailableDepartureHours);
		if (document.getElementById("rf_form_date_fin").value != ""){
			var e = {target: {value: document.getElementById("rf_form_date_fin").value}};
			rf_getAvailableDepartureHours(e);
		}
	}
	
}

function rf_getAvailableArrivalHours(e){
	if (document.getElementById("rf_form_heure_debutp1")){
		var hourSelected = "99:99";
		if ((document.getElementById("rf_form_heure_debut")) && (document.getElementById("rf_form_heure_debut").value)){
			hourSelected = document.getElementById("rf_form_heure_debut").value;
		}
		var arrivalDate = e.target.value;
		arrivalDate = arrivalDate.split("-");
		var arrival = new Date(arrivalDate[0],arrivalDate[1]-1,arrivalDate[2]);
		if (arrival.getFullYear() >= 2018){
			var day = arrival.getDay();
			var OTDay = rf_openingtimes[day];
			var minHourArrival = '23:59';
			var maxHourArrival = '00:00';
			if ((OTDay[1].length > 1) && (OTDay[1][0] != "0")){
				for(var i = 1; i < OTDay[1].length; i++){
					if (OTDay[1][i][0] < minHourArrival){
						minHourArrival = OTDay[1][i][0];
					}
					if (OTDay[1][i][1] > maxHourArrival){
						maxHourArrival = OTDay[1][i][1];
					}
				}
			}else{
				for(var i = 1; i < OTDay[0].length; i++){
					if (OTDay[0][i][0] < minHourArrival){
						minHourArrival = OTDay[0][i][0];
					}
					if (OTDay[0][i][1] > maxHourArrival){
						maxHourArrival = OTDay[0][i][1];
					}
				}
			}		
			if (minHourArrival == '23:59'){minHourArrival = '00:00';}
			if (maxHourArrival == '00:00'){maxHourArrival = '24:00';}
			minHourArrival = minHourArrival.substr(0,2);
			maxHourArrival = maxHourArrival.substr(0,2);
			var selectHourContent = '';
			for(var i=minHourArrival; i<=maxHourArrival;i++){
				selected = "";
				if (hourSelected.substr(0,2) == ("0" + i).slice(-2)){
					selected = "selected";
				}
				selectHourContent += '<option ' + selected + ' value="' + ("0" + i).slice(-2) + '">' + ("0" + i).slice(-2) + '</option>';
			}
			document.getElementById("rf_form_heure_debutp1").innerHTML = selectHourContent;
			document.getElementById("rf_form_heure_debutp1").disabled = false;
			document.getElementById("rf_form_heure_debutp2").disabled = false;
			
		}else{
			document.getElementById("rf_form_heure_debutp1").disabled = true;
			document.getElementById("rf_form_heure_debutp2").disabled = true;
		}
	}
	rf_getAvailableOptions();
}

function rf_getAvailableOptions(){
	if (document.getElementById("rf_getAvailableOptions")){
		var wpnonce = document.getElementById("rf_getAvailableOptions").value;
		var rf_idSpace = document.getElementById("rf_idSpace").value; 
		var arrivalDate = document.getElementById("rf_form_date_debut").value; 
		var data = {
			'action' : 'js_getAvailableOptions',
			'_wpnonce': wpnonce,
			'rf_idSpace': rf_idSpace,
			'arrivalDate': arrivalDate
		};
		if (document.getElementById("rf_optionsBackup")){
			var optionsBackup = document.getElementById("rf_optionsBackup").innerHTML;
			var tabOB = optionsBackup.split("**");
			for(var i = 0; i < tabOB.length; i++){
				if (tabOB[i] != ""){
					var tabOption = tabOB[i].split("++");
					data["form_option_" + tabOption[0]] = tabOption[1];
				}
			}
		}
		if (document.getElementById("rf_couponBackup")){
			data["form_coupon"] = document.getElementById("rf_couponBackup").innerHTML;
		}
		if (document.getElementById("rf_reductionBackup")){
			data["form_reduction"] = document.getElementById("rf_reductionBackup").innerHTML;
		}
		var ajaxurl = WPJS.adminAjaxUrl;
		jQuery.post(ajaxurl, data, function(response) {
			var rep = JSON.parse(response);
			document.getElementById("rf_displayOptions").innerHTML = rep[0];
			for(var i=0; i < rep[1].length; i++){
				var script = document.createElement("script");
				var text = 'var x = ' + rep[1][i][0] + ';';
				text += 'x = x.toLocaleString("none",{ style: "currency", currency: "' + rep[1][i][1] + '"});';
				text += 'document.getElementById("' + rep[1][i][2] + '").innerHTML = x;';
				script.innerHTML = text;
				document.body.appendChild(script);
			}
		});
	}
}

function rf_getAvailableDepartureHours(e){
	if (document.getElementById("rf_form_heure_finp1")){
		var hourSelected = "99:99";
		if ((document.getElementById("rf_form_heure_fin")) && (document.getElementById("rf_form_heure_fin").value)){
			hourSelected = document.getElementById("rf_form_heure_fin").value;
		}
		var departureDate = e.target.value;
		departureDate = departureDate.split("-");
		var departure = new Date(departureDate[0],departureDate[1]-1,departureDate[2]);
		if (departure.getFullYear() >= 2018){
			var day = departure.getDay();
			var OTDay = rf_openingtimes[day];
			var minHourDeparture = '23:59';
			var maxHourDeparture = '00:00';
			if ((OTDay[2].length > 1) && (OTDay[2][0] != "0")){
				for(var i = 1; i < OTDay[2].length; i++){
					if (OTDay[2][i][0] < minHourDeparture){
						minHourDeparture = OTDay[2][i][0];
					}
					if (OTDay[2][i][1] > maxHourDeparture){
						maxHourDeparture = OTDay[2][i][1];
					}
				}
			}else{
				for(var i = 1; i < OTDay[0].length; i++){
					if (OTDay[0][i][0] < minHourDeparture){
						minHourDeparture = OTDay[0][i][0];
					}
					if (OTDay[0][i][1] > maxHourDeparture){
						maxHourDeparture = OTDay[0][i][1];
					}
				}
			}		
			if (minHourDeparture == '23:59'){minHourDeparture = '00:00';}
			if (maxHourDeparture == '00:00'){maxHourDeparture = '24:00';}
			minHourDeparture = minHourDeparture.substr(0,2);
			maxHourDeparture = maxHourDeparture.substr(0,2);
			var selectHourContent = '';
			for(var i=minHourDeparture; i<=maxHourDeparture;i++){
				selected = "";
				if (hourSelected.substr(0,2) == ("0" + i).slice(-2)){
					selected = "selected";
				}
				selectHourContent += '<option ' + selected + '  value="' + ("0" + i).slice(-2) + '">' + ("0" + i).slice(-2) + '</option>';
			}
			document.getElementById("rf_form_heure_finp1").innerHTML = selectHourContent;
			document.getElementById("rf_form_heure_finp1").disabled = false;
			document.getElementById("rf_form_heure_finp2").disabled = false;
			
		}else{
			document.getElementById("rf_form_heure_finp1").disabled = true;
			document.getElementById("rf_form_heure_finp2").disabled = true;
		}
	}
}

function rf_bookingConfirmation(msg){
	if (document.getElementById("rf_nb_de_place")){document.getElementById("rf_nb_de_place").value = ""};
	if (document.getElementById("rf_form_date_debut")){document.getElementById("rf_form_date_debut").value = ""};
	if (document.getElementById("rf_form_heure_debut")){document.getElementById("rf_form_heure_debut").value = ""};
	if (document.getElementById("rf_form_date_fin")){document.getElementById("rf_form_date_fin").value = ""};
	if (document.getElementById("rf_form_heure_fin")){document.getElementById("rf_form_heure_fin").value = ""};
	if (document.getElementById("rf_form_personnes")){document.getElementById("rf_form_personnes").value = ""};
	if (document.getElementById("rf_form_nom")){document.getElementById("rf_form_nom").value = ""};
	if (document.getElementById("rf_form_prenom")){document.getElementById("rf_form_prenom").value = ""};
	if (document.getElementById("rf_form_adresse")){document.getElementById("rf_form_adresse").value = ""};
	if (document.getElementById("rf_form_code_postal")){document.getElementById("rf_form_code_postal").value = ""};
	if (document.getElementById("rf_form_ville")){document.getElementById("rf_form_ville").value = ""};
	if (document.getElementById("rf_form_pays")){document.getElementById("rf_form_pays").value = ""};
	if (document.getElementById("rf_form_email")){document.getElementById("rf_form_email").value = ""};
	if (document.getElementById("rf_form_telephone")){document.getElementById("rf_form_telephone").value = ""};
	if (document.getElementById("rf_form_remarques")){document.getElementById("rf_form_remarques").value = ""};
	alert(msg);
	window.location.href = window.location.href;
}

var rf_saveInputsError = new Array();
function rf_show_inputs_error(codedArray){
	//for(var i in rf_saveInputsError){
	for(var i = 0; i < rf_saveInputsError.length; i++){
		if (document.getElementsByName(rf_saveInputsError[i])[0]){
			document.getElementsByName(rf_saveInputsError[i])[0].style.border = '';
		}else{
			document.getElementById(rf_saveInputsError[i]).style.border = '';
		}
	}
	var tab = JSON.parse(codedArray);
	//for(var i in tab){
	for(var i = 0; i < tab.length; i++){
		if (document.getElementsByName(tab[i])[0]){
			document.getElementsByName(tab[i])[0].style.border = '2px solid red';
		}else{
			document.getElementById(tab[i]).style.border = '2px solid red';
		}
	}
	rf_saveInputsError = tab;
}


function rf_saveBooking(){
	document.addEventListener("submit",rf_ajaxSaveBooking);
}

function rf_ajaxSaveBooking(e){
	if (e.target.name == "rf_saveBooking") {
		e.preventDefault();	
		if (document.getElementById("rf_calendarLoading")){
			document.getElementById("rf_calendarLoading").style.display = "flex";
		}
		var wpnonce = document.getElementById("rf_mainSaveBooking").value; 
		var data = {
			'action' : 'js_saveBooking',
			'_wpnonce': wpnonce
		};
		var formInput = document.getElementById("rf_formAllDataBooking").getElementsByTagName("input");
		//for(var i in formInput){
		for(var i = 0; i < formInput.length; i++){
			if (formInput[i].type == "hidden"){
				var name = formInput[i].name;
				if (name != '_wpnonce'){
					var value = formInput[i].value;
					data[name] = value;
				}
			}
		}
		var ajaxurl = WPJS.adminAjaxUrl;
		jQuery.post(ajaxurl, data, function(response) {
			var rep = JSON.parse(response);
			if (!rep[0]){
				alert(rep[1]);
				return;
			}
			if (document.querySelector("[name=item_name]")){
				document.querySelector("[name=item_name]").value = "#" + rep[2] + " " + document.querySelector("[name=item_name]").value;
			}
			if (document.getElementById("rf_calendarLoading")){
				document.getElementById("rf_calendarLoading").style.display = "none";
			}
			alert(rep[1]);
			window.location.href = window.location.href;
		});
	}
}

var rf_currentDate = new Date();
rf_currentDate = rf_currentDate.getFullYear() + "-" + rf_2digits(rf_currentDate.getMonth()+1) + "-" + rf_2digits(rf_currentDate.getDate());

function rf_initCalendar(){
	document.addEventListener("DOMContentLoaded", rf_calendarDefaults, false);
}

function rf_calendarOnMonth(){
	rf_calendarMakeCalendar(document.forms["when"].month.value + "-01",false);
}

function rf_calendarDefaults(){
	rf_calendarMakeCalendar(rf_currentDate,true);
}

function rf_calendarSkip(Direction){
	var currDate = new Date(rf_currentDate.substr(0,4),rf_currentDate.substr(5,2)-1,rf_currentDate.substr(8,2));
	if (Direction == "+") {
		currDate.setDate(currDate.getDate() + 7);
	}else{
		currDate.setDate(currDate.getDate() - 7);
	}
	rf_currentDate = currDate.getFullYear() + "-" + rf_2digits(currDate.getMonth()+1) + "-" + rf_2digits(currDate.getDate());
	currDate.setDate(currDate.getDate() + 6);
	var getOption = currDate.getFullYear() + "-" + rf_2digits(currDate.getMonth()+1);
	document.forms["when"].month.value = getOption;
	rf_calendarMakeCalendar(rf_currentDate,false);
}

function rf_calendarMakeCalendar(CurrentDate,init){
	if (document.getElementById("rf_idSpace")){
		var rf_idSpace = document.getElementById("rf_idSpace").value; 
	}else if (document.forms["rf_formAddEditBookingSpace"]){
		var rf_idSpace = document.forms["rf_formAddEditBookingSpace"].rf_idSpace.value;
	}else if (document.getElementsByName("nospace")[0]){
		var rf_idSpace = -1;
	}else{
		return;
	}
	var wpnonce = document.getElementById("rf_mainCalendar").value; 
	var data = {
		'action': 'js_chargeCalendrier',
		'rf_idSpace': rf_idSpace,
		'rf_init': init,
		'CurrentDate': CurrentDate,
		'_wpnonce': wpnonce
	};
	
	if (typeof(ajaxurl) == "undefined"){
		var ajaxurl = WPJS.adminAjaxUrl;
	}
	if (document.getElementById("rf_calendarLoading")){
		document.getElementById("rf_calendarLoading").style.display = "flex";
	}
	if (document.forms["rf_filterBookings"]){
		var start = document.forms["rf_filterBookings"].filterDepartureDate.value;
		var finish = document.forms["rf_filterBookings"].filterArrivalDate.value;
		var status = document.forms["rf_filterBookings"].filterBookingStatus.value;
		data['filterDepartureDate']  = start;
		data['filterArrivalDate']  = finish;
		data['filterBookingStatus']  = status;
	}
	jQuery.post(ajaxurl, data, function(response) {
		rf_calendatMakeCalendarResa(JSON.parse(response));
	});
}

function rf_calendatMakeCalendarResa(liste_resa) {
	var joursSemaine = [WPJS.rf_GetTexte58,WPJS.rf_GetTexte52,WPJS.rf_GetTexte53,WPJS.rf_GetTexte54,WPJS.rf_GetTexte55,WPJS.rf_GetTexte56,WPJS.rf_GetTexte57];
	if ((document.getElementById("rf_contentReservations")) && (liste_resa["echo"])){
		document.getElementById("rf_contentReservations").innerHTML = liste_resa["echo"];
	}
	if (liste_resa[0]){
		var tabMonth = [WPJS.rf_GetTexte59,WPJS.rf_GetTexte60,WPJS.rf_GetTexte61,WPJS.rf_GetTexte62,WPJS.rf_GetTexte63,WPJS.rf_GetTexte64,WPJS.rf_GetTexte65,WPJS.rf_GetTexte66,WPJS.rf_GetTexte67,WPJS.rf_GetTexte68,WPJS.rf_GetTexte69,WPJS.rf_GetTexte70];
		var minHour = [0,0,0,0,0,0,0];
		var maxHour = [1440,1440,1440,1440,1440,1440,1440];
		if ((liste_resa[0]["timeUnit"] == 0) || (liste_resa[0]["timeUnit"] == null)){
			liste_resa[0]["timeUnit"] = 1;
		}
		for(var day = 0; day < 7; day++){
			var hour = 0;
			while((hour < 1440) && (liste_resa[day]["planning"][hour][1] == "c")){
				hour++;
			}
			minHour[day] = hour;
			var hour = 1439;
			while((hour > 0) && (liste_resa[day]["planning"][hour][1] == "c")){
				hour--;
			}
			maxHour[day] = hour+1;
		}
		//OSX compatibility
		Array.prototype.rf_max = function() {
			return Math.max.apply(null, this);
		};
		Array.prototype.rf_min = function() {
			return Math.min.apply(null, this);
		};
		//minHour = Math.min(...minHour);
		//maxHour = Math.max(...maxHour);
		minHour = minHour.rf_min();
		maxHour = maxHour.rf_max();
		if (maxHour == 1){
			minHour = 0;
			maxHour = 1439;
		}
		coeff = 6;
		if (liste_resa[0]["timeUnit"] < 1){
			coeff = coeff * liste_resa[0]["timeUnit"];
		}
		HTML_String = '<div class="rf_weekHeader"><div class="rf_w12"></div>';
		for(i = 1; i < 8; i++){
			HTML_String += '<div class="rf_w12"><span class="rf_calendarLabelDay">' + joursSemaine[liste_resa[i % 7]["labelDay"]].substr(0,3) + '.</span><br><span class="rf_calendarDateDay">' + liste_resa[i % 7]["dateDay"] + '</span></div>';
			if (i == 1){
				rf_currentDate = liste_resa[i % 7]["date"];
			}
		}
		HTML_String += '</div><div class="rfCalendarContent"><div class="rf_w12">';
		for(i = minHour; i <= maxHour; i++){
			if ((i % 60) == 0){
				HTML_String += '<div class="rf_calendarTimes" style="height:'+(120/coeff)+'px">' + rf_2digits(i/60) + ':00</div>';
			}
		}
		HTML_String += '</div>';
		for(i = 1; i < 8; i++){
			HTML_String += '<div class="rf_w12 rf_calendarAllDay">';
			hour = minHour;
			fullZero = true;color = "rf_calendarAllAvailable";
			while(hour < maxHour){
				startBloc = hour;
				valBloc = liste_resa[i % 7]["planning"][hour][1];
				content = liste_resa[i % 7]["planning"][hour][0];
				if (content != 0){
					fullZero = false;
				}
				duree = 0;
				if (liste_resa[i % 7]){
					while((valBloc == liste_resa[i % 7]["planning"][hour][1]) && (hour < maxHour) && (duree < (liste_resa[0]["timeUnit"] * 60))){
						hour++;
						duree++;
					}
				}
				color = "rf_calendarAllAvailable";
				contentClass = "rf_bookable";
				
				if (content < liste_resa[i % 7]["nb_de_place"]){
					color = "rf_calendarAvailable";
				}
				if (valBloc == "d"){
					color = "rf_calendarDeparture";
				}
				if (valBloc == "a"){
					color = "rf_calendarArrival";
				}
				if (content <= 0){
					color = "rf_calendarUnavailable";
					contentClass = "";
					content = 0;
				}
				if (content > 1){
					content += " " + WPJS.rf_spaces;
				}else{
					content += " " + WPJS.rf_space;
				}
				if (valBloc == "c"){
					content = "";
					color = "rf_calendarClosed";
					contentClass = "";
				}
				
				HTML_String += '<div rf_data="'+liste_resa[i % 7]["date"]+';'+startBloc+'" class="'+contentClass+' rf_calendarOnePeriod ' + color + '" style="height:' + ((duree) * 2 / coeff) + 'px;line-height: ' + ((duree) * 2 / coeff) + 'px;">' + content + '</div>';
			}
			if (fullZero){
				HTML_String += '<div class="rf_calendarFullZero ' + color + '" style="height:' + ((liste_resa[0]["planning"].length-minHour-(1440-maxHour)) * 2 / coeff) + 'px;line-height: ' + ((liste_resa[0]["planning"].length-minHour-(1440-maxHour)) * 2 / coeff) + 'px;">'+content+'</div>';
			}
			HTML_String += '</div>';
		}
		HTML_String += '<div class="rf_calendarGrid">';
		for(i = minHour; i <= maxHour; i+=60){
			HTML_String += '<div class="rf_calendarGridRow" style="height:' + (120 / coeff) + 'px"></div>';
		}
		HTML_String += '</div>';
		HTML_String += '</div>';
		if (minHour == 1440){
			HTML_String += '<span class="rf_calendarBookingUnavailable">'+WPJS.rf_BookingUnavailable+'</span>';
		}
		HTML_String += '<div style="font-size:11px;text-align:right">'+String.fromCharCode(86)+String.fromCharCode(105)+String.fromCharCode(97)+' ' + WPJS.rf_PluginData + '</div><div class="rf_CalendarLegend"><div class="rf_calendarClosed">'+WPJS.rf_Closed+'</div><div class="rf_calendarAllAvailable">'+WPJS.rf_AllAvailable+'</div><div class="rf_calendarAvailable">'+WPJS.rf_Available+'</div><div class="rf_calendarUnavailable">'+WPJS.rf_Unavailable+'</div><div class="rf_calendarArrival">'+WPJS.rf_Arrival+'</div><div class="rf_calendarDeparture">'+WPJS.rf_Departure+'</div></div>';
		HTML_String += '<div id="rf_calendarLoading"><div class="rf_loadingAnimation"></div></div>';
		var currDate = new Date(rf_currentDate.substr(0,4),rf_currentDate.substr(5,2)-1,rf_currentDate.substr(8,2));
		currDate.setDate(currDate.getDate() + 6);
		var getOption = currDate.getFullYear() + "-" + rf_2digits(currDate.getMonth()+1);
		var optionMissing = true;
		var monthLength = document.forms["when"].month.options.length;
		for (var i = 0; i <= monthLength - 1; i++){
			if (document.forms["when"].month.options[i].value == getOption){
			   optionMissing = false;
			}
		}
		if (optionMissing){
			var opt = new Option((tabMonth[currDate.getMonth()]) + " " + currDate.getFullYear(), getOption);
			if (getOption > document.forms["when"].month.options[monthLength - 1].value){
				document.forms["when"].month.options[monthLength] = opt;
			}else{
				document.forms["when"].month.insertBefore(opt, document.forms["when"].month.firstChild);
			}
		}
		document.forms["when"].month.value = getOption;
		cross_el = document.getElementById("rf_calendar");
		cross_el.innerHTML = HTML_String;
		var tabBookable = document.getElementsByClassName("rf_bookable");
		for(i = 0; i < tabBookable.length; i++){
			tabBookable[i].addEventListener("click",rf_dateToForm);
		}
		document.getElementById("rf_calendarLoading").style.display = "none";
	}
}

function rf_dateToForm(e){
	data = e.target.getAttribute("rf_data").split(";");
	if ((document.getElementById("rf_form_date_debut")) && (document.getElementById("rf_form_date_debut").value == "")){
		document.getElementById("rf_form_date_debut").value = data[0];
		document.getElementById("rf_form_date_debut").focus();
		var e = {target: {value: document.getElementById("rf_form_date_debut").value}};
		rf_getAvailableArrivalHours(e);
		if (document.getElementById("rf_form_heure_debutp1")){
			hour = data[1];
			h = Math.floor(hour / 60);
			m = hour % 60;
			document.getElementById("rf_form_heure_debutp1").value = rf_2digits(h);
			document.getElementById("rf_form_heure_debutp2").value = rf_2digits(m);
		}
	}else if (document.getElementById("rf_form_date_fin")){
		document.getElementById("rf_form_date_fin").value = data[0];
		document.getElementById("rf_form_date_fin").focus();
		var e = {target: {value: document.getElementById("rf_form_date_fin").value}};
		rf_getAvailableDepartureHours(e);
		if (document.getElementById("rf_form_heure_finp1")){
			hour = data[1];
			h = Math.floor(hour / 60);
			m = hour % 60;
			document.getElementById("rf_form_heure_finp1").value = rf_2digits(h);
			document.getElementById("rf_form_heure_finp2").value = rf_2digits(m);
		}
	}
}

function rf_checkDateInOT(OT,datetime,type,exceptionalclosure){
	var numDay = datetime.getDay();
	var time = rf_2digits(datetime.getHours()) + ":" + rf_2digits(datetime.getMinutes());
	var datetimestr = datetime.getFullYear() + "-" + rf_2digits(datetime.getMonth()+1) + "-" + rf_2digits(datetime.getDate()) + " " + rf_2digits(datetime.getHours()) + ":" + rf_2digits(datetime.getMinutes());
	arrivalPresent = false;
	for(i = 0; i < OT.length; i++){
		if ((OT[i][1][0] != "0") && (OT[i][1].length > 1)){
			arrivalPresent = true;
		}
	}
	departurePresent = false;
	for(i = 0; i < OT.length; i++){
		if ((OT[i][2][0] != "0") && (OT[i][2].length > 1)){
			departurePresent = true;
		}
	}
	var tabClosure = exceptionalclosure.split("--o--");
	for(i = 0; i < tabClosure.length; i++){
		close = tabClosure[i].split(";");
		if (close[1]){
			debut = close[0];
			fin = close[1];
			if ((datetimestr >= debut) && (datetimestr < fin)){
				return false;
			}
		}
	}
	if ((OT[numDay][type].length > 1) && (OT[numDay][type][0] != "0")){
		for(i = 1; i < OT[numDay][type].length; i++){
			if (OT[numDay][type][i][1] == '00:00'){
				OT[numDay][type][i][1] = '24:00';
			}
			if ((time >= OT[numDay][type][i][0]) && (time <= OT[numDay][type][i][1])){
				return true;
			}
		}
	}else if ((type > 0) && (!arrivalPresent) && (!departurePresent)){
		type = 0;
		if (OT[numDay][type][0] == "0"){return false;}
		if (OT[numDay][type].length > 1){
			for(i = 1; i < OT[numDay][type].length; i++){
				if (OT[numDay][type][i][1] == '00:00'){
					OT[numDay][type][i][1] = '24:00';
				}
				if ((time >= OT[numDay][type][i][0]) && (time <= OT[numDay][type][i][1])){
					return true;
				}
			}
		}	
	}
	return false;
}

function dateDiffInMinutes(a, b) {
  var utc1 = Date.UTC(a.getFullYear(), a.getMonth(), a.getDate(), a.getHours(), a.getMinutes(), a.getSeconds());
  var utc2 = Date.UTC(b.getFullYear(), b.getMonth(), b.getDate(), b.getHours(), b.getMinutes(), b.getSeconds());
  return Math.floor((utc2 - utc1) / (1000 * 60));
}

function rf_getReferenceTimeInOT(OT,numDay,datetime,type,dureeMin,dateDebut,user_minutes_interval){
	user_minutes_interval = parseInt(user_minutes_interval);
	dureeMin = dureeMin * 60;
	var refTime = '00:00';
	if ((type == "2") && (user_minutes_interval > 0)){
		while(datetime <= dateDebut){
			datetime.setMinutes(datetime.getMinutes() + user_minutes_interval);
		}
		if (dureeMin > 0){
			while(dateDiffInMinutes(dateDebut,datetime) < dureeMin){
				datetime.setMinutes(datetime.getMinutes() + user_minutes_interval);
			}
		}
		var nbTentative = 10080 / user_minutes_interval;
		var compteur = 0;
		while((compteur < nbTentative) && (!rf_checkDateInOT(OT,datetime,type))){
			datetime.setMinutes(datetime.getMinutes() + user_minutes_interval);
			compteur++;
		}
		if (compteur < nbTentative){
			return rf_2digits(datetime.getHours()) + ":" + rf_2digits(datetime.getMinutes());
		}
	}
	if ((OT[numDay][type].length > 1) && (OT[numDay][type][0] != "0")){
		for(var i = 1; i < OT[numDay][type].length; i++){
			if (i == 1){
				refTime = OT[numDay][type][i][0];
			}
			if (OT[numDay][type][i][2] == "true"){
				return OT[numDay][type][i][0];
			}
			if (OT[numDay][type][i][3] == "true"){
				if (OT[numDay][type][i][1] == "00:00"){return "24:00";}
				return OT[numDay][type][i][1];
			}
		}
	}else{
		for(i = 1; i < OT[numDay][0].length; i++){
			if (i == 1){
				refTime = OT[numDay][0][i][0];
			}
			if (OT[numDay][0][i][2] == "true"){
				return OT[numDay][0][i][0];
			}
			if (OT[numDay][0][i][3] == "true"){
				if (OT[numDay][0][i][1] == "00:00"){return "24:00";}
				return OT[numDay][0][i][1];
			}
		}
	}
	if ((type == "2") && (refTime == "00:00")){refTime = "24:00";}
	return refTime;
}
function rf_checkForm(timeUnit,minBookingDuration,tps_reservation_max_heure,date_debut_reservation,date_fin_reservation,user_minutes_interval,exceptionalclosure){
	var ok = true;
	var msg = "";
	var inputsError = new Array();
	openingtimes = rf_openingtimes;
	
	var date_debut_reservation_aff = date_debut_reservation.substr(8,2) + '/' + date_debut_reservation.substr(5,2) + '/' + date_debut_reservation.substr(0,4);
	var date_fin_reservation_aff = date_fin_reservation.substr(8,2) + '/' + date_fin_reservation.substr(5,2) + '/' + date_fin_reservation.substr(0,4);
	var date_debut_reservation = new Date(date_debut_reservation.substr(0,4),date_debut_reservation.substr(5,2)-1,date_debut_reservation.substr(8,2));
	var date_fin_reservation = new Date(date_fin_reservation.substr(0,4),date_fin_reservation.substr(5,2)-1,date_fin_reservation.substr(8,2),00,00);
	date_fin_reservation.setDate(date_fin_reservation.getDate() + 1);
	var joursSemaine = [WPJS.rf_GetTexte58,WPJS.rf_GetTexte52,WPJS.rf_GetTexte53,WPJS.rf_GetTexte54,WPJS.rf_GetTexte55,WPJS.rf_GetTexte56,WPJS.rf_GetTexte57];
	if ((document.getElementsByName("form_date_debut")[0])&&(document.getElementsByName("form_date_debut")[0].value)){
		var d = document.getElementsByName("form_date_debut")[0].value;
		if (document.getElementsByName("form_heure_debut")[0]){
			var h = rf_2digits(document.getElementById("rf_form_heure_debutp1").value) + ":" + rf_2digits(document.getElementById("rf_form_heure_debutp2").value);
			document.getElementsByName("form_heure_debut")[0].value = h;
			var form_date_debut = new Date(d.substr(0,4),d.substr(5,2)-1,d.substr(8,2),h.substr(0,2),h.substr(3,2));
		}else{
			var tempDate = new Date(d.substr(0,4),d.substr(5,2)-1,d.substr(8,2),"12","00");
			var numDay = tempDate.getDay();
			var h = rf_getReferenceTimeInOT(openingtimes,numDay,tempDate,1,minBookingDuration,"",1);
			var form_date_debut = new Date(d.substr(0,4),d.substr(5,2)-1,d.substr(8,2),h.substr(0,2),h.substr(3,2));
		}
	}
	if (document.getElementsByName("form_date_fin")[0]){
		if (document.getElementById("rf_booking_duration")){
			var d = document.getElementsByName("form_date_debut")[0].value;
			var h = rf_2digits(document.getElementById("rf_form_heure_debutp1").value) + ":" + rf_2digits(document.getElementById("rf_form_heure_debutp2").value);
			var form_date_fin = new Date(d.substr(0,4),d.substr(5,2)-1,d.substr(8,2),h.substr(0,2),h.substr(3,2));
			form_date_fin.setTime(form_date_fin.getTime() + (document.getElementById("rf_booking_duration").value * 60 * 1000));
			document.getElementsByName("form_date_fin")[0].value = form_date_fin.getFullYear() + "-" + rf_2digits(form_date_fin.getMonth()+1) + "-" + rf_2digits(form_date_fin.getDate());
			document.getElementsByName("form_heure_fin")[0].value = rf_2digits(form_date_fin.getHours()) + ":" + rf_2digits(form_date_fin.getMinutes());
			document.getElementById("rf_form_heure_finp1").value = rf_2digits(form_date_fin.getHours());
			document.getElementById("rf_form_heure_finp2").value = rf_2digits(form_date_fin.getMinutes());
		}else if (document.getElementsByName("form_date_fin")[0].value){
			var d = document.getElementsByName("form_date_fin")[0].value;
			if (document.getElementsByName("form_heure_fin")[0]){
				var h = rf_2digits(document.getElementById("rf_form_heure_finp1").value) + ":" + rf_2digits(document.getElementById("rf_form_heure_finp2").value);
				document.getElementsByName("form_heure_fin")[0].value = h;
				var form_date_fin = new Date(d.substr(0,4),d.substr(5,2)-1,d.substr(8,2),h.substr(0,2),h.substr(3,2));
			}else{
				var tempDate = new Date(d.substr(0,4),d.substr(5,2)-1,d.substr(8,2),"00","00");
				var numDay = tempDate.getDay();
				var h = rf_getReferenceTimeInOT(openingtimes,numDay,tempDate,2,minBookingDuration,form_date_debut,user_minutes_interval);
				var form_date_fin = new Date(d.substr(0,4),d.substr(5,2)-1,d.substr(8,2),h.substr(0,2),h.substr(3,2));
			}
		}
	}
	if (document.getElementById("rf_form_heure_debut")){
		document.getElementById("rf_form_heure_debut").value = rf_2digits(document.getElementById("rf_form_heure_debutp1").value) + ":" + rf_2digits(document.getElementById("rf_form_heure_debutp2").value);
		var form_heure_debut = document.getElementById("rf_form_heure_debut").value;
		if (form_heure_debut != ""){
			//var dd = new Date(document.getElementsByName("form_date_debut")[0].value + " " + form_heure_debut);
			var dd = document.getElementsByName("form_date_debut")[0].value.split("-");
			var hh = form_heure_debut.split(":");
			dd = new Date(dd[0],dd[1]-1,dd[2],hh[0],hh[1]);
			if (!rf_checkDateInOT(openingtimes,dd,1,exceptionalclosure)){
				msg += WPJS.rf_GetTexte37 + "\n";
				ok = false;
				inputsError.push("rf_form_heure_debutp1"); 
				inputsError.push("rf_form_heure_debutp2");
			}
		}
	}
	if (document.getElementById("rf_form_heure_fin")){
		document.getElementById("rf_form_heure_fin").value = rf_2digits(document.getElementById("rf_form_heure_finp1").value) + ":" + rf_2digits(document.getElementById("rf_form_heure_finp2").value);
		var form_heure_fin = document.getElementById("rf_form_heure_fin").value;
		if (form_heure_fin != ""){
			//var dd = new Date(document.getElementsByName("form_date_fin")[0].value + " " + form_heure_fin);
			//var dd2 = new Date(document.getElementsByName("form_date_fin")[0].value + " " + form_heure_fin);
			var dd = document.getElementsByName("form_date_fin")[0].value.split("-");
			var dd2 = document.getElementsByName("form_date_fin")[0].value.split("-");
			var hh = form_heure_fin.split(":");
			dd = new Date(dd[0],dd[1]-1,dd[2],hh[0],hh[1]);
			dd2 = new Date(dd2[0],dd2[1]-1,dd2[2],hh[0],hh[1]);
			dd.setSeconds(dd.getSeconds() - 1);
			if ((!rf_checkDateInOT(openingtimes,dd,2,exceptionalclosure)) && (!rf_checkDateInOT(openingtimes,dd2,2,exceptionalclosure))){
				msg += WPJS.rf_GetTexte39 + "\n";
				ok = false;
				inputsError.push("rf_form_heure_finp1"); 
				inputsError.push("rf_form_heure_finp2");
			}
		}
	}
	if (form_date_debut){						
		if (form_date_debut < date_debut_reservation){
			msg += WPJS.rf_GetTexte41 + " " + date_debut_reservation_aff + "\n";
			ok = false;
			inputsError.push("form_date_debut"); 
		}
		if (form_date_debut > date_fin_reservation){
			msg += WPJS.rf_GetTexte42 + " " + date_fin_reservation_aff + "\n";
			ok = false;
			inputsError.push("form_date_debut"); 
		}
	}
	if (form_date_fin){
		if (form_date_fin < date_debut_reservation){
			msg += WPJS.rf_GetTexte41 + " " + date_debut_reservation_aff + "\n";
			ok = false;
			inputsError.push("form_date_fin"); 
		}
		if (form_date_fin > date_fin_reservation){
			msg += WPJS.rf_GetTexte42 + " " + date_fin_reservation_aff + "\n";
			ok = false;
			inputsError.push("form_date_fin"); 
		}
	}
	if (form_date_debut < (new Date())){
		msg += WPJS.rf_GetTexte43 + " \n";
		ok = false;
		inputsError.push("form_date_debut"); 
	}
	if (form_date_debut && form_date_fin){
		var reservation_duree_heure = ((form_date_fin - form_date_debut) / 1000) / 3600;
		if ((minBookingDuration > 0)&&(reservation_duree_heure < minBookingDuration)){
			ok = false;
			msg += WPJS.rf_GetTexte44 + " " + minBookingDuration + WPJS.rf_GetTexte51 + "\n";
			inputsError.push("form_date_debut"); 
			inputsError.push("form_date_fin"); 
		}
		if ((tps_reservation_max_heure > 0)&&(reservation_duree_heure > tps_reservation_max_heure)){
			ok = false;
			msg += WPJS.rf_GetTexte45 + " " + tps_reservation_max_heure + WPJS.rf_GetTexte51 + "\n";
			inputsError.push("form_date_debut"); 
			inputsError.push("form_date_fin");
		}
		if (reservation_duree_heure < 0){
			ok = false;
			msg += WPJS.rf_TArrivalDateAfterDepartureDate + "\n";
			inputsError.push("form_date_debut"); 
			inputsError.push("form_date_fin");
		}
		
		var dateParcours = new Date(form_date_debut.getTime());
		var ok2 = true;
		var spaceInterval = parseInt(user_minutes_interval);
		if (spaceInterval < 1){spaceInterval = 1;}
		while ((dateParcours < form_date_fin) && ok2){
			if (!rf_checkDateInOT(openingtimes,dateParcours,0,"")){
				ok2 = false;
				ok = false;
				msg += WPJS.rf_GetTexte46 + " " + joursSemaine[dateParcours.getDay()] + " " + rf_2digits(dateParcours.getHours())+":"+rf_2digits(dateParcours.getMinutes()) + "\n";
				inputsError.push("form_date_debut"); 
				inputsError.push("form_date_fin");
			}
			dateParcours.setMinutes(dateParcours.getMinutes()+spaceInterval);
		}
	}
	if ((form_date_debut) && (!form_date_fin)){
		if (!rf_checkDateInOT(openingtimes,form_date_debut,0,"")){
			ok = false;
			msg += WPJS.rf_GetTexte46 + " " + joursSemaine[form_date_debut.getDay()] + " " + rf_2digits(form_date_debut.getHours())+":"+rf_2digits(form_date_debut.getMinutes()) + "\n";
			inputsError.push("form_date_debut"); 
			inputsError.push("form_date_fin");
		}
	}
	if (form_date_debut){
		if (!rf_checkDateInOT(openingtimes,form_date_debut,1,"")){
			ok = false;
			msg += WPJS.rf_GetTexte75 + " " + joursSemaine[form_date_debut.getDay()] + " " + rf_2digits(form_date_debut.getHours())+":"+rf_2digits(form_date_debut.getMinutes()) + "\n";
			inputsError.push("form_date_debut"); 
		}
	}
	if (form_date_fin){
		var form_date_fin_tmp = new Date(form_date_fin.getTime());
		form_date_fin_tmp.setSeconds(form_date_fin.getSeconds() - 1);
		if ((!rf_checkDateInOT(openingtimes,form_date_fin_tmp,2,"")) && (!rf_checkDateInOT(openingtimes,form_date_fin,2,""))){
			ok = false;
			msg += WPJS.rf_GetTexte76 + " " + joursSemaine[form_date_fin.getDay()] + " " + rf_2digits(form_date_fin.getHours())+":"+rf_2digits(form_date_fin.getMinutes()) + "\n";
			inputsError.push("form_date_fin"); 
		}
	}
	if (!ok){
		rf_show_inputs_error(JSON.stringify(inputsError));
		alert(msg);
	}
	return ok;
}

function rf_2digits(val) {
  return ('0' + val).slice(-2);
}