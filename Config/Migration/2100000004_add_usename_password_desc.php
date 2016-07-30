<?php
/**
 * ログインIDとパスワードの補足説明追加 migration
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsMigration', 'NetCommons.Config/Migration');

/**
 * ログインIDとパスワードの補足説明追加 migration
 *
 * @package NetCommons\UserAttributes\Config\Migration
 */
class AddUsenamePasswordDesc extends NetCommonsMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'add_usename_password_desc';

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
		'UserAttribute' => array(
			//ログインID
			array('id' => '2', 'description' => '4文字以上の英数字または記号を入力してください。'),
			array('id' => '22', 'description' => 'Please choose at least 4 characters string. No space or special character is allowed.'),
			//パスワード
			array('id' => '3', 'description' => '4文字以上の英数字または記号を入力してください。'),
			array('id' => '23', 'description' => 'Please choose at least 4 characters string. No space or special character is allowed.'),
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

		$UserAttribute = $this->generateModel('UserAttribute');
		foreach ($this->records['UserAttribute'] as $i => $record) {
			$update = array(
				'description' => '\'' . $record['description'] . '\''
			);
			$conditions = array(
				'id' => $record['id'],
			);
			if (! $UserAttribute->updateAll($update, $conditions)) {
				return false;
			}
		}

		return true;
	}
}
