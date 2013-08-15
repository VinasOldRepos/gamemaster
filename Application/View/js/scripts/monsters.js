$('document').ready(function() {

	// What happens when user clicks on "Cancel" while creating
	// a new course
	$(".bt_cancel, .show_form").live("click", function() {
		$(location).attr('href', '/gamemaster/Monsters/insert');
	});

	// What happens when user adds a new monster
	$(".new_monster").live("click", function() {
		document.body.style.cursor	= 'wait';
		$vc_id				= $("#vc_id").val();
		$int_level			= $("#int_level").val();
		$vc_name			= $("#vc_name").val();
		$int_hits_min		= $("#int_hits_min").val();
		$int_hits_max		= $("#int_hits_max").val();
		$int_me				= $("#int_me").val();
		$int_damage_min		= $("#int_damage_min").val();
		$int_damage_max		= $("#int_damage_max").val();
		$int_ds				= $("#int_ds").val();
		$int_knowledge		= $("#int_knowledge").val();
		$int_treasure_min	= $("#int_treasure_min").val();
		$int_treasure_max	= $("#int_treasure_max").val();
		$tx_description		= $("#tx_description").val();
		if (($vc_id) && ($int_level) && ($vc_name) && ($int_hits_min) && ($int_hits_max) && ($int_me) && ($int_damage_min) && ($int_damage_max) && ($int_ds) && ($int_knowledge) && ($int_treasure_min) && ($int_treasure_max) && ($tx_description)) {
			$.post('/gamemaster/Monsters/addMonster/', {
				vc_id:				$vc_id,
				vc_name:			$vc_name,
				int_level:			$int_level,
				int_hits_min:		$int_hits_min,
				int_hits_max:		$int_hits_max,
				int_me:				$int_me,
				int_damage_min:		$int_damage_min,
				int_damage_max:		$int_damage_max,
				int_ds:				$int_ds,
				int_knowledge:		$int_knowledge,
				int_treasure_min:	$int_treasure_min,
				int_treasure_max:	$int_treasure_max,
				tx_description:		$tx_description
			}, function($return) {
				$return			= $return.trim();
				if ($return == 'ok') {
					$("#formInsertCourse").hide();
					$("#message_area").hide();
					$("#main_title").hide();
					contentShowData("#message_area", '<span class="title_01">New Monster succefully created!!<br /><br /><a href="#" class="text_01 show_form"><u>Add another Monster</u></a>');
				} else {
					alert("Sorry,\n\nIt was't possible to add this monster to the database.\n\nError: "+$return);
				}
				document.body.style.cursor = 'default';
				return false;
			});
		} else {
			alert('Please,\n\nMake sure all fields are filled!');
		}
		document.body.style.cursor	= 'default';
		return false;
	});

	// What happens when user click on database result line
	$(".return_row").live("click", function() {
		$key		= $(this).attr('key');
		if ($key) {
			openFancybox('/gamemaster/Monsters/details/'+$key, 800, 500);
		}
		return false;
	});

	// What happens when a user is updated
	$(".updt_monster").live("click", function() {
		document.body.style.cursor = 'wait';
		$id_monster			= $("#id_monster").val();
		$vc_id				= $("#vc_id").val();
		$int_level			= $("#int_level").val();
		$vc_name			= $("#vc_name").val();
		$int_hits_min		= $("#int_hits_min").val();
		$int_hits_max		= $("#int_hits_max").val();
		$int_me				= $("#int_me").val();
		$int_damage_min		= $("#int_damage_min").val();
		$int_damage_max		= $("#int_damage_max").val();
		$int_ds				= $("#int_ds").val();
		$int_knowledge		= $("#int_knowledge").val();
		$int_treasure_min	= $("#int_treasure_min").val();
		$int_treasure_max	= $("#int_treasure_max").val();
		$tx_description		= $("#tx_description").val();
		if (($id_monster) && ($vc_id) && ($int_level) && ($vc_name) && ($int_hits_min) && ($int_hits_max) && ($int_me) && ($int_damage_min) && ($int_damage_max) && ($int_ds) && ($int_knowledge) && ($int_treasure_min) && ($int_treasure_max) && ($tx_description)) {
			$.post('/gamemaster/Monsters/updateMonster/', {
			id_monster:			$id_monster,
			vc_id:				$vc_id,
			int_level:			$int_level,
			vc_name:			$vc_name,
			int_hits_min:		$int_hits_min,
			int_hits_max:		$int_hits_max,
			int_me:				$int_me,
			int_damage_min:		$int_damage_min,
			int_damage_max:		$int_damage_max,
			int_ds:				$int_ds,
			int_knowledge:		$int_knowledge,
			int_treasure_min:	$int_treasure_min,
			int_treasure_max:	$int_treasure_max,
			tx_description:		$tx_description
			}, function($return) {
				$return			= $return.trim();
				if ($return == 'ok') {
					$("#formInsertCourse").hide();
					$("#message_area").hide();
					$("#main_title").hide();
					contentShowData("#message_area", '<span class="title_01">Monster succefully Updated!!</span>');
					parent.$.fancybox.close();
				} else {
					alert("Sorry,\n\nIt was't possible to update this monster on the database.\n\nError: "+$return);
				}
				document.body.style.cursor = 'default';
				return false;
			});
		} else {
			alert("Please\n\nMake sure ALL fields are filled.");
		}
		document.body.style.cursor = 'default';
		return false;
	});

	// What happens when user tries to delete a User
	$(".delete_user").live("click", function () {
		$res					= confirm("ATENTION,\n\nAre you sure you want to delete this monster??");
		if ($res) {
			document.body.style.cursor	= 'wait';
			$id_monster			= $("#id_monster").val();
			if ($id_monster) {
				$.post('/gamemaster/Monsters/deleteMonster/', {
					id_monster:	$id_monster
				}, function($return) {
					if ($return == 'ok') {
						alert("Monster succefully deleted!");
						document.body.style.cursor	= 'default';
						parent.$.fancybox.close();
					} else {
						alert("Monster could NOT be deleted!\n\n"+$return);
						document.body.style.cursor	= 'default';
					}
					return false;
				});
			}
		}
		document.body.style.cursor	= 'default';
	});

});