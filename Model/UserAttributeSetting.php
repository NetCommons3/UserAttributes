<?php
/**
 * UserAttributeSetting Model
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('UserAttributesAppModel', 'UserAttributes.Model');

/**
 * UserAttributeSetting Model
 */
class UserAttributeSetting extends UserAttributesAppModel {

/**
 * 追加用データタイプ
 *
 * @var array
 */
	public $addDataTypes = array(
		'text', 'textarea', 'radio', 'checkbox',
		'select', 'email', 'img', 'prefecture',
	);

/**
 * 編集用データタイプ
 *
 * @var array
 */
	public $editDataTypes = array(
		'label', 'text', 'textarea', 'radio', 'checkbox',
		'select', 'password', 'email', 'img', 'prefecture', 'timezone',
	);

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
		$this->validate = Hash::merge($this->validate, array(
			'user_attribute_key' => array(
				'notBlank' => array(
					'rule' => array('notBlank'),
					'message' => __d('net_commons', 'Invalid request.'),
					'on' => 'update', // Limit validation to 'create' or 'update' operations
				),
			),
			'data_type_key' => array(
				'notBlank' => array(
					'rule' => array('notBlank'),
					'message' => __d('net_commons', 'Invalid request.'),
				),
				'inList' => array(
					'rule' => array('inList', $this->editDataTypes),
					'message' => __d('net_commons', 'Invalid request.'),
				),
			),
			'row' => array(
				'numeric' => array(
					'rule' => array('numeric'),
					'message' => __d('net_commons', 'Invalid request.'),
				),
			),
			'col' => array(
				'numeric' => array(
					'rule' => array('numeric'),
					'message' => __d('net_commons', 'Invalid request.'),
				),
			),
			'weight' => array(
				'numeric' => array(
					'rule' => array('numeric'),
					'message' => __d('net_commons', 'Invalid request.'),
				),
			),
			'required' => array(
				'boolean' => array(
					'rule' => array('boolean'),
					'message' => __d('net_commons', 'Invalid request.'),
					'required' => false,
				),
			),
			'display' => array(
				'boolean' => array(
					'rule' => array('boolean'),
					'message' => __d('net_commons', 'Invalid request.'),
					'required' => false,
				),
			),
			'only_administrator_readable' => array(
				'boolean' => array(
					'rule' => array('boolean'),
					'message' => __d('net_commons', 'Invalid request.'),
					'required' => false,
				),
			),
			'only_administrator_editable' => array(
				'boolean' => array(
					'rule' => array('boolean'),
					'message' => __d('net_commons', 'Invalid request.'),
					'required' => false,
				),
			),
			'display_label' => array(
				'boolean' => array(
					'rule' => array('boolean'),
					'message' => __d('net_commons', 'Invalid request.'),
					'required' => false,
				),
			),

			//display_search_resultは画面で設定することがないので不要

			'self_public_setting' => array(
				'boolean' => array(
					'rule' => array('boolean'),
					'message' => __d('net_commons', 'Invalid request.'),
					'required' => false,
				),
			),
			'self_email_setting' => array(
				'boolean' => array(
					'rule' => array('boolean'),
					'message' => __d('net_commons', 'Invalid request.'),
					'required' => false,
				),
			),
			'is_multilingualization' => array(
				'boolean' => array(
					'rule' => array('boolean'),
					'message' => __d('net_commons', 'Invalid request.'),
					'required' => false,
				),
			),
		));

		return parent::beforeValidate($options);
	}

/**
 * MAXの順番を取得するメソッド
 *
 * @param int $row Row number
 * @param int $col Col number
 * @return int $weight user_attribute_settings.weight
 */
	public function getMaxWeight($row, $col) {
		$order = $this->find('first', array(
			'recursive' => -1,
			'fields' => array('weight'),
			'conditions' => array('row' => $row, 'col' => $col),
			'order' => array('weight' => 'DESC')
		));

		if (isset($order['UserAttributeSetting']['weight'])) {
			$weight = (int)$order['UserAttributeSetting']['weight'];
		} else {
			$weight = 0;
		}
		return $weight;
	}

/**
 * ユーザ属性の順番を変更するメソッド
 *
 * @param array $data received post data
 * @return bool True on success, false on validation errors
 * @throws InternalErrorException
 */
	public function saveUserAttributeWeight($data) {
		//トランザクションBegin
		$this->begin();

		$before = $this->find('first', array(
			'recursive' => -1,
			'conditions' => array('id' => $data[$this->alias]['id'])
		));
		if (! $before) {
			return false;
		}

		$after = Hash::merge($before, $data);
		unset($after[$this->alias]['modified'], $after[$this->alias]['modified_user']);

		try {
			//移動元の順番を更新
			$this->updateUserAttributeWeight(
				$before[$this->alias]['row'],
				$before[$this->alias]['col'],
				$before[$this->alias]['weight'],
				-1, '>'
			);
			//移動先の順番を更新
			$this->updateUserAttributeWeight(
				$after[$this->alias]['row'],
				$after[$this->alias]['col'],
				$after[$this->alias]['weight'],
				1, '>='
			);
			//対象項目の更新
			if (! $this->save($after)) {
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

/**
 * 表示・非表示の切り替え
 *
 * @param array $data リクエストデータ
 * @param string $fieldName フィールド名
 * @return bool True on success
 * @throws InternalErrorException
 */
	public function saveUserAttributeSetting($data, $fieldName) {
		//トランザクションBegin
		$this->begin();

		$this->id = $data[$this->alias]['id'];
		if (! $this->exists()) {
			return false;
		}

		try {
			//UserAttributeSettingテーブルの登録
			if (! $this->saveField($fieldName, $data[$this->alias][$fieldName], false)) {
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

/**
 * ユーザ属性の順番を更新メソッド
 * ※トランザクションは、呼び出し元で行う。
 *
 * @param int $row 段
 * @param int $col 列
 * @param int $weight 順番
 * @param int $fluctuation 増減
 * @param string $sign 符号（> or >=）
 * @return bool True on success
 * @throws InternalErrorException
 */
	public function updateUserAttributeWeight($row, $col, $weight, $fluctuation, $sign) {
		//移動元の順番を更新
		$result = $this->updateAll(
			array($this->alias . '.weight' => $this->alias . '.weight + (' . $fluctuation . ')'),
			array(
				$this->alias . '.weight ' . $sign => $weight,
				$this->alias . '.row' => $row,
				$this->alias . '.col' => $col,
			)
		);
		if (! $result) {
			throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
		}

		return true;
	}

}
