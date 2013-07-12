$('document').ready(function() {

	// What happens when user clicks on "Cancel" while creating
	// a new course
	$(".bt_cancel, .show_form").live("click", function() {
		$(location).attr('href', '/gamemaster/Textures/insert');
	});

	// What happens when user changes Texture Type on Insert Form
	$("#id_texturetype").live("click", function() {
		$id_texturetype	= $(this).val();
		if ($id_texturetype == 1) {
			$(".not_shown").hide();
			$("#image").val('');
			contentShow("#map_tile");
			contentShow(".img_input");
		} else if ($id_texturetype == 2) {
			$(".not_shown").hide();
			$("#image").val('');
			contentShow("#map_icon");
			contentShow(".img_input");
		} else if ($id_texturetype == 3) {
			$(".not_shown").hide();
			$("#image").val('');
			contentShow("#image");
			contentShow(".img_input");
		} else {
			$(".not_shown").hide();
			$(".img_input").hide();
		}
	});

	// What happens when user selects a image file
	$("#image").live("change", function() {
		$("#form_buttons").show();
		return false;
	});

	// What happens when user clicks the "upload texure" button
	$(".new_texture").live("click", function() {
		$("#file_upload_form").submit();
		return false;
	});

	// What happens when user click on database result line
	$(".tile_row").live("click", function() {
		$key		= $(this).attr('key');
		if ($key) {
			openFancybox('/gamemaster/Textures/detailsTile/'+$key, 800, 500);
		}
		return false;
	});

	// What happens when user clicks on ordering option
	$(".result_header_tile").live("click", function() {
		document.body.style.cursor	= 'wait';
		$key		= 1; // Should go to first page
		$ordering	= $(this).attr('key');
		$offset		= $('#offset').val();
		$limit		= $('#limit').val();
		$direction	= $(this).attr('direction');
		$str_search	= $('#str_search').val();
		$parent_id	= $('#parent_id').val();
		$actionurl	= actionURL();
		if (($key) && ($ordering) && ($offset) && ($limit) && ($direction) && ($actionurl)) {
			fetchResults($actionurl, $key, $ordering, $offset, $limit, $direction, $str_search, $parent_id);
		}
		document.body.style.cursor	= 'default';
		return false;
	});

	// what happens when user tries to delete a tile
	$("#delete_tile").live("click", function() {
		$res					= confirm("ATENTION,\n\nAre you sure you want to delete this tile??");
		if ($res) {
			document.body.style.cursor	= 'wait';
			$id_tile			= $("#id_tile").val();
			if ($id_tile) {
				$.post('/gamemaster/Textures/deleteTile/', {
					id_tile:	$id_tile
				}, function($return) {
					if ($return == 'ok') {
						alert("Tile succefully deleted!");
						document.body.style.cursor	= 'default';
						parent.$.fancybox.close();
					} else {
						alert("Tile could NOT be deleted!\n\n"+$return);
						document.body.style.cursor	= 'default';
					}
					return false;
				});
			}
		}
		document.body.style.cursor	= 'default';
	});

	// What happens when a user is updated
	$(".updt_tile").live("click", function() {
		$id_tile		= $("#id_tile").val();
		$id_tiletype	= $("#id_tiletype").val();
		$vc_name		= $("#tile_name").val();
		if (($id_tile) && ($id_tiletype) && ($vc_name)) {
			$.post('/gamemaster/Textures/updateTile/', {
				id_tile:		$id_tile,
				id_tiletype:	$id_tiletype,
				vc_name:		$vc_name
			}, function($return) {
				$return			= $return.trim();
				if ($return == 'ok') {
					$("#formInsertCourse").hide();
					$("#message_area").hide();
					$("#main_title").hide();
					contentShowData("#message_area", '<span class="title_01">Tile succefully Updated!!</span>');
					parent.$.fancybox.close();
				} else {
					alert("Sorry,\n\nIt was't possible to update this tile on the database.\n\nError: "+$return);
				}
				document.body.style.cursor = 'default';
				return false;
			});
		} else {
			alert("Please\n\nFill all the fields.");
		}
		return false;
	});

});