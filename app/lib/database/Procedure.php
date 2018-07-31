<?php 

namespace dbfetch{
    class procedure extends \PDO{

        private $_placeholders = '';
        private $_values = array;

		public function __construct(){
			try {
				$this->_db = new PDO(DNS(), utils\Config::get('database_credits/login'), utils\Config::get('database_credits/password'));
				$this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			} catch (\PDOException $e) {
                
			}
        }
        
        public function __destruct(){
			$this->_db = null;
        }

        public function bind_proc(array $parms){
            ksort($parms);
            foreach($parms as $key => $value){
                $this->_placeholders .= "?,";
                $this->_placeholders = rtrim($this->_placeholders,",");
                array_push($this->_values, $value);
            }
        }

        public function call(string $procedure_name){
            try{
                $stm = $this->prepare("CALL $procedure_name( $this->_placeholders )");
                $stm->execute($this->_values);
            }catch(Exception $e){
                //Application Error
            }
        }
    }
}