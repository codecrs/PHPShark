<?php 

namespace crud{
    use core\orm\mysql as mysql;
    use core\lib\files as files;
    use core\lib\imgs as imgs;
    use core\lib\errors as errs;
    use core\lib\forms as forms;
    use core\lib\utilities as utils;
    use core\lib\json as json;
    use core\lib\pages as pages;
    use \PDO as PDO;

    class DB extends \PDO{
        private $_query;
        private $_statement;
        private $_result;
        private $_database;
        private $_queryType;
        private $_insertArray = array();

        public function __construct(array $op = []){
            $this->_database = utils\Config::get('database_credits/database');
            if(!array_key_exists("buffered", $op))  $op["buffered"]  = false;
            if(!array_key_exists("stringify", $op)) $op["stringify"] = false;
            if(!array_key_exists("emulation", $op)) $op["emulation"] = true;
            //if(!array_key_exists("err_mode", $op))  $op["err_mode"]  = "warning";
            if(!array_key_exists("mode",     $op))  $op["mode"]      = PDO::FETCH_OBJ;
            
            // if(array_key_exists("err_mode", $op)){
            //     $op["err_mode"] = strtolower($op["err_mode"]);
            //     switch($op["err_mode"]){
            //         case 'warning': 
            //             $err_mode = PDO::ERRMODE_WARNING;
            //         default: 
            //             $err_mode = PDO::ATTR_ERRMODE;
            //     }
            // }

            try {
                parent::__construct(
                    DNS(),
                    utils\Config::get('database_credits/login'),
                    utils\Config::get('database_credits/password')
                );
                 //parent::setAttribute(PDO::ERRMODE_EXCEPTION, $err_mode); 
                 parent::setAttribute(PDO::ATTR_EMULATE_PREPARES, $op["emulation"]);
                 parent::setAttribute(PDO::ATTR_STRINGIFY_FETCHES, $op["stringify"]);
                 parent::setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, $op["buffered"]);
                 parent::setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, $op["mode"]);
                 parent::setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            } catch (PDOException $e) {
                writeDBLog($e);
            }
        }

        public function select(array $columns = []){
            $this->_query = "";
            $this->_queryType = "select";
            if(!empty($columns)){
                $col = "`".implode("`,`", $columns)."`";
            }else{
                $col = "*";
            }
            $this->_query = "SELECT {$col} ";
            return $this;
        }

        public function top(int $number){
            $this->_query = "";
            $this->_queryType = "select";
            $this->_query = "SELECT TOP {$number} * ";
            return $this;
        }

        public function distinct(array $columns){
            $this->_query = "";
            $this->_queryType = "select";
            $col = join(",", $columns);
            $this->_query = "SELECT DISTINCT {$col} ";
            return $this;
        }
        
         public function count_distinct(array $columns){
            $this->_query = "";
            $this->_queryType = "select";
            $col = join(",", $columns);
            $this->_query = "SELECT COUNT (DISTINCT {$col}) ";
            return $this;
        }

        public function function(string $func, string $column){
            $this->_query = "";
            $this->_queryType = "select";
            $this->_query = "SELECT {$func}({$column}) ";
            return $this;
        }

        public function from(string $table){
            $this->_query .= "FROM `{$this->_database}`.`{$table}` ";
            return $this;
        }

        public function where(string $exp, string $null = ""){
            $explode = explode(" ",$exp);
            $explode[0] = "`{$explode[0]}`";
            $explode[1] = $explode[1];
            $explode[2] = ":{$explode[2]}";  
            if($null !== ""){
                if(is_null($null)){
                    $this->_query .= "WHERE {$explode[0]} {$explode[1]} {$explode[2]} IS NULL";
                }

                if(strToLower($null) == "no"){
                    $this->_query .= "WHERE {$explode[0]} {$explode[1]} {$explode[2]}IS NOT NULL";
                }
            }else{
                $this->_query .= "WHERE {$explode[0]} {$explode[1]} {$explode[2]} ";
            }        
            return $this;
        }

        public function whereNot(string $exp){
            $explode = explode(" ",$exp);
            $explode[0] = "`{$explode[0]}`";
            $explode[1] = $explode[1];
            $explode[2] = ":{$explode[2]}";          
            $this->_query .= "WHERE NOT {$explode[0]} {$explode[1]} {$explode[2]} ";
            return $this;
        }
        
        public function and(string $exp){
            $explode = explode(" ",$exp);
            $explode[0] = "`{$explode[0]}`";
            $explode[1] = $explode[1];
            $explode[2] = ":{$explode[2]}";          
            $this->_query .= "AND {$explode[0]} {$explode[1]} {$explode[2]} ";
            return $this;
        }

        public function or(string $exp){
            $explode = explode(" ",$exp);
            $explode[0] = "`{$explode[0]}`";
            $explode[1] = $explode[1];
            $explode[2] = ":{$explode[2]}";           
            $this->_query .= "OR {$explode[0]} {$explode[1]} {$explode[2]} ";
            return $this;
        }

        public function orderBy($columns, $progress = "ASC"){
            if(is_array($columns)){
                $col = join(",",$columns);
            }
            $this->_query .= "ORDER BY {$col} {$progress} ";
            return $this;
        }

        public function groupBy($columns, $progress = "ASC"){
            if(is_array($columns)){
                $col = join(",",$columns);
            }
            $this->_query .= "GROUP BY {$col} {$progress} ";
            return $this;
        }

        public function limit(int $limit){
            $this->_query .= "LIMIT {$limit} ";
            return $this;
        }


        public function offset(int $offset){
            $this->_query .= "OFFSET {$offset} ";
            return $this;
        }

        public function x($opt = null){
            $this->_statement = $this->prepare(trim($this->_query));
            return $this->_statement;
        }

        //Common Logic - Value
        public function value(string $param, $value, $type = null){
            $php_true = true;
            if(is_null($type)){
                switch(true){
                    case is_int($value):
                        $type = PDO::PARAM_INT;
                        break;
                    case is_bool($value):   
                        $type = PDO::PARAM_BOOL;
                        break;
                    case is_null($value):
                        $type = PDO::PARAM_NULL;
                        break;
                    default:
                        $type = PDO::PARAM_STR;
                }
            }
            $param = ":{$param}";
            if(!$this->_statement->bindValue($param, $value, $type)){
                $php_true = false;
            }

            if($php_true === false){
                throw new \Exception("Value entry encountered an error. Please re-check table: :{$para} = {$value}");
            }
        }

        //Common Logic - Parameter
        public function parameter(string $param, $value, $type = null){
            try{
                $php_true = true;
                if(is_null($type)){
                    switch(true){
                        case is_int($value):
                            $type = PDO::PARAM_INT;
                            break;
                        case is_bool($value):   
                            $type = PDO::PARAM_BOOL;
                            break;
                        case is_null($value):
                            $type = PDO::PARAM_NULL;
                            break;
                        default:
                            $type = PDO::PARAM_STR;
                    }
                }
                $param = ":{$param}";
                if(!$this->_statement->bindParam($param, $value, $type)){
                    $php_true = false;
                }

                if($php_true === false){
                    throw new \Exception("Parameter entry encountered an error. Please re-check table: :{$para} = {$value}");
                }
            }catch(Exception $e){
                writeDBLog($e, $this->_result);
            }
        }

        public function fetch(array $opt = []){
            if(array_key_exists("buffer",$opt)){
                $buffer = $opt["buffer"];
            }else{
                $buffer = "false";
            }
              
            parent::setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, $buffer);
            if(array_key_exists("fetch",$opt)){
                $fetch = $opt["fetch"];
                switch ($fetch) {
                    case 'number':
                         $fetchSet = PDO::FETCH_NUM;
                         break;
                    case 'both':
                         $fetchSet = PDO::FETCH_BOTH;
                         break;
                    case 'object':
                         $fetchSet = PDO::FETCH_OBJ;
                         break;
                    case 'lazy':
                         $fetchSet = PDO::FETCH_LAZY;
                         break;
                    case 'pair':
                         $fetchSet = PDO::FETCH_KEY_PAIR;
                         break;
                    case 'group':
                         $fetchSet = PDO::FETCH_GROUP;
                         break;
                    case 'unique':
                         $fetchSet = PDO::FETCH_UNIQUE;
                         break;
                    case 'assoc':
                         $fetchSet = PDO::FETCH_ASSOC;
                         break;
                    default:
                         $fetchSet = PDO::FETCH_OBJ;
                }
            }else{
                $fetchSet = PDO::FETCH_OBJ;
            }

            if(array_key_exists("function",$opt)){
                $function = $opt["function"];
            }else{
                $function = "fetchAll";
            }
            switch($this->_queryType){
                case "insert":
                    try {
                        if($this->_statement->execute()) {
                            return $this->lastInsertId(); 
                        }else{
                            return false;
                        }
                        return $this->_result;
                    } catch (PDOException $e) {
                        writeDBLog($e, $this->_result);
                    }
                break;
                case "delete":
                    try {
                        if ($this->_statement->execute()) {
                            return true;
                        }
                    } catch (PDOException $e) {
                        writeDBLog($e, $this->_result);
                    }
                break;
                case "update":
                    try {
                        if ($this->_statement->execute()) {
                            return true;
                        }
                    } catch (PDOException $e) {
                        writeDBLog($e, $this->_result);
                    }
                break;
                default:
                    try {
                        if ($this->_statement->execute()) {
                            if(array_key_exists("class",$opt)){ 
                                $this->_result = $this->_statement->{$function}(PDO::FETCH_CLASS, $opt["class"]);
                            } else{ 
                                $this->_result = $this->_statement->{$function}($fetchSet); 
                            }
                        }else{
                            return false;
                        }
                        return $this->_result;
                    } catch (PDOException $e) {
                        writeDBLog($e, $this->_result);
                    }
            }
        }

        public function dbCount(){
            if(isset($this->_result))
                return count($this->_result);
        }

        public function insert(string $into){
            $this->_query = "";
            $this->_queryType = "insert";
            $this->_query = "INSERT INTO `{$this->_database}`.`{$into}` ";
            return $this;
        }

        public function values(array $column){
            $field = "`".implode("`,`",$column)."`";
            $value = ":".implode(", :",$column);
            $this->_query .= "( {$field} ) VALUES ( {$value} ) ";
            return $this;
        }

        public function update(string $table){
            $this->_query = "";
            $this->_queryType = "update";
            $this->_query = "UPDATE `{$this->_database}`.`{$table}` ";
            return $this;
        }

        public function set(array $column){
           $fieldset = "";
           foreach($column as $field){
                $fieldset .= "`{$field}` = :{$field}, ";
           }
           $fieldset = rtrim($fieldset,", ");
           $this->_query .= "SET {$fieldset} ";
           return $this;
        }

        public function delete(string $from){
            $this->_query = "";
            $this->_queryType = "delete";
            $this->_query = "DELETE FROM `{$this->_database}`.`{$from}` ";
            return $this;
        }

        public function xinsert(string $into, array $rowset){
            $this->_insertArray = [];
            $field = implode("`,`",array_keys($rowset));
            $values = implode("','",array_values($rowset));
            $l_query = "INSERT INTO `{$this->_database}`.`{$into}` ( {$field} ) VALUES ( {$values} )";
            array_push($this->_insertArray,$l_query);
            return $this;
        }

        public function commit(){
            try{
                $this->beginTransaction();
                foreach($this->_insertArray as $insert){
                    $this->exec($insert);
                }
                $this->commit();
                return true;
            }catch(PDOException $e){
                // roll back the transaction if something failed
                $this->rollback();
                writeDBLog($e, $this->_result);
            }
        }

        public function get($table, Array $col = [], $conditions = null){
            if(empty($col)){
                $q = "SELECT * FROM {$table}";
            }else{
                $cols  = implode("`,`",$col);
                $q = "SELECT `{$cols}` FROM `{$this->_database}`.`{$table}`";
            }

            if(!is_null($conditions)){
                $q .= " {$conditions}";
            }
            $stm = $this->prepare(trim($q));
            try{
                if($stm->execute()){
                    return $stm->fetchAll(PDO::FETCH_OBJ);
                }else{
                    return null;
                }
            }catch(PDOException $e){
                // roll back the transaction if something failed
                writeDBLog($e, $this->_result);
            }
        }

    }
}
