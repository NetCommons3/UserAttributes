<?php
/**
 * UserAttributes Controller
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('UserAttributesAppController', 'UserAttributes.Controller');

/**
 * UserAttributes Controller
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\UserAttributes\Controller
 */
class UserAttributesController extends UserAttributesAppController {

/**
 * use model
 *
 * @var array
 */
	public $uses = array(
		'UserAttributes.UserAttribute',
		'UserAttributes.UserAttributeChoice',
		'UserAttributes.UserAttributeSetting',
	);

/**
 * use component
 *
 * @var array
 */
	public $components = array(
		'ControlPanel.ControlPanelLayout',
		'M17n.SwitchLanguage' => array(
			'fields' => array(
				'UserAttribute.name', 'UserAttribute.description',
			)
		),
		'UserAttributes.UserAttributeLayout',
		'DataTypes.DataTypeForm',
	);

/**
 * use helpers
 *
 * @var array
 */
	public $helpers = array(
		'UserAttributes.UserAttribute',
		'UserAttributes.UserAttributeLayout',
	);

/**
 * index
 *
 * @return void
 */
	public function index() {
		$this->DataTypeForm->dataTypes = null;
	}

/**
 * add
 *
 * @param int $row 段
 * @param int $col 列
 * @return void
 */
	public function add($row, $col) {
		$this->view = 'edit';

		if ($this->request->is('post')) {
			//不要パラメータ除去
			unset($this->request->data['save'], $this->request->data['active_lang_id']);

			//他言語が入力されていない場合、表示されている言語データをセット
			$this->SwitchLanguage->setM17nRequestValue();

			//登録処理
			$row = $this->request->data['UserAttributeSetting']['row'];
			$col = $this->request->data['UserAttributeSetting']['col'];
			$weight = $this->UserAttributeSetting->getMaxWeight($row, $col) + 1;
			$this->request->data['UserAttributeSetting']['weight'] = $weight;

			$result = $this->UserAttributeChoice->validateRequestData($this->request->data);
			if ($result === false) {
				$this->throwBadRequest();
				return;
			}
			$this->request->data['UserAttributeChoice'] = $result;

			if ($this->UserAttribute->saveUserAttribute($this->request->data)) {
				//正常の場合
				$this->redirect('/user_attributes/user_attributes/index/');
				return;
			}
			$this->NetCommons->handleValidationError($this->UserAttribute->validationErrors);

		} else {
			//初期値セット
			$this->request->data['UserAttribute'] = array();
			foreach (array_keys($this->viewVars['languages']) as $langId) {
				$index = count($this->request->data['UserAttribute']);
				$userAttribute = $this->UserAttribute->create(array(
					'id' => null,
					'language_id' => $langId,
				));
				$this->request->data['UserAttribute'][$index] = $userAttribute['UserAttribute'];
			}

			$this->request->data = Hash::merge($this->request->data,
				$this->UserAttributeSetting->create(array(
					'data_type_key' => 'text',
					'row' => $row,
					'col' => $col,
				))
			);
		}

		$this->DataTypeForm->dataTypes = $this->UserAttributeSetting->addDataTypes;
	}

/**
 * edit
 *
 * @param string $key user_attributes.key
 * @return void
 */
	public function edit($key = null) {
		if ($this->request->is('put')) {
			//不要パラメータ除去
			unset($this->request->data['save'], $this->request->data['active_lang_id']);

			$result = $this->UserAttributeChoice->validateRequestData($this->request->data);
			if ($result === false) {
				$this->throwBadRequest();
				return;
			}
			$this->request->data['UserAttributeChoice'] = $result;

			//他言語が入力されていない場合、表示されている言語データをセット
			$this->SwitchLanguage->setM17nRequestValue();

			//登録処理
			if ($this->UserAttribute->saveUserAttribute($this->request->data)) {
				//正常の場合
				$this->redirect('/user_attributes/user_attributes/index/');
				return;
			}
			$this->NetCommons->handleValidationError($this->UserAttribute->validationErrors);

		} else {
			//既存データ取得
			$this->request->data = $this->UserAttribute->getUserAttribute($key);
			if (! $this->request->data) {
				$this->throwBadRequest();
				return;
			}
		}
		if ($this->request->data['UserAttributeSetting']['is_system']) {
			$this->DataTypeForm->dataTypes = $this->UserAttributeSetting->editDataTypes;
		} else {
			$this->DataTypeForm->dataTypes = $this->UserAttributeSetting->addDataTypes;
		}
	}

/**
 * delete
 *
 * @return void
 */
	public function delete() {
		if (! $this->request->is('delete')) {
			$this->throwBadRequest();
			return;
		}

		$key = Hash::get($this->data, 'UserAttributeSetting.user_attribute_key');
		if (! $this->UserAttribute->deleteUserAttribute($key)) {
			$this->throwBadRequest();
			return;
		}
		$this->redirect('/user_attributes/user_attributes/index/');
	}

}
