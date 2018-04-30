<?php
namespace app\modules\banner\controllers\v1;

use app\components\api\ActiveController;

class AdminController extends ActiveController
{
    public $modelClass = 'app\modules\banner\models\Banners';
    public static $authType = 2;
}
