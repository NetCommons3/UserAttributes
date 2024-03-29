<?php
/**
 * UserAttributeLayout Model
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('UserAttributesAppModel', 'UserAttributes.Model');

/**
 * UserAttributeLayout Model
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\UserAttributes\Model
 */
class UserAttributeLayout extends UserAttributesAppModel {

/**
 * レイアウト列数
 */
	const LAYOUT_COL_NUMBER = 2;

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array();

/**
 * Called during validation operations, before validation. Please note that custom
 * validation rules can be defined in $validate.
 *
 * @param array $options Options passed from Model::save().
 * @return bool True if validate operation should continue, false to abort
 * @link http://book.cakephp.org/2.0/en/models/callback-methods.html#beforevalidate
 * @see Model::save()
 */
	public function beforeValidate($options = array()) {
		$this->validate = ValidateMerge::merge($this->validate, array(
			'col' => array(
				'numeric' => array(
					'rule' => array('numeric'),
					'message' => __d('net_commons', 'Invalid request.'),
					'required' => true,
				),
				'range' => array(
					'rule' => array('range', 0, self::LAYOUT_COL_NUMBER + 1),
					'message' => __d('net_commons', 'Invalid request.'),
					'required' => true,
				),
			),
		));

		return parent::beforeValidate($options);
	}

/**
 * レイアウトの保存
 *
 * @param array $data リクエストデータ
 * @param string $fieldName フィールド名
 * @return bool True on success
 * @throws InternalErrorException
 */
	public function updateUserAttributeLayout($data, $fieldName) {
		//トランザクションBegin
		$this->begin();

		$this->id = $data[$this->alias]['id'];
		$value = Hash::get($data, $this->alias . '.' . $fieldName);
		if (! $this->exists() || ! $this->hasField($fieldName) || ! is_numeric($value)) {
			return false;
		}

		try {
			//UserAttributeLayoutテーブルの登録
			if (! $this->saveField($fieldName, $data[$this->alias][$fieldName], ['callbacks' => false])) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}

			//トランザクションCommit
			$this->commit();

		} catch (Exception $ex) {
			//トランザクションRollback
			$this->rollback($ex);
		}

		return true;
	}

}
