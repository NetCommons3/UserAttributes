<?php
/**
 * View/Elements/UserAttributes/edit_formテスト用Controller
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('AppController', 'Controller');

/**
 * View/Elements/UserAttributes/edit_formテスト用Controller
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\UserAttributes\Test\test_app\Plugin\UserAttributes\Controller
 */
class TestViewElementsUserAttributesEditFormController extends AppController {

/**
 * use component
 *
 * @var array
 */
	public $components = array(
		'M17n.SwitchLanguage',
		'DataTypes.DataTypeForm',
	);

/**
 * edit_form
 *
 * @return void
 */
	public function edit_form() {
		$this->autoRender = true;

		App::uses('UserAttributeFixture', 'UserAttributes.Test/Fixture');
		App::uses('UserAttributeSettingFixture', 'UserAttributes.Test/Fixture');

		$userAttributeRecords = (new UserAttributeFixture())->records;
		//存在しない言語
		array_push($userAttributeRecords, array(
			'id' => '3',
			'key' => 'user_attribute_key',
			'language_id' => '3',
			'name' => 'Other name',
			'description' => 'Other description',
		));
		$this->request->data = array(
			'UserAttributeSetting' => (new UserAttributeSettingFixture())->records[0],
			'UserAttribute' => $userAttributeRecords,
		);
	}

/**
 * edit_form
 *
 * @return void
 */
	public function edit_form_is_system() {
		$this->autoRender = true;
		$this->view = 'edit_form';

		$this->edit_form();
		$this->request->data['UserAttributeSetting']['is_system'] = '1';
	}

/**
 * edit_form
 *
 * @return void
 */
	public function edit_form_action_edit() {
		$this->autoRender = true;
		$this->view = 'edit_form';

		$this->edit_form();
		$this->request->params['action'] = 'edit';
	}

}
