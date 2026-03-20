<?php

namespace app\modules\quanly\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\quanly\models\DuongThuy;

/**
 * DuongThuySearch represents the model behind the search form about `app\modules\quanly\models\DuongThuy`.
 */
class DuongThuySearch extends DuongThuy
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status'], 'integer'],
            [['ma_duong_thuy', 'ten', 'loai_hinh', 'luu_vuc', 'chuc_nang', 'tinh_trang_o_nhiem', 'tinh_trang_bo', 'phuong_xa', 'quan_huyen', 'don_vi_quan_ly', 'ghi_chu', 'geom', 'file_dinhkem', 'created_at', 'updated_at', 'created_by', 'updated_by', 'geojson'], 'safe'],
            [['chieu_dai_m', 'dien_tich_m2', 'chieu_rong_tb_m', 'do_sau_tb_m', 'lat', 'long'], 'number'],
            [['co_tiep_can', 'co_ke_bo', 'co_lan_can'], 'boolean'],
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
        $query = DuongThuy::find()->where(['status' => 1]);

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
            'chieu_dai_m' => $this->chieu_dai_m,
            'dien_tich_m2' => $this->dien_tich_m2,
            'chieu_rong_tb_m' => $this->chieu_rong_tb_m,
            'do_sau_tb_m' => $this->do_sau_tb_m,
            'co_tiep_can' => $this->co_tiep_can,
            'co_ke_bo' => $this->co_ke_bo,
            'co_lan_can' => $this->co_lan_can,
            'lat' => $this->lat,
            'long' => $this->long,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'upper(ma_duong_thuy)', mb_strtoupper($this->ma_duong_thuy)])
            ->andFilterWhere(['like', 'upper(ten)', mb_strtoupper($this->ten)])
            ->andFilterWhere(['like', 'upper(loai_hinh)', mb_strtoupper($this->loai_hinh)])
            ->andFilterWhere(['like', 'upper(luu_vuc)', mb_strtoupper($this->luu_vuc)])
            ->andFilterWhere(['like', 'upper(chuc_nang)', mb_strtoupper($this->chuc_nang)])
            ->andFilterWhere(['like', 'upper(tinh_trang_o_nhiem)', mb_strtoupper($this->tinh_trang_o_nhiem)])
            ->andFilterWhere(['like', 'upper(tinh_trang_bo)', mb_strtoupper($this->tinh_trang_bo)])
            ->andFilterWhere(['like', 'upper(phuong_xa)', mb_strtoupper($this->phuong_xa)])
            ->andFilterWhere(['like', 'upper(quan_huyen)', mb_strtoupper($this->quan_huyen)])
            ->andFilterWhere(['like', 'upper(don_vi_quan_ly)', mb_strtoupper($this->don_vi_quan_ly)])
            ->andFilterWhere(['like', 'upper(ghi_chu)', mb_strtoupper($this->ghi_chu)])
            ->andFilterWhere(['like', 'upper(geom)', mb_strtoupper($this->geom)])
            ->andFilterWhere(['like', 'upper(file_dinhkem)', mb_strtoupper($this->file_dinhkem)])
            ->andFilterWhere(['like', 'upper(created_by)', mb_strtoupper($this->created_by)])
            ->andFilterWhere(['like', 'upper(updated_by)', mb_strtoupper($this->updated_by)])
            ->andFilterWhere(['like', 'upper(geojson)', mb_strtoupper($this->geojson)]);

        return $dataProvider;
    }

    public function getExportColumns()
    {
        return [
            [
                'class' => 'kartik\grid\SerialColumn',
            ],
    
        'ma_duong_thuy',
        'ten',
        'loai_hinh',
        'chieu_dai_m',
        'dien_tich_m2',
        'chieu_rong_tb_m',
        'do_sau_tb_m',
        'luu_vuc',
        'chuc_nang',
        'co_tiep_can',
        'tinh_trang_o_nhiem',
        'tinh_trang_bo',
        'co_ke_bo',
        'co_lan_can',
        'don_vi_quan_ly',
        'ghi_chu',
               ];
    }
}
