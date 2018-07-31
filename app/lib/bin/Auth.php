<?php 
use core\lib\utilities as utils;
//=======================================
// AUTHORIZATION TABLE DOCUMENTATION
//=======================================
//Table Refresh Query
// SET FOREIGN_KEY_CHECKS = 0;
// TRUNCATE table `system_users`;
// SET FOREIGN_KEY_CHECKS = 1
//=======================================
// auth_activities
//=======================================
//=======================================
// The Table Defines the Application Activities that user performs
// These are generally the set of values Related to CRUD.
//=======================================
//=======================================
// activity_id|| activity_desc
//=======================================
// 1 || CREATE
// 2 || UPDATE
// 3 || INSERT
// 4 || DELETE
//=======================================
// auth_obj_type
//=======================================
//=======================================
// The Table Define the Application Elements that user are authorised to see
// These are generally the set of screen elements or sections 
//=======================================
//=======================================
// type_id|| obj_type_desc
//=======================================
// 1 || TABLE 
// 2 || FIELD-NAME
//=======================================
// auth_permissions
//=======================================
//=======================================
// The Table Define the Permissions of the user.
//=======================================
//=======================================
// perm_id || perm_obj || perm_act || obj_type || perm_desc || value_range
//=======================================
// 1 || READ_USER_TABLE || 1 || 1 || Read User Table || 
// 1 || EDIT_USER_TABLE || 1 || 2 || Edit User Table || 
//=======================================
// auth_role_permissions
//=======================================
//=======================================
// The Table Define the permission mapping with the user
//=======================================
//=======================================
// role_id || perm_id
//=======================================
// 1 || 1
// 1 || 1
//=======================================
// auth_roles
//=======================================
//=======================================
// The Table Define the Roles Description of the user
//=======================================
//=======================================
// role_id || role_name
//=======================================
// 1 || Administrator
// 2 || Manager
// 3 || Dealer
//=======================================
// auth_user_roles
//=======================================
//=======================================
// The Table Define the User to Role Mapping
//=======================================
//=======================================
// sys_user_id || role_id
//=======================================
// 1 || 1
// 2 || 1
// 3 || 2
//=======================================
// system_users
//=======================================
//=======================================
// The System Defined User Table Meant for the Application Backend User
//=======================================
//=======================================
// sys_user_id || name || username || password || role_id
//=======================================
// 1 || Ankit Kumar || mail2ankit85@gmail.com || [LONG ENCRYPTED HASS PASSWORD] || 1
//=======================================
// Application Check by Object auth_object_for_user($obj, $key = null)
//=======================================
function auth_object_for_user(sting $obj, string $key = null){
    $user_key = utils\Config::get('webadmin/userKey');
    if (isset($_SESSION[$user_key])) {
        $u = core\roles\PrivilegedUser::getByUsername($_SESSION[$user_key]);
        $obj = $u->hasPrivilege($obj);

        if ($key !== null) {
            switch (strtolower($key)) {
                case 'range':
                    return $obj['value_range'];
                    break;
                case 'activity':
                    return $obj['perm_act'];
                    break;
                case 'object':
                    return $obj['obj_type'];
                    break;
                case 'name':
                    return $obj['perm_obj'];
                    break;
                default:
                    user_error('Invalid Key Provided @ 2nd Parameter');
            }
        } else {
            if(!is_initial($obj)){
                return $obj;
            }else{
                // trigger_error("Object Does Not Exit!", E_USER_NOTICE);
                // echo "<pre>";
                //     debug_print_backtrace();
                // echo "</pre>";
                return NULL;
            }
        }
    } else {
        return null;
    }
}
