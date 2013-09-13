<?php
/************************************************************************************
* Name:				Item Model														*
* File:				Application\Model\Item.php 										*
* Author(s):		Vinas de Andrade												*
*																					*
* Description: 		This is the Item's model.										*
*																					*
* Creation Date:	23/08/2012														*
* Version:			1.12.0823														*
* License:			http://www.opensource.org/licenses/bsd-license.php BSD			*
*************************************************************************************/

	namespace Application\Model;

	class Item {

		public function combo($entries = false, $null_select = false, $id = false) {
			$return				= false;
			if ($entries) {
				$return			.= ($null_select) ? '<option value="0">Select...</option>' : ''.PHP_EOL;
				foreach ($entries as $entry) {
					if ($entry['id'] == $id) {
						$return	.= '<option value="'.$entry['id'].'" selected="selected">'.$entry['vc_name'].'</option>'.PHP_EOL;
					} else {
						$return	.= '<option value="'.$entry['id'].'">'.$entry['vc_name'].'</option>'.PHP_EOL;
					}
				}
			}
			return $return;
		}

		public function listCombatItems($entries = false, $fields = false, $ordering = false, $direction = false) {
			$return					= false;
			if (($entries) && ($fields)) {
				$return				= $this->resultCombatHeader($ordering, $direction);
				$tot_entries		= count($entries);
				for ($i = 0; $i < $tot_entries; $i++) {
					$return			.= '<div class="return_row combat_row" key="'.$entries[$i]['id'].'">'.PHP_EOL;
					$return			.= '	<div class="result_field result_id">'.$entries[$i]['id'].'</div>'.PHP_EOL;
					foreach ($fields as $field) {
						if ($entries[$i]['id_field'] > 0) {
							if ($field['id'] == $entries[$i]['id_field']) {
								$return	.= '	<div class="result_field result_idfield">'.$field['vc_name'].'</div>'.PHP_EOL;
								break;
							}
						} else {
							$return		.= '	<div class="result_field result_idfield">General</div>'.PHP_EOL;
						}
					}
					$return			.= '	<div class="result_field result_item">'.$entries[$i]['vc_type'].'</div>'.PHP_EOL;
					$return			.= '	<div class="result_field result_id">'.$entries[$i]['int_level'].'</div>'.PHP_EOL;
					$return			.= '	<div class="result_field result_name">'.$entries[$i]['vc_name'].'</div>'.PHP_EOL;
					//$return			.= '	<div class="result_field result_id">'.$entries[$i]['int_bonus'].'</div>'.PHP_EOL;
					$return			.= '</div><br />'.PHP_EOL;
				}
			}
			return $return;
		}

		public function listNonCombatItems($entries = false, $ordering = false, $direction = false) {
			$return				= false;
			if ($entries) {
				$return			= $this->resultNonCombatHeader($ordering, $direction);
				$tot_entries	= count($entries);
				for ($i = 0; $i < $tot_entries; $i++) {
					$return		.= '<div class="return_row noncombat_row" key="'.$entries[$i]['id'].'">'.PHP_EOL;
					$return		.= '	<div class="result_field result_id">'.$entries[$i]['id'].'</div>'.PHP_EOL;
					$return		.= '	<div class="result_field result_item">'.$entries[$i]['vc_type'].'</div>'.PHP_EOL;
					$return		.= '	<div class="result_field result_id">'.$entries[$i]['int_level'].'</div>'.PHP_EOL;
					$return		.= '	<div class="result_field result_name">'.$entries[$i]['vc_name'].'</div>'.PHP_EOL;
					$return		.= '	<div class="result_field result_id">'.$entries[$i]['int_bonus_start'].' - '.$entries[$i]['int_bonus_end'].'</div>'.PHP_EOL;
					$return		.= '</div><br />'.PHP_EOL;
				}
			}
			return $return;
		}

		private	function resultCombatHeader($ordering = 'u.id', $direction = 'ASC') {
			$return		= '<div class="result_header">'.PHP_EOL;
			if (($ordering == 'i.id') && ($direction == 'ASC')) {
				$return	.= '	<div class="result_header_field result_id" key="i.id" direction="DESC"><img src="/gamemaster/Application/View/img/arrow_down_mini.gif" width="16" height="16" align="absmiddle" />ID</div>'.PHP_EOL;
			} else if (($ordering == 'i.id') && ($direction == 'DESC')) {
				$return	.= '	<div class="result_header_field result_id" key="i.id" direction="ASC"><img src="/gamemaster/Application/View/img/arrow_up_mini.gif" width="16" height="16" align="absmiddle" />ID</div>'.PHP_EOL;
			} else {
				$return	.= '	<div class="result_header_field result_id" key="i.id" direction="ASC">ID</div>'.PHP_EOL;
			}
			if (($ordering == 't.id_field') && ($direction == 'ASC')) {
				$return	.= '	<div class="result_header_field result_idfield"  key="i.id_field" direction="DESC" style="text-align: center;"><img src="/gamemaster/Application/View/img/arrow_down_mini.gif" width="16" height="16" align="absmiddle" />Field</div>'.PHP_EOL;
			} else if (($ordering == 'i.vc_type') && ($direction == 'DESC')) {
				$return	.= '	<div class="result_header_field result_idfield"  key="i.id_field" direction="ASC" style="text-align: center;"><img src="/gamemaster/Application/View/img/arrow_up_mini.gif" width="16" height="16" align="absmiddle" />Field</div>'.PHP_EOL;
			} else {
				$return	.= '	<div class="result_header_field result_idfield"  key="i.id_field" direction="ASC" style="text-align: center;">Field</div>'.PHP_EOL;
			}
			if (($ordering == 't.vc_type') && ($direction == 'ASC')) {
				$return	.= '	<div class="result_header_field result_item"  key="t.vc_type" direction="DESC" style="text-align: center;"><img src="/gamemaster/Application/View/img/arrow_down_mini.gif" width="16" height="16" align="absmiddle" />Type</div>'.PHP_EOL;
			} else if (($ordering == 't.vc_type') && ($direction == 'DESC')) {
				$return	.= '	<div class="result_header_field result_item"  key="t.vc_type" direction="ASC" style="text-align: center;"><img src="/gamemaster/Application/View/img/arrow_up_mini.gif" width="16" height="16" align="absmiddle" />Type</div>'.PHP_EOL;
			} else {
				$return	.= '	<div class="result_header_field result_item"  key="t.vc_type" direction="ASC" style="text-align: center;">Type</div>'.PHP_EOL;
			}
			if (($ordering == 'i.int_level') && ($direction == 'ASC')) {
				$return	.= '	<div class="result_header_field result_id"  key="i.int_level" direction="DESC" style="text-align: center;"><img src="/gamemaster/Application/View/img/arrow_down_mini.gif" width="16" height="16" align="absmiddle" />Level</div>'.PHP_EOL;
			} else if (($ordering == 'i.int_level') && ($direction == 'DESC')) {
				$return	.= '	<div class="result_header_field result_id"  key="i.int_level" direction="ASC" style="text-align: center;"><img src="/gamemaster/Application/View/img/arrow_up_mini.gif" width="16" height="16" align="absmiddle" />Level</div>'.PHP_EOL;
			} else {
				$return	.= '	<div class="result_header_field result_id"  key="i.int_level" direction="ASC" style="text-align: center;">Level</div>'.PHP_EOL;
			}
			if (($ordering == 'i.vc_name') && ($direction == 'ASC')) {
				$return	.= '	<div class="result_header_field result_name"  key="i.vc_name" direction="DESC" style="text-align: center;"><img src="/gamemaster/Application/View/img/arrow_down_mini.gif" width="16" height="16" align="absmiddle" />Name</div>'.PHP_EOL;
			} else if (($ordering == 'i.vc_name') && ($direction == 'DESC')) {
				$return	.= '	<div class="result_header_field result_name"  key="i.vc_name" direction="ASC" style="text-align: center;"><img src="/gamemaster/Application/View/img/arrow_up_mini.gif" width="16" height="16" align="absmiddle" />Name</div>'.PHP_EOL;
			} else {
				$return	.= '	<div class="result_header_field result_name"  key="i.vc_name" direction="ASC" style="text-align: center;">Name</div>'.PHP_EOL;
			}
			/*if (($ordering == 'i.int_bonus') && ($direction == 'ASC')) {
				$return	.= '	<div class="result_header_field result_id"  key="i.int_bonus" direction="DESC" style="text-align: center;"><img src="/gamemaster/Application/View/img/arrow_down_mini.gif" width="16" height="16" align="absmiddle" />Bonus</div>'.PHP_EOL;
			} else if (($ordering == 'i.int_bonus') && ($direction == 'DESC')) {
				$return	.= '	<div class="result_header_field result_id"  key="i.int_bonus" direction="ASC" style="text-align: center;"><img src="/gamemaster/Application/View/img/arrow_up_mini.gif" width="16" height="16" align="absmiddle" />Bonus</div>'.PHP_EOL;
			} else {
				$return	.= '	<div class="result_header_field result_id"  key="i.int_bonus" direction="ASC" style="text-align: center;">Bonus</div>'.PHP_EOL;
			}*/
			$return	.= '</div><br />'.PHP_EOL;
			return $return;
		}

		private	function resultNonCombatHeader($ordering = 'u.id', $direction = 'ASC') {
			$return		= '<div class="result_header">'.PHP_EOL;
			if (($ordering == 'i.id') && ($direction == 'ASC')) {
				$return	.= '	<div class="result_header_field result_id" key="i.id" direction="DESC"><img src="/gamemaster/Application/View/img/arrow_down_mini.gif" width="16" height="16" align="absmiddle" />ID</div>'.PHP_EOL;
			} else if (($ordering == 'i.id') && ($direction == 'DESC')) {
				$return	.= '	<div class="result_header_field result_id" key="i.id" direction="ASC"><img src="/gamemaster/Application/View/img/arrow_up_mini.gif" width="16" height="16" align="absmiddle" />ID</div>'.PHP_EOL;
			} else {
				$return	.= '	<div class="result_header_field result_id" key="i.id" direction="ASC">ID</div>'.PHP_EOL;
			}
			if (($ordering == 't.vc_type') && ($direction == 'ASC')) {
				$return	.= '	<div class="result_header_field result_item"  key="t.vc_type" direction="DESC" style="text-align: center;"><img src="/gamemaster/Application/View/img/arrow_down_mini.gif" width="16" height="16" align="absmiddle" />Type</div>'.PHP_EOL;
			} else if (($ordering == 't.vc_type') && ($direction == 'DESC')) {
				$return	.= '	<div class="result_header_field result_item"  key="t.vc_type" direction="ASC" style="text-align: center;"><img src="/gamemaster/Application/View/img/arrow_up_mini.gif" width="16" height="16" align="absmiddle" />Type</div>'.PHP_EOL;
			} else {
				$return	.= '	<div class="result_header_field result_item"  key="t.vc_type" direction="ASC" style="text-align: center;">Type</div>'.PHP_EOL;
			}
			if (($ordering == 'i.int_level') && ($direction == 'ASC')) {
				$return	.= '	<div class="result_header_field result_id"  key="i.int_level" direction="DESC" style="text-align: center;"><img src="/gamemaster/Application/View/img/arrow_down_mini.gif" width="16" height="16" align="absmiddle" />Level</div>'.PHP_EOL;
			} else if (($ordering == 'i.int_level') && ($direction == 'DESC')) {
				$return	.= '	<div class="result_header_field result_id"  key="i.int_level" direction="ASC" style="text-align: center;"><img src="/gamemaster/Application/View/img/arrow_up_mini.gif" width="16" height="16" align="absmiddle" />Level</div>'.PHP_EOL;
			} else {
				$return	.= '	<div class="result_header_field result_id"  key="i.int_level" direction="ASC" style="text-align: center;">Level</div>'.PHP_EOL;
			}
			if (($ordering == 'i.vc_name') && ($direction == 'ASC')) {
				$return	.= '	<div class="result_header_field result_name"  key="i.vc_name" direction="DESC" style="text-align: center;"><img src="/gamemaster/Application/View/img/arrow_down_mini.gif" width="16" height="16" align="absmiddle" />Name</div>'.PHP_EOL;
			} else if (($ordering == 'i.vc_name') && ($direction == 'DESC')) {
				$return	.= '	<div class="result_header_field result_name"  key="i.vc_name" direction="ASC" style="text-align: center;"><img src="/gamemaster/Application/View/img/arrow_up_mini.gif" width="16" height="16" align="absmiddle" />Name</div>'.PHP_EOL;
			} else {
				$return	.= '	<div class="result_header_field result_name"  key="i.vc_name" direction="ASC" style="text-align: center;">Name</div>'.PHP_EOL;
			}
			if (($ordering == 'i.int_bonus') && ($direction == 'ASC')) {
				$return	.= '	<div class="result_header_field result_id"  key="i.int_bonus" direction="DESC" style="text-align: center;"><img src="/gamemaster/Application/View/img/arrow_down_mini.gif" width="16" height="16" align="absmiddle" />Bonus</div>'.PHP_EOL;
			} else if (($ordering == 'i.int_bonus') && ($direction == 'DESC')) {
				$return	.= '	<div class="result_header_field result_id"  key="i.int_bonus" direction="ASC" style="text-align: center;"><img src="/gamemaster/Application/View/img/arrow_up_mini.gif" width="16" height="16" align="absmiddle" />Bonus</div>'.PHP_EOL;
			} else {
				$return	.= '	<div class="result_header_field result_id"  key="i.int_bonus" direction="ASC" style="text-align: center;">Bonus</div>'.PHP_EOL;
			}
			$return	.= '</div><br />'.PHP_EOL;
			return $return;
		}

		public function jqueryCombatItems($entries = false, $fields	= false, $pager = false, $ordering = 'i.id', $direction = 'ASC') {
			$return				= false;
			$rows				= '';
			if (($entries) && ($pager) && ($fields)) {
				$tot_entries	= count($entries);
				for ($i = 0; $i < $tot_entries; $i++) {
					$rows				.= '<div class="return_row combat_row" key="'.$entries[$i]['id'].'">'.PHP_EOL;
					$rows				.= '	<div class="result_field result_id">'.$entries[$i]['id'].'</div>'.PHP_EOL;
					foreach ($fields as $field) {
						if ($entries[$i]['id_field'] > 0) {
							if ($field['id'] == $entries[$i]['id_field']) {
								$rows	.= '	<div class="result_field result_idfield">'.$field['vc_name'].'</div>'.PHP_EOL;
								break;
							}
						} else {
							$rows		.= '	<div class="result_field result_idfield">General</div>'.PHP_EOL;
						}
					}
					$rows				.= '	<div class="result_field result_item">'.$entries[$i]['vc_type'].'</div>'.PHP_EOL;
					$rows				.= '	<div class="result_field result_id">'.$entries[$i]['int_level'].'</div>'.PHP_EOL;
					$rows				.= '	<div class="result_field result_name">'.$entries[$i]['vc_name'].'</div>'.PHP_EOL;
					//$rows				.= '	<div class="result_field result_id">'.$entries[$i]['int_bonus'].'</div>'.PHP_EOL;
					$rows				.= '</div><br />'.PHP_EOL;
				}
				$return			.= '<div class="navigation_box" id="up_nav_box">'.PHP_EOL;
				$return			.= $pager.PHP_EOL;
				$return			.= '</div>'.PHP_EOL;
				$return			.= '<div class="result_box" id="result_box">'.PHP_EOL;
				$return			.= $this->resultCombatHeader($ordering, $direction).PHP_EOL;
				$return			.= $rows.PHP_EOL;
				$return			.= '</div>'.PHP_EOL;
				$return			.= '<div class="navigation_box" id="down_nav_box">'.PHP_EOL;
				$return			.= $pager.PHP_EOL;
				$return			.= '</div>'.PHP_EOL;
			}
			return $return;
		}

		public function jqueryNonCombatItems($entries = false, $pager = false, $ordering = 'i.id', $direction = 'ASC') {
			$return				= false;
			$rows				= '';
			if (($entries) && ($pager)) {
				$tot_entries	= count($entries);
				for ($i = 0; $i < $tot_entries; $i++) {
					$rows				.= '<div class="return_row noncombat_row" key="'.$entries[$i]['id'].'">'.PHP_EOL;
					$rows				.= '	<div class="result_field result_id">'.$entries[$i]['id'].'</div>'.PHP_EOL;
					$rows				.= '	<div class="result_field result_item">'.$entries[$i]['vc_type'].'</div>'.PHP_EOL;
					$rows				.= '	<div class="result_field result_id">'.$entries[$i]['int_level'].'</div>'.PHP_EOL;
					$rows				.= '	<div class="result_field result_name">'.$entries[$i]['vc_name'].'</div>'.PHP_EOL;
					$rows				.= '	<div class="result_field result_id">'.$entries[$i]['int_bonus_start'].' - '.$entries[$i]['int_bonus_end'].'</div>'.PHP_EOL;
					$rows				.= '</div><br />'.PHP_EOL;
				}
				$return			.= '<div class="navigation_box" id="up_nav_box">'.PHP_EOL;
				$return			.= $pager.PHP_EOL;
				$return			.= '</div>'.PHP_EOL;
				$return			.= '<div class="result_box" id="result_box">'.PHP_EOL;
				$return			.= $this->resultNonCombatHeader($ordering, $direction).PHP_EOL;
				$return			.= $rows.PHP_EOL;
				$return			.= '</div>'.PHP_EOL;
				$return			.= '<div class="navigation_box" id="down_nav_box">'.PHP_EOL;
				$return			.= $pager.PHP_EOL;
				$return			.= '</div>'.PHP_EOL;
			}
			return $return;
		}

	}