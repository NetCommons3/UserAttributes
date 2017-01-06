<?php
/**
 * ユーザ項目に言語追加 migration
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsMigration', 'NetCommons.Config/Migration');

/**
 * ユーザ項目に言語追加 migration
 *
 * @package NetCommons\UserAttributes\Config\Migration
 */
class AddLanguageRecords extends NetCommonsMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'add_language_records';

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
			array(
				'user_attribute_key' => 'language',
				'data_type_key' => 'language',
				'row' => '1',
				'col' => '1',
				'weight' => '5',
				'required' => '0',
				'display' => '1',
				'only_administrator_readable' => '0',
				'only_administrator_editable' => '0',
				'is_system' => '1',
				'display_label' => '1',
				'display_search_result' => '0',
				'self_public_setting' => '0',
				'self_email_setting' => '0',
				'is_multilingualization' => '0',
				'auto_regist_display' => null,
				'auto_regist_weight' => '9999',
			),
		),
		'UserAttribute' => array(
			//日本語
			array('language_id' => '2', 'key' => 'language', 'name' => '言語', ),
			//英語
			array('language_id' => '1', 'key' => 'language', 'name' => 'Language', ),
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

		//weightの更新
		$update = array(
			'weight' => 'weight + 1'
		);
		$conditions = array(
			'row' => $this->records['UserAttributeSetting'][0]['row'],
			'col' => $this->records['UserAttributeSetting'][0]['col'],
			'weight >=' => $this->records['UserAttributeSetting'][0]['weight'],
		);
		$Model = $this->generateModel('UserAttributeSetting');
		if (! $Model->updateAll($update, $conditions)) {
			return false;
		}

		//auto_regist_weightの更新
		$update = array(
			'auto_regist_weight' => 'auto_regist_weight + 1'
		);
		$conditions = array(
			'auto_regist_weight >=' => $this->records['UserAttributeSetting'][0]['auto_regist_weight'],
			'auto_regist_weight <' => '9999',
		);
		$Model = $this->generateModel('UserAttributeSetting');
		if (! $Model->updateAll($update, $conditions)) {
			return false;
		}

		foreach ($this->records as $model => $records) {
			if (!$this->updateRecords($model, $records)) {
				return false;
			}
		}
		return true;
	}
}
