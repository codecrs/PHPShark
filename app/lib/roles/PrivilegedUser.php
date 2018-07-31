<?php 
namespace core\roles {

    use core\lib\utilities as utils;

    class PrivilegedUser
    {

        private $roles;

        private $_roles;
        private $_permissions;
        private $_roles_permissions;
        private $_users_roles;
        private $_activity;
        private $_objects;

        public function __construct()
        {
            $this->_roles             = utils\config::get('role/roles');
            $this->_permissions       = utils\config::get('role/permissions');
            $this->_roles_permissions = utils\config::get('role/role_permissions');
            $this->_users_roles       = utils\config::get('role/user_roles');
            $this->_activity          = utils\config::get('role/activity');
            $this->_objects           = utils\config::get('role/objects');
        }

        public static function getByUsername($username)
        {
            $from = utils\Config::get('webadmin/userTable');
            $user_key = utils\Config::get('webadmin/userKey');
            $role_sel = \orm\Select::from($from)
                ->columns('*')->where("{$user_key} = {$username}")
                ->execute();

            if (!empty($role_sel)) {
                $privUser = new PrivilegedUser();
                $privUser->user_id = $role_sel[0][$user_key];
                $privUser->username = $username;
                $privUser->initRoles();
                return $privUser;
            } else {
                return false;
            }
        }

    // populate roles with their associated permissions
        protected function initRoles()
        {
            $this->roles = array();
            $userTable = utils\Config::get('webadmin/userTable');
            $userKey = utils\Config::get('webadmin/userKey');
            $result = \orm\Join::from($this->_users_roles, 't1')
                ->select('t1.role_id, t2.role_name')
                ->join($this->_roles, 't2')->match('t1.role_id = t2.role_id')
                ->where("t1.{$userKey} = {$this->user_id}")
                ->execute();

            if(!empty($result)){
                foreach ($result as $u_roles) {
                    $this->roles[$u_roles["role_name"]] = Role::getRolePerms($u_roles["role_id"]);
                } 
            }
        }

    // check if user has a specific privilege
        public function hasPrivilege($perm)
        {
            $result = array();
            foreach ($this->roles as $role) {
                foreach($role as $roleIndex){
                    if ($roleIndex['perm_obj'] === $perm ) {
                        $result['perm_obj'] = $roleIndex['perm_obj'];
                        $act = $roleIndex['perm_act'];
                        $result['perm_act'] = \orm\Select::from($this->_activity)->columns('activity_desc')
                                                         ->where("activity_id = {$act}")->execute();
    
                        $obj = $roleIndex['obj_type'];
                        $result['obj_type'] = \orm\Select::from($this->_objects)->columns('obj_type_desc')
                                                         ->where("type_id = {$obj}")->execute();
    
                        $result['value_range'] = self::processValueRange($roleIndex['value_range']);
                        return $result;
                    }
                }
            }
            return null;
        }

        private function processValueRange($range_arr){
            $return = array();
            $range_arr = explode(';',$range_arr);
            $range_arr = array_map('trim',$range_arr);
            $range_arr = array_clean($range_arr);
            foreach($range_arr as $arr){
                $cond = explode(':',$arr);
                $cond = array_map('trim',$cond);
                $return[$cond[0]] = $cond[1];
            }
            return $return;
        }

    }
}
