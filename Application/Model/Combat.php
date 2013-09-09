<?php
/************************************************************************************
* Name:				Combat Model													*
* File:				Application\Model\Combat.php 									*
* Author(s):		Vinas de Andrade												*
*																					*
* Description: 		This is the Combat's model.										*
*																					*
* Creation Date:	04/09/2012														*
* Version:			1.12.0904														*
* License:			http://www.opensource.org/licenses/bsd-license.php BSD			*
*************************************************************************************/

	namespace Application\Model;

	class Combat {

		public function answers($answers = false) {
			$return	= false;
			if ($answers) {
				shuffle($answers);
				foreach ($answers as $answer) {
					$return	.= '<input type="radio" name="answer_opt" id="opt_'.$answer['id'].'" value="'.$answer['id'].'" class="radio_answer_opt" /> '.$answer['vc_answer'].'<br />'.PHP_EOL;
				}
			}
			return $return;
		}

	}