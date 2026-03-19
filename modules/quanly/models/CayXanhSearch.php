<?php

namespace app\modules\quanly\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\quanly\models\CayXanh;

/**
 * CayXanhSearch represents the model behind the search form about `app\modules\quanly\models\CayXanh`.
 */
class CayXanhSearch extends CayXanh
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'nam_trong', 'status'], 'integer'],
            [['ma_cay', 'loai_hinh', 'ten_cay', 'ten_khoa_hoc', 'ten_khu_vuc', 'tinh_trang', 'lan_cat_tinh_cuoi', 'ghi_chu_benh', 'vi_tri_trong', 'duong_pho', 'phuong_xa', 'quan_huyen', 'don_vi_quan_ly', 'ghi_chu', 'geom', 'file_dinhkem', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'safe'],
            [['duong_kinh_cm', 'chieu_cao_m', 'duong_tan_m', 'dien_tich_m2', 'mat_do_phu_xanh', 'lat', 'long'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = CayXanh::find()->where(['status' => 1]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'nam_trong' => $this->nam_trong,
            'duong_kinh_cm' => $this->duong_kinh_cm,
            'chieu_cao_m' => $this->chieu_cao_m,
            'duong_tan_m' => $this->duong_tan_m,
            'dien_tich_m2' => $this->dien_tich_m2,
            'mat_do_phu_xanh' => $this->mat_do_phu_xanh,
            'lan_cat_tinh_cuoi' => $this->lan_cat_tinh_cuoi,
            'lat' => $this->lat,
            'long' => $this->long,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'upper(ma_cay)', mb_strtoupper($this->ma_cay)])
            ->andFilterWhere(['like', 'upper(loai_hinh)', mb_strtoupper($this->loai_hinh)])
            ->andFilterWhere(['like', 'upper(ten_cay)', mb_strtoupper($this->ten_cay)])
            ->andFilterWhere(['like', 'upper(ten_khoa_hoc)', mb_strtoupper($this->ten_khoa_hoc)])
            ->andFilterWhere(['like', 'upper(ten_khu_vuc)', mb_strtoupper($this->ten_khu_vuc)])
            ->andFilterWhere(['like', 'upper(tinh_trang)', mb_strtoupper($this->tinh_trang)])
            ->andFilterWhere(['like', 'upper(ghi_chu_benh)', mb_strtoupper($this->ghi_chu_benh)])
            ->andFilterWhere(['like', 'upper(vi_tri_trong)', mb_strtoupper($this->vi_tri_trong)])
            ->andFilterWhere(['like', 'upper(duong_pho)', mb_strtoupper($this->duong_pho)])
            ->andFilterWhere(['like', 'upper(phuong_xa)', mb_strtoupper($this->phuong_xa)])
            ->andFilterWhere(['like', 'upper(quan_huyen)', mb_strtoupper($this->quan_huyen)])
            ->andFilterWhere(['like', 'upper(don_vi_quan_ly)', mb_strtoupper($this->don_vi_quan_ly)])
            ->andFilterWhere(['like', 'upper(ghi_chu)', mb_strtoupper($this->ghi_chu)])
            ->andFilterWhere(['like', 'upper(geom)', mb_strtoupper($this->geom)])
            ->andFilterWhere(['like', 'upper(file_dinhkem)', mb_strtoupper($this->file_dinhkem)])
            ->andFilterWhere(['like', 'upper(created_by)', mb_strtoupper($this->created_by)])
            ->andFilterWhere(['like', 'upper(updated_by)', mb_strtoupper($this->updated_by)]);

        return $dataProvider;
    }

    public function getExportColumns()
    {
        return [
            [
                'class' => 'kartik\grid\SerialColumn',
            ],
            'id',
        'ma_cay',
        'loai_hinh',
        'ten_cay',
        'ten_khoa_hoc',
        'nam_trong',
        'duong_kinh_cm',
        'chieu_cao_m',
        'duong_tan_m',
        'ten_khu_vuc',
        'dien_tich_m2',
        'mat_do_phu_xanh',
        'tinh_trang',
        'lan_cat_tinh_cuoi',
        'ghi_chu_benh',
        'vi_tri_trong',
        'duong_pho',
        'phuong_xa',
        'quan_huyen',
        'don_vi_quan_ly',
        'ghi_chu',
           ];
    }
}
