<?php

namespace app\modules\quanly\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\quanly\models\DiemRacThai;

/**
 * DiemRacThaiSearch represents the model behind the search form about `app\modules\quanly\models\DiemRacThai`.
 */
class DiemRacThaiSearch extends DiemRacThai
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'so_thung_rac', 'the_tich_thung_l', 'status'], 'integer'],
            [['ma_diem', 'ten_diem', 'loai_hinh', 'loai_rac_tiep_nhan', 'tinh_trang', 'lich_thu_gom', 'gio_thu_gom', 'don_vi_thu_gom', 'phuong_xa', 'quan_huyen', 'dia_chi_cu_the', 'don_vi_quan_ly', 'ghi_chu', 'geom', 'file_dinhkem', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'safe'],
            [['suc_chua_m3', 'dien_tich_m2', 'khoang_cach_dan_cu_m', 'lat', 'long'], 'number'],
            [['co_mai_che', 'co_hang_rao', 'co_bien_bao', 'hay_bi_qua_tai', 'phan_anh_mui'], 'boolean'],
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
        $query = DiemRacThai::find()->where(['status' => 1]);

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
            'suc_chua_m3' => $this->suc_chua_m3,
            'dien_tich_m2' => $this->dien_tich_m2,
            'so_thung_rac' => $this->so_thung_rac,
            'the_tich_thung_l' => $this->the_tich_thung_l,
            'co_mai_che' => $this->co_mai_che,
            'co_hang_rao' => $this->co_hang_rao,
            'co_bien_bao' => $this->co_bien_bao,
            'hay_bi_qua_tai' => $this->hay_bi_qua_tai,
            'khoang_cach_dan_cu_m' => $this->khoang_cach_dan_cu_m,
            'phan_anh_mui' => $this->phan_anh_mui,
            'lat' => $this->lat,
            'long' => $this->long,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'upper(ma_diem)', mb_strtoupper($this->ma_diem)])
            ->andFilterWhere(['like', 'upper(ten_diem)', mb_strtoupper($this->ten_diem)])
            ->andFilterWhere(['like', 'upper(loai_hinh)', mb_strtoupper($this->loai_hinh)])
            ->andFilterWhere(['like', 'upper(loai_rac_tiep_nhan)', mb_strtoupper($this->loai_rac_tiep_nhan)])
            ->andFilterWhere(['like', 'upper(tinh_trang)', mb_strtoupper($this->tinh_trang)])
            ->andFilterWhere(['like', 'upper(lich_thu_gom)', mb_strtoupper($this->lich_thu_gom)])
            ->andFilterWhere(['like', 'upper(gio_thu_gom)', mb_strtoupper($this->gio_thu_gom)])
            ->andFilterWhere(['like', 'upper(don_vi_thu_gom)', mb_strtoupper($this->don_vi_thu_gom)])
            ->andFilterWhere(['like', 'upper(phuong_xa)', mb_strtoupper($this->phuong_xa)])
            ->andFilterWhere(['like', 'upper(quan_huyen)', mb_strtoupper($this->quan_huyen)])
            ->andFilterWhere(['like', 'upper(dia_chi_cu_the)', mb_strtoupper($this->dia_chi_cu_the)])
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
    
        'ma_diem',
        'ten_diem',
        'loai_hinh',
        'suc_chua_m3',
        'dien_tich_m2',
        'loai_rac_tiep_nhan',
        'so_thung_rac',
        'the_tich_thung_l',
        'tinh_trang',
        'lich_thu_gom',
        'gio_thu_gom',
        'don_vi_thu_gom',
        'co_mai_che',
        'co_hang_rao',
        'co_bien_bao',
        'hay_bi_qua_tai',
        'khoang_cach_dan_cu_m',
        'phan_anh_mui',
        'dia_chi_cu_the',
        'don_vi_quan_ly',
        'ghi_chu',
                ];
    }
}
