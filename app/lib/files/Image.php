<?php
namespace core\lib\imgs {
	if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 *  Qmvc - Quick PHP MVC 
 *  Developed by Contempative Radical Solutions Consulting Private Ltd. 
 *  Project Initiative By Ankit Kumar
 *  http://www.contemplativeradicals.com
 *  
 *  @copyright  Contempative Radical Solutions Consulting Private Ltd. 
 *  @link
 *  @since      1.0.0
 *  @license
 *  
 * Change Logs
 * *******************************************************************
 * 
 * *******************************************************************
 **/


/***************************************************************/

	//----------------------------------------------------------------------------------------------------------------------------------------//
	// Description
	//----------------------------------------------------------------------------------------------------------------------------------------//
	// Access the $_FILES global variable for this specific file being uploaded
	// and create local PHP variables from the $_FILES array of information

	// The file name
	// File in the PHP tmp folder
	// The type of file it is
	// File size in bytes
	// 0 = false | 1 = true

	// Split file name into an array using the dot
	// Now target the last array element to get the file extension
	// removed for use when required:  $fileExt = end($spit_file_name); 	
	// Place it into your "uploads" folder mow using the move_uploaded_file() function

	// START PHP Image Upload Error Handling --------------------------------------------------
	// if file not chosen
	// if file size is larger than 5 Megabytes
	// Remove the uploaded file from the PHP temp folder
	// This condition is only if you wish to allow uploading of specific file types 
	// Remove the uploaded file from the PHP temp folder
	// if file upload error key is equal to 1
	// END PHP Image Upload Error Handling ----------------------------------------------------
	// For Later Use:
	// imagecopyresampled(dst_img, src_img, dst_x, dst_y, src_x, src_y, dst_w, dst_h, src_w, src_h)
	use core\lib\files as files;
	use core\lib\errors as errs;
	use core\lib\forms as forms;
	use core\lib\utilities as utils;
	use core\lib\json as json;
	use core\lib\pages as pages;

	class Image extends files\Files
	{
		/**
		 * Private Variables 
		 **/

		private $moveResult;
		private $target_file;
		private $resized_file;
		private $thumbnail;
		private $_fileTmpLoc;
		private $original_folder;
		private $_fileExt;
		/***************************************************************
		 ***************************************************************/
		/**
		 * Public:: File Constructor  
		 **/

		public function __construct()
		{

		}

		/***************************************************************
		 ***************************************************************/
		/**
		 * Public:: Put the file to the desired locations
		 * @param  string  $location   File path 
		 *				  Place it into your "uploads" folder mow using the move_uploaded_file() function
		 * @return boolean true/false    
		 **/

		protected function putFile($location)
		{
			$this->moveResult = move_uploaded_file($this->_fileTmpLoc, $location);
			if ($this->moveResult != true) {
				// Do some Error related Action

			}
			else {
				$this->validateFile();
			}

		}
		/***************************************************************
		 ***************************************************************/
		/**
		 * Public:: File Validation and Error Msgs.
		 * @param  string   $path          File path 
		 * @return boolean true/false       
		 **/

		public function validateFile()
		{
			return true;
		}
		/***************************************************************
		 ***************************************************************/
		/**
		 * Public:: Image Upload - Puts the file into the file location
		 *		   Set $this->target_original into the configuration file
		 * @return boolean true/false  
		 **/

		public function imgUpload()
		{
			try {
				$this->putFile($this->target_original);
				return true;
			} catch (Exception $e) {
				return $e;
			}
		}
		/***************************************************************
		 ***************************************************************/
		/**
		 * Public:: File Delete - Deletes the file from the location.
		 * @param  string $$fileToDelete   File path  
		 * @return boolean true/false  
		 **/

		public function fileDelete($fileToDelete)
		{
			if (file_exists($fileToDelete)) {
				if (!unlink($fileToDelete)) {
					return false;
				}
				else {
					return true;
				}
			}
			return false;
		}
		/***************************************************************
		 ***************************************************************/
		/**
		 * Public:: Resize Image File. 
		 * @param  int	  $w	  Image Maximum Width  
		 * @param  int	  $h	  Image Maximum Height
		 * @param  boolean $state  Upload original file or not. (Example & Default = false)
		 * @return mixed Error Msg. or
		 * @return boolean true. 
		 **/

		public function imgResize($w, $h, $state = false)
		{
			if ($state == true) {
				$this->putFile($this->target_original);
			}
			else {
				$this->putFile($this->target_file);
			}

			if ($state == true) {
				$target = $this->target_original;
			}
			else {
				$target = $this->target_file;
			}

			try {
				$newcopy = $this->resized_file;
				$ext = $this->_fileExt;
				$this->reCreateFile($target, $newcopy, $w, $h, $this->_fileExt, 'resize');
				$this->saveOriginal(false);
				return true;
			} catch (Exception $e) {
				return $e;
			}
		}

		/***************************************************************
		 ***************************************************************/
		/**
		 * Public:: Crop Image File. 
		 * @param  int	  $w	  Image Maximum Width  
		 * @param  int	  $h	  Image Maximum Height
		 * @param  boolean $state  Upload original file or not. (Example & Default = false)
		 * @return mixed Error Msg. or
		 * @return boolean true. 
		 **/

		public function imgCrop($w, $h, $state = false)
		{
			if ($state == true) {
				$this->putFile($this->target_original);
			}
			else {
				$this->putFile($this->target_file);
			}

			if ($state == true) {
				$target = $this->target_original;
			}
			else {
				$target = $this->target_file;
			}
			try {
				$newcopy = $this->thumbnail;
				$ext = $this->_fileExt;
				$this->reCreateFile($target, $newcopy, $w, $h, $this->_fileExt, 'crop');
				$this->saveOriginal(false);
			} catch (Exception $e) {
				return $e;
			}
		}
		/***************************************************************
		 ***************************************************************/
		/**
		 * Public:: Crop Image File. 
		 * @param  int	  $w	  Image Maximum Width  
		 * @param  int	  $h	  Image Maximum Height
		 * @param  boolean $state  Upload original file or not. (Example & Default = false)
		 * @return mixed Error Msg. or
		 * @return boolean true. 
		 **/

		public function reCreateFile($target_file, $resized_file, $w, $h, $ext, $activity = null)
		{
			list($w_orig, $h_orig) = getimagesize($target_file);
			if ($activity == 'resize') {
				$scale_ratio = $w_orig / $h_orig;
				if ( ($w / $h) > $scale_ratio) {
					$w = $h * $scale_ratio;
				}
				else {
					$h = $w / $scale_ratio;
				}
			}

			if ($activity == 'crop') {
				$src_x = ($w_orig / 2) - ($w / 2);
				$src_y = ($h_orig / 2) - ($h / 2);
			}

			$img = "";
			$ext = strtolower($ext);
			if ($ext == "gif") {
				$img = imagecreatefromgif($target_file);
			}
			else if ($ext == "png") {
				$img = imagecreatefrompng($target_file);
			}
			else {
				$img = imagecreatefromjpeg($target_file);
			}

			$tci = imagecreatetruecolor($w, $h);
			if ($activity == 'resize') {
				imagecopyresampled($tci, $img, 0, 0, 0, 0, $w, $h, $w_orig, $h_orig);
			}

			if ($activity == 'crop') {
				imagecopyresampled($tci, $img, 0, 0, $src_x, $src_y, $w, $h, $w, $h);
			}

			if ($ext == "gif") {
				imagegif($tci, $resized_file);
			}
			else if ($ext == "png") {
				imagepng($tci, $resized_file);
			}
			else {
				imagejpeg($tci, $resized_file, 84);
			}

			return true;
		}
		/***************************************************************
		 ***************************************************************/
		/**
		 * Public:: Crop Image File. 
		 * @param  boolean $state  Upload original file or not. (Example & Default = false)
		 * @return mixed Error Msg. or
		 * @return boolean true. 
		 **/
		private function saveOriginal($state = true)
		{
			if ($state == false) {
				$this->fileDelete($this->target_file);
			}
			return true;
		}
		/***************************************************************
		 ***************************************************************/
		/**
		 * public:: Remove the temporary file.
		 * @param  string  $path   File path  
		 * @return boolean true/false    
		 **/

		public function removeTempFile($path)
		{
			// Remove the uploaded file from the PHP temp folder
			if (file_exists($path)) {
				unlink($path);
			}
			return true;
		}
		/***************************************************************
		 ***************************************************************/
		/*
		 *   Contruct the Bootstrap.
		 *   @return boolen|String
		 */
		public function confirmMsg()
		{
			$this->removeTempFile($this->_fileTmpLoc, $target = null);
		}

		public function convert_to_jpg()
		{
			if($target !== null) $target = $this->target_file;
			$newcopy = 'conv_' . $this->target_file;
			$ext = $this->_fileExt;

			list($w_orig, $h_orig) = getimagesize($target);
			$ext = strtolower($ext);
			$img = "";

			if ($ext == "gif") {
				$img = imagecreatefromgif($target);
			}
			else if ($ext == "png") {
				$img = imagecreatefrompng($target);
			}

			$tci = imagecreatetruecolor($w_orig, $h_orig);
			imagecopyresampled($tci, $img, 0, 0, 0, 0, $w_orig, $h_orig, $w_orig, $h_orig);
			imagejpeg($tci, $newcopy, 84);
		}

		/***************************************************************
		 ***************************************************************/
		/*
		 *   Contruct the Bootstrap.
		 *   @return boolen|String
		 */
		public function imgWatermark($wtrmrk_file,$target == null)
		{

			if($target !== null) $target = $this->target_file;
			$newcopy = 'conv_' . $this->target_file;
			$watermark = imagecreatefrompng($wtrmrk_file);

			imagealphablending($watermark, false);
			imagesavealpha($watermark, true);
			$img = imagecreatefromjpeg($target);
			$img_w = imagesx($img);
			$img_h = imagesy($img);
			$wtrmrk_w = imagesx($watermark);
			$wtrmrk_h = imagesy($watermark);
			$dst_x = ($img_w / 2) - ($wtrmrk_w / 2); // For centering the watermark on any image
			$dst_y = ($img_h / 2) - ($wtrmrk_h / 2); // For centering the watermark on any image
			imagecopy($img, $watermark, $dst_x, $dst_y, 0, 0, $wtrmrk_w, $wtrmrk_h);
			imagejpeg($img, $newcopy, 100);
			imagedestroy($img);
			imagedestroy($watermark);
		} 


		//$this->target_original
		//$this->resized_file
		//$this->_fileExt

	}
}
