$('document').ready(function() {

	// What happens when user selects a monster to be inserted
	$(".monsters_return_row").live("click", function() {
		$id_monster	= $(this).attr('key');
		$("#id_monster").val($id_monster);
		$('.selected_monsters_return_row').attr('class', 'monsters_return_row');
		$(this).attr('class', 'selected_monsters_return_row');
		// get monster ifo
		$.post('/gamemaster/Robots/loadMonster/', {
			id_monster:		$id_monster,
		}, function($return) {
			if ($return) {
				$("#monster_hp").val($return.monster_hp);
				$("#monster_min_dmg").val($return.monster_min_dmg);
				$("#monster_max_dmg").val($return.monster_max_dmg);
			}
		});
		return false;
	});

});