$('document').ready(function() {

	// What happens when user clicks on "Cancel" while creating
	// a new course
	$(".bt_cancel, .show_form").live("click", function() {
		$(location).attr('href', '/gamemaster/Items/insert');
	});

	// What happens when user clicks on "cancel" at items' details
	$(".bt_cancel_detail").live("click", function() {
		parent.$.fancybox.close();
	});

	// What happens when user selects item type on insert
	$("#item_type").live("change", function() {
		$item_type	= $(this).val();
		clearAllFormInfo();
		if ($item_type == 1) {
			$("#formNonCombat").hide();
			contentShow("#formCombat");
		} else if ($item_type == 2) {
			$("#formCombat").hide();
			contentShow("#formNonCombat");
		} else {
			contentHide("#formCombat");
			contentHide("#formNonCombat");
		}
		return false;
	});

	// What happens when user selects branch (new item)
	$("#id_branch").live("change", function() {
		$id_branch	= $(this).val();
		if ($id_branch) {
			$.post('/gamemaster/Maps/loadFields', {
				id_branch:	$id_branch
			}, function($return) {
				if ($return) {
					$("#id_field").html($return);
				} else {
					alert("Sorry,\n\nThere was a problem when loading the field for the selected branch.\n\nError: ");
				}
				return false;
			});
		}
		return false;
	});

	// What happens when user tries to save a combat item
	$(".new_combatitem").live("click", function() {
		$id_field		= $("#id_field").val();
		$id_type		= $("#combat_type").val();
		$int_level		= $("#combat_int_level").val();
		$me_min			= $("#me_min").val();
		$me_max			= $("#me_max").val();
		$magic_me		= $("#magic_me").val();
		$ds				= $("#ds").val();
		$magic_ds		= $("#magic_ds").val();
		$magic_ds		= $("#magic_ds").val();
		$vc_wearable	= $("#vc_wearable").val();
		$time			= $("#time").val();
		if (($id_type) && ($int_level) && ($vc_name)) {
			$.post('/gamemaster/Items/addCombatItem/', {
				id_field:		$id_field,
				id_type:		$id_type,
				int_level:		$int_level,
				me_min:			$me_min,
				me_max:			$me_max,
				magic_me:		$magic_me,
				ds:				$ds,
				magic_ds:		$magic_ds,
				vc_wearable:	$vc_wearable,
				time:			$time,
				vc_name:		$vc_name
			}, function($return) {
				$return	= $return.trim();
				if ($return == 'ok') {
					$(location).attr('href', '/gamemaster/Items/Inserted');
				} else {
					alert("Hum,\n\nIt seem I wasn't able to save yout Item.\nPerhaps you should try it later?\n\nError: "+$return);
				}
				return false;
			});
		} else {
			alert("Please,\n\nBy this point you should have realized that all fields must be filled before you do that, for Christ's sake!");
		}
		return false;
	});

	// What happens when user tries to save a combat item
	$(".new_noncombatitem").live("click", function() {
		$id_type			= $("#noncombat_type").val();
		$int_level			= $("#noncombat_int_level").val();
		$int_bonus_start	= $("#noncombat_bonus_min").val();
		$int_bonus_end		= $("#noncombat_bonus_max").val();
		$vc_name			= $("#vc_noncombatname").val();
		if (($id_type) && ($int_level) && ($int_bonus_start) && ($int_bonus_end) && ($vc_name)) {
			$.post('/gamemaster/Items/addNonCombatItem/', {
				id_type:			$id_type,
				int_level:			$int_level,
				int_bonus_start:	$int_bonus_start,
				int_bonus_end:		$int_bonus_end,
				vc_name:			$vc_name
			}, function($return) {
				$return				= $return.trim();
				if ($return == 'ok') {
					$(location).attr('href', '/gamemaster/Items/Inserted');
				} else {
					alert("Well,\n\nApparently my computational system wasn't able to compute the data you've tried to insert.\n\nBut if you're a wanna-be-a-hacker smart ass, here a hint of what have happened: "+$return);
				}
				return false;
			});
		} else {
			alert("Please,\n\nstop joking around and fill all form fields.\n\n(That is, if ever want to get that saved, of course...)");
		}
		return false;
	});

	// What happens when user clicks a combat result row
	$(".combat_row").live("click", function() {
		$key		= $(this).attr('key');
		if ($key) {
			openFancybox('/gamemaster/Items/combatDetails/'+$key, 800, 500);
		}
		return false;
	});

	// What happens when user clicks a non combat result row
	$(".noncombat_row").live("click", function() {
		$key		= $(this).attr('key');
		if ($key) {
			openFancybox('/gamemaster/Items/nonCombatDetails/'+$key, 800, 500);
		}
		return false;
	});

	// What happens when user updates a combat item
	$(".updt_combatItem").live("click", function() {
		$id				= $("#id_item").val();
		$id_field		= $("#id_field").val();
		$int_level		= $("#int_level").val();
		$me_min			= $("#me_min").val();
		$me_max			= $("#me_max").val();
		$magic_me		= $("#magic_me").val();
		$ds				= $("#ds").val();
		$magic_ds		= $("#magic_ds").val();
		$id_type		= $("#id_type").val();
		$vc_wearable	= $("#vc_wearable").val();
		$vc_name		= $("#vc_name").val();
		$time			= $("#time").val();
		if (($id) && ($id_field) && ($int_level) /*&& ($int_bonus)*/ && ($id_type) && ($vc_name)) {
			$.post('/gamemaster/Items/updtCombatItem/', {
				id:				$id,
				id_field:		$id_field,
				int_level:		$int_level,
				me_min:			$me_min,
				me_max:			$me_max,
				magic_me:		$magic_me,
				ds:				$ds,
				magic_ds:		$magic_ds,
				id_type:		$id_type,
				time:			$time,
				vc_wearable:	$vc_wearable,
				vc_name:		$vc_name
			}, function($return) {
				$return	= $return.trim();
				if ($return == 'ok') {
					alert("Item saved!");
				} else {
					alert("Well,\n\nApparently my computational system wasn't able to compute the data you've tried to insert.\n\nBut if you're a wanna-be-a-hacker smart ass, here's a hint of what have happened:\n\n"+$return);
				}
				return false;
			});
		}
	});

	// What happens when user updates a combat item
	$(".updt_nonCombatItem").live("click", function() {
		$id					= $("#id_item").val();
		$int_level			= $("#int_level").val();
		$int_bonus_start	= $("#int_bonus_start").val();
		$int_bonus_end		= $("#int_bonus_end").val();
		$id_type			= $("#id_type").val();
		$vc_name			= $("#vc_name").val();
		if (($id) && ($int_level) && ($int_bonus_start) && ($int_bonus_end) && ($id_type) && ($vc_name)) {
			$.post('/gamemaster/Items/updtNonCombatItem/', {
				id:					$id,
				int_level:			$int_level,
				int_bonus_start:	$int_bonus_start,
				int_bonus_end:		$int_bonus_end,
				id_type:			$id_type,
				vc_name:			$vc_name
			}, function($return) {
				$return	= $return.trim();
				if ($return == 'ok') {
					alert("Item saved!");
				} else {
					alert("Well,\n\nApparently my computational system wasn't able to compute the data you've tried to insert.\n\nBut if you're a wanna-be-a-hacker smart ass, here a hint of what have happened:\n\n"+$return);
				}
				return false;
			});
		}
	});

	// What happens when user deletes a combat item
	$(".delete_combatItem").live("click", function() {
		$id				= $("#id_item").val();
		if ($id) {
			$.post('/gamemaster/Items/deleteCombatItem/', {
				id:			$id
			}, function($return) {
				$return	= $return.trim();
				if ($return == 'ok') {
					parent.$.fancybox.close();
				} else {
					alert("Please,\n\nDo NOT press this button again.\n\nHere's the problem you've caused:\n\n"+$return);
				}
				return false;
			});
		}
	});

	// What happens when user deletes a non-combat item
	$(".delete_nonCombatItem").live("click", function() {
		$id				= $("#id_item").val();
		if ($id) {
			$.post('/gamemaster/Items/deleteNonCombatItem/', {
				id:		$id
			}, function($return) {
				$return	= $return.trim();
				if ($return == 'ok') {
					parent.$.fancybox.close();
				} else {
					alert("Please,\n\nDo NOT press this button again.\n\nHere's the problem you've caused:\n\n"+$return);
				}
				return false;
			});
		}
	});

});

// Clears all form info
function clearAllFormInfo() {
	$("#combat_int_level").val('');
	$("#noncombat_int_level").val('');
	$("#combat_bonus").val('');
	$("#noncombat_bonus_min").val('');
	$("#noncombat_bonus_max").val('');
	$("#vc_combatname").val('');
	$("#vc_noncombatname").val('');
	$("#id_branch").val('');
	$("#id_field").html('');
}