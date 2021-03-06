<?php
/**
 * UserAttributeLayoutHelper::renderRow()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsHelperTestCase', 'NetCommons.TestSuite');
App::uses('UserAttributeLayoutFixture', 'UserAttributes.Test/Fixture');
App::uses('UserAttributeLayout', 'UserAttributes.Model');
class_exists('UserAttributeLayout'); // phpunitでエラーになるため

/**
 * UserAttributeLayoutHelper::renderRow()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\UserAttributes\Test\Case\View\Helper\UserAttributeLayoutHelper
 */
class UserAttributeLayoutHelperRenderRowTest extends NetCommonsHelperTestCase {

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

		//テストデータ生成
		$viewVars = array();
		$records = (new UserAttributeLayoutFixture())->records;
		foreach ($records as $i => $record) {
			$viewVars['userAttributeLayouts'][$i]['UserAttributeLayout'] = $record;
		}

		$requestData = array();

		//Helperロード
		$this->loadHelper('UserAttributes.UserAttributeLayout', $viewVars, $requestData);
	}

/**
 * renderRow()のテスト
 *
 * @return void
 */
	public function testRenderRow() {
		//テスト実施
		$result = $this->UserAttributeLayout->renderRow('UserAttributes/render_index_row');

		//チェック
		$this->assertInput('form', null, 'user_attribute_layouts/edit/1', $result);
		$this->assertInput('form', null, 'user_attribute_layouts/edit/2', $result);
		$this->assertInput('form', null, 'user_attribute_layouts/edit/3', $result);
		$this->assertInput('select', 'data[UserAttributeLayout][id]', null, $result);
	}

}
