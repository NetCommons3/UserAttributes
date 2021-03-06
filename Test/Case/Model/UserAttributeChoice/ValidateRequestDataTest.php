<?php
/**
 * UserAttributeChoice::validateRequestData()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsModelTestCase', 'NetCommons.TestSuite');
App::uses('UserAttributeChoice4testFixture', 'UserAttributes.Test/Fixture');

/**
 * UserAttributeChoice::validateRequestData()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\UserAttributes\Test\Case\Model\UserAttributeChoice
 */
class UserAttributeChoiceValidateRequestDataTest extends NetCommonsModelTestCase {

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
 * Model name
 *
 * @var string
 */
	protected $_modelName = 'UserAttributeChoice';

/**
 * Method name
 *
 * @var string
 */
	protected $_methodName = 'validateRequestData';

/**
 * テストデータセット
 *
 * @return void
 */
	private function __data() {
		//データ生成
		$choiceRecords = (new UserAttributeChoice4testFixture())->records;

		$data = array();

		$userAttributeChoices = Hash::extract($choiceRecords, '{n}[user_attribute_id=8]');
		foreach ($userAttributeChoices as $i => $userAttributeChoice) {
			$data['UserAttributeChoice'][$i][$userAttributeChoice['language_id']] = $userAttributeChoice;
		}
		$userAttributeChoices = Hash::extract($choiceRecords, '{n}[user_attribute_id=28]');
		foreach ($userAttributeChoices as $i => $userAttributeChoice) {
			$data['UserAttributeChoice'][$i][$userAttributeChoice['language_id']] = $userAttributeChoice;
		}

		$data['UserAttributeChoiceMap'] = Hash::combine($data['UserAttributeChoice'], '{n}.{n}.id', '{n}.{n}');

		return $data;
	}

/**
 * validateRequestData()のテスト
 *
 * @return void
 */
	public function testValidateRequestData() {
		//データ生成
		$data = $this->__data();

		//テスト実施
		$model = $this->_modelName;
		$methodName = $this->_methodName;
		$result = $this->$model->$methodName($data);

		//チェック
		$this->assertEquals($data['UserAttributeChoice'], $result);
	}

/**
 * validateRequestData()のテスト(追加)
 *
 * @return void
 */
	public function testAdd() {
		//データ生成
		$data = $this->__data();
		//追加
		$data['UserAttributeChoice'][count($data['UserAttributeChoice'])] = array(
			'2' => array(
				'id' => null, 'language_id' => '2', 'user_attribute_id' => '8', 'key' => '',
				'name' => '追加選択肢', 'code' => '', 'weight' => '4'
			),
			'1' => array(
				'id' => null, 'language_id' => '1', 'user_attribute_id' => '28', 'key' => '',
				'name' => 'Add choice', 'code' => '', 'weight' => '4'
			),
		);

		//テスト実施
		$model = $this->_modelName;
		$methodName = $this->_methodName;
		$result = $this->$model->$methodName($data);

		//チェック
		$this->assertCount(4, $data['UserAttributeChoice']);
		$data['UserAttributeChoice'][3] = Hash::merge($data['UserAttributeChoice'][3], array(
			'2' => array(
				'created_user' => null, 'created' => null, 'modified_user' => null, 'modified' => null
			),
			'1' => array(
				'created_user' => null, 'created' => null, 'modified_user' => null, 'modified' => null
			),
		));
		$this->assertEquals($data['UserAttributeChoice'], $result);
	}

/**
 * validateRequestData()のテスト(削除)
 *
 * @return void
 */
	public function testDelete() {
		//データ生成
		$data = $this->__data();

		//削除
		unset($data['UserAttributeChoice'][1]);

		$result = array();
		$weight = 0;
		foreach ($data['UserAttributeChoice'] as $choice) {
			$weight++;
			$choice = Hash::insert($choice, '{n}.weight', $weight);
			$result[] = $choice;
		}
		$data['UserAttributeChoice'] = $result;

		//テスト実施
		$model = $this->_modelName;
		$methodName = $this->_methodName;
		$result = $this->$model->$methodName($data);

		//チェック
		$this->assertCount(2, $data['UserAttributeChoice']);
		$this->assertEquals($data['UserAttributeChoice'], $result);
	}

/**
 * 空値テストのDataProvider
 *
 * ### 戻り値
 *  - data データ
 *
 * @return array テストデータ
 */
	public function dataProvider() {
		return array(
			array('data' => array()),
			array('data' => array('UserAttributeChoice' => array())),
			array('data' => array('UserAttributeChoice' => false)),
		);
	}

/**
 * 空値のテスト
 *
 * @param mixed $data データ
 * @dataProvider dataProvider
 * @return void
 */
	public function testEmpty($data) {
		//テスト実施
		$model = $this->_modelName;
		$methodName = $this->_methodName;
		$result = $this->$model->$methodName($data);

		//チェック
		$this->assertEmpty($result);
	}

/**
 * 不正データのテスト
 *
 * @return void
 */
	public function testBadRequest() {
		//データ生成
		$data = $this->__data();
		$data['UserAttributeChoice'][0]['2']['id'] = 99;

		//テスト実施
		$model = $this->_modelName;
		$methodName = $this->_methodName;
		$result = $this->$model->$methodName($data);

		//チェック
		$this->assertFalse($result);
	}

}
