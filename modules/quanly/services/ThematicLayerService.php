<?php

namespace app\modules\quanly\services;

use app\modules\quanly\models\CayXanh;
use app\modules\quanly\models\ChieuSang;
use app\modules\quanly\models\DiemRacThai;
use app\modules\quanly\models\TramThoatNuoc;
use app\modules\quanly\models\TuyenCongThoatNuoc;
use yii\db\Expression;

class ThematicLayerService
{
    public static function getDefinitions()
    {
        return [
            'cayXanh' => [
                'key' => 'cayXanh',
                'title' => 'Cây xanh đô thị',
                'icon' => 'CX',
                'color' => '#3b82f6',
                'table' => 'cay_xanh',
                'modelClass' => CayXanh::class,
                'idField' => 'id',
                'codeField' => 'ma_cay',
                'nameField' => 'ten_cay',
                'statusField' => 'tinh_trang',
                'typeField' => 'loai_hinh',
                'typeLabel' => 'Loại hình',
                'popupFields' => [
                    'ma_cay' => 'Mã cây',
                    'ten_cay' => 'Tên cây',
                    'loai_hinh' => 'Loại hình',
                    'tinh_trang' => 'Tình trạng',
                    'duong_pho' => 'Đường phố',
                    'phuong_xa' => 'Phường/Xã',
                ],
                'geometryType' => 'point',
                'detailRoute' => '/quanly/cay-xanh/view',
            ],
            'chieuSang' => [
                'key' => 'chieuSang',
                'title' => 'Chiếu sáng công cộng',
                'icon' => 'CS',
                'color' => '#06b6d4',
                'table' => 'chieu_sang',
                'modelClass' => ChieuSang::class,
                'idField' => 'id',
                'codeField' => 'ma_cot',
                'nameField' => 'ma_cot',
                'statusField' => 'tinh_trang',
                'typeField' => 'loai_den',
                'typeLabel' => 'Loại đèn',
                'popupFields' => [
                    'ma_cot' => 'Mã cột',
                    'loai_den' => 'Loại đèn',
                    'tinh_trang' => 'Tình trạng',
                    'duong_pho' => 'Đường phố',
                    'phuong_xa' => 'Phường/Xã',
                ],
                'geometryType' => 'point',
                'detailRoute' => '/quanly/chieu-sang/view',
            ],
            'tramThoatNuoc' => [
                'key' => 'tramThoatNuoc',
                'title' => 'Trạm/điểm thoát nước',
                'icon' => 'TN',
                'color' => '#8b5cf6',
                'table' => 'tram_thoat_nuoc',
                'modelClass' => TramThoatNuoc::class,
                'idField' => 'id',
                'codeField' => 'ma_tram',
                'nameField' => 'ten_tram',
                'statusField' => 'tinh_trang',
                'typeField' => 'loai_hinh',
                'typeLabel' => 'Loại hình',
                'popupFields' => [
                    'ma_tram' => 'Mã trạm',
                    'ten_tram' => 'Tên trạm',
                    'loai_hinh' => 'Loại hình',
                    'tinh_trang' => 'Tình trạng',
                    'phuong_xa' => 'Phường/Xã',
                ],
                'geometryType' => 'point',
                'detailRoute' => '/quanly/tram-thoat-nuoc/view',
            ],
            'tuyenCongThoatNuoc' => [
                'key' => 'tuyenCongThoatNuoc',
                'title' => 'Tuyến cống thoát nước',
                'icon' => 'TC',
                'color' => '#14b8a6',
                'table' => 'tuyen_cong_thoat_nuoc',
                'modelClass' => TuyenCongThoatNuoc::class,
                'idField' => 'id',
                'codeField' => 'ma_tuyen',
                'nameField' => 'ten_tuyen',
                'statusField' => 'tinh_trang',
                'typeField' => 'loai_cong',
                'typeLabel' => 'Loại cống',
                'popupFields' => [
                    'ma_tuyen' => 'Mã tuyến',
                    'ten_tuyen' => 'Tên tuyến',
                    'loai_cong' => 'Loại cống',
                    'tinh_trang' => 'Tình trạng',
                    'phuong_xa' => 'Phường/Xã',
                ],
                'geometryType' => 'line',
                'detailRoute' => '/quanly/tuyen-cong-thoat-nuoc/view',
            ],
            'diemRacThai' => [
                'key' => 'diemRacThai',
                'title' => 'Điểm tập kết rác thải',
                'icon' => 'RT',
                'color' => '#f97316',
                'table' => 'diem_rac_thai',
                'modelClass' => DiemRacThai::class,
                'idField' => 'id',
                'codeField' => 'ma_diem',
                'nameField' => 'ten_diem',
                'statusField' => 'tinh_trang',
                'typeField' => 'loai_hinh',
                'typeLabel' => 'Loại hình',
                'popupFields' => [
                    'ma_diem' => 'Mã điểm',
                    'ten_diem' => 'Tên điểm',
                    'loai_hinh' => 'Loại hình',
                    'tinh_trang' => 'Tình trạng',
                    'dia_chi_cu_the' => 'Địa chỉ',
                ],
                'geometryType' => 'point',
                'detailRoute' => '/quanly/diem-rac-thai/view',
            ],
        ];
    }

    public static function getDefinition($key)
    {
        $definitions = static::getDefinitions();

        return isset($definitions[$key]) ? $definitions[$key] : null;
    }

    public static function buildDashboardData()
    {
        $definitions = static::getDefinitions();
        $layers = [];
        $globalStatuses = [];
        $mainLabels = [];
        $mainKeys = [];
        $summaryLabels = [];
        $summaryColors = [];
        $summaryData = [];

        foreach ($definitions as $key => $definition) {
            $statusRows = static::getGroupedCounts($definition['modelClass'], $definition['statusField']);
            $typeRows = static::getGroupedCounts($definition['modelClass'], $definition['typeField']);
            $total = 0;

            foreach ($statusRows as $row) {
                $total += $row['count'];
                $globalStatuses[$row['value']] = [
                    'label' => $row['value'],
                    'color' => static::getStatusColor($row['value']),
                    'severity' => static::getStatusSeverity($row['value']),
                ];
            }

            $layers[$key] = [
                'key' => $key,
                'title' => $definition['title'],
                'icon' => $definition['icon'],
                'color' => $definition['color'],
                'typeLabel' => $definition['typeLabel'],
                'total' => $total,
                'statuses' => $statusRows,
                'types' => $typeRows,
                'topStatuses' => array_slice($statusRows, 0, 3),
                'statusMap' => static::rowsToMap($statusRows),
            ];

            $mainLabels[] = $definition['title'];
            $mainKeys[] = $key;
            $summaryLabels[] = $definition['title'];
            $summaryColors[] = $definition['color'];
            $summaryData[] = $total;
        }

        uasort($globalStatuses, function ($a, $b) {
            if ($a['severity'] === $b['severity']) {
                return strcmp($a['label'], $b['label']);
            }

            return $a['severity'] - $b['severity'];
        });

        $datasets = [];
        foreach ($globalStatuses as $statusLabel => $statusMeta) {
            $data = [];
            foreach ($layers as $layer) {
                $data[] = isset($layer['statusMap'][$statusLabel]) ? $layer['statusMap'][$statusLabel] : 0;
            }

            $datasets[] = [
                'label' => $statusMeta['label'],
                'backgroundColor' => $statusMeta['color'],
                'borderColor' => $statusMeta['color'],
                'data' => $data,
            ];
        }

        return [
            'layers' => $layers,
            'mainChart' => [
                'labels' => $mainLabels,
                'keys' => $mainKeys,
                'datasets' => $datasets,
            ],
            'summaryChart' => [
                'labels' => $summaryLabels,
                'data' => $summaryData,
                'colors' => $summaryColors,
            ],
        ];
    }

    public static function getFilterMeta()
    {
        $result = [];

        foreach (static::getDefinitions() as $key => $definition) {
            $statusRows = static::getGroupedCounts($definition['modelClass'], $definition['statusField']);
            $typeRows = static::getGroupedCounts($definition['modelClass'], $definition['typeField']);

            $result[$key] = [
                'key' => $key,
                'title' => $definition['title'],
                'icon' => $definition['icon'],
                'color' => $definition['color'],
                'typeLabel' => $definition['typeLabel'],
                'statusField' => $definition['statusField'],
                'typeField' => $definition['typeField'],
                'geometryType' => $definition['geometryType'],
                'popupFields' => $definition['popupFields'],
                'statuses' => $statusRows,
                'types' => $typeRows,
            ];
        }

        return $result;
    }

    public static function getGroupedCounts($modelClass, $field)
    {
        if (empty($field)) {
            return [];
        }

        $rows = $modelClass::find()
            ->select([
                'value' => new Expression('COALESCE(' . $field . ", '(Chưa cập nhật)')"),
                'count' => new Expression('COUNT(*)'),
            ])
            ->where(['status' => 1])
            ->groupBy([$field])
            ->orderBy(['count' => SORT_DESC, 'value' => SORT_ASC])
            ->asArray()
            ->all();

        $normalizedRows = [];

        foreach ($rows as $row) {
            $value = trim((string) $row['value']);
            $count = (int) $row['count'];
            $normalizedRows[] = [
                'value' => $value === '' ? '(Chưa cập nhật)' : $value,
                'count' => $count,
                'color' => static::getStatusColor($value),
                'severity' => static::getStatusSeverity($value),
            ];
        }

        if ($field === 'tinh_trang') {
            usort($normalizedRows, function ($a, $b) {
                if ($a['severity'] === $b['severity']) {
                    return $b['count'] - $a['count'];
                }

                return $a['severity'] - $b['severity'];
            });
        }

        return $normalizedRows;
    }

    public static function rowsToMap($rows)
    {
        $map = [];

        foreach ($rows as $row) {
            $map[$row['value']] = $row['count'];
        }

        return $map;
    }

    public static function getStatusColor($status)
    {
        $severity = static::getStatusSeverity($status);

        if ($severity === 1) {
            return '#ef4444';
        }

        if ($severity === 2) {
            return '#f59e0b';
        }

        if ($severity === 3) {
            return '#22c55e';
        }

        return '#64748b';
    }

    public static function getStatusSeverity($status)
    {
        $value = static::normalizeText($status);

        $dangerKeywords = [
            'hong',
            'chay bong',
            'nghieng do',
            'sau benh',
            'nguy hiem',
            'chet',
            'hu hong',
            'ngap',
            'o nhiem nang',
            'qua tai',
            'tam dong',
            'tac nghen',
            'vo',
            'mat nap',
            'nguy co',
            'sap',
        ];

        foreach ($dangerKeywords as $keyword) {
            if (strpos($value, $keyword) !== false) {
                return 1;
            }
        }

        $warningKeywords = [
            'can cat tia',
            'cho sua',
            'dang sua',
            'dang sua chua',
            'can xu ly',
            'theo doi',
        ];

        foreach ($warningKeywords as $keyword) {
            if (strpos($value, $keyword) !== false) {
                return 2;
            }
        }

        $goodKeywords = [
            'tot',
            'hoat dong tot',
            'hoat dong',
            'nguyen ven',
            'binh thuong',
            'on dinh',
        ];

        foreach ($goodKeywords as $keyword) {
            if (strpos($value, $keyword) !== false) {
                return 3;
            }
        }

        return 4;
    }

    protected static function normalizeText($value)
    {
        $value = trim((string) $value);
        $value = mb_strtolower($value, 'UTF-8');

        $replaceMap = [
            'á' => 'a', 'à' => 'a', 'ả' => 'a', 'ã' => 'a', 'ạ' => 'a',
            'ă' => 'a', 'ắ' => 'a', 'ằ' => 'a', 'ẳ' => 'a', 'ẵ' => 'a', 'ặ' => 'a',
            'â' => 'a', 'ấ' => 'a', 'ầ' => 'a', 'ẩ' => 'a', 'ẫ' => 'a', 'ậ' => 'a',
            'é' => 'e', 'è' => 'e', 'ẻ' => 'e', 'ẽ' => 'e', 'ẹ' => 'e',
            'ê' => 'e', 'ế' => 'e', 'ề' => 'e', 'ể' => 'e', 'ễ' => 'e', 'ệ' => 'e',
            'í' => 'i', 'ì' => 'i', 'ỉ' => 'i', 'ĩ' => 'i', 'ị' => 'i',
            'ó' => 'o', 'ò' => 'o', 'ỏ' => 'o', 'õ' => 'o', 'ọ' => 'o',
            'ô' => 'o', 'ố' => 'o', 'ồ' => 'o', 'ổ' => 'o', 'ỗ' => 'o', 'ộ' => 'o',
            'ơ' => 'o', 'ớ' => 'o', 'ờ' => 'o', 'ở' => 'o', 'ỡ' => 'o', 'ợ' => 'o',
            'ú' => 'u', 'ù' => 'u', 'ủ' => 'u', 'ũ' => 'u', 'ụ' => 'u',
            'ư' => 'u', 'ứ' => 'u', 'ừ' => 'u', 'ử' => 'u', 'ữ' => 'u', 'ự' => 'u',
            'ý' => 'y', 'ỳ' => 'y', 'ỷ' => 'y', 'ỹ' => 'y', 'ỵ' => 'y',
            'đ' => 'd',
        ];

        return strtr($value, $replaceMap);
    }
}

