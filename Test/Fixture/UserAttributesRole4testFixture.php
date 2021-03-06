<?php
/**
 * UserAttributesRoleFixture
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('UserAttributesRoleFixture', 'UserRoles.Test/Fixture');

/**
 * UserAttributesRoleFixture
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\UserAttributes\Model
 */
class UserAttributesRole4testFixture extends UserAttributesRoleFixture {

/**
 * Model name
 *
 * @var string
 */
	public $name = 'UserAttributesRole';

/**
 * Full Table Name
 *
 * @var string
 */
	public $table = 'user_attributes_roles';

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array('role_key' => 'system_administrator', 'user_attribute_key' => 'avatar', 'self_readable' => '1', 'self_editable' => '1', 'other_readable' => '1', 'other_editable' => '1', ),
		array('role_key' => 'system_administrator', 'user_attribute_key' => 'username', 'self_readable' => '1', 'self_editable' => '1', 'other_readable' => '1', 'other_editable' => '1', ),
		array('role_key' => 'system_administrator', 'user_attribute_key' => 'password', 'self_readable' => '0', 'self_editable' => '1', 'other_readable' => '0', 'other_editable' => '1', ),
		array('role_key' => 'system_administrator', 'user_attribute_key' => 'handlename', 'self_readable' => '1', 'self_editable' => '1', 'other_readable' => '1', 'other_editable' => '1', ),
		array('role_key' => 'system_administrator', 'user_attribute_key' => 'name', 'self_readable' => '1', 'self_editable' => '1', 'other_readable' => '1', 'other_editable' => '1', ),
		array('role_key' => 'system_administrator', 'user_attribute_key' => 'email', 'self_readable' => '1', 'self_editable' => '1', 'other_readable' => '1', 'other_editable' => '1', ),
		array('role_key' => 'system_administrator', 'user_attribute_key' => 'moblie_mail', 'self_readable' => '1', 'self_editable' => '1', 'other_readable' => '1', 'other_editable' => '1', ),
		array('role_key' => 'system_administrator', 'user_attribute_key' => 'sex', 'self_readable' => '1', 'self_editable' => '1', 'other_readable' => '1', 'other_editable' => '1', ),
		array('role_key' => 'system_administrator', 'user_attribute_key' => 'timezone', 'self_readable' => '1', 'self_editable' => '1', 'other_readable' => '1', 'other_editable' => '1', ),
		array('role_key' => 'system_administrator', 'user_attribute_key' => 'role_key', 'self_readable' => '1', 'self_editable' => '1', 'other_readable' => '1', 'other_editable' => '1', ),
		array('role_key' => 'system_administrator', 'user_attribute_key' => 'status', 'self_readable' => '1', 'self_editable' => '1', 'other_readable' => '1', 'other_editable' => '1', ),
		array('role_key' => 'system_administrator', 'user_attribute_key' => 'created', 'self_readable' => '1', 'self_editable' => '0', 'other_readable' => '1', 'other_editable' => '0', ),
		array('role_key' => 'system_administrator', 'user_attribute_key' => 'created_user', 'self_readable' => '1', 'self_editable' => '0', 'other_readable' => '1', 'other_editable' => '0', ),
		array('role_key' => 'system_administrator', 'user_attribute_key' => 'modified', 'self_readable' => '1', 'self_editable' => '0', 'other_readable' => '1', 'other_editable' => '0', ),
		array('role_key' => 'system_administrator', 'user_attribute_key' => 'modified_user', 'self_readable' => '1', 'self_editable' => '0', 'other_readable' => '1', 'other_editable' => '0', ),
		array('role_key' => 'system_administrator', 'user_attribute_key' => 'password_modified', 'self_readable' => '1', 'self_editable' => '0', 'other_readable' => '1', 'other_editable' => '0', ),
		array('role_key' => 'system_administrator', 'user_attribute_key' => 'last_login', 'self_readable' => '1', 'self_editable' => '0', 'other_readable' => '1', 'other_editable' => '0', ),
		array('role_key' => 'system_administrator', 'user_attribute_key' => 'previous_login', 'self_readable' => '1', 'self_editable' => '0', 'other_readable' => '1', 'other_editable' => '0', ),
		array('role_key' => 'system_administrator', 'user_attribute_key' => 'profile', 'self_readable' => '1', 'self_editable' => '1', 'other_readable' => '1', 'other_editable' => '1', ),
		array('role_key' => 'system_administrator', 'user_attribute_key' => 'search_keywords', 'self_readable' => '1', 'self_editable' => '1', 'other_readable' => '1', 'other_editable' => '1', ),
		array('role_key' => 'administrator', 'user_attribute_key' => 'avatar', 'self_readable' => '1', 'self_editable' => '1', 'other_readable' => '1', 'other_editable' => '1', ),
		array('role_key' => 'administrator', 'user_attribute_key' => 'username', 'self_readable' => '1', 'self_editable' => '1', 'other_readable' => '1', 'other_editable' => '1', ),
		array('role_key' => 'administrator', 'user_attribute_key' => 'password', 'self_readable' => '0', 'self_editable' => '1', 'other_readable' => '0', 'other_editable' => '1', ),
		array('role_key' => 'administrator', 'user_attribute_key' => 'handlename', 'self_readable' => '1', 'self_editable' => '1', 'other_readable' => '1', 'other_editable' => '1', ),
		array('role_key' => 'administrator', 'user_attribute_key' => 'name', 'self_readable' => '1', 'self_editable' => '1', 'other_readable' => '1', 'other_editable' => '1', ),
		array('role_key' => 'administrator', 'user_attribute_key' => 'email', 'self_readable' => '1', 'self_editable' => '1', 'other_readable' => '1', 'other_editable' => '1', ),
		array('role_key' => 'administrator', 'user_attribute_key' => 'moblie_mail', 'self_readable' => '1', 'self_editable' => '1', 'other_readable' => '1', 'other_editable' => '1', ),
		array('role_key' => 'administrator', 'user_attribute_key' => 'sex', 'self_readable' => '1', 'self_editable' => '1', 'other_readable' => '1', 'other_editable' => '1', ),
		array('role_key' => 'administrator', 'user_attribute_key' => 'timezone', 'self_readable' => '1', 'self_editable' => '1', 'other_readable' => '1', 'other_editable' => '1', ),
		array('role_key' => 'administrator', 'user_attribute_key' => 'role_key', 'self_readable' => '1', 'self_editable' => '1', 'other_readable' => '1', 'other_editable' => '1', ),
		array('role_key' => 'administrator', 'user_attribute_key' => 'status', 'self_readable' => '1', 'self_editable' => '1', 'other_readable' => '1', 'other_editable' => '1', ),
		array('role_key' => 'administrator', 'user_attribute_key' => 'created', 'self_readable' => '1', 'self_editable' => '0', 'other_readable' => '1', 'other_editable' => '0', ),
		array('role_key' => 'administrator', 'user_attribute_key' => 'created_user', 'self_readable' => '1', 'self_editable' => '0', 'other_readable' => '1', 'other_editable' => '0', ),
		array('role_key' => 'administrator', 'user_attribute_key' => 'modified', 'self_readable' => '1', 'self_editable' => '0', 'other_readable' => '1', 'other_editable' => '0', ),
		array('role_key' => 'administrator', 'user_attribute_key' => 'modified_user', 'self_readable' => '1', 'self_editable' => '0', 'other_readable' => '1', 'other_editable' => '0', ),
		array('role_key' => 'administrator', 'user_attribute_key' => 'password_modified', 'self_readable' => '1', 'self_editable' => '0', 'other_readable' => '1', 'other_editable' => '0', ),
		array('role_key' => 'administrator', 'user_attribute_key' => 'last_login', 'self_readable' => '1', 'self_editable' => '0', 'other_readable' => '1', 'other_editable' => '0', ),
		array('role_key' => 'administrator', 'user_attribute_key' => 'previous_login', 'self_readable' => '1', 'self_editable' => '0', 'other_readable' => '1', 'other_editable' => '0', ),
		array('role_key' => 'administrator', 'user_attribute_key' => 'profile', 'self_readable' => '1', 'self_editable' => '1', 'other_readable' => '1', 'other_editable' => '1', ),
		array('role_key' => 'administrator', 'user_attribute_key' => 'search_keywords', 'self_readable' => '1', 'self_editable' => '1', 'other_readable' => '1', 'other_editable' => '1', ),
		array('role_key' => 'common_user', 'user_attribute_key' => 'avatar', 'self_readable' => '1', 'self_editable' => '1', 'other_readable' => '1', 'other_editable' => '0', ),
		array('role_key' => 'common_user', 'user_attribute_key' => 'username', 'self_readable' => '0', 'self_editable' => '0', 'other_readable' => '0', 'other_editable' => '0', ),
		array('role_key' => 'common_user', 'user_attribute_key' => 'password', 'self_readable' => '0', 'self_editable' => '1', 'other_readable' => '0', 'other_editable' => '0', ),
		array('role_key' => 'common_user', 'user_attribute_key' => 'handlename', 'self_readable' => '1', 'self_editable' => '1', 'other_readable' => '1', 'other_editable' => '0', ),
		array('role_key' => 'common_user', 'user_attribute_key' => 'name', 'self_readable' => '1', 'self_editable' => '1', 'other_readable' => '0', 'other_editable' => '0', ),
		array('role_key' => 'common_user', 'user_attribute_key' => 'email', 'self_readable' => '1', 'self_editable' => '1', 'other_readable' => '0', 'other_editable' => '0', ),
		array('role_key' => 'common_user', 'user_attribute_key' => 'moblie_mail', 'self_readable' => '1', 'self_editable' => '1', 'other_readable' => '0', 'other_editable' => '0', ),
		array('role_key' => 'common_user', 'user_attribute_key' => 'sex', 'self_readable' => '0', 'self_editable' => '0', 'other_readable' => '0', 'other_editable' => '0', ),
		array('role_key' => 'common_user', 'user_attribute_key' => 'timezone', 'self_readable' => '1', 'self_editable' => '1', 'other_readable' => '0', 'other_editable' => '0', ),
		array('role_key' => 'common_user', 'user_attribute_key' => 'role_key', 'self_readable' => '0', 'self_editable' => '0', 'other_readable' => '0', 'other_editable' => '0', ),
		array('role_key' => 'common_user', 'user_attribute_key' => 'status', 'self_readable' => '0', 'self_editable' => '0', 'other_readable' => '0', 'other_editable' => '0', ),
		array('role_key' => 'common_user', 'user_attribute_key' => 'created', 'self_readable' => '0', 'self_editable' => '0', 'other_readable' => '0', 'other_editable' => '0', ),
		array('role_key' => 'common_user', 'user_attribute_key' => 'created_user', 'self_readable' => '0', 'self_editable' => '0', 'other_readable' => '0', 'other_editable' => '0', ),
		array('role_key' => 'common_user', 'user_attribute_key' => 'modified', 'self_readable' => '0', 'self_editable' => '0', 'other_readable' => '0', 'other_editable' => '0', ),
		array('role_key' => 'common_user', 'user_attribute_key' => 'modified_user', 'self_readable' => '0', 'self_editable' => '0', 'other_readable' => '0', 'other_editable' => '0', ),
		array('role_key' => 'common_user', 'user_attribute_key' => 'password_modified', 'self_readable' => '1', 'self_editable' => '0', 'other_readable' => '0', 'other_editable' => '0', ),
		array('role_key' => 'common_user', 'user_attribute_key' => 'last_login', 'self_readable' => '1', 'self_editable' => '0', 'other_readable' => '0', 'other_editable' => '0', ),
		array('role_key' => 'common_user', 'user_attribute_key' => 'previous_login', 'self_readable' => '1', 'self_editable' => '0', 'other_readable' => '0', 'other_editable' => '0', ),
		array('role_key' => 'common_user', 'user_attribute_key' => 'profile', 'self_readable' => '1', 'self_editable' => '1', 'other_readable' => '0', 'other_editable' => '0', ),
		array('role_key' => 'common_user', 'user_attribute_key' => 'search_keywords', 'self_readable' => '0', 'self_editable' => '0', 'other_readable' => '0', 'other_editable' => '0', ),
	);

}
