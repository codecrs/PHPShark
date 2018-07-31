<?php 
namespace core\lib\files {
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

	use core\lib\imgs as imgs;
	use core\lib\errors as errs;
	use core\lib\forms as forms;
	use core\lib\utilities as utils;
	use core\lib\json as json;
	use core\lib\pages as pages;

	class Upload extends Files
	{

		private $_uploaded_file;
		/***************************************************************
         ***************************************************************/
		public function __construct()
		{
			parent::__construct();
		}
		/***************************************************************
         ***************************************************************/
		public function x($opt = array())
		{
			$upload = "1";
			$msg_log = array();
			$success_log = array();
			
			//var_dump(sys_get_temp_dir());
			$keywords = array('attribute-name', 'location', 'size-limit', 'allowed-type');
			if (!array_key_exists('attribute-name', $opt) == true && $opt[0] = 'attribute-name') {
				echo 'Mandatory attributes not provided to the function';
				exit;
			}
			if (!array_key_exists('location', $opt) == true && $opt[1] = 'location') {
				echo 'Mandatory attributes not provided to the function';
				exit;
			} 

			// Set Upload Location/ $taget Directory
			if (!isset($opt['location']) && $opt['location'] == '') {
				$target_dir = PUBLIC_PATH;
			}
			else {
				$target_dir = $opt['location'];
			}
			if ($target_dir !== '') {
				$fileSize = $this->fileSize($opt['attribute-name']);
				$isImage = $this->isImage($opt['attribute-name']);
				$fileType = $this->fileType($opt['location'], $opt['attribute-name']);
				$filename = $this->fileName($opt['attribute-name']);

				// Set Size Limit 
				if (isset($opt['size-limit'])) {
					if ($this->fileSize($opt['attribute-name']) > $opt['size-limit']) {
						$upload = "0";
						$msg_log['number'] = '2';
						$msg_log['text'] = 'File size exceeding the Limit ' . $opt['size-limit'];
						$msg_log['analysis'] = 'current File size ' . $this->fileSize($opt['attribute-name']);
					}
				}

				// Do not upload if exist or replace
				if (isset($opt['duplicate'])) {
					if ($opt['duplicate'] == 'skip') {
						$upload = "0";
						$msg_log['number'] = '5';
						$msg_log['text'] = 'File already exist in the system.';
						$msg_log['analysis'] = 'use skip option to replace, just incase';
					}
					if ($opt['duplicate'] == 'replace') {}
				}

				//Check file types
				if (isset($opt['allowed-type'])) {
					if (is_array($opt['allowed-type'])) {
						$arr = $opt['allowed-type'];
						if (!in_array($fileType, $arr)) {
							$upload = "0";
							$msg_log['number'] = '4';
							$msg_log['text'] = 'File upload failed due to invalid type';
							$msg_log['analysis'] = 'Upload a valid ' . $fileType . ' File.';
						}
					}else {
						$upload = "0";
						$msg_log['number'] = '3';
						$msg_log['text'] = 'Invalid definition of invalid allowed types.';
						$msg_log['analysis'] = 'Use array definition or refer to the manual.';
					}
				}
				if ($upload == "1") {
					// Everything is OK, Now Upload the File.
					$this->uploadThisFile($target_dir, $opt['attribute-name']);
					//If encryption is true, rename the uploaded file.
					if (isset($opt['encrypt']) && is_bool(isset($opt['encrypt']))) {
						if ($opt['encrypt'] == true) {
							$hash_name = hash_token();
							$this->fileRename($opt['location'], $filename, $hash_name . '.' . $fileType);
						}
					}

					$success_log['number'] = '0';
					if(isset($filename))
					$success_log['name'] = $filename;
					$success_log['text'] = 'File uploaded successfully.';
					if(isset($fileSize))
					$success_log['size'] = $fileSize;
					if(isset($fileType))
					$success_log['type'] = $fileType;
					if(isset($isImage))
					$success_log['image'] = $isImage;
					if(isset($target_dir))
					$success_log['location'] = $target_dir;
					if(isset($hash_name))
					$success_log['encrypted_name'] = $hash_name;
					return $success_log;
				}
				else {
					return $msg_log;
				}
			}
			else {
				$msg_log['number'] = '1';
				$msg_log['text'] = 'File location not set properly.';
				$msg_log['analysis'] = 'current File size ' . $this->fileSize($opt['attribute-name']);
			}
		}
		/***************************************************************
         ***************************************************************/
		private function uploadThisFile($target_dir, $formUploadNameAttr)
		{
			$this->fileMode($target_dir, 0777);
			$target_file = $target_dir . $this->fileName($formUploadNameAttr);
			$t = $this->fileUploadToServer($formUploadNameAttr, $target_file);
			$this->fileMode($target_dir, 0755);
		}

	}
}