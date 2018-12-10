//Auto Complete Product Name

$('#product').autocomplete({
	source: function( request, response ) {
		$.ajax({
			url : 'ajax.php',
			dataType: "json",
			method: 'post',
			data: {
			   name_startsWith: request.term,
			   type: 'product',
			   row_num : 1
			},
			 success: function( data ) {
				 response( $.map( data, function( item ) {
				 	var code = item.split("|");
					return {
						label: code[0],
						value: code[0],
						data : item
					}
				}));
			}
		});
	},
	autoFocus: true,	      	
	minLength: 0,
	select: function( event, ui ) {
		var names = ui.item.data.split("|");						
		
	}		      	
});

//Auto Complete Port Of Loading 
$('#loading').autocomplete({

	source: function( request, response ) {
		$.ajax({
			url : 'ajax.php',
			dataType: "json",
			method: 'post',
			data: {
			   name_startsWith: request.term,
			   type: 'port_location',
			   row_num : 1
			},
			 success: function( data ) {
				 response( $.map( data, function( item ) {
				 	var code = item.split("|");
					return {
						label: code[0],
						value: code[0],
						data : item
					}
				}));
			}
		});
	},
	autoFocus: true,	      	
	minLength: 0,
	select: function( event, ui ) {
		var names = ui.item.data.split("|");						
		
	}		      	
});

//Auto Complete Port Of Discharge 
$('#port_discharge').autocomplete({

	source: function( request, response ) {
		$.ajax({
			url : 'ajax.php',
			dataType: "json",
			method: 'post',
			data: {
			   name_startsWith: request.term,
			   type: 'port_location',
			   row_num : 1
			},
			 success: function( data ) {
				 response( $.map( data, function( item ) {
				 	var code = item.split("|");
					return {
						label: code[0],
						value: code[0],
						data : item
					}
				}));
			}
		});
	},
	autoFocus: true,	      	
	minLength: 0,
	select: function( event, ui ) {
		var names = ui.item.data.split("|");						
		
	}		      	
});

//Auto Complete Loading Transport
$('#loading_transport').autocomplete({

	source: function( request, response ) {
		$.ajax({
			url : 'ajax.php',
			dataType: "json",
			method: 'post',
			data: {
			   name_startsWith: request.term,
			   type: 'transport',
			   row_num : 1
			},
			 success: function( data ) {
				 response( $.map( data, function( item ) {
				 	var code = item.split("|");
					return {
						label: code[0],
						value: code[0],
						data : item
					}
				}));
			}
		});
	},
	autoFocus: true,	      	
	minLength: 0,
	select: function( event, ui ) {
		var names = ui.item.data.split("|");						
		
	}		      	
});

//Auto Complete Discharge Transport
$('#discharge_transport').autocomplete({

	source: function( request, response ) {
		$.ajax({
			url : 'ajax.php',
			dataType: "json",
			method: 'post',
			data: {
			   name_startsWith: request.term,
			   type: 'transport',
			   row_num : 1
			},
			 success: function( data ) {
				 response( $.map( data, function( item ) {
				 	var code = item.split("|");
					return {
						label: code[0],
						value: code[0],
						data : item
					}
				}));
			}
		});
	},
	autoFocus: true,	      	
	minLength: 0,
	select: function( event, ui ) {
		var names = ui.item.data.split("|");						
		
	}		      	
});

//Auto Complete Loading Vehicle

$('#loading_vehicle_no').autocomplete({

	source: function( request, response ) {
		$.ajax({
			url : 'ajax.php',
			dataType: "json",
			method: 'post',
			data: {
			   name_startsWith: request.term,
			   type: 'vehicle',
			   row_num : 1
			},
			 success: function( data ) {
				 response( $.map( data, function( item ) {
				 	var code = item.split("|");
					return {
						label: code[0],
						value: code[0],
						data : item
					}
				}));
			}
		});
	},
	autoFocus: true,	      	
	minLength: 0,
	select: function( event, ui ) {
		var names = ui.item.data.split("|");						
		
	}		      	
});

//Auto Complete Loading Vehicle

$('#discharge_vehicle_no').autocomplete({

	source: function( request, response ) {
		$.ajax({
			url : 'ajax.php',
			dataType: "json",
			method: 'post',
			data: {
			   name_startsWith: request.term,
			   type: 'vehicle',
			   row_num : 1
			},
			 success: function( data ) {
				 response( $.map( data, function( item ) {
				 	var code = item.split("|");
					return {
						label: code[0],
						value: code[0],
						data : item
					}
				}));
			}
		});
	},
	autoFocus: true,	      	
	minLength: 0,
	select: function( event, ui ) {
		var names = ui.item.data.split("|");						
		
	}		      	
});

//Auto Complete Pickup Vehicle

$('#pickup_location').autocomplete({

	source: function( request, response ) {
		$.ajax({
			url : 'ajax.php',
			dataType: "json",
			method: 'post',
			data: {
			   name_startsWith: request.term,
			   type: 'location',
			   row_num : 1
			},
			 success: function( data ) {
				 response( $.map( data, function( item ) {
				 	var code = item.split("|");
					return {
						label: code[0],
						value: code[0],
						data : item
					}
				}));
			}
		});
	},
	autoFocus: true,	      	
	minLength: 0,
	select: function( event, ui ) {
		var names = ui.item.data.split("|");						
		
	}		      	
});

$('#delivery_location').autocomplete({

	source: function( request, response ) {
		$.ajax({
			url : 'ajax.php',
			dataType: "json",
			method: 'post',
			data: {
			   name_startsWith: request.term,
			   type: 'location',
			   row_num : 1
			},
			 success: function( data ) {
				 response( $.map( data, function( item ) {
				 	var code = item.split("|");
					return {
						label: code[0],
						value: code[0],
						data : item
					}
				}));
			}
		});
	},
	autoFocus: true,	      	
	minLength: 0,
	select: function( event, ui ) {
		var names = ui.item.data.split("|");						
		
	}		      	
});

//Auto Complete Consignee

$('#consignee_name').autocomplete({

	source: function( request, response ) {
		$.ajax({
			url : 'ajax.php',
			dataType: "json",
			method: 'post',
			data: {
			   name_startsWith: request.term,
			   type: 'consignee',
			   row_num : 1
			},
			 success: function( data ) {
				 response( $.map( data, function( item ) {
				 	var code = item.split("|");
					return {
						label: code[0],
						value: code[0],
						
						data : item
					}
				}));
			}
		});
	},
	autoFocus: true,	      	
	minLength: 0,
	select: function( event, ui ) {
		var names = ui.item.data.split("|");						
		$('#consignee_name').val(names[0]);
		$('#consign_id').val(names[1]);
		$('#consignee_address').val(names[2]);
	}		      	
});


//Auto Complete Consignor

$('#consignor_name').autocomplete({

	source: function( request, response ) {
		$.ajax({
			url : 'ajax.php',
			dataType: "json",
			method: 'post',
			data: {
			   name_startsWith: request.term,
			   type: 'consignor',
			   row_num : 1
			},
			 success: function( data ) {
				 response( $.map( data, function( item ) {
				 	var code = item.split("|");
					return {
						label: code[0],
						value: code[0],
						
						data : item
					}
				}));
			}
		});
	},
	autoFocus: true,	      	
	minLength: 0,
	select: function( event, ui ) {
		var names = ui.item.data.split("|");						
		$('#consignor_name').val(names[0]);
		$('#consignor_address').val(names[1]);
	}		      	
});


//Auto Complete Vessel

$('#vessel_name').autocomplete({

	source: function( request, response ) {
		$.ajax({
			url : 'ajax.php',
			dataType: "json",
			method: 'post',
			data: {
			   name_startsWith: request.term,
			   type: 'vessel',
			   row_num : 1
			},
			 success: function( data ) {
				 response( $.map( data, function( item ) {
				 	var code = item.split("|");
					return {
						label: code[0],
						value: code[0],
						
						data : item
					}
				}));
			}
		});
	},
	autoFocus: true,	      	
	minLength: 0,
	select: function( event, ui ) {
		var names = ui.item.data.split("|");						
		
	}		      	
});


//Auto Complete Agent

$('#agent_name').autocomplete({

	source: function( request, response ) {
		$.ajax({
			url : 'ajax.php',
			dataType: "json",
			method: 'post',
			data: {
			   name_startsWith: request.term,
			   type: 'agent',
			   row_num : 1
			},
			 success: function( data ) {
				 response( $.map( data, function( item ) {
				 	var code = item.split("|");
					return {
						label: code[0],
						value: code[0],
						
						data : item
					}
				}));
			}
		});
	},
	autoFocus: true,	      	
	minLength: 0,
	select: function( event, ui ) {
		var names = ui.item.data.split("|");						
		$('#agent').val(names[0]);
		
	}		      	
});

//Auto Complete Invoice CN No

$('#cn_no').autocomplete({

	source: function( request, response ) {
		$.ajax({
			url : 'ajax.php',
			dataType: "json",
			method: 'post',
			data: {
			   name_startsWith: request.term,
			   type: 'cnno',
			   row_num : 1
			},
			 success: function( data ) {
				 response( $.map( data, function( item ) {
				 	var code = item.split("|");
					return {
						label: code[0],
						value: code[0],
						
						data : item
					}
				}));
			}
		});
	},
	autoFocus: true,	      	
	minLength: 0,
	select: function( event, ui ) {
		var names = ui.item.data.split("|");						
		$('#cn_no').val(names[0]);
		$('#container_no').val(names[1]);
		$('#consignee').val(names[2]);
		$('#consignee_id').val(names[3]);
		$('#consignee_address').val(names[4]);
		$('#customer_invoiceno').val(names[5]);
		$('#consignor').val(names[6]);
		$('#consignor_address').val(names[7]);
		$('#pickup_location').val(names[8]);
		$('#delivery_location').val(names[9]);
		$('#port_loading').val(names[10]);
		$('#product').val(names[11]);
	}		      	
});

//autocomplete script
$(document).on('focus','.autocomplete_txt',function(){
	
    

    
	type = $(this).data('type');


	id_arr = $(this).attr('id');
	id = id_arr.split("_");
	element_id = id[id.length-1];

	if(type =='productCode' ){
		
		autoTypeNo=0;
		$('#add_icon_'+element_id).removeClass('hide');
	}
	if(type =='productName' )autoTypeNo=1;

	$(this).autocomplete({
		source: function( request, response ) {
			$.ajax({
				url : 'ajax.php',
				dataType: "json",
				method: 'post',
				data: {
				   name_startsWith: request.term,
				   type: type
				},
				success: function( data ) {
					if(!data.length && readonly != 'readonly'){

						$('#product_code_modal').val( $('#itemNo_'+element_id).val() );
						$('#product_name_modal').val( $('#itemName_'+element_id).val());

						$('#current_element_id').val(element_id);

						$('#add_product_form').find('.form-group').removeClass('animated fadeIn').addClass('animated fadeIn');
						$('#addNewProduct').modal('show');

						/*var result = [
			              {
			            	  label: 'No record found',
			            	  value: ''
			              }
			            ];
			            response(result);*/
					}else{
						 response( $.map( data, function( item ) {
						 	var code = item.split("|");
							return {
								label: code[autoTypeNo],
								value: code[autoTypeNo],
								data : item
							}
						}));
					}
				}
			});
		},
		autoFocus: true,
		minLength: 0,
		select: function( event, ui ) {
			if( typeof ui.item.data !== "undefined" ){
				var names = ui.item.data.split("|");

				$('#product'+element_id).val(names[0]);
				

			}else{
				return false;
			}
			calculateTotal();
		}
	});
});



//autocomplete script
$(document).on('focus','.autocomplete_txt',function(){
    
    

    
	type = $(this).data('type');


	id_arr = $(this).attr('id');
	id = id_arr.split("_");
	element_id = id[id.length-1];

	if(type =='HSN_Code' ){
		
		autoTypeNo=0;
		$('#add_icon_'+element_id).removeClass('hide');
	}
	if(type =='hsncode' )autoTypeNo=1;
	

	$(this).autocomplete({
		source: function( request, response ) {
			$.ajax({
				url : 'ajax.php',
				dataType: "json",
				method: 'post',
				data: {
				   name_startsWith: request.term,
				   type: 'hsn',
				   row_num : 1
				},
				success: function( data ) {
					if(!data.length && readonly != 'readonly'){

						$('#product_code_modal').val( $('#itemNo_'+element_id).val() );
						$('#product_name_modal').val( $('#itemName_'+element_id).val());

						$('#current_element_id').val(element_id);

						$('#add_product_form').find('.form-group').removeClass('animated fadeIn').addClass('animated fadeIn');
						$('#addNewProduct').modal('show');

						/*var result = [
			              {
			            	  label: 'No record found',
			            	  value: ''
			              }
			            ];
			            response(result);*/
					}else{
						 response( $.map( data, function( item ) {
						 	var code = item.split("|");
							return {
								label: code[autoTypeNo],
								value: code[autoTypeNo],
								data : item
							}
						}));
					}
				}
			});
		},
		autoFocus: true,
		minLength: 0,
		select: function( event, ui ) {
			if( typeof ui.item.data !== "undefined" ){
				var names = ui.item.data.split("|");

				
				$('#hsncode_'+element_id).val(names[0]);
				$('#itemName_'+element_id).val(names[1]);

			}else{
				return false;
			}
			calculateTotal();
		}
	});
});

