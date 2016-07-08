<?php
/**
 * UserAttribute Model
 *
 * @property Language $Language
 * @property UserAttributeChoice $UserAttributeChoice
 * @property Role $Role
 * @property User $User
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('UserAttributesAppModel', 'UserAttributes.Model');

/**
 * UserAttribute Model
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\UserAttributes\Model
 */
class UserAttribute extends UserAttributesAppModel {

/**
 * 公開・非公開フィールドのFormat
 *
 * @var const
 */
	const PUBLIC_FIELD_FORMAT = 'is_%s_public';

/**
 * 受信可否フィールドのフォーマット
 *
 * @var const
 */
	const MAIL_RECEPTION_FIELD_FORMAT = 'is_%s_reception';

/**
 * ログインIDフィールド
 *
 * @var const
 */
	const LOGIN_ID_FIELD = 'username';

/**
 * パスワードフィールド
 *
 * @var const
 */
	const PASSWORD_FIELD = 'password';

/**
 * ハンドルフィールド
 *
 * @var const
 */
	const HANDLENAME_FIELD = 'handlename';

/**
 * アバターフィールド
 *
 * @var const
 */
	const AVATAR_FIELD = 'avatar';

/**
 * UserAttribute->getUserAttributesForLayout()で取得したデータ
 *
 * @var array
 */
	public static $userAttributes = null;

/**
 * datetimeとするフィールド
 *
 * @var array
 */
	public static $typeDatetime = array(
		'created', 'modified', 'last_login', 'previous_login', 'password_modified'
	);

/**
 * 日時フィールドの表示フォーマット
 *
 * @var const
 */
	const DISPLAY_DATETIME_FORMAT = 'Y/m/d G:i';

/**
 * use behaviors
 *
 * @var array
 */
	public $actsAs = array(
		'NetCommons.OriginalKey',
		'UserAttributes.UserAttribute'
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
		//'Language' => array(
		//	'className' => 'M17n.Language',
		//	'foreignKey' => 'language_id',
		//	'conditions' => '',
		//	'fields' => '',
		//	'order' => ''
		//),
		'UserAttributeSetting' => array(
			'className' => 'UserAttributes.UserAttributeSetting',
			'foreignKey' => false,
			'conditions' => 'UserAttribute.key = UserAttributeSetting.user_attribute_key',
			'fields' => '',
			'order' => ''
		)
	);

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'UserAttributeChoice' => array(
			'className' => 'UserAttributes.UserAttributeChoice',
			'foreignKey' => 'user_attribute_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
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
		$this->validate = Hash::merge($this->validate, array(
			'language_id' => array(
				'numeric' => array(
					'rule' => array('numeric'),
					'message' => __d('net_commons', 'Invalid request.'),
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
			'name' => array(
				'notBlank' => array(
					'rule' => array('notBlank'),
					'message' => sprintf(
						__d('net_commons', 'Please input %s.'),
						__d('user_attributes', 'Item name')
					),
					'required' => true,
				),
			),
		));

		return parent::beforeValidate($options);
	}

/**
 * UserAttribureデータ取得
 *
 * @return array 会員項目データ配列
 * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
 */
	public function getUserAttriburesForAutoUserRegist() {
		//モデルのロード
		$this->loadModels([
			'DataType' => 'DataTypes.DataType',
			'UserRole' => 'UserRoles.UserRole',
			'UserAttributesRole' => 'UserRoles.UserAttributesRole',
		]);

		//UserAttributeデータ取得
		$conditions = array(
			'OR' => array(
				'UserAttributeSetting.required' => true,
				'UserAttributeSetting.auto_regist_display' => true,
			)
		);
		$query = $this->findOptionsForLayout($conditions);
		$query['order'] = array(
			'UserAttributeSetting.auto_regist_weight' => 'asc',
			'UserAttributeSetting.row' => 'asc',
			'UserAttributeSetting.col' => 'asc',
			'UserAttributeSetting.weight' => 'asc'
		);
		$userAttributes = $this->find('all', $query);
		$userAttrForAutoReg = Hash::combine($userAttributes, '{n}.UserAttribute.id', '{n}');

		//UserAttributeChoiceデータ取得
		$userAttributeIds = Hash::extract($userAttributes, '{n}.UserAttribute.id');
		$userAttributeChoices = $this->__getUserAttributeChoice($userAttributeIds);

		//DataTypeデータ取得
		$dataTypes = $this->DataType->getDataTypes($this->UserAttributeSetting->editDataTypes);

		//UserRoleデータの取得
		$userRoles = $this->__getUserRole();

		//戻り値の設定
		foreach ($userAttrForAutoReg as $id => $userAttribute) {
			$key = Hash::get($userAttribute, 'UserAttribute.key');
			$dataTypeKey = $userAttribute['UserAttributeSetting']['data_type_key'];

			$result = $userAttribute;

			if (in_array($key, ['username', 'password'], true)) {
				//ログインIDとパスワードは、強制的に編集可にする。
				$result['UserAttributesRole']['self_editable'] = true;
				$result['UserAttributeSetting']['only_administrator_editable'] = false;

			} elseif ($userAttribute['UserAttribute']['key'] === 'role_key') {
				//権限の設定
				$result['UserAttributeChoice'] = $userRoles;

			} elseif (isset($dataTypes[$dataTypeKey]['DataTypeChoice'])) {
				//DataTypeChoiceにデータがある場合
				$result['UserAttributeSetting']['data_type_key'] = DataType::DATA_TYPE_SELECT;
				$result['UserAttributeChoice'] = $dataTypes[$dataTypeKey]['DataTypeChoice'];

			} elseif (isset($userAttributeChoices[$id])) {
				//UserAttributeChoiceにデータがある場合
				$result['UserAttributeChoice'] = $userAttributeChoices[$id];
			}

			$userAttrForAutoReg[$id] = $result;
		}

		return $userAttrForAutoReg;
	}

/**
 * 会員項目のレイアウト用のデータ取得
 *
 * @param bool $force 強制的に取得するフラグ
 * @return array 会員項目データ配列
 * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
 */
	public function getUserAttributesForLayout($force = false) {
		$this->loadModels([
			'DataType' => 'DataTypes.DataType',
			'UserRole' => 'UserRoles.UserRole',
			'UserAttributesRole' => 'UserRoles.UserAttributesRole',
		]);

		if (isset(self::$userAttributes) && !$force) {
			return self::$userAttributes;
		}

		//UserAttributeデータ取得
		$userAttributes = $this->find('all', $this->findOptionsForLayout());

		//UserAttributeChoiceデータ取得
		$userAttributeIds = Hash::extract($userAttributes, '{n}.UserAttribute.id');
		$userAttributeChoices = $this->__getUserAttributeChoice($userAttributeIds);

		//DataTypeデータ取得
		$dataTypes = $this->DataType->getDataTypes($this->UserAttributeSetting->editDataTypes);

		//UserRoleデータの取得
		$userRoles = $this->__getUserRole();

		//戻り値の設定
		$results = array();
		foreach ($userAttributes as $userAttribute) {
			$userAttributeId = $userAttribute['UserAttribute']['id'];
			$dataTypeKey = $userAttribute['UserAttributeSetting']['data_type_key'];

			$result = $userAttribute;

			if ($userAttribute['UserAttribute']['key'] === 'role_key') {
				//権限の設定
				$result['UserAttributeChoice'] = $userRoles;
				$result['UserAttributeChoice'] =
						Hash::insert($result['UserAttributeChoice'], '{n}.user_attribute_id', $userAttributeId);

			} elseif (isset($dataTypes[$dataTypeKey]['DataTypeChoice'])) {
				//DataTypeChoiceにデータがある場合
				$result['UserAttributeSetting']['data_type_key'] = DataType::DATA_TYPE_SELECT;
				$result['UserAttributeChoice'] = $dataTypes[$dataTypeKey]['DataTypeChoice'];
				$result['UserAttributeChoice'] =
						Hash::insert($result['UserAttributeChoice'], '{n}.user_attribute_id', $userAttributeId);

			} elseif (isset($userAttributeChoices[$userAttributeId])) {
				//UserAttributeChoiceにデータがある場合
				$result['UserAttributeChoice'] = $userAttributeChoices[$userAttributeId];
			}

			$row = $userAttribute['UserAttributeSetting']['row'];
			$col = $userAttribute['UserAttributeSetting']['col'];
			$weight = $userAttribute['UserAttributeSetting']['weight'];

			$results[$row][$col][$weight] = $result;
		}

		self::$userAttributes = $results;
		return $results;
	}

/**
 * UserAttributeChoiceの取得処理
 *
 * @param array $userAttributeIds UserAttribute.idのリスト
 * @return array UserAttributeChoiceデータ
 */
	private function __getUserAttributeChoice($userAttributeIds) {
		//UserAttributeChoiceデータ取得
		$userAttributeChoices = $this->UserAttributeChoice->find('all', array(
			'recursive' => -1,
			'conditions' => array(
				'user_attribute_id' => $userAttributeIds
			),
		));

		return Hash::combine($userAttributeChoices,
			'{n}.' . $this->UserAttributeChoice->alias . '.id',
			'{n}.' . $this->UserAttributeChoice->alias,
			'{n}.' . $this->UserAttributeChoice->alias . '.user_attribute_id'
		);
	}

/**
 * UserRoleの取得処理
 *
 * @return array UserRoleデータ
 */
	private function __getUserRole() {
		//UserRoleデータ取得
		$userRoles = $this->UserRole->find('all', array(
			'recursive' => -1,
			'fields' => array(
				'id', 'language_id', 'key', 'name'
			),
			'conditions' => array(
				'type' => UserRole::ROLE_TYPE_USER,
				'language_id' => Current::read('Language.id'),
			),
			'order' => 'id'
		));
		return Hash::combine($userRoles, '{n}.UserRole.id', '{n}.UserRole');
	}

/**
 * 会員項目のレイアウト用のデータ取得
 *
 * @param string $key UserAttributeキー
 * @return array 会員項目データ配列
 */
	public function getUserAttribute($key) {
		//UserAttributeデータ取得
		$result = $this->find('all', array(
			'recursive' => -1,
			'conditions' => array('key' => $key),
		));
		if (! $result) {
			return false;
		}
		$userAttribute['UserAttribute'] = Hash::combine(
			$result, '{n}.UserAttribute.id', '{n}.UserAttribute'
		);

		//UserAttributeSettingデータ取得
		$result = $this->UserAttributeSetting->find('first', array(
			'recursive' => -1,
			'conditions' => array('user_attribute_key' => $key),
		));
		if (! $result) {
			return false;
		}
		$userAttribute = Hash::merge($userAttribute, $result);

		//UserAttributeChoiceデータ取得
		$result = $this->UserAttributeChoice->find('all', array(
			'recursive' => -1,
			'conditions' => array('user_attribute_id' => array_keys($userAttribute['UserAttribute'])),
		));
		if (! $result) {
			return $userAttribute;
		}
		$userAttribute['UserAttributeChoice'] = Hash::combine($result,
			'{n}.' . $this->UserAttributeChoice->alias . '.language_id',
			'{n}.' . $this->UserAttributeChoice->alias,
			'{n}.' . $this->UserAttributeChoice->alias . '.weight'
		);

		return $userAttribute;
	}

/**
 * 会員項目の登録
 *
 * @param array $data リクエストデータ
 * @return bool Trueは成功。Falseはバリデーションエラー
 * @throws InternalErrorException
 */
	public function saveUserAttribute($data) {
		$this->loadModels([
			'UserAttributeSetting' => 'UserAttributes.UserAttributeSetting',
			'UserAttributeChoice' => 'UserAttributes.UserAttributeChoice',
		]);

		//トランザクションBegin
		$this->begin();

		//バリデーション
		$userAttributeKey = Hash::get(Hash::extract($data['UserAttribute'], '{n}.key'), '0');
		if (! $this->validateUserAttribute($data)) {
			return false;
		}
		$updated = (bool)$userAttributeKey;

		try {
			//UserAttributeの登録処理
			foreach ($data['UserAttribute'] as $i => $userAttribute) {
				$userAttribute['key'] = $userAttributeKey;
				$data['UserAttribute'][$i] = $this->save($userAttribute, false);
				if (! $data['UserAttribute'][$i]) {
					throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
				}
				$userAttributeKey = $data['UserAttribute'][$i]['UserAttribute']['key'];
			}

			//既存のデータ取得
			$before = $this->UserAttributeSetting->find('first', array(
				'recursive' => -1,
				'conditions' => array(
					'user_attribute_key' => $data['UserAttributeSetting']['user_attribute_key'],
				),
			));

			//UserAttributeSettingの登録処理
			$data['UserAttributeSetting']['user_attribute_key'] = $userAttributeKey;
			if (! $this->UserAttributeSetting->save($data['UserAttributeSetting'], false)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}

			//UserAttributeChoiceの登録処理
			//システム項目でなくて、ラジオボタン・チェックボタン・セレクトボックスの場合のみ
			if (! $data['UserAttributeSetting']['is_system']) {
				$this->UserAttributeChoice->saveUserAttributeChoices($data);
			}

			//UserAttributesRoleのデフォルトデータ登録処理
			if ($this->hasUpdatedUserAttributeRole($before, $data)) {
				$this->saveDefaultUserAttributeRoles($data);
			}

			//各自で受信可否を不可にした場合、User.is_xxxx_mail_receptionをONにする
			$this->saveSelfEmailSetting($before, $data);

			if (! $updated) {
				//フィールドの作成処理
				$this->addColumnByUserAttribute($data);
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
 * 会員項目権限の登録できるかどうか
 *
 * @param array $before 更新前データ
 * @param array $data リクエストデータ
 * @return bool Trueはあり。Falseはなし
 */
	public function hasUpdatedUserAttributeRole($before, $data) {
		if (! $before) {
			return true;
		}

		//UserAttributesRole登録の判断
		if ((int)Hash::get($before, 'UserAttributeSetting.only_administrator_readable') ===
					(int)Hash::get($data, 'UserAttributeSetting.only_administrator_readable') &&
			(int)Hash::get($before, 'UserAttributeSetting.only_administrator_editable') ===
					(int)Hash::get($data, 'UserAttributeSetting.only_administrator_editable')) {

			return false;
		} else {
			return true;
		}
	}

/**
 * UserAttributeのバリデーション処理
 *
 * @param array $data received post data
 * @return bool True on success, false on validation errors
 */
	public function validateUserAttribute($data) {
		//UserAttributeのバリデーション処理
		if (! isset($data['UserAttribute'])) {
			return false;
		}
		if (! $this->validateMany($data['UserAttribute'])) {
			return false;
		}

		//UserAttributeSettingのバリデーション処理
		if (! isset($data['UserAttributeSetting'])) {
			return false;
		}
		$this->UserAttributeSetting->set($data['UserAttributeSetting']);
		if (! $this->UserAttributeSetting->validates()) {
			$this->validationErrors = Hash::merge(
				$this->validationErrors,
				$this->UserAttributeSetting->validationErrors
			);
			return false;
		}

		//UserAttributeChoiceのバリデーション処理
		if (! isset($data['UserAttributeChoice'])) {
			return false;
		}
		foreach ($data['UserAttributeChoice'] as $choice) {
			if (! $this->UserAttributeChoice->validateMany($choice)) {
				$this->validationErrors = Hash::merge(
					$this->validationErrors,
					$this->UserAttributeChoice->validationErrors
				);
				return false;
			}
		}

		return true;
	}

/**
 * UserAttribute削除処理
 *
 * @param string $userAttributeKey received post data
 * @return mixed On success Model::$data if its not empty or true, false on failure
 * @throws InternalErrorException
 */
	public function deleteUserAttribute($userAttributeKey) {
		$this->loadModels([
			'UserAttributeSetting' => 'UserAttributes.UserAttributeSetting',
			'UserAttributeChoice' => 'UserAttributes.UserAttributeChoice',
		]);

		//トランザクションBegin
		$this->begin();

		$colUserAttributeKey = $this->UserAttributeSetting->alias . '.user_attribute_key';
		$userAttributeSetting = $this->UserAttributeSetting->find('first', array(
			'recursive' => -1,
			'conditions' => array($colUserAttributeKey => $userAttributeKey),
		));
		if (! $userAttributeSetting) {
			return false;
		}

		$userAttributeIds = $this->find('list', array(
			'recursive' => -1,
			'conditions' => array('key' => $userAttributeKey),
		));
		if (! $userAttributeIds) {
			return false;
		}
		$userAttributeIds = array_keys($userAttributeIds);

		try {
			//削除項目より後の順番を詰める
			$this->UserAttributeSetting->updateUserAttributeWeight(
				$userAttributeSetting[$this->UserAttributeSetting->alias]['row'],
				$userAttributeSetting[$this->UserAttributeSetting->alias]['col'],
				$userAttributeSetting[$this->UserAttributeSetting->alias]['weight'],
				-1, '>'
			);

			//UserAttributeの削除処理
			if (! $this->deleteAll(array($this->alias . '.key' => $userAttributeKey), false)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}
			//UserAttributeSettingの削除処理
			$conditions = array($colUserAttributeKey => $userAttributeKey);
			if (! $this->UserAttributeSetting->deleteAll($conditions, false)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}
			//UserAttributeChoiceの削除処理
			$conditions = array(
				$this->UserAttributeChoice->alias . '.user_attribute_id' => $userAttributeIds
			);
			if (! $this->UserAttributeChoice->deleteAll($conditions, false)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}

			//Alterテーブルは暗黙のコミットが起こるため、
			//一度トランザクションCommitする
			$this->commit();

		} catch (Exception $ex) {
			//トランザクションRollback
			$this->rollback($ex);
		}

		try {
			//フィールドの削除処理
			$this->dropColumnByUserAttribute($userAttributeSetting);
		} catch (Exception $ex) {
			//トランザクションRollback
			$this->rollback($ex);
		}

		return true;
	}

}
