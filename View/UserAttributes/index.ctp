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

echo $this->NetCommonsHtml->css('/user_attributes/css/style.css');
?>

<?php foreach ($userAttributeLayouts as $layout) : ?>
	<?php $row = $layout['UserAttributeLayout']['id']; ?>

	<?php echo $this->element('UserAttributes/render_index_row', array('row' => $row, 'layout' => $layout)); ?>
<?php endforeach;
