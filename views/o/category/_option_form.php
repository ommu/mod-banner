<?php
/**
 * Banner Categories (banner-category)
 * @var $this CategoryController
 * @var $model BannerCategory
 * @var $form CActiveForm
 * version: 1.3.0
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2014 Ommu Platform (opensource.ommu.co)
 * @link https://github.com/ommu/ommu-banner
 * @contact (+62)856-299-4114
 *
 */

	$cs = Yii::app()->getClientScript();
$js=<<<EOP
	$('form[name="gridoption"] :checkbox').click(function(){
		var url = $('form[name="gridoption"]').attr('action');
		$.ajax({
			url: url,
			data: $('form[name="gridoption"] :checked').serialize(),
			success: function(response) {
				$.fn.yiiGridView.update('banner-category-grid', {
					data: $('form[name="gridoption"]').serialize()
				});
				return false;
			}
		});
	});
EOP;
	$cs->registerScript('grid-option', $js, CClientScript::POS_END);
?>

<?php echo CHtml::beginForm(Yii::app()->createUrl($this->route), 'get', array(
	'name' => 'gridoption',
));
$columns   = array();
$exception = array('_option','_no','id');
foreach($model->templateColumns as $key => $val) {
	if(!in_array($key, $exception))
		$columns[$key] = $key;
}
?>
<ul>
	<?php foreach($columns as $val): ?>
	<li>
		<?php echo CHtml::checkBox('GridColumn['.$val.']', in_array($key, $gridColumns) ? true : false); ?>
		<?php echo CHtml::label($model->getAttributeLabel($val), 'GridColumn_'.$val); ?>
	</li>
	<?php endforeach; ?>
</ul>
<?php echo CHtml::endForm(); ?>
