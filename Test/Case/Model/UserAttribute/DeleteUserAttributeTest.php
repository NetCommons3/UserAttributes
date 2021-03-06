<?php
/**
 * UserAttribute::deleteUserAttribute()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsDeleteTest', 'NetCommons.TestSuite');
App::uses('UserAttributeSetting4editFixture', 'UserAttributes.Test/Fixture');

/**
 * UserAttribute::deleteUserAttribute()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\UserAttributes\Test\Case\Model\UserAttribute
 */
class UserAttributeDeleteUserAttributeTest extends NetCommonsDeleteTest {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.user_attributes.user_attribute4edit',
		'plugin.user_attributes.user_attribute_choice4edit',
		'plugin.user_attributes.user_attribute_layout',
		'plugin.user_attributes.user_attribute_setting4edit',
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
	protected $_methodName = 'deleteUserAttribute';

/**
 * Delete用DataProvider
 *
 * ### 戻り値
 *  - data: 削除データ
 *  - associationModels: 削除確認の関連モデル array(model => conditions)
 *
 * @return array テストデータ
 */
	public function dataProviderDelete() {
		$userAttributeKey = (new UserAttributeSetting4editFixture())->records[1]['user_attribute_key'];
		$association = array(
			'UserAttributeSetting' => array(
				'user_attribute_key' => $userAttributeKey
			),
			'UserAttributeChoice' => array(
				'user_attribute_id' => array('3', '4')
			),
		);

		$results = array();
		$results[0] = array($userAttributeKey, $association);

		return $results;
	}

/**
 * Deleteのテスト
 *
 * @param array $data 削除データ
 * @param array $associationModels 削除確認の関連モデル array(model => conditions)
 * @dataProvider dataProviderDelete
 * @return void
 */
	public function testDelete($data, $associationModels = null) {
		$model = $this->_modelName;

		//Mockの生成
		$this->_mockForReturnTrue($model, 'UserAttributes.UserAttribute', 'dropColumnByUserAttribute');

		//テスト実行
		parent::testDelete($data, $associationModels);

		//チェック
		$actual = $this->$model->UserAttributeSetting->find('all', array(
			'recursive' => -1,
			'order' => array('id' => 'asc')
		));
		$this->assertCount(2, $actual);
		$this->assertEquals('3', Hash::get($actual, '1.UserAttributeSetting.id'));
		$this->assertEquals('system_attribute_key', Hash::get($actual, '1.UserAttributeSetting.user_attribute_key'));
		$this->assertEquals('2', Hash::get($actual, '1.UserAttributeSetting.weight'));
	}

/**
 * UserAttribute->find()がエラーのテスト
 *
 * @return void
 */
	public function testGetOnUserAttributeError() {
		$model = $this->_modelName;
		$methodName = $this->_methodName;

		//データ生成
		$this->_mockForReturnFalse($model, 'UserAttributes.UserAttribute', 'find');
		$userAttributeKey = (new UserAttributeSetting4editFixture())->records[0]['user_attribute_key'];

		//テスト実施
		$result = $this->$model->$methodName($userAttributeKey);

		//チェック
		$this->assertFalse($result);
	}

/**
 * UserAttributeSetting->find()がエラーのテスト
 *
 * @return void
 */
	public function testGetOnUserAttributeSettingError() {
		$model = $this->_modelName;
		$methodName = $this->_methodName;

		//データ生成
		$this->_mockForReturnFalse($model, 'UserAttributes.UserAttributeSetting', 'find');
		$userAttributeKey = (new UserAttributeSetting4editFixture())->records[0]['user_attribute_key'];

		//テスト実施
		$result = $this->$model->$methodName($userAttributeKey);

		//チェック
		$this->assertFalse($result);
	}

/**
 * ExceptionError用DataProvider
 *
 * ### 戻り値
 *  - data 登録データ
 *  - mockModel Mockのモデル
 *  - mockMethod Mockのメソッド
 *
 * @return array テストデータ
 */
	public function dataProviderDeleteOnExceptionError() {
		$userAttributeKey = (new UserAttributeSetting4editFixture())->records[0]['user_attribute_key'];

		return array(
			array($userAttributeKey, 'UserAttributes.UserAttribute', 'deleteAll'),
			array($userAttributeKey, 'UserAttributes.UserAttributeSetting', 'deleteAll'),
			array($userAttributeKey, 'UserAttributes.UserAttributeChoice', 'deleteAll'),
		);
	}

/**
 * UserAttributeBehavior::dropColumnByUserAttribute()でエラーが発生したときのテスト
 *
 * @return void
 */
	public function testDeleteOnDropColumnError() {
		$model = $this->_modelName;
		$method = $this->_methodName;

		$this->setExpectedException('MigrationException');

		//データ生成
		$userAttributeKey = (new UserAttributeSetting4editFixture())->records[0]['user_attribute_key'];

		//テスト実行
		$this->$model->$method($userAttributeKey);
	}

}
