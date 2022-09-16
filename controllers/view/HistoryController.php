<?php
/**
 * HistoryController
 * @var $this ommu\banner\controllers\view\HistoryController
 * @var $model ommu\banner\models\BannerViewHistory
 *
 * HistoryController implements the CRUD actions for BannerViewHistory model.
 * Reference start
 * TOC :
 *	Index
 *	Manage
 *	View
 *	Delete
 *
 *	findModel
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 6 October 2017, 13:24 WIB
 * @modified date 24 January 2019, 17:55 WIB
 * @link https://github.com/ommu/mod-banner
 *
 */

namespace ommu\banner\controllers\view;

use Yii;
use app\components\Controller;
use mdm\admin\components\AccessControl;
use yii\filters\VerbFilter;
use ommu\banner\models\BannerViewHistory;
use ommu\banner\models\search\BannerViewHistory as BannerViewHistorySearch;

class HistoryController extends Controller
{
	/**
	 * {@inheritdoc}
	 */
	public function init()
	{
        parent::init();

        if (Yii::$app->request->get('view') || Yii::$app->request->get('id') || Yii::$app->request->get('banner')) {
            $this->subMenu = $this->module->params['banner_submenu'];
        }
	}

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
	 * {@inheritdoc}
	 */
	public function actionIndex()
	{
        return $this->redirect(['manage']);
	}

	/**
	 * Lists all BannerViewHistory models.
	 * @return mixed
	 */
	public function actionManage()
	{
        $searchModel = new BannerViewHistorySearch();
        if (($banner = Yii::$app->request->get('banner')) != null) {
            $searchModel = new BannerViewHistorySearch(['bannerId' => $banner]);
        }
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

        if (($view = Yii::$app->request->get('view')) != null) {
            $view = \ommu\banner\models\BannerViews::findOne($view);
			$this->subMenuParam = $view->banner_id;
		}
        if ($banner) {
			$this->subMenuParam = $banner;
			$banner = \ommu\banner\models\Banners::findOne($banner);
		}

		$this->view->title = Yii::t('app', 'View Histories');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_manage', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'columns' => $columns,
			'view' => $view,
			'banner' => $banner,
		]);
	}

	/**
	 * Displays a single BannerViewHistory model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionView($id)
	{
        $model = $this->findModel($id);
		$this->subMenuParam = $model->view->banner_id;

		$this->view->title = Yii::t('app', 'Detail View History: {view-id}', ['view-id' => $model->view->banner->title]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->oRender('admin_view', [
			'model' => $model,
		]);
	}

	/**
	 * Deletes an existing BannerViewHistory model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$model = $this->findModel($id);
		$model->delete();

		Yii::$app->session->setFlash('success', Yii::t('app', 'Banner view history success deleted.'));
		return $this->redirect(Yii::$app->request->referrer ?: ['manage', 'view' => $model->view_id]);
	}

	/**
	 * Finds the BannerViewHistory model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return BannerViewHistory the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
        if (($model = BannerViewHistory::findOne($id)) !== null) {
            return $model;
        }

		throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}
}
