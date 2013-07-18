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
		$map		= $(this).attr('map');
		if ($map) {
			$(location).attr('href', '/gamemaster/Maps/EditLocalMap/'+$map);
		} else {
			if (($id_world > 0) && ($pos) && ($key)) {
				$("#action").val($key);
				$("#world_pos").val($pos);
				contentHide("#map_area");
				contentShow("#tiletype");
				contentShow("#branch_field");
			}
		}
		return false;
	});

	// What happens when user clickes a local map tile
	$(".local_map_tile").live("click", function() {
		$("#tile_options").show();
		$(".opt_details").hide();
		$last_id		= $("#target_tile_id").val();
		$this_id		= $(this).attr('id');
		$last_content	= $("#"+$last_id).html();
		$last_status	= $("#"+$last_id).attr('status');
		$last_icon		= $("#"+$last_id).attr('icon');
		if ($last_status == 'selected') {
			$("#"+$last_id).attr('status', 'unselected');
			if ($last_icon) {
				$("#"+$last_id).css("visibility", 'visible');
			} else {
				$("#"+$last_id).html('');
			}
		}
		if (!$(this).html()) {
			$html			= $(this).html('<img src="/gamemaster/Application/View/img/textures/selected.png" width="35" height="35" border="0" />');
		} else {
			$html			= $(this).css("visibility", 'hidden');
		}
		$html				= $(this).attr('status', 'selected');
		$("#target_tile_id").val($this_id);
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

	// What happens when user clicks on an action menu item
	$(".tile_opt").live("click", function() {
		$this_block	= $(this).attr('key');
		$id_map		= $("#id_map").val();
		if ($this_block) {
			$(".tile_options").hide();
			if ($this_block == 'linkTile') {
				contentShow("#"+$this_block);
			} else {
				$.post('/gamemaster/Maps/'+$this_block, false, function($return) {
					if ($return) {
						contentShowData("#"+$this_block, $return);
					}
					return false;
				});
			}
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
				$("#"+$position).attr('image', $image);
				$("#"+$position).attr('icon', $id_icon);
				contentHide("#map_interaction");
			}
		}
		return false;
	});

	// What happens when user enters a Map ID for linking
	$("#id_map").live("keypress", function(e) {
		if (e.keyCode == 13) {
			$id_map	= $(this).val();
			alert('to map id: '+$id_map);
		}
	});

	// What happens when user click on "link to a new dungeon"
	$(".newDungeon").live("click", function() {
	});

	// What happens when user click on "Save Map"
	$(".save_map").live("click", function() {
		$position			= $("#target_tile_id").val();
		$id_areatype		= $("#id_areatype").val();
		$world_pos			= $("#world_pos").val();
		$id_world			= $("#id_world").val();
		$id_field			= $("#id_field").val();
		$level				= $("#level").val();
		$coords				= getAllMapsCoords();
		$.post('/gamemaster/Maps/saveMap', {
			id_areatype:	$id_areatype,
			world_pos:		$world_pos,
			id_world:		$id_world,
			id_field:		$id_field,
			level:			$level,
			coords:			$coords
		}, function($return) {
			$return			= $return.trim();
			if ($return) {
				$(location).attr('href', '/gamemaster/Maps/EditLocalMap/'+$return);
			} else {
				alert("Sorry,\n\nbla bla bla.\n\nError: "+$return);
			}
			return false;
		});
		return false;
	});
});


// ********* GENERAL FUNCTIONS  ********* \\

function getAllMapsCoords() {
	var $coords			= new Array();
	for ($i = 1; $i <= 100; $i++) {
		if ($i <= 9) {
			$pos		= '00'+$i;
		} else if (($i > 9) && ($i < 100)) {
			$pos		= '0' + $i;
		} else {
			$pos		= $i;
		}
		$coords[$i]		= new Array();
		$coords[$i][0]	= $("#"+$pos).attr('bkgrnd');
		$coords[$i][1]	= $("#"+$pos).attr('icon');
	}
	return $coords;
}