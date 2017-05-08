<?php
/**
 * ハンドルやログインIDのシステムで必須の項目が編集時に必須が外れてしまうバグ修正 migration
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsMigration', 'NetCommons.Config/Migration');

/**
 * ハンドルやログインIDのシステムで必須の項目が編集時に必須が外れてしまうバグ修正 migration
 *
 * @package NetCommons\UserAttributes\Config\Migration
 * @link
 */
class UpdateSystemRequire extends NetCommonsMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'update_system_require';

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

		$update = array(
			'required' => true
		);
		$conditions = array(
			'user_attribute_key' => ['username', 'password', 'handlename', 'role_key', 'status'],
			'required' => false
		);
		$UserAttributeSetting = $this->generateModel('UserAttributeSetting');
		if (! $UserAttributeSetting->updateAll($update, $conditions)) {
			return false;
		}

		return true;
	}
}
