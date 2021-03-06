<?php
/**
 * UserAttributeChoice Model
 *
 * @property Language $Language
 * @property UserAttribute $UserAttribute
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('UsersAppModel', 'Users.Model');
App::uses('DataType', 'DataTypes.Model');

/**
 * UserAttributeChoice Model
 */
class UserAttributeChoice extends UsersAppModel {

/**
 * 状態のステータス(利用可能)
 *
 * @var const
 */
	const STATUS_KEY_ACTIVE = 'status_1',
			STATUS_CODE_ACTIVE = '1';

/**
 * 状態のステータス(利用不可)
 *
 * @var const
 */
	const STATUS_KEY_NONACTIVE = 'status_0',
			STATUS_CODE_NONACTIVE = '0';

/**
 * 状態のステータス(承認待ち)
 *
 * @var const
 */
	const STATUS_KEY_WAITING = 'status_2',
			STATUS_CODE_WAITING = '2';

/**
 * 状態のステータス(承認済み⇒本人の確認待ち)
 *
 * @var const
 */
	const STATUS_KEY_APPROVED = 'status_3',
			STATUS_CODE_APPROVED = '3';

/**
 * 言語の自動
 *
 * @var const
 */
	const LANGUAGE_KEY_AUTO = 'auto';

/**
 * use behaviors
 *
 * @var array
 */
	public $actsAs = array(
		'NetCommons.OriginalKey',
	);

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array();

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Language' => array(
			'className' => 'M17n.Language',
			'foreignKey' => 'language_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'UserAttribute' => array(
			'className' => 'UserAttributes.UserAttribute',
			'foreignKey' => 'user_attribute_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

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
			'language_id' => array(
				'numeric' => array(
					'rule' => array('numeric'),
					'message' => __d('net_commons', 'Invalid request.'),
					'required' => true,
				),
			),
			'user_attribute_id' => array(
				'numeric' => array(
					'rule' => array('numeric'),
					'message' => __d('net_commons', 'Invalid request.'),
					'required' => true,
					'on' => 'update', // Limit validation to 'create' or 'update' operations
				),
			),
			'name' => array(
				'notBlank' => array(
					'rule' => array('notBlank'),
					'message' => sprintf(
						__d('net_commons', 'Please input %s.'), __d('user_attributes', 'Item choice name')
					),
					'required' => true,
				),
			),
			'key' => array(
				'notBlank' => array(
					'rule' => array('notBlank'),
					'message' => __d('net_commons', 'Invalid request.'),
					'required' => true,
					'on' => 'update', // Limit validation to 'create' or 'update' operations
				),
			),
			'weight' => array(
				'numeric' => array(
					'rule' => array('numeric'),
					'message' => __d('net_commons', 'Invalid request.'),
				),
			),
		));

		return parent::beforeValidate($options);
	}

/**
 * Called before each save operation, after validation. Return a non-true result
 * to halt the save.
 *
 * @param array $options Options passed from Model::save().
 * @return bool True if the operation should continue, false if it should abort
 * @link http://book.cakephp.org/2.0/en/models/callback-methods.html#beforesave
 * @see Model::save()
 */
	public function beforeSave($options = array()) {
		if (isset($this->data[$this->alias]['key']) &&
			isset($this->data[$this->alias]['code']) && ! $this->data[$this->alias]['code']) {

			$this->data[$this->alias]['code'] = $this->data[$this->alias]['key'];
		}

		return parent::beforeSave($options);
	}

/**
 * 会員項目の選択肢の登録
 * トランザクションは呼び出し元のUserAttribute->saveUserAttribute()で行う。
 *
 * @param array $data リクエストデータ
 * @return bool Trueは成功。Falseはバリデーションエラー
 * @throws InternalErrorException
 */
	public function saveUserAttributeChoices($data) {
		//システム項目、ラジオボタン・チェックボタン・セレクトボックス以外、処理を抜ける
		if (! ($data['UserAttributeSetting']['data_type_key'] === DataType::DATA_TYPE_RADIO ||
					$data['UserAttributeSetting']['data_type_key'] === DataType::DATA_TYPE_CHECKBOX ||
					$data['UserAttributeSetting']['data_type_key'] === DataType::DATA_TYPE_SELECT)
		) {
			return true;
		}

		//登録処理
		$userAttributeIds = Hash::combine(
			$data['UserAttribute'], '{n}.UserAttribute.language_id', '{n}.UserAttribute.id'
		);
		$useChoiceKeys = array();
		foreach ($data['UserAttributeChoice'] as $choiceByWeight) {
			$choiceKey = null;
			foreach ($choiceByWeight as $languageId => $choice) {
				if (! isset($choiceKey)) {
					$choiceKey = $choice['key'];
				}
				$choice['user_attribute_id'] = $userAttributeIds[$languageId];
				$choice['key'] = $choiceKey;

				$this->create();
				if (! $result = $this->save($choice)) {
					throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
				}
				$choiceKey = $result['UserAttributeChoice']['key'];

			}
			$useChoiceKeys[] = $choiceKey;
		}

		//不要データの削除
		$conditions = array(
			$this->alias . '.user_attribute_id' => $userAttributeIds,
			$this->alias . '.key NOT' => $useChoiceKeys,
		);
		if (! $this->deleteAll($conditions, false)) {
			throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
		}

		return true;
	}

/**
 * リクエストのバリデーション処理
 * UserAttributeChoiceとUserAttributeChoiceMapが必要
 *
 * @param array $data received post data
 * @return bool True on success, false on validation errors
 * @throws BadRequestException
 */
	public function validateRequestData($data) {
		$result = array();

		if (! is_array(Hash::get($data, 'UserAttributeChoice'))) {
			return $result;
		}

		$weight = 0;
		foreach ($data['UserAttributeChoice'] as $choiceByWeight) {
			foreach ($choiceByWeight as $langId => $choice) {
				if (! Hash::get($choice, 'id')) {
					$created = $this->create(array(
						'id' => null,
						'language_id' => $choice['language_id'],
						'user_attribute_id' => $choice['user_attribute_id'],
						'weight' => $weight + 1,
						'name' => $choice['name'],
					));
					$result[$weight][$langId] = $created[$this->alias];
				} elseif (isset($data['UserAttributeChoiceMap'][$choice['id']])) {
					$result[$weight][$langId] = $data['UserAttributeChoiceMap'][$choice['id']];
					$result[$weight][$langId]['weight'] = $weight + 1;
					$result[$weight][$langId]['name'] = $choice['name'];
				} else {
					//不正なリクエスト
					return false;
				}
			}
			$weight++;
		}

		return $result;
	}

}
