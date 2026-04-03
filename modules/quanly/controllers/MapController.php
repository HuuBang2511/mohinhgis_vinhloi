<?php

namespace app\modules\quanly\controllers;

use app\modules\quanly\models\Kp;
use app\modules\quanly\services\ThematicLayerService;
use Yii;
use yii\db\Expression;
use yii\helpers\Json;
use yii\web\Response;

class MapController extends \app\modules\quanly\base\QuanlyBaseController
{
    public $layout = '@app/views/layouts/map/main';

    public function actionVuviec()
    {
        return $this->render('vuviec', [
            'thematicMeta' => ThematicLayerService::getFilterMeta(),
            'kpOptions' => $this->getKpOptions(),
        ]);
    }

    public function actionThematicStats()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $layerKey = Yii::$app->request->get('layerKey', '');
        $definition = ThematicLayerService::getDefinition($layerKey);
        if ($definition === null) {
            return [
                'success' => false,
                'message' => 'Lớp chuyên đề không hợp lệ.',
            ];
        }

        $table = $definition['table'];
        $statusField = $definition['statusField'];
        $typeField = $definition['typeField'];
        $kpId = (int) Yii::$app->request->get('kpId', 0);
        $statusValues = array_values(array_filter((array) Yii::$app->request->get('statusValues', []), static function ($value) {
            return trim((string) $value) !== '';
        }));
        $typeValues = array_values(array_filter((array) Yii::$app->request->get('typeValues', []), static function ($value) {
            return trim((string) $value) !== '';
        }));

        $query = (new \yii\db\Query())
            ->from(['t' => $table])
            ->where(['t.status' => 1]);

        if ($kpId > 0) {
            $query->innerJoin(['kp_filter' => 'kp'], 'ST_Intersects(t.geom, kp_filter.geom)');
            $query->andWhere(['kp_filter.id' => $kpId]);
        }

        if (!empty($statusValues)) {
            $query->andWhere(['t.' . $statusField => $statusValues]);
        }

        if (!empty($typeValues)) {
            $query->andWhere(['t.' . $typeField => $typeValues]);
        }

        return [
            'success' => true,
            'data' => [
                'key' => $definition['key'],
                'title' => $definition['title'],
                'total' => (int) (clone $query)->count('*', Yii::$app->db),
                'statuses' => $this->buildGroupedCounts(clone $query, $statusField),
                'types' => $this->buildGroupedCounts(clone $query, $typeField),
            ],
        ];
    }

    protected function getKpOptions()
    {
        $rows = Kp::find()
            ->select([
                'id',
                'TenKhuPho',
                'geojson' => new Expression('ST_AsGeoJSON(geom)'),
                'wkt' => new Expression('ST_AsText(geom)'),
            ])
            ->orderBy(['TenKhuPho' => SORT_ASC])
            ->asArray()
            ->all();

        return array_map(static function ($row) {
            return [
                'id' => (int) $row['id'],
                'name' => $row['TenKhuPho'],
                'geojson' => Json::decode($row['geojson']),
                'wkt' => $row['wkt'],
            ];
        }, $rows);
    }

    protected function buildGroupedCounts(\yii\db\Query $query, $field)
    {
        $rows = $query
            ->select([
                'value' => new Expression('COALESCE(t.' . $field . ", '(Chưa cập nhật)')"),
                'count' => new Expression('COUNT(*)'),
            ])
            ->groupBy(['t.' . $field])
            ->orderBy(['count' => SORT_DESC, 'value' => SORT_ASC])
            ->all(Yii::$app->db);

        return array_map(static function ($row) {
            $value = trim((string) $row['value']);
            return [
                'value' => $value === '' ? '(Chưa cập nhật)' : $value,
                'count' => (int) $row['count'],
            ];
        }, $rows);
    }
}
