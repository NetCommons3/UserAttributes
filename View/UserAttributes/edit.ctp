<?php
/**
 * UserAttribute edit template
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

echo $this->NetCommonsHtml->css('/user_attributes/css/style.css');
echo $this->NetCommonsHtml->script('/user_attributes/js/user_attributes.js');

//Javascriptに渡すデータ生成
$camelizeData['userAttributeSetting'] = NetCommonsAppController::camelizeKeyRecursive($this->data['UserAttributeSetting']);

//'_' . $langIdにしないと、Javascript側で認識されない
if (! isset($this->request->data['UserAttributeChoice'])) {
	$this->request->data['UserAttributeChoice'] = array();
}
foreach ($this->request->data['UserAttributeChoice'] as $weight => $choiceByLang) {
	foreach ($choiceByLang as $langId => $choice) {
		$camelizeData['userAttributeChoices'][$weight]['_' . $langId] = $choice;
	}
}

$newChoice = array();
foreach ($this->request->data['UserAttribute'] as $userAttribute) {
	$camelizeData['newChoice']['_' . $userAttribute['language_id']] = array(
		'id' => null,
		'language_id' => $userAttribute['language_id'],
		'user_attribute_id' => $userAttribute['id'],
		'key' => null,
		'name' => null,
		'value' => null,
		'weight' => null,
	);
}

?>

<div class="panel panel-default" ng-controller="UserAttributes" ng-init='initialize(<?php echo h(json_encode($camelizeData)) ?>)'>
	<?php echo $this->NetCommonsForm->create('UserAttribute'); ?>

	<div class="panel-body">
		<?php echo $this->SwitchLanguage->tablist('user-attributes-'); ?>

		<div class="tab-content">
			<?php echo $this->element('UserAttributes/edit_form'); ?>
		</div>
	</div>

	<div class="panel-footer text-center">
		<?php echo $this->Button->cancelAndSave(
				__d('net_commons', 'Cancel'),
				__d('net_commons', 'OK'),
				$this->NetCommonsHtml->url(array('action' => 'index'))
			); ?>
	</div>

	<?php echo $this->NetCommonsForm->end(); ?>
</div>

<?php if ($this->request->params['action'] === 'edit' && ! $this->data['UserAttributeSetting']['is_systemized']) : ?>
	<?php echo $this->element('UserAttributes/delete_form'); ?>
<?php endif;
