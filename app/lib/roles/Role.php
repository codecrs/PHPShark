<?php
namespace core\roles {
    use core\lib\utilities as utils;

    class Role
    {
        protected $permissions;

        private static $_roles;
        private static $_permissions;
        private static $_roles_permissions;
        private static $_users_roles;
        private static $_activity;
        private static $_objects;

        protected function __construct()
        {
            $this->permissions        = array();
            self::$_roles             = utils\Config::get('role/roles');
            self::$_permissions       = utils\Config::get('role/permissions');
            self::$_roles_permissions = utils\Config::get('role/role_permissions');
            self::$_users_roles       = utils\Config::get('role/user_roles');
            self::$_activity          = utils\Config::get('role/activity');
            self::$_objects           = utils\Config::get('role/objects');
        }

    // return a role object with associated permissions
        public static function getRolePerms($role_id)
        {
            $role = new Role();

            $role = \orm\Join::from(self::$_roles_permissions, 't1')
                ->select('t2.perm_obj, t2.perm_act, t2.obj_type, t2.value_range')
                ->join(self::$_permissions, 't2')->match('t1.perm_id = t2.perm_id')
                ->where("t1.role_id = {$role_id}")
                ->execute();
            return $role;
        }

    // check if a permission is set
        public function hasPerm($permission)
        {
            return isset($this->permissions[$permission]);
        }
    }
}