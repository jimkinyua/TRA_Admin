// JavaScript Document

function autocompletEmail(id,target) {
	//alert ("sawa");
/*  userID = UserID;
 customerID = CustomerID; */
 field_id = id;
 //alert (id);
 target_id = target;
 var min_length = 0; // min caracters to display the autocomplete
 var field = "#"+field_id;
 var targetField = "#"+target_id;
 var keyword = $(field).val();

 if (keyword.length >= min_length) {
  $.ajax({
   url: 'email_autocomplete.php?id='+field_id+'&target='+target_id,
   type: 'POST',
   data: {keyword:keyword},
   success:function(data){
    $(targetField).show();
    $(targetField).html(data);
   }
  });
 } else {
  $(targetField).hide();
 } 
  
}

function set_item(item,id,target) {
 
 document.getElementById(id).value += item+";";
 
 document.getElementById(target).style.display = "none";
}

function test()
{
    alert('Script Executed');
}
