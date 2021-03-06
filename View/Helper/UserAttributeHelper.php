<?php
/**
 * UserAttribute Helper
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('AppHelper', 'View/Helper');
App::uses('UserAttributeLayout', 'UserAttributes.Model');

/**
 * 会員項目設定で使用するヘルパー
 *
 * このHelperを使う場合、
 * [UserAttributes.UserAttributeLayoutComponent](./UserAttributeLayoutComponent.html)
 * が読み込まれている必要がある。
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\UserAttribute\View\Helper
 */
class UserAttributeHelper extends AppHelper {

/**
 * 使用するHelpsers
 *
 * - [NetCommons.ButtonHelper](../../NetCommons/classes/ButtonHelper.html)
 * - [NetCommons.NetCommonsHtml](../../NetCommons/classes/NetCommonsHtml.html)
 * - [NetCommons.NetCommonsForm](../../NetCommons/classes/NetCommonsForm.html)
 *
 * @var array
 */
	public $helpers = array(
		'NetCommons.Button',
		'NetCommons.NetCommonsHtml',
		'NetCommons.NetCommonsForm'
	);

/**
 * 表示列の変更HTMLを出力する
 *
 * @param array $layout userAttributeLayoutデータ配列
 * @return string HTML
 */
	public function editCol($layout) {
		$output = '';

		$url = NetCommonsUrl::actionUrlAsArray(array(
			'controller' => 'user_attribute_layouts',
			'action' => 'edit',
			$layout['UserAttributeLayout']['id']
		));
		$output .= $this->NetCommonsForm->create(
			'UserAttributeLayout', array('type' => 'put', 'url' => $url)
		);

		$output .= $this->NetCommonsForm->hidden('UserAttributeLayout.id',
				array('value' => $layout['UserAttributeLayout']['id']));

		$options = array();
		for ($col = 1; $col <= UserAttributeLayout::LAYOUT_COL_NUMBER; $col++) {
			if ($col === 1) {
				$options['1'] = __d('user_attributes', '%s Col', $col);
			} else {
				$options[(string)$col] = __d('user_attributes', '%s Cols', $col);
			}
		}

		$output .= $this->NetCommonsForm->select('UserAttributeLayout.col', $options, array(
			'value' => $layout['UserAttributeLayout']['col'],
			'class' => 'form-control',
			'empty' => false,
			'onchange' => 'submit()'
		));

		$output .= $this->NetCommonsForm->end();
		return $output;
	}

/**
 * 表示・非表示の変更HTMLを出力する
 *
 * @param array $userAttribute userAttributeデータ配列
 * @return string HTML
 */
	public function displaySetting($userAttribute) {
		$output = '';

		$userAttrSettingId = $userAttribute['UserAttributeSetting']['id'];

		$output .= $this->NetCommonsForm->create(null, array(
			'name' => 'UserAttributeDidplayForm' . $userAttrSettingId,
			'url' => NetCommonsUrl::actionUrlAsArray(array(
				'controller' => 'user_attribute_settings',
				'action' => 'display',
				$userAttrSettingId
			)),
		));

		$output .= $this->NetCommonsForm->hidden('UserAttributeSetting.id', array(
			'value' => $userAttrSettingId,
		));

		if ($userAttribute['UserAttributeSetting']['display']) {
			$output .= $this->NetCommonsForm->hidden('UserAttributeSetting.display', array('value' => '0'));
			$buttonIcon = 'glyphicon-eye-open';
			$active = ' active';
			$label = __d('user_attributes', 'Display');
		} else {
			$output .= $this->NetCommonsForm->hidden('UserAttributeSetting.display', array('value' => '1'));
			$buttonIcon = 'glyphicon-minus';
			$active = '';
			$label = __d('user_attributes', 'Non display');
		}
		$output .= $this->Button->save(
			'<span class="glyphicon ' . $buttonIcon . '" aria-hidden="true"></span> ' . $label,
			array(
				'class' => 'btn btn-xs btn-default user-attributes-display-btn' . $active,
			)
		);

		$output .= $this->NetCommonsForm->end();
		return $output;
	}

/**
 * 項目の移動HTMLを出力する
 *
 * @param array $layout userAttributeLayoutデータ配列
 * @param array $userAttribute userAttributeデータ配列
 * @return string HTML
 */
	public function moveSetting($layout, $userAttribute) {
		$output = '';

		$output .= '<button type="button" ' .
							'class="btn btn-xs btn-default dropdown-toggle" ' .
							'data-toggle="dropdown" ' .
							'aria-haspopup="true" ' .
							'aria-expanded="false" ' .
							'ng-disabled="sending">' .
					__d('user_attributes', 'Move') .
					' <span class="caret"></span>' .
				'</button>';

		$output .= '<ul class="dropdown-menu">';
		$output .= $this->moveSettingTopMenu($layout, $userAttribute);
		$output .= $this->moveSettingBottomMenu($layout, $userAttribute);
		$output .= $this->moveSettingLeftMenu($layout, $userAttribute);
		$output .= $this->moveSettingRightMenu($layout, $userAttribute);

		//区切り線
		$output .= '<li class="divider"></li>';

		$output .= $this->moveSettingRowMenu($layout, $userAttribute);

		$output .= '</ul>';

		return $output;
	}

/**
 * 項目の移動メニューHTMLを出力する
 *
 * @param string $formName フォーム名
 * @param int $userAttrSettingId UserAttributeSetting.id
 * @param int $updWeight 順序
 * @param int $updRow ○段目
 * @param int $updCol 行
 * @param string $disabled disabledのCSS
 * @param string $class ボタンのCSS
 * @param string $message メッセージ
 * @return string HTML
 */
	private function __moveSettingForm($formName, $userAttrSettingId, $updWeight, $updRow, $updCol,
											$disabled, $class, $message) {
		$output = '';

		$output .= '<li' . $disabled . '>';
		if ($disabled) {
			$output .= '<a href=""> ';
		} else {
			$output .= '<a href="" onclick="$(\'form[name=' . $formName . ']\')[0].submit()"> ';
		}

		if ($class) {
			$output .= '<span class="glyphicon ' . $class . '">' . $message . '</span>';
		} else {
			$output .= '<span>' . $message . '</span>';
		}

		$output .= $this->NetCommonsForm->create(null, array('type' => 'put', 'name' => $formName,
			'url' => NetCommonsUrl::actionUrlAsArray(array(
				'controller' => 'user_attribute_settings',
				'action' => 'move',
				'key' => $userAttrSettingId
			)),
		));

		$output .= $this->NetCommonsForm->hidden(
			'UserAttributeSetting.id', array('value' => $userAttrSettingId)
		);
		$output .= $this->NetCommonsForm->hidden(
			'UserAttributeSetting.row', array('value' => $updRow)
		);
		if ($updCol) {
			$output .= $this->NetCommonsForm->hidden(
				'UserAttributeSetting.col', array('value' => $updCol)
			);
		}
		if ($updWeight) {
			$output .= $this->NetCommonsForm->hidden(
				'UserAttributeSetting.weight', array('value' => $updWeight)
			);
		}

		$output .= $this->NetCommonsForm->end();

		$output .= '</a></li>';

		return $output;
	}

/**
 * 項目の移動メニューHTMLを出力する(上へ)
 *
 * @param array $layout userAttributeLayoutデータ配列
 * @param array $userAttribute userAttributeデータ配列
 * @return string HTML
 */
	public function moveSettingTopMenu($layout, $userAttribute) {
		$output = '';

		//データを変数にセット
		$userAttrSettingId = $userAttribute['UserAttributeSetting']['id'];
		$weight = (int)$userAttribute['UserAttributeSetting']['weight'];
		$row = (int)$layout['UserAttributeLayout']['id'];
		$col = (int)$userAttribute['UserAttributeSetting']['col'];
		$formName = 'UserAttributeMoveForm' . $userAttrSettingId . 'Top';

		//上に移動
		if ($weight === 1) {
			if ((int)$layout['UserAttributeLayout']['col'] === 2 ||
					$col === 1 || ! isset($this->_View->viewVars['userAttributes'][$row][1])) {
				$disabled = ' class="disabled"';
				$updCol = $col;
				$updRow = $row;
				$updWeight = $weight;
			} else {
				$disabled = '';
				$updCol = $col - 1;
				$updRow = $row;
				$updWeight = count($this->_View->viewVars['userAttributes'][$row][($col - 1)]);
			}
		} else {
			$disabled = '';
			$updCol = $col;
			$updRow = $row;
			$updWeight = $weight - 1;
		}

		//HTML出力
		$output .= $this->__moveSettingForm(
			$formName, $userAttrSettingId, $updWeight, $updRow, $updCol,
			$disabled, 'glyphicon-arrow-up', __d('user_attributes', 'Go to Up')
		);

		return $output;
	}

/**
 * 項目の移動メニューHTMLを出力する(下へ)
 *
 * @param array $layout userAttributeLayoutデータ配列
 * @param array $userAttribute userAttributeデータ配列
 * @return string HTML
 */
	public function moveSettingBottomMenu($layout, $userAttribute) {
		$output = '';

		//データを変数にセット
		$userAttrSettingId = $userAttribute['UserAttributeSetting']['id'];
		$weight = (int)$userAttribute['UserAttributeSetting']['weight'];
		$row = (int)$layout['UserAttributeLayout']['id'];
		$col = (int)$userAttribute['UserAttributeSetting']['col'];
		$formName = 'UserAttributeMoveForm' . $userAttrSettingId . 'Bottom';

		//下に移動
		if ($weight === count($this->_View->viewVars['userAttributes'][$row][$col])) {
			if ((int)$layout['UserAttributeLayout']['col'] === 2 ||
					$col === 2 || ! isset($this->_View->viewVars['userAttributes'][$row][2])) {
				$disabled = ' class="disabled"';
				$updCol = $col;
				$updRow = $row;
				$updWeight = $weight;
			} else {
				//レイアウトが2列から1列の場合で、2列目がある場合の処理で、
				//その1列目の末尾だった項目に対する移動のため、2列目の2番目に移動する
				$disabled = '';

				$updCol = $col + 1;
				$updRow = $row;
				$updWeight = 2;
			}
		} else {
			$disabled = '';
			$updCol = $col;
			$updRow = $row;
			$updWeight = $weight + 1;
		}

		//HTML出力
		$output .= $this->__moveSettingForm(
			$formName, $userAttrSettingId, $updWeight, $updRow, $updCol,
			$disabled, 'glyphicon-arrow-down', __d('user_attributes', 'Go to Down')
		);

		return $output;
	}

/**
 * 項目の移動メニューHTMLを出力する(左へ)
 *
 * @param array $layout userAttributeLayoutデータ配列
 * @param array $userAttribute userAttributeデータ配列
 * @return string HTML
 */
	public function moveSettingLeftMenu($layout, $userAttribute) {
		$output = '';

		//データを変数にセット
		$userAttrSettingId = $userAttribute['UserAttributeSetting']['id'];
		$weight = (int)$userAttribute['UserAttributeSetting']['weight'];
		$row = (int)$layout['UserAttributeLayout']['id'];
		$col = (int)$userAttribute['UserAttributeSetting']['col'];
		$formName = 'UserAttributeMoveForm' . $userAttrSettingId . 'Left';

		if ((int)$layout['UserAttributeLayout']['col'] === 2) {
			//左に移動
			if ($col === 1) {
				$disabled = ' class="disabled"';
				$updCol = $col;
				$updRow = $row;
				$updWeight = $weight;
			} else {
				$disabled = '';
				$updCol = $col - 1;
				$updRow = $row;
				if (! isset($this->_View->viewVars['userAttributes'][$row][1])) {
					$updWeight = 1;
				} elseif ($weight > count($this->_View->viewVars['userAttributes'][$row][1])) {
					$updWeight = count($this->_View->viewVars['userAttributes'][$row][1]) + 1;
				} else {
					$updWeight = $weight;
				}
			}

			//HTML出力
			$output .= $this->__moveSettingForm(
				$formName, $userAttrSettingId, $updWeight, $updRow, $updCol,
				$disabled, 'glyphicon-arrow-left', __d('user_attributes', 'Go to Left')
			);
		}

		return $output;
	}

/**
 * 項目の移動メニューHTMLを出力する(右へ)
 *
 * @param array $layout userAttributeLayoutデータ配列
 * @param array $userAttribute userAttributeデータ配列
 * @return string HTML
 */
	public function moveSettingRightMenu($layout, $userAttribute) {
		$output = '';

		//データを変数にセット
		$userAttrSettingId = $userAttribute['UserAttributeSetting']['id'];
		$weight = (int)$userAttribute['UserAttributeSetting']['weight'];
		$row = (int)$layout['UserAttributeLayout']['id'];
		$col = (int)$userAttribute['UserAttributeSetting']['col'];
		$formName = 'UserAttributeMoveForm' . $userAttrSettingId . 'Right';

		if ((int)$layout['UserAttributeLayout']['col'] === 2) {
			//右に移動
			if ($col === 2) {
				$disabled = ' class="disabled"';
				$updCol = $col;
				$updRow = $row;
				$updWeight = $weight;
			} else {
				$disabled = '';
				$updCol = $col + 1;
				$updRow = $row;
				if (! isset($this->_View->viewVars['userAttributes'][$row][2])) {
					$updWeight = 1;
				} elseif ($weight > count($this->_View->viewVars['userAttributes'][$row][2])) {
					$updWeight = count($this->_View->viewVars['userAttributes'][$row][2]) + 1;
				} else {
					$updWeight = $weight;
				}
			}

			//HTML出力
			$output .= $this->__moveSettingForm(
				$formName, $userAttrSettingId, $updWeight, $updRow, $updCol,
				$disabled, 'glyphicon-arrow-right', __d('user_attributes', 'Go to Right')
			);
		}

		return $output;
	}

/**
 * 項目の移動メニューHTMLを出力する(○段へ)
 *
 * @param array $layout userAttributeLayoutデータ配列
 * @param array $userAttribute userAttributeデータ配列
 * @return string HTML
 */
	public function moveSettingRowMenu($layout, $userAttribute) {
		$output = '';

		//データを変数にセット
		$userAttrSettingId = $userAttribute['UserAttributeSetting']['id'];
		$row = (int)$layout['UserAttributeLayout']['id'];
		$formName = 'UserAttributeMoveForm' . $userAttrSettingId . 'Row';

		foreach ($this->_View->viewVars['userAttributeLayouts'] as $moveLayout) {
			//○段目に移動
			if ((int)$moveLayout['UserAttributeLayout']['id'] === (int)$row) {
				$disabled = ' class="disabled"';
				$updRow = $row;
			} else {
				$disabled = '';
				$updRow = $moveLayout['UserAttributeLayout']['id'];
			}

			//HTML出力
			$output .= $this->__moveSettingForm(
				$formName . $moveLayout['UserAttributeLayout']['id'],
				$userAttrSettingId,
				null,
				$updRow,
				null,
				$disabled,
				'',
				sprintf(__d('user_attributes', 'Go to %s row'), $moveLayout['UserAttributeLayout']['id'])
			);
		}

		return $output;
	}

}
