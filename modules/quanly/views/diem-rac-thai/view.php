<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\quanly\models\DiemRacThai */
?>
<div class="diem-rac-thai-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'ma_diem',
            'ten_diem',
            'loai_hinh',
            'suc_chua_m3',
            'dien_tich_m2',
            'loai_rac_tiep_nhan',
            'so_thung_rac',
            'the_tich_thung_l',
            'tinh_trang',
            'lich_thu_gom',
            'gio_thu_gom',
            'don_vi_thu_gom',
            'co_mai_che:boolean',
            'co_hang_rao:boolean',
            'co_bien_bao:boolean',
            'hay_bi_qua_tai:boolean',
            'khoang_cach_dan_cu_m',
            'phan_anh_mui:boolean',
            'phuong_xa',
            'quan_huyen',
            'dia_chi_cu_the',
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
