<?php 

namespace core\roles{
    class Query{
        private static $_roles;
        private static $_permissions;
        private static $_roles_permissions;
        private static $_users_roles;
        private static $_activity;
        private static $_objects;

        private  function __construct(){
            self::$_roles             = utils\Config::get('role/roles');
            self::$_permissions       = utils\Config::get('role/permissions');
            self::$_roles_permissions = utils\Config::get('role/role_permissions');
            self::$_users_roles       = utils\Config::get('role/user_roles');
            self::$_activity          = utils\Config::get('role/activity');
            self::$_objects           = utils\Config::get('role/objects');
        }

        public static function insertRole($role_name){
            return \orm\Insert::into(self::$_roles)
            ->values([
                'role_name' => $role_name,
            ]);
        }

        // insert array of roles for specified user id
        public static function insertUserRoles($user_id, $roles) {
            return \orm\Insert::into(self::$_users_roles)
            ->values([
                'user_id' => $user_id,
                'role_id' => $roles,
            ]);
        }

        public static function deleteRoles($roles) {
            \orm\Join::from(self::$_roles,'t1')
            ->delete('t1, t2, t3')
            ->join('user_role','t2')->on('t1.role_id = t2.role_id')
            ->join('role_perm','t3')->on('t1.role_id = t3.role_id')
            ->where("t1.role_id = {$roles}")->execute();

            return true;
        }

        // delete ALL roles for specified user id   
        public static function deleteUserRoles($user_id) {
            $user_key = utils\Config::get('webadmin/userKey');
            \orm\Delete::from(self::$_users_roles)
            ->where("{$user_key} = {$user_id}");
        }

        // insert a new role permission association
        public static function insertPermission($role_id, $perm_id) {
            return \orm\Insert::into(self::$_roles_permissions)
                    ->values([
                        'role_id' => $role_id,
                        'perm_id' => $perm_id 
                    ]);
        }

        // delete ALL role permissions
        public static function deletePermission() {
            \orm\Truncate::table(self::$_roles_permissions)
            ->delete()->execute();
        }

    }
}