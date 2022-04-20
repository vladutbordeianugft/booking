$(document).ready(function(){
	
	
	$("#login").click(function(){
		
		var email = $("#email").val();
		var password = $("#password").val();
		
		var data = { "email": email, "password": password };
		
		var btn = $(this);
		
		btn.attr("disabled",true);
		
		$.ajax({
			type: "POST",
			url: BASE_URL+"/api/user/login",
			data: JSON.stringify(data),
			contentType: "application/json; charset=utf-8",
			dataType: "json",
			success: function(data){
				alert(data.message);
				location.href = BASE_URL;	
			},
			error: function(errMsg) {
				var resp = JSON.parse(errMsg.responseText);
				alert(resp.message);
				btn.attr("disabled",false);
			}
		});
	});
	
	
	
	$(".book-trip").click(function(){
		
		var trip_id = $(this).data("trip-id");
		
		var data = { "id": trip_id};
		
		var btn = $(this);
		
		btn.attr("disabled",true);
		
		$.ajax({
			type: "POST",
			url: BASE_URL+"/api/trip/book",
			data: JSON.stringify(data),
			contentType: "application/json; charset=utf-8",
			dataType: "json",
			success: function(data){
				alert(data.message);
				if(data.status == "success"){
					btn.val("Booked");	
				}else{
					btn.attr("disabled",false);
				}
				
			},
			error: function(errMsg) {
				var resp = JSON.parse(errMsg.responseText);
				alert(resp.message);
				btn.attr("disabled",false);
			}
		});
		
	})
	
});