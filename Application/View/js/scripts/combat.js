$('document').ready(function() {

	// What happens when user selects a monster to be inserted
	$(".monsters_return_row").live("click", function() {
		$id_monster		= $(this).attr('key');
		$id_course		= $("#id_course").val();
		$tot_treasure	= $("tot_treasure").val();
		if (($id_monster) && ($id_course)) {
			$("#id_monster").val($id_monster);
			$('.selected_monsters_return_row').attr('class', 'monsters_return_row');
			$(this).attr('class', 'selected_monsters_return_row');
			// get monster info
			$.post('/gamemaster/Robots/loadMonster/', {
				id_monster:		$id_monster,
				id_course:		$id_course
			}, function($return) {
				if ($return) {
					$("#turn").val('');
					$("#monster_hp").val($return.monster_hp);
					$("#monster_min_dmg").val($return.monster_min_dmg);
					$("#monster_max_dmg").val($return.monster_max_dmg);
					$("#monster_treasure").val($return.treasure);
					$("#monster_ds").val($return.int_ds);
					$("#monster_me").val($return.int_me);
					$("#monster_knowledge").val($return.int_knowledge);
					//$("#turn").val('player');
					loadQuestion($id_course);
				}
			});
		}
		return false;
	});

	$(".box_run_round").live("click", function() {
		$action				= false;
		$id_answer			= $('input[name=answer_opt]:checked').val()
		$correct			= $("#correct").val();
		$id_course			= $("#id_course").val();
		$monster_treasure	= $("#monster_treasure").val();
		$turn				= $("#turn").val();
		$monster_xp			= 1;
		$answer				= $('#opt_'+$correct).attr('caption');
		clearInterval(window.counter);
		if (($id_course) && ($turn)) {
			//contentHide("#box_rightanswer");
			// If it's player's turn
			if ($turn == 'player') {
				// if answer is correct
				$("#id_answer").val($id_answer);
				if ($correct == $id_answer) {
					$action	= playersTurn();
				// If the Player got it wrong
				} else {
					contentHide("#box_run_round");
					contentShowData("#box_rightanswer", 'You were wrong.<br />Right answer was: "'+$answer+'"<br /><br /> Click here to proceed.');
				}
			// If it's monster's turn
			} else {
				$action		= monstersTurn();
			}
			performAction($action);
		}
		return false;
	});
	
	$(".box_rightanswer").live("click", function() {
		$id_course	= $("#id_course").val();
		contentHide("#box_rightanswer");
		loadQuestion($id_course);
		contentShow("#box_run_round");
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

	// What happens when user selects a course
	$("#id_course").live("change", function() {
		$id_course	= $(this).val();
		if ($id_course) {
			$.post('/gamemaster/Robots/loadMonsters', {
				id_course:	$id_course
			}, function($return) {
				if ($return) {
					$("#monster_list").html($return);
				} else {
					alert("Sorry,\n\nThere was a problem when loading monsters for the selected course.\n\nError: ");
				}
				return false;
			});
		}
		return false;
	});

	/*/
	$(".radio_answer_opt").live("click", function() {
		alert($("#correct").val());
		return false;
	});
	/*/

});

function loadQuestion($id_course) {
	$turn		= $("#turn").val();
	$action		= false;
	if ($id_course) {
		$turn	= ($turn == 'player') ? 'monster' : 'player';
		$("#turn").val($turn);
		$.post('/gamemaster/Robots/loadQuestion/', {
			id_course:	$id_course,
		}, function($return) {
			if ($return) {
				$("#id_answer").val('');
				contentHide("#box_rightanswer");
				$("#box_question").html($return.question);
				$("#box_answers").html($return.answers);
				$("#time_limit").val($return.time_limit);
				$("#id_question").val($return.id_question);
				$("#correct").val($return.correct);
				$("#box_counter").html();
				$id_question		= $return.id_question;
				if ($turn == 'player') {
					$time_limit		= parseInt($return.time_limit) * 1000;
					clearInterval(window.counter);
					window.counter	= displayCounter($time_limit);
				} else {
					$("#box_counter").html("Monster's turn");
				}
			}
		});
	}
}

function playersTurn() {
	$player_min_dmg		= parseInt($("#player_min_dmg").val());
	$player_max_dmg		= parseInt($("#player_max_dmg").val());
	$player_hp			= parseInt($("#player_hp").val());
	$player_me			= parseInt($("#player_me").val());
	$player_ds			= parseInt($("#player_ds").val());
	$monster_hp			= parseInt($("#monster_hp").val());
	$monster_ds			= parseInt($("#monster_ds").val());
	$return				= false;
	$player_dmg			= Math.floor(Math.random() * ($player_max_dmg - $player_min_dmg + 1)) + $player_min_dmg;
	$player_dmg			= ($player_dmg + $player_me) - $monster_ds;
	$player_dmg			= ($player_dmg < 1) ? 1 : $player_dmg;
	$return				= monsterDamage($player_dmg);
	return $return;
}

function monstersTurn() {
	$player_hp			= parseInt($("#player_hp").val());
	$monster_min_dmg	= parseInt($("#monster_min_dmg").val());
	$monster_max_dmg	= parseInt($("#monster_max_dmg").val());
	$monster_hp			= parseInt($("#monster_hp").val());
	$monster_me			= parseInt($("#monster_me").val());
	$monster_ds			= parseInt($("#monster_ds").val());
	$monster_knowledge	= parseInt($("#monster_knowledge").val());
	$player_ds			= parseInt($("#player_ds").val());
	$return				= false;
	$number				= Math.floor(Math.random() * 100) + 1
	if ($number <= $monster_knowledge) {
		contentShow("#monster_hit");
		//setTimeout(function(){contentHide("#monster_hit")}, 2000);
		$monster_dmg	= Math.floor(Math.random() * ($monster_max_dmg - $monster_min_dmg + 1)) + $monster_min_dmg;
		$monster_dmg	= ($monster_dmg + $monster_me) - $player_ds;
		$monster_dmg	= ($monster_dmg < 1) ? 1 : $monster_dmg;
		$return			= playerDamage($monster_dmg);
	} else {
		contentShow("#monster_missed");
		setTimeout(function(){contentHide("#monster_missed")}, 2000);
		contentHide("#box_run_round");
		contentShowData("#box_rightanswer", 'The monster didn'+"'"+'t reply correctly.<br />Right answer was: "'+$answer+'"<br /><br /> Click here to proceed.');
	}
	//clearInterval(window.counter);
	return $return;
}

function playerDamage($damage) {
	$player_hp		= parseInt($("#player_hp").val());
	$player_hp		= $player_hp - $damage;
	$("#player_hp").val($player_hp);
	contentShow("#monster_hit");
	setTimeout(function(){contentHide("#monster_hit")}, 2000);
	if ($player_hp <= 0) {
		alert("Voce perdeu");
		$return		= 'player_lost';
	} else {
		$return		= 'monster_hit';
	}
	clearInterval(window.counter);
	return $return;
}

function monsterDamage($damage) {
	$monster_hp		= $monster_hp - $damage;
	$("#monster_hp").val($monster_hp);
	contentShow("#user_hit");
	setTimeout(function(){contentHide("#user_hit")}, 2000);
	if ($monster_hp <= 0) {
		$return		= 'player_won';
	} else {
		$return		= 'loadQuestion';
	}
	return $return;
}

function dimAnswer($id_answer) {
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
	//$("#player_hp").val('');
	$("#box_question").html('');
	$("#box_answers").html('');
	$("#turn").val('');
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

function timeLimit($id_question) {
	// Checa se houve resposta
	$id_answer			= $("#id_answer").val();
	$curr_id_question	= $("#id_question").val();
	if (($id_question == $curr_id_question) && (!$id_answer)) {
		$action			= monstersTurn();
		performAction($action);
		/*/
		if ($id_answer) {
			dimAnswer($id_answer);
		}
		/*/
	}
}

function displayCounter($period) {
	$seconds	= $period / 1000;
	$remaining	= $seconds;
	$i			= 0;
	$("#box_counter").html($seconds);
	return setInterval(function($remaining) {
		$remaining	= parseInt($("#box_counter").html());
		$remaining--;
		if ($remaining >= 0) {
			$("#box_counter").html($remaining);
		} else if ($remaining == -1) {
			$id_answer	= $("#id_answer").val();
			if (!$id_answer) {
				alert("Time is up!")
				playerDamage(1, true);
			}
		}
	}, 1000);
}

function performAction($action) {
	if ($action) {
		if ($action == 'loadQuestion') {
			loadQuestion($("#id_course").val());
		} else if ($action == 'monster_hit') {
			contentShowData("#box_rightanswer", 'The monster got it right.<br />The answered "'+$answer+'"<br /><br /> Click here to proceed.');
			//setTimeout(function(){contentHide("#box_rightanswer")}, 2000);
			//loadQuestion($("#id_course").val());
		} else if ($action == 'player_lost') {
			restartCombat();
		} else if ($action == 'player_won') {
			alert("You won!");
			$("#box_counter").html('');
			addTreasure($monster_treasure);
			addXp($monster_xp);
			restartCombat();
		}
	}
}