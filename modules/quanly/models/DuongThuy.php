<?php

namespace app\modules\quanly\models;

use Yii;

/**
 * This is the model class for table "duong_thuy".
 *
 * @property int $id
 * @property string|null $ma_duong_thuy
 * @property string $ten
 * @property string|null $loai_hinh
 * @property float|null $chieu_dai_m
 * @property float|null $dien_tich_m2
 * @property float|null $chieu_rong_tb_m
 * @property float|null $do_sau_tb_m
 * @property string|null $luu_vuc
 * @property string|null $chuc_nang Thoát lũ / Tưới tiêu / Giao thông thuỷ / Cảnh quan / Cấp nước
 * @property bool|null $co_tiep_can
 * @property string|null $tinh_trang_o_nhiem Sạch / Ô nhiễm nhẹ / Ô nhiễm nặng
 * @property string|null $tinh_trang_bo
 * @property bool|null $co_ke_bo
 * @property bool|null $co_lan_can
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
 */
class DuongThuy extends QuanlyBaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'duong_thuy';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ten'], 'required'],
            [['chieu_dai_m', 'dien_tich_m2', 'chieu_rong_tb_m', 'do_sau_tb_m', 'lat', 'long'], 'number'],
            [['co_tiep_can', 'co_ke_bo', 'co_lan_can'], 'boolean'],
            [['ghi_chu', 'geom', 'file_dinhkem', 'geojson'], 'string'],
            [['status'], 'default', 'value' => null],
            [['status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['ma_duong_thuy'], 'string', 'max' => 20],
            [['ten', 'luu_vuc', 'don_vi_quan_ly'], 'string', 'max' => 255],
            [['loai_hinh', 'tinh_trang_o_nhiem', 'tinh_trang_bo'], 'string', 'max' => 50],
            [['chuc_nang', 'phuong_xa', 'quan_huyen', 'created_by', 'updated_by'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ma_duong_thuy' => 'Ma Duong Thuy',
            'ten' => 'Ten',
            'loai_hinh' => 'Loai Hinh',
            'chieu_dai_m' => 'Chieu Dai M',
            'dien_tich_m2' => 'Dien Tich M2',
            'chieu_rong_tb_m' => 'Chieu Rong Tb M',
            'do_sau_tb_m' => 'Do Sau Tb M',
            'luu_vuc' => 'Luu Vuc',
            'chuc_nang' => 'Thoát lũ / Tưới tiêu / Giao thông thuỷ / Cảnh quan / Cấp nước',
            'co_tiep_can' => 'Co Tiep Can',
            'tinh_trang_o_nhiem' => 'Sạch / Ô nhiễm nhẹ / Ô nhiễm nặng',
            'tinh_trang_bo' => 'Tinh Trang Bo',
            'co_ke_bo' => 'Co Ke Bo',
            'co_lan_can' => 'Co Lan Can',
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
}
