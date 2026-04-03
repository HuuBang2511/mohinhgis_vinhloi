<?php

use yii\helpers\Json;
use yii\helpers\Html;
use yii\helpers\Url;

// Đăng ký các asset cần thiết.
app\widgets\maps\LeafletMapAsset::register($this);
app\widgets\maps\plugins\leafletprint\PrintMapAsset::register($this);
app\widgets\maps\plugins\markercluster\MarkerClusterAsset::register($this);
app\widgets\maps\plugins\leaflet_measure\LeafletMeasureAsset::register($this);
app\widgets\maps\plugins\leafletlocate\LeafletLocateAsset::register($this);

$this->title = 'Bản đồ GIS - Dịch vụ đô thị';
$this->params['hideHero'] = true;

// Tạo URL cơ sở cho tất cả các trang chi tiết
$vuViecDetailUrlBase = Url::to(['/quanly/vu-viec/view']);
$diemNhayCamDetailUrlBase = Url::to(['/quanly/diem-nhay-cam/view']);
$thematicMetaJson = Json::encode(isset($thematicMeta) ? $thematicMeta : []);
$kpOptionsJson = Json::encode(isset($kpOptions) ? $kpOptions : []);
?>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

<!-- Import Google Font & Icons -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap" rel="stylesheet">
<script src="https://unpkg.com/lucide@latest"></script>
<script src="https://unpkg.com/leaflet.heat@0.2.0/dist/leaflet-heat.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

<style>
    :root {
        --primary-color: #0d6efd;
        --light-gray: #f8fafc;
        --border-color: #e5e7eb;
        --background-color: #ffffff;
        --text-color: #1e293b;
        --text-light-color: #64748b;
        --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        --shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px -1px rgba(0, 0, 0, 0.1);
        --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -4px rgba(0, 0, 0, 0.1);
        --transition-speed: 0.3s;
        --font-family: 'Inter', sans-serif;
        --app-height: 100vh;
    }

    body, html {
        margin: 0; padding: 0; height: 100%; width: 100%;
        overflow: hidden; font-family: var(--font-family); color: var(--text-color);
        background-color: var(--light-gray);
    }

    #mapInfo {
        display: flex; height: var(--app-height);
    }

    #mapTong {
        flex-grow: 1; height: 100%; transition: width var(--transition-speed); position: relative;
    }

    #map {
        height: 100%; width: 100%; background-color: var(--light-gray);
    }

    /* --- Side Panel (Tabs) --- */
    #tabs {
        width: 25%; max-width: 380px; min-width: 320px;
        background: var(--background-color); border-right: 1px solid var(--border-color);
        transition: transform var(--transition-speed) ease-in-out, min-width var(--transition-speed) ease-in-out, width var(--transition-speed) ease-in-out;
        display: flex; flex-direction: column; transform: translateX(0);
        box-shadow: var(--shadow-lg);
    }

    #tabs.hidden {
        min-width: 0; width: 0; transform: translateX(0); border-right: none;
    }
    
    .tabs-header {
        display: flex; justify-content: space-between; align-items: center;
        padding: 10px 15px; border-bottom: 1px solid var(--border-color); flex-shrink: 0;
    }
    .header-title {
        font-size: 14px;
        font-weight: 700;
        color: var(--primary-color);
        line-height: 1.3;
        flex: 1;
    }

    .tab-buttons { display: flex; border-bottom: 1px solid var(--border-color); flex-shrink: 0; }
    .tab-button {
        flex: 1; padding: 12px; text-align: center; cursor: pointer;
        background: var(--background-color); border: none; font-weight: 500;
        color: var(--text-light-color); border-bottom: 3px solid transparent;
        transition: color 0.2s, border-color 0.2s;
    }
    .tab-button:hover { color: var(--primary-color); }
    .tab-button.active { color: var(--text-color); border-bottom: 3px solid var(--primary-color); }

    .tab-content { display: none; padding: 15px; overflow-y: auto; flex-grow: 1; -webkit-overflow-scrolling: touch; }
    .tab-content.active { display: block; }
    
    .section-title { font-size: 1.1rem; font-weight: 600; margin-bottom: 1rem; }
    #search-box { position: relative; margin-bottom: 1rem; }
    #search-input { width: 100%; padding: 8px 12px 8px 36px; border: 1px solid var(--border-color); border-radius: 8px; box-sizing: border-box; }
    #search-box .icon { position: absolute; left: 10px; top: 50%; transform: translateY(-50%); color: var(--text-light-color); }
    
    #feature-details { word-wrap: break-word; }
    .popup-content table { width: 100%; border-collapse: collapse; font-size: 14px; }
    .popup-content th, .popup-content td { padding: 10px; text-align: left; border-bottom: 1px solid var(--border-color); }
    .popup-content th { font-weight: 500; width: 40%; color: var(--text-light-color); }
    .popup-content h4 { margin-top: 0; color: var(--primary-color); }

    .detail-button {
        display: inline-flex;
        align-items: center;
        text-decoration: none;
        font-size: 14px;
        color: white;
        background-color: var(--primary-color);
        padding: 8px 15px;
        border-radius: 8px;
        font-weight: 500;
        transition: background-color 0.2s;
    }
    .detail-button:hover {
        background-color: #0b5ed7;
    }
    .detail-button .icon {
        width: 16px;
        height: 16px;
        margin-right: 6px;
    }

    .legend { background-color: var(--background-color); padding: 15px; border-radius: 8px; box-shadow: var(--shadow-lg); display: none; max-height: 40vh; overflow-y: auto; }
    .legend-item { display: flex; align-items: center; margin-bottom: 8px; font-size: 14px; }
    .legend img { width: 20px; height: 20px; margin-right: 10px; }

    #toggle-tab-btn {
        position: absolute; top: 15px; left: 15px; z-index: 1000;
        background: var(--background-color); border: 1px solid var(--border-color); border-radius: 8px;
        width: 40px; height: 40px; cursor: pointer; display: flex; align-items: center; justify-content: center;
        box-shadow: var(--shadow);
    }
    
    .leaflet-bar { border-radius: 8px !important; box-shadow: var(--shadow) !important; }

    /* Loading Overlay */
    #loading-overlay {
        position: absolute; top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(255, 255, 255, 0.7); z-index: 20000;
        display: flex; align-items: center; justify-content: center;
        transition: opacity 0.3s;
    }
    #loading-overlay.hidden { opacity: 0; pointer-events: none; }
    .spinner { width: 50px; height: 50px; border: 5px solid #f3f3f3; border-top: 5px solid var(--primary-color); border-radius: 50%; animation: spin 1s linear infinite; }
    @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }


    /* --- STYLES MỚI CHO CÂY THƯ MỤC --- */
    .layer-tree {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .layer-tree li {
        padding-left: 0;
    }
    .layer-tree details {
        padding-left: 20px;
    }
    .layer-tree details[open] > summary {
        margin-bottom: 5px;
    }
    .layer-tree details > summary {
        display: flex;
        align-items: center;
        cursor: pointer;
        padding: 8px;
        border-radius: 6px;
        font-weight: 500;
        list-style: none; /* Tắt marker mặc định */
    }
    .layer-tree details > summary:hover {
        background-color: var(--light-gray);
    }
    .layer-tree details > summary::before { /* Tạo marker tùy chỉnh */
        content: '\25B6'; /* Tam giác phải (đóng) */
        margin-right: 8px;
        font-size: 0.7em;
        transition: transform 0.2s;
    }
    .layer-tree details[open] > summary::before {
        transform: rotate(90deg); /* Tam giác xuống (mở) */
    }
    .layer-tree summary .icon {
        margin-right: 8px;
        width: 18px;
        height: 18px;
        color: var(--text-light-color);
    }
    .layer-tree ul {
        list-style: none;
        padding-left: 20px; /* Thụt lề cho các mục con */
    }
    .layer-tree-item { /* Đây là một layer cụ thể */
        display: flex;
        align-items: center;
        padding: 6px 8px;
        border-radius: 6px;
    }
    .layer-tree-item:hover {
        background-color: var(--light-gray);
    }
    .layer-tree-item .icon {
        margin-right: 8px;
        width: 16px;
        height: 16px;
        color: var(--text-light-color);
        flex-shrink: 0;
    }
    .layer-tree-item label {
        display: flex;
        align-items: center;
        width: 100%;
        cursor: pointer;
    }
    .layer-tree-item label span {
        flex-grow: 1;
        font-size: 14px;
    }
    .layer-tree-item input[type="checkbox"] {
        margin-left: auto;
        flex-shrink: 0;
    }
    /* --- Kết thúc CSS mới --- */

    .report-card {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px;
        border: 1px solid var(--border-color);
        border-radius: 10px;
        margin-bottom: 10px;
        text-decoration: none;
        color: var(--text-color);
        transition: background 0.2s, box-shadow 0.2s;
        background: var(--background-color);
    }
    .report-card:hover {
        background: var(--light-gray);
        box-shadow: var(--shadow);
    }
    .report-icon {
        width: 22px; height: 22px;
        color: var(--primary-color);
        flex-shrink: 0;
    }
    .report-card-title { font-weight: 600; font-size: 14px; }
    .report-card-desc  { font-size: 12px; color: var(--text-light-color); margin-top: 2px; }

    .filter-box {
        border: 1px solid var(--border-color);
        border-radius: 10px;
        padding: 14px;
        background: var(--background-color);
        margin-bottom: 12px;
    }

    .filter-label {
        display: block;
        font-size: 12px;
        font-weight: 600;
        color: var(--text-light-color);
        margin-bottom: 6px;
    }

    .filter-select {
        width: 100%;
        padding: 9px 10px;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        background: #fff;
        box-sizing: border-box;
        margin-bottom: 12px;
    }

    .filter-checklist {
        display: grid;
        gap: 8px;
        max-height: 220px;
        overflow-y: auto;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        padding: 10px;
        background: var(--light-gray);
        margin-bottom: 12px;
    }

    .filter-check-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
    }

    .filter-actions {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .filter-btn {
        border: none;
        border-radius: 8px;
        padding: 9px 12px;
        cursor: pointer;
        font-size: 13px;
        font-weight: 600;
    }

    .filter-btn.primary {
        background: var(--primary-color);
        color: #fff;
    }

    .filter-btn.secondary {
        background: var(--light-gray);
        color: var(--text-color);
        border: 1px solid var(--border-color);
    }

    .filter-summary {
        margin-top: 12px;
        padding: 12px;
        border-radius: 10px;
        border: 1px solid rgba(13, 110, 253, 0.18);
        background: linear-gradient(135deg, rgba(13, 110, 253, 0.08), rgba(14, 165, 233, 0.04));
    }

    .filter-summary__label {
        font-size: 12px;
        color: var(--text-light-color);
        margin-bottom: 4px;
    }

    .filter-summary__value {
        font-size: 28px;
        font-weight: 700;
        color: var(--text-color);
        line-height: 1.1;
    }

    .filter-summary__meta {
        margin-top: 4px;
        font-size: 12px;
        color: var(--text-light-color);
    }


    @media screen and (max-width: 768px) {
        #tabs {
            width: 100%; max-width: none; position: absolute; top: 0; left: 0;
            height: var(--app-height); z-index: 2000; transform: translateX(-100%);
            border-right: none;
        }
        #tabs.active { transform: translateX(0); }
        #mapTong { width: 100% !important; }
    }
</style>

<div id="mapInfo">
    <div id="tabs">
        <div class="tabs-header">
            <a href="<?= Yii::$app->homeUrl ?>" target="_blank" style="display: flex; align-items: center; text-decoration: none;">
                <div class="header-title">Bản đồ số dịch vụ đô thị<br><span style="font-size: 11px; font-weight: 500; color: var(--text-light-color);">Phường Cái Răng, TP. Cần Thơ</span></div>
            </a>
            <button id="back-to-map-mobile-btn"></button>
        </div>
        
        <div class="tab-buttons">
            <button class="tab-button active" data-tab="layer">Lớp dữ liệu</button>
            <button class="tab-button" data-tab="filter">Lọc</button>
            <button class="tab-button" data-tab="info">Thông tin</button>
            <button class="tab-button" data-tab="report">Cán bộ hiện trường/người dân</button>
        </div>

        <div id="layer-content" class="tab-content active">
            <div class="section-title">Tìm kiếm Vụ việc</div>
             <div id="search-box">
                <i class="icon" data-lucide="search" style="width:18px; height:18px;"></i>
                <input type="text" id="search-input" placeholder="Nhập mã hoặc nội dung...">
            </div>

            <div class="section-title">Các lớp dữ liệu</div>
            <!-- CẤU TRÚC CÂY THƯ MỤC HTML MỚI -->
            <div id="layer-control">
                <ul class="layer-tree">
                    <!-- NHÓM 1: DỮ LIỆU NỀN -->
                    <li>
                        <details open>
                            <summary>
                                <i data-lucide="database" class="icon"></i>
                                Các lớp dữ liệu nền
                            </summary>
                            <ul>
                                <li class="layer-tree-item">
                                    <i data-lucide="map" class="icon"></i>
                                    <label>
                                        <span>Phường/Xã</span>
                                        <input type="checkbox" data-layer-id="wmsPhuongXaLayer" data-layer-type="wms" data-z-index="440" 
                                               data-wms-name="mohinhgis_pa05:phuongxa" data-display-name="Phường/Xã" 
                                               data-popup-fields='{"tenXa": "Tên Xã", "maXa": "Mã Xã", "tenTinh": "Tên Tỉnh", "danSo": "Dân số", "dienTich": "Diện tích"}' checked>
                                    </label>
                                </li>
                                <li class="layer-tree-item">
                                    <i data-lucide="map" class="icon"></i>
                                    <label>
                                        <span>Khu phố</span>
                                        <input type="checkbox" data-layer-id="wmsKhuphoLayer" data-layer-type="wms" data-z-index="450" 
                                               data-wms-name="mohinhgis_pa05:kp" data-display-name="Khu phố" 
                                               data-popup-fields='{"TenKhuPho": "Tên khu phố"}'>
                                    </label>
                                </li>
                                <li class="layer-tree-item">
                                    <i data-lucide="road" class="icon"></i>
                                    <label>
                                        <span>Đường giao thông</span>
                                        <input type="checkbox" data-layer-id="wmsGiaoThongLayer" data-layer-type="wms" data-z-index="445" 
                                               data-wms-name="mohinhgis_pa05:giaothong" data-display-name="Đường giao thông" 
                                               data-popup-fields='{"name": "Tên đường", "fclass": "Loại đường"}'>
                                    </label>
                                </li>
                                <li class="layer-tree-item">
                                    <i data-lucide="waves" class="icon"></i>
                                    <label>
                                        <span>Đường thuỷ</span>
                                        <input type="checkbox" data-layer-id="wmsDuongThuyLayer" data-layer-type="wms" data-z-index="500" 
                                               data-wms-name="mohinhgis_pa05:duong_thuy" data-display-name="Đường thuỷ" 
                                               data-popup-fields='{"ma_duong_thuy": "Mã", "ten": "Tên", "loai_hinh": "Loại hình", "chuc_nang": "Chức năng"}'>
                                    </label>
                                </li>
                            </ul>
                        </details>
                    </li>

                    <!-- NHÓM 2: DỊCH VỤ ĐÔ THỊ -->
                    <li>
                        <details open>
                            <summary>
                                <i data-lucide="building-2" class="icon"></i>
                                Dịch vụ đô thị
                            </summary>
                            <ul>
                                <li class="layer-tree-item">
                                    <i data-lucide="trees" class="icon"></i>
                                    <label>
                                        <span>Cây xanh đô thị</span>
                                        <input type="checkbox" data-layer-id="wmsCayXanhLayer" data-layer-type="wms" data-z-index="510" 
                                               data-wms-name="mohinhgis_pa05:cay_xanh" data-display-name="Cây xanh đô thị" 
                                               data-popup-fields='{"ma_cay": "Mã cây", "ten_cay": "Tên cây", "loai_hinh": "Loại hình", "tinh_trang": "Tình trạng"}'>
                                    </label>
                                </li>
                                <li class="layer-tree-item">
                                    <i data-lucide="lightbulb" class="icon"></i>
                                    <label>
                                        <span>Chiếu sáng công cộng</span>
                                        <input type="checkbox" data-layer-id="wmsChieuSangLayer" data-layer-type="wms" data-z-index="520" 
                                               data-wms-name="mohinhgis_pa05:chieu_sang" data-display-name="Chiếu sáng" 
                                               data-popup-fields='{"ma_cot": "Mã cột", "loai_den": "Loại đèn", "tinh_trang": "Tình trạng"}'>
                                    </label>
                                </li>
                                <li class="layer-tree-item">
                                    <i data-lucide="droplet-off" class="icon"></i>
                                    <label>
                                        <span>Trạm/điểm thoát nước</span>
                                        <input type="checkbox" data-layer-id="wmsTramThoatNuocLayer" data-layer-type="wms" data-z-index="530" 
                                               data-wms-name="mohinhgis_pa05:tram_thoat_nuoc" data-display-name="Trạm/điểm thoát nước" 
                                               data-popup-fields='{"ma_tram": "Mã trạm", "ten_tram": "Tên trạm", "loai_hinh": "Loại hình", "tinh_trang": "Tình trạng"}'>
                                    </label>
                                </li>
                                <li class="layer-tree-item">
                                    <i data-lucide="pipette" class="icon"></i>
                                    <label>
                                        <span>Tuyến cống thoát nước</span>
                                        <input type="checkbox" data-layer-id="wmsTuyenCongThoatNuocLayer" data-layer-type="wms" data-z-index="525" 
                                               data-wms-name="mohinhgis_pa05:tuyen_cong_thoat_nuoc" data-display-name="Tuyến cống thoát nước" 
                                               data-popup-fields='{"ma_tuyen": "Mã tuyến", "ten_tuyen": "Tên tuyến", "loai_cong": "Loại cống"}'>
                                    </label>
                                </li>
                                <li class="layer-tree-item">
                                    <i data-lucide="trash-2" class="icon"></i>
                                    <label>
                                        <span>Điểm tập kết rác thải</span>
                                        <input type="checkbox" data-layer-id="wmsDiemRacThaiLayer" data-layer-type="wms" data-z-index="540" 
                                               data-wms-name="mohinhgis_pa05:diem_rac_thai" data-display-name="Điểm tập kết rác thải" 
                                               data-popup-fields='{"ma_diem": "Mã điểm", "ten_diem": "Tên điểm", "loai_hinh": "Loại hình", "tinh_trang": "Tình trạng"}'>
                                    </label>
                                </li>
                            </ul>
                        </details>
                    </li>

                    <!-- NHÓM 3: AN NINH & SỰ CỐ -->
                    <li>
                        <details open>
                            <summary>
                                <i data-lucide="shield-alert" class="icon"></i>
                                An ninh & Sự cố
                            </summary>
                            <ul>
                                <li class="layer-tree-item">
                                    <i data-lucide="camera" class="icon"></i>
                                    <label>
                                        <span>Camera an ninh</span>
                                        <input type="checkbox" data-layer-id="wmsCameraAnNinhLayer" data-layer-type="wms" data-z-index="568" 
                                               data-wms-name="mohinhgis_pa05:camera_an_ninh" data-display-name="Camera an ninh" 
                                               data-popup-fields='{"ma_camera": "Mã camera", "ten_diem": "Tên điểm", "dia_chi": "Địa chỉ", "trang_thai": "Trạng thái"}'>
                                    </label>
                                </li>
                                <li class="layer-tree-item">
                                    <i data-lucide="map-pin" class="icon"></i>
                                    <label>
                                        <span>Phản ánh sự cố (Vụ việc)</span>
                                        <input type="checkbox" data-layer-id="wmsVuviecLayer" data-layer-type="wms" data-z-index="550" 
                                               data-wms-name="mohinhgis_pa05:vu_viec" data-display-name="Phản ánh sự cố" 
                                               data-popup-fields='{"ma_vu_viec": "Mã vụ việc", "tom_tat_noi_dung": "Tóm tắt nội dung"}'>
                                    </label>
                                </li>
                                <li class="layer-tree-item">
                                    <i data-lucide="siren" class="icon"></i>
                                    <label>
                                        <span>Điểm nhạy cảm</span>
                                        <input type="checkbox" data-layer-id="wmsDiemnhaycamLayer" data-layer-type="wms" data-z-index="540" 
                                               data-wms-name="mohinhgis_pa05:diem_nhay_cam" data-display-name="Điểm nhạy cảm" 
                                               data-popup-fields='{"tenloaihinh": "Tên loại hình", "thongtin": "Thông tin"}'>
                                    </label>
                                </li>
                            </ul>
                        </details>
                    </li>
                </ul>
            </div>
            <!-- KẾT THÚC CẤU TRÚC CÂY THƯ MỤC -->
        </div>

        <div id="filter-content" class="tab-content">
            <div class="section-title">Lọc lớp chuyên đề</div>
            <div class="filter-box">
                <label class="filter-label" for="thematic-layer-select">Chọn lớp chuyên đề</label>
                <select id="thematic-layer-select" class="filter-select">
                    <option value="">-- Chọn lớp --</option>
                    <?php foreach ($thematicMeta as $key => $meta): ?>
                        <option value="<?= Html::encode($key) ?>"><?= Html::encode($meta['title']) ?></option>
                    <?php endforeach; ?>
                </select>

                <label class="filter-label" for="kp-filter-select">Khu phố</label>
                <select id="kp-filter-select" class="filter-select">
                    <option value="">-- Tất cả khu phố --</option>
                    <?php foreach ($kpOptions as $kp): ?>
                        <option value="<?= (int) $kp['id'] ?>"><?= Html::encode($kp['name']) ?></option>
                    <?php endforeach; ?>
                </select>

                <label class="filter-label">Tình trạng</label>
                <div id="status-filter-list" class="filter-checklist">
                    <div style="font-size:12px; color:var(--text-light-color);">Chọn lớp để hiển thị tiêu chí lọc.</div>
                </div>

                <label class="filter-label">Phân loại</label>
                <div id="type-filter-list" class="filter-checklist">
                    <div style="font-size:12px; color:var(--text-light-color);">Chọn lớp để hiển thị tiêu chí lọc.</div>
                </div>

                <div class="filter-actions">
                    <button type="button" id="apply-thematic-filter" class="filter-btn primary">Áp dụng bộ lọc</button>
                    <button type="button" id="clear-thematic-filter" class="filter-btn secondary">Xóa bộ lọc</button>
                </div>

                <div id="thematic-filter-summary" class="filter-summary">
                    <div class="filter-summary__label">Tổng số đối tượng</div>
                    <div class="filter-summary__value">0</div>
                    <div class="filter-summary__meta">Chọn lớp chuyên đề và bấm lọc để xem số lượng trong khu phố.</div>
                </div>
            </div>
            <p style="font-size:12px; color:var(--text-light-color); margin:0;">
                Ví dụ: chọn lớp <strong>Cây xanh đô thị</strong>, sau đó chọn một tình trạng như <strong>Hỏng</strong> hoặc giá trị tương ứng hiện có trong dữ liệu để chỉ hiển thị các đối tượng phù hợp.
            </p>
        </div>

        <div id="info-content" class="tab-content">
            <div class="section-title">Thông tin chi tiết</div>
            <div id="feature-details"><p>Nhấn vào một đối tượng trên bản đồ để xem thông tin.</p></div>
        </div>
        <div id="report-content" class="tab-content">
            <div class="section-title">Tham gia bản đồ số</div>
            <p style="font-size:13px; color:var(--text-light-color); margin-bottom:1rem;">
                Chọn loại thông tin bạn muốn báo cáo. Không cần đăng nhập.
            </p>

            <a href="<?= Url::to(['/quanly/diem-nhay-cam/create', 'ref' => 'map']) ?>" class="report-card">
                <i data-lucide="alert-triangle" class="report-icon"></i>
                <div>
                    <div class="report-card-title">Điểm phản ánh của dân</div>
                    <div class="report-card-desc">Báo cáo khu vực nhạy cảm cần chú ý</div>
                </div>
                <i data-lucide="chevron-right" style="margin-left:auto; flex-shrink:0;"></i>
            </a>

            <!-- QR Code nằm trong tab, tự động hiện -->
            <div style="margin-top:20px; border-top:1px solid var(--border-color); padding-top:16px; text-align:center;">
                <p style="font-size:13px; font-weight:600; margin:0 0 12px; color:var(--text-color);">
                    <i data-lucide="qr-code" style="width:15px;height:15px;vertical-align:middle;margin-right:5px;"></i>
                    Quét QR để truy cập bản đồ
                </p>
                <div id="qr-canvas" style="display:inline-block; padding:10px; background:#fff; border:1px solid var(--border-color); border-radius:10px; margin-bottom:12px;"></div>
                <p id="qr-url-text" style="font-size:11px; color:var(--text-light-color); word-break:break-all; margin:0 0 12px; padding:8px; background:var(--light-gray); border-radius:6px;"></p>
                <button onclick="App.UI.downloadQR()" style="
                    width:100%; padding:9px; background:var(--primary-color); color:white;
                    border:none; border-radius:8px; cursor:pointer; font-size:13px; font-weight:500;
                    display:flex; align-items:center; justify-content:center; gap:6px;">
                    <i data-lucide="download" style="width:15px;height:15px;"></i> Tải QR Code (PNG)
                </button>
            </div>
        </div>
    </div>

    <div id="mapTong">
        <div id="map"></div>
        <button id="toggle-tab-btn"></button>
        <div id="loading-overlay"><div class="spinner"></div></div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const App = {
        // --- CONFIGURATION ---
        WMS_URL: 'https://gis.cinotec.com.vn/geoserver/mohinhgis_vinhloi/wms',
        GEOJSON_VUVEC_URL: 'https://gis.cinotec.com.vn/geoserver/mohinhgis_vinhloi/ows?service=WFS&version=1.0.0&request=GetFeature&typeName=mohinhgis_vinhloi5%3Avu_viec&maxFeatures=5000&outputFormat=application%2Fjson',
        DETAIL_URLS: {
            vuViec: '<?= $vuViecDetailUrlBase ?>',
            diemNhayCam: '<?= $diemNhayCamDetailUrlBase ?>',
        },
        THEMATIC_META: <?= $thematicMetaJson ?>,
        KP_OPTIONS: <?= $kpOptionsJson ?>,
        FILES_API_URL: '<?= Yii::$app->urlManager->createUrl(["/quanly/map/get-files"]) ?>',
        THEMATIC_STATS_URL: '<?= Yii::$app->urlManager->createUrl(["/quanly/map/thematic-stats"]) ?>',
        MAP_CENTER: [9.990668, 105.754463],
        MAP_ZOOM: 14,
        
        map: null,
        leafletLayers: {},
        vuViecGeoJsonData: null,
        thematicFilterState: {
            layerKey: '',
            kpId: '',
            statusValues: [],
            typeValues: [],
        },
        
        init() {
            this.UI.init();
            this.Map.init();
            this.Layers.init();
            this.Events.init();
            lucide.createIcons();
        },

        // --- MODULE QUẢN LÝ BẢN ĐỒ ---
        Map: {
            init() {
                App.map = L.map('map', { zoomControl: false }).setView(App.MAP_CENTER, App.MAP_ZOOM);
                L.control.zoom({ position: 'topright' }).addTo(App.map);

                const baseMaps = {
                    "Bản đồ Google": L.tileLayer('https://{s}.google.com/vt/lyrs=r&x={x}&y={y}&z={z}', { maxZoom: 22, subdomains: ['mt0', 'mt1', 'mt2', 'mt3'], opacity: 0.7 }).addTo(App.map),
                    "Ảnh vệ tinh": L.tileLayer('https://{s}.google.com/vt/lyrs=s,h&x={x}&y={y}&z={z}', { maxZoom: 22, subdomains: ['mt0', 'mt1', 'mt2', 'mt3'], opacity: 0.7 })
                };

                App.map.createPane('highlightPane').style.zIndex = 700;
                App.leafletLayers.highlight = L.geoJSON(null, {
                    pane: 'highlightPane',
                    style: { color: '#ff0000', weight: 5, opacity: 1, fillOpacity: 0.3, dashArray: '5, 5' }
                }).addTo(App.map);

                L.control.layers(baseMaps, null, { position: 'topright' }).addTo(App.map);
                L.control.scale({ imperial: false }).addTo(App.map);
                new L.Control.Measure({ position: 'topright', primaryLengthUnit: 'meters', primaryAreaUnit: 'sqmeters' }).addTo(App.map);
                new L.Control.Locate({ position: 'topright', strings: { title: "Hiện vị trí" } }).addTo(App.map);
                
                const legendControl = L.control({ position: 'bottomright' });
                legendControl.onAdd = () => L.DomUtil.create('div', 'legend');
                legendControl.addTo(App.map);
                App.UI.legendContainer = legendControl.getContainer();

                const legendToggle = L.control({ position: 'bottomright' });
                legendToggle.onAdd = () => {
                    const button = L.DomUtil.create('button', 'leaflet-bar leaflet-control-layers');
                    button.innerHTML = '<i data-lucide="book-open" style="width:18px; height:18px; margin: 5px;"></i>';
                    button.style.cursor = 'pointer';
                    button.title = 'Hiện chú giải';
                    button.onclick = () => App.UI.toggleLegend();
                    return button;
                };
                legendToggle.addTo(App.map);
            }
        },

        // --- MODULE QUẢN LÝ LỚP DỮ LIỆU ---
        Layers: {
            registry: {},
            
            async init() {
                App.UI.setLoading(true);
                try {
                    await this.fetchVuViecData(); 
                    this.buildLayersFromDOM();
                    App.UI.buildLegend();
                } catch (error) {
                    console.error("Lỗi nghiêm trọng khi khởi tạo lớp dữ liệu:", error);
                    App.UI.showError("Đã xảy ra lỗi khi tải dữ liệu bản đồ. Vui lòng thử lại.");
                } finally {
                    App.UI.setLoading(false);
                }
            },

            buildLayersFromDOM() {
                document.querySelectorAll('#layer-control input[type="checkbox"]').forEach(el => {
                    const cfg = el.dataset;
                    const layerId = cfg.layerId;
                    if (!layerId) return;

                    const config = {
                        id: layerId,
                        type: cfg.layerType,
                        wmsName: cfg.wmsName,
                        displayName: cfg.displayName,
                        zIndex: parseInt(cfg.zIndex, 10) || 400,
                        popupFields: JSON.parse(cfg.popupFields || '{}'),
                        icon: cfg.icon,
                        defaultVisible: el.checked
                    };
                    App.Layers.registry[layerId] = config;
                    this.createLayer(config);
                });
            },
            
            async fetchVuViecData() {
                try {
                    const response = await fetch(App.GEOJSON_VUVEC_URL);
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    App.vuViecGeoJsonData = await response.json();
                } catch (error) {
                    console.error('Lỗi tải dữ liệu GeoJSON Vụ việc:', error);
                    throw error;
                }
            },
            
            createLayer(config) {
                let layer;
                switch(config.type) {
                    case 'wms':
                        App.map.createPane(config.id).style.zIndex = config.zIndex;
                        layer = L.tileLayer.wms(App.WMS_URL, {
                            layers: config.wmsName, format: 'image/png', transparent: true, maxZoom: 22, pane: config.id
                        });
                        break;
                    case 'heatmap':
                         if (App.vuViecGeoJsonData && App.vuViecGeoJsonData.features) {
                            const heatPoints = App.vuViecGeoJsonData.features
                                .filter(f => f.geometry && f.geometry.coordinates)
                                .map(f => [f.geometry.coordinates[1], f.geometry.coordinates[0]]);
                            layer = L.heatLayer(heatPoints, { radius: 25, blur: 15, maxZoom: 18 });
                        }
                        break;
                    case 'cluster':
                         if (App.vuViecGeoJsonData && App.vuViecGeoJsonData.features) {
                            layer = L.markerClusterGroup();
                            const validFeatures = App.vuViecGeoJsonData.features.filter(f => f.geometry && f.geometry.coordinates);
                            const geoJsonLayer = L.geoJSON({type: 'FeatureCollection', features: validFeatures}, {
                                onEachFeature: (feature, marker) => {
                                    marker.on('click', () => {
                                        App.UI.displayFeatureInfo(feature, config);
                                        App.UI.openTab('info');
                                        App.leafletLayers.highlight.clearLayers().addData(feature);
                                    });
                                }
                            });
                            layer.addLayer(geoJsonLayer);
                        }
                        break;
                }
                if (layer) {
                    App.leafletLayers[config.id] = layer;
                    if (config.defaultVisible) layer.addTo(App.map);
                }
            },

            toggle(layerId, visible) {
                const layer = App.leafletLayers[layerId];
                if (!layer) return;
                if (visible) App.map.addLayer(layer);
                else App.map.removeLayer(layer);
            },

            filterClusterLayer(searchText) {
                const clusterLayer = App.leafletLayers.clusterVuviecLayer;
                if (!clusterLayer || !App.vuViecGeoJsonData) return;
                
                clusterLayer.clearLayers();
                const filteredData = App.vuViecGeoJsonData.features.filter(feature => {
                    const props = feature.properties;
                    const content = `${props.ma_vu_viec || ''} ${props.tom_tat_noi_dung || ''}`.toLowerCase();
                    return content.includes(searchText);
                });

                const newGeoJsonLayer = L.geoJSON({ type: 'FeatureCollection', features: filteredData }, {
                    onEachFeature: (feature, marker) => {
                        marker.on('click', () => {
                            App.leafletLayers.highlight.clearLayers().addData(feature);
                        });
                    }
                });
                clusterLayer.addLayer(newGeoJsonLayer);
            },

            getThematicLayerId(layerKey) {
                const mapping = {
                    cayXanh: 'wmsCayXanhLayer',
                    chieuSang: 'wmsChieuSangLayer',
                    tramThoatNuoc: 'wmsTramThoatNuocLayer',
                    tuyenCongThoatNuoc: 'wmsTuyenCongThoatNuocLayer',
                    diemRacThai: 'wmsDiemRacThaiLayer'
                };
                return mapping[layerKey] || null;
            },

            getKpOption(kpId) {
                const numericId = parseInt(kpId, 10);
                if (!numericId) return null;
                return App.KP_OPTIONS.find(item => parseInt(item.id, 10) === numericId) || null;
            },

            escapeCqlValue(value) {
                return String(value).replace(/'/g, "''");
            },

            buildThematicCqlFilter(layerKey, statusValues, typeValues, kpId) {
                const meta = App.THEMATIC_META[layerKey];
                if (!meta) return '';

                const clauses = [];
                if (statusValues.length > 0) {
                    const statusClause = statusValues
                        .map(value => `${meta.statusField}='${this.escapeCqlValue(value)}'`)
                        .join(' OR ');
                    clauses.push(`(${statusClause})`);
                }
                if (typeValues.length > 0) {
                    const typeClause = typeValues
                        .map(value => `${meta.typeField}='${this.escapeCqlValue(value)}'`)
                        .join(' OR ');
                    clauses.push(`(${typeClause})`);
                }

                const kpOption = this.getKpOption(kpId);
                if (kpOption && kpOption.wkt) {
                    clauses.push(`INTERSECTS(geom,SRID=4326;${kpOption.wkt})`);
                }

                return clauses.join(' AND ');
            },

            showSelectedKpBoundary(kpId) {
                let layer = App.leafletLayers.selectedKpBoundary;
                if (!layer) {
                    if (!App.map.getPane('selectedKpPane')) {
                        App.map.createPane('selectedKpPane').style.zIndex = 650;
                    }
                    layer = App.leafletLayers.selectedKpBoundary = L.geoJSON(null, {
                        pane: 'selectedKpPane',
                        style: { color: '#f97316', weight: 3, opacity: 1, fillOpacity: 0.08, dashArray: '10, 6' }
                    }).addTo(App.map);
                }

                layer.clearLayers();
                const kpOption = this.getKpOption(kpId);
                if (!kpOption || !kpOption.geojson) return;

                layer.addData(kpOption.geojson);
                const bounds = layer.getBounds();
                if (bounds.isValid()) {
                    App.map.fitBounds(bounds, { padding: [20, 20] });
                }
            },

            async updateThematicStats(layerKey, kpId, statusValues, typeValues) {
                if (!layerKey) {
                    App.UI.resetThematicSummary();
                    return;
                }

                const params = new URLSearchParams();
                params.set('layerKey', layerKey);
                if (kpId) {
                    params.set('kpId', kpId);
                }
                statusValues.forEach(value => params.append('statusValues[]', value));
                typeValues.forEach(value => params.append('typeValues[]', value));

                const response = await fetch(`${App.THEMATIC_STATS_URL}?${params.toString()}`);
                const payload = await response.json();
                if (!payload.success) {
                    throw new Error(payload.message || 'Không thể thống kê dữ liệu theo khu phố.');
                }

                App.UI.updateThematicSummary(payload.data, kpId);
            },

            async applyThematicFilter(layerKey, statusValues, typeValues, kpId) {
                const layerId = this.getThematicLayerId(layerKey);
                if (!layerId || !App.leafletLayers[layerId]) return;

                App.thematicFilterState = { layerKey, kpId, statusValues, typeValues };
                App.UI.setLoading(true);
                try {
                    App.leafletLayers[layerId].setParams({
                        CQL_FILTER: this.buildThematicCqlFilter(layerKey, statusValues, typeValues, kpId)
                    }, false);

                    const input = document.querySelector(`#layer-control input[data-layer-id="${layerId}"]`);
                    if (input && !input.checked) {
                        input.checked = true;
                        App.Layers.toggle(layerId, true);
                    } else if (App.leafletLayers[layerId] && !App.map.hasLayer(App.leafletLayers[layerId])) {
                        App.Layers.toggle(layerId, true);
                    }

                    this.showSelectedKpBoundary(kpId);
                    await this.updateThematicStats(layerKey, kpId, statusValues, typeValues);
                } finally {
                    App.UI.setLoading(false);
                }
            },

            async clearThematicFilter(layerKey) {
                const layerId = this.getThematicLayerId(layerKey);
                if (!layerId || !App.leafletLayers[layerId]) {
                    App.thematicFilterState = {
                        layerKey: '',
                        kpId: '',
                        statusValues: [],
                        typeValues: [],
                    };
                    App.UI.resetThematicSummary();
                    return;
                }

                App.thematicFilterState = {
                    layerKey: '',
                    kpId: '',
                    statusValues: [],
                    typeValues: [],
                };

                App.leafletLayers[layerId].setParams({
                    CQL_FILTER: ''
                }, false);

                const input = document.querySelector(`#layer-control input[data-layer-id="${layerId}"]`);
                if (input) {
                    input.checked = false;
                }
                App.Layers.toggle(layerId, false);

                if (App.leafletLayers.selectedKpBoundary) {
                    App.leafletLayers.selectedKpBoundary.clearLayers();
                }

                App.UI.resetThematicSummary();
            }
        },
        
        // --- MODULE QUẢN LÝ GIAO DIỆN ---
        UI: {
            init() {
                this.fixMobileHeight();
                document.getElementById('toggle-tab-btn').innerHTML = `<i data-lucide="menu"></i>`;
                document.getElementById('back-to-map-mobile-btn').innerHTML = `<i data-lucide="x"></i>`;
                if (window.innerWidth <= 768) this.toggleTabPanel(false);
                this.renderQRCode();
            },
            fixMobileHeight: () => {
                const setAppHeight = () => document.documentElement.style.setProperty('--app-height', `${window.innerHeight}px`);
                window.addEventListener('resize', setAppHeight);
                window.addEventListener('orientationchange', setAppHeight);
                setAppHeight();
            },

            buildLegend() {
                if (!this.legendContainer) return;
                let legendHtml = '<h4>Chú giải</h4>';
                
                for (const layerId in App.Layers.registry) {
                    const config = App.Layers.registry[layerId];
                    if (config.type === 'wms') {
                        const legendUrl = `${App.WMS_URL}?REQUEST=GetLegendGraphic&VERSION=1.0.0&FORMAT=image/png&WIDTH=20&HEIGHT=20&LAYER=${config.wmsName}`;
                        legendHtml += `<div class="legend-item"><img src="${legendUrl}" alt="${config.displayName}"><span>${config.displayName}</span></div>`;
                    }
                }
                this.legendContainer.innerHTML = legendHtml;
            },
            
            displayFeatureInfo(feature, config) {
                const props = feature.properties;
                let content = `<div class='popup-content'><h4>${config.displayName}</h4><table>`;

                let fields = config.popupFields || {};
                if (config.type === 'cluster') {
                    fields = {'ma_vu_viec': 'Mã vụ việc', 'tom_tat_noi_dung': 'Tóm tắt nội dung', 'dia_chi_su_viec': 'Địa chỉ'};
                }

                for (const key in fields) {
                    if (props.hasOwnProperty(key)) {
                        content += `<tr><th>${fields[key]}</th><td>${props[key] || 'Không có'}</td></tr>`;
                    }
                }
                content += `</table>`;

                // --- Map layer ID → detail URL key + files API layer key ---
                const layerDetailMap = {
                    'wmsVuviecLayer':              { urlKey: 'vuViec',     filesKey: null },
                    'clusterVuviecLayer':           { urlKey: 'vuViec',     filesKey: null },
                    'wmsDiemnhaycamLayer':          { urlKey: 'diemNhayCam', filesKey: null },
                    'wmsDiemRacThaiLayer':          { urlKey: null,         filesKey: 'diemRacThai' },
                    'wmsCayXanhLayer':              { urlKey: null,         filesKey: 'cayXanh' },
                    'wmsChieuSangLayer':            { urlKey: null,         filesKey: 'chieuSang' },
                    'wmsTramThoatNuocLayer':        { urlKey: null,         filesKey: 'tramThoatNuoc' },
                    'wmsTuyenCongThoatNuocLayer':   { urlKey: null,         filesKey: 'tuyenCongThoatNuoc' },
                    'wmsDuongThuyLayer':            { urlKey: null,         filesKey: 'duongThuy' },
                    'wmsCameraAnNinhLayer':         { urlKey: null,         filesKey: 'cameraAnNinh' },
                };

                const featureId  = feature.id;
                const numericId  = featureId ? featureId.split('.').pop() : null;
                const layerCfg   = layerDetailMap[config.id] || {};
                const detailUrl  = (numericId && !isNaN(numericId) && layerCfg.urlKey)
                    ? `${App.DETAIL_URLS[layerCfg.urlKey]}?id=${numericId}` : '';
                const filesKey   = layerCfg.filesKey || null;

                // --- Nút Xem chi tiết ---
                if (detailUrl) {
                    content += `
                        <div style="margin-top:15px; text-align:right;">
                            <a href="${detailUrl}" target="_blank" class="detail-button">
                                <i data-lucide="external-link" class="icon"></i> Xem chi tiết
                            </a>
                        </div>`;
                }

                // --- Khu vực ảnh đính kèm (placeholder, sẽ được load async) ---
                if (filesKey && numericId && !isNaN(numericId)) {
                    content += `<div id="attachment-section" style="margin-top:14px;">
                        <div style="font-size:12px;color:var(--text-light-color);">
                            <i data-lucide="loader" style="width:14px;height:14px;animation:spin 1s linear infinite;vertical-align:middle;"></i>
                            Đang tải file đính kèm...
                        </div>
                    </div>`;
                }

                content += `</div>`;
                document.getElementById('feature-details').innerHTML = content;
                lucide.createIcons();

                // --- Fetch file đính kèm async ---
                if (filesKey && numericId && !isNaN(numericId)) {
                    fetch(`${App.FILES_API_URL}?layer=${filesKey}&id=${numericId}`)
                        .then(r => r.json())
                        .then(data => {
                            const section = document.getElementById('attachment-section');
                            if (!section) return;
                            if (!data.success || data.files.length === 0) {
                                section.innerHTML = `<p style="font-size:12px;color:var(--text-light-color);margin:0;">Không có file đính kèm.</p>`;
                                return;
                            }
                            let html = `<div style="font-size:12px;font-weight:600;color:var(--text-color);margin-bottom:8px;">
                                📎 File đính kèm (${data.files.length})
                            </div>`;

                            // Ảnh — hiển thị dạng lưới
                            const images = data.files.filter(f => f.isImage);
                            const others = data.files.filter(f => !f.isImage);

                            if (images.length > 0) {
                                html += `<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(80px,1fr));gap:6px;margin-bottom:8px;">`;
                                images.forEach(f => {
                                    html += `<a href="${f.url}" target="_blank" style="display:block;border-radius:6px;overflow:hidden;border:1px solid var(--border-color);">
                                        <img src="${f.url}" alt="${f.name}" 
                                             style="width:100%;height:70px;object-fit:cover;display:block;"
                                             onerror="this.parentElement.style.display='none'">
                                    </a>`;
                                });
                                html += `</div>`;
                            }

                            // File khác — danh sách link
                            if (others.length > 0) {
                                others.forEach(f => {
                                    html += `<a href="${f.url}" target="_blank" 
                                               style="display:flex;align-items:center;gap:6px;padding:6px 8px;
                                                      border:1px solid var(--border-color);border-radius:6px;
                                                      text-decoration:none;color:var(--text-color);font-size:12px;
                                                      margin-bottom:5px;transition:background 0.2s;"
                                               onmouseover="this.style.background='var(--light-gray)'"
                                               onmouseout="this.style.background=''"
                                            >
                                        <i data-lucide="file" style="width:14px;height:14px;flex-shrink:0;color:var(--primary-color);"></i>
                                        <span style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">${f.name}</span>
                                        <i data-lucide="download" style="width:12px;height:12px;margin-left:auto;flex-shrink:0;color:var(--text-light-color);"></i>
                                    </a>`;
                                });
                            }

                            section.innerHTML = html;
                            lucide.createIcons();
                        })
                        .catch(() => {
                            const section = document.getElementById('attachment-section');
                            if (section) section.innerHTML = `<p style="font-size:12px;color:#ef4444;margin:0;">Lỗi tải file đính kèm.</p>`;
                        });
                }
            },
            
            setLoading(isLoading) { 
                document.getElementById('loading-overlay').classList.toggle('hidden', !isLoading);
             },
            showError(message) { 
                alert(message);
             },
            openTab(tabName) { 
                document.querySelectorAll('.tab-content').forEach(tab => tab.classList.remove('active'));
                document.getElementById(tabName + '-content').classList.add('active');
                document.querySelectorAll('.tab-button').forEach(btn => btn.classList.remove('active'));
                document.querySelector(`.tab-button[data-tab='${tabName}']`).classList.add('active');
             },
            toggleTabPanel(forceShow) { 
                const tabs = document.getElementById('tabs');
                const isMobile = window.innerWidth <= 768;
                let show = (typeof forceShow === 'boolean') ? forceShow : (isMobile ? !tabs.classList.contains('active') : tabs.classList.contains('hidden'));
                
                tabs.classList.toggle(isMobile ? 'active' : 'hidden', isMobile ? show : !show);
                setTimeout(() => App.map.invalidateSize(), 300);
             },
            toggleLegend() {
                const legend = this.legendContainer;
                legend.style.display = (legend.style.display === 'none' || legend.style.display === '') ? 'block' : 'none';
            },

            renderQRCode() {
                const container = document.getElementById('qr-canvas');
                const urlText = document.getElementById('qr-url-text');
                if (!container) return;
                const publicUrl = '<?= Yii::$app->urlManager->createAbsoluteUrl(['/quanly/map/vuviec']) ?>';
                if (urlText) urlText.textContent = publicUrl;
                container.innerHTML = '';
                new QRCode(container, {
                    text: publicUrl,
                    width: 180,
                    height: 180,
                    colorDark: '#1e293b',
                    colorLight: '#ffffff',
                    correctLevel: QRCode.CorrectLevel.H
                });
                lucide.createIcons();
            },

            downloadQR() {
                const canvas = document.querySelector('#qr-canvas canvas');
                const img = document.querySelector('#qr-canvas img');
                let dataUrl;
                if (canvas) dataUrl = canvas.toDataURL('image/png');
                else if (img) dataUrl = img.src;
                else return;
                const a = document.createElement('a');
                a.href = dataUrl;
                a.download = 'qrcode-bandodso.png';
                a.click();
            },

            renderThematicFilterOptions(layerKey) {
                const statusList = document.getElementById('status-filter-list');
                const typeList = document.getElementById('type-filter-list');
                const meta = App.THEMATIC_META[layerKey];

                if (!meta) {
                    statusList.innerHTML = '<div style="font-size:12px; color:var(--text-light-color);">Chọn lớp để hiển thị tiêu chí lọc.</div>';
                    typeList.innerHTML = '<div style="font-size:12px; color:var(--text-light-color);">Chọn lớp để hiển thị tiêu chí lọc.</div>';
                    this.resetThematicSummary();
                    return;
                }

                statusList.innerHTML = meta.statuses.length
                    ? meta.statuses.map(item => `
                        <label class="filter-check-item">
                            <input type="checkbox" class="thematic-status-check" value="${item.value}">
                            <span>${item.value}</span>
                            <span style="margin-left:auto; color:var(--text-light-color);">${item.count}</span>
                        </label>
                    `).join('')
                    : '<div style="font-size:12px; color:var(--text-light-color);">Không có dữ liệu tình trạng.</div>';

                typeList.innerHTML = meta.types.length
                    ? meta.types.map(item => `
                        <label class="filter-check-item">
                            <input type="checkbox" class="thematic-type-check" value="${item.value}">
                            <span>${item.value}</span>
                            <span style="margin-left:auto; color:var(--text-light-color);">${item.count}</span>
                        </label>
                    `).join('')
                    : '<div style="font-size:12px; color:var(--text-light-color);">Không có dữ liệu phân loại.</div>';

                this.resetThematicSummary(meta.title);
            },

            updateThematicSummary(data, kpId) {
                const summary = document.getElementById('thematic-filter-summary');
                if (!summary) return;

                const kpOption = App.Layers.getKpOption(kpId);
                summary.querySelector('.filter-summary__value').textContent = Number(data.total || 0).toLocaleString('vi-VN');
                summary.querySelector('.filter-summary__meta').textContent = kpOption
                    ? `${data.title} trong ${kpOption.name}`
                    : `${data.title} trên toàn bộ khu vực hiển thị`;
            },

            resetThematicSummary(layerTitle = '') {
                const summary = document.getElementById('thematic-filter-summary');
                if (!summary) return;

                summary.querySelector('.filter-summary__value').textContent = '0';
                summary.querySelector('.filter-summary__meta').textContent = layerTitle
                    ? `Chọn khu phố rồi bấm lọc để thống kê ${layerTitle.toLowerCase()}.`
                    : 'Chọn lớp chuyên đề và bấm lọc để xem số lượng trong khu phố.';
            },
        },
        
        // --- MODULE QUẢN LÝ SỰ KIỆN ---
        Events: {
            init() {
                App.map.on('click', this.onMapClick);
                
                document.getElementById('layer-control').addEventListener('change', e => {
                    if (e.target.matches('input[type="checkbox"]')) {
                        App.Layers.toggle(e.target.dataset.layerId, e.target.checked);
                    }
                });
                document.getElementById('search-input').addEventListener('input', e => {
                    App.Layers.filterClusterLayer(e.target.value.toLowerCase());
                });
                document.getElementById('thematic-layer-select').addEventListener('change', async e => {
                    const previousLayerKey = App.thematicFilterState.layerKey;
                    const nextLayerKey = e.target.value;

                    if (previousLayerKey && previousLayerKey !== nextLayerKey) {
                        await App.Layers.clearThematicFilter(previousLayerKey);
                    }

                    App.thematicFilterState.layerKey = nextLayerKey;
                    App.thematicFilterState.kpId = '';
                    App.thematicFilterState.statusValues = [];
                    App.thematicFilterState.typeValues = [];

                    document.getElementById('kp-filter-select').value = '';
                    App.UI.renderThematicFilterOptions(nextLayerKey);
                });
                document.getElementById('kp-filter-select').addEventListener('change', e => {
                    App.thematicFilterState.kpId = e.target.value;
                });
                document.getElementById('apply-thematic-filter').addEventListener('click', async () => {
                    const layerKey = document.getElementById('thematic-layer-select').value;
                    if (!layerKey) {
                        alert('Vui lòng chọn lớp chuyên đề cần lọc.');
                        return;
                    }

                    const kpId = document.getElementById('kp-filter-select').value;
                    const statusValues = Array.from(document.querySelectorAll('.thematic-status-check:checked')).map(el => el.value);
                    const typeValues = Array.from(document.querySelectorAll('.thematic-type-check:checked')).map(el => el.value);
                    await App.Layers.applyThematicFilter(layerKey, statusValues, typeValues, kpId);
                });
                document.getElementById('clear-thematic-filter').addEventListener('click', async () => {
                    const layerKey = document.getElementById('thematic-layer-select').value;
                    if (layerKey) {
                        await App.Layers.clearThematicFilter(layerKey);
                    }
                    document.getElementById('thematic-layer-select').value = '';
                    document.getElementById('kp-filter-select').value = '';
                    document.querySelectorAll('.thematic-status-check, .thematic-type-check').forEach(el => {
                        el.checked = false;
                    });
                    App.UI.renderThematicFilterOptions('');
                });
                document.querySelector('.tab-buttons').addEventListener('click', e => {
                    if (e.target.matches('.tab-button')) App.UI.openTab(e.target.dataset.tab);
                });
                document.getElementById('toggle-tab-btn').addEventListener('click', () => App.UI.toggleTabPanel());
                document.getElementById('back-to-map-mobile-btn').addEventListener('click', () => App.UI.toggleTabPanel(false));
            },

            async onMapClick(e) {
                const point = App.map.latLngToContainerPoint(e.latlng, App.map.getZoom());
                const size = App.map.getSize();
                const bbox = App.map.getBounds().toBBoxString();

                const visibleWmsLayers = Object.keys(App.leafletLayers)
                    .filter(id => App.map.hasLayer(App.leafletLayers[id]) && App.Layers.registry[id] && App.Layers.registry[id].type === 'wms')
                    .map(id => App.Layers.registry[id])
                    .sort((a, b) => b.zIndex - a.zIndex);
                
                if (visibleWmsLayers.length === 0) return;

                document.getElementById('feature-details').innerHTML = '<p>Đang tải...</p>';
                App.leafletLayers.highlight.clearLayers();

                for (const config of visibleWmsLayers) {
                    const url = `${App.WMS_URL}?SERVICE=WMS&VERSION=1.1.1&REQUEST=GetFeatureInfo&LAYERS=${config.wmsName}&QUERY_LAYERS=${config.wmsName}&BBOX=${bbox}&FEATURE_COUNT=1&HEIGHT=${size.y}&WIDTH=${size.x}&INFO_FORMAT=application/json&SRS=EPSG:4326&X=${Math.round(point.x)}&Y=${Math.round(point.y)}`;
                    try {
                        const response = await fetch(url);
                        const data = await response.json();
                        if (data.features && data.features.length > 0) {
                            App.UI.displayFeatureInfo(data.features[0], config);
                            App.UI.openTab('info');
                            App.leafletLayers.highlight.addData(data.features[0]);
                            return;
                        }
                    } catch (error) {
                        console.error(`Lỗi GetFeatureInfo lớp ${config.displayName}:`, error);
                    }
                }
                document.getElementById('feature-details').innerHTML = '<p>Không tìm thấy thông tin tại vị trí này.</p>';
            }
        }
    };

    App.init();
});
</script>
