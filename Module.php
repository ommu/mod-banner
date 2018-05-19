<?php
/**
 * banner module definition class
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 ECC UGM (ecc.ft.ugm.ac.id)
 * @created date 15 September 2017, 19:05 WIB
 * @link http://ecc.ft.ugm.ac.id
 *
 */

namespace ommu\banner;

class Module extends \app\components\Module
{
	public $layout = 'main';

	/**
	 * @inheritdoc
	 */
	public $controllerNamespace = 'ommu\banner\controllers';

	/**
	 * @inheritdoc
	 */
	public function init()
	{
		parent::init();

		// custom initialization code goes here
	}
}
