<?php

namespace app\modules\quanly\models;

use Yii;

/**
 * This is the model class for table "diem_rac_thai".
 *
 * @property int $id
 * @property string|null $ma_diem
 * @property string|null $ten_diem
 * @property string $loai_hinh Điểm tập kết / Trạm trung chuyển / Bãi chứa tạm / Thùng rác công cộng
 * @property float|null $suc_chua_m3
 * @property float|null $dien_tich_m2
 * @property string|null $loai_rac_tiep_nhan Rác sinh hoạt / Rác cồng kềnh / Rác tái chế / Rác nguy hại / Tất cả
 * @property int|null $so_thung_rac
 * @property int|null $the_tich_thung_l
 * @property string|null $tinh_trang Hoạt động / Quá tải / Tạm đóng / Ô nhiễm nặng
 * @property string|null $lich_thu_gom
 * @property string|null $gio_thu_gom
 * @property string|null $don_vi_thu_gom
 * @property bool|null $co_mai_che
 * @property bool|null $co_hang_rao
 * @property bool|null $co_bien_bao
 * @property bool|null $hay_bi_qua_tai
 * @property float|null $khoang_cach_dan_cu_m
 * @property bool|null $phan_anh_mui
 * @property string|null $phuong_xa
 * @property string|null $quan_huyen
 * @property string|null $dia_chi_cu_the
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
class DiemRacThai extends QuanlyBaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'diem_rac_thai';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['loai_hinh'], 'required'],
            [['suc_chua_m3', 'dien_tich_m2', 'khoang_cach_dan_cu_m', 'lat', 'long'], 'number'],
            [['so_thung_rac', 'the_tich_thung_l', 'status'], 'default', 'value' => null],
            [['so_thung_rac', 'the_tich_thung_l', 'status'], 'integer'],
            [['co_mai_che', 'co_hang_rao', 'co_bien_bao', 'hay_bi_qua_tai', 'phan_anh_mui'], 'boolean'],
            [['ghi_chu', 'geom', 'file_dinhkem'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['ma_diem'], 'string', 'max' => 20],
            [['ten_diem', 'don_vi_thu_gom', 'dia_chi_cu_the', 'don_vi_quan_ly'], 'string', 'max' => 255],
            [['loai_hinh', 'tinh_trang', 'gio_thu_gom'], 'string', 'max' => 50],
            [['loai_rac_tiep_nhan', 'lich_thu_gom', 'phuong_xa', 'quan_huyen', 'created_by', 'updated_by'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ma_diem' => 'Mã điểm',
            'ten_diem' => 'Tên điểm',
            'loai_hinh' => 'Điểm tập kết / Trạm trung chuyển / Bãi chứa tạm / Thùng rác công cộng',
            'suc_chua_m3' => 'Suc Chua M3',
            'dien_tich_m2' => 'Dien Tich M2',
            'loai_rac_tiep_nhan' => 'Rác sinh hoạt / Rác cồng kềnh / Rác tái chế / Rác nguy hại / Tất cả',
            'so_thung_rac' => 'So Thung Rac',
            'the_tich_thung_l' => 'The Tich Thung L',
            'tinh_trang' => 'Hoạt động / Quá tải / Tạm đóng / Ô nhiễm nặng',
            'lich_thu_gom' => 'Lịch Thu Gom',
            'gio_thu_gom' => 'Giờ Thu Gom',
            'don_vi_thu_gom' => 'Đơn vị Thu Gom',
            'co_mai_che' => 'Có mái che',
            'co_hang_rao' => 'Có hàng rào',
            'co_bien_bao' => 'Có biển báo',
            'hay_bi_qua_tai' => 'Hay Bi Qua Tai',
            'khoang_cach_dan_cu_m' => 'Khoang Cach Dan Cu M',
            'phan_anh_mui' => 'Phan Anh Mui',
            'phuong_xa' => 'Phuong Xa',
            'quan_huyen' => 'Quan Huyen',
            'dia_chi_cu_the' => 'Dia Chi Cu The',
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
