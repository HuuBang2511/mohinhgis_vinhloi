<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\quanly\models\DuongThuy */
?>
<div class="duong-thuy-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'ma_duong_thuy',
            'ten',
            'loai_hinh',
            'chieu_dai_m',
            'dien_tich_m2',
            'chieu_rong_tb_m',
            'do_sau_tb_m',
            'luu_vuc',
            'chuc_nang',
            'co_tiep_can:boolean',
            'tinh_trang_o_nhiem',
            'tinh_trang_bo',
            'co_ke_bo:boolean',
            'co_lan_can:boolean',
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
