var uc_library = {
	mass_herd: function(selectables){
		//fetch all selected
		var selected = $('.ui-selected'),
			qstring = '';
	
		$.each(selected, function(k, v){
			//ID attribute of element, which has the ID
			//of the creature
			var attrID = $(v).attr('id');

			//id of creature
			var splitattr = attrID.split('-');

			//append to query string
			qstring += ('&id[]='+splitattr[1]);
		});

		qstring = qstring.substring(1, qstring.length);

		//xmlhttprequest to do herding
		var request = $.ajax({
			url: "herd-script.php",
			type: "POST",
			data: qstring
		});
	},

	drag_drop_move: function(){
		$(document).keydown(function(e){
			switch(e.which){
				case 84: $(document).scrollTop(0); break;
				case 77: window.scrollTo(0, $(document).height() / 2); break;
				case 66: window.scrollTo(0, $('#footer').offset().top); break;
			}
		});

		$("#items").sortable({
			opacity: 0.4,
			connectWith: ".inactive-group",
			cursor: "move",
			scroll: true
		});

		$(".inactive-group").droppable({
			scroll: true,
			//when dropping an item onto a group
			drop: function(e, ui){
				var group_id = $(this).attr("id"),
					item_id = $(ui.draggable).attr("id");
		
				//xmlhttprequest to save item to that group
				var request = $.ajax({
					url: "save_to_group.php",
					type: "POST",
					//we just want the integer at the end
					data: {group: group_id.substring(6, group_id.length),
						   id: item_id.substring(5, item_id.length)}
				});

				//remove item from DOM
				$(ui.draggable).remove();
			}
		});
		
		$('#save_a').click(function(e){
			e.preventDefault();
			
			var data = $('#items').sortable('serialize');
			
			//xmlhttprequest to save sorting
			var request = $.ajax({
				url: "sort_save.php",
				type: "POST",
				data: data
			});
		});
	},
	
	accomps: function(){
		$('#hide').on('click', function(){
			//fetch table rows
			var accs = $('.accomplishment');

			for(i = 0; i < accs.length; ++i){
				var family = accs[i];

				//look for a no image in the html, this tells us it's not complete
				if(family.innerHTML.indexOf('/images/icons2/no.png') == -1){
					$(family).slideToggle(0);
				}
			}
		});
	}
};