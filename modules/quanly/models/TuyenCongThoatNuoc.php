<?php

namespace app\modules\quanly\models;

use Yii;

/**
 * This is the model class for table "tuyen_cong_thoat_nuoc".
 *
 * @property int $id
 * @property string|null $ma_tuyen
 * @property string|null $ten_tuyen
 * @property string|null $loai_cong
 * @property float|null $chieu_dai_m
 * @property float|null $duong_kinh_mm
 * @property float|null $do_doc_phan_tram
 * @property string|null $vat_lieu
 * @property int|null $nam_xay_dung
 * @property string|null $loai_thoat_nuoc Nước mưa / Nước thải sinh hoạt / Hỗn hợp
 * @property string|null $tinh_trang
 * @property int|null $diem_dau_id
 * @property int|null $diem_cuoi_id
 * @property string|null $phuong_xa
 * @property string|null $quan_huyen
 * @property string|null $don_vi_quan_ly
 * @property string|null $ghi_chu
 * @property float|null $lat
 * @property float|null $long
 * @property string|null $geom
 * @property string|null $file_dinhkem
 * @property int|null $status
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property string|null $geojson
 *
 * @property TramThoatNuoc $diemDau
 * @property TramThoatNuoc $diemCuoi
 */
class TuyenCongThoatNuoc extends QuanlyBaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tuyen_cong_thoat_nuoc';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['chieu_dai_m', 'duong_kinh_mm', 'do_doc_phan_tram', 'lat', 'long'], 'number'],
            [['nam_xay_dung', 'diem_dau_id', 'diem_cuoi_id', 'status'], 'default', 'value' => null],
            [['nam_xay_dung', 'diem_dau_id', 'diem_cuoi_id', 'status'], 'integer'],
            [['ghi_chu', 'geom', 'file_dinhkem', 'geojson'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['ma_tuyen'], 'string', 'max' => 20],
            [['ten_tuyen', 'don_vi_quan_ly'], 'string', 'max' => 255],
            [['loai_cong', 'vat_lieu', 'loai_thoat_nuoc', 'tinh_trang'], 'string', 'max' => 50],
            [['phuong_xa', 'quan_huyen', 'created_by', 'updated_by'], 'string', 'max' => 100],
            [['diem_dau_id'], 'exist', 'skipOnError' => true, 'targetClass' => TramThoatNuoc::className(), 'targetAttribute' => ['diem_dau_id' => 'id']],
            [['diem_cuoi_id'], 'exist', 'skipOnError' => true, 'targetClass' => TramThoatNuoc::className(), 'targetAttribute' => ['diem_cuoi_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ma_tuyen' => 'Ma Tuyen',
            'ten_tuyen' => 'Ten Tuyen',
            'loai_cong' => 'Loai Cong',
            'chieu_dai_m' => 'Chieu Dai M',
            'duong_kinh_mm' => 'Duong Kinh Mm',
            'do_doc_phan_tram' => 'Do Doc Phan Tram',
            'vat_lieu' => 'Vat Lieu',
            'nam_xay_dung' => 'Nam Xay Dung',
            'loai_thoat_nuoc' => 'Nước mưa / Nước thải sinh hoạt / Hỗn hợp',
            'tinh_trang' => 'Tinh Trang',
            'diem_dau_id' => 'Điểm đầu',
            'diem_cuoi_id' => 'Điểm cuối',
            'phuong_xa' => 'Phuong Xa',
            'quan_huyen' => 'Quan Huyen',
            'don_vi_quan_ly' => 'Don Vi Quan Ly',
            'ghi_chu' => 'Ghi Chu',
            'lat' => 'Lat',
            'long' => 'Long',
            'geom' => 'Geom',
            'file_dinhkem' => 'File Dinhkem',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'geojson' => 'Geojson',
        ];
    }

    /**
     * Gets query for [[DiemDau]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDiemDau()
    {
        return $this->hasOne(TramThoatNuoc::className(), ['id' => 'diem_dau_id']);
    }

    /**
     * Gets query for [[DiemCuoi]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDiemCuoi()
    {
        return $this->hasOne(TramThoatNuoc::className(), ['id' => 'diem_cuoi_id']);
    }
}
