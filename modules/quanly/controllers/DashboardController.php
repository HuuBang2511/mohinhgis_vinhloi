<?php

namespace app\modules\quanly\controllers;

use app\modules\quanly\base\QuanlyBaseController;
use app\modules\quanly\models\CayXanh;
use app\modules\quanly\models\ChieuSang;
use app\modules\quanly\models\DiemRacThai;
use app\modules\quanly\models\TramThoatNuoc;
use app\modules\quanly\models\TuyenCongThoatNuoc;

class DashboardController extends QuanlyBaseController
{
    public function actionIndex()
    {
        $cayXanhTotal = (int) CayXanh::find()->where(['status' => 1])->count();
        $cayXanhDo = (int) CayXanh::find()
            ->where(['status' => 1])
            ->andWhere(['in', 'tinh_trang', ['Sâu bệnh', 'Nguy hiểm', 'Chết']])
            ->count();
        $cayXanhXanh = (int) CayXanh::find()
            ->where(['status' => 1, 'tinh_trang' => 'Tốt'])
            ->count();
        $cayXanhVang = max(0, $cayXanhTotal - $cayXanhDo - $cayXanhXanh);

        $chieuSangTotal = (int) ChieuSang::find()->where(['status' => 1])->count();
        $chieuSangDo = (int) ChieuSang::find()
            ->where(['status' => 1])
            ->andWhere(['in', 'tinh_trang', ['Hỏng', 'Cháy bóng', 'Nghiêng đổ']])
            ->count();
        $chieuSangXanh = (int) ChieuSang::find()
            ->where(['status' => 1, 'tinh_trang' => 'Hoạt động tốt'])
            ->count();
        $chieuSangVang = max(0, $chieuSangTotal - $chieuSangDo - $chieuSangXanh);

        $tramThoatNuocTotal = (int) TramThoatNuoc::find()->where(['status' => 1])->count();
        $tramThoatNuocDo = (int) TramThoatNuoc::find()
            ->where(['status' => 1])
            ->andWhere([
                'or',
                ['in', 'tinh_trang', ['Hư hỏng', 'Ngập']],
                ['in', 'tinh_trang_nap', ['Vỡ', 'Mất nắp']],
            ])
            ->count();
        $tramThoatNuocXanh = (int) TramThoatNuoc::find()
            ->where([
                'status' => 1,
                'tinh_trang' => 'Hoạt động tốt',
                'tinh_trang_nap' => 'Nguyên vẹn',
                'co_nguy_co_ngap' => false,
            ])
            ->count();
        $tramThoatNuocVang = max(0, $tramThoatNuocTotal - $tramThoatNuocDo - $tramThoatNuocXanh);

        $tuyenCongTotal = (int) TuyenCongThoatNuoc::find()->where(['status' => 1])->count();
        $tuyenCongDo = (int) TuyenCongThoatNuoc::find()
            ->where(['status' => 1])
            ->andWhere([
                'or',
                ['like', 'tinh_trang', 'Hư', false],
                ['like', 'tinh_trang', 'Sập', false],
                ['like', 'tinh_trang', 'Vỡ', false],
            ])
            ->count();
        $tuyenCongXanh = (int) TuyenCongThoatNuoc::find()
            ->where(['status' => 1])
            ->andWhere(['in', 'tinh_trang', ['Tốt', 'Hoạt động tốt']])
            ->count();
        $tuyenCongVang = max(0, $tuyenCongTotal - $tuyenCongDo - $tuyenCongXanh);

        $diemRacThaiTotal = (int) DiemRacThai::find()->where(['status' => 1])->count();
        $diemRacThaiDo = (int) DiemRacThai::find()
            ->where(['status' => 1])
            ->andWhere([
                'or',
                ['tinh_trang' => 'Ô nhiễm nặng'],
                ['and', ['hay_bi_qua_tai' => true], ['phan_anh_mui' => true]],
            ])
            ->count();
        $diemRacThaiXanh = (int) DiemRacThai::find()
            ->where(['status' => 1, 'tinh_trang' => 'Hoạt động'])
            ->count();
        $diemRacThaiVang = max(0, $diemRacThaiTotal - $diemRacThaiDo - $diemRacThaiXanh);

        $chartData = [
            'labels' => [
                'Cây xanh',
                'Chiếu sáng',
                'Trạm thoát nước',
                'Tuyến cống',
                'Điểm rác thải',
            ],
            'do' => [
                $cayXanhDo,
                $chieuSangDo,
                $tramThoatNuocDo,
                $tuyenCongDo,
                $diemRacThaiDo,
            ],
            'vang' => [
                $cayXanhVang,
                $chieuSangVang,
                $tramThoatNuocVang,
                $tuyenCongVang,
                $diemRacThaiVang,
            ],
            'xanh' => [
                $cayXanhXanh,
                $chieuSangXanh,
                $tramThoatNuocXanh,
                $tuyenCongXanh,
                $diemRacThaiXanh,
            ],
        ];

        $summaryChartData = [
            'labels' => ['Đỏ (Nghiêm trọng)', 'Vàng (Cần xử lý)', 'Xanh (Ổn định)'],
            'data' => [
                array_sum($chartData['do']),
                array_sum($chartData['vang']),
                array_sum($chartData['xanh']),
            ],
        ];

        $layerData = [
            'cayXanh' => [
                'title' => 'Cây xanh đô thị',
                'chart' => ['do' => $cayXanhDo, 'vang' => $cayXanhVang, 'xanh' => $cayXanhXanh],
            ],
            'chieuSang' => [
                'title' => 'Chiếu sáng công cộng',
                'chart' => ['do' => $chieuSangDo, 'vang' => $chieuSangVang, 'xanh' => $chieuSangXanh],
            ],
            'tramThoatNuoc' => [
                'title' => 'Trạm thoát nước',
                'chart' => ['do' => $tramThoatNuocDo, 'vang' => $tramThoatNuocVang, 'xanh' => $tramThoatNuocXanh],
            ],
            'tuyenCongThoatNuoc' => [
                'title' => 'Tuyến cống thoát nước',
                'chart' => ['do' => $tuyenCongDo, 'vang' => $tuyenCongVang, 'xanh' => $tuyenCongXanh],
            ],
            'diemRacThai' => [
                'title' => 'Điểm rác thải',
                'chart' => ['do' => $diemRacThaiDo, 'vang' => $diemRacThaiVang, 'xanh' => $diemRacThaiXanh],
            ],
        ];

        return $this->render('index', [
            'chartData' => $chartData,
            'summaryChartData' => $summaryChartData,
            'layerData' => $layerData,
        ]);
    }
}
