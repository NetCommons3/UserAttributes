<?php
/**
 * UserAttributeBehaviorテスト用Model
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('AppModel', 'Model');

/**
 * UserAttributeBehaviorテスト用Model
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\UserAttributes\Test\test_app\Plugin\UserAttributes\Model
 */
class TestUserAttributeBehaviorModel extends AppModel {

/**
 * テーブル名
 *
 * @var mixed
 */
	public $useTable = 'user_attributes';

/**
 * 使用ビヘイビア
 *
 * @var array
 */
	public $actsAs = array(
		'UserAttributes.UserAttribute'
	);

}
