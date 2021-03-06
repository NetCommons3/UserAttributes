<?php
/**
 * UserAttributesController::add()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsControllerTestCase', 'NetCommons.TestSuite');

/**
 * UserAttributesController::add()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\UserAttributes\Test\Case\Controller\UserAttributesController
 */
class UserAttributesControllerAddTest extends NetCommonsControllerTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.user_attributes.plugin4test',
		'plugin.user_attributes.plugins_role4test',
		'plugin.user_attributes.user_attribute',
		'plugin.user_attributes.user_attribute_choice',
		'plugin.user_attributes.user_attribute_layout',
		'plugin.user_attributes.user_attribute_setting',
	);

/**
 * Plugin name
 *
 * @var string
 */
	public $plugin = 'user_attributes';

/**
 * Controller name
 *
 * @var string
 */
	protected $_controller = 'user_attributes';

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		//ログイン
		TestAuthGeneral::login($this);
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		//ログアウト
		TestAuthGeneral::logout($this);

		parent::tearDown();
	}

/**
 * テストデータセット
 *
 * @return void
 */
	private function __data() {
		$data = array(
			'UserAttributeSetting' => array(
				'id' => '',
				'row' => '2',
				'col' => '1',
				'weight' => 1,
				'display' => '1',
				'is_system' => '0',
				'user_attribute_key' => '',
				'display_label' => '1',
				'data_type_key' => 'text',
				'is_multilingualization' => '1',
				'required' => '0',
				'only_administrator_readable' => '0',
				'only_administrator_editable' => '0',
				'self_public_setting' => '0',
				'self_email_setting' => '0',
			),
			'UserAttribute' => array(
				0 => array (
					'id' => '',
					'key' => '',
					'language_id' => '1',
					'name' => 'test2',
					'description' => '',
				),
				1 => array (
					'id' => '',
					'key' => '',
					'language_id' => '2',
					'name' => 'test2',
					'description' => '',
				),
			),
		);

		return $data;
	}

/**
 * add()のGETアクションのテスト
 *
 * @return void
 */
	public function testAddGet() {
		//テスト実行
		$this->_testGetAction(array('action' => 'add', '2', '1'), array('method' => 'assertNotEmpty'), null, 'view');

		//チェック
		$this->assertTextContains('ng-controller="UserAttributes"', $this->view);
		$this->assertInput('form', null, 'user_attributes/user_attributes/add/2/1', $this->view);
		$this->assertTextNotContains('user_attributes/user_attributes/delete', $this->view);
		$this->assertEquals($this->controller->UserAttributeSetting->addDataTypes, $this->controller->DataTypeForm->dataTypes );

		$this->assertArrayHasKey('UserAttribute', $this->controller->data);
		$this->assertCount(2, $this->controller->data['UserAttribute']);
		$this->assertArrayHasKey('key', $this->controller->data['UserAttribute'][0]);
		$this->assertArrayHasKey('key', $this->controller->data['UserAttribute'][1]);
		$this->assertArrayHasKey('UserAttributeSetting', $this->controller->data);
		$this->assertArrayHasKey('user_attribute_key', $this->controller->data['UserAttributeSetting']);
	}

/**
 * add()のPOSTアクションのテスト
 *
 * @return void
 */
	public function testAddPost() {
		$this->_mockForReturnTrue('UserAttributes.UserAttributeChoice', 'validateRequestData');
		$this->_mockForReturnTrue('UserAttributes.UserAttribute', 'saveUserAttribute');

		//テスト実行
		$data = $this->__data();
		$this->_testPostAction('post', $data, array('action' => 'add', '2', '1'), null, 'view');

		//チェック
		$header = $this->controller->response->header();
		$this->assertTextContains('/user_attributes/user_attributes/index', $header['Location']);
	}

/**
 * UserAttributeChoice->validateRequestData()のエラー発生テスト
 *
 * @return void
 */
	public function testAddPostChoiceValidateError() {
		$this->_mockForReturnFalse('UserAttributes.UserAttributeChoice', 'validateRequestData');

		//テスト実行
		$data = $this->__data();
		$this->_testPostAction('post', $data, array('action' => 'add', '2', '1'), 'BadRequestException', 'view');
	}

/**
 * UserAttributeChoice->validateRequestData()のエラー発生テスト(JSON形式)
 *
 * @return void
 */
	public function testAddPostChoiceValidateErrorJson() {
		$this->_mockForReturnFalse('UserAttributes.UserAttributeChoice', 'validateRequestData');

		//テスト実行
		$data = $this->__data();
		$this->_testPostAction('post', $data, array('action' => 'add', '2', '1'), 'BadRequestException', 'json');
	}

/**
 * UserAttribute->validationErrorsのテスト
 *
 * @return void
 */
	public function testAddPostValidateError() {
		$this->_mockForReturn('UserAttributes.UserAttributeChoice', 'validateRequestData', null);

		//テスト実行
		$data = $this->__data();
		$data = Hash::remove($data, 'UserAttribute.{n}.name');
		$this->_testPostAction('post', $data, array('action' => 'add', '2', '1'), null, 'view');

		//チェック
		$this->assertTextContains('ng-controller="UserAttributes"', $this->view);
		$this->assertInput('form', null, 'user_attributes/user_attributes/add/2/1', $this->view);
		$this->assertTextNotContains('user_attributes/user_attributes/delete', $this->view);
		$this->assertTextContains(
			sprintf(__d('net_commons', 'Please input %s.'), __d('user_attributes', 'Item name')),
			$this->view
		);
	}

}
