<?php
/**
 * UserAttributeHelper::moveSettingRowMenu()のテスト
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
 * UserAttributeHelper::moveSettingRowMenu()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\UserAttributes\Test\Case\View\Helper\UserAttributeHelper
 */
class UserAttributeHelperMoveSettingRowMenuTest extends NetCommonsHelperTestCase {

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
 * moveSettingRightMenu()のテストチェック
 *
 * @param string $result テスト結果
 * @param int $userAttrSettingId UserAttributeSetting.id
 * @param int $updRow 更新する段
 * @param bool $disabled disabledかどうか
 * @return void
 */
	private function __assertMoveSettingRowMenu($result, $userAttrSettingId, $updRow, $disabled) {
		$formName = 'UserAttributeMoveForm' . $userAttrSettingId . 'Row' . $updRow;
		//チェック
		if ($disabled) {
			$pattern = '<li class="disabled"><a href=""> <span>' . sprintf(__d('user_attributes', 'Go to %s row'), $updRow) . '</span>';
			$this->assertContains($pattern, $result);
			$this->assertNotContains('<a href="" onclick="$(\'form[name=' . $formName . ']\')[0].submit()">', $result);
		} else {
			$pattern = '<li class="disabled"><a href=""> <span>' . sprintf(__d('user_attributes', 'Go to %s row'), $updRow) . '</span>';
			$this->assertNotContains($pattern, $result);
			$this->assertContains('<a href="" onclick="$(\'form[name=' . $formName . ']\')[0].submit()">', $result);
		}
		$this->assertContains('<span>' . sprintf(__d('user_attributes', 'Go to %s row'), $updRow) . '</span>', $result);
		$this->assertInput('form', $formName, '/user_attribute_settings/move/' . $userAttrSettingId, $result);
		$this->assertInput('input', '_method', 'PUT', $result);
		$this->assertInput('input', 'data[UserAttributeSetting][id]', $userAttrSettingId, $result);
		$this->assertInput('input', 'data[UserAttributeSetting][row]', $updRow, $result);
		$this->assertNotContains('data[UserAttributeSetting][col]', $result);
		$this->assertNotContains('data[UserAttributeSetting][weight]', $result);
	}

/**
 * moveSettingRowMenu()のテスト
 *
 * @return void
 */
	public function testMoveSettingRowMenu() {
		//テストデータ生成
		$viewVars = $this->__viewVars;
		$requestData = array();

		//Helperロード
		$this->loadHelper('UserAttributes.UserAttribute', $viewVars, $requestData);

		//データ生成
		$layout['UserAttributeLayout'] = (new UserAttributeLayoutFixture())->records[0];
		$userAttribute = Hash::get($viewVars['userAttributes'], '1.1.1');

		//テスト実施
		$result = $this->UserAttribute->moveSettingRowMenu($layout, $userAttribute);

		//チェック
		$this->__assertMoveSettingRowMenu($result, '1', '1', true);
		$this->__assertMoveSettingRowMenu($result, '1', '2', false);
		$this->__assertMoveSettingRowMenu($result, '1', '3', false);
	}

}
