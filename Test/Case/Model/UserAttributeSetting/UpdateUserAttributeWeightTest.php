<?php
/**
 * UserAttributeSetting::updateUserAttributeWeight()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsModelTestCase', 'NetCommons.TestSuite');

/**
 * UserAttributeSetting::updateUserAttributeWeight()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\UserAttributes\Test\Case\Model\UserAttributeSetting
 */
class UserAttributeSettingUpdateUserAttributeWeightTest extends NetCommonsModelTestCase {

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
	protected $_methodName = 'updateUserAttributeWeight';

/**
 * 移動元の順番を更新のテスト
 *
 * @return void
 */
	public function testUpdateOrderOriginalMoving() {
		$model = $this->_modelName;
		$methodName = $this->_methodName;

		//データ生成
		$row = '1';
		$col = '2';
		$weight = '4';
		$fluctuation = -1;
		$sign = '>';

		//更新前のデータ取得
		$beforeTestCount = $this->$model->find('count', array(
			'recursive' => -1,
			'conditions' => array(
				$this->$model->alias . '.weight ' . $sign => $weight,
				$this->$model->alias . '.row' => $row,
				$this->$model->alias . '.col' => $col,
			)
		));

		//テスト実施
		$result = $this->$model->$methodName($row, $col, $weight, $fluctuation, $sign);

		//チェック
		$this->assertTrue($result);
		$this->assertEquals(2, $this->$model->find('count', array(
			'recursive' => -1,
			'conditions' => array(
				$this->$model->alias . '.weight' => $weight,
				$this->$model->alias . '.row' => $row,
				$this->$model->alias . '.col' => $col,
			)
		)));
		$this->assertEquals($beforeTestCount - 1, $this->$model->find('count', array(
			'recursive' => -1,
			'conditions' => array(
				$this->$model->alias . '.weight ' . $sign => $weight,
				$this->$model->alias . '.row' => $row,
				$this->$model->alias . '.col' => $col,
			)
		)));
	}

/**
 * 移動先の順番を更新のテスト
 *
 * @return void
 */
	public function testUpdateMovingDestinationOrder() {
		$model = $this->_modelName;
		$methodName = $this->_methodName;

		//データ生成
		$row = '1';
		$col = '2';
		$weight = '3';
		$fluctuation = 1;
		$sign = '>=';

		//更新前のデータ取得
		$beforeTestCount = $this->$model->find('count', array(
			'recursive' => -1,
			'conditions' => array(
				$this->$model->alias . '.weight ' . $sign => $weight,
				$this->$model->alias . '.row' => $row,
				$this->$model->alias . '.col' => $col,
			)
		));

		//テスト実施
		$result = $this->$model->$methodName($row, $col, $weight, $fluctuation, $sign);

		//チェック
		$this->assertTrue($result);
		$this->assertEquals(0, $this->$model->find('count', array(
			'recursive' => -1,
			'conditions' => array(
				$this->$model->alias . '.weight' => $weight,
				$this->$model->alias . '.row' => $row,
				$this->$model->alias . '.col' => $col,
			)
		)));
		$this->assertEquals($beforeTestCount, $this->$model->find('count', array(
			'recursive' => -1,
			'conditions' => array(
				$this->$model->alias . '.weight ' . $sign => $weight + 1,
				$this->$model->alias . '.row' => $row,
				$this->$model->alias . '.col' => $col,
			)
		)));
	}

/**
 * ErrorExceptionのテスト
 *
 * @return void
 */
	public function testErrorException() {
		$this->_mockForReturnFalse('UserAttributeSetting', 'UserAttributes.UserAttributeSetting', 'updateAll');

		//データ生成
		$row = null;
		$col = null;
		$weight = null;
		$fluctuation = null;
		$sign = null;

		//テスト実施
		$this->setExpectedException('InternalErrorException');
		$model = $this->_modelName;
		$methodName = $this->_methodName;
		$this->$model->$methodName($row, $col, $weight, $fluctuation, $sign);
	}

}
