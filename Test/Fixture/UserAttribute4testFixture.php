<?php
/**
 * UserAttributeFixture
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('UserAttributeFixture', 'UserAttributes.Test/Fixture');

/**
 * UserAttributeFixture
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\UserAttributes\Test\Fixture
 * @codeCoverageIgnore
 */
class UserAttribute4testFixture extends UserAttributeFixture {

/**
 * Model name
 *
 * @var string
 */
	public $name = 'UserAttribute';

/**
 * Full Table Name
 *
 * @var string
 */
	public $table = 'user_attributes';

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		//日本語
		array('id' => '1', 'language_id' => '2', 'key' => 'avatar', 'name' => 'アバター', ),
		array('id' => '2', 'language_id' => '2', 'key' => 'username', 'name' => 'ログインID', ),
		array('id' => '3', 'language_id' => '2', 'key' => 'password', 'name' => 'パスワード', ),
		array('id' => '4', 'language_id' => '2', 'key' => 'handlename', 'name' => 'ハンドル', ),
		array('id' => '5', 'language_id' => '2', 'key' => 'name', 'name' => '氏名', ),
		array('id' => '6', 'language_id' => '2', 'key' => 'email', 'name' => 'eメール', ),
		array('id' => '7', 'language_id' => '2', 'key' => 'moblie_mail', 'name' => '携帯メール', ),
		array('id' => '8', 'language_id' => '2', 'key' => 'sex', 'name' => '性別', ),
		array('id' => '9', 'language_id' => '2', 'key' => 'timezone', 'name' => 'タイムゾーン', ),
		array('id' => '10', 'language_id' => '2', 'key' => 'role_key', 'name' => '権限', ),
		array('id' => '11', 'language_id' => '2', 'key' => 'status', 'name' => '状態', ),
		array('id' => '12', 'language_id' => '2', 'key' => 'created', 'name' => '作成日時', ),
		array('id' => '13', 'language_id' => '2', 'key' => 'created_user', 'name' => '作成者', ),
		array('id' => '14', 'language_id' => '2', 'key' => 'modified', 'name' => '更新日時', ),
		array('id' => '15', 'language_id' => '2', 'key' => 'modified_user', 'name' => '更新者', ),
		array('id' => '16', 'language_id' => '2', 'key' => 'password_modified', 'name' => 'パスワード変更日時', ),
		array('id' => '17', 'language_id' => '2', 'key' => 'last_login', 'name' => '最終ログイン日時', ),
		array('id' => '18', 'language_id' => '2', 'key' => 'previous_login', 'name' => '前回ログイン日時', ),
		array('id' => '19', 'language_id' => '2', 'key' => 'profile', 'name' => 'プロフィール', ),
		array('id' => '20', 'language_id' => '2', 'key' => 'search_keywords', 'name' => '検索キーワード', ),
		//英語
		array('id' => '22', 'language_id' => '1', 'key' => 'username', 'name' => 'ID', ),
		array('id' => '28', 'language_id' => '1', 'key' => 'sex', 'name' => 'Sex', ),
	);

}
