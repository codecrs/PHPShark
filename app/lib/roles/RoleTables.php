<?php 

namespace core\roles\tables{

	use core\lib\utilities as utils;
    class RoleTables{

        private $_userTable;
        private $_userTableKey;

        private $_roles;
        private $_permissions;
        private $_roles_permissions;
        private $_users_roles;
        private $_activity;
        private $_objects;

        public function __construct($userTable, $userTblKey){
            $this->_userTable = $userTable;
            $this->_userTableKey = $userTblKey;

            $this->_roles             = utils\Config::get('role/roles');
            $this->_permissions       = utils\Config::get('role/permissions');
            $this->_roles_permissions = utils\Config::get('role/role_permissions');
            $this->_users_roles       = utils\Config::get('role/user_roles');
            $this->_activity          = utils\Config::get('role/activity');
            $this->_objects           = utils\Config::get('role/objects');
        }

        public function addRoleTables(){
            $this->createBasicUserTable($this->_userTable, $this->_userTableKey);
            $this->createRoles();
            $this->createObjTypes();
            $this->createActivityTypes();
            $this->createPermissions();
            $this->createRolePermissions();
            //$this->createUserRoles();
        }

        private function createObjTypes(){
            if(!\orm\Query::is_table($this->_objects)){
                \orm\Table::create()
                ->table($this->_objects)
                ->field('type_id')->type('i(11)')->constraint('UNSIGNED NOT NULL')->auto_increment()
                ->field('obj_type_desc')->type('varchar(50)')->constraint('NOT NULL')
                ->primary('type_id')
                ->execute();
            }
        }

        private function createActivityTypes(){
            if(!\orm\Query::is_table($this->_activity)){
                \orm\Table::create()
                ->table($this->_activity)
                ->field('activity_id')->type('i(11)')->constraint('UNSIGNED NOT NULL')->auto_increment()
                ->field('activity_desc')->type('varchar(50)')->constraint('NOT NULL')
                ->primary('activity_id')
                ->execute();
            }
        }


        private function createBasicUserTable($uTable, $uTableKey){
            if(!\orm\Query::is_table($uTable)){
                \orm\Table::create()
                ->table($uTable)
                ->field($uTableKey)->type('i(11)')->constraint('UNSIGNED NOT NULL')->auto_increment()
                ->field('username')->type('varchar(255)')->constraint('NOT NULL')
                ->field('password')->type('varchar(255)')->constraint('NOT NULL')
                ->field('active')->type('char(1)')->constraint('NOT NULL')
                ->primary($uTableKey)
                ->execute();

                $this->createAdministrator();
            }
        }

        private function createAdministrator(){
            \orm\Query::insert()->into($uTable)
            ->values([
                'username' => 'administrator',
                'password' => hash_encrypt('admin'),
                'active'   => '1',
            ]);
        }

        private function createRoles(){
            if(!\orm\Query::is_table($this->_roles)){
                \orm\Table::create()
                ->table($this->_roles)
                ->field('role_id')->type('i(11)')->constraint('UNSIGNED NOT NULL')->auto_increment()
                ->field('role_name')->type('varchar(30)')->constraint('NOT NULL')
                ->primary('role_id')
                ->execute();
            }
        }
        
        private function createPermissions(){
            if(!\orm\Query::is_table($this->_permissions)){
                \orm\Table::create()
                ->table($this->_permissions)
                ->field('perm_id')->type('i(11)')->constraint('UNSIGNED NOT NULL')->auto_increment()
                ->field('perm_obj')->type('varchar(15)')->constraint('NOT NULL')
                ->field('perm_act')->type('i(11)')->constraint('UNSIGNED NOT NULL')
                ->field('obj_type')->type('i(11)')->constraint('UNSIGNED NOT NULL')
                ->field('perm_desc')->type('varchar(50)')->constraint('NOT NULL')
                ->field('value_range')->type('text')
                ->primary('perm_id')
                ->foreign('obj_type',"{$this->_objects}.type_id")
                ->foreign('perm_act',"{$this->_activity}.activity_id")
                ->execute();
            }
        }
        
        private function createRolePermissions(){
            if(!\orm\Query::is_table($this->_roles_permissions)){
                \orm\Table::create()
                ->table($this->_roles_permissions)
                ->field('role_id')->type('i(11)')->constraint('UNSIGNED NOT NULL')
                ->field('perm_id')->type('i(11)')->constraint('UNSIGNED NOT NULL')
                ->foreign('role_id',"{$this->_roles}.role_id")
                ->foreign('perm_id',"{$this->_permissions}.perm_id")
                ->execute();
            }
        }  

        // private function createUserRoles(){
        //     if(!\orm\Query::is_table($this->_users_roles)){
        //         \orm\Table::create()
        //         ->table($this->_users_roles)
        //         ->field($this->_userTableKey)->type('i(11)')->constraint('UNSIGNED NOT NULL')
        //         ->field('role_id')->type('i(11)')->constraint('UNSIGNED NOT NULL')
        //         ->foreign($this->_userTableKey,"{$this->_userTable}.{$this->_userTableKey}")
        //         ->foreign('role_id',"{$this->_roles}.role_id")
        //         ->execute();
        //     }
        // }  

    }
}