<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\quanly\models\CayXanh */
?>
<div class="cay-xanh-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'ma_cay',
            'loai_hinh',
            'ten_cay',
            'ten_khoa_hoc',
            'nam_trong',
            'duong_kinh_cm',
            'chieu_cao_m',
            'duong_tan_m',
            'ten_khu_vuc',
            'dien_tich_m2',
            'mat_do_phu_xanh',
            'tinh_trang',
            'lan_cat_tinh_cuoi',
            'ghi_chu_benh:ntext',
            'vi_tri_trong',
            'duong_pho',
            'phuong_xa',
            'quan_huyen',
            'don_vi_quan_ly',
            'ghi_chu:ntext',
            'lat',
            'long',
            'geom',
            'file_dinhkem',
            'status',
            'created_at',
            'updated_at',
            'created_by',
            'updated_by',
        ],
    ]) ?>

</div>
