<?php
/************************************************************************************
* Name:				User Model														*
* File:				Application\Model\User.php 										*
* Author(s):		Vinas de Andrade												*
*																					*
* Description: 		This is the User's model.										*
*																					*
* Creation Date:	15/11/2012														*
* Version:			1.12.1115														*
* License:			http://www.opensource.org/licenses/bsd-license.php BSD			*
*************************************************************************************/

	namespace Application\Model;

	class Monster {

		public function listMonsters($entries = false, $ordering = false, $direction = false) {
			$return				= false;
			if ($entries) {
				$return			= $this->resultHeader($ordering, $direction);
				$tot_entries	= count($entries);
				for ($i = 0; $i < $tot_entries; $i++) {
					$return		.= '<div class="return_row" key="'.$entries[$i]['id'].'">'.PHP_EOL;
					$return		.= '	<div class="result_field result_id">'.$entries[$i]['id'].'</div>'.PHP_EOL;
					$return		.= '	<div class="result_field result_id">'.$entries[$i]['int_level'].'</div>'.PHP_EOL;
					$return		.= '	<div class="result_field result_vc_id">'.$entries[$i]['vc_id'].'</div>'.PHP_EOL;
					$return		.= '	<div class="result_field result_name">'.$entries[$i]['vc_name'].'</div>'.PHP_EOL;
					$return		.= '	<div class="result_field result_hits">'.$entries[$i]['int_hits_min'].' - '.$entries[$i]['int_hits_max'].'</div>'.PHP_EOL;
					$return		.= '	<div class="result_field result_hits">'.$entries[$i]['int_damage_min'].' - '.$entries[$i]['int_damage_max'].'</div>'.PHP_EOL;
					$return		.= '	<div class="result_field result_treasure">'.$entries[$i]['int_treasure_min'].' - '.$entries[$i]['int_treasure_max'].' gold</div>'.PHP_EOL;
					$return		.= '</div><br />'.PHP_EOL;
				}
			}
			return $return;
		}

		public function jqueryMonsters($entries = false, $pager = false, $ordering = 'id', $direction = 'ASC') {
			$return				= false;
			$rows				= '';
			if (($entries) && ($pager)) {
				$tot_entries	= count($entries);
				for ($i = 0; $i < $tot_entries; $i++) {
					$rows		.= '<div class="return_row" key="'.$entries[$i]['id'].'">'.PHP_EOL;
					$rows		.= '	<div class="result_field result_id">'.$entries[$i]['id'].'</div>'.PHP_EOL;
					$rows		.= '	<div class="result_field result_id">'.$entries[$i]['int_level'].'</div>'.PHP_EOL;
					$rows		.= '	<div class="result_field result_vc_id">'.$entries[$i]['vc_id'].'</div>'.PHP_EOL;
					$rows		.= '	<div class="result_field result_name">'.$entries[$i]['vc_name'].'</div>'.PHP_EOL;
					$rows		.= '	<div class="result_field result_hits">'.$entries[$i]['int_hits_min'].' - '.$entries[$i]['int_hits_max'].'</div>'.PHP_EOL;
					$rows		.= '	<div class="result_field result_hits">'.$entries[$i]['int_damage_min'].' - '.$entries[$i]['int_damage_max'].'</div>'.PHP_EOL;
					$rows		.= '	<div class="result_field result_treasure">'.$entries[$i]['int_treasure_min'].' - '.$entries[$i]['int_treasure_max'].' gold</div>'.PHP_EOL;
					$rows		.= '</div><br />'.PHP_EOL;
				}
				$return			.= '<div class="navigation_box" id="up_nav_box">'.PHP_EOL;
				$return			.= $pager.PHP_EOL;
				$return			.= '</div>'.PHP_EOL;
				$return			.= '<div class="result_box" id="result_box">'.PHP_EOL;
				$return			.= $this->resultHeader($ordering, $direction).PHP_EOL;
				$return			.= $rows.PHP_EOL;
				$return			.= '</div>'.PHP_EOL;
				$return			.= '<div class="navigation_box" id="down_nav_box">'.PHP_EOL;
				$return			.= $pager.PHP_EOL;
				$return			.= '</div>'.PHP_EOL;
			}
			return $return;
		}

		private	function resultHeader($ordering = 'u.id', $direction = 'ASC') {
			$return		= '<div class="result_header">'.PHP_EOL;
			if (($ordering == 'id') && ($direction == 'ASC')) {
				$return	.= '	<div class="result_header_field result_id" key="id" direction="DESC"><img src="/gamemaster/Application/View/img/arrow_down_mini.gif" width="16" height="16" align="absmiddle" />ID</div>'.PHP_EOL;
			} else if (($ordering == 'id') && ($direction == 'DESC')) {
				$return	.= '	<div class="result_header_field result_id" key="id" direction="ASC"><img src="/gamemaster/Application/View/img/arrow_up_mini.gif" width="16" height="16" align="absmiddle" />ID</div>'.PHP_EOL;
			} else {
				$return	.= '	<div class="result_header_field result_id" key="id" direction="ASC">ID</div>'.PHP_EOL;
			}
			if (($ordering == 'int_level') && ($direction == 'ASC')) {
				$return	.= '	<div class="result_header_field result_id"  key="int_level" direction="DESC" style="text-align: center;"><img src="/gamemaster/Application/View/img/arrow_down_mini.gif" width="16" height="16" align="absmiddle" />Level</div>'.PHP_EOL;
			} else if (($ordering == 'int_level') && ($direction == 'DESC')) {
				$return	.= '	<div class="result_header_field result_id"  key="int_level" direction="ASC" style="text-align: center;"><img src="/gamemaster/Application/View/img/arrow_up_mini.gif" width="16" height="16" align="absmiddle" />Level</div>'.PHP_EOL;
			} else {
				$return	.= '	<div class="result_header_field result_id"  key="int_level" direction="ASC" style="text-align: center;">Level</div>'.PHP_EOL;
			}
			if (($ordering == 'vc_id') && ($direction == 'ASC')) {
				$return	.= '	<div class="result_header_field result_vc_id"  key="vc_id" direction="DESC" style="text-align: center;"><img src="/gamemaster/Application/View/img/arrow_down_mini.gif" width="16" height="16" align="absmiddle" />Game ID</div>'.PHP_EOL;
			} else if (($ordering == 'vc_id') && ($direction == 'DESC')) {
				$return	.= '	<div class="result_header_field result_vc_id"  key="vc_id" direction="ASC" style="text-align: center;"><img src="/gamemaster/Application/View/img/arrow_up_mini.gif" width="16" height="16" align="absmiddle" />Game ID</div>'.PHP_EOL;
			} else {
				$return	.= '	<div class="result_header_field result_vc_id"  key="vc_id" direction="ASC" style="text-align: center;">Game ID</div>'.PHP_EOL;
			}
			if (($ordering == 'vc_name') && ($direction == 'ASC')) {
				$return	.= '	<div class="result_header_field result_name"  key="vc_name" direction="DESC" style="text-align: center;"><img src="/gamemaster/Application/View/img/arrow_down_mini.gif" width="16" height="16" align="absmiddle" />Name</div>'.PHP_EOL;
			} else if (($ordering == 'vc_name') && ($direction == 'DESC')) {
				$return	.= '	<div class="result_header_field result_name"  key="vc_name" direction="ASC" style="text-align: center;"><img src="/gamemaster/Application/View/img/arrow_up_mini.gif" width="16" height="16" align="absmiddle" />Name</div>'.PHP_EOL;
			} else {
				$return	.= '	<div class="result_header_field result_name"  key="vc_name" direction="ASC" style="text-align: center;">Name</div>'.PHP_EOL;
			}
			if (($ordering == 'int_hits_min') && ($direction == 'ASC')) {
				$return	.= '	<div class="result_header_field result_hits"  key="int_hits_min" direction="DESC" style="text-align: center;"><img src="/gamemaster/Application/View/img/arrow_down_mini.gif" width="16" height="16" align="absmiddle" />Hit Points</div>'.PHP_EOL;
			} else if (($ordering == 'int_hits_min') && ($direction == 'DESC')) {
				$return	.= '	<div class="result_header_field result_hits"  key="int_hits_min" direction="ASC" style="text-align: center;"><img src="/gamemaster/Application/View/img/arrow_up_mini.gif" width="16" height="16" align="absmiddle" />Hit Points</div>'.PHP_EOL;
			} else {
				$return	.= '	<div class="result_header_field result_hits"  key="int_hits_min" direction="ASC" style="text-align: center;">Hit Points</div>'.PHP_EOL;
			}

			if (($ordering == 'int_damage_min') && ($direction == 'ASC')) {
				$return	.= '	<div class="result_header_field result_hits"  key="int_damage_min" direction="DESC" style="text-align: center;"><img src="/gamemaster/Application/View/img/arrow_down_mini.gif" width="16" height="16" align="absmiddle" />Damage</div>'.PHP_EOL;
			} else if (($ordering == 'int_damage_min') && ($direction == 'DESC')) {
				$return	.= '	<div class="result_header_field result_hits"  key="int_damage_min" direction="ASC" style="text-align: center;"><img src="/gamemaster/Application/View/img/arrow_up_mini.gif" width="16" height="16" align="absmiddle" />Damage</div>'.PHP_EOL;
			} else {
				$return	.= '	<div class="result_header_field result_hits"  key="int_damage_min" direction="ASC" style="text-align: center;">Damage</div>'.PHP_EOL;
			}
			if (($ordering == 'int_treasure_max') && ($direction == 'ASC')) {
				$return	.= '	<div class="result_header_field result_treasure"  key="int_treasure_max" direction="DESC" style="text-align: center;"><img src="/gamemaster/Application/View/img/arrow_down_mini.gif" width="16" height="16" align="absmiddle" />Treasure Drop</div>'.PHP_EOL;
			} else if (($ordering == 'int_treasure_max') && ($direction == 'DESC')) {
				$return	.= '	<div class="result_header_field result_treasure"  key="int_treasure_max" direction="ASC" style="text-align: center;"><img src="/gamemaster/Application/View/img/arrow_up_mini.gif" width="16" height="16" align="absmiddle" />Treasure Drop</div>'.PHP_EOL;
			} else {
				$return	.= '	<div class="result_header_field result_treasure"  key="int_treasure_max" direction="ASC" style="text-align: center;">Treasure Drop</div>'.PHP_EOL;
			}
			$return	.= '</div><br />'.PHP_EOL;
			return $return;
		}

	}