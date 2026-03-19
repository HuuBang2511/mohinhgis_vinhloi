<?php

namespace app\modules\quanly\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\quanly\models\ChieuSang;

/**
 * ChieuSangSearch represents the model behind the search form about `app\modules\quanly\models\ChieuSang`.
 */
class ChieuSangSearch extends ChieuSang
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'cong_suat_w', 'nam_lap_dat', 'so_bong_tren_cot', 'status'], 'integer'],
            [['ma_cot', 'loai_den', 'chat_lieu_cot', 'tinh_trang', 'gio_bat', 'gio_tat', 'nguon_dien', 'tu_dieu_khien', 'lan_bao_duong_cuoi', 'duong_pho', 'phuong_xa', 'quan_huyen', 'don_vi_quan_ly', 'ghi_chu', 'geom', 'file_dinhkem', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'safe'],
            [['chieu_cao_cot_m', 'lat', 'long'], 'number'],
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
        $query = ChieuSang::find()->where(['status' => 1]);

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
            'cong_suat_w' => $this->cong_suat_w,
            'chieu_cao_cot_m' => $this->chieu_cao_cot_m,
            'nam_lap_dat' => $this->nam_lap_dat,
            'so_bong_tren_cot' => $this->so_bong_tren_cot,
            'gio_bat' => $this->gio_bat,
            'gio_tat' => $this->gio_tat,
            'lan_bao_duong_cuoi' => $this->lan_bao_duong_cuoi,
            'lat' => $this->lat,
            'long' => $this->long,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'upper(ma_cot)', mb_strtoupper($this->ma_cot)])
            ->andFilterWhere(['like', 'upper(loai_den)', mb_strtoupper($this->loai_den)])
            ->andFilterWhere(['like', 'upper(chat_lieu_cot)', mb_strtoupper($this->chat_lieu_cot)])
            ->andFilterWhere(['like', 'upper(tinh_trang)', mb_strtoupper($this->tinh_trang)])
            ->andFilterWhere(['like', 'upper(nguon_dien)', mb_strtoupper($this->nguon_dien)])
            ->andFilterWhere(['like', 'upper(tu_dieu_khien)', mb_strtoupper($this->tu_dieu_khien)])
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
            
        'ma_cot',
        'loai_den',
        'cong_suat_w',
        'chieu_cao_cot_m',
        'chat_lieu_cot',
        'nam_lap_dat',
        'so_bong_tren_cot',
        'tinh_trang',
        'gio_bat',
        'gio_tat',
        'nguon_dien',
        'tu_dieu_khien',
        'lan_bao_duong_cuoi',
        'duong_pho',
        'phuong_xa',
        'quan_huyen',
        'don_vi_quan_ly',
        'ghi_chu',
               ];
    }
}
