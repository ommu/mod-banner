<?php
/**
 * Banner Click Details (banner-click-detail)
 * @var $this ClickdetailController
 * @var $data BannerClickDetail
 * version: 0.0.1
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2017 Ommu Platform (ommu.co)
 * @created date 8 January 2017, 21:21 WIB
 * @link http://company.ommu.co
 * @contect (+62)856-299-4114
 *
 */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('click_id')); ?>:</b>
	<?php echo CHtml::encode($data->click_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('click_date')); ?>:</b>
	<?php echo CHtml::encode($data->click_date); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('click_ip')); ?>:</b>
	<?php echo CHtml::encode($data->click_ip); ?>
	<br />


</div>