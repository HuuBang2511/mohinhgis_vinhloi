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
            'ma_tram'            => 'Mã Trạm/Hố Ga',
            'ten_tram'           => 'Tên Trạm/Hố Ga',
            'loai_hinh'          => 'Loại Hình',
            'cong_suat_m3h'      => 'Công Suất (m³/h)',
            'duong_kinh_mm'      => 'Đường Kính (mm)',
            'do_sau_m'           => 'Độ Sâu (m)',
            'vat_lieu'           => 'Vật Liệu',
            'nam_xay_dung'       => 'Năm Xây Dựng',
            'tinh_trang'         => 'Tình Trạng (Hoạt động tốt / Tắc nghẽn / Hư hỏng / Ngập / Đang sửa chữa)',
            'co_nap'             => 'Có Nắp',
            'tinh_trang_nap'     => 'Tình Trạng Nắp (Nguyên vẹn / Vỡ / Mất nắp)',
            'lan_no_vay_cuoi'    => 'Lần Nạo Vét Cuối',
            'tan_suat_no_vay'    => 'Tần Suất Nạo Vét',
            'co_nguy_co_ngap'    => 'Có Nguy Cơ Ngập',
            'do_sau_ngap_cm'     => 'Độ Sâu Ngập (cm)',
            'duong_pho'          => 'Đường Phố',
            'phuong_xa'          => 'Phường/Xã',
            'quan_huyen'         => 'Quận/Huyện',
            'don_vi_quan_ly'     => 'Đơn Vị Quản Lý',
            'ghi_chu'            => 'Ghi Chú',
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
