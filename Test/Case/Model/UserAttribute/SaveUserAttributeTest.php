<?php
/**
 * UserAttribute::saveUserAttribute()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsModelTestCase', 'NetCommons.TestSuite');
App::uses('UserAttributeFixture', 'UserAttributes.Test/Fixture');
App::uses('UserAttributeSettingFixture', 'UserAttributes.Test/Fixture');
App::uses('UserAttributeChoiceFixture', 'UserAttributes.Test/Fixture');

/**
 * UserAttribute::saveUserAttribute()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\UserAttributes\Test\Case\Model\UserAttribute
 */
class UserAttributeSaveUserAttributeTest extends NetCommonsModelTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.plugin_manager.plugins_role',
		'plugin.user_attributes.user_attribute',
		'plugin.user_attributes.user_attribute_choice',
		'plugin.user_attributes.user_attribute_layout',
		'plugin.user_attributes.user_attribute_setting',
		'plugin.user_roles.user_role_setting',
	);

/**
 * Plugin name
 *
 * @var string
 */
	public $plugin = 'user_attributes';

/**
 * Model name
 *
 * @var string
 */
	protected $_modelName = 'UserAttribute';

/**
 * Method name
 *
 * @var string
 */
	protected $_methodName = 'saveUserAttribute';

/**
 * テストデータセット
 *
 * @param string $type 入力タイプ
 * @param bool $isNew 新規かどうか
 * @return void
 * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
 */
	private function __data($type = 'text', $isNew = false) {
		if ($type === 'text') {
			$data['UserAttribute'] = (new UserAttributeFixture())->records;
			$data['UserAttributeSetting'] = (new UserAttributeSettingFixture())->records[0];
			$data['UserAttributeChoice'] = array();
		} else {
			$data['UserAttribute'] = (new UserAttributeFixture())->records;
			$data['UserAttributeSetting'] = (new UserAttributeSettingFixture())->records[0];
			$data['UserAttributeSetting']['data_type_key'] = $type;
			$data['UserAttributeChoice'] = (new UserAttributeChoiceFixture())->records;
		}

		if ($isNew) {
			$data['UserAttribute'] = (new UserAttributeFixture())->records;
			$data['UserAttribute'] = Hash::insert($data['UserAttribute'], '{n}.id', null);
			$data['UserAttribute'] = Hash::insert($data['UserAttribute'], '{n}.key', null);
			$data['UserAttribute'] = Hash::insert($data['UserAttribute'], '{n}.name', 'Add name');

			$data['UserAttributeSetting'] = (new UserAttributeSettingFixture())->records[0];
			$data['UserAttributeSetting'] = Hash::insert($data['UserAttributeSetting'], 'id', null);
			$data['UserAttributeSetting'] = Hash::insert($data['UserAttributeSetting'], 'user_attribute_key', null);

			$data['UserAttributeChoice'] = array();
		} else {
			$data['UserAttribute'] = Hash::insert($data['UserAttribute'], '{n}.name', 'Edit name');
		}
		$data['UserAttributeSetting']['required'] = false;

		return $data;
	}

/**
 * 期待値用データ取得
 *
 * @return void
 */
	private function __actual() {
		$model = $this->_modelName;

		$actual = array();
		$actual['UserAttribute'] = $this->$model->find('all', array(
			'recursive' => -1,
			'order' => array('id' => 'asc'),
		));
		$actual['UserAttributeSetting'] = $this->$model->UserAttributeSetting->find('all', array(
			'recursive' => -1,
			'order' => array('id' => 'asc'),
		));
		$actual = Hash::remove($actual, '{s}.{n}.{s}.modified');
		$actual = Hash::remove($actual, '{s}.{n}.{s}.modified_user');

		return $actual;
	}

/**
 * チェック用データ取得
 *
 * @return void
 */
	private function __expected() {
		$model = $this->_modelName;

		$expected = array();
		$expected['UserAttribute'] = $this->$model->find('all', array(
			'recursive' => -1,
			'order' => array('id' => 'asc'),
		));
		$expected['UserAttributeSetting'] = $this->$model->UserAttributeSetting->find('all', array(
			'recursive' => -1,
			'order' => array('id' => 'asc'),
		));
		$expected = Hash::remove($expected, '{s}.{n}.{s}.modified');
		$expected = Hash::remove($expected, '{s}.{n}.{s}.modified_user');

		return $expected;
	}

/**
 * Saveのテスト(更新)
 *
 * @return void
 */
	public function testSave4Update() {
		$model = $this->_modelName;
		$method = $this->_methodName;

		//Mockの生成
		$mockMethods = array('saveDefaultUserAttributeRoles', 'addColumnByUserAttribute');
		$this->_mockForReturn($model, 'UserAttributes.UserAttribute', $mockMethods, true, 0);
		$this->_mockForReturnTrue($model, 'UserAttributes.UserAttributeChoice', 'saveUserAttributeChoices');

		//テストデータ
		$data = $this->__data();

		//実行前のデータ取得
		$actual = $this->__actual();
		$actual['UserAttribute'] = Hash::insert($actual['UserAttribute'], '{n}.UserAttribute.name', 'Edit name');
		$actual['UserAttributeSetting'][0]['UserAttributeSetting']['required'] = false;

		//テスト実行
		$result = $this->$model->$method($data);
		$this->assertNotEmpty($result);

		//チェック
		$expected = $this->__expected();
		$this->assertEquals($actual, $expected);
	}

/**
 * Saveのテスト(新規)
 *
 * @return void
 */
	public function testSave4Insert() {
		$model = $this->_modelName;
		$method = $this->_methodName;

		//Mockの生成
		$mockMethods = array('saveDefaultUserAttributeRoles', 'addColumnByUserAttribute');
		$this->_mockForReturnTrue($model, 'UserAttributes.UserAttribute', $mockMethods);
		$this->_mockForReturnTrue($model, 'UserAttributes.UserAttributeChoice', 'saveUserAttributeChoices');

		//テストデータ
		$data = $this->__data('text', true);

		//実行前のデータ取得
		$actual = $this->__actual();
		$actual = Hash::remove($actual, '{s}.{n}.{s}.created_user');
		$actual = Hash::remove($actual, '{s}.{n}.{s}.created');

		//テスト実行
		$result = $this->$model->$method($data);
		$this->assertNotEmpty($result);

		//チェック
		foreach ($data['UserAttribute'] as $userAttribute) {
			$userAttribute['id'] = (string)(count($actual['UserAttribute']) + 1);
			$userAttribute['key'] = OriginalKeyBehavior::generateKey($this->$model->alias, $this->$model->useDbConfig);
			$actual['UserAttribute'][] = array('UserAttribute' => $userAttribute);
		}
		$data['UserAttributeSetting']['id'] = '2';
		$data['UserAttributeSetting']['user_attribute_key'] = OriginalKeyBehavior::generateKey($this->$model->alias, $this->$model->useDbConfig);
		$actual['UserAttributeSetting'][] = array('UserAttributeSetting' => $data['UserAttributeSetting']);

		$expected = $this->__expected();
		$expected = Hash::remove($expected, '{s}.{n}.{s}.created_user');
		$expected = Hash::remove($expected, '{s}.{n}.{s}.created');

		$this->assertEquals($actual, $expected);
	}

/**
 * UserAttributeSetting.is_system=trueの場合、saveUserAttributeChoices()を実行しないテスト
 *
 * @return void
 */
	public function testIsSystem() {
		$model = $this->_modelName;
		$method = $this->_methodName;

		//Mockの生成
		$this->_mockForReturn($model, 'UserAttributes.UserAttribute', 'saveUserAttributeChoices', true, 0);

		//テストデータ
		$data = $this->__data();
		$data['UserAttributeSetting']['is_system'] = '1';

		//テスト実行
		$result = $this->$model->$method($data);
		$this->assertNotEmpty($result);
	}

/**
 * testOnlyAdmin用DataProvider
 *
 * ### 戻り値
 *  - data 登録データ
 *  - execProcess 処理実行有無(1:実行,0:未実行)
 *
 * @return array テストデータ
 */
	public function dataProviderOnlyAdmin() {
		$data = $this->__data();

		return array(
			array(Hash::merge($data, array('UserAttributeSetting' => array('only_administrator_readable' => false))), 1),
			array(Hash::merge($data, array('UserAttributeSetting' => array('only_administrator_editable' => false))), 1),
			array(Hash::merge($data, array()), 0),
		);
	}

/**
 * UserAttributeSetting.only_administrator_readableもしくはUserAttributeSetting.only_administrator_editableが変わった場合の
 * saveDefaultUserAttributeRoles()を実行有無テスト
 *
 * @param array $data 登録データ
 * @param int $execProcess 処理実行有無(1:実行,0:未実行)
 * @dataProvider dataProviderOnlyAdmin
 * @return void
 */
	public function testOnlyAdmin($data, $execProcess) {
		$model = $this->_modelName;
		$method = $this->_methodName;

		//Mockの生成
		$this->_mockForReturn($model, 'UserAttributes.UserAttribute', 'saveDefaultUserAttributeRoles', true, $execProcess);

		//テスト実行
		$result = $this->$model->$method($data);
		$this->assertNotEmpty($result);
	}

/**
 * SaveのExceptionError用DataProvider
 *
 * ### 戻り値
 *  - data 登録データ
 *  - mockModel Mockのモデル
 *  - mockMethod Mockのメソッド
 *
 * @return array テストデータ
 */
	public function dataProviderSaveOnExceptionError() {
		return array(
			array($this->__data(), 'UserAttributes.UserAttribute', 'save'),
			array($this->__data(), 'UserAttributes.UserAttributeSetting', 'save'),
		);
	}

/**
 * SaveのExceptionErrorテスト
 *
 * @param array $data 登録データ
 * @param string $mockModel Mockのモデル
 * @param string $mockMethod Mockのメソッド
 * @dataProvider dataProviderSaveOnExceptionError
 * @return void
 */
	public function testSaveOnExceptionError($data, $mockModel, $mockMethod) {
		$model = $this->_modelName;
		$method = $this->_methodName;

		$this->_mockForReturnFalse($model, $mockModel, $mockMethod);

		$this->setExpectedException('InternalErrorException');
		$this->$model->$method($data);
	}

/**
 * validateUserAttribute()エラーのテスト
 *
 * @return void
 */
	public function testSaveOnValidateUserAttribute() {
		$model = $this->_modelName;
		$method = $this->_methodName;

		//Mockの生成
		$this->_mockForReturnFalse($model, 'UserAttributes.UserAttribute', 'validateUserAttribute');

		//テストデータ
		$data = $this->__data();

		//テスト実行
		$result = $this->$model->$method($data);

		//チェック
		$this->assertFalse($result);
	}

/**
 * $data['UserAttribute'][0][key]がないエラーのテスト
 *
 * @return void
 */
	public function testNoUserAttributeKey() {
		$model = $this->_modelName;
		$method = $this->_methodName;

		//テストデータ
		$data = $this->__data();
		$data['UserAttribute'] = Hash::remove($data['UserAttribute'], '{n}.key');

		//テスト実行
		$result = $this->$model->$method($data);

		//チェック
		$this->assertFalse($result);
	}

}
