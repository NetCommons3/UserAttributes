<?php
/**
 * Init migration
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

/**
 * Init migration
 *
 * @package NetCommons\UserAttributes\Config\Migration
 */
class AddIndex extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'add_index';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'alter_field' => array(
				'user_attribute_choices' => array(
					'user_attribute_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false, 'key' => 'index'),
				),
				'user_attribute_settings' => array(
					'user_attribute_key' => array('type' => 'string', 'null' => false, 'default' => null, 'key' => 'index', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'data_type_key' => array('type' => 'string', 'null' => false, 'default' => 'text', 'key' => 'index', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
				),
				'user_attributes' => array(
					'key' => array('type' => 'string', 'null' => false, 'default' => null, 'key' => 'index', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
				),
			),
			'create_field' => array(
				'user_attribute_choices' => array(
					'indexes' => array(
						'user_attribute_id' => array('column' => 'user_attribute_id', 'unique' => 0),
					),
				),
				'user_attribute_settings' => array(
					'indexes' => array(
						'user_attribute_key' => array('column' => 'user_attribute_key', 'unique' => 0),
						'data_type_key' => array('column' => 'data_type_key', 'unique' => 0),
					),
				),
				'user_attributes' => array(
					'indexes' => array(
						'key' => array('column' => 'key', 'unique' => 0),
					),
				),
			),
		),
		'down' => array(
			'alter_field' => array(
				'user_attribute_choices' => array(
					'user_attribute_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
				),
				'user_attribute_settings' => array(
					'user_attribute_key' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'data_type_key' => array('type' => 'string', 'null' => false, 'default' => 'text', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
				),
				'user_attributes' => array(
					'key' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
				),
			),
			'drop_field' => array(
				'user_attribute_choices' => array('indexes' => array('user_attribute_id')),
				'user_attribute_settings' => array('indexes' => array('user_attribute_key', 'data_type_key')),
				'user_attributes' => array('indexes' => array('key')),
			),
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
		return true;
	}
}
