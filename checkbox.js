$('#getCheckedTransporters').click(function(){
			var t = $("input:checkbox:checked[name='transporters[]']").map(function(){
				return "'"+this.value+"'";
			}).get();
			console.log(t);
			console.log(t.join());
		});
console.log("did anyone call me?");