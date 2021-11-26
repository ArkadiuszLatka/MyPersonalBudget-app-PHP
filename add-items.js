$(document).ready( function() 
{
	var d = new Date();
	var day = d.getDate();
	var month = d.getMonth() +1;
	var year = d.getFullYear();

	if(day <=9) day='0'+day;
	if(month <=9) month = '0' + month;

	//alert(day +"-"+month+"-"+year);
	$('#date').val(year+"-"+month+"-"+day);
	
})

