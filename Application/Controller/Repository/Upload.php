<?php
/************************************************************************************
* Name:				Upload Repository												*
* File:				Application\Controller\Repository\Upload.php 					*
* Author(s):		Vinas de Andrade												*
*																					*
* Description: 		This contains pre-written functions that execute upload related	*
*					actions. It was placed in the repository because it deals with	*
*					saving and retreiving information from the server.				*
*																					*
* Creation Date:	27/06/2013														*
* Version:			1.13.0627														*
* License:			http://www.opensource.org/licenses/bsd-license.php BSD			*
*************************************************************************************/

	namespace Application\Controller\Repository;

	use Application\Controller\Repository\dbFunctions;
	use SaSeed\General;

	class Upload {

		/*
		Faz upload das fotos de evento - Upload($image, $format)
			@param string	- Temp uploaded file path
			@param string	- image type 'image/jpeg', 'image/gif', 'image/png'
			@param array	- Image's final size
			@return format	- print to AJAX
		*/
		public function Upload($image = false, $type = false, $format = false) {
			$base_path				= VIEW_PATH."img/textures/";
			$return					= false;
			// Se valores foram enviados
			if (($image) && ($type) && ($format)) {
				// Define extensão do arquivo
				if ($type == 'image/jpeg') {
					$extension		= 'jpg';
				} else if ($type == 'image/gif') {
					$extension		= 'gif';
				} else if ($type == 'image/png') {
					$extension		= 'png';
				}
				// Faz upload da image
				$temp_path			= $base_path.$image;
				if (is_uploaded_file($image)) {
					// Formata foto
					$filename		= General::randomString(15).'.'.$extension;
					$res			= $this->formatPicture($image, $base_path, $filename, $extension, $format);
					$return			= ($res) ? $filename : false;
				}
			}
			return $return;
		}

		/*
		Formata e salva imagem de upload - formatPicture($temp_path, $base_path, $filename)
			@param string	- arquivo 
			@return format	- print to AJAX
		*/
		public function formatPicture($temp_path = false, $base_path = false, $filename = false, $extension = false, $format = false) {
			// Inicializa variáveis
			$return						= false;
			// Se valores foram eviados
			if (($temp_path) && ($base_path) && ($filename)  && ($extension) && is_array($format)) {
				// Pega tamanho original
				list($width, $height)	= getimagesize($temp_path);
				// Define tamanhos-padrão
				$final_width			= $format['int_width'];
				$final_height			= $format['int_height'];
				// Se a imagem for jpg
				if ($extension == 'jpg') {
					// Cria modelo virtual da imagem
					$image_model		= imagecreatefromjpeg($temp_path);
					// Resize na imagem
					$final_image		= imagecreatetruecolor($final_width, $final_height);
					$final_thumb		= imagecreatetruecolor(10, 10);
					// Create final images
					imagecopyresampled($final_image, $image_model, 0, 0, 0, 0, $final_width, $final_height, $width, $height);
					imagecopyresampled($final_thumb, $image_model, 0, 0, 0, 0, 10, 10, $width, $height);
					// Salva imagem e thumb
					$return				= imagejpeg($final_image, $base_path.$filename);
					imagejpeg($final_thumb, $base_path.'t_'.$filename);
				} else if ($extension == 'gif') {
					// Cria modelo virtual da imagem
					$image_model		= imagecreatefromgif($temp_path);
					// Resize na imagem
					$final_image		= imagecreatetruecolor($final_width, $final_height);
					$final_thumb		= imagecreatetruecolor(10, 10);
					// Imagem
					imagealphablending($final_image, false);
					imagesavealpha($final_image,true);
					$transparent		= imagecolorallocatealpha($final_image, 255, 255, 255, 127);
					imagefilledrectangle($final_image, 0, 0, $width, $height, $transparent);
					// Thumb
					imagealphablending($final_thumb, false);
					imagesavealpha($final_thumb,true);
					$transparent		= imagecolorallocatealpha($final_thumb, 255, 255, 255, 127);
					imagefilledrectangle($final_thumb, 0, 0, 10, 10, $transparent);
					// Create final images
					imagecopyresampled($final_image, $image_model, 0, 0, 0, 0, $final_width, $final_height, $width, $height);
					imagecopyresampled($final_thumb, $image_model, 0, 0, 0, 0, 10, 10, $width, $height);
					// Salva imagem e thumb
					$return				= imagegif($final_image, $base_path.$filename);
					imagegif($final_thumb, $base_path.'t_'.$filename);
				} else if ($extension == 'png') {
					// Cria modelo virtual da imagem
					$image_model		= imagecreatefrompng($temp_path);
					// Resize na imagem
					$final_image		= imagecreatetruecolor($final_width, $final_height);
					$final_thumb		= imagecreatetruecolor(10, 10);
					// Imagem
					imagealphablending($final_image, false);
					imagesavealpha($final_image,true);
					$transparent		= imagecolorallocatealpha($final_image, 255, 255, 255, 127);
					imagefilledrectangle($final_image, 0, 0, $width, $height, $transparent);
					// Thumb
					imagealphablending($final_thumb, false);
					imagesavealpha($final_thumb,true);
					$transparent		= imagecolorallocatealpha($final_thumb, 255, 255, 255, 127);
					imagefilledrectangle($final_thumb, 0, 0, 10, 10, $transparent);
					// Create final images
					imagecopyresampled($final_image, $image_model, 0, 0, 0, 0, $final_width, $final_height, $width, $height);
					imagecopyresampled($final_thumb, $image_model, 0, 0, 0, 0, 10, 10, $width, $height);
					// Salva imagem e thumb
					$return				= imagepng($final_image, $base_path.$filename);
					imagepng($final_thumb, $base_path.'t_'.$filename);
				}
				// Se a imagem foi salva
				if ($return) {
					// Apaga imagem temporária
					unlink($temp_path);
					// Prepara retorno
					$return				= true;
				}
			}
			// Retorno
			return $return;
		}
	
	}
