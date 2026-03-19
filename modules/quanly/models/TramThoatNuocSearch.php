<?php

namespace app\modules\quanly\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\quanly\models\TramThoatNuoc;

/**
 * TramThoatNuocSearch represents the model behind the search form about `app\modules\quanly\models\TramThoatNuoc`.
 */
class TramThoatNuocSearch extends TramThoatNuoc
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'nam_xay_dung', 'status'], 'integer'],
            [['ma_tram', 'ten_tram', 'loai_hinh', 'vat_lieu', 'tinh_trang', 'tinh_trang_nap', 'lan_no_vay_cuoi', 'tan_suat_no_vay', 'duong_pho', 'phuong_xa', 'quan_huyen', 'don_vi_quan_ly', 'ghi_chu', 'geom', 'file_dinhkem', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'safe'],
            [['cong_suat_m3h', 'duong_kinh_mm', 'do_sau_m', 'do_sau_ngap_cm', 'lat', 'long'], 'number'],
            [['co_nap', 'co_nguy_co_ngap'], 'boolean'],
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
        $query = TramThoatNuoc::find()->where(['status' => 1]);

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
            'cong_suat_m3h' => $this->cong_suat_m3h,
            'duong_kinh_mm' => $this->duong_kinh_mm,
            'do_sau_m' => $this->do_sau_m,
            'nam_xay_dung' => $this->nam_xay_dung,
            'co_nap' => $this->co_nap,
            'lan_no_vay_cuoi' => $this->lan_no_vay_cuoi,
            'co_nguy_co_ngap' => $this->co_nguy_co_ngap,
            'do_sau_ngap_cm' => $this->do_sau_ngap_cm,
            'lat' => $this->lat,
            'long' => $this->long,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'upper(ma_tram)', mb_strtoupper($this->ma_tram)])
            ->andFilterWhere(['like', 'upper(ten_tram)', mb_strtoupper($this->ten_tram)])
            ->andFilterWhere(['like', 'upper(loai_hinh)', mb_strtoupper($this->loai_hinh)])
            ->andFilterWhere(['like', 'upper(vat_lieu)', mb_strtoupper($this->vat_lieu)])
            ->andFilterWhere(['like', 'upper(tinh_trang)', mb_strtoupper($this->tinh_trang)])
            ->andFilterWhere(['like', 'upper(tinh_trang_nap)', mb_strtoupper($this->tinh_trang_nap)])
            ->andFilterWhere(['like', 'upper(tan_suat_no_vay)', mb_strtoupper($this->tan_suat_no_vay)])
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
            
        'ma_tram',
        'ten_tram',
        'loai_hinh',
        'cong_suat_m3h',
        'duong_kinh_mm',
        'do_sau_m',
        'vat_lieu',
        'nam_xay_dung',
        'tinh_trang',
        'co_nap',
        'tinh_trang_nap',
        'lan_no_vay_cuoi',
        'tan_suat_no_vay',
        'co_nguy_co_ngap',
        'do_sau_ngap_cm',
        'duong_pho',
        'phuong_xa',
        'quan_huyen',
        'don_vi_quan_ly',
        'ghi_chu',
              ];
    }
}
