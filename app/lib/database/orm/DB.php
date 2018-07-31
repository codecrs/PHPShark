<?php
namespace orm {
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

	use core\orm\mysql as mysql;
	use core\lib\files as files;
	use core\lib\imgs as imgs;
	use core\lib\errors as errs;
	use core\lib\forms as forms;
	use core\lib\utilities as utils;
	use core\lib\json as json;
	use core\lib\pages as pages;
	use \PDO;


	class DB
	{

		private $_dbh;
		private $_name;
		private $_host;
		private $_root;
		private $_root_password;
		private $_user;
		private $_pass;
		private $_dbtype;
		public  $db;

		/***************************************************************
		 ***************************************************************/
		public static function create(string $name, array $opt)
		{
			try {
				self::$_name = $name;

				if (!empty($opt)) {
					isset($opt['host']) ? self::$_host = $opt['host']
						: self::$_host = utils\Config::get('database_credits/host');
					isset($opt['root']) ? self::$_root = $opt['root']
						: self::$_root = utils\Config::get('database_credits/login');
					isset($opt['root_pass']) ? self::$_root_password = $opt['root_pass']
						: self::$_root_password = utils\Config::get('database_credits/password');
					isset($opt['user']) ? self::$_user = $opt['user']
						: self::$_user = '';
					isset($opt['user_pass']) ? self::$_pass = $opt['user_pass']
						: self::$_pass = '';
					isset($opt['db_type']) ? self::$_dbtype = $opt['db_type']
						: self::$_dbtype = utils\Config::get('database_credits/datasource');
				}

				$__dbtype = self::$_dbtype;
				$__host   = self::$_host;
				$__name   = self::$_name;
				$__user   = self::$_user;
				$__pass   = self::$_pass;

				self::$_dbh = new PDO(
					"{$__dbtype}:host={$__host}",
					  self::$_root, self::$_root_password);

				self::$_dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				self::$_dbh->exec("CREATE DATABASE IF NOT EXISTS `{}`;
							CREATE USER ' {$__user}'@'localhost' IDENTIFIED BY '{$__pass}';
							GRANT ALL ON `{$__name}`.* TO '{$__user}'@'{$__host}';
							FLUSH PRIVILEGES;")
				or die(print_r($dbh->errorInfo(), true));
				return true;
			} catch (PDOException $e) {
				//DB Log
				//die("DB ERROR: ". $e->getMessage());
				return false;
			}
		}

		/***************************************************************
		 ***************************************************************/
		public static function connect(array $opt)
		{
			try {
				if (!empty($opt)) {
					isset($opt['host']) ? self::$_root_password_host = $opt['host']
						: self::$_host = utils\Config::get('database_credits/host');
					isset($opt['root']) ? self::$_root = $opt['root']
						: self::$_root = utils\Config::get('database_credits/login');
					isset($opt['root_pass']) ? self::$_root_password = $opt['root_pass']
						: self::$_root_password = utils\Config::get('database_credits/password');
					isset($opt['user']) ? self::$_user = $opt['user']
						: self::$_user = '';
					isset($opt['user_pass']) ? self::$_pass = $opt['user_pass']
						: self::$_pass = '';
					isset($opt['db_type']) ? self::$_dbtype = $opt['db_type']
						: self::$_dbtype = utils\Config::get('database_credits/datasource');
					isset($opt['db']) ? self::$_name = $opt['db']
						: self::$_name = utils\Config::get('database_credits/database');
				}

				$__dbtype = self::$_dbtype;
				$__host   = self::$_host;
				$__name   = self::$_name;
				$__user   = self::$_user;
				$__pass   = self::$_pass;

				self::$db = new PDO(
					"{$__dbtype}:host={$__host};dbname={$__name}",
					self::$_root,
					self::$_root_password
				);
				self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				return self;
			} catch (PDOException $e) {
				//DB Log
				//die("DB ERROR: ". $e->getMessage());
			}
		}
	}
}