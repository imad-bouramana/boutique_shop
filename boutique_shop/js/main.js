$(function(){
	'use strict';
   $(window).scroll(function(){
   	var scrol = $(this).scrollTop();
	$('#logotext').css({
		'transform' : "translate(0px, "+scrol/2+"px)"
	});


   });
});
 //confirm delite
      $(".confirm").click(function(){
        return confirm("You Are Sure?");

      });
//modaldetail 
function detailmodal(ID){
	var data = {"ID" : ID};
	$.ajax({
		url     : "includes/modadetail.php",
		method  : "POST",
		data    : data,
		success : function(data){
			$('body').append(data);
			$('#detail-modal').modal('toggle');
		},
		error  : function(){
			alert('Somthing Went Wrong');
		}
	});

} 
//update cart
 function update_cart(mode,edit_id,edit_size){
   var data = {"mode": mode, "edit_id": edit_id, "edit_size": edit_size};
   $.ajax({
   	   url    : '/boutique_shop/admin/parsers/update_cart.php',
   	   method : "post",
   	   data   : data,
   	   success : function(){location.reload();},
   	   error   : function(){alert('Somthing Went Wrong');},
   });
}

// modal cart
function add_to_cart(){
	 $('#span_cart').html('');
	var size = $('#size').val();
	var quantity = $('#quantity').val();
	var avialable = parseInt($('#avialable').val());
	var error  = '';
	var data = $('#cart_form').serialize();
	if(size == '' || quantity == '' || quantity == 0){
		error = '<p class="text-center text-danger">You Must Choose A Size And Quantity</p>';
		$('#span_cart').html(error);
		return;
	}else if(quantity > avialable){
        error = '<p class="text-center text-danger">There are Only '+avialable+' avialable</p>';
		$('#span_cart').html(error);
		return;
    }else{
    	$.ajax({
    		url : "/boutique_shop/admin/parsers/add_cart.php",
    		method : 'post',
    		data : data,
    		success : function(){
    			location.reload();
    		},
    		error   : function(){alert("semthing went Wrong");}
    	});
    }
}
// chooze size

//hide modal


// child category
function child_category(selected){
	if(typeof selected === 'undefined'){
		var selected = '';
	}
	var  parentID = $('#parent').val();
	jQuery.ajax({
		url     : "/boutique_shop/admin/parsers/child_categories.php",
		type    : 'POST',
		data    : {parentID : parentID, selected : selected},
		success : function(data){
			$('#child').html(data);
			},
		error  : function(){
			alert('Somthing Went Wrong');
		},
	});

}
$('select[name="parent"]').change(function(){
	child_category();
});

// modal size and quatity
function updatesize(){
     var sizestring = '';
     for(var i=1; i<= 12; i++){
        if($('#size'+i).val() != ''){
            sizestring += $('#size'+i).val()+':'+$('#qnt'+i).val()+':'+$('#threshold'+i).val()+',';
        }
     }
     $('#Sizes').val(sizestring);
}
