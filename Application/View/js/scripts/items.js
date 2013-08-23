$('document').ready(function() {

	// What happens when user clicks on "Cancel" while creating
	// a new course
	$(".bt_cancel, .show_form").live("click", function() {
		$(location).attr('href', '/gamemaster/Items/insert');
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
		$int_bonus		= $("#combat_bonus").val();
		$vc_name		= $("#vc_combatname").val();
		if (($id_field) && ($id_type) && ($int_level) && ($int_bonus) && ($vc_name)) {
			$.post('/gamemaster/Items/addCombatItem/', {
				id_field:		$id_field,
				id_type:		$id_type,
				int_level:		$int_level,
				int_bonus:		$int_bonus,
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
				$return	= $return.trim();
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
		alert('combat');
		return false;
	});

	// What happens when user clicks a non combat result row
	$(".noncombat_row").live("click", function() {
		alert('non-combat');
		return false;
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