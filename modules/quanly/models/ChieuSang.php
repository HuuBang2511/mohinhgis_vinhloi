<?php

namespace app\modules\quanly\models;

use Yii;

/**
 * This is the model class for table "chieu_sang".
 *
 * @property int $id
 * @property string|null $ma_cot
 * @property string|null $loai_den
 * @property int|null $cong_suat_w
 * @property float|null $chieu_cao_cot_m
 * @property string|null $chat_lieu_cot
 * @property int|null $nam_lap_dat
 * @property int|null $so_bong_tren_cot
 * @property string|null $tinh_trang Hoạt động tốt / Hỏng / Cháy bóng / Nghiêng đổ / Chờ sửa
 * @property string|null $gio_bat
 * @property string|null $gio_tat
 * @property string|null $nguon_dien Lưới điện quốc gia / Pin mặt trời
 * @property string|null $tu_dieu_khien
 * @property string|null $lan_bao_duong_cuoi
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
class ChieuSang extends QuanlyBaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'chieu_sang';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cong_suat_w', 'nam_lap_dat', 'so_bong_tren_cot', 'status'], 'default', 'value' => null],
            [['cong_suat_w', 'nam_lap_dat', 'so_bong_tren_cot', 'status'], 'integer'],
            [['chieu_cao_cot_m', 'lat', 'long'], 'number'],
            [['gio_bat', 'gio_tat', 'lan_bao_duong_cuoi', 'created_at', 'updated_at'], 'safe'],
            [['ghi_chu', 'geom', 'file_dinhkem'], 'string'],
            [['ma_cot'], 'string', 'max' => 20],
            [['loai_den', 'chat_lieu_cot', 'tinh_trang', 'nguon_dien'], 'string', 'max' => 50],
            [['tu_dieu_khien', 'phuong_xa', 'quan_huyen', 'created_by', 'updated_by'], 'string', 'max' => 100],
            [['duong_pho', 'don_vi_quan_ly'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ma_cot' => 'Ma Cot',
            'loai_den' => 'Loai Den',
            'cong_suat_w' => 'Cong Suat W',
            'chieu_cao_cot_m' => 'Chieu Cao Cot M',
            'chat_lieu_cot' => 'Chat Lieu Cot',
            'nam_lap_dat' => 'Nam Lap Dat',
            'so_bong_tren_cot' => 'So Bong Tren Cot',
            'tinh_trang' => 'Hoạt động tốt / Hỏng / Cháy bóng / Nghiêng đổ / Chờ sửa',
            'gio_bat' => 'Gio Bat',
            'gio_tat' => 'Gio Tat',
            'nguon_dien' => 'Lưới điện quốc gia / Pin mặt trời',
            'tu_dieu_khien' => 'Tu Dieu Khien',
            'lan_bao_duong_cuoi' => 'Lan Bao Duong Cuoi',
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
