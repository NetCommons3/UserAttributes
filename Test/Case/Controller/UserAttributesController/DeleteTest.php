<?php
/**
 * UserAttributesController::delete()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsControllerTestCase', 'NetCommons.TestSuite');

/**
 * UserAttributesController::delete()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\UserAttributes\Test\Case\Controller\UserAttributesController
 */
class UserAttributesControllerDeleteTest extends NetCommonsControllerTestCase {

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
 * delete()アクションのGETパラメータテスト
 *
 * @return void
 */
	public function testGet() {
		//テスト実行
		$this->_testGetAction(array('action' => 'delete'), null, 'BadRequestException', 'view');
	}

/**
 * delete()アクションのGETパラメータテスト(JSON形式)
 *
 * @return void
 */
	public function testGetJson() {
		//テスト実行
		$this->_testGetAction(array('action' => 'delete'), null, 'BadRequestException', 'json');
	}

/**
 * delete()アクションのテスト
 *
 * @return void
 */
	public function testDelete() {
		$this->_mockForReturnTrue('UserAttributes.UserAttribute', 'deleteUserAttribute');

		//テスト実行
		$this->_testPostAction('delete', array(), array('action' => 'delete'), null, 'view');

		//チェック
		$header = $this->controller->response->header();
		$this->assertTextContains('/user_attributes/user_attributes/index', $header['Location']);
	}

/**
 * delete()アクションのExceptionエラーテスト
 *
 * @return void
 */
	public function testDeleteOnExceptionError() {
		$this->_mockForReturnFalse('UserAttributes.UserAttribute', 'deleteUserAttribute');

		//テスト実行
		$this->_testPostAction('delete', array(), array('action' => 'delete'), 'BadRequestException', 'view');
	}

/**
 * delete()アクションのExceptionエラーテスト(JSON形式)
 *
 * @return void
 */
	public function testDeleteOnExceptionErrorJson() {
		$this->_mockForReturnFalse('UserAttributes.UserAttribute', 'deleteUserAttribute');

		//テスト実行
		$this->_testPostAction('delete', array(), array('action' => 'delete'), 'BadRequestException', 'json');
	}

}
