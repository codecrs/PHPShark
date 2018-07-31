<?php 
namespace core{
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

	//Technical Information 
	//Msg Class : D000
	use core\orm\mysql as mysql;
	use core\lib\files as files;
	use core\lib\imgs as imgs;
	use core\lib\errors as errs;
	use core\lib\forms as forms;
	use core\lib\utilities as utils;
	use core\lib\json as json;
	use app\lib\pages as pages;
	use \orm as orm;
	use \PDO;


	class Database
	{

		private $_db;
		private $_lid;
		protected $orm;
		/***************************************************************
Description: 
		 ***************************************************************/
		public function __construct()
		{
			try {
				$this->_db = new PDO(DNS(), utils\Config::get('database_credits/login'), utils\Config::get('database_credits/password'));
				$this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			} catch (\PDOException $e) {
				//Application Log
			}
		}

		public function __destruct()
		{
			$this->_db = null;
		}

		/***************************************************************
Description: Select Function 
		 ***************************************************************/
		protected function select(array $param)
		{
			orm\Query::checkKeywords('select', $param);
			orm\Query::checkSyntax('select', $param);
			$query = mysql\Select::setQuery($param);
			$ret = $this->query($query);
			return $ret;
		}
		/***************************************************************
Description: Insert Function 
		 ***************************************************************/
		//Insert
		protected function insert(array $param)
		{
			orm\Query::checkKeywords('insert', $param);
			orm\Query::checkSyntax('insert', $param);
			$this->checkCreateFields($param['into']);
			$query = mysql\Insert::setQuery($param);
			$ret = $this->activity($query);
			return $this->_lid;
		}
		/***************************************************************
Description: Delete Function 
		 ***************************************************************/
		//Delete
		protected function delete(array $param)
		{
			orm\Query::checkKeywords('delete', $param);
			orm\Query::checkSyntax('delete', $param);
			$query = mysql\Delete::setQuery($param);
			$this->_deletedRecored($param);
			$ret = $this->activity($query);
			return $ret;
		}
		/***************************************************************
Description: Update Function 
		 ***************************************************************/
		//Update
		protected function update(array $param)
		{
			orm\Query::checkKeywords('update', $param);
			orm\Query::checkSyntax('update', $param);
			$this->checkCreateFields($param['into']);
			$query = mysql\Update::setQuery($param);
			$ret = $this->activity($query);
			return $ret;
		}
		/***************************************************************
Description: Alter Function 
		 ***************************************************************/
		protected function alter(array $param)
		{
			orm\Query::checkKeywords('alter', $param);
			orm\Query::checkSyntax('alter', $param);
			$query = mysql\Alter::setQuery($param);
			$this->query($query);
		}
		/***************************************************************
Description: Join Function 
		 ***************************************************************/
		protected function table_join(array $declarion, array $joinSet, $querySet = '*', string $joinType = 'left', $extra = null)
		{
			$query = mysql\Join::setQuery($declarion, $joinSet,$querySet, $joinType, $extra);
			return $this->query($query);
		}
		/***************************************************************
Description: Write the native PDO query.
		 ***************************************************************/
		protected function query(srting $query)
		{
			try {
				$fetchMode = PDO::FETCH_ASSOC;
				$query = trim($query);
				$statement = $this->_db->prepare($query);
				if ($statement->execute()) {
					$result = $statement->fetchAll($fetchMode);
					return $result;
				}
			} catch (\PDOException $e) {
				writeDBLog($e, $query);
			}
		}
		/***************************************************************
Description: PDO function for Native Query 
DEMO (internal use) 
		 ***************************************************************/
		protected function activity(string $query)
		{
			try {
				$fetchMode = PDO::FETCH_ASSOC;
				$query = trim($query);
				$statement = $this->_db->prepare($query);
				$statement->execute();
				if ($this->_db->lastInsertId() != NULL) {
					$this->_lid = $this->_db->lastInsertId();
				}
			} catch (\PDOException $e) {
				writeDBLog($e, $query);
			}
		}
		/***************************************************************
Description: checkCreateFields($table) checks the integrity of the tables 
associated to the QMVC application.
It automatically introduces the audit field - modified,
created, modifiedBy and changedBy, 
if they are not already created in the tables. 
		 ***************************************************************/
		private function checkCreateFields(string $table)
		{
			if (!orm\Query::is_field($table, 'modified')) {
				$this->alter([
					'table' => $table,
					'field' => 'modified',
					'operation' => 'add',
					'type' => 'datetime',
					'null' => false,
				]);
			}

			if (!orm\Query::is_field($table, 'created')) {
				$this->alter([
					'table' => $table,
					'field' => 'created',
					'operation' => 'add',
					'type' => 'datetime',
					'null' => false,
				]);
			}

			if (!orm\Query::is_field($table, 'modifiedBy')) {
				$this->alter([
					'table' => $table,
					'field' => 'modifiedBy',
					'operation' => 'add',
					'type' => 'i(11)',
					'null' => false,
				]);
			}

			if (!orm\Query::is_field($table, 'createdBy')) {
				$this->alter([
					'table' => $table,
					'field' => 'createdBy',
					'operation' => 'add',
					'type' => 'i(11)',
					'null' => false,
				]);
			}
		}
		/***************************************************************
Description: This function deletes the record from the table. 
DEMO (internal use)
		 ***************************************************************/
		protected function _deletedRecored(string $table, array $param)
		{
			if (!empty($param['where'])) {
				$record = $this->select([
					'columns' => '*',
					'from' => $param['from'],
					'where' => $param['where']
				]);
				deleteLog('\DELETE:' . $param, $record);
			}
			else {
				$record = $this->select([
					'columns' => '*',
					'from' => $param['from']
				]);
				deleteLog('\DELETE:' . $param, $record);
			}
		}
	}
}