<?php
/**
 * Insert records migration
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsMigration', 'NetCommons.Config/Migration');

/**
 * Insert records migration
 *
 * @package NetCommons\UserAttributes\Config\Migration
 */
class UserAttributeSettingRecords extends NetCommonsMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'user_attribute_setting';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(),
		'down' => array(),
	);

/**
 * Insert records
 *
 * @var array $migration
 */
	public $records = array(
		'UserAttributeSetting' => array(
			array('id' => '1', 'user_attribute_key' => 'avatar', 'data_type_key' => 'img', 'row' => '1', 'col' => '1', 'weight' => '1', 'required' => '0', 'display' => '1', 'only_administrator_readable' => '0', 'only_administrator_editable' => '0', 'is_system' => '1', 'display_label' => '1', 'display_search_result' => '0', 'self_public_setting' => '0', 'self_email_setting' => '0', 'is_multilingualization' => '0', ),
			array('id' => '2', 'user_attribute_key' => 'username', 'data_type_key' => 'text', 'row' => '1', 'col' => '2', 'weight' => '1', 'required' => '1', 'display' => '0', 'only_administrator_readable' => '1', 'only_administrator_editable' => '1', 'is_system' => '1', 'display_label' => '1', 'display_search_result' => '0', 'self_public_setting' => '0', 'self_email_setting' => '0', 'is_multilingualization' => '0', ),
			array('id' => '3', 'user_attribute_key' => 'password', 'data_type_key' => 'password', 'row' => '1', 'col' => '2', 'weight' => '2', 'required' => '1', 'display' => '0', 'only_administrator_readable' => '1', 'only_administrator_editable' => '0', 'is_system' => '1', 'display_label' => '1', 'display_search_result' => '0', 'self_public_setting' => '0', 'self_email_setting' => '0', 'is_multilingualization' => '0', ),
			array('id' => '4', 'user_attribute_key' => 'handlename', 'data_type_key' => 'text', 'row' => '1', 'col' => '2', 'weight' => '3', 'required' => '1', 'display' => '1', 'only_administrator_readable' => '0', 'only_administrator_editable' => '0', 'is_system' => '1', 'display_label' => '1', 'display_search_result' => '1', 'self_public_setting' => '0', 'self_email_setting' => '0', 'is_multilingualization' => '0', ),
			array('id' => '5', 'user_attribute_key' => 'name', 'data_type_key' => 'text', 'row' => '1', 'col' => '2', 'weight' => '4', 'required' => '0', 'display' => '1', 'only_administrator_readable' => '0', 'only_administrator_editable' => '0', 'is_system' => '0', 'display_label' => '1', 'display_search_result' => '1', 'self_public_setting' => '0', 'self_email_setting' => '0', 'is_multilingualization' => '1', ),
			array('id' => '6', 'user_attribute_key' => 'email', 'data_type_key' => 'email', 'row' => '1', 'col' => '1', 'weight' => '2', 'required' => '1', 'display' => '1', 'only_administrator_readable' => '0', 'only_administrator_editable' => '0', 'is_system' => '0', 'display_label' => '1', 'display_search_result' => '0', 'self_public_setting' => '0', 'self_email_setting' => '0', 'is_multilingualization' => '0', ),
			array('id' => '7', 'user_attribute_key' => 'moblie_mail', 'data_type_key' => 'email', 'row' => '1', 'col' => '1', 'weight' => '3', 'required' => '0', 'display' => '1', 'only_administrator_readable' => '0', 'only_administrator_editable' => '0', 'is_system' => '0', 'display_label' => '1', 'display_search_result' => '0', 'self_public_setting' => '1', 'self_email_setting' => '1', 'is_multilingualization' => '0', ),
			array('id' => '8', 'user_attribute_key' => 'sex', 'data_type_key' => 'radio', 'row' => '1', 'col' => '1', 'weight' => '4', 'required' => '1', 'display' => '0', 'only_administrator_readable' => '1', 'only_administrator_editable' => '1', 'is_system' => '0', 'display_label' => '1', 'display_search_result' => '0', 'self_public_setting' => '0', 'self_email_setting' => '0', 'is_multilingualization' => '0', ),
			array('id' => '9', 'user_attribute_key' => 'timezone', 'data_type_key' => 'timezone', 'row' => '1', 'col' => '1', 'weight' => '5', 'required' => '1', 'display' => '1', 'only_administrator_readable' => '0', 'only_administrator_editable' => '0', 'is_system' => '1', 'display_label' => '1', 'display_search_result' => '0', 'self_public_setting' => '0', 'self_email_setting' => '0', 'is_multilingualization' => '0', ),
			array('id' => '10', 'user_attribute_key' => 'role_key', 'data_type_key' => 'select', 'row' => '1', 'col' => '1', 'weight' => '6', 'required' => '1', 'display' => '0', 'only_administrator_readable' => '1', 'only_administrator_editable' => '1', 'is_system' => '1', 'display_label' => '1', 'display_search_result' => '1', 'self_public_setting' => '0', 'self_email_setting' => '0', 'is_multilingualization' => '0', ),
			array('id' => '11', 'user_attribute_key' => 'status', 'data_type_key' => 'select', 'row' => '1', 'col' => '1', 'weight' => '7', 'required' => '1', 'display' => '0', 'only_administrator_readable' => '1', 'only_administrator_editable' => '1', 'is_system' => '1', 'display_label' => '1', 'display_search_result' => '0', 'self_public_setting' => '0', 'self_email_setting' => '0', 'is_multilingualization' => '0', ),
			array('id' => '12', 'user_attribute_key' => 'created', 'data_type_key' => 'label', 'row' => '1', 'col' => '2', 'weight' => '8', 'required' => '0', 'display' => '0', 'only_administrator_readable' => '1', 'only_administrator_editable' => '1', 'is_system' => '1', 'display_label' => '1', 'display_search_result' => '1', 'self_public_setting' => '0', 'self_email_setting' => '0', 'is_multilingualization' => '0', ),
			array('id' => '13', 'user_attribute_key' => 'created_user', 'data_type_key' => 'label', 'row' => '1', 'col' => '2', 'weight' => '9', 'required' => '0', 'display' => '0', 'only_administrator_readable' => '1', 'only_administrator_editable' => '1', 'is_system' => '1', 'display_label' => '1', 'display_search_result' => '0', 'self_public_setting' => '0', 'self_email_setting' => '0', 'is_multilingualization' => '0', ),
			array('id' => '14', 'user_attribute_key' => 'modified', 'data_type_key' => 'label', 'row' => '1', 'col' => '2', 'weight' => '10', 'required' => '0', 'display' => '0', 'only_administrator_readable' => '1', 'only_administrator_editable' => '1', 'is_system' => '1', 'display_label' => '1', 'display_search_result' => '0', 'self_public_setting' => '0', 'self_email_setting' => '0', 'is_multilingualization' => '0', ),
			array('id' => '15', 'user_attribute_key' => 'modified_user', 'data_type_key' => 'label', 'row' => '1', 'col' => '2', 'weight' => '11', 'required' => '0', 'display' => '0', 'only_administrator_readable' => '1', 'only_administrator_editable' => '1', 'is_system' => '1', 'display_label' => '1', 'display_search_result' => '0', 'self_public_setting' => '0', 'self_email_setting' => '0', 'is_multilingualization' => '0', ),
			array('id' => '16', 'user_attribute_key' => 'password_modified', 'data_type_key' => 'label', 'row' => '1', 'col' => '2', 'weight' => '5', 'required' => '0', 'display' => '1', 'only_administrator_readable' => '0', 'only_administrator_editable' => '0', 'is_system' => '1', 'display_label' => '1', 'display_search_result' => '0', 'self_public_setting' => '0', 'self_email_setting' => '0', 'is_multilingualization' => '0', ),
			array('id' => '17', 'user_attribute_key' => 'last_login', 'data_type_key' => 'label', 'row' => '1', 'col' => '2', 'weight' => '6', 'required' => '0', 'display' => '1', 'only_administrator_readable' => '0', 'only_administrator_editable' => '0', 'is_system' => '1', 'display_label' => '1', 'display_search_result' => '1', 'self_public_setting' => '0', 'self_email_setting' => '0', 'is_multilingualization' => '0', ),
			array('id' => '18', 'user_attribute_key' => 'previous_login', 'data_type_key' => 'label', 'row' => '1', 'col' => '2', 'weight' => '7', 'required' => '0', 'display' => '1', 'only_administrator_readable' => '0', 'only_administrator_editable' => '0', 'is_system' => '1', 'display_label' => '1', 'display_search_result' => '0', 'self_public_setting' => '0', 'self_email_setting' => '0', 'is_multilingualization' => '0', ),
			array('id' => '19', 'user_attribute_key' => 'profile', 'data_type_key' => 'textarea', 'row' => '2', 'col' => '1', 'weight' => '1', 'required' => '0', 'display' => '0', 'only_administrator_readable' => '0', 'only_administrator_editable' => '0', 'is_system' => '0', 'display_label' => '1', 'display_search_result' => '0', 'self_public_setting' => '0', 'self_email_setting' => '0', 'is_multilingualization' => '1', ),
			array('id' => '20', 'user_attribute_key' => 'search_keywords', 'data_type_key' => 'text', 'row' => '2', 'col' => '1', 'weight' => '2', 'required' => '0', 'display' => '0', 'only_administrator_readable' => '1', 'only_administrator_editable' => '1', 'is_system' => '0', 'display_label' => '1', 'display_search_result' => '0', 'self_public_setting' => '0', 'self_email_setting' => '0', 'is_multilingualization' => '1', ),
		),
	);

/**
 * Before migration callback
 *
 * @param string $direction Direction of migration process (up or down)
 * @return bool Should process continue
 */
	public function before($direction) {
		return true;
	}

/**
 * After migration callback
 *
 * @param string $direction Direction of migration process (up or down)
 * @return bool Should process continue
 */
	public function after($direction) {
		if ($direction === 'down') {
			return true;
		}
		foreach ($this->records as $model => $records) {
			if (!$this->updateRecords($model, $records)) {
				return false;
			}
		}
		return true;
	}
}
