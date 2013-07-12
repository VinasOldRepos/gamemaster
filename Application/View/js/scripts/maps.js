$('document').ready(function() {

	// What happens when user clicks on "Cancel" while creating
	// a new course
	$(".bt_cancel, .show_form").live("click", function() {
		$(location).attr('href', '/gamemaster/Users/insert');
	});

	// What happens when user selects a world on Insert Map
	$("#id_world").live("change", function() {
		$id_world	= $(this).val();
		if ($id_world > 0) {
			$.post('/gamemaster/Maps/loadWorldMap/', {
				id_world:	$id_world
			}, function($return) {
				if ($return) {
					contentShowData("#map_area", $return);
				} else {
					alert("Sorry,\n\nThere was a problem when loading the selected world.\n\nError: ");
				}
				return false;
			});
		}
		return false;
	});

	// What happens when wants to create a new local map
	$(".world_map_tile").live("click", function() {
		$id_world	= $("#id_world").val();
		$pos		= $(this).attr('pos');
		$key		= $(this).attr('key');
		if (($id_world > 0) && ($pos) && ($key)) {
			$("#action").val($key);
			contentHide("#map_area");
			contentShow("#tiletype");
			contentShow("#branch_field");
		}
		return false;
	});

	// What happens when user clickes a local map tile
	$(".local_map_tile").live("click", function() {
		$(".tile_options").show();
		$(".opt_details").hide();
		$last_id		= $("#target_tile_id").val();
		$this_id		= $(this).attr('id');
		$last_content	= $("#"+$last_id).html();
		if ($last_content == '<img src="/gamemaster/Application/View/img/textures/selected.png" width="35" height="35" border="0">') {
			$("#"+$last_id).html('');
		} else {
			$("#"+$last_id).css("visibility", 'visible');
		}
		$("#target_tile_id").val($this_id);
		if (!$(this).html()) {
			$html			= $(this).html('<img src="/gamemaster/Application/View/img/textures/selected.png" width="35" height="35" border="0" />');
		} else {
			$html			= $(this).css("visibility", 'hidden');
		}
		contentShow("#map_interaction");
		return false;
	});

	// What happens when user selects tile type (new map)
	$("#id_tiletype").live("change", function() {
		$action			= $("#action").val();
		$id_tiletype	= $(this).val();
		if (($action) && ($id_tiletype)) {
			$.post('/gamemaster/Maps/'+$action, {
				id_world:	$id_world,
				position:	$pos,
				id_tiletype: $id_tiletype
			}, function($return) {
				if ($return) {
					contentShowData("#map_area", $return);
				} else {
					alert("Sorry,\n\nThere was a problem when loading the selected area.\n\nError: ");
				}
				return false;
			});
		}
		return false;
	});

	// What happens when user selects branch (new map)
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

	// What happens when user clicks on "Insert an icon"
	$(".tile_opt").live("click", function() {
		$this_block	= $(this).attr('key');
		if ($this_block) {
			$.post('/gamemaster/Maps/'+$this_block, false, function($return) {
				if ($return) {
					$(".tile_options").hide();
					contentShowData("#"+$this_block, $return);
				}
				return false;
			});
		}
		return false;
	});

	// What happens when user clicks on an icon to be added
	$(".details_return_row").live("click", function() {
		$position	= $("#target_tile_id").val();
		$id_map		= $("#id_map").val();
		$id_icon	= $(this).attr('key');
		$image		= $(this).attr('image');
		if ($id_map) {
			alert('edicao');
		} else {
			if ($id_icon) {
				$("#"+$position).html('<img src="/gamemaster/Application/View/img/textures/'+$image+'" width="15" height="15" border="0">');
				contentHide("#map_interaction");
			}
		}
		return false;
	});

});