<?php
/**
 * UserAttribute index col template
 *   - $userAttribute: UserAttribut
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */
?>

<ul class="user-attribute-edit">
	<li class="list-group-item clearfix">
		<div class="pull-left user-attribute-display">
			<?php echo $this->UserAttributeLayout->displaySetting($userAttribute); ?>
		</div>
		<div class="pull-left user-attribute-move">
			<div class="btn-group">
				<?php echo $this->UserAttributeLayout->moveSetting($layout, $userAttribute); ?>
			</div>
		</div>

		<div class="pull-left">
			<?php if ($userAttribute['UserAttributeSetting']['required']) : ?>
				<?php echo $this->NetCommonsHtml->requireTitle($userAttribute['UserAttribute']['name']); ?>
			<?php else : ?>
				<?php echo h($userAttribute['UserAttribute']['name']); ?>
			<?php endif; ?>
		</div>

		<div class="pull-right">
			<?php echo $this->Button->editLink('',
					array('action' => 'edit', h($userAttribute['UserAttribute']['key'])),
					array('iconSize' => 'btn-xs')
				); ?>
		</div>
	</li>
</ul>
