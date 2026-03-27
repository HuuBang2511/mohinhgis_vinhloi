<?php
/**
 * @var yii\web\View $this
 * @var array $dashboardData
 */

use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;

$this->title = 'Bảng điều hành dịch vụ đô thị';
$this->params['breadcrumbs'][] = $this->title;

$dashboardDataJson = Json::encode($dashboardData);
$mapUrl = Url::to(['/quanly/map/vuviec']);
$recordsUrl = Url::to(['/quanly/dashboard/records']);

$layerMeta = isset($dashboardData['layers']) ? $dashboardData['layers'] : [];
?>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<style>
    :root {
        --dash-bg: #09111f;
        --dash-panel: #111c2f;
        --dash-panel-soft: #15233b;
        --dash-border: rgba(255, 255, 255, 0.08);
        --dash-text: #eaf2ff;
        --dash-muted: #95a7c4;
        --dash-accent: #4f8cff;
        --dash-shadow: 0 24px 60px rgba(0, 0, 0, 0.32);
        --dash-radius: 18px;
        --dash-radius-sm: 12px;
        --dash-font: 'Be Vietnam Pro', sans-serif;
        --dash-mono: 'JetBrains Mono', monospace;
    }

    .urban-dashboard {
        min-height: 100vh;
        background:
            radial-gradient(circle at top left, rgba(79, 140, 255, 0.14), transparent 32%),
            radial-gradient(circle at bottom right, rgba(14, 165, 233, 0.12), transparent 28%),
            var(--dash-bg);
        color: var(--dash-text);
        font-family: var(--dash-font);
        padding: 24px;
    }

    .urban-dashboard__shell {
        max-width: 1560px;
        margin: 0 auto;
    }

    .dash-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        margin-bottom: 24px;
        padding: 22px 24px;
        background: rgba(17, 28, 47, 0.82);
        border: 1px solid var(--dash-border);
        border-radius: var(--dash-radius);
        box-shadow: var(--dash-shadow);
        backdrop-filter: blur(18px);
    }

    .dash-title {
        font-size: 28px;
        font-weight: 800;
        margin: 0 0 6px;
    }

    .dash-subtitle {
        margin: 0;
        color: var(--dash-muted);
        font-size: 14px;
    }

    .dash-actions {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    .dash-chip {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 14px;
        border-radius: 999px;
        background: rgba(34, 197, 94, 0.12);
        color: #4ade80;
        border: 1px solid rgba(74, 222, 128, 0.25);
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }

    .dash-chip::before {
        content: '';
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: currentColor;
        box-shadow: 0 0 12px currentColor;
    }

    .dash-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        color: #fff;
        font-weight: 700;
        padding: 11px 18px;
        border-radius: 12px;
        background: linear-gradient(135deg, #2563eb, #0ea5e9);
        box-shadow: 0 16px 34px rgba(37, 99, 235, 0.28);
    }

    .dash-grid {
        display: grid;
        gap: 18px;
    }

    .dash-kpis {
        grid-template-columns: repeat(5, minmax(0, 1fr));
        margin-bottom: 18px;
    }

    .dash-card {
        background: rgba(17, 28, 47, 0.88);
        border: 1px solid var(--dash-border);
        border-radius: var(--dash-radius);
        box-shadow: var(--dash-shadow);
    }

    .dash-kpi {
        padding: 18px;
        display: flex;
        flex-direction: column;
        gap: 14px;
        min-height: 170px;
    }

    .dash-kpi__top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 12px;
    }

    .dash-kpi__icon {
        width: 44px;
        height: 44px;
        border-radius: 14px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-family: var(--dash-mono);
        font-weight: 700;
        font-size: 15px;
        color: #fff;
    }

    .dash-kpi__value {
        font-size: 34px;
        line-height: 1;
        font-family: var(--dash-mono);
        font-weight: 800;
    }

    .dash-kpi__label {
        margin: 0;
        font-size: 14px;
        font-weight: 700;
    }

    .dash-kpi__sub {
        margin: 0;
        color: var(--dash-muted);
        font-size: 12px;
    }

    .dash-kpi__badges {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 10px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 600;
        text-decoration: none;
        border: 1px solid transparent;
        color: var(--dash-text);
        background: rgba(255, 255, 255, 0.05);
    }

    .status-badge::before {
        content: '';
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: var(--badge-color, #64748b);
    }

    .dash-main {
        grid-template-columns: minmax(0, 2fr) minmax(360px, 1fr);
        margin-bottom: 22px;
    }

    .dash-card__head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        padding: 18px 20px 0;
    }

    .dash-card__title {
        margin: 0;
        font-size: 16px;
        font-weight: 700;
    }

    .dash-card__hint {
        margin: 4px 0 0;
        color: var(--dash-muted);
        font-size: 12px;
    }

    .dash-card__body {
        padding: 18px 20px 20px;
    }

    .chart-box {
        position: relative;
        min-height: 360px;
    }

    .chart-box--sm {
        min-height: 280px;
    }

    .donut-total {
        position: absolute;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        pointer-events: none;
    }

    .donut-total strong {
        font-size: 34px;
        font-family: var(--dash-mono);
        font-weight: 800;
    }

    .donut-total span {
        font-size: 12px;
        color: var(--dash-muted);
    }

    .layer-block {
        margin-bottom: 20px;
    }

    .layer-block__head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 14px;
        margin-bottom: 12px;
    }

    .layer-block__meta {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .layer-block__icon {
        width: 42px;
        height: 42px;
        border-radius: 14px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-family: var(--dash-mono);
        font-weight: 700;
        color: #fff;
    }

    .layer-block__title {
        margin: 0;
        font-size: 18px;
        font-weight: 800;
    }

    .layer-block__count {
        color: var(--dash-muted);
        font-size: 13px;
    }

    .layer-block__grid {
        display: grid;
        grid-template-columns: minmax(0, 1.1fr) minmax(0, 1fr);
        gap: 18px;
    }

    .type-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px;
    }

    .type-card {
        display: block;
        text-decoration: none;
        color: inherit;
        padding: 16px;
        background: rgba(255, 255, 255, 0.04);
        border: 1px solid rgba(255, 255, 255, 0.06);
        border-radius: 14px;
        min-height: 110px;
        width: 100%;
        text-align: left;
        cursor: pointer;
    }

    .type-card strong {
        display: block;
        font-size: 28px;
        line-height: 1;
        font-family: var(--dash-mono);
        margin-bottom: 8px;
    }

    .type-card span {
        display: block;
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 6px;
    }

    .type-card small {
        display: block;
        color: var(--dash-muted);
        font-size: 11px;
    }

    .type-card--empty {
        opacity: 0.7;
    }

    .dashboard-modal {
        position: fixed;
        inset: 0;
        background: rgba(9, 17, 31, 0.76);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 1200;
        padding: 20px;
    }

    .dashboard-modal.is-open {
        display: flex;
    }

    .dashboard-modal__panel {
        width: min(980px, 100%);
        max-height: min(82vh, 920px);
        overflow: hidden;
        background: #f8fbff;
        border: 1px solid rgba(15, 23, 42, 0.12);
        border-radius: 20px;
        box-shadow: 0 28px 90px rgba(0,0,0,.32);
        display: flex;
        flex-direction: column;
    }

    .dashboard-modal__head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        padding: 18px 20px;
        border-bottom: 1px solid rgba(15, 23, 42, 0.08);
        background: linear-gradient(180deg, #ffffff 0%, #f4f8ff 100%);
    }

    .dashboard-modal__title {
        margin: 0;
        font-size: 18px;
        font-weight: 800;
        color: #0f172a;
    }

    .dashboard-modal__sub {
        margin: 4px 0 0;
        color: #475569;
        font-size: 13px;
    }

    .dashboard-modal__close {
        width: 40px;
        height: 40px;
        border: 0;
        border-radius: 12px;
        background: #e2e8f0;
        color: #1e293b;
        cursor: pointer;
        font-size: 18px;
    }

    .dashboard-modal__body {
        padding: 18px 20px 20px;
        overflow: auto;
        background: #f8fbff;
    }

    .records-table {
        width: 100%;
        border-collapse: collapse;
        background: #ffffff;
        border: 1px solid #dbe6f3;
        border-radius: 14px;
        overflow: hidden;
    }

    .records-table th,
    .records-table td {
        padding: 12px 10px;
        text-align: left;
        border-bottom: 1px solid #e7eef7;
        font-size: 13px;
        color: #0f172a;
    }

    .records-table th {
        color: #475569;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: .06em;
        background: #eef4fb;
    }

    .records-table tr {
        cursor: pointer;
    }

    .records-table tbody tr:hover {
        background: #eaf3ff;
    }

    .records-table tbody tr:nth-child(even) {
        background: #f8fbff;
    }

    .records-empty {
        color: #475569;
        font-size: 14px;
        line-height: 1.6;
    }

    @media (max-width: 1280px) {
        .dash-kpis {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
    }

    @media (max-width: 980px) {
        .urban-dashboard {
            padding: 14px;
        }

        .dash-main,
        .layer-block__grid {
            grid-template-columns: 1fr;
        }

        .dash-kpis {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 640px) {
        .dash-header {
            padding: 16px;
        }

        .dash-title {
            font-size: 22px;
        }

        .dash-kpis,
        .type-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="urban-dashboard">
    <div class="urban-dashboard__shell">
        <header class="dash-header">
            <div>
                <h1 class="dash-title">Bảng điều hành 5 lớp chuyên đề đô thị</h1>
                <p class="dash-subtitle">Dashboard đã phân loại theo đúng giá trị tình trạng thực tế trong cơ sở dữ liệu, không còn gom chung theo Đỏ, Vàng, Xanh.</p>
            </div>
            <div class="dash-actions">
                <span class="dash-chip">Dữ liệu trực tuyến</span>
                <a class="dash-link" href="<?= Html::encode($mapUrl) ?>" target="_blank">Mở bản đồ chuyên đề</a>
            </div>
        </header>

        <section class="dash-grid dash-kpis">
            <?php foreach ($layerMeta as $layer): ?>
                <article class="dash-card dash-kpi">
                    <div class="dash-kpi__top">
                        <div class="dash-kpi__icon" style="background:<?= Html::encode($layer['color']) ?>;">
                            <?= Html::encode($layer['icon']) ?>
                        </div>
                        <div class="dash-kpi__value"><?= Html::encode($layer['total']) ?></div>
                    </div>
                    <div>
                        <p class="dash-kpi__label"><?= Html::encode($layer['title']) ?></p>
                        <p class="dash-kpi__sub">Tổng số đối tượng đang quản lý</p>
                    </div>
                            <div class="dash-kpi__badges">
                        <?php foreach ($layer['topStatuses'] as $status): ?>
                            <button
                                type="button"
                                class="status-badge"
                                style="--badge-color:<?= Html::encode($status['color']) ?>;"
                                data-record-trigger="1"
                                data-layer="<?= Html::encode($layer['key']) ?>"
                                data-status="<?= Html::encode($status['value']) ?>"
                                data-title="<?= Html::encode($layer['title']) ?>"
                            >
                                <?= Html::encode($status['value']) ?>: <?= Html::encode($status['count']) ?>
                            </button>
                        <?php endforeach; ?>
                    </div>
                </article>
            <?php endforeach; ?>
        </section>

        <section class="dash-grid dash-main">
            <article class="dash-card">
                <div class="dash-card__head">
                    <div>
                        <h2 class="dash-card__title">Thống kê theo lớp chuyên đề và tình trạng</h2>
                        <p class="dash-card__hint">Bấm vào từng cột trạng thái để chuyển sang bản đồ với đúng bộ lọc tương ứng.</p>
                    </div>
                </div>
                <div class="dash-card__body">
                    <div class="chart-box">
                        <canvas id="dashboardMainChart"></canvas>
                    </div>
                </div>
            </article>

            <article class="dash-card">
                <div class="dash-card__head">
                    <div>
                        <h2 class="dash-card__title">Tổng số đối tượng theo từng lớp</h2>
                        <p class="dash-card__hint">Bấm vào phần của biểu đồ để mở lớp tương ứng trên bản đồ.</p>
                    </div>
                </div>
                <div class="dash-card__body">
                    <div class="chart-box">
                        <canvas id="dashboardSummaryChart"></canvas>
                        <div class="donut-total">
                            <strong id="summaryTotal">0</strong>
                            <span>Tổng đối tượng</span>
                        </div>
                    </div>
                </div>
            </article>
        </section>

        <?php foreach ($layerMeta as $layer): ?>
            <section class="layer-block">
                <div class="layer-block__head">
                    <div class="layer-block__meta">
                        <div class="layer-block__icon" style="background:<?= Html::encode($layer['color']) ?>;">
                            <?= Html::encode($layer['icon']) ?>
                        </div>
                        <div>
                            <h3 class="layer-block__title"><?= Html::encode($layer['title']) ?></h3>
                            <div class="layer-block__count"><?= Html::encode($layer['total']) ?> đối tượng</div>
                        </div>
                    </div>
                </div>

                <div class="layer-block__grid">
                    <article class="dash-card">
                        <div class="dash-card__head">
                            <div>
                                <h4 class="dash-card__title">Phân loại theo <?= Html::encode(mb_strtolower($layer['typeLabel'], 'UTF-8')) ?></h4>
                                <p class="dash-card__hint">Bấm vào từng loại để mở bộ lọc tương ứng trên bản đồ.</p>
                            </div>
                        </div>
                        <div class="dash-card__body">
                            <div class="type-grid">
                                <?php if (!empty($layer['types'])): ?>
                                    <?php foreach ($layer['types'] as $type): ?>
                                        <button
                                            type="button"
                                            class="type-card"
                                            data-record-trigger="1"
                                            data-layer="<?= Html::encode($layer['key']) ?>"
                                            data-type="<?= Html::encode($type['value']) ?>"
                                            data-title="<?= Html::encode($layer['title']) ?>"
                                        >
                                            <strong><?= Html::encode($type['count']) ?></strong>
                                            <span><?= Html::encode($type['value']) ?></span>
                                            <small><?= Html::encode($layer['typeLabel']) ?></small>
                                        </button>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="type-card type-card--empty">
                                        <strong>0</strong>
                                        <span>Chưa có dữ liệu phân loại</span>
                                        <small><?= Html::encode($layer['typeLabel']) ?></small>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </article>

                    <article class="dash-card">
                        <div class="dash-card__head">
                            <div>
                                <h4 class="dash-card__title">Tình trạng chi tiết</h4>
                                <p class="dash-card__hint">Bấm vào từng cột để xem trực tiếp các đối tượng trên bản đồ.</p>
                            </div>
                        </div>
                        <div class="dash-card__body">
                            <div class="chart-box chart-box--sm">
                                <canvas id="statusChart_<?= Html::encode($layer['key']) ?>"></canvas>
                            </div>
                        </div>
                    </article>
                </div>
            </section>
        <?php endforeach; ?>
    </div>
</div>

<div id="recordsModal" class="dashboard-modal" aria-hidden="true">
    <div class="dashboard-modal__panel">
        <div class="dashboard-modal__head">
            <div>
                <h3 id="recordsModalTitle" class="dashboard-modal__title">Danh sách đối tượng</h3>
                <p id="recordsModalSub" class="dashboard-modal__sub">Chọn một dòng để vào trang chi tiết của đối tượng.</p>
            </div>
            <button type="button" id="recordsModalClose" class="dashboard-modal__close" aria-label="Đóng">×</button>
        </div>
        <div id="recordsModalBody" class="dashboard-modal__body">
            <p class="records-empty">Đang tải dữ liệu...</p>
        </div>
    </div>
</div>

<script>
(() => {
    const dashboardData = <?= $dashboardDataJson ?>;
    const mapUrl = <?= Json::encode($mapUrl) ?>;
    const recordsUrl = <?= Json::encode($recordsUrl) ?>;
    const ctx = (id) => document.getElementById(id)?.getContext('2d');
    const sum = (values) => values.reduce((total, value) => total + Number(value || 0), 0);
    const recordsModal = document.getElementById('recordsModal');
    const recordsModalTitle = document.getElementById('recordsModalTitle');
    const recordsModalSub = document.getElementById('recordsModalSub');
    const recordsModalBody = document.getElementById('recordsModalBody');

    function openModal(params) {
        const url = new URL(recordsUrl, window.location.origin);
        Object.entries(params).forEach(([key, value]) => {
            if (value !== undefined && value !== null && value !== '') {
                url.searchParams.set(key, value);
            }
        });

        recordsModal.classList.add('is-open');
        recordsModal.setAttribute('aria-hidden', 'false');
        recordsModalTitle.textContent = params.modalTitle || 'Danh sách đối tượng';
        recordsModalSub.textContent = 'Chọn một dòng để vào trang chi tiết của đối tượng.';
        recordsModalBody.innerHTML = '<p class="records-empty">Đang tải dữ liệu...</p>';

        fetch(url.toString())
            .then((response) => response.json())
            .then((payload) => {
                const parts = [];
                if (payload.status) {
                    parts.push(`Tình trạng: ${payload.status}`);
                }
                if (payload.type) {
                    parts.push(`Phân loại: ${payload.type}`);
                }
                recordsModalSub.textContent = parts.length ? parts.join(' · ') : 'Chọn một dòng để vào trang chi tiết của đối tượng.';

                if (!payload.records || !payload.records.length) {
                    recordsModalBody.innerHTML = '<p class="records-empty">Không có bản ghi phù hợp với điều kiện đã chọn.</p>';
                    return;
                }

                recordsModalBody.innerHTML = `
                    <table class="records-table">
                        <thead>
                            <tr>
                                <th>Mã</th>
                                <th>Tên</th>
                                <th>Tình trạng</th>
                                <th>Phân loại</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${payload.records.map((record) => `
                                <tr data-record-url="${record.url}">
                                    <td>${escapeHtml(record.code || '')}</td>
                                    <td>${escapeHtml(record.name || '')}</td>
                                    <td>${escapeHtml(record.status || '')}</td>
                                    <td>${escapeHtml(record.type || '')}</td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                `;
            })
            .catch(() => {
                recordsModalBody.innerHTML = '<p class="records-empty">Không tải được danh sách dữ liệu.</p>';
            });
    }

    function closeModal() {
        recordsModal.classList.remove('is-open');
        recordsModal.setAttribute('aria-hidden', 'true');
    }

    function escapeHtml(value) {
        return String(value ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function buildBarOptions(onClick) {
        return {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'nearest',
                intersect: true
            },
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        color: '#95a7c4',
                        padding: 16,
                        boxWidth: 12,
                        usePointStyle: true
                    }
                }
            },
            scales: {
                x: {
                    stacked: true,
                    ticks: {
                        color: '#c8d5ea',
                        font: {
                            size: 12,
                            weight: '600'
                        }
                    },
                    grid: {
                        display: false
                    },
                    border: {
                        color: 'rgba(255,255,255,.08)'
                    }
                },
                y: {
                    stacked: true,
                    beginAtZero: true,
                    ticks: {
                        color: '#95a7c4',
                        precision: 0
                    },
                    grid: {
                        color: 'rgba(255,255,255,.06)'
                    },
                    border: {
                        color: 'rgba(255,255,255,.08)'
                    }
                }
            },
            onClick
        };
    }

    function buildSimpleBarOptions(onClick) {
        return {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                x: {
                    ticks: {
                        color: '#c8d5ea',
                        font: {
                            size: 12,
                            weight: '600'
                        }
                    },
                    grid: {
                        display: false
                    },
                    border: {
                        color: 'rgba(255,255,255,.08)'
                    }
                },
                y: {
                    beginAtZero: true,
                    ticks: {
                        color: '#95a7c4',
                        precision: 0
                    },
                    grid: {
                        color: 'rgba(255,255,255,.06)'
                    },
                    border: {
                        color: 'rgba(255,255,255,.08)'
                    }
                }
            },
            onClick
        };
    }

    const dataLabelPlugin = {
        id: 'dataLabelPlugin',
        afterDatasetsDraw(chart) {
            if (chart.config.type !== 'bar') {
                return;
            }

            const chartContext = chart.ctx;
            chartContext.save();
            chartContext.font = '700 11px JetBrains Mono, monospace';
            chartContext.fillStyle = '#f8fbff';
            chartContext.textAlign = 'center';
            chartContext.textBaseline = 'bottom';

            chart.data.datasets.forEach((dataset, datasetIndex) => {
                const meta = chart.getDatasetMeta(datasetIndex);
                const realData = dataset._realData || dataset.data;
                meta.data.forEach((element, index) => {
                    const realValue = Number(realData[index] || 0);
                    if (realValue > 0) {
                        chartContext.fillText(String(realValue), element.x, Math.max(element.y - 4, 18));
                    }
                });
            });

            chartContext.restore();
        }
    };

    const summaryTotal = document.getElementById('summaryTotal');
    if (summaryTotal && dashboardData.summaryChart) {
        summaryTotal.textContent = sum(dashboardData.summaryChart.data).toLocaleString('vi-VN');
    }

    const mainChartContext = ctx('dashboardMainChart');
    if (mainChartContext && dashboardData.mainChart) {
        // Calculate max stacked total to determine a minimum visible threshold
        const layerCount = dashboardData.mainChart.labels.length;
        const layerTotals = [];
        for (let i = 0; i < layerCount; i++) {
            let total = 0;
            dashboardData.mainChart.datasets.forEach(ds => { total += (ds.data[i] || 0); });
            layerTotals.push(total);
        }
        const maxTotal = Math.max(...layerTotals, 1);
        // Minimum visible value: 2.5% of maxTotal so small bars are visible on chart
        const minVisible = Math.ceil(maxTotal * 0.025);

        const processedDatasets = dashboardData.mainChart.datasets.map((dataset) => {
            const realData = dataset.data.slice();
            // Boost non-zero values that are too small, keep zero values at zero
            const displayData = realData.map(v => (v > 0 && v < minVisible) ? minVisible : v);
            return {
                ...dataset,
                data: displayData,
                _realData: realData,
                borderRadius: 0,
                borderSkipped: false,
                barPercentage: 0.72,
                categoryPercentage: 0.68
            };
        });

        // Override tooltip to show real values
        const mainBarOptions = buildBarOptions((event, elements, chart) => {
            if (!elements.length) {
                return;
            }

            const point = elements[0];
            const layerKey = dashboardData.mainChart.keys[point.index];
            const statusLabel = chart.data.datasets[point.datasetIndex].label;
            const layer = dashboardData.layers[layerKey];
            openModal({ layer: layerKey, status: statusLabel, modalTitle: layer ? layer.title : 'Danh sách đối tượng' });
        });

        // Patch tooltip to show real value instead of boosted display value
        mainBarOptions.plugins = mainBarOptions.plugins || {};
        mainBarOptions.plugins.tooltip = {
            callbacks: {
                label: function(context) {
                    const ds = context.dataset;
                    const realData = ds._realData || ds.data;
                    const realValue = realData[context.dataIndex] || 0;
                    return ds.label + ': ' + realValue;
                }
            }
        };

        new Chart(mainChartContext, {
            type: 'bar',
            data: {
                labels: dashboardData.mainChart.labels,
                datasets: processedDatasets
            },
            options: mainBarOptions,
            plugins: [dataLabelPlugin]
        });
    }

    const summaryChartContext = ctx('dashboardSummaryChart');
    if (summaryChartContext && dashboardData.summaryChart) {
        new Chart(summaryChartContext, {
            type: 'doughnut',
            data: {
                labels: dashboardData.summaryChart.labels,
                datasets: [{
                    data: dashboardData.summaryChart.data,
                    backgroundColor: dashboardData.summaryChart.colors,
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: '#95a7c4',
                            padding: 16,
                            boxWidth: 12,
                            usePointStyle: true
                        }
                    }
                },
                onClick(event, elements, chart) {
                    if (!elements.length) {
                        return;
                    }
                    const point = elements[0];
                    const layerKey = Object.keys(dashboardData.layers)[point.index];
                    const layer = dashboardData.layers[layerKey];
                    openModal({ layer: layerKey, modalTitle: layer ? layer.title : 'Danh sách đối tượng' });
                }
            }
        });
    }

    Object.values(dashboardData.layers || {}).forEach((layer) => {
        const chartContext = ctx(`statusChart_${layer.key}`);
        if (!chartContext) {
            return;
        }

        new Chart(chartContext, {
            type: 'bar',
            data: {
                labels: layer.statuses.map((status) => status.value),
                datasets: [{
                    data: layer.statuses.map((status) => status.count),
                    backgroundColor: layer.statuses.map((status) => status.color),
                    borderColor: layer.statuses.map((status) => status.color),
                    borderRadius: 8,
                    borderSkipped: false
                }]
            },
            options: buildSimpleBarOptions((event, elements, chart) => {
                if (!elements.length) {
                    return;
                }

                const point = elements[0];
                const statusLabel = chart.data.labels[point.index];
                openModal({ layer: layer.key, status: statusLabel, modalTitle: layer.title });
            }),
            plugins: [dataLabelPlugin]
        });
    });

    document.querySelectorAll('[data-record-trigger="1"]').forEach((element) => {
        element.addEventListener('click', () => {
            openModal({
                layer: element.dataset.layer,
                status: element.dataset.status || '',
                type: element.dataset.type || '',
                modalTitle: element.dataset.title || 'Danh sách đối tượng'
            });
        });
    });

    document.getElementById('recordsModalClose').addEventListener('click', closeModal);
    recordsModal.addEventListener('click', (event) => {
        if (event.target === recordsModal) {
            closeModal();
        }
    });
    recordsModalBody.addEventListener('click', (event) => {
        const row = event.target.closest('tr[data-record-url]');
        if (!row) {
            return;
        }
        window.location.href = row.dataset.recordUrl;
    });
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && recordsModal.classList.contains('is-open')) {
            closeModal();
        }
    });
})();
</script>
