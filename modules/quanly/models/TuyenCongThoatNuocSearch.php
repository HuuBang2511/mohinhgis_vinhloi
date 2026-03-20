<?php

namespace app\modules\quanly\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\quanly\models\TuyenCongThoatNuoc;

/**
 * TuyenCongThoatNuocSearch represents the model behind the search form about `app\modules\quanly\models\TuyenCongThoatNuoc`.
 */
class TuyenCongThoatNuocSearch extends TuyenCongThoatNuoc
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'nam_xay_dung', 'diem_dau_id', 'diem_cuoi_id', 'status'], 'integer'],
            [['ma_tuyen', 'ten_tuyen', 'loai_cong', 'vat_lieu', 'loai_thoat_nuoc', 'tinh_trang', 'phuong_xa', 'quan_huyen', 'don_vi_quan_ly', 'ghi_chu', 'geom', 'file_dinhkem', 'created_at', 'updated_at', 'created_by', 'updated_by', 'geojson'], 'safe'],
            [['chieu_dai_m', 'duong_kinh_mm', 'do_doc_phan_tram', 'lat', 'long'], 'number'],
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
        $query = TuyenCongThoatNuoc::find()->where(['status' => 1]);

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
            'duong_kinh_mm' => $this->duong_kinh_mm,
            'do_doc_phan_tram' => $this->do_doc_phan_tram,
            'nam_xay_dung' => $this->nam_xay_dung,
            'diem_dau_id' => $this->diem_dau_id,
            'diem_cuoi_id' => $this->diem_cuoi_id,
            'lat' => $this->lat,
            'long' => $this->long,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'upper(ma_tuyen)', mb_strtoupper($this->ma_tuyen)])
            ->andFilterWhere(['like', 'upper(ten_tuyen)', mb_strtoupper($this->ten_tuyen)])
            ->andFilterWhere(['like', 'upper(loai_cong)', mb_strtoupper($this->loai_cong)])
            ->andFilterWhere(['like', 'upper(vat_lieu)', mb_strtoupper($this->vat_lieu)])
            ->andFilterWhere(['like', 'upper(loai_thoat_nuoc)', mb_strtoupper($this->loai_thoat_nuoc)])
            ->andFilterWhere(['like', 'upper(tinh_trang)', mb_strtoupper($this->tinh_trang)])
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
           
        'ma_tuyen',
        'ten_tuyen',
        'loai_cong',
        'chieu_dai_m',
        'duong_kinh_mm',
        'do_doc_phan_tram',
        'vat_lieu',
        'nam_xay_dung',
        'loai_thoat_nuoc',
        'tinh_trang',
        'diem_dau_id',
        'diem_cuoi_id',
        'don_vi_quan_ly',
        'ghi_chu',
              ];
    }
}
