<?php
/**
 * UserAttributeBehavior::addColumnByUserAttribute()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsCakeTestCase', 'NetCommons.TestSuite');

/**
 * UserAttributeBehavior::addColumnByUserAttribute()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\UserAttributes\Test\Case\Model\Behavior\UserAttributeBehavior
 */
class UserAttributeBehaviorAddColumnByUserAttributeTest extends NetCommonsCakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array();

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
		$this->TestModel = ClassRegistry::init('TestUserAttributes.TestUserAttributeBehaviorModel');
	}

/**
 * tearDown
 *
 * @return void
 */
	public function tearDown() {
		parent::tearDown();
		$this->fixtureManager->shutDown();
	}

/**
 * addColumnByUserAttribute()のテスト
 *
 * alter table で暗黙的なコミットが発生するため、rollbackしても戻らない。
 * [https://dev.mysql.com/doc/refman/5.6/ja/implicit-commit.html]
 *
 * @return void
 */
	public function testAddColumnByUserAttribute() {
		$data = array(
			'name' => 'Rollback test',
			'UserAttributeSetting' => array(
				'user_attribute_key' => 'rollback_test_key',
				'data_type_key' => 'rollback_test_key'
			)
		);

		$this->TestModel->begin();
		$this->TestModel->save($data, false);
		$this->TestModel->addColumnByUserAttribute($data);
		$this->TestModel->rollback();

		$this->TestModel->recursive = -1;
		$this->assertEquals(3, $this->TestModel->find('count'));

		$userFields = $this->TestModel->User->schema(true);
		$this->assertArrayHasKey('rollback_test_key', $userFields);
		$this->assertArrayHasKey(sprintf(UserAttribute::PUBLIC_FIELD_FORMAT, 'rollback_test_key'), $userFields);
	}

/**
 * addColumnByUserAttribute()のテスト(is_multilingualization=trueの場合)
 *
 * @return void
 */
	public function testIsMultilingualization() {
		$data = array(
			'name' => 'Rollback test',
			'UserAttributeSetting' => array(
				'user_attribute_key' => 'rollback_test_key',
				'data_type_key' => 'rollback_test_key',
				'is_multilingualization' => true
			)
		);

		$this->TestModel->begin();
		$this->TestModel->save($data, false);
		$this->TestModel->addColumnByUserAttribute($data);
		$this->TestModel->rollback();

		$this->TestModel->recursive = -1;
		$this->assertEquals(3, $this->TestModel->find('count'));

		$usersLangfields = $this->TestModel->UsersLanguage->schema(true);
		$this->assertArrayHasKey('rollback_test_key', $usersLangfields);

		$userFields = $this->TestModel->User->schema(true);
		$this->assertArrayHasKey(sprintf(UserAttribute::PUBLIC_FIELD_FORMAT, 'rollback_test_key'), $userFields);
	}

/**
 * addColumnByUserAttribute()のテスト(data_type_key=emailの場合)
 *
 * @return void
 */
	public function testMailType() {
		$data = array(
			'name' => 'Rollback test',
			'UserAttributeSetting' => array(
				'user_attribute_key' => 'rollback_test_key',
				'data_type_key' => DataType::DATA_TYPE_EMAIL
			)
		);

		$this->TestModel->begin();
		$this->TestModel->save($data, false);
		$this->TestModel->addColumnByUserAttribute($data);
		$this->TestModel->rollback();

		$this->TestModel->recursive = -1;
		$this->assertEquals(3, $this->TestModel->find('count'));

		$userFields = $this->TestModel->User->schema(true);
		$this->assertArrayHasKey('rollback_test_key', $userFields);
		$this->assertArrayHasKey(sprintf(UserAttribute::PUBLIC_FIELD_FORMAT, 'rollback_test_key'), $userFields);
		$this->assertArrayHasKey(sprintf(UserAttribute::MAIL_RECEPTION_FIELD_FORMAT, 'rollback_test_key'), $userFields);
	}

/**
 * alter table時に発生するrollbackのチェック
 *
 * @return void
 */
	public function testAlterTableErrorAndRollback() {
		$data = array(
			'name' => 'Rollback test',
			'UserAttributeSetting' => array(
				'user_attribute_key' => 'rollback_test_key',
				'data_type_key' => 'rollback_test_key'
			)
		);

		$cakeMigrationMock = $this->getMock(
			'CakeMigration',
			['before'],
			[['connection' => $this->TestModel->useDbConfig]]
		);
		$cakeMigrationMock
			->expects($this->once())
			->method('before')
			->will($this->throwException(new Exception));
		$this->TestModel->Behaviors->UserAttribute->cakeMigration = $cakeMigrationMock;

		$this->TestModel->begin();
		try {
			$this->TestModel->addColumnByUserAttribute($data);
		}
		catch (Exception $ex) {
			$this->TestModel->rollback();
			$this->TestModel->recursive = -1;
			$this->assertEquals(2, $this->TestModel->find('count'));

			$fields = $this->TestModel->User->schema(true);
			$this->assertArrayNotHasKey('rollback_test_key', $fields);

			return;
		}

		$this->fail();
	}

}
