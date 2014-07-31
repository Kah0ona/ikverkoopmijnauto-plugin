jQuery(document).ready(function($){
	var fetchedData;
	function populateBrandSelect(obj){
		var elt = $('select.brand');
		for(var i = 0; i < obj.length; i++){
			elt.append(
			"<option value='"+obj[i].CarBrand_id+"'>"+
				obj[i].brand+
			"</option>");	
		}
	}

	function populateModelSelect(){
		var elt = $('select.modelName');
		elt.html('');
		//lookup selected brand
		var brandId = parseInt($('select.brand').val());
		for(var i = 0; i < fetchedData.length; i++){
			var cb = fetchedData[i];
			if(cb.CarBrand_id == brandId){
				//found!
				//populate the form with all the models of this brand
				if(cb.CarModel != null) {
					for(var j = 0; j < cb.CarModel.length; j++){
						var m = cb.CarModel[j];
						elt.append("<option value='"+m.CarModel_id+"'>"+m.modelName+"</option");
					}
				}
			}
		}
	}

	//make ajax call to fetch brands and models	
	$.ajax({
		url: serviceUrlBackend,
		jsonp: 'callback',
		dataType : 'jsonp',
		data: {},
		success : function(jsonobj){
			fetchedData = jsonobj;
			populateBrandSelect(jsonobj);
			populateModelSelect();
		}
	});

	$('select.brand').change(function(){
		populateModelSelect();
	});

});

//validation


var submitType = "invoice";
jQuery(document).ready(function($){
	var reqMsg = "&uarr; Dit veld is verplicht.";
	var emailMsg = "Vul een geldig e-mailadres in.";
		
	var submitOptions = {
		beforeSubmit : function(arr, form, options){
										
		  	//hide the form to prevent another click
		  	var f = $('#order-form');
		
			showSendingMessage(f);
			return true;	
			
		},
		data : { 
			"action" : "place_order",
			/*"cart_nonce" : SubmitFormUrl.cart_nonce,
			orderType : submitType*/
		},
		success : function(data, textStatus, jqXHR) {
			if(data.error != null){
				$('#order-form').replaceWith('<div class="alert alert-error span12"><strong>Fout:</strong> Er ging iets mis met het versturen van de gegevens: '+data.error+'</div>');
			} else {
				window.location.href = '/success';
			} 
		}
	};
	 
	var validationOptions = {
			rules : {
				gender : { 
					required: true
				},
				firstname : {
					required: true
				},
				surname : {
					required: true
				},
				street : {
					required: true
				},
				zipcode : {
					required: true
				},
				city : {
					required: true
				},
				country : {
					required: true
				},
				email : {
					required: true,
					email: true
				},
				phone : {
					required: true
				},
				accept_terms : {
					required: true
				},
				modelName : {
					required: true
				},
				km : {
					required: true
				},
				year : {
					required: true
				}
			},
		
			messages : {
				gender : {
				   required: reqMsg
				},
				firstname: {
				   required: reqMsg
			    },
				surname: {
				   required: reqMsg
			    },
				street: {
				   required: reqMsg
			    },
				number: {
				   required: reqMsg
			    },
				postcode: {
				   required: reqMsg
			    },
				city: {
				   required: reqMsg
			    },
				email: {
				   required: reqMsg,
				   email: emailMsg
			    },
				phone: {
				   required: reqMsg
			    },
			    modelName: {
				    required: reqMsg
			    },
			    km: { 
				    required: reqMsg
			    },
			    year: {
				    required: reqMsg
			    }
			    
			},
			debug: true,
		    errorPlacement: function(error, element) {
			   error.insertAfter(element);
			}
			,
			submitHandler : function(form){
				$('#order-form').ajaxSubmit(submitOptions);	//does some extra validation.		
				
			},
			invalidHandler: function(form, validator) {
				//alert('invalid');
			}
	}; 

	
    $('#order-form').validate(validationOptions);
	
});


function showSendingMessage(f){
	f.replaceWith('<div class="alert alert-info span12"><strong>Even geduld:</strong> bezig met versturen van de gegevens...</div>');
}

function showSuccesMessage(ret){
	//just redirect to the success page
	window.location.href = "/success";
}


