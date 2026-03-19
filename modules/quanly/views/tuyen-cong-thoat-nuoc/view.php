<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\quanly\models\TuyenCongThoatNuoc */
?>
<div class="tuyen-cong-thoat-nuoc-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'ma_tuyen',
            'ten_tuyen',
            'loai_cong',
            'chieu_dai_m',
            'duong_kinh_mm',
            'do_doc_phan_tram',
            'vat_lieu',
            'nam_xay_dung',
            'loai_thoat_nuoc',
            'tinh_trang',
            'diem_dau_id',
            'diem_cuoi_id',
            'phuong_xa',
            'quan_huyen',
            'don_vi_quan_ly',
            'ghi_chu:ntext',
            'lat',
            'long',
            'geom',
            'file_dinhkem:ntext',
            'status',
            'created_at',
            'updated_at',
            'created_by',
            'updated_by',
            'geojson',
        ],
    ]) ?>

</div>
