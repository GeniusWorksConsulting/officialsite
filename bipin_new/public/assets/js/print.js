
	
	$(document).on('click','.print_class', function(){
		
		uuid = $(this).data('uuid');
		
		$.ajax({
			url: "print.php",
			data:{uuid:uuid}, 
			method:'post',
			success: function(result){
	        	$("#print_content").html(result);
	        	$('#myModal').modal('show');
	    	}
	    });
	});


	var timeoutHnd; 
	var flAuto = true;
	function doSearch(ev){
	 	if(!flAuto)return; 
	 	if(timeoutHnd) clearTimeout(timeoutHnd);
	  	timeoutHnd = setTimeout(gridReload,500);
	}

	invoiceNo = 10;
	searchData = {
			invoiceNo: invoiceNo
	};
	function gridReload(){
	 	searchData = {
	 		invoiceNo: jQuery("#invoiceNoSearch").val(),
	 		invoiceClient: jQuery("#invoiceClientSearch").val(),
	 		invoiceStartDate: jQuery("#invoiceStartDateSearch").val(),
	 		invoiceEndDate: jQuery("#invoiceEndDateSearch").val()
	 	};
	    var gridParam = { url: "server.php", datatype: "json", postData: searchData, page: 1 };                        
	    jQuery("#list2").jqGrid('setGridParam', gridParam).trigger("reloadGrid");
	}	





$(document).ready(function(){
	
	$("#emailForm").validate({
		submitHandler : function(form) {
			$('#email_btn').attr('disabled','disabled');
			$('#email_btn').button('loading');
			
			return false;
		},
		rules : {
			invoiceEmail : {
				required : true,
				email: true
			}
		},
		messages : {
			invoiceEmail : {
				required : "Please enter email"
			}
		},
		errorPlacement : function(error, element) {
			$(element).closest('div').find('.help-block').html(error.html());
		},
		highlight : function(element) {
			$(element).closest('div').removeClass('has-success').addClass('has-error');
		},
		unhighlight: function(element, errorClass, validClass) {
			 $(element).closest('div').removeClass('has-error').addClass('has-success');
			 $(element).closest('div').find('.help-block').html('');
		}
	});
	
	country = $('#country_hidden').val();
	if(country !=''){
		$("#country option").filter(function() {
		    //may want to use $.trim in here
		    return $(this).text() == country; 
		}).attr('selected', true);
	}
	
	
	$(document).on('click','.printEmail', function(){
		uuid = $(this).data('uuid');
		$('#email_btn').button('reset');
		$('#emailModal').modal('show');
		$('#invoiceUuid').val( uuid );
	});
	
	
	
	$(document).on('click', '#email_btn',function(){
		
		if($('#emailForm').valid()){
			$(this).button('loading');
			email = $('#invoiceEmail').val();
			uuid  = $('#invoiceUuid').val();
			$.ajax({
				url : 'ajax.php',
				dataType: "json",
				method: 'post',
				data: {
				   email: email,
				   uuid : uuid,
				   type: 'sendEmail'
				},
				success: function( data ) {
					 $('#email_btn').button('reset');
					 $('#emailModal').modal('hide');
					 $('#successEmail').modal('show');
					 
				}
			});
		}
		
	});
});


function printInvoice( id ) {
	$('#printBtn').button('loading');
	printContent = $('#'+id).html();
	$('#PrintIframe').contents().find('html').html(printContent);
	var ua = window.navigator.userAgent;
    var msie = ua.indexOf ("MSIE");
    var iframe = document.getElementById('PrintIframe');
    if (msie > 0) {
    	$('#printBtn').button('reset');
        iframe.contentWindow.document.execCommand('print', false, null);
    } else {
        iframe.contentWindow.print();
        $('#printBtn').button('reset');
    }
    
}

$('#invoiceClientSearch').autocomplete({
	source: function( request, response ) {
		$.ajax({
			url : 'ajax.php',
			dataType: "json",
			method: 'post',
			data: {
				name_startsWith: request.term,
				type: 'customerName'
			},
			success: function( data ) {
				response( $.map( data, function( item ) {
					var code = item.split("|");
						return {
							label: code[1],
							value: code[1],
							data : item
						}
					}));
				}
			});
	},
	autoFocus: true,	      	
	minLength: 1,
	select: function( event, ui ) {
		var names = ui.item.data.split("|");
		$(this).val(names[1]);
	}		      	
});


//Delete invoices

//Delete product script
/*$(document).on('click', '.delete_invoice', function(e){
	var url = $.trim( $(this).data('href') );
	if( url == ''){
		alert('Invalid Invoice');
		return false;
	}
	var r = confirm("Are you sure want to delete this product");
	if (r == true){
		location.href = url;
	}else return false;
});*/



$(document).on('click', '.delete_invoice', function(e){
	var uuid = $.trim( $(this).data('uuid') );
	if( uuid == ''){
		alert('Invalid Invoice');
		return false;
	}
	var r = confirm("Are you sure want to delete this Invoice");
	if (r == true){
		$.ajax({
			url : 'ajax.php',
			dataType: 'json',
			method: 'post',
			data: {
				uuid: uuid,
				type: 'delete_invoice'
			},
			success: function(data){
				if (typeof data.success != 'undefined' && data.success){
					message('success', INVOICE_DELETE_SUCCESS);
					jQuery("#list2").trigger("reloadGrid");
				}else{
					message('fail', INVOICE_DELETE_FAIL);
				}
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) { 
		        alert("Status: " + textStatus + " Error: "+ errorThrown);
		    }  
			
		});
	}
	else return false;
});