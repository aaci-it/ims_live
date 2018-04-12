var Provinces = ["Bulacan","Cagayan","Caloocan","Cavite","Cebu","Davao Del Norte","Davao Del Sur","Davao Occidental","Davao Oriental","Iloilo","Isabela","Laguna"];
var Bulacan = ["Angat", "Balagtas", "Baliuag","Bocaue","Bulacan","Bustos","Calumpit","Doña Remedios Trinidad","Guiginto","Hagonoy","Malolos","Marilao","Meycauayan","Norzagaray","Obando","Pandi","Paombong","Plaridel","Pulilan","San Ildefonso","San Jose del Monte","San Miguel","San Rafael","Santa Maria"];
var Cagayan = ["Abulug","Alcala","Allacapan","Amulung","Appari","Baggao","Ballesteros","Buguey","Calayan","Camalanuigan","Claveria","Enrile","Gattaran","Gonzaga","Iguig","Lal-lo","Lasam","Pamplona","Peñablanca","Piat","Rizal","Sanchez-Mira","Santa Ana","Santa Praxedes","Santa Teresita","Santo Niño","Solana","Tuao","Tuguegarao"];
var Caloocan = ["Amparo Subdivision","Baesa","Bagong","Bagumbong","Bankers","Caloocan","Captol","First","Fish","Grace","Isla","Kapitbayan","Kaunlaran","Kaybiga","Lilles","Maypajo","Novaliches","San","Sangandaan","Santa","Tala","University"];
var Cavite=["Alfonso","Amadeo","Bacoor","Carmona","Cavite City","Corregidor Island","Dasmariñas","Gen. Emilio Aguinaldo(Bailen)","General Mariano Alvarez","General Trias","Imus","Indang","Kawit","Magallanes","Maragondon","Mendez","Naic","Noveleta","Rosario","Sangley Point Naval Base","Silang","Tagaytay","Tanza","Ternate","Trece Martires City"];
var Cebu=["Alcantara","Alcoy","Alegria","Aloguinsan","Argao","Asturias","Badian","Balamban","Bantayan","Barili","Bogo City","Boljoon","Borbon","Carcar","Carmen","Catmon","Cebu City","Compostela","Consolacion","Cordova","Daanbantaya","Dalaguete","Danao City","Dumanjug","Ginatilan","Lapu-Lapu City",
"Liloan","Mactan Airport","Madridejos","Malabuyo","Mandaue","Medelli","Minglanilla","Moalboal","Naga City","Oslo","Pila","Pinamungaja","Por","Rond","Samboan","San Fernando","San Francisco","San Remigio","Santa Fe","Santander","Sibonga","Sogod","Tabogon","Tabuelan","Talisay City","Toledo","Tuburan","Tudela"];
var DavaoDelNorte=["Asuncion","Babak","Carmen","Kapalong","Kaputian","New Corella","Panabo","Samal City","Santo Tomas","Tagum"];
var DavaoDelSur=["Bansalan","Davao City","Digos","Hagonoy","Kiblawan","Magsaysay","Malalag","Matanao","Padada","Santa Cruz","Sulop"];
var DavaoOccidental=["Don Marcelino","Jose Abad Santos","Malita","Santa Maria","Sarangani"];
var DavaoOriental=["Baganga","Banaybanay","Boston","Caraga","Cateel","Governor Generoso","Lupon","Manay","Mati","San Isidro","Tarragona"];
var Iloilo=["Ajuy","Alimodian","Anilao","Badiangan","Balasan","Banate","Barotac Nuevo","Barotac Viejo","Batad","Bingawan","Cabatuan","Calinog","Carles",
"Concepcion","Diangle","Duenas","Dumangas","Estancia","Guimbal","Igbaras","Iloilo City","Janiuay","Lambunao","Leganes","Lemery","Leon","Maasin","Miagao",
"Mina","New Lucena","Oton","Passi City","Pavia","Pototan","San Dionisio","San Enrique","San Joaquin","San Miguel","San Rafael","Santa Barbara","Sara","Tigabuan","Tubungan","Zarraga"];
var Isabela=["Alicia","Angadanan","Aurora","Benito Soliven","Burgos","Cabagan","Cabatuan","Cauayan City","Cordon","Delfin Albano","Dinapigui","Divilacan","Echague","Gamu","Ilagan","Jones",
"Luna","Maconacon","Mailig","Naguillan","Palalan","Quezon","Quirino","Ramon","Reina Mercedes","Roxas","San Agustin","San Guillermo","San Isidro","San Manuel(Callang)","San Mariano","San Mateo","San Pablo","Santa Maria","Santiago City","Santo Tomas","Tumauini"];
var Laguna=["Alaminos","Bay","Biñan City","Cabuyao city","Calamba City","Calauan","City","Canlubang","Cavinti City","Famy","Kalayaan","Liliw",
"Los Baños","Luisiana","Lumban","Mabitac","Magdalena","Majayjay","Nagcarlan","Paete","Pagsanjan","Pakil","Pangil","Pila","Rizal","San Pablo City","San Pedro City","Santa Cruz","Santa Maria","Santa Rosa City","Siniloan","University Of the Philippines, Los Baños","Victoria"];

var BulacanZip=["3012","3016","3006","3018","3017","3007","3003","3009","3015","3002","3000","3019","3020","3013","3021","3014","3001","3004","3005","3010","3023","3011","3008","3022"];
var CagayanZip=["3517","3507","3523","3505","3515","3506","3516","3511","3520","3510","3519","3501","3508","3513","3504","3509","3524","3522","3502","3527","3526","3518","3514","3521","3512","3525","3503","3528","3500"];
var CaloocanZip=["1425","1401","1428","1421","1426","1400","1424","1405","1411","1406","1412","1413","1409","1420","1423","1410","1422","1404","1408","1402","1427","1407"];
var CaviteZip=["4123","4119","4102","4116","4100","4101","4125","4114","4124","4117","4107","4103","4122","4104","4113","4112","4121","4110","4105","4106","4118","4120","4108","4111","4109"];
var CebuZip=["6033","6023","6030","6040","6021","6042","6031","6041","6052","6036","6010","6024","6008","6019","6005","6006","6000","6003","6001","6017","6013","6022","6004","6035","6028","6015","6002","6016","6053","6029","6014","6012","6046","6032","6037","6025","6048","6039","6049","6034","6027","6018","6050","6011","6047","6026","6020","6007","6009","6044","6045","6038","6043","6051"];
var DavaoDelNorteZip=["8102","8118","8101","8113","8120","8104","8105","8119","8112","8100"];
var DavaoDelSurZip=["8005","8000","8002","8006","8008","8004","8010","8003","8007","8001","8009"];
var DavaoOccidentalZip=["8013","8014","8012","8011","8015"];
var DavaoOrientalZip=["8204","8208","8206","8203","8205","8210","8207","8202","8200","8209","8201"];
var IloiloZip=["5012","5028","5009","5033","5018","5010","5007","5011","5016","5041","5031","5040","5019","5013","5035","5038","5006","5017","5022","5029","5000","5034","5042","5003","5043","5026","5030","5023","5032","5005","5020","5037","5001","5008","5015","5036","5024","5025","5039","5002","5014","5021","5027","5004"];
var IsabelaZip=["3306","3307","3316","3331","3322","3328","3315","3305","3312","3326","3336","3335","3309","3301","3300","3313","3304","3333","3323","3302","3334","3324","3321","3319","3303","3320","3314","3308","3310","3317","3332","3318","3329","3330","3311","3327","3325"];
var LagunaZip=["4001","4033","4024","4025","4027","4012","4029","4028","4013","4021","4015","4004","4030","4032","4014","4020","4007","4005","4002","4016","4008","4017","4018","4010","4003","4000","4023","4009","4022","4026","4019","4031","4011"];

var selectedArray,selectedArrayZip;

function disableObjects(){
		
}
$(document).ready(function() {
   if(document.getElementById('submit').value == "Update"){
	    $('#tbPurpose').prop('disabled',true);
		$('#tbBussName').prop('disabled',true);
		$('#tbAddress').prop('disabled',true);
		$('#tbSecondAddress').prop('disabled',true);
		$('#tbThirdAddress').prop('disabled',true);
		$('#tbTin').prop('disabled',true);
		$('#tbContactPerson').prop('disabled',true);
		$('#tbTelNo').prop('disabled',true);
		$('#tbEmail').prop('disabled',true);
		$('#tbPaymentTerms').prop('disabled',true);
		$('#tbGroup').prop('disabled',true);
		$('#tbFaxNo').prop('disabled',true);
		$('#tbWebsite').prop('disabled',true);
		$('#tbCreditLimit').prop('disabled',true);
		$('#tbReqBy').prop('disabled',true);
		$('#tbCertifiedBy').prop('disabled',true);
		$('#tbApprovedBy').prop('disabled',true);
		$('#tbApprovedForBp').prop('disabled',true);
		$('#tbReviewedBy').prop('disabled',true);
		$('#tbEncodedBy').prop('disabled',true);
		$('#userfile').prop('disabled',true);	
		$('#typeofclient1').prop('disabled',true);
		$('#typeofclient2').prop('disabled',true);
		$('#typeofbp1').prop('disabled',true);
		$('#typeofbp2').prop('disabled',true);
		$('#typeofbp3').prop('disabled',true);
		$('#typeofbp4').prop('disabled',true);
		$('#typeofcurrency1').prop('disabled',true);
		$('#typeofcurrency2').prop('disabled',true);
		$('#typeofcurrency3').prop('disabled',true);
		$('#typeofsri1').prop('disabled',true);
		$('#typeofsri2').prop('disabled',true);
		$('#rLiable').prop('disabled',true);
		$('#rExempt').prop('disabled',true);
		$('#rEu').prop('disabled',true);
		$('#apptype').prop('disabled',true);
		$('#sales_type').prop('disabled',true);
		$('#provinces').prop('disabled',true);
		$('#cities').prop('disabled',true);
		document.getElementById('tbCode').required = true;
		document.getElementById('tbAcctControlNo').required = true;
		$('#tbCode').prop('disabled',true);
		$('#tbAcctControlNo').prop('disabled',true);
		var sel = document.getElementById("selectData");
		if(sel.length == 1){
			$('#selectData').prop('disabled',true);	
			$('#selectData option:contains("Choose Index")').text('No Data Available.');
			$('#tbCode').prop('disabled',true);
			$('#tbAcctControlNo').prop('disabled',true);
		}
		$('#btnAdmin').prop('disabled',true);
   }else if(document.getElementById('submit').value == "UPDATE"){
	   $('#tbPurpose').prop('disabled',true);
		$('#tbBussName').prop('disabled',true);
		$('#tbAddress').prop('disabled',true);
		$('#tbSecondAddress').prop('disabled',true);
		$('#tbThirdAddress').prop('disabled',true);
		$('#tbTin').prop('disabled',true);
		$('#tbContactPerson').prop('disabled',true);
		$('#tbTelNo').prop('disabled',true);
		$('#tbEmail').prop('disabled',true);
		$('#tbPaymentTerms').prop('disabled',true);
		$('#tbGroup').prop('disabled',true);
		$('#tbFaxNo').prop('disabled',true);
		$('#tbWebsite').prop('disabled',true);
		$('#tbCreditLimit').prop('disabled',true);
		$('#tbReqBy').prop('disabled',true);
		$('#tbCertifiedBy').prop('disabled',true);
		$('#tbApprovedBy').prop('disabled',true);
		$('#tbApprovedForBp').prop('disabled',true);
		$('#tbReviewedBy').prop('disabled',true);
		$('#tbEncodedBy').prop('disabled',true);
		$('#userfile').prop('disabled',true);	
		$('#typeofclient1').prop('disabled',true);
		$('#typeofclient2').prop('disabled',true);
		$('#typeofbp1').prop('disabled',true);
		$('#typeofbp2').prop('disabled',true);
		$('#typeofbp3').prop('disabled',true);
		$('#typeofbp4').prop('disabled',true);
		$('#typeofcurrency1').prop('disabled',true);
		$('#typeofcurrency2').prop('disabled',true);
		$('#typeofcurrency3').prop('disabled',true);
		$('#typeofsri1').prop('disabled',true);
		$('#typeofsri2').prop('disabled',true);
		$('#rLiable').prop('disabled',true);
		$('#rExempt').prop('disabled',true);
		$('#rEu').prop('disabled',true);
		$('#apptype').prop('disabled',true);
		$('#sales_type').prop('disabled',true);
		$('#provinces').prop('disabled',true);
		$('#cities').prop('disabled',true);
		document.getElementById('tbCode').required = true;
		document.getElementById('tbAcctControlNo').required = true;
		$('#tbCode').prop('disabled',true);
		$('#tbAcctControlNo').prop('disabled',true);
		var sel = document.getElementById("selectData");
		if(sel.length == 1){
			$('#selectData').prop('disabled',true);	
			$('#selectData option:contains("Choose Index")').text('No Data Available.');
			$('#tbCode').prop('disabled',true);
			$('#tbAcctControlNo').prop('disabled',true);
		}
   }else{
	   $('#divData').hide();
	   $('#selectData').prop('disabled',true);
   }
 });
function RedirectToPHP(id){
	if(id != "Choose Index"){
		$.ajax({
					url: 'populate.php',
					type: 'POST',
					data: {'id':id},
					success: function(row) {
							var arr=JSON.parse(row);
							if(String(arr["type"]).includes("customer")){
								document.getElementById("typeofclient1").checked=true;
							}else{
								document.getElementById("typeofclient2").checked=true;
							} 	
							if(String(arr["type"]).includes("creation")){
								document.getElementById("typeofbp1").checked=true;
								document.getElementById('tbOther').type = "hidden";
								document.getElementById('tbOther').value ="";
							}else if(String(arr["type"]).includes("modification")){
								document.getElementById("typeofbp2").checked=true;
								document.getElementById('tbOther').type = "hidden";
								document.getElementById('tbOther').value ="";
							}else if(String(arr["type"]).includes("deactivation")){
								document.getElementById("typeofbp3").checked=true;
								document.getElementById('tbOther').type = "hidden";
								document.getElementById('tbOther').value ="";
							}else{
								document.getElementById("typeofbp4").checked=true;
								if(document.getElementById('submit').value == "Update"){
									document.getElementById('tbOther').disabled = true;
								}else{
									document.getElementById('tbOther').disabled = false;
								}
								document.getElementById('tbOther').type = "text";
								document.getElementById("tbOther").value = arr["type"];
							}
							if(arr["currency"] == "php"){
								document.getElementById("typeofcurrency1").checked=true;
							}else if(arr["currency"] == "usd"){
								document.getElementById("typeofcurrency2").checked=true;
							}else{
								document.getElementById("typeofcurrency3").checked=true;
							}
							if(String(arr["payment_terms"]).includes("with")){
								document.getElementById("typeofsri1").checked=true;
							}else{
								document.getElementById("typeofsri2").checked=true;
							}
							if(arr["tax"] == "liable"){
								document.getElementById("rLiable").checked=true;
							}else if(arr["tax"] == "exempt"){
								document.getElementById("rExempt").checked=true;
							}else{
								document.getElementById("rEu").checked=true;
							}
							if(arr["company"] == "AACI" || arr["company"] == "Omnigrains" || arr["company"] == "SCI.SF" ||
							arr["company"] == "SCI.Peras" || arr["company"] == "JSY" || arr["company"] == "SUPC" ||
							arr["company"] == "St.Claire" || arr["company"] == "AABC"){
								document.getElementById("apptype").value = arr["company"];
								$('.selectpicker').selectpicker('refresh');
								document.getElementById('tbOtherCompany').type="hidden";
								document.getElementById('tbOtherCompany').value ="";
							}else{
								var sel = document.getElementById("apptype");
								sel.selectedIndex=8;
								$('.selectpicker').selectpicker('refresh');
								if(document.getElementById('submit').value == "Update"){
									document.getElementById('tbOtherCompany').disabled = true;
								}else{
									document.getElementById('tbOtherCompany').disabled = false;
								}
								document.getElementById('tbOtherCompany').type = "text";
								document.getElementById("tbOtherCompany").value = arr["company"];
							}
							if(arr["sales_type"] == "Cash"){
								var sel = document.getElementById("sales_type");
								sel.selectedIndex=1;
								$('.selectpicker').selectpicker('refresh');
							}else{
								var sel = document.getElementById("sales_type");
								sel.selectedIndex=2;
								$('.selectpicker').selectpicker('refresh');
							}
							document.getElementById("tbPurpose").value = arr["purpose"];
							document.getElementById("tbCode").value = arr["code"];
							document.getElementById("tbBussName").value = arr["buss_name"];
							document.getElementById("tbAddress").value = arr["address"];
							document.getElementById("tbSecondAddress").value = arr["second_address"];
							document.getElementById("tbThirdAddress").value = arr["third_address"];
							document.getElementById("tbTin").value = arr["tin"];
							document.getElementById("tbContactPerson").value = arr["contact_person"];
							document.getElementById("tbTelNo").value = arr["tel_no"];
							document.getElementById("tbEmail").value = arr["email"];
							document.getElementById("tbPaymentTerms").value = arr["payment_terms"];
							document.getElementById("tbAcctControlNo").value = arr["acct_controlno"];
							document.getElementById("tbGroup").value = arr["group_name"];
							document.getElementById("tbFaxNo").value = arr["fax_no"];
							document.getElementById("tbWebsite").value = arr["website"];
							document.getElementById("tbCreditLimit").value = arr["credit_limit"];
							document.getElementById("tbReqBy").value = arr["requested_by"];
							document.getElementById("tbCertifiedBy").value = arr["certified_by"];
							document.getElementById("tbApprovedBy").value = arr["approved_by"];
							document.getElementById("tbApprovedForBp").value = arr["approved_forbp"];
							document.getElementById("tbReviewedBy").value = arr["reviewed_by"];
							document.getElementById("tbEncodedBy").value = arr["encoded_by"];
							document.getElementById('userfile').required = false;
							document.getElementById('tbCode').disabled = false;
							document.getElementById('tbAcctControlNo').disabled = false;
						},
					error: function(){
						alert("ERROR!");
					}
					});
	}else{
		document.getElementById("mainForm").reset();
		document.getElementById('tbOtherCompany').disabled = true;
		document.getElementById('tbOtherCompany').type="hidden";
		document.getElementById('tbOther').disabled = true;
		document.getElementById('tbOther').type = "hidden";
		document.getElementById('tbCode').disabled = true;
		document.getElementById('tbAcctControlNo').disabled = true;
	}
				
			}
			
function showHideApp(value){
    if (value=='Others'){
    	document.getElementById('tbOtherCompany').disabled = false;
		document.getElementById('tbOtherCompany').type="text";
    }
    else{
		document.getElementById('tbOtherCompany').disabled = true;
		document.getElementById('tbOtherCompany').type="hidden";
		document.getElementById('tbOtherCompany').value ="";
    }
}
function showHideBP(value){
    if (value=='others'){
    	document.getElementById('tbOther').disabled = false;
		document.getElementById('tbOther').type = "text";
    }
    else{
		document.getElementById('tbOther').disabled = true;
		document.getElementById('tbOther').type = "hidden";
		document.getElementById('tbOther').value ="";
		
    }
}

function setRequired(value){
	if(value=='customer'){
			document.getElementById('tbGroup').required = true;
			document.getElementById('tbEmail').required = true;
			document.getElementById('tbPaymentTerms').required = true;
			document.getElementById('tbCreditLimit').required = true;
			document.getElementById('typeofsri').required = true;
			
		}else{
			document.getElementById('tbGroup').required = false;
			document.getElementById('tbEmail').required = false;
			document.getElementById('tbPaymentTerms').required = false;
			document.getElementById('tbCreditLimit').required = false;
			document.getElementById('typeofsri').required = false;
		}
}

function populateProvinces(){
	selectedArray=Provinces;
	for(var i = 0; i < selectedArray.length; i++) {
		document.write('<option value="' + selectedArray[i] +'">' + selectedArray[i] + '</option>');
	}
	}

function getSelectedProvince(){
	$("#provinces option[value='selectprovince']").remove();
	if(document.getElementById("provinces").value == "Bulacan"){
		selectedArray=Bulacan;
		selectedArrayZip=BulacanZip;
	}else if(document.getElementById("provinces").value == "Cagayan"){
		selectedArray=Cagayan;
		selectedArrayZip=CagayanZip;
	}else if(document.getElementById("provinces").value == "Caloocan"){
		selectedArray=Caloocan;
		selectedArrayZip=CaloocanZip;
	}else if(document.getElementById("provinces").value == "Cavite"){
		selectedArray=Cavite;
		selectedArrayZip=CaviteZip;
	}else if(document.getElementById("provinces").value == "Cebu"){
		selectedArray=Cebu;
		selectedArrayZip=CebuZip;
	}else if(document.getElementById("provinces").value == "Davao Del Norte"){
		selectedArray=DavaoDelNorte;
		selectedArrayZip=DavaoDelNorteZip;
	}else if(document.getElementById("provinces").value == "Davao Del Sur"){
		selectedArray=DavaoDelSur;
		selectedArrayZip=DavaoDelSurZip;
	}else if(document.getElementById("provinces").value == "Davao Occidental"){
		selectedArray=DavaoOccidental;
		selectedArrayZip=DavaoOccidentalZip;
	}else if(document.getElementById("provinces").value == "Davao Oriental"){
		selectedArray=DavaoOriental;
		selectedArrayZip=DavaoOrientalZip;
	}else if(document.getElementById("provinces").value == "Iloilo"){
		selectedArray=Iloilo;
		selectedArrayZip=IloiloZip;
	}else if(document.getElementById("provinces").value == "Isabela"){
		selectedArray=Isabela;
		selectedArrayZip=IsabelaZip;
	}else if(document.getElementById("provinces").value == "Laguna"){
		selectedArray=Laguna;
		selectedArrayZip=LagunaZip;
	}
	populateSelect();
	getSelectedCity();
}

function getSelectedCity(){
	document.getElementById("tbZipCode").value=selectedArrayZip[document.getElementById("cities").selectedIndex];
}
function populateSelect(){
	var sel = document.getElementById("cities");
	$("#cities").empty();
	for(var i = 0; i < selectedArray.length; i++) {
		var opt = selectedArray[i];
		var el = document.createElement("option");
		el.textContent = opt;
		el.value = opt;
		sel.appendChild(el);
	}	
	$('.selectpicker').selectpicker('refresh');
}