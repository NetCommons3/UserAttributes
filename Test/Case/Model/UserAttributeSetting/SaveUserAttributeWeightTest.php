<?php
/**
 * UserAttributeSetting::saveUserAttributeWeight()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsModelTestCase', 'NetCommons.TestSuite');

/**
 * UserAttributeSetting::saveUserAttributeWeight()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\UserAttributes\Test\Case\Model\UserAttributeSetting
 */
class UserAttributeSettingSaveUserAttributeWeightTest extends NetCommonsModelTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.user_attributes.user_attribute',
		'plugin.user_attributes.user_attribute_choice',
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
	protected $_modelName = 'UserAttributeSetting';

/**
 * Method name
 *
 * @var string
 */
	protected $_methodName = 'saveUserAttributeWeight';

/**
 * saveUserAttributeWeight()のテスト
 *
 * @return void
 */
	public function testSaveUserAttributeWeight() {
		$model = $this->_modelName;
		$methodName = $this->_methodName;

		//期待値のデータ取得
		$expected = $this->$model->find('all', array(
			'recursive' => -1,
			'order' => array('id' => 'asc'),
		));
		$expected = Hash::combine($expected, '{n}.UserAttributeSetting.id', '{n}');
		$expected = Hash::remove($expected, '5.UserAttributeSetting.modified');
		$expected = Hash::remove($expected, '5.UserAttributeSetting.modified_user');
		$expected['5']['UserAttributeSetting']['weight'] = '6';
		$expected['16']['UserAttributeSetting']['weight'] = '4';
		$expected['17']['UserAttributeSetting']['weight'] = '5';

		//データ生成
		$data = array(
			'UserAttributeSetting' => array('id' => '5', 'row' => '1', 'col' => '2', 'weight' => '6')
		);

		//テスト実施
		$result = $this->$model->$methodName($data);

		//チェック
		$this->assertTrue($result);

		$actual = $this->$model->find('all', array(
			'recursive' => -1,
			'order' => array('id' => 'asc'),
		));
		$actual = Hash::combine($actual, '{n}.UserAttributeSetting.id', '{n}');
		$actual = Hash::remove($actual, '5.UserAttributeSetting.modified');
		$actual = Hash::remove($actual, '5.UserAttributeSetting.modified_user');

		$this->assertEquals($expected, $actual);
	}

/**
 * saveUserAttributeWeight()のテスト(段の移動のテスト)
 *
 * @return void
 */
	public function testSaveUserAttributeWeightOnlyRow() {
		$model = $this->_modelName;
		$methodName = $this->_methodName;

		//期待値のデータ取得
		$expected = $this->$model->find('all', array(
			'recursive' => -1,
			'order' => array('id' => 'asc'),
		));
		$expected = Hash::combine($expected, '{n}.UserAttributeSetting.id', '{n}');
		$expected = Hash::remove($expected, '5.UserAttributeSetting.modified');
		$expected = Hash::remove($expected, '5.UserAttributeSetting.modified_user');
		$expected['16']['UserAttributeSetting']['weight'] = '4';
		$expected['17']['UserAttributeSetting']['weight'] = '5';
		$expected['18']['UserAttributeSetting']['weight'] = '6';
		$expected['12']['UserAttributeSetting']['weight'] = '7';
		$expected['13']['UserAttributeSetting']['weight'] = '8';
		$expected['14']['UserAttributeSetting']['weight'] = '9';
		$expected['15']['UserAttributeSetting']['weight'] = '10';

		$expected['5']['UserAttributeSetting']['row'] = '3';
		$expected['5']['UserAttributeSetting']['col'] = '1';
		$expected['5']['UserAttributeSetting']['weight'] = '1';

		//データ生成
		$data = array(
			'UserAttributeSetting' => array('id' => '5', 'row' => '3')
		);

		//テスト実施
		$result = $this->$model->$methodName($data);

		//チェック
		$this->assertTrue($result);

		$actual = $this->$model->find('all', array(
			'recursive' => -1,
			'order' => array('id' => 'asc'),
		));
		$actual = Hash::combine($actual, '{n}.UserAttributeSetting.id', '{n}');
		$actual = Hash::remove($actual, '5.UserAttributeSetting.modified');
		$actual = Hash::remove($actual, '5.UserAttributeSetting.modified_user');

		$this->assertEquals($expected, $actual);
	}

/**
 * データエラーのテスト
 *
 * @return void
 */
	public function testDataError() {
		//データ生成
		$data = array(
			'UserAttributeSetting' => array('id' => '999', 'row' => '1', 'col' => '2', 'weight' => '6')
		);

		//テスト実施
		$model = $this->_modelName;
		$methodName = $this->_methodName;
		$result = $this->$model->$methodName($data);

		//チェック
		$this->assertFalse($result);
	}

/**
 * ErrorExceptionのテスト
 *
 * @return void
 */
	public function testErrorException() {
		$this->setExpectedException('InternalErrorException');

		//データ生成
		$this->_mockForReturnFalse('UserAttributeSetting', 'UserAttributes.UserAttributeSetting', 'save');
		$data = array(
			'UserAttributeSetting' => array('id' => '5', 'row' => '1', 'col' => '2', 'weight' => '6')
		);

		//テスト実施
		$model = $this->_modelName;
		$methodName = $this->_methodName;
		$this->$model->$methodName($data);
	}

}
