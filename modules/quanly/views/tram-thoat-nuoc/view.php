<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\quanly\models\TramThoatNuoc */
?>
<div class="tram-thoat-nuoc-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'ma_tram',
            'ten_tram',
            'loai_hinh',
            'cong_suat_m3h',
            'duong_kinh_mm',
            'do_sau_m',
            'vat_lieu',
            'nam_xay_dung',
            'tinh_trang',
            'co_nap:boolean',
            'tinh_trang_nap',
            'lan_no_vay_cuoi',
            'tan_suat_no_vay',
            'co_nguy_co_ngap:boolean',
            'do_sau_ngap_cm',
            'duong_pho',
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
        ],
    ]) ?>

</div>
