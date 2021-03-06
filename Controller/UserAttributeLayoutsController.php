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
class UserAttributeLayoutsController extends UserAttributesAppController {

/**
 * use model
 *
 * @var array
 */
	public $uses = array(
		'UserAttributes.UserAttributeLayout',
	);

/**
 * edit
 *
 * @return void
 */
	public function edit() {
		if (! $this->request->is('put')) {
			$this->throwBadRequest();
			return;
		}

		if (! $this->UserAttributeLayout->updateUserAttributeLayout($this->data, 'col')) {
			$this->throwBadRequest();
			return;
		}

		$this->NetCommons->setFlashNotification(
			__d('net_commons', 'Successfully saved.'), array('class' => 'success')
		);
		$this->redirect('/user_attributes/user_attributes/index/');
	}
}
