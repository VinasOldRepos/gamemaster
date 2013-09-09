$('document').ready(function() {

	// What happens when user selects a monster to be inserted
	$(".monsters_return_row").live("click", function() {
		$id_monster		= $(this).attr('key');
		$id_course		= $("#id_course").val();
		$tot_treasure	= $("tot_treasure").val();
		$("#id_monster").val($id_monster);
		$('.selected_monsters_return_row').attr('class', 'monsters_return_row');
		$(this).attr('class', 'selected_monsters_return_row');
		// get monster info
		$.post('/gamemaster/Robots/loadMonster/', {
			id_monster:		$id_monster,
			id_course:		$id_course
		}, function($return) {
			if ($return) {
				$("#monster_hp").val($return.monster_hp);
				$("#monster_min_dmg").val($return.monster_min_dmg);
				$("#monster_max_dmg").val($return.monster_max_dmg);
				$("#box_question").html($return.question);
				$("#box_answers").html($return.answers);
				$("#monster_treasure").val($return.treasure)
				$("#monster_ds").val($return.int_ds)
				$("#monster_me").val($return.int_me)
				$("#monster_knowledge").val($return.int_knowledge)
			}
		});
		return false;
	});

	$(".radio_answer_opt").live("click", function() {
		$("#id_answer").val($(this).val());
	});

	$(".box_run_round").live("click", function() {
		$action				= false;
		$id_answer			= $("#id_answer").val();
		$id_course			= $("#id_course").val();
		$monster_treasure	= $("#monster_treasure").val();
		$monster_xp			= 1;
		$player_min_dmg		= parseInt($("#player_min_dmg").val());
		$player_max_dmg		= parseInt($("#player_max_dmg").val());
		$player_hp			= parseInt($("#player_hp").val());
		$monster_min_dmg	= parseInt($("#monster_min_dmg").val());
		$monster_max_dmg	= parseInt($("#monster_max_dmg").val());
		$monster_hp			= parseInt($("#monster_hp").val());
		$player_me			= parseInt($("#player_me").val());
		$player_ds			= parseInt($("#player_ds").val());
		$monster_me			= parseInt($("#monster_me").val());
		$monster_ds			= parseInt($("#monster_ds").val());
		$monster_knwoledge	= parseInt($("#monster_knwoledge").val());
		if (($id_answer) && ($id_course) && ($monster_treasure)) {
			$.post('/gamemaster/Robots/checkAnswer/', {
				id_answer:		$id_answer,
			}, function($return) {
				$return			= $return.trim();
				if ($return == 'ok') {
					$player_dmg		= Math.floor(Math.random() * ($player_max_dmg - $player_min_dmg + 1)) + $player_min_dmg;
					$player_dmg		= ($player_dmg + $player_me) - $monster_ds;
					$player_dmg		= ($player_dmg < 1) ? 1 : $player_dmg;
					$monster_hp		= $monster_hp - $player_dmg;
					$("#monster_hp").val($monster_hp);
					if ($monster_hp <= 0) {
						alert("Voce ganhou");
						$action		= 'player_won';
					} else {
						$action		= 'loadQuestion';
					}
				} else {
					$number			= Math.floor(Math.random() * 100) + 1
					if ($number <= $monster_knowledge) {
						$monster_dmg	= Math.floor(Math.random() * ($monster_max_dmg - $monster_min_dmg + 1)) + $monster_min_dmg;
						$monster_dmg	= ($monster_dmg + $monster_me) - $player_ds;
						$monster_dmg	= ($monster_dmg < 1) ? 1 : $monster_dmg;
						$player_hp		= $player_hp - $monster_dmg;
						$("#player_hp").val($player_hp);
						if ($player_hp <= 0) {
							alert("Voce perdeu");
							$action		= 'player_lost';
						} else {
							$action		= 'dimeAnswer';
						}
					}
				}
				if ($action == 'loadQuestion') {
					loadQuestion($id_course);
				} else if ($action == 'dimeAnswer') {
					dimeAnswer($id_answer);
				} else if ($action == 'player_lost') {
					restartCombat();
				} else if ($action == 'player_won') {
					addTreasure($monster_treasure);
					addXp($monster_xp);
					restartCombat();
				}
			});
		}
		return false;
	});

	// What happens when user selects branch
	$("#id_branch").live("change", function() {
		$id_branch	= $(this).val();
		if ($id_branch) {
			$.post('/gamemaster/Maps/loadFields', {
				id_branch:	$id_branch
			}, function($return) {
				if ($return) {
					$("#id_field").html($return);
				} else {
					alert("Sorry,\n\nThere was a problem when loading fields for the selected branch.\n\nError: ");
				}
				return false;
			});
		}
		return false;
	});

	// What happens when user selects a field
	$("#id_field").live("change", function() {
		$id_field	= $(this).val();
		if ($id_field) {
			$.post('/gamemaster/Maps/loadCourses', {
				id_field:	$id_field
			}, function($return) {
				if ($return) {
					$("#id_course").html($return);
				} else {
					alert("Sorry,\n\nThere was a problem when loading courses for the selected field.\n\nError: ");
				}
				return false;
			});
		}
		return false;
	});

});

function loadQuestion($id_course) {
	if ($id_course) {
		$.post('/gamemaster/Robots/loadQuestion/', {
			id_course:	$id_course,
		}, function($return) {
			if ($return) {
				$("#box_question").html($return.question);
				$("#box_answers").html($return.answers);
			}
		});
	}
}

function dimeAnswer($id_answer) {
	if ($id_answer) {
		$("#opt_"+$id_answer).attr("disabled", "disabled");
	}
}

function restartCombat() {
	$("#id_answer").val('');
	$("#id_monster").val('');
	$("#monster_hp").val('');
	$("#monster_min_dmg").val('');
	$("#monster_max_dmg").val('');
	$("#player_hp").val('');
	$("#box_question").html('');
	$("#box_answers").html('');
	$('.selected_monsters_return_row').attr('class', 'monsters_return_row');
}

function addTreasure($treasure) {
	if ($treasure) {
		$tot_treasure	= $("#tot_treasure").val();
		$tot_treasure	= (parseInt($tot_treasure) || 0) + parseInt($treasure);
		$("#tot_treasure").val($tot_treasure);
	}
}

function addXp($monster_xp) {
	if ($monster_xp) {
		$tot_xp	= $("#tot_xp").val();
		$tot_xp	= (parseInt($tot_xp) || 0) + parseInt($monster_xp);
		$("#tot_xp").val($tot_xp);
	}
}