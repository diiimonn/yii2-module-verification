<?php
namespace diiimonn\modules\verification\controllers;

use Yii;
use yii\rest\Controller;
use diiimonn\modules\verification\models\Verification;

/**
 * Class ApiController
 * @package diiimonn\modules\verification\controllers
 */
class ApiController extends Controller
{
    public function verbs()
    {
        return [
            'generate' => ['GET'],
            'check' => ['GET'],
            'invalidate' => ['GET'],
        ];
    }

    public function actionGenerate()
    {
        $response = new \stdClass();
        $response->status = false;
        $response->message = Yii::t('app', 'Failed');

        $model = new Verification();
        $model->scenario = Verification::SCENARIO_GENERATE;

        if (!$model->load([
                $model->formName() => Yii::$app->request->get()
            ]) || !$model->generate()) {

            return $response;
        }

        $response->status = true;
        $response->message = Yii::t('app', 'Success');

        return $response;
    }

    public function actionCheck()
    {
        $response = new \stdClass();
        $response->status = false;
        $response->message = Yii::t('app', 'Failed');

        $model = new Verification();
        $model->scenario = Verification::SCENARIO_CHECK;

        if (!$model->load([
                $model->formName() => Yii::$app->request->get()
            ]) || !$model->check()) {

            return $response;
        }

        $response->status = true;
        $response->message = Yii::t('app', 'Success');

        return $response;
    }

    public function actionInvalidate()
    {
        $response = new \stdClass();
        $response->status = false;
        $response->message = Yii::t('app', 'Failed');

        $model = new Verification();
        $model->scenario = Verification::SCENARIO_INVALIDATE;

        if (!$model->load([
                $model->formName() => Yii::$app->request->get()
            ]) || !$model->invalidate()) {

            return $response;
        }

        $response->status = true;
        $response->message = Yii::t('app', 'Success');

        return $response;
    }
}