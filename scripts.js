$(document).ready(function(){
		
	//meal yükle ajax
	$("#meal_yukle").click(function (e) {

		$("#loading").css( "display", "block" );
		$("#ayet_meal").val("");
		
		$.ajax({
			type: "GET",
			data: $('form').serialize(),
			url: "mealYukle.php",
			success: function(msg){
				$("#ayet_meal").val(msg);
				$("#loading").css( "display", "none" );
			}
		});
		e.preventDefault();
		
	});
	
	//sıfırla butonu
	$("#sifirla_button").click(function (e) {
		$("#ayet_meal").val("");
	});

	//açılır kapanır menü
	$("#box h3").click( function()
	{
		$(this).siblings("ul").slideToggle();
	});
});




function mealSec() {
	var meal_sec = document.getElementById("meal_sec");
	var meal_yukle_button = document.getElementById("meal_yukle");
	
	if(meal_sec.value == 'kendin')
	{
		meal_yukle_button.style.display = "none";
	}
	else
	{
		meal_yukle_button.style.display = "block";
	}		
}
	
function checkForm(){
	var regex1 = /^([1-9]\d{0,2})-([1-9]\d{0,2})$/; //regular expression defining a 5 digit number
	var regex2 = /^([1-9]\d{0,2})$/;
	var sureNo = document.getElementById("sure_no");
	var sureIndex = document.getElementById("sure_index");
	var alertMessage = "";
	var formHatali = false;
	
	if( sureIndex.value == -27)
	{
		formHatali = true;
		alertMessage += "Sure seçiniz.\n\n";
	}
	
	if (sureNo.value.search(regex1)==-1 && sureNo.value.search(regex2)==-1) //if match failed
	{
		formHatali = true;
		alertMessage += "Lütfen sure numarasına xxx-xxx veya xxx şeklinde giriş yapınız. \n(Örnek: 12-155 veya 129)";
	}
	
	
	if(formHatali)
	{
		document.getElementById("form_submit").disabled  = true;
		alert(alertMessage);
	}
	else
	{
		document.getElementById("form_submit").disabled  = false;
	}
}

	