<?php
/**
 * UserAttributeLayoutComponent::startup()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsControllerTestCase', 'NetCommons.TestSuite');
App::uses('UserAttributeLayoutFixture', 'UserAttributes.Test/Fixture');

/**
 * UserAttributeLayoutComponent::startup()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\UserAttributes\Test\Case\Controller\Component\UserAttributeLayoutComponent
 */
class UserAttributeLayoutComponentStartupTest extends NetCommonsControllerTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
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
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		//テストプラグインのロード
		NetCommonsCakeTestCase::loadTestPlugin($this, 'UserAttributes', 'TestUserAttributes');
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
 * startup()のテスト
 *
 * @return void
 */
	public function testStartup() {
		//テストコントローラ生成
		$this->generateNc('TestUserAttributes.TestUserAttributeLayoutComponent');
		$this->controller->UserAttribute = $this->getMockForModel('UserAttributes.UserAttribute');
		$this->_mockForReturnTrue('UserAttributes.UserAttribute', 'getUserAttributesForLayout');

		//ログイン
		TestAuthGeneral::login($this);

		//テスト実行
		$this->_testGetAction('/test_user_attributes/test_user_attribute_layout_component/index',
				array('method' => 'assertNotEmpty'), null, 'view');

		//チェック
		$pattern = '/' . preg_quote('Controller/Component/UserAttributeLayoutComponent', '/') . '/';
		$this->assertRegExp($pattern, $this->view);

		$this->assertTrue($this->vars['userAttributes']);

		$records = (new UserAttributeLayoutFixture())->records;
		$actual[0]['UserAttributeLayout'] = $records[0];
		$actual[1]['UserAttributeLayout'] = $records[1];
		$actual[2]['UserAttributeLayout'] = $records[2];
		$this->assertEquals($this->vars['userAttributeLayouts'], $actual);
	}

/**
 * startup()のテスト
 *
 * @return void
 */
	public function testStartupRequestAction() {
		//テストコントローラ生成
		$this->generateNc('TestUserAttributes.TestUserAttributeLayoutComponent');

		//ログイン
		TestAuthGeneral::login($this);

		//テスト実行
		$this->_testGetAction('/test_user_attributes/test_user_attribute_layout_component/index_request_action',
				array('method' => 'assertNotEmpty'), null, 'view');

		//チェック
		$pattern = '/' . preg_quote('Controller/Component/UserAttributeLayoutComponent', '/') . '/';
		$this->assertRegExp($pattern, $this->view);

		$pattern = '/' . preg_quote('Controller/Component/UserAttributeLayoutComponent/index_request_action', '/') . '/';
		$this->assertRegExp($pattern, $this->view);
	}

}
