<?php

namespace app\modules\quanly\controllers;

use app\modules\quanly\base\QuanlyBaseController;
use app\modules\quanly\services\ThematicLayerService;
use Yii;
use yii\db\Query;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use yii\web\Response;

class DashboardController extends QuanlyBaseController
{
    public function actionIndex()
    {
        $dashboardData = ThematicLayerService::buildDashboardData();

        return $this->render('index', [
            'dashboardData' => $dashboardData,
        ]);
    }

    public function actionRecords()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $layerKey = Yii::$app->request->get('layer');
        $status = Yii::$app->request->get('status');
        $type = Yii::$app->request->get('type');

        $definition = ThematicLayerService::getDefinition($layerKey);
        if ($definition === null) {
            throw new BadRequestHttpException('Lớp chuyên đề không hợp lệ.');
        }

        $fields = [
            'id',
            $definition['idField'],
            $definition['codeField'],
            $definition['nameField'],
            $definition['statusField'],
            $definition['typeField'],
        ];

        $query = (new Query())
            ->select(array_values(array_unique($fields)))
            ->from($definition['table'])
            ->where(['status' => 1])
            ->orderBy([$definition['idField'] => SORT_DESC]);

        if ($status !== null && $status !== '') {
            $query->andWhere([$definition['statusField'] => $status]);
        }

        if ($type !== null && $type !== '') {
            $query->andWhere([$definition['typeField'] => $type]);
        }

        $rows = $query->limit(300)->all();
        $detailBaseUrl = Url::to([$definition['detailRoute']]);

        $records = array_map(function ($row) use ($definition, $detailBaseUrl) {
            $id = isset($row[$definition['idField']]) ? $row[$definition['idField']] : null;

            return [
                'id' => $id,
                'code' => isset($row[$definition['codeField']]) ? $row[$definition['codeField']] : '',
                'name' => isset($row[$definition['nameField']]) ? $row[$definition['nameField']] : '',
                'status' => isset($row[$definition['statusField']]) ? $row[$definition['statusField']] : '',
                'type' => isset($row[$definition['typeField']]) ? $row[$definition['typeField']] : '',
                'url' => $id !== null ? $detailBaseUrl . '?id=' . urlencode((string) $id) : '',
            ];
        }, $rows);

        return [
            'success' => true,
            'title' => $definition['title'],
            'status' => $status,
            'type' => $type,
            'records' => $records,
        ];
    }
}
