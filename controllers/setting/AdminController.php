<?php
/**
 * AdminController
 * @var $this ommu\banner\controllers\setting\AdminController
 * @var $model ommu\banner\models\BannerSetting
 *
 * AdminController implements the CRUD actions for BannerSetting model.
 * Reference start
 * TOC :
 *	Index
 *	Update
 *	Delete
 *
 *	findModel
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 6 October 2017, 06:22 WIB
 * @modified date 23 January 2019, 16:05 WIB
 * @link https://github.com/ommu/mod-banner
 *
 */

namespace ommu\banner\controllers\setting;

use Yii;
use app\components\Controller;
use mdm\admin\components\AccessControl;
use yii\filters\VerbFilter;
use ommu\banner\models\BannerSetting;
use ommu\banner\models\search\BannerCategory as BannerCategorySearch;

class AdminController extends Controller
{
	/**
	 * {@inheritdoc}
	 */
	public function behaviors()
	{
        return [
            'access' => [
                'class' => AccessControl::className(),
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
	}

	/**
	 * Lists all BannerSetting models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		$model = BannerSetting::findOne(1);
        if ($model === null) {
            $model = new BannerSetting(['id' => 1]);
        }

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            // $postData = Yii::$app->request->post();
            // $model->load($postData);
            // $model->order = $postData['order'] ? $postData['order'] : 0;

            if ($model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Banner setting success updated.'));
                return $this->redirect(['index']);

            } else {
                if (Yii::$app->request->isAjax) {
                    return \yii\helpers\Json::encode(\app\components\widgets\ActiveForm::validate($model));
                }
            }
        }
		
		$searchModel = new BannerCategorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $gridColumn = Yii::$app->request->get('GridColumn', null);
        $cols = [];
        if ($gridColumn != null && count($gridColumn) > 0) {
            foreach ($gridColumn as $key => $val) {
                if ($gridColumn[$key] == 1) {
                    $cols[] = $key;
                }
            }
        }
        $columns = $searchModel->getGridColumn($cols);

		$this->view->cards = false;
		$this->view->title = Yii::t('app', 'Banner Settings');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_index', [
			'model' => $model,
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'columns' => $columns,
		]);
	}

	/**
	 * Updates an existing BannerSetting model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionUpdate()
	{
		$model = BannerSetting::findOne(1);
        if ($model === null) {
            $model = new BannerSetting(['id' => 1]);
        }

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            // $postData = Yii::$app->request->post();
            // $model->load($postData);
            // $model->order = $postData['order'] ? $postData['order'] : 0;

            if ($model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Banner setting success updated.'));
                return $this->redirect(['update']);

            } else {
                if (Yii::$app->request->isAjax) {
                    return \yii\helpers\Json::encode(\app\components\widgets\ActiveForm::validate($model));
                }
            }
        }

		$this->subMenu = $this->module->params['setting_submenu'];
		$this->view->title = Yii::t('app', 'Banner Settings');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_update', [
			'model' => $model,
			'breadcrumb' => true,
		]);
	}

	/**
	 * Deletes an existing BannerSetting model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete()
	{
		$this->findModel(1)->delete();
		
		Yii::$app->session->setFlash('success', Yii::t('app', 'Banner setting success deleted.'));
		return $this->redirect(Yii::$app->request->referrer ?: ['index']);
	}

	/**
	 * Finds the BannerSetting model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return BannerSetting the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
        if (($model = BannerSetting::findOne($id)) !== null) {
            return $model;
        }

		throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}
}
