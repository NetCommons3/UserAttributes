<?php
/**
 * View/Elements/UserAttributes/render_index_colのテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsControllerTestCase', 'NetCommons.TestSuite');

/**
 * View/Elements/UserAttributes/render_index_colのテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\UserAttributes\Test\Case\View\Elements\UserAttributes\RenderIndexCol
 */
class UserAttributesViewElementsUserAttributesRenderIndexColTest extends NetCommonsControllerTestCase {

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

		//テストプラグインのロード
		NetCommonsCakeTestCase::loadTestPlugin($this, 'UserAttributes', 'TestUserAttributes');
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
 * View/Elements/UserAttributes/render_index_colのテスト
 *
 * @return void
 */
	public function testRenderIndexCol() {
		//テストコントローラ生成
		$this->generateNc('TestUserAttributes.TestViewElementsUserAttributesRenderIndexCol');

		//テスト実行
		$this->_testGetAction('/test_user_attributes/test_view_elements_user_attributes_render_index_col/render_index_col',
				array('method' => 'assertNotEmpty'), null, 'view');

		//チェック
		$pattern = '/' . preg_quote('View/Elements/UserAttributes/render_index_col', '/') . '/';
		$this->assertRegExp($pattern, $this->view);
		$this->assertContains('list-group-item-success', $this->view);
		$this->assertContains('user-attribute-display', $this->view);
		$this->assertContains('user-attribute-move', $this->view);

		$pattern = '/<a.*?href=".*' . preg_quote('/edit/user_attribute_key', '/') . '".*?>/';
		$this->assertRegExp($pattern, $this->view);
	}

/**
 * display=falseのテスト
 *
 * @return void
 */
	public function testDisplayFalse() {
		//テストコントローラ生成
		$this->generateNc('TestUserAttributes.TestViewElementsUserAttributesRenderIndexCol');

		//テスト実行
		$this->_testGetAction('/test_user_attributes/test_view_elements_user_attributes_render_index_col/render_index_col_display_false',
				array('method' => 'assertNotEmpty'), null, 'view');

		//チェック
		$pattern = '/' . preg_quote('View/Elements/UserAttributes/render_index_col', '/') . '/';
		$this->assertRegExp($pattern, $this->view);
		$this->assertNotContains('list-group-item-success', $this->view);
		$this->assertContains('user-attribute-display', $this->view);
		$this->assertContains('user-attribute-move', $this->view);

		$pattern = '/<a.*?href=".*' . preg_quote('/edit/system_attribute_key', '/') . '".*?>/';
		$this->assertRegExp($pattern, $this->view);
	}

}
