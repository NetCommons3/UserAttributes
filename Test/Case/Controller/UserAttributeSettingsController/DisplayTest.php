<?php
/**
 * UserAttributeSettingsController::display()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsControllerTestCase', 'NetCommons.TestSuite');

/**
 * UserAttributeSettingsController::display()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\UserAttributes\Test\Case\Controller\UserAttributeSettingsController
 */
class UserAttributeSettingsControllerDisplayTest extends NetCommonsControllerTestCase {

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
	protected $_controller = 'user_attribute_settings';

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
 * display()アクションのテスト
 *
 * @return void
 */
	public function testSuccess() {
		$this->_mockForReturnTrue('UserAttributes.UserAttributeSetting', 'updateDisplay');

		//テスト実行
		$this->_testPostAction('post', array(), array('action' => 'display'), null, 'view');

		//チェック
		$header = $this->controller->response->header();
		$pattern = '/' . preg_quote('/user_attributes/user_attributes/index', '/') . '/';
		$this->assertRegExp($pattern, $header['Location']);
	}

/**
 * GETリクエストのテスト
 *
 * @return void
 */
	public function testGet() {
		//テスト実行
		$this->_testGetAction(array('action' => 'display'), null, 'BadRequestException', 'view');
	}

/**
 * GETリクエストのテスト(JSON形式)
 *
 * @return void
 */
	public function testGetJson() {
		//テスト実行
		$this->_testGetAction(array('action' => 'display'), null, 'BadRequestException', 'json');
	}

/**
 * UserAttributeSetting->updateDisplay()がエラーのテスト
 *
 * @return void
 */
	public function testupdateDisplayError() {
		$this->_mockForReturnFalse('UserAttributes.UserAttributeSetting', 'updateDisplay');

		//テスト実行
		$this->_testPostAction('post', array(), array('action' => 'display'), 'BadRequestException', 'view');
	}

/**
 * UserAttributeSetting->updateDisplay()がエラーのテスト
 *
 * @return void
 */
	public function testupdateDisplayErrorJson() {
		$this->_mockForReturnFalse('UserAttributes.UserAttributeSetting', 'updateDisplay');

		//テスト実行
		$this->_testPostAction('post', array(), array('action' => 'display'), 'BadRequestException', 'json');
	}

}
