<?php

namespace app\modules\quanly\models;
use app\modules\quanly\base\QuanlyBaseModel;
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
            'ma_tuyen'         => 'Mã Tuyến',
            'ten_tuyen'        => 'Tên Tuyến',
            'loai_cong'        => 'Loại Cống',
            'chieu_dai_m'      => 'Chiều Dài (m)',
            'duong_kinh_mm'    => 'Đường Kính (mm)',
            'do_doc_phan_tram' => 'Độ Dốc (%)',
            'vat_lieu'         => 'Vật Liệu',
            'nam_xay_dung'     => 'Năm Xây Dựng',
            'loai_thoat_nuoc'  => 'Loại Thoát Nước (Nước mưa / Nước thải sinh hoạt / Hỗn hợp)',
            'tinh_trang'       => 'Tình Trạng',
            'diem_dau_id'      => 'Điểm Đầu (ID)',
            'diem_cuoi_id'     => 'Điểm Cuối (ID)',
            'phuong_xa'        => 'Phường/Xã',
            'quan_huyen'       => 'Quận/Huyện',
            'don_vi_quan_ly'   => 'Đơn Vị Quản Lý',
            'ghi_chu'          => 'Ghi Chú',
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
