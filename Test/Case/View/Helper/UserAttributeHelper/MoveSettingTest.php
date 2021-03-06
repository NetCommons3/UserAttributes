<?php
/**
 * UserAttributeHelper::moveSetting()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsHelperTestCase', 'NetCommons.TestSuite');
App::uses('UserAttributeLayoutFixture', 'UserAttributes.Test/Fixture');

/**
 * UserAttributeHelper::moveSetting()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\UserAttributes\Test\Case\View\Helper\UserAttributeHelper
 */
class UserAttributeHelperMoveSettingTest extends NetCommonsHelperTestCase {

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
		'plugin.user_roles.user_attributes_role4edit',
		'plugin.user_attributes.user_role_setting4test',
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
		Current::$current['User']['role_key'] = UserRole::USER_ROLE_KEY_SYSTEM_ADMINISTRATOR;

		//テストデータ生成
		$UserAttribute = ClassRegistry::init('UserAttributes.UserAttribute');
		$UserAttributeLayout = ClassRegistry::init('UserAttributes.UserAttributeLayout');

		UserAttribute::$userAttributes = null;
		$this->__viewVars['userAttributes'] = $UserAttribute->getUserAttributesForLayout();
		$this->__viewVars['userAttributeLayouts'] = $UserAttributeLayout->find('all', array(
			'fields' => array('id', 'col'),
			'recursive' => -1,
			'order' => array('id' => 'asc'),
		));
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		Current::$current['User']['role_key'] = null;
		parent::tearDown();
	}

/**
 * moveSetting()のテスト
 *
 * @return void
 */
	public function testMoveSetting() {
		//テストデータ生成
		$viewVars = $this->__viewVars;
		$requestData = array();

		//Helperロード
		$this->loadHelper('UserAttributes.UserAttribute', $viewVars, $requestData);

		//データ生成
		$layout['UserAttributeLayout'] = (new UserAttributeLayoutFixture())->records[0];
		$userAttribute = Hash::get($viewVars['userAttributes'], '1.1.1');

		//テスト実施
		$result = $this->UserAttribute->moveSetting($layout, $userAttribute);

		//チェック
		$pattern = '/<button.*?class=".*?' . preg_quote('dropdown-toggle', '/') . '".*?>/';
		$this->assertRegExp($pattern, $result);

		$pattern = '/<ul class="dropdown-menu">/';
		$this->assertRegExp($pattern, $result);
	}

}
