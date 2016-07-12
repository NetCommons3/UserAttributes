<?php
/**
 * データタイプから言語タイプ削除 migration
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsMigration', 'NetCommons.Config/Migration');

/**
 * データタイプから言語タイプ削除 migration
 *
 * @package NetCommons\UserAttributes\Config\Migration
 */
class DeleteDataTypeLanguage extends NetCommonsMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'delete_data_type_language';

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
		'UserAttributeChoice' => array(
			array('language_id' => '2', 'user_attribute_id' => '', 'key' => 'language_0', 'name' => '自動', 'code' => 'auto', 'weight' => '1', ),
			array('language_id' => '2', 'user_attribute_id' => '', 'key' => 'language_1', 'name' => '英語', 'code' => 'en', 'weight' => '2', ),
			array('language_id' => '2', 'user_attribute_id' => '', 'key' => 'language_2', 'name' => '日本語', 'code' => 'ja', 'weight' => '3', ),
			array('language_id' => '1', 'user_attribute_id' => '', 'key' => 'language_0', 'name' => 'Auto', 'code' => 'auto', 'weight' => '1', ),
			array('language_id' => '1', 'user_attribute_id' => '', 'key' => 'language_1', 'name' => 'English', 'code' => 'en', 'weight' => '2', ),
			array('language_id' => '1', 'user_attribute_id' => '', 'key' => 'language_2', 'name' => 'Japanese', 'code' => 'ja', 'weight' => '3', ),
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

		//UserAttributeSettingのdata_type_key変更
		$update = array(
			'data_type_key' => '\'select\''
		);
		$conditions = array(
			'data_type_key' => 'language',
		);
		$UserAttributeSetting = $this->generateModel('UserAttributeSetting');
		if (! $UserAttributeSetting->updateAll($update, $conditions)) {
			return false;
		}

		$UserAttribute = $this->generateModel('UserAttribute');
		$userAttributes = $UserAttribute->find('list', array(
			'recursive' => -1,
			'fields' => array('language_id', 'id'),
			'conditions' => array('key' => 'language')
		));

		foreach ($this->records['UserAttributeChoice'] as $i => $record) {
			$record['user_attribute_id'] = Hash::get($userAttributes, $record['language_id']);
			$this->records['UserAttributeChoice'][$i] = $record;
		}
debug($this->records);

		foreach ($this->records as $model => $records) {
			if (!$this->updateRecords($model, $records)) {
				return false;
			}
		}

		return true;
	}
}
