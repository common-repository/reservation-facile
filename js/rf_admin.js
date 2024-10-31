
document.addEventListener("DOMContentLoaded", rf_initAccordions, false);
document.addEventListener("DOMContentLoaded", rf_initTextarea, false);
document.addEventListener("DOMContentLoaded", rf_initCSS, false);
document.addEventListener("DOMContentLoaded", rf_initDisplayPrice, false);

function rf_initDisplayPrice(){
	var tab = document.getElementsByClassName("rf_displayLocalPrice");
	if (document.getElementsByName("devise")[0]){
		var devise = document.getElementsByName("devise")[0].value;
		for(var i = 0; i < tab.length; i++){
			if (!isNaN(tab[i].innerHTML)){
				var script = document.createElement("script");
				var text = 'var x = ' + tab[i].innerHTML + ';';
				text += 'x = x.toLocaleString("none",{ style: "currency", currency: "' + devise + '"});';
				text += 'document.getElementsByClassName("rf_displayLocalPrice")['+i+'].innerHTML = x;';
				script.innerHTML = text;
				document.body.appendChild(script);
			}
		}
	}
}

function rf_initCSS(){
	if (document.getElementById("rf_content")){
		document.getElementById("wpcontent").style.backgroundColor = "#FFFFFF";
		document.body.style.backgroundColor = "#FFFFFF";
		if (document.getElementById("wpfooter")){
			document.getElementById("wpfooter").style.display = "none";
		}
	}
}

function rf_initAccordions(){
	var acc = document.getElementsByClassName("rf_accordion");
	var i;

	for (i = 0; i < acc.length; i++){
		if ((!acc[i].classList.contains("rf_hideAccordion")) && (!acc[i].classList.contains("rf_masked"))){
			acc[i].classList.toggle("rf_isactive");
			var panel = acc[i].nextElementSibling;
			panel.style.maxHeight = "initial";
		}
		
		acc[i].onclick = function(){
			this.classList.toggle("rf_isactive");
			var panel = this.nextElementSibling;
			if (panel.style.maxHeight){
				panel.style.maxHeight = null;
			}else{
				panel.style.maxHeight = panel.scrollHeight + "px";
			} 
			return false;
		}
	}
}

function rf_initTextarea() {
	tabTextarea = document.getElementsByTagName('textarea');
	for(var i=0; i<tabTextarea.length; i++){
		tabTextarea[i].onkeydown = function(e) {
			if (e.keyCode === 9) {
				var val = this.value,
				start = this.selectionStart,
				end = this.selectionEnd;
				this.value = val.substring(0, start) + '\t' + val.substring(end);
				this.selectionStart = this.selectionEnd = start + 1;
				return false;

			}
		};
	}
}

function rf_initOpeningTime(){
	document.getElementById("rf_OTDays").addEventListener("change",rf_OTLoadDay);
	document.getElementById("rf_OTType").addEventListener("change",rf_OTLoadDayType);
	document.getElementById("rf_OTOpened").addEventListener("change",rf_OTToogleOpened);
	document.getElementById("rf_OTBtnAddTime").addEventListener("click",rf_OTAddTime);
	document.getElementById("rf_OTBtnDuplicate").addEventListener("click",rf_OTDuplicateTime);
	rf_OTLoadAllDays();
	document.getElementById("rf_OTDays").selectedIndex = 0;
	rf_OTLoadDay();
}

function rf_OTLoadAllDays(){
	var tabDays = JSON.parse(document.getElementById("rf_OT").innerHTML);
	for(var i = 0; i < tabDays.length; i++){
		if (tabDays[i][0][0] == "0"){
			document.getElementById("rf_OTDay" + i).innerHTML = "&#10060; ";
		}else{
			document.getElementById("rf_OTDay" + i).innerHTML = "&#9989; ";
		}
		document.getElementById("rf_OTDay" + i).innerHTML += document.getElementById("rf_OTDay" + i).getAttribute("rf_day");
	}
}

function rf_OTLoadDay(){
	var tabDays = JSON.parse(document.getElementById("rf_OT").innerHTML);
	var numDay = (document.getElementById("rf_OTDays").selectedIndex + 1) % 7;
	var day = tabDays[numDay];
	document.getElementById("rf_OTType").selectedIndex = 0;
	for(var i = 0; i < day.length; i++){
		if (day[i][0] == "0"){
			document.getElementById("rf_OTType" + i).innerHTML = "#10060; ";
		}else{
			document.getElementById("rf_OTType" + i).innerHTML = "#9989; ";
		}
		document.getElementById("rf_OTType" + i).innerHTML = "&" + document.getElementById("rf_OTType" + i).innerHTML + document.getElementById("rf_OTType" + i).getAttribute("rf_type");
	}
	document.getElementById("rf_OTType").selectedIndex = 0;
	rf_OTLoadDayType();
}

function rf_OTLoadDayType(){
	var tabDays = JSON.parse(document.getElementById("rf_OT").innerHTML);
	var numDay = (document.getElementById("rf_OTDays").selectedIndex + 1) % 7;
	var type = tabDays[numDay][document.getElementById("rf_OTType").selectedIndex];
	
	document.getElementById("rf_OTPeriods").innerHTML = "";
	var tabLabel = [WPJS.rf_OpeningTime,WPJS.rf_AllowedArrivalTime,WPJS.rf_RequestedDepartureTime];
	document.getElementById("rf_OTOpenedLabel").innerHTML = tabLabel[document.getElementById("rf_OTType").selectedIndex];
	if (type[0] == "0"){
		document.getElementById("rf_OTOpened").checked = false;
	}else{
		document.getElementById("rf_OTOpened").checked = true;
	}
	//for(var i in type){
	for(var i = 0; i < type.length; i++){
		if (i > 0){
			document.getElementById("rf_OTPeriods").innerHTML += '<div id="rf_OTTimeBlock'+i+'">De <input type="time" id="rf_OTStartTime'+i+'" value="'+type[i][0]+'" class="rf_OTStartTime"> à <input type="time" id="rf_OTEndTime'+i+'" value="'+type[i][1]+'" class="rf_OTEndTime"><select style="top:-2px" id="rf_OTChangeReference'+i+'" class="rf_OTChangeReference"><option value="0"></option><option value="1">Réf. debut</option><option value="2">Réf. fin</option><option value="3">Réf. debut et fin</option></select><a href="#" style="text-decoration:none;font-size: 16px;color:red" id="rf_OTRemoveTime'+i+'" class="rf_OTRemoveTime">&#10008;</a></div>';
		}
	}
	var tabInputTime = document.getElementsByClassName("rf_OTStartTime");
	for(var i = 0; i < tabInputTime.length; i++){
		tabInputTime[i].addEventListener("change",rf_OTChangeStartTime);
	}
	var tabInputTime = document.getElementsByClassName("rf_OTEndTime");
	for(var i = 0; i < tabInputTime.length; i++){
		tabInputTime[i].addEventListener("change",rf_OTChangeEndTime);
	}
	var tabInputTime = document.getElementsByClassName("rf_OTChangeReference");
	for(var i = 0; i < tabInputTime.length; i++){
		tabInputTime[i].addEventListener("change",rf_OTChangeReference);
	}
	var tabInputTime = document.getElementsByClassName("rf_OTRemoveTime");
	for(var i = 0; i < tabInputTime.length; i++){
		tabInputTime[i].addEventListener("click",rf_OTRemoveTime);
	}
	
	var tabInputTime = document.getElementsByClassName("rf_OTStartTime");
	for(var numTime = 1; numTime <= tabInputTime.length; numTime++){
		rf_checkOT(numTime)
	}
	
}

function rf_checkOT(numTime){
	if ((document.getElementById("rf_OTStartTime"+numTime).value > document.getElementById("rf_OTEndTime"+numTime).value) && (document.getElementById("rf_OTEndTime"+numTime).value != "00:00")){
			document.getElementById("rf_OTStartTime"+numTime).style.backgroundColor = "red";
			document.getElementById("rf_OTEndTime"+numTime).style.backgroundColor = "red";
			document.getElementById("rf_OTStartTime"+numTime).style.color = "white";
			document.getElementById("rf_OTEndTime"+numTime).style.color = "white";
		}else{
			document.getElementById("rf_OTStartTime"+numTime).style.backgroundColor = "white";
			document.getElementById("rf_OTEndTime"+numTime).style.backgroundColor = "white";
			document.getElementById("rf_OTStartTime"+numTime).style.color = "#32373c";
			document.getElementById("rf_OTEndTime"+numTime).style.color = "#32373c";
		}
}

function rf_OTToogleOpened(e){
	var currentType = document.getElementById("rf_OTType").selectedIndex;
	var tabDays = JSON.parse(document.getElementById("rf_OT").innerHTML);
	var numDay = (document.getElementById("rf_OTDays").selectedIndex + 1) % 7;
	
	tabDays[numDay][currentType][0] = 1 - tabDays[numDay][currentType][0];
	document.getElementById("rf_OT").innerHTML = JSON.stringify(tabDays);
	
	if (tabDays[numDay][currentType][0] == "0"){
		if (currentType == 0){
			document.getElementById("rf_OTDay" + numDay).innerHTML = "&#10060; ";
			document.getElementById("rf_OTDay" + numDay).innerHTML += document.getElementById("rf_OTDay" + numDay).getAttribute("rf_day");
		}
		document.getElementById("rf_OTType" + currentType).innerHTML = "&#10060; ";
		document.getElementById("rf_OTType" + currentType).innerHTML += document.getElementById("rf_OTType" + currentType).getAttribute("rf_type");
	}else{
		if (currentType == 0){
			document.getElementById("rf_OTDay" + numDay).innerHTML = "&#9989; ";
			document.getElementById("rf_OTDay" + numDay).innerHTML += document.getElementById("rf_OTDay" + numDay).getAttribute("rf_day");
		}
		document.getElementById("rf_OTType" + currentType).innerHTML = "&#9989; ";
		document.getElementById("rf_OTType" + currentType).innerHTML += document.getElementById("rf_OTType" + currentType).getAttribute("rf_type");
	}
	

	
	
}

function rf_OTChangeStartTime(e){
	var currentType = document.getElementById("rf_OTType").selectedIndex;
	var tabDays = JSON.parse(document.getElementById("rf_OT").innerHTML);
	var numDay = (document.getElementById("rf_OTDays").selectedIndex + 1) % 7;
	var numTime = e.target.id.replace("rf_OTStartTime","");
	
	tabDays[numDay][currentType][numTime][0] = e.target.value;
	document.getElementById("rf_OT").innerHTML = JSON.stringify(tabDays);
	
	rf_checkOT(numTime);
}

function rf_OTChangeEndTime(e){
	var currentType = document.getElementById("rf_OTType").selectedIndex;
	var tabDays = JSON.parse(document.getElementById("rf_OT").innerHTML);
	var numDay = (document.getElementById("rf_OTDays").selectedIndex + 1) % 7;
	var numTime = e.target.id.replace("rf_OTEndTime","");
	
	tabDays[numDay][currentType][numTime][1] = e.target.value;
	document.getElementById("rf_OT").innerHTML = JSON.stringify(tabDays);
	rf_checkOT(numTime)
}

function rf_OTChangeReference(e){
	var currentType = document.getElementById("rf_OTType").selectedIndex;
	var tabDays = JSON.parse(document.getElementById("rf_OT").innerHTML);
	var numDay = (document.getElementById("rf_OTDays").selectedIndex + 1) % 7;
	var numReference = e.target.id.replace("rf_OTChangeReference","");
	
	var refStart = false;
	var refEnd = false;
	if ((e.target.value == "1")||(e.target.value == "3")){
		refStart = true;
	}
	if ((e.target.value == "2")||(e.target.value == "3")){
		refEnd = true;
	}
	tabDays[numDay][currentType][numReference][2] = refStart;
	tabDays[numDay][currentType][numReference][3] = refEnd;
	document.getElementById("rf_OT").innerHTML = JSON.stringify(tabDays);
}

function rf_OTRemoveTime(e){
	e.preventDefault();
	var currentType = document.getElementById("rf_OTType").selectedIndex;
	var tabDays = JSON.parse(document.getElementById("rf_OT").innerHTML);
	var numDay = (document.getElementById("rf_OTDays").selectedIndex + 1) % 7;
	var numTime = e.target.id.replace("rf_OTRemoveTime","");
	
	tabDays[numDay][currentType].splice(numTime,1);
	document.getElementById("rf_OT").innerHTML = JSON.stringify(tabDays);
	rf_OTLoadDayType();
}

function rf_OTAddTime(e){
	e.preventDefault();
	var timeCount = document.getElementsByClassName("rf_OTStartTime").length + 1;
	var currentType = document.getElementById("rf_OTType").selectedIndex;
	var tabDays = JSON.parse(document.getElementById("rf_OT").innerHTML);
	var numDay = (document.getElementById("rf_OTDays").selectedIndex + 1) % 7;
	
	tabDays[numDay][currentType].push(["08:00","12:00",false,false]);
	document.getElementById("rf_OT").innerHTML = JSON.stringify(tabDays);
	rf_OTLoadDayType();
}

function rf_OTDuplicateTime(e){
	var label = document.getElementById("rf_OTOpenedLabel").innerHTML;
	var currentType = document.getElementById("rf_OTType").selectedIndex;
	var tabDays = JSON.parse(document.getElementById("rf_OT").innerHTML);
	var numDay = (document.getElementById("rf_OTDays").selectedIndex + 1) % 7;
	
	if (confirm("Voulez-vous vraiment dupliquer les " + label + " sur toute la semaine?")){
		var duplicate = tabDays[numDay][currentType];
		for(var i=0; i < 7; i++){
			tabDays[i][currentType] = duplicate;
		}
	}
	document.getElementById("rf_OT").innerHTML = JSON.stringify(tabDays);
	rf_OTLoadAllDays();
}

function rf_txt2color(e){
	var id = e.target.id.substr(12);
	document.getElementById("rf_color" + id).value = e.target.value;
}

function rf_color2txt(e){
	var id = e.target.id.substr(9);
	document.getElementById("rf_colortxt" + id).value = e.target.value;
}

function rf_getEmplacementVal(id_emplacement,initOnlyNbDePlace){
	var initOnlyNbDePlace = (typeof initOnlyNbDePlace !== 'undefined') ? initOnlyNbDePlace : false;
	if ((id_emplacement != "")&&(document.getElementById("rf_mainGetEmplacementVal"))){	
		var wpnonce = document.getElementById("rf_mainGetEmplacementVal").value;
		var data = {
			'action': 'js_getEmplacementVal',
			'rf_idSpace': id_emplacement,
			'_wpnonce': wpnonce
		};
		
		jQuery.post(ajaxurl, data, function(response) {
			var reponse = JSON.parse(response);
			if (!initOnlyNbDePlace){
				document.getElementsByName("devise")[0].value = reponse.devise;
				document.getElementsByName("prix_de_la_place")[0].value = reponse.prix_de_la_place;
				document.getElementsByName("devise")[0].value = reponse.devise;
				document.getElementsByName("acompte_prix")[0].value = reponse.acompte_prix;
				document.getElementsByName("acompte_pourcentage")[0].value = reponse.acompte_pourcentage;	
			}
			var option = "<option></option>";
			for(var i=0;i<reponse.listoption.length;i++){
				option += '<option mpid="'+reponse.listoption[i][0]+'" value="'+reponse.listoption[i][1]+'">'+reponse.listoption[i][1]+'</option>';
			}
			document.getElementsByName("labeloption-1")[0].innerHTML = option;
			var option = "<option></option>";
			for(var i=0;i<reponse.listtaxe.length;i++){
				option += '<option mpid="'+reponse.listtaxe[i][0]+'" value="'+reponse.listtaxe[i][1]+'">'+reponse.listtaxe[i][1]+'</option>';
			}
			document.getElementsByName("labeltaxe-1")[0].innerHTML = option;
			var option = "<option></option>";
			for(var i=0;i<reponse.listcoupon.length;i++){
				option += '<option mpid="'+reponse.listcoupon[i][0]+'" value="'+reponse.listcoupon[i][1]+'">'+reponse.listcoupon[i][1]+'</option>';
			}
			document.getElementsByName("labelcoupon-1")[0].innerHTML = option;
			var option = "<option></option>";
			for(var i=0;i<reponse.listreduction.length;i++){
				option += '<option mpid="'+reponse.listreduction[i][0]+'" value="'+reponse.listreduction[i][1]+'">'+reponse.listreduction[i][1]+'</option>';
			}
			document.getElementsByName("labelreduction-1")[0].innerHTML = option;
			document.getElementById("rf_nbDePlace").value = reponse.nb_de_place;
			document.getElementsByName("timeUnit")[0].value = reponse.timeUnit;
			document.getElementsByName("periodesprices")[0].value = reponse.periodesprices;
			document.getElementsByName("dayprice")[0].value = reponse.dayprice;
			rf_calendarDefaults();
		});
	}
}

function rf_showOnglet(onglet){
	var onglets = document.getElementsByClassName("rf_plugin_onglets");
	for(var i=0; i < onglets.length; i++){
		var ulChildren = onglets[i].children;                  
		for(var j = 0; j < ulChildren.length; j++){
			if(ulChildren[j].nodeName.toLowerCase() === 'li'){
				
				document.getElementById(ulChildren[j].id).className = "";
				document.getElementById(ulChildren[j].id.slice(0,-3)).style.display = "none";
			}
		}
	}
	//document.getElementById("rf_plugin_onglets_formulaire").classList.remove("rf_flex");
	document.getElementById(onglet+"_li").className = "rf_isactive";
	//if (onglet == "rf_plugin_onglets_formulaire"){
		//document.getElementById(onglet).style.display = "flex";
	//	document.getElementById(onglet).classList.add("rf_flex");
	//}else{
		document.getElementById(onglet).style.display = "block";
	//}
	
}

function rf_delete_mp(id){
	if (confirm(WPJS.rf_TConfirmDeleteItem)){
		document.getElementById("rf_coupon_"+id).style.display = "none";
		document.getElementById("rf_coupon_panel_"+id).style.display = "none";		
		var wpnonce = document.getElementById("rf_deleteCoupon").value;
		var data = {
			'action': 'js_deleteCoupon',
			'id_coupon': id,
			'_wpnonce': wpnonce
		};
		jQuery.post(ajaxurl, data, function(response) {});
	}
}

function rf_closeAdminNotice(elt){
	elt.parentNode.style.display = "none";
}

function rf_reload_page(){
	window.location.reload();
}

function rf_eventFire(el, etype){
	if (el.fireEvent) {
		el.fireEvent("on" + etype);
	}else{
		var evObj = document.createEvent("Events");
		evObj.initEvent(etype, true, false);
		el.dispatchEvent(evObj);
	}
}

function rf_currentdate(day){
	var today = new Date();
	today.setDate(today.getDate() + day);
	var dd = today.getDate();
	var mm = today.getMonth()+1;
	var yyyy = today.getFullYear();
	if(dd<10) {
		dd = "0"+dd
	} 
	if(mm<10) {
		mm = "0"+mm
	}
	return yyyy + "-" + mm + "-" + dd;
}

function rf_ucfirst(str) {
	if (str.length > 0) {
		return str[0].toUpperCase() + str.substring(1);
	}else{
		return str;
	}
}

function rf_ajouterCoupon(type){
	if (document.getElementById("rf_mainAjouterCoupon")){
		var label = document.getElementsByName("label_"+type)[0].value;
		var date_debut = document.getElementsByName("date_debut_"+type)[0].value;
		var date_fin = document.getElementsByName("date_fin_"+type)[0].value;
		var quantite = document.getElementsByName("quantite_"+type)[0].value;
		if (document.getElementsByName("details_texte_"+type)[0]){
			var details_texte = document.getElementsByName("details_texte_"+type)[0].value;
		}else{
			var details_texte = '';
		}
		var montant = document.getElementsByName("montant_"+type)[0].value;
		var pourcentage = document.getElementsByName("pourcentage_"+type)[0].value;
		var periode_heure = document.getElementsByName("periode_heure_"+type)[0].value;
		var code = document.getElementsByName("code_"+type)[0].value;
		var description = document.getElementsByName("description_"+type)[0].value;
		var rf_idSpace = document.getElementsByName("rf_idSpace")[1].value;
		if (label == ""){
			alert(WPJS.rf_TPleaseFillLabel);
			document.getElementsByName("label_"+type)[0].focus();
			return;
		}
		if ((quantite == "") && (type == "option") && (code == "userchoice")){
			alert(WPJS.rf_TPleaseFillMaxQty);
			document.getElementsByName("quantite_"+type)[0].focus();
			return;
		}
		if (date_fin == ""){
			alert(WPJS.rf_TPleaseFillEndDate);
			document.getElementsByName("date_fin_"+type)[0].focus();
			return;
		}
		var wpnonce = document.getElementById("rf_mainAjouterCoupon").value;
		var data = {
			'action': 'js_ajouterCoupon',
			'label': label,
			'date_debut': date_debut,
			'date_fin': date_fin,
			'quantite': quantite,
			'details_texte': details_texte,
			'montant': montant,
			'pourcentage': pourcentage,
			'periode_heure': periode_heure,
			'code': code,
			'description': description,
			'rf_idSpace': rf_idSpace,
			'type': type,
			'_wpnonce': wpnonce
		};
		jQuery.post(ajaxurl, data, function(response) {
			var id_coupon = response;
			var div = document.createElement("div");
			div.id = "rf_" + type + "Panel" + id_coupon;
			div.className = "rf_panel";
			var innerHTML = '<br><table>';
			if (date_debut != ""){ innerHTML += "<tr><td><b>"+WPJS.rf_TAvailable+" </b></td><td>"+date_debut+"</td></tr>";}
			if (date_fin != ""){ innerHTML += "<tr><td><b>"+WPJS.rf_TUntil+" </b></td><td>"+date_fin+"</td></tr>";}
			if ((quantite > 0)&&(type != "option")){ innerHTML += "<tr><td><b>"+WPJS.rf_TRemainingQuantity+" </b></td><td>"+quantite+"/"+quantite+"</td></tr>";}
			if ((code != "")&&(type == "option")){ 
				innerHTML += "<tr><td><b>"+WPJS.rf_TAutomaticQuantity+" </b></td><td><select disabled>";
				innerHTML += '<option value="userchoice" '+((code == 'userchoice') ? 'selected' : '' )+'>'+WPJS.rf_tUserChoice+'</option>';
				innerHTML += '<option value="oneperhour" '+((code == 'oneperhour') ? 'selected' : '' )+'>'+WPJS.rf_TOnePerHour+'</option>';
				innerHTML += '<option value="oneperday" '+((code == 'oneperday') ? 'selected' : '' )+'>'+WPJS.rf_TOnePerDay+'</option>';
				innerHTML += '<option value="onepernight" '+((code == 'onepernight') ? 'selected' : '' )+'>'+WPJS.rf_TOnePerNight+'</option>';
				innerHTML += '<option value="oneperweek" '+((code == 'oneperweek') ? 'selected' : '' )+'>'+WPJS.rf_TOnePerWeek+'</option>';
				innerHTML += '<option value="onepermonth" '+((code == 'onepermonth') ? 'selected' : '' )+'>'+WPJS.rf_TOnePerMonth+'</option>';
				innerHTML += "</select></td></tr>";
			}
			if (montant > 0){ innerHTML += "<tr><td><b>"+WPJS.rf_TAmount+" </b></td><td class='rf_displayLocalPrice'>"+montant+"</td></tr>";}
			if (pourcentage > 0){ innerHTML += "<tr><td><b>"+WPJS.rf_TPercentage+" </b></td><td>"+pourcentage+"%</td></tr>";}
			if (periode_heure > 0){ innerHTML += "<tr><td><b>"+WPJS.rf_TPeriodicity+" </b></td><td>"+periode_heure+"h</td></tr>";}
			if ((code != "")&&(type != "option")){ innerHTML += "<tr><td><b>"+WPJS.rf_TCode+" </b></td><td>"+code+"</td></tr>";}
			if (details_texte != ""){
				innerHTML += "<tr><td colspan='2'><b>"+WPJS.rf_tOptions+': </b><br>';
				var displayDetails = details_texte.split("\n");
				for(var i=0;i<displayDetails.length;i++){
					innerHTML += '#'+(i+1)+': ';
					innerHTML += displayDetails[i];
					innerHTML += '<br>';
				}
				innerHTML += '</td></tr>';
			}
			if (description != ""){ innerHTML += "<tr><td colspan='2'><br><b>"+WPJS.rf_TDescriptionDetails+": </b><br>"+description+"</td></tr>";}
			div.innerHTML = innerHTML + "</table><br>";
			document.getElementById("rf_btnAjout"+type).prepend(div);
			var bouton = document.createElement("button");
			bouton.id = "rf_" + type + id_coupon;
			bouton.className = "rf_accordion";
			bouton.style.display = "none";
			var tmp = "'idCoupon'";
			var tmp2 = "'"+type+"'";
			bouton.innerHTML = 
				'<a href="#" onclick="rf_associeCoupon(this.getAttribute('+tmp+'),'+tmp2+')" idcoupon="'+id_coupon+'"><span class="rf_no_rotate_arrow_price"><img class="emoji" src="' + WPJS.pluginsUrl + '/img/arrow.svg' + '"></span></a><b>' + rf_ucfirst(label) + '</b>';
			document.getElementById("rf_btnAjout"+type).prepend(bouton);
			
			bouton.onclick = function() {
				this.classList.toggle("rf_isactive");
				var panel = this.nextElementSibling;
				if (panel.style.maxHeight){
					panel.style.maxHeight = null;
				}else{
					panel.style.maxHeight = panel.scrollHeight + "px";
				} 
			}
			var bouton = document.createElement("button");
			bouton.id = "rf_" + type + "Space" + id_coupon;
			bouton.className = "rf_accordion";
			var tmp = "'idCoupon'";
			var tmp2 = "'"+type+"'";
			bouton.innerHTML = 
				'<a href="#" onclick="rf_desassocieCoupon(this.getAttribute('+tmp+'),'+tmp2+')" idcoupon="'+id_coupon+'"><span class="rf_rotate_arrow_price"><img class="emoji" src="' + WPJS.pluginsUrl + '/img/arrow.svg' + '"></span></a><b>' + rf_ucfirst(label) + '</b>';
			document.getElementById("rf_" + type + "s_associes").firstChild.appendChild(bouton);
			bouton.onclick = function() {
				this.classList.toggle("rf_isactive");
				var panel = this.nextElementSibling;
				if (panel.style.maxHeight){
					panel.style.maxHeight = null;
				}else{
					panel.style.maxHeight = panel.scrollHeight + "px";
				} 
			}
			var div = document.createElement("div");
			div.id = "rf_" + type + "PanelSpace" + id_coupon;
			div.className = "rf_panel";
			var innerHTML = '<br><table>';
			if (date_debut != ""){ innerHTML += "<tr><td><b>"+WPJS.rf_TAvailable+" </b></td><td>"+date_debut+"</td></tr>";}
			if (date_fin != ""){ innerHTML += "<tr><td><b>"+WPJS.rf_TUntil+" </b></td><td>"+date_fin+"</td></tr>";}
			if ((quantite > 0)&&(type != "option")){ innerHTML += "<tr><td><b>"+WPJS.rf_TRemainingQuantity+" </b></td><td>"+quantite+"/"+quantite+"</td></tr>";}
			if ((quantite > 0)&&(type == "option")){ innerHTML += "<tr><td><b>"+WPJS.rf_TMax+" </b></td><td>"+quantite+"</td></tr>";}
			if ((code != "")&&(type == "option")){ 
				innerHTML += "<tr><td><b>"+WPJS.rf_TAutomaticQuantity+" </b></td><td><select disabled>";
				innerHTML += '<option value="userchoice" '+((code == 'userchoice') ? 'selected' : '' )+'>'+WPJS.rf_tUserChoice+'</option>';
				innerHTML += '<option value="oneperhour" '+((code == 'oneperhour') ? 'selected' : '' )+'>'+WPJS.rf_TOnePerHour+'</option>';
				innerHTML += '<option value="oneperday" '+((code == 'oneperday') ? 'selected' : '' )+'>'+WPJS.rf_TOnePerDay+'</option>';
				innerHTML += '<option value="onepernight" '+((code == 'onepernight') ? 'selected' : '' )+'>'+WPJS.rf_TOnePerNight+'</option>';
				innerHTML += '<option value="oneperweek" '+((code == 'oneperweek') ? 'selected' : '' )+'>'+WPJS.rf_TOnePerWeek+'</option>';
				innerHTML += '<option value="onepermonth" '+((code == 'onepermonth') ? 'selected' : '' )+'>'+WPJS.rf_TOnePerMonth+'</option>';
				innerHTML += "</select></td></tr>";
			}
			if (montant > 0){ innerHTML += "<tr><td><b>"+WPJS.rf_TAmount+" </b></td><td class='rf_displayLocalPrice'>"+montant+"</td></tr>";}
			if (pourcentage > 0){ innerHTML += "<tr><td><b>"+WPJS.rf_TPercentage+" </b></td><td>"+pourcentage+"%</td></tr>";}
			if (periode_heure > 0){ innerHTML += "<tr><td><b>"+WPJS.rf_TPeriodicity+" </b></td><td>"+periode_heure+"h</td></tr>";}
			if ((code != "")&&(type != "option")){ innerHTML += "<tr><td><b>"+WPJS.rf_TCode+" </b></td><td>"+code+"</td></tr>";}
			if (details_texte != ""){
				innerHTML += "<tr><td colspan='2'><b>"+WPJS.rf_tOptions+': </b><br>';
				var displayDetails = details_texte.split("\n");
				for(var i=0;i<displayDetails.length;i++){
					innerHTML += '#'+(i+1)+': ';
					innerHTML += displayDetails[i];
					innerHTML += '<br>';
				}
				innerHTML += '</td></tr>';
			}
			if (description != ""){ innerHTML += "<tr><td colspan='2'><br><b>"+WPJS.rf_TDescriptionDetails+": </b><br>"+description+"</td></tr>";}
			div.innerHTML = innerHTML + "</table><br>";
			div.style.maxHeight = "initial";
			document.getElementById("rf_" + type + "s_associes").firstChild.appendChild(div);
			window.scrollTo(0, 0);
			rf_initDisplayPrice();
			document.getElementsByName("label_"+type)[0].value = "";
			document.getElementsByName("date_debut_"+type)[0].value = rf_currentdate(0);
			document.getElementsByName("date_fin_"+type)[0].value = "";
			document.getElementsByName("quantite_"+type)[0].value = "";
			if (document.getElementsByName("details_texte_"+type)[0]){
				document.getElementsByName("details_texte_"+type)[0].value = "";
			}
			document.getElementsByName("montant_"+type)[0].value = "";
			document.getElementsByName("pourcentage_"+type)[0].value = "";
			document.getElementsByName("periode_heure_"+type)[0].value = "";
			document.getElementsByName("code_"+type)[0].value = "userchoice";
			document.getElementsByName("description_"+type)[0].value = "";
		});
	}
}

function rf_associeCoupon(id_coupon,type){
	if (document.getElementById("rf_mainDesassocieCoupon")){
		document.getElementById("rf_" + type + id_coupon).style.display = "none";
		document.getElementById("rf_" + type + "Panel" + id_coupon).style.maxHeight = "1px";
		document.getElementById("rf_" + type + "Space" + id_coupon).style.display = "block";
		document.getElementById("rf_" + type + "PanelSpace" + id_coupon).style.maxHeight = "initial";
		var rf_idSpace = document.getElementsByName("rf_idSpace")[1].value;
		var wpnonce = document.getElementById("rf_mainAssocieCoupon").value;
		var data = {
			'action': 'js_associeCoupon',
			'rf_idSpace': rf_idSpace,
			'id_coupon': id_coupon,
			'_wpnonce': wpnonce
		};
		jQuery.post(ajaxurl, data, function(response) {});
	}
}

function rf_desassocieCoupon(id_coupon,type){
	if (document.getElementById("rf_mainDesassocieCoupon")){
		document.getElementById("rf_" + type + id_coupon).style.display = "block";
		document.getElementById("rf_" + type + "Panel" + id_coupon).style.maxHeight = "initial";
		document.getElementById("rf_" + type + "Space" + id_coupon).style.display = "none";
		document.getElementById("rf_" + type + "PanelSpace" + id_coupon).style.maxHeight = "1px";
		var rf_idSpace = document.getElementsByName("rf_idSpace")[1].value;
		var wpnonce = document.getElementById("rf_mainDesassocieCoupon").value;
		var data = {
			'action': 'js_desassocieCoupon',
			'rf_idSpace': rf_idSpace,
			'id_coupon': id_coupon,
			'_wpnonce': wpnonce
		};
		jQuery.post(ajaxurl, data, function(response) {});
	}
}

function rf_emptyMontant(type,id){
	var id = (typeof id !== 'undefined') ? id : '';
	if (document.getElementsByName("pourcentage_"+type+id)[0].value != ""){
		document.getElementsByName("montant_"+type+id)[0].value = "";
	}
}

function rf_emptyPourcentage(type,id){
	var id = (typeof id !== 'undefined') ? id : '';
	if (document.getElementsByName("montant_"+type+id)[0].value != ""){
		document.getElementsByName("pourcentage_"+type+id)[0].value = "";
	}
}

function rf_listenerExportCSV(){
	document.forms["rf_exportCSV"].addEventListener("submit",rf_exportCSV);
}

function rf_listenerSaveShortcode(){
	document.forms["rf_saveShortcode"].addEventListener("submit",rf_saveShortcode);
}

function rf_listenerFilterBookings(){
	document.forms["rf_filterBookings"].addEventListener("submit",rf_filterBookings);
}

function rf_filterBookings(e){
	e.preventDefault();
	var wpnonce = encodeURIComponent(document.getElementById("rf_mainCalendar").value);
	var data = {
		'action': 'js_chargeCalendrier',
		'rf_idSpace': "-1",
		'rf_init': "false",
		'CurrentDate': "0000-00-00",
		'_wpnonce': wpnonce,
		'filterDepartureDate': document.getElementsByName('filterDepartureDate')[0].value,
		'filterArrivalDate': document.getElementsByName('filterArrivalDate')[0].value,
		'filterBookingStatus': document.getElementsByName('filterBookingStatus')[0].value
	};
	if (typeof(ajaxurl) == "undefined"){
		var ajaxurl = WPJS.adminAjaxUrl;
	}
	jQuery.post(ajaxurl, data, function(response) {
		rf_calendatMakeCalendarResa(JSON.parse(response));
	});
}

function rf_exportCSV(e){
	document.getElementsByName('rf_ead')[0].value = document.getElementsByName('filterArrivalDate')[0].value;
	document.getElementsByName('rf_edd')[0].value = document.getElementsByName('filterDepartureDate')[0].value;
	document.getElementsByName('rf_ebs')[0].value = document.getElementsByName('filterBookingStatus')[0].value;
}
var panels;
function rf_saveShortcode(e){
	var tabEmplacements = new Array();
	panels = document.getElementsByClassName("rf_panel");
	var ok = true;
	for(var i=0; i<panels.length; i++){
		var table = new Array('','','','','');
		var posttype = -1;
		var customlink = '';
		
		var inputs = panels[i].getElementsByTagName("input");
		for (var j=0; j < inputs.length; j++){
			if (inputs[j].name.substr(0,22) == "shortcodespaceposttype"){
				if (inputs[j].checked){
					posttype = inputs[j].value;
					table[2] = posttype;
				}
			}
			if (inputs[j].name == "shortcodespacecustomlink"){
				customlink = inputs[j].value;
			}
		}
		if (posttype == "link"){
			table[3] = customlink;
		}
		var selects = panels[i].getElementsByTagName("select");
		for (var j=0; j < selects.length; j++){
			if (selects[j].name == "shortcodespace"){
				table[0] = selects[j].value;
			}
			if (selects[j].name == "shortcodespaceposition"){
				table[1] = selects[j].value;
			}
			if ((selects[j].name == "shortcodeRedirectionSelectPage")&&(posttype == "page")){
				table[3] = selects[j].value;
			}
			if ((selects[j].name == "shortcodeRedirectionSelectPost")&&(posttype == "post")){
				table[3] = selects[j].value;
			}
		}
		var textareas = panels[i].getElementsByTagName("textarea");
		for (var j=0; j < textareas.length; j++){
			if (textareas[j].name.substr(0,25) == "shortcodespacedescription"){
				table[4] = rf_getWPEditorContent(textareas[j].id);
			}
		}
		if ((table[0] != "")&&(table[2] != "")&&(table[3] != "")){
			tabEmplacements.push(table);
		}else if ((table[0] != "")||(table[2] != "")||(table[3] != "")||(table[4] != "")){
			ok = false;
			alert(WPJS.rf_TFillAllFieldsInPosition + " " + table[1]);
		}
	}
	if (ok){
		for(var i=0; i<panels.length; i++){
			panels[i].innerHTML = "";
		}
		document.getElementById("tabEmplacements").innerHTML = JSON.stringify(tabEmplacements);
	}else{
		e.preventDefault();
	}
}

function rf_getWPEditorContent(id) {
    var content;
    var inputid = id;
    var editor = tinyMCE.get(inputid);
    var textArea = jQuery('textarea#' + inputid);    
    if (textArea.length>0 && textArea.is(':visible')) {
        content = textArea.val();        
    } else {
        content = editor.getContent();
    }
    return content;
}

function rf_getMP(elt,list,type){
	var list = document.getElementsByName(list)[0];
	var value = elt.value;
	//for(var i in list.options){
	for(var i = 0; i < list.options.length; i++){
		if (list.options[i].value == value){
			var idMP = list.options[i].getAttribute("mpid");
			var wpnonce = document.getElementById("rf_get_MP").value;
			var data = {
				'action': 'js_getMP',
				'idMP': idMP,
				'_wpnonce': wpnonce
			};
			jQuery.post(ajaxurl, data, function(response) {
				var reponse = JSON.parse(response);
				document.getElementsByName("montant"+type+"-1")[0].value = reponse.montant;
				document.getElementsByName("pourcentage"+type+"-1")[0].value = reponse.pourcentage;
				document.getElementsByName("periode_heure"+type+"-1")[0].value = reponse.periode_heure;
				document.getElementsByName("code"+type+"-1")[0].value = reponse.code;
				document.getElementsByName("description"+type+"-1")[0].value = reponse.description;
			});
			
			break;
		}
	}
}

var rf_tzafrica = new Array('Africa/Abidjan','Africa/Accra','Africa/Addis_Ababa','Africa/Algiers','Africa/Asmara','Africa/Asmera','Africa/Bamako','Africa/Bangui','Africa/Banjul','Africa/Bissau','Africa/Blantyre','Africa/Brazzaville','Africa/Bujumbura','Africa/Cairo','Africa/Casablanca','Africa/Ceuta','Africa/Conakry','Africa/Dakar','Africa/Dar_es_Salaam','Africa/Djibouti','Africa/Douala','Africa/El_Aaiun','Africa/Freetown','Africa/Gaborone','Africa/Harare','Africa/Johannesburg','Africa/Juba','Africa/Kampala','Africa/Khartoum','Africa/Kigali','Africa/Kinshasa','Africa/Lagos','Africa/Libreville','Africa/Lome','Africa/Luanda','Africa/Lubumbashi','Africa/Lusaka','Africa/Malabo','Africa/Maputo','Africa/Maseru','Africa/Mbabane','Africa/Mogadishu','Africa/Monrovia','Africa/Nairobi','Africa/Ndjamena','Africa/Niamey','Africa/Nouakchott','Africa/Ouagadougou','Africa/Porto-Novo','Africa/Sao_Tome','Africa/Timbuktu','Africa/Tripoli','Africa/Tunis','Africa/Windhoek');
var rf_tzamerica = new Array('America/Adak','America/Anchorage','America/Anguilla','America/Antigua','America/Araguaina','America/Argentina/Buenos_Aires','America/Argentina/Catamarca','America/Argentina/ComodRivadavia','America/Argentina/Cordoba','America/Argentina/Jujuy','America/Argentina/La_Rioja','America/Argentina/Mendoza','America/Argentina/Rio_Gallegos','America/Argentina/Salta','America/Argentina/San_Juan','America/Argentina/San_Luis','America/Argentina/Tucuman','America/Argentina/Ushuaia','America/Aruba','America/Asuncion','America/Atikokan','America/Atka','America/Bahia','America/Bahia_Banderas','America/Barbados','America/Belem','America/Belize','America/Blanc-Sablon','America/Boa_Vista','America/Bogota','America/Boise','America/Buenos_Aires','America/Cambridge_Bay','America/Campo_Grande','America/Cancun','America/Caracas','America/Catamarca','America/Cayenne','America/Cayman','America/Chicago','America/Chihuahua','America/Coral_Harbour','America/Cordoba','America/Costa_Rica','America/Creston','America/Cuiaba','America/Curacao','America/Danmarkshavn','America/Dawson','America/Dawson_Creek','America/Denver','America/Detroit','America/Dominica','America/Edmonton','America/Eirunepe','America/El_Salvador','America/Ensenada','America/Fort_Wayne','America/Fortaleza','America/Glace_Bay','America/Godthab','America/Goose_Bay','America/Grand_Turk','America/Grenada','America/Guadeloupe','America/Guatemala','America/Guayaquil','America/Guyana','America/Halifax','America/Havana','America/Hermosillo','America/Indiana/Indianapolis','America/Indiana/Knox','America/Indiana/Marengo','America/Indiana/Petersburg','America/Indiana/Tell_City','America/Indiana/Vevay','America/Indiana/Vincennes','America/Indiana/Winamac','America/Indianapolis','America/Inuvik','America/Iqaluit','America/Jamaica','America/Jujuy','America/Juneau','America/Kentucky/Louisville','America/Kentucky/Monticello','America/Knox_IN','America/Kralendijk','America/La_Paz','America/Lima','America/Los_Angeles','America/Louisville','America/Lower_Princes','America/Maceio','America/Managua','America/Manaus','America/Marigot','America/Martinique','America/Matamoros','America/Mazatlan','America/Mendoza','America/Menominee','America/Merida','America/Metlakatla','America/Mexico_City','America/Miquelon','America/Moncton','America/Monterrey','America/Montevideo','America/Montreal','America/Montserrat','America/Nassau','America/New_York','America/Nipigon','America/Nome','America/Noronha','America/North_Dakota/Beulah','America/North_Dakota/Center','America/North_Dakota/New_Salem','America/Ojinaga','America/Panama','America/Pangnirtung','America/Paramaribo','America/Phoenix','America/Port-au-Prince','America/Port_of_Spain','America/Porto_Acre','America/Porto_Velho','America/Puerto_Rico','America/Rainy_River','America/Rankin_Inlet','America/Recife','America/Regina','America/Resolute','America/Rio_Branco','America/Rosario','America/Santa_Isabel','America/Santarem','America/Santiago','America/Santo_Domingo','America/Sao_Paulo','America/Scoresbysund','America/Shiprock','America/Sitka','America/St_Barthelemy','America/St_Johns','America/St_Kitts','America/St_Lucia','America/St_Thomas','America/St_Vincent','America/Swift_Current','America/Tegucigalpa','America/Thule','America/Thunder_Bay','America/Tijuana','America/Toronto','America/Tortola','America/Vancouver','America/Virgin','America/Whitehorse','America/Winnipeg','America/Yakutat','America/Yellowknife');
var rf_tzantarctica = new Array('Antarctica/Casey','Antarctica/Davis','Antarctica/DumontDUrville','Antarctica/Macquarie','Antarctica/Mawson','Antarctica/McMurdo','Antarctica/Palmer','Antarctica/Rothera','Antarctica/South_Pole','Antarctica/Syowa','Antarctica/Troll','Antarctica/Vostok');
var rf_tzarctic = new Array('Arctic/Longyearbyen');
var rf_tzasia = new Array('Asia/Aden','Asia/Almaty','Asia/Amman','Asia/Anadyr','Asia/Aqtau','Asia/Aqtobe','Asia/Ashgabat','Asia/Ashkhabad','Asia/Baghdad','Asia/Bahrain','Asia/Baku','Asia/Bangkok','Asia/Beirut','Asia/Bishkek','Asia/Brunei','Asia/Calcutta','Asia/Chita','Asia/Choibalsan','Asia/Chongqing','Asia/Chungking','Asia/Colombo','Asia/Dacca','Asia/Damascus','Asia/Dhaka','Asia/Dili','Asia/Dubai','Asia/Dushanbe','Asia/Gaza','Asia/Harbin','Asia/Hebron','Asia/Ho_Chi_Minh','Asia/Hong_Kong','Asia/Hovd','Asia/Irkutsk','Asia/Istanbul','Asia/Jakarta','Asia/Jayapura','Asia/Jerusalem','Asia/Kabul','Asia/Kamchatka','Asia/Karachi','Asia/Kashgar','Asia/Kathmandu','Asia/Katmandu','Asia/Khandyga','Asia/Kolkata','Asia/Krasnoyarsk','Asia/Kuala_Lumpur','Asia/Kuching','Asia/Kuwait','Asia/Macao','Asia/Macau','Asia/Magadan','Asia/Makassar','Asia/Manila','Asia/Muscat','Asia/Nicosia','Asia/Novokuznetsk','Asia/Novosibirsk','Asia/Omsk','Asia/Oral','Asia/Phnom_Penh','Asia/Pontianak','Asia/Pyongyang','Asia/Qatar','Asia/Qyzylorda','Asia/Rangoon','Asia/Riyadh','Asia/Saigon','Asia/Sakhalin','Asia/Samarkand','Asia/Seoul','Asia/Shanghai','Asia/Singapore','Asia/Srednekolymsk','Asia/Taipei','Asia/Tashkent','Asia/Tbilisi','Asia/Tehran','Asia/Tel_Aviv','Asia/Thimbu','Asia/Thimphu','Asia/Tokyo','Asia/Ujung_Pandang','Asia/Ulaanbaatar','Asia/Ulan_Bator','Asia/Urumqi','Asia/Ust-Nera','Asia/Vientiane','Asia/Vladivostok','Asia/Yakutsk','Asia/Yekaterinburg','Asia/Yerevan');
var rf_tzatlantic = new Array('Atlantic/Azores','Atlantic/Bermuda','Atlantic/Canary','Atlantic/Cape_Verde','Atlantic/Faeroe','Atlantic/Faroe','Atlantic/Jan_Mayen','Atlantic/Madeira','Atlantic/Reykjavik','Atlantic/South_Georgia','Atlantic/St_Helena','Atlantic/Stanley');
var rf_tzaustralia = new Array('Australia/ACT','Australia/Adelaide','Australia/Brisbane','Australia/Broken_Hill','Australia/Canberra','Australia/Currie','Australia/Darwin','Australia/Eucla','Australia/Hobart','Australia/LHI','Australia/Lindeman','Australia/Lord_Howe','Australia/Melbourne','Australia/North','Australia/NSW','Australia/Perth','Australia/Queensland','Australia/South','Australia/Sydney','Australia/Tasmania','Australia/Victoria','Australia/West','Australia/Yancowinna');
var rf_tzeurope = new Array('Europe/Amsterdam','Europe/Andorra','Europe/Athens','Europe/Belfast','Europe/Belgrade','Europe/Berlin','Europe/Bratislava','Europe/Brussels','Europe/Bucharest','Europe/Budapest','Europe/Busingen','Europe/Chisinau','Europe/Copenhagen','Europe/Dublin','Europe/Gibraltar','Europe/Guernsey','Europe/Helsinki','Europe/Isle_of_Man','Europe/Istanbul','Europe/Jersey','Europe/Kaliningrad','Europe/Kiev','Europe/Lisbon','Europe/Ljubljana','Europe/London','Europe/Luxembourg','Europe/Madrid','Europe/Malta','Europe/Mariehamn','Europe/Minsk','Europe/Monaco','Europe/Moscow','Europe/Nicosia','Europe/Oslo','Europe/Paris','Europe/Podgorica','Europe/Prague','Europe/Riga','Europe/Rome','Europe/Samara','Europe/San_Marino','Europe/Sarajevo','Europe/Simferopol','Europe/Skopje','Europe/Sofia','Europe/Stockholm','Europe/Tallinn','Europe/Tirane','Europe/Tiraspol','Europe/Uzhgorod','Europe/Vaduz','Europe/Vatican','Europe/Vienna','Europe/Vilnius','Europe/Volgograd','Europe/Warsaw','Europe/Zagreb','Europe/Zaporozhye','Europe/Zurich');
var rf_tzindian = new Array('Indian/Antananarivo','Indian/Chagos','Indian/Christmas','Indian/Cocos','Indian/Comoro','Indian/Kerguelen','Indian/Mahe','Indian/Maldives','Indian/Mauritius','Indian/Mayotte','Indian/Reunion');
var rf_tzpacific = new Array('Pacific/Apia','Pacific/Auckland','Pacific/Bougainville','Pacific/Chatham','Pacific/Chuuk','Pacific/Easter','Pacific/Efate','Pacific/Enderbury','Pacific/Fakaofo','Pacific/Fiji','Pacific/Funafuti','Pacific/Galapagos','Pacific/Gambier','Pacific/Guadalcanal','Pacific/Guam','Pacific/Honolulu','Pacific/Johnston','Pacific/Kiritimati','Pacific/Kosrae','Pacific/Kwajalein','Pacific/Majuro','Pacific/Marquesas','Pacific/Midway','Pacific/Nauru','Pacific/Niue','Pacific/Norfolk','Pacific/Noumea','Pacific/Pago_Pago','Pacific/Palau','Pacific/Pitcairn','Pacific/Pohnpei','Pacific/Ponape','Pacific/Port_Moresby','Pacific/Rarotonga','Pacific/Saipan','Pacific/Samoa','Pacific/Tahiti','Pacific/Tarawa','Pacific/Tongatapu','Pacific/Truk','Pacific/Wake','Pacific/Wallis','Pacific/Yap');
var rf_tzothers = new Array('UTC');

function showTimezone(value,selected){
	var tab = [];
	if (value == 1){tab = rf_tzafrica;}
	if (value == 2){tab = rf_tzamerica;}
	if (value == 3){tab = rf_tzantarctica;}
	if (value == 4){tab = rf_tzarctic;}
	if (value == 5){tab = rf_tzasia;}
	if (value == 6){tab = rf_tzatlantic;}
	if (value == 7){tab = rf_tzaustralia;}
	if (value == 8){tab = rf_tzeurope;}
	if (value == 9){tab = rf_tzindian;}
	if (value == 10){tab = rf_tzpacific;}
	if (value == 11){tab = rf_tzothers;}
	
	var options = '';
	//for(var i in tab){
	for(var i = 0; i < tab.length; i++){
		var sel = '';
		if (tab[i] == selected){sel = "selected";}
		options += '<option ' + sel + ' value="'+tab[i]+'">'+tab[i]+'</option>';
	}
	document.getElementById('rf_timezone').innerHTML = options;	
	//document.getElementById('rf_timezone').value = '';	
}

function rf_shortcodeChangeLanguage(select){
	var id = select.name.split("-")[1];
	document.getElementById("rf_shortcodeShortcode" + id).innerHTML = '[rf_shortcode id="' + id + '" lang="' + select.value + '"]';
}

function rf_initDefaultActionButton(){
	if (document.getElementById("rf_btnAddSpace")){
		document.getElementById("rf_btnAddSpace").addEventListener("click",rf_showAddSpaceForm);
		document.getElementById("rf_btnCancelAddSpace").addEventListener("click",rf_hideAddSpaceForm);
	}
	if (document.getElementById("rf_btnEditSpace")){
		document.getElementById("rf_btnEditSpace").addEventListener("click",rf_showEditSpaceForm);
		document.getElementById("rf_btnCancelEditSpace").addEventListener("click",rf_hideEditSpaceForm);
	}
}

function rf_showAddSpaceForm(){
	//document.getElementById("rf_addSpaceForm").style.display = "flex";
	document.getElementById("rf_addSpaceForm").classList.add("rf_flex");
}
function rf_showEditSpaceForm(){
	//document.getElementById("rf_editSpaceForm").style.display = "flex";
	document.getElementById("rf_editSpaceForm").classList.add("rf_flex");
}
function rf_hideAddSpaceForm(){
	//document.getElementById("rf_addSpaceForm").style.display = "none";
	document.getElementById("rf_addSpaceForm").classList.remove("rf_flex");
}
function rf_hideEditSpaceForm(){
	//document.getElementById("rf_editSpaceForm").style.display = "none";
	document.getElementById("rf_editSpaceForm").classList.remove("rf_flex");
}

function rf_ajouterPeriodePrix(){
	if ((document.getElementById("rf_periodPrice")) &&
		(document.getElementById("rf_periodPriceStartDate")) && (document.getElementById("rf_periodPriceStartDate").value != "") &&
		(document.getElementById("rf_periodPriceFinishDate")) && (document.getElementById("rf_periodPriceFinishDate").value != "")	
	){
		var start = document.getElementById("rf_periodPriceStartDate").value + " " + document.getElementById("rf_periodPriceStartTime").value;
		var finish = document.getElementById("rf_periodPriceFinishDate").value + " " + document.getElementById("rf_periodPriceFinishTime").value;
		var price = document.getElementById("rf_periodPrice").value;
		var rf_idSpace = document.getElementsByName("rf_idSpace")[1].value;
		var wpnonce = document.getElementById("rf_mainAddPeriodePrice").value;
		var data = {
			'action': 'js_addPeriodePrice',
			'start': start,
			'finish': finish,
			'price': price,
			'rf_idSpace': rf_idSpace,
			'_wpnonce': wpnonce
		};
		jQuery.post(ajaxurl, data, function(response) {
			if (response != ''){
				alert(response);
			}else{
				var index = document.getElementsByClassName('rf_periodList').length + 1;
				var div = document.createElement('div');
				div.className = 'rf_periodList';
				div.id = 'rf_period' + index;
				var innerHTML =	'<div><div><input type="date" disabled value="'+start.split(' ')[0]+'"><input type="time" disabled value="'+start.split(' ')[1]+'"></div><div><input disabled type="date" value="'+finish.split(' ')[0]+'"><input disabled type="time" value="'+finish.split(' ')[1]+'"></div></div>';
				innerHTML +=	'<div><div>'+'Price'+': ' + price + '</div></div>';
				innerHTML +=	'<div><div class="rf_delete"><span class="rf_deleteAjax" onclick="rf_delete_period(\''+index+'\',\''+rf_idSpace+'\',\''+start+'\',\''+finish+'\',\''+price+'\')">'+WPJS.rf_TDelete+'</span></div></div>';
				div.innerHTML = innerHTML;
				document.getElementById('rf_listperiodesprices').appendChild(div);
				document.getElementById("rf_periodPriceStartDate").value = '',
				document.getElementById("rf_periodPriceStartTime").value = '00:00',
				document.getElementById("rf_periodPriceFinishDate").value = '';
				document.getElementById("rf_periodPriceFinishTime").value = '00:00';
				document.getElementById("rf_periodPrice").value = 0;
			}
			
		});
	}
}

function rf_delete_period(index,rf_idSpace,start,finish,price){
	if (confirm(WPJS.rf_TConfirmDeleteItem)){
		document.getElementById("rf_period"+index).style.display = "none";	
		var wpnonce = document.getElementById("rf_deletePeriod").value;
		var data = {
			'action': 'js_deletePeriod',
			'rf_idSpace': rf_idSpace,
			'start': start,
			'finish': finish,
			'price': price,
			'_wpnonce': wpnonce
		};
		jQuery.post(ajaxurl, data, function(response) {});
	}
}

function rf_ajouterExceptionalClosure(){
	if ((document.getElementById("rf_periodClosureStartDate")) && (document.getElementById("rf_periodClosureStartDate").value != "") &&
		(document.getElementById("rf_periodClosureFinishDate")) && (document.getElementById("rf_periodClosureFinishDate").value != "")
	){
		var start = document.getElementById("rf_periodClosureStartDate").value + " " + document.getElementById("rf_periodClosureStartTime").value;
		var finish = document.getElementById("rf_periodClosureFinishDate").value + " " + document.getElementById("rf_periodClosureFinishTime").value;
		var rf_idSpace = document.getElementsByName("rf_idSpace")[1].value;
		var wpnonce = document.getElementById("rf_mainAddExceptionalClosure").value;
		var data = {
			'action': 'js_addExceptionalClosure',
			'start': start,
			'finish': finish,
			'rf_idSpace': rf_idSpace,
			'_wpnonce': wpnonce
		};
		jQuery.post(ajaxurl, data, function(response) {
			if (response != ''){
				alert(response);
			}else{
				var index = document.getElementsByClassName('rf_periodListClosure').length + 1;
				var div = document.createElement('div');
				div.className = 'rf_periodListClosure';
				div.id = 'rf_periodclosure' + index;
				var innerHTML =	'<div><div><input type="date" disabled value="'+start.split(' ')[0]+'"><input type="time" disabled value="'+start.split(' ')[1]+'"></div><div><input disabled type="date" value="'+finish.split(' ')[0]+'"><input disabled type="time" value="'+finish.split(' ')[1]+'"></div></div>';
				innerHTML +=	'<div><div class="rf_deleteclosure"><span class="rf_deleteAjax" onclick="rf_delete_period_closure(\''+index+'\',\''+rf_idSpace+'\',\''+start+'\',\''+finish+'\')">'+WPJS.rf_TDelete+'</span></div></div>';
				div.innerHTML = innerHTML;
				document.getElementById('rf_listperiodesclosures').appendChild(div);
				document.getElementById("rf_periodClosureStartDate").value = '',
				document.getElementById("rf_periodClosureStartTime").value = '00:00',
				document.getElementById("rf_periodClosureFinishDate").value = '';
				document.getElementById("rf_periodClosureFinishTime").value = '00:00';
			}
			
		});
	}
}

function rf_delete_period_closure(index,rf_idSpace,start,finish){
	if (confirm(WPJS.rf_TConfirmDeleteItem)){
		document.getElementById("rf_periodclosure"+index).style.display = "none";	
		var wpnonce = document.getElementById("rf_deletePeriodClosure").value;
		var data = {
			'action': 'js_deletePeriodClosure',
			'rf_idSpace': rf_idSpace,
			'start': start,
			'finish': finish,
			'_wpnonce': wpnonce
		};
		jQuery.post(ajaxurl, data, function(response) {});
	}
}
