<?php

namespace app\modules\quanly\models;
use app\modules\quanly\base\QuanlyBaseModel;
use Yii;

/**
 * This is the model class for table "tram_thoat_nuoc".
 *
 * @property int $id
 * @property string|null $ma_tram
 * @property string|null $ten_tram
 * @property string|null $loai_hinh
 * @property float|null $cong_suat_m3h
 * @property float|null $duong_kinh_mm
 * @property float|null $do_sau_m
 * @property string|null $vat_lieu
 * @property int|null $nam_xay_dung
 * @property string|null $tinh_trang Hoạt động tốt / Tắc nghẽn / Hư hỏng / Ngập / Đang sửa chữa
 * @property bool|null $co_nap
 * @property string|null $tinh_trang_nap Nguyên vẹn / Vỡ / Mất nắp
 * @property string|null $lan_no_vay_cuoi
 * @property string|null $tan_suat_no_vay
 * @property bool|null $co_nguy_co_ngap
 * @property float|null $do_sau_ngap_cm
 * @property string|null $duong_pho
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
 *
 * @property TuyenCongThoatNuoc[] $tuyenCongThoatNuocs
 * @property TuyenCongThoatNuoc[] $tuyenCongThoatNuocs0
 */
class TramThoatNuoc extends QuanlyBaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tram_thoat_nuoc';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cong_suat_m3h', 'duong_kinh_mm', 'do_sau_m', 'do_sau_ngap_cm', 'lat', 'long'], 'number'],
            [['nam_xay_dung', 'status'], 'default', 'value' => null],
            [['nam_xay_dung', 'status'], 'integer'],
            [['co_nap', 'co_nguy_co_ngap'], 'boolean'],
            [['lan_no_vay_cuoi', 'created_at', 'updated_at'], 'safe'],
            [['ghi_chu', 'geom', 'file_dinhkem'], 'string'],
            [['ma_tram'], 'string', 'max' => 20],
            [['ten_tram', 'duong_pho', 'don_vi_quan_ly'], 'string', 'max' => 255],
            [['loai_hinh', 'vat_lieu', 'tinh_trang', 'tinh_trang_nap', 'tan_suat_no_vay'], 'string', 'max' => 50],
            [['phuong_xa', 'quan_huyen', 'created_by', 'updated_by'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ma_tram' => 'Ma Tram',
            'ten_tram' => 'Ten Tram',
            'loai_hinh' => 'Loai Hinh',
            'cong_suat_m3h' => 'Cong Suat M3h',
            'duong_kinh_mm' => 'Duong Kinh Mm',
            'do_sau_m' => 'Do Sau M',
            'vat_lieu' => 'Vat Lieu',
            'nam_xay_dung' => 'Nam Xay Dung',
            'tinh_trang' => 'Hoạt động tốt / Tắc nghẽn / Hư hỏng / Ngập / Đang sửa chữa',
            'co_nap' => 'Co Nap',
            'tinh_trang_nap' => 'Nguyên vẹn / Vỡ / Mất nắp',
            'lan_no_vay_cuoi' => 'Lan No Vay Cuoi',
            'tan_suat_no_vay' => 'Tan Suat No Vay',
            'co_nguy_co_ngap' => 'Co Nguy Co Ngap',
            'do_sau_ngap_cm' => 'Do Sau Ngap Cm',
            'duong_pho' => 'Duong Pho',
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
        ];
    }

    /**
     * Gets query for [[TuyenCongThoatNuocs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTuyenCongThoatNuocs()
    {
        return $this->hasMany(TuyenCongThoatNuoc::className(), ['diem_dau_id' => 'id']);
    }

    /**
     * Gets query for [[TuyenCongThoatNuocs0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTuyenCongThoatNuocs0()
    {
        return $this->hasMany(TuyenCongThoatNuoc::className(), ['diem_cuoi_id' => 'id']);
    }
}
