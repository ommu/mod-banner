<?php
namespace ommu\banner\controllers\v1;

use app\components\api\ActiveController;

class AdminController extends ActiveController
{
	public $modelClass = 'ommu\banner\models\Banners';
	public static $authType = 2;
}
