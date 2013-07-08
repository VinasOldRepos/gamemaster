$('document').ready(function() {

	// What happens when user clicks on "Cancel" while creating
	// a new course
	$(".bt_cancel, .show_form").live("click", function() {
		$(location).attr('href', '/gamemaster/Users/insert');
	});

	// What happens when user tries to delete a User
	$(".delete_user").live("click", function () {
		$res					= confirm("ATENTION,\n\nAre you sure you want to delete this user??");
		if ($res) {
			document.body.style.cursor	= 'wait';
			$id_user			= $("#id_user").val();
			if ($id_user) {
				$.post('/gamemaster/Users/deleteUser/', {
					id_user:	$id_user
				}, function($return) {
					if ($return == 'ok') {
						alert("User succefully deleted!");
						document.body.style.cursor	= 'default';
						parent.$.fancybox.close();
					} else {
						alert("User could NOT be deleted!\n\n"+$return);
						document.body.style.cursor	= 'default';
					}
					return false;
				});
			}
		}
		document.body.style.cursor	= 'default';
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
		}
		return false;
	});

	// What happens when user clickes a local map tile
	$(".local_map_tile").live("click", function() {
		$tile_pos	= $(this).attr('id');
		alert($tile_pos);
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

});