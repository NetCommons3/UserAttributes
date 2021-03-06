<?php
/**
 * UserAttributeSetting::getMaxWeight()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsGetTest', 'NetCommons.TestSuite');

/**
 * UserAttributeSetting::getMaxWeight()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\UserAttributes\Test\Case\Model\UserAttributeSetting
 */
class UserAttributeSettingGetMaxWeightTest extends NetCommonsGetTest {

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
	protected $_methodName = 'getMaxWeight';

/**
 * getMaxWeight()のテスト
 *
 * @return void
 */
	public function testGetMaxWeight() {
		//データ生成
		$row = 1;
		$col = 2;

		//テスト実施
		$model = $this->_modelName;
		$methodName = $this->_methodName;
		$result = $this->$model->$methodName($row, $col);

		//チェック
		$this->assertEquals(11, $result);
	}

/**
 * getMaxWeight()のテスト(rowのみ指定)
 *
 * @return void
 */
	public function testGetMaxWeightOnlyRow() {
		//データ生成
		$row = 1;

		//テスト実施
		$model = $this->_modelName;
		$methodName = $this->_methodName;
		$result = $this->$model->$methodName($row);

		//チェック
		$this->assertEquals(array(2, 11), $result);
	}

/**
 * getMaxWeight()のテスト(データなし)
 *
 * @return void
 */
	public function testGetMaxWeightByNoData() {
		//データ生成
		$row = 4;
		$col = 1;

		//テスト実施
		$model = $this->_modelName;
		$methodName = $this->_methodName;
		$result = $this->$model->$methodName($row, $col);

		//チェック
		$this->assertEquals(0, $result);
	}

/**
 * getMaxWeight()のテスト(データなし、Rowのみ指定)
 *
 * @return void
 */
	public function testGetMaxWeightByNoDataOnlyRow() {
		//データ生成
		$row = 4;

		//テスト実施
		$model = $this->_modelName;
		$methodName = $this->_methodName;
		$result = $this->$model->$methodName($row);

		//チェック
		$this->assertEquals(array(1, 0), $result);
	}

}
