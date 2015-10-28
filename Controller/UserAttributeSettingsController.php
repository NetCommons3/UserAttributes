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
 * UserAttributeSettingsController
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\UserAttributes\Controller
 */
class UserAttributeSettingsController extends UserAttributesAppController {

/**
 * use model
 *
 * @var array
 */
	public $uses = array(
		'UserAttributes.UserAttributeSetting',
	);

/**
 * 会員項目の表示・非表示の切り替え
 *
 * @return void
 */
	public function display() {
		if (! $this->request->isPost()) {
			$this->throwBadRequest();
			return;
		}

		if (! $this->UserAttributeSetting->saveUserAttributeSetting($this->data, 'display')) {
			$this->throwBadRequest();
			return;
		}

		$this->NetCommons->setFlashNotification(__d('net_commons', 'Successfully saved.'), array('class' => 'success'));
		$this->redirect('/user_attributes/user_attributes/index/');
	}

/**
 * 会員項目の移動
 *
 * @return void
 */
	public function move() {
		if (! $this->request->isPost()) {
			$this->throwBadRequest();
			return;
		}
		$data['UserAttributeSetting']['id'] = $this->data['UserAttributeSetting']['id'];
		foreach (['row', 'col', 'weight'] as $field) {
			if (! isset($this->data['UserAttributeSetting'][$field . '_' . $data['UserAttributeSetting']['id']])) {
				$this->throwBadRequest();
				return;
			}
			if (! $this->data['UserAttributeSetting'][$field . '_' . $data['UserAttributeSetting']['id']]) {
				continue;
			}
			$data['UserAttributeSetting'][$field] = $this->data['UserAttributeSetting'][$field . '_' . $data['UserAttributeSetting']['id']];
		}

		if (! $this->UserAttributeSetting->saveUserAttributeWeight($data)) {
			$this->throwBadRequest();
			return;
		}

		$this->NetCommons->setFlashNotification(__d('net_commons', 'Successfully saved.'), array('class' => 'success'));
		$this->redirect('/user_attributes/user_attributes/index/');
	}

}
