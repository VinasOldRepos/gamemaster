$('document').ready(function() {

	// What happens when a dropdown button is pressed
	// (used to step approach)
	$(".bt_dropdown").live("click", function() {
		$key	= $(this).attr('key');
		if ($key) {
			$("#"+$key).show(400);
			$(this).attr('src', '/gamemaster/Application/View/img/bt_dropdown_off.png');
		}
		return false;
	});


	// **************** \\
	// ***** MENU ***** \\
	// **************** \\

	// What happens when user clicks a main menu item
	$(".menu .item_off").live("click", function() {
		$(".item_on").attr('class', 'item_off');
		$(".item_on").css('background-color', '#7A7A7A');
		$(".menu .item_off").css('background-color', '#7A7A7A');
		$(this).attr('class', 'item_on');
		$(this).css('background-color', '#FFFFFF');
		$controller	= $(this).attr('key');
		if ($controller) {
			$(".menu .details").hide();
			contentShow("#"+$controller);
		}
		return false;
	});

	// What happens when user clicks a sub menu item
	$(".details_item_off, .details_item_on").live("click", function() {
		$controller	= this.parentNode.id;
		$key		= $(this).attr('key');
		if ($key == 'CombatSimulator') {
			$.fancybox({
				href			: '/gamemaster/Robots/CombatSimulator/',
				width			: 900,
				height			: 650,
				autoScale		: false,
				showCloseButton	: true,
				scrolling		: 'yes',
				transitionIn	: 'elastic',
				transitionOut	: 'elastic',
				speedIn			: 600,
				speedOut		: 200,
				type			: 'iframe'
			});
		} else {
			$(location).attr('href', '/gamemaster/'+$controller+'/'+$key);
		}
		return false;
	});

	// Main Menu item mouseover/out
	$(".menu .item_off").live("mouseover", function() {
		$(this).css('background-color', '#FFFFFF');
	}).live("mouseout", function() {
		$controller	= $(this).attr('key');
		$vis		= $("#"+$controller).css('display');
		if ($vis != 'block') {
			$(this).css('background-color', '#7A7A7A');
		} else {
			$(this).css('background-color', '#FFFFFF');
		}
	});


	// ******************* \\
	// ***** RESULTS ***** \\
	// ******************* \\

	// What happens when user performs a search
	$("#str_search").live("keypress", function(e) {
		if (e.keyCode == 13) {
			document.body.style.cursor	= 'wait';
			$key		= 1; // Should go to first page
			$ordering	= $('#ordering').val();
			$offset		= $('#offset').val();
			$limit		= $('#limit').val();
			$direction	= $('#direction').val();
			$direction	= $('#direction').val();
			$str_search	= $(this).val();
			$parent_id	= $('#parent_id').val();
			$actionurl	= actionURL();
			if (($key) && ($ordering) && ($offset) && ($limit) && ($direction) && ($actionurl)) {
				fetchResults($actionurl, $key, $ordering, $offset, $limit, $direction, $str_search, $parent_id);
			}
			return false;
		}
	});

	// Click navigation next/previous buttons
	$(".goto_page").live("click", function() {
		document.body.style.cursor	= 'wait';
		$key		= $(this).attr('key');
		$ordering	= $('#ordering').val();
		$offset		= $('#offset').val();
		$limit		= $('#limit').val();
		$direction	= $('#direction').val();
		$str_search	= $('#str_search').val();
		$parent_id	= $('#parent_id').val();
		$actionurl	= actionURL();
		// if (($key) && ($ordering) && ($offset) && ($limit) && ($direction) && ($actionurl)) {
		if (($key) && ($ordering) && ($limit) && ($direction) && ($actionurl)) {
			fetchResults($actionurl, $key, $ordering, $offset, $limit, $direction, $str_search, $parent_id);
		}
		return false;
	});

	// Go to specific page
	$(".pager").live("keypress", function(e) {
		if (e.keyCode == 13) {
			document.body.style.cursor	= 'wait';
			$key		= parseInt($(this).val());
			$ordering	= $('#ordering').val();
			$offset		= $('#offset').val();
			$limit		= $('#limit').val();
			$direction	= $('#direction').val();
			$str_search	= $('#str_search').val();
			$tot_pages	= parseInt($("#pager_tot_pages").val());
			$crrnt_page	= parseInt($("#pager_pg_num").val());
			$parent_id	= $('#parent_id').val();
			$actionurl	= actionURL();
			if (($key > 0) && ($key <= $tot_pages) && ($key != $crrnt_page)) {
				if (($key) && ($ordering) && ($offset) && ($limit) && ($direction) && ($actionurl)) {
					fetchResults($actionurl, $key, $ordering, $offset, $limit, $direction, $str_search, $parent_id);
				}
			} else {
				alert("Please enter a number between 1 and " + $tot_pages);
				$(this).val($crrnt_page);
				$(this).focus();
				$(this).select();
				document.body.style.cursor = 'default';
			}
			return false;
		}
	});

	// Select # of rows per page
	$(".pager_select").live("change", function() {
		document.body.style.cursor	= 'wait';
		$key		= 1; // Should go to first page
		$ordering	= $('#ordering').val();
		$offset		= $('#offset').val();
		$limit		= $(this).val();
		$direction	= $('#direction').val();
		$str_search	= $('#str_search').val();
		$actionurl	= actionURL();
		if (($key) && ($ordering) && ($offset) && ($limit) && ($direction) && ($actionurl)) {
			fetchResults($actionurl, $key, $ordering, $offset, $limit, $direction, $str_search);
		}
		return false;
	});

	// What happens when user clicks on ordering option
	$(".result_header_field").live("click", function() {
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

});

// ***************************** \\
// ***** GENERAL FUNCTIONS ***** \\
// ***************************** \\

// Fetch and display result list
function fetchResults($actionurl, $key, $ordering, $offset, $limit, $direction, $str_search, $parent_id) {
	$.post($actionurl, {
		num_page:	$key,
		ordering:	$ordering,
		offset:		$offset,
		limit:		$limit,
		str_search:	$str_search,
		direction:	$direction,
		parent_id:	$parent_id
	}, function($data) {
		$("#results").hide();
		$("#results").html($data);
		$("#result_box").hide();
		$("#results").show();
		contentShow("#result_box");
		//contentShowData("#results", $data)
		document.body.style.cursor = 'default';
		return false;
	});
}

// Define action urls for paging/search
function actionURL() {
	$return		= false;
	$thispage	= $("#this_page").val();
	$action		= $("#action").val();
	if (($thispage) && ($action)) {
		if ($thispage == 'textures') {
			$return	= '/gamemaster/Textures/'+$action;
		} else if ($thispage == 'monsters') {
			$return	= '/gamemaster/Monsters/'+$action;
		} else if ($thispage == 'users') {
			$return	= '/gamemaster/Users/'+$action;
		} else if ($thispage == 'items') {
			$return	= '/gamemaster/Items/'+$action;
		}
		return $return;
	/* } else {
			alert("Sorry,\n\nIt wasn't possible to perform the requested action.\n\nError: $thispage or $action not defined");
			document.body.style.cursor = 'default';
			return $return;
		} */
	}
}

// Handles ajax content being showed
function contentShowData($object, $data) {
	$($object).hide();
	$($object).html($data);
	$($object).show(400);
}

// Handles static content being showed
function contentShow($object) {
	$($object).hide();
	$($object).show(400);
}

// Handles static content being hidden
function contentHide($object) {
	$($object).hide(200);
}

// Opens fancybox
function openFancybox($url, $width, $height) {
	$.fancybox({
		href			: $url,
		width			: $width,
		height			: $height,
		autoScale		: false,
		showCloseButton	: true,
		scrolling		: 'no',
		transitionIn	: 'elastic',
		transitionOut	: 'elastic',
		speedIn			: 600,
		speedOut		: 200,
		type			: 'iframe',
		onClosed		: function() {
			$key		= parent.$('#pager_pg_num').val();
			$ordering	= parent.$('#ordering').val();
			$offset		= parent.$('#offset').val();
			$limit		= parent.$('#limit').val();
			$direction	= parent.$('#direction').val();
			$direction	= parent.$('#direction').val();
			$str_search	= parent.$('#str_search').val();
			$parent_id	= parent.$('#parent_id').val();
			$actionurl	= actionURL();
			if (($key) && ($ordering) && ($offset) && ($limit) && ($direction) && ($actionurl)) {
				document.body.style.cursor	= 'wait';
				fetchResults($actionurl, $key, $ordering, $offset, $limit, $direction, $str_search, $parent_id);
			}
		}
	});
}

// Similiar to the PHP one
function sprintf($length, $char, $string) {
	if (($length) && ($string)&& ($char)) {
		$str_len	= $string.length;
		if ($length > $str_len) {
			for ($i = 0; $i < $length - $str_len; $i++) {
				$string	= $char+$string;
			}
		}
		return $string;
	}
}