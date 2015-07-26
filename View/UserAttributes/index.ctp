<?php
/**
 * UserAttribute index template
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */
?>

<?php $this->assign('title', __d('user_attributes', 'User attributes setting')); ?>

<?php foreach ($userAttributeLayouts as $layout) : ?>
	<?php echo $this->element('UserAttributes/frame_layout', array(
			'layout' => $layout,
		)); ?>
<?php endforeach;