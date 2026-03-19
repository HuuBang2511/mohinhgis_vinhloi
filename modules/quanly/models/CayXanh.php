<?php

namespace app\modules\quanly\models;
use app\modules\quanly\base\QuanlyBaseModel;
use Yii;

/**
 * This is the model class for table "cay_xanh".
 *
 * @property int $id
 * @property string|null $ma_cay
 * @property string|null $loai_hinh
 * @property string|null $ten_cay
 * @property string|null $ten_khoa_hoc
 * @property int|null $nam_trong
 * @property float|null $duong_kinh_cm
 * @property float|null $chieu_cao_m
 * @property float|null $duong_tan_m
 * @property string|null $ten_khu_vuc
 * @property float|null $dien_tich_m2
 * @property float|null $mat_do_phu_xanh
 * @property string|null $tinh_trang Tốt / Cần cắt tỉa / Sâu bệnh / Nguy hiểm / Chết
 * @property string|null $lan_cat_tinh_cuoi
 * @property string|null $ghi_chu_benh
 * @property string|null $vi_tri_trong Vỉa hè / Dải phân cách / Sân trường / Công viên / Khuôn viên cơ quan
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
 */
class CayXanh extends QuanlyBaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cay_xanh';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nam_trong', 'status'], 'default', 'value' => null],
            [['nam_trong', 'status'], 'integer'],
            [['duong_kinh_cm', 'chieu_cao_m', 'duong_tan_m', 'dien_tich_m2', 'mat_do_phu_xanh', 'lat', 'long'], 'number'],
            [['lan_cat_tinh_cuoi', 'created_at', 'updated_at'], 'safe'],
            [['ghi_chu_benh', 'ghi_chu', 'geom', 'file_dinhkem'], 'string'],
            [['ma_cay'], 'string', 'max' => 20],
            [['loai_hinh', 'tinh_trang'], 'string', 'max' => 50],
            [['ten_cay', 'vi_tri_trong', 'phuong_xa', 'quan_huyen', 'created_by', 'updated_by'], 'string', 'max' => 100],
            [['ten_khoa_hoc'], 'string', 'max' => 150],
            [['ten_khu_vuc', 'duong_pho', 'don_vi_quan_ly'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ma_cay' => 'Ma Cay',
            'loai_hinh' => 'Loai Hinh',
            'ten_cay' => 'Ten Cay',
            'ten_khoa_hoc' => 'Ten Khoa Hoc',
            'nam_trong' => 'Nam Trong',
            'duong_kinh_cm' => 'Duong Kinh Cm',
            'chieu_cao_m' => 'Chieu Cao M',
            'duong_tan_m' => 'Duong Tan M',
            'ten_khu_vuc' => 'Ten Khu Vuc',
            'dien_tich_m2' => 'Dien Tich M2',
            'mat_do_phu_xanh' => 'Mat Do Phu Xanh',
            'tinh_trang' => 'Tốt / Cần cắt tỉa / Sâu bệnh / Nguy hiểm / Chết',
            'lan_cat_tinh_cuoi' => 'Lan Cat Tinh Cuoi',
            'ghi_chu_benh' => 'Ghi Chu Benh',
            'vi_tri_trong' => 'Vỉa hè / Dải phân cách / Sân trường / Công viên / Khuôn viên cơ quan',
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
}
