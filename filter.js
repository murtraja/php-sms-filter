$(document).ready(function(){
	//alert("jquery loaded!");
	$('#transporter_param').hide();
	var getCheckedTransporters = function(){
		var t = $("input:checkbox:checked[name='transporters[]']").map(function(){
				return "'"+this.value+"'";
			}).get().join();
		return t;
	}

	$('#filterButton').click(function(){
		//alert("you clicked me");
		//get the values from user
		amount_operator = $('#amount_operator').val();
		amount_param = $('#amount_param').val();
		console.log("amount_param is "+amount_param);
		amount_param2 = $('#amount_param2').val();
		transporter_operator = $("#transporter_operator").val();
		transporter_param = getCheckedTransporters();
		requestObj = $.post("index.php", { 
			'amount_operator':amount_operator,
			'amount_param':amount_param,
			'amount_param2':amount_param2,
			'transporter_operator':transporter_operator,
			'transporter_param':transporter_param
		});
		requestObj.done(function(data){
			var json = null;
			try{
				json = JSON.parse(data);
			}catch(err){
				document.write(data);
				//alert(err);
				return;
			}
			
			//alert(json['query']);
			if(json['result'] == "success")
			{
				// now populate the table with new records!
				$("#recipientTable td").parent().remove();
				var html = "";
				var records = json['data'];
				//alert("total:"+records.length);
				for(index=0; index<records.length; index++)
				{
					html+="<tr>";
					record = records[index];
					for(var key in record){
						html+= "<td>"+record[key]+"</td>";
					}
					html+="</tr>\n";
				}
				$('#recipientTable').append(html);
				$('#query_status').html("Fetched "+records.length+" record(s)");
			}
			else
			{
				alert("there was some error in retrieving the records");
			}
			//alert(json['result']);
		});
	});

	$('#amount_operator').change(function(){
		switch($(this).val())
		{
			case '>':
			case '<':
			case '>=':
			case '<=':
			case '=':
				$('#amount_param').show();
				$('#amount_param2').hide();
			break;
			case 'between':
				$('#amount_param').show();
				$('#amount_param2').show();
			break;
			case 'none':
				$('#amount_param').hide();
				$('#amount_param2').hide();
			break;
		}
	});

	$('#transporter_operator').change(function(){
		switch($(this).val())
		{
			case 'none':
				$('#transporter_param').hide();
			break;
			case 'not in':
			case 'in':
				$('#transporter_param').show();
			break;
		}
	});


});