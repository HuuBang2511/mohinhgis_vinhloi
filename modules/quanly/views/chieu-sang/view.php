<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\quanly\models\ChieuSang */
?>
<div class="chieu-sang-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'ma_cot',
            'loai_den',
            'cong_suat_w',
            'chieu_cao_cot_m',
            'chat_lieu_cot',
            'nam_lap_dat',
            'so_bong_tren_cot',
            'tinh_trang',
            'gio_bat',
            'gio_tat',
            'nguon_dien',
            'tu_dieu_khien',
            'lan_bao_duong_cuoi',
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
