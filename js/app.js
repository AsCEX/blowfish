$(document).ready(function(){

	var type = $("#inputType").val();
	$(".inputType").html("( " + type + " )");

	var type2 = $("#inputType2").val();
	$(".inputType2").html("( " + type2 + " )");

	 $("#inputType").change(function(){
		$(".inputType").html("( " + $(this).val() + " )");
	 });

	 $("#inputType2").change(function(){
		$(".inputType2").html("( " + $(this).val() + " )");
	 });
});