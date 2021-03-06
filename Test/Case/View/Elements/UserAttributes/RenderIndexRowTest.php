<?php
/**
 * View/Elements/UserAttributes/render_index_rowのテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsControllerTestCase', 'NetCommons.TestSuite');

/**
 * View/Elements/UserAttributes/render_index_rowのテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\UserAttributes\Test\Case\View\Elements\UserAttributes\RenderIndexRow
 */
class UserAttributesViewElementsUserAttributesRenderIndexRowTest extends NetCommonsControllerTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.user_attributes.user_attribute_layout',
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
 * View/Elements/UserAttributes/render_index_rowのテスト
 *
 * @return void
 */
	public function testRenderIndexRow() {
		//テストコントローラ生成
		$this->generateNc('TestUserAttributes.TestViewElementsUserAttributesRenderIndexRow');

		//テスト実行
		$this->_testGetAction('/test_user_attributes/test_view_elements_user_attributes_render_index_row/render_index_row',
				array('method' => 'assertNotEmpty'), null, 'view');

		//チェック
		$pattern = '/' . preg_quote('View/Elements/UserAttributes/render_index_row', '/') . '/';
		$this->assertRegExp($pattern, $this->view);

		$this->assertInput('input', '_method', 'PUT', $this->view);
		$this->assertInput('input', 'data[UserAttributeLayout][id]', '1', $this->view);
		$this->assertInput('select', 'data[UserAttributeLayout][col]', null, $this->view);
		$this->assertInput('option', '1', null, $this->view);
		$this->assertInput('option', '2', 'selected', $this->view);

		$pattern = '/<a.*?href=".*' . preg_quote('/add/1/2', '/') . '".*?>/';
		$this->assertRegExp($pattern, $this->view);
	}

}
