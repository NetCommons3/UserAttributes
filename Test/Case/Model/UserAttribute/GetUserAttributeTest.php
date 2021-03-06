<?php
/**
 * UserAttribute::getUserAttribute()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsGetTest', 'NetCommons.TestSuite');

/**
 * UserAttribute::getUserAttribute()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\UserAttributes\Test\Case\Model\UserAttribute
 */
class UserAttributeGetUserAttributeTest extends NetCommonsGetTest {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.user_attributes.user_attribute4test',
		'plugin.user_attributes.user_attribute_choice4test',
		'plugin.user_attributes.user_attribute_layout',
		'plugin.user_attributes.user_attribute_setting4test',
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
	protected $_methodName = 'getUserAttribute';

/**
 * getUserAttribute()のテスト(例：性別)
 *
 * @return void
 */
	public function testGetUserAttribute() {
		$model = $this->_modelName;
		$methodName = $this->_methodName;

		//データ生成
		$key = 'sex';

		//テスト実施
		$result = $this->$model->$methodName($key);

		//チェック
		$this->assertCount(3, $result);
		$this->assertEquals(array('UserAttribute', 'UserAttributeSetting', 'UserAttributeChoice'), array_keys($result));

		$this->assertCount(2, $result['UserAttribute']);
		$this->assertEquals('8', Hash::get($result, 'UserAttribute.8.id'));
		$this->assertEquals('2', Hash::get($result, 'UserAttribute.8.language_id'));
		$this->assertEquals('sex', Hash::get($result, 'UserAttribute.8.key'));
		$this->assertEquals('28', Hash::get($result, 'UserAttribute.28.id'));
		$this->assertEquals('1', Hash::get($result, 'UserAttribute.28.language_id'));
		$this->assertEquals('sex', Hash::get($result, 'UserAttribute.28.key'));

		$this->assertEquals('8', Hash::get($result, 'UserAttributeSetting.id'));
		$this->assertEquals('sex', Hash::get($result, 'UserAttributeSetting.user_attribute_key'));
		$this->assertEquals('radio', Hash::get($result, 'UserAttributeSetting.data_type_key'));

		$this->assertCount(3, $result['UserAttributeChoice']);
		$this->assertEquals('1', Hash::get($result, 'UserAttributeChoice.1.2.id'));
		$this->assertEquals('2', Hash::get($result, 'UserAttributeChoice.1.2.language_id'));
		$this->assertEquals('8', Hash::get($result, 'UserAttributeChoice.1.2.user_attribute_id'));
		$this->assertEquals('sex_no_setting', Hash::get($result, 'UserAttributeChoice.1.2.key'));
		$this->assertEquals('4', Hash::get($result, 'UserAttributeChoice.1.1.id'));
		$this->assertEquals('1', Hash::get($result, 'UserAttributeChoice.1.1.language_id'));
		$this->assertEquals('28', Hash::get($result, 'UserAttributeChoice.1.1.user_attribute_id'));
		$this->assertEquals('sex_no_setting', Hash::get($result, 'UserAttributeChoice.1.1.key'));
		$this->assertEquals('2', Hash::get($result, 'UserAttributeChoice.2.2.id'));
		$this->assertEquals('2', Hash::get($result, 'UserAttributeChoice.2.2.language_id'));
		$this->assertEquals('8', Hash::get($result, 'UserAttributeChoice.2.2.user_attribute_id'));
		$this->assertEquals('sex_male', Hash::get($result, 'UserAttributeChoice.2.2.key'));
		$this->assertEquals('5', Hash::get($result, 'UserAttributeChoice.2.1.id'));
		$this->assertEquals('1', Hash::get($result, 'UserAttributeChoice.2.1.language_id'));
		$this->assertEquals('28', Hash::get($result, 'UserAttributeChoice.2.1.user_attribute_id'));
		$this->assertEquals('sex_male', Hash::get($result, 'UserAttributeChoice.2.1.key'));
		$this->assertEquals('3', Hash::get($result, 'UserAttributeChoice.3.2.id'));
		$this->assertEquals('2', Hash::get($result, 'UserAttributeChoice.3.2.language_id'));
		$this->assertEquals('8', Hash::get($result, 'UserAttributeChoice.3.2.user_attribute_id'));
		$this->assertEquals('sex_female', Hash::get($result, 'UserAttributeChoice.3.2.key'));
		$this->assertEquals('6', Hash::get($result, 'UserAttributeChoice.3.1.id'));
		$this->assertEquals('1', Hash::get($result, 'UserAttributeChoice.3.1.language_id'));
		$this->assertEquals('28', Hash::get($result, 'UserAttributeChoice.3.1.user_attribute_id'));
		$this->assertEquals('sex_female', Hash::get($result, 'UserAttributeChoice.3.1.key'));
	}

/**
 * UserAttributeChoiceのデータなしテスト
 *
 * @return void
 */
	public function testNoUserAttributeChoice() {
		$model = $this->_modelName;
		$methodName = $this->_methodName;

		//データ生成
		$key = 'username';

		//テスト実施
		$result = $this->$model->$methodName($key);

		//チェック
		$this->assertCount(2, $result);
		$this->assertEquals(array('UserAttribute', 'UserAttributeSetting'), array_keys($result));

		$this->assertCount(2, $result['UserAttribute']);
		$this->assertEquals('2', Hash::get($result, 'UserAttribute.2.id'));
		$this->assertEquals('2', Hash::get($result, 'UserAttribute.2.language_id'));
		$this->assertEquals('username', Hash::get($result, 'UserAttribute.2.key'));
		$this->assertEquals('22', Hash::get($result, 'UserAttribute.22.id'));
		$this->assertEquals('1', Hash::get($result, 'UserAttribute.22.language_id'));
		$this->assertEquals('username', Hash::get($result, 'UserAttribute.22.key'));

		$this->assertEquals('2', Hash::get($result, 'UserAttributeSetting.id'));
		$this->assertEquals('username', Hash::get($result, 'UserAttributeSetting.user_attribute_key'));
		$this->assertEquals('text', Hash::get($result, 'UserAttributeSetting.data_type_key'));
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
		$key = 'sex';

		//テスト実施
		$result = $this->$model->$methodName($key);

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
		$key = 'sex';

		//テスト実施
		$result = $this->$model->$methodName($key);

		//チェック
		$this->assertFalse($result);
	}

}
