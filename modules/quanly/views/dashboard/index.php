<?php
/**
 * @var yii\web\View $this
 * @var array $summaryChartData
 * @var array $chartData
 * @var array $layerData
 */

use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;

$this->title = 'Bảng điều hành dịch vụ đô thị';
$this->params['breadcrumbs'][] = $this->title;

$summaryChartDataJson = Json::encode($summaryChartData);
$chartDataJson = Json::encode($chartData);
$layerDataJson = Json::encode($layerData);
$mapUrl = Url::to(['/quanly/map/vuviec']);
?>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<style>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

:root {
    --bg-base: #0b0f1a;
    --bg-panel: #111827;
    --bg-card: #161d2e;
    --bg-hover: #1e2a3d;
    --border: rgba(255,255,255,.07);
    --border-hi: rgba(255,255,255,.15);
    --txt-primary: #f0f4ff;
    --txt-muted: #8896b3;
    --txt-dim: #4a5568;
    --accent-blue: #3b82f6;
    --accent-cyan: #06b6d4;
    --accent-red: #ef4444;
    --accent-amber: #f59e0b;
    --accent-green: #22c55e;
    --radius-sm: 8px;
    --radius-md: 12px;
    --radius-lg: 18px;
    --font-ui: 'Be Vietnam Pro', sans-serif;
    --font-mono: 'JetBrains Mono', monospace;
    --shadow-card: 0 4px 24px rgba(0,0,0,.4);
}

.ud-dash {
    font-family: var(--font-ui);
    background: var(--bg-base);
    color: var(--txt-primary);
    min-height: 100vh;
    padding: 0 0 64px;
    position: relative;
    overflow-x: hidden;
}

.ud-dash::before {
    content: '';
    position: fixed;
    inset: 0;
    background:
        radial-gradient(ellipse 80% 40% at 10% 0%, rgba(59,130,246,.12) 0%, transparent 60%),
        radial-gradient(ellipse 50% 30% at 90% 100%, rgba(6,182,212,.08) 0%, transparent 60%);
    pointer-events: none;
    z-index: 0;
}

.ud-header {
    position: sticky;
    top: 0;
    z-index: 100;
    background: rgba(11,15,26,.85);
    backdrop-filter: blur(20px);
    border-bottom: 1px solid var(--border);
    padding: 14px 24px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    flex-wrap: wrap;
}

.ud-header-brand {
    display: flex;
    align-items: center;
    gap: 12px;
}

.ud-header-icon {
    width: 38px;
    height: 38px;
    background: linear-gradient(135deg, var(--accent-blue), var(--accent-cyan));
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    flex-shrink: 0;
}

.ud-header-title {
    font-size: clamp(13px, 2vw, 16px);
    font-weight: 700;
    letter-spacing: .04em;
    line-height: 1.2;
}

.ud-header-sub {
    font-size: 11px;
    color: var(--txt-muted);
    font-weight: 400;
    letter-spacing: .06em;
    text-transform: uppercase;
    margin-top: 2px;
}

.ud-header-actions {
    display: flex;
    gap: 10px;
    align-items: center;
    flex-wrap: wrap;
}

.btn-map {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 9px 18px;
    background: linear-gradient(135deg, var(--accent-blue), #2563eb);
    color: #fff;
    font-size: 13px;
    font-weight: 600;
    border-radius: var(--radius-sm);
    text-decoration: none;
    border: none;
    cursor: pointer;
    transition: opacity .2s, transform .15s;
    box-shadow: 0 4px 12px rgba(59,130,246,.35);
}

.btn-map:hover { opacity: .88; transform: translateY(-1px); }

.live-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    background: rgba(34,197,94,.1);
    border: 1px solid rgba(34,197,94,.25);
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    color: var(--accent-green);
    letter-spacing: .08em;
    text-transform: uppercase;
}

.live-dot {
    width: 7px;
    height: 7px;
    background: var(--accent-green);
    border-radius: 50%;
    animation: pulse-dot 1.5s ease-in-out infinite;
}

@keyframes pulse-dot {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: .4; transform: scale(.7); }
}

.ud-body {
    position: relative;
    z-index: 1;
    padding: 24px 20px;
    max-width: 1600px;
    margin: 0 auto;
}

.ud-section-title {
    font-size: 11px;
    font-weight: 700;
    letter-spacing: .12em;
    text-transform: uppercase;
    color: var(--txt-muted);
    margin-bottom: 14px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.ud-section-title::before {
    content: '';
    width: 3px;
    height: 14px;
    background: var(--accent-blue);
    border-radius: 3px;
    display: inline-block;
}

.ud-kpi-strip {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 12px;
    margin-bottom: 24px;
}

.kpi-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: var(--radius-md);
    padding: 18px 16px;
    position: relative;
    overflow: hidden;
    transition: border-color .2s, transform .2s;
}

.kpi-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 2px;
    background: var(--kpi-color, var(--accent-blue));
}

.kpi-card:hover {
    border-color: var(--border-hi);
    transform: translateY(-2px);
}

.kpi-icon {
    font-size: 20px;
    margin-bottom: 10px;
    display: block;
}

.kpi-value {
    font-size: 28px;
    font-weight: 800;
    font-family: var(--font-mono);
    color: var(--kpi-color, var(--txt-primary));
    line-height: 1;
    margin-bottom: 4px;
}

.kpi-label {
    font-size: 11px;
    font-weight: 600;
    color: var(--txt-muted);
    letter-spacing: .04em;
    text-transform: uppercase;
    line-height: 1.4;
}

.kpi-sub {
    margin-top: 8px;
    font-size: 11px;
    color: var(--txt-dim);
    display: flex;
    align-items: center;
    gap: 5px;
    flex-wrap: wrap;
}

.kpi-pill {
    display: inline-flex;
    align-items: center;
    gap: 3px;
    padding: 2px 7px;
    border-radius: 10px;
    font-size: 10px;
    font-weight: 700;
    background: rgba(239,68,68,.15);
    color: var(--accent-red);
}

.kpi-pill.green {
    background: rgba(34,197,94,.15);
    color: var(--accent-green);
}

.kpi-pill.amber {
    background: rgba(245,158,11,.15);
    color: var(--accent-amber);
}

.ud-grid-2 {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 16px;
}

.ud-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    overflow: hidden;
    display: flex;
    flex-direction: column;
    box-shadow: var(--shadow-card);
}

.ud-card-head {
    padding: 14px 18px;
    border-bottom: 1px solid var(--border);
    display: flex;
    align-items: center;
    gap: 10px;
    flex-shrink: 0;
}

.ud-card-head-icon {
    width: 28px;
    height: 28px;
    border-radius: 7px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    flex-shrink: 0;
}

.ud-card-title {
    font-size: 12px;
    font-weight: 700;
    letter-spacing: .07em;
    text-transform: uppercase;
    color: var(--txt-muted);
    flex: 1;
}

.ud-card-body {
    padding: 18px;
    flex: 1;
    position: relative;
}

.chart-wrap {
    position: relative;
    width: 100%;
    height: 360px;
}

.chart-wrap-sm {
    position: relative;
    width: 100%;
    height: 280px;
}

.donut-wrap {
    position: relative;
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.donut-center {
    position: absolute;
    text-align: center;
    pointer-events: none;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    line-height: 1;
}

.donut-total {
    font-size: 26px;
    font-weight: 800;
    font-family: var(--font-mono);
}

.donut-lbl {
    font-size: 10px;
    color: var(--txt-muted);
    letter-spacing: .06em;
    text-transform: uppercase;
    margin-top: 4px;
}

.bar-legend {
    display: flex;
    justify-content: center;
    gap: 20px;
    padding: 10px 18px 14px;
    border-top: 1px solid var(--border);
    flex-wrap: wrap;
}

.bar-legend-item {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    font-weight: 500;
    color: var(--txt-muted);
}

.legend-dot {
    width: 8px;
    height: 8px;
    border-radius: 2px;
}

.layer-sep {
    display: flex;
    align-items: center;
    gap: 14px;
    margin: 32px 0 16px;
}

.layer-sep-num {
    width: 28px;
    height: 28px;
    border-radius: 8px;
    background: var(--layer-bg, rgba(59,130,246,.15));
    border: 1px solid var(--layer-border, rgba(59,130,246,.3));
    color: var(--layer-color, var(--accent-blue));
    font-family: var(--font-mono);
    font-size: 12px;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.layer-sep-title {
    font-size: 15px;
    font-weight: 700;
    letter-spacing: .02em;
}

.layer-sep-line {
    flex: 1;
    height: 1px;
    background: var(--border);
}

.layer-sep-count {
    font-family: var(--font-mono);
    font-size: 11px;
    color: var(--txt-muted);
    background: var(--bg-hover);
    padding: 3px 10px;
    border-radius: 20px;
    border: 1px solid var(--border);
}

.layer-detail-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 14px;
}

.eval-bars {
    display: flex;
    flex-direction: column;
    gap: 10px;
    padding: 4px 0;
}

.eval-row {
    display: grid;
    grid-template-columns: 70px 1fr 42px;
    align-items: center;
    gap: 10px;
}

.eval-label {
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .06em;
}

.eval-bar-track {
    background: rgba(255,255,255,.06);
    border-radius: 4px;
    height: 8px;
    overflow: hidden;
}

.eval-bar-fill {
    height: 100%;
    border-radius: 4px;
    transition: width 1.2s cubic-bezier(.19,1,.22,1);
}

.eval-num {
    font-family: var(--font-mono);
    font-size: 13px;
    font-weight: 700;
    text-align: right;
}

.stat-mini-grid {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    gap: 1px;
    background: var(--border);
    border-radius: var(--radius-sm);
    overflow: hidden;
}

.stat-mini-cell {
    background: var(--bg-hover);
    padding: 14px 12px;
    text-align: center;
}

.stat-mini-val {
    font-size: 22px;
    font-weight: 800;
    font-family: var(--font-mono);
    line-height: 1;
    margin-bottom: 4px;
}

.stat-mini-lbl {
    font-size: 10px;
    font-weight: 600;
    letter-spacing: .08em;
    text-transform: uppercase;
    color: var(--txt-muted);
}

.ud-anim {
    opacity: 0;
    transform: translateY(14px);
    animation: fadeUp .45s cubic-bezier(.19,1,.22,1) forwards;
}

@keyframes fadeUp {
    to { opacity: 1; transform: translateY(0); }
}

.ud-anim:nth-child(1) { animation-delay: .05s; }
.ud-anim:nth-child(2) { animation-delay: .10s; }
.ud-anim:nth-child(3) { animation-delay: .15s; }
.ud-anim:nth-child(4) { animation-delay: .20s; }
.ud-anim:nth-child(5) { animation-delay: .25s; }
.ud-anim:nth-child(6) { animation-delay: .30s; }

@media (max-width: 1280px) {
    .ud-kpi-strip { grid-template-columns: repeat(3, 1fr); }
    .ud-grid-2 { grid-template-columns: 1fr 1fr; }
}

@media (max-width: 900px) {
    .ud-kpi-strip { grid-template-columns: repeat(2, 1fr); }
    .ud-grid-2 { grid-template-columns: 1fr; }
    .layer-detail-grid { grid-template-columns: 1fr; }
    .ud-body { padding: 16px 12px; }
    .chart-wrap { height: 280px; }
    .chart-wrap-sm { height: 220px; }
}

@media (max-width: 560px) {
    .ud-kpi-strip { grid-template-columns: 1fr 1fr; }
    .kpi-value { font-size: 22px; }
    .ud-header { padding: 10px 14px; }
}
</style>

<div class="ud-dash">
    <header class="ud-header">
        <div class="ud-header-brand">
            <div class="ud-header-icon">CT</div>
            <div>
                <div class="ud-header-title">Bảng Điều Hành Dịch Vụ Đô Thị</div>
                <div class="ud-header-sub">Phường Cái Răng · 5 lớp hạ tầng và môi trường đô thị</div>
            </div>
        </div>
        <div class="ud-header-actions">
            <div class="live-badge">
                <span class="live-dot"></span> Trực tuyến
            </div>
            <a href="<?= Html::encode($mapUrl) ?>" target="_blank" class="btn-map">Xem Bản Đồ</a>
        </div>
    </header>

    <main class="ud-body">
        <div class="ud-section-title">Tổng quan 5 lớp chuyên đề</div>
        <div class="ud-kpi-strip" id="kpiStrip">
            <?php
            $kpiDefs = [
                ['icon' => 'CX', 'label' => 'Cây xanh', 'color' => '#3b82f6', 'layer' => 0],
                ['icon' => 'CS', 'label' => 'Chiếu sáng', 'color' => '#06b6d4', 'layer' => 1],
                ['icon' => 'TN', 'label' => 'Trạm thoát nước', 'color' => '#8b5cf6', 'layer' => 2],
                ['icon' => 'TC', 'label' => 'Tuyến cống thoát nước', 'color' => '#14b8a6', 'layer' => 3],
                ['icon' => 'RT', 'label' => 'Điểm tập kết rác thải', 'color' => '#f97316', 'layer' => 4],
            ];
            foreach ($kpiDefs as $i => $k):
                $do = $chartData['do'][$i];
                $vang = $chartData['vang'][$i];
                $xanh = $chartData['xanh'][$i];
                $total = $do + $vang + $xanh;
            ?>
            <div class="kpi-card ud-anim" style="--kpi-color:<?= $k['color'] ?>">
                <span class="kpi-icon"><?= Html::encode($k['icon']) ?></span>
                <div class="kpi-value"><?= $total ?></div>
                <div class="kpi-label"><?= Html::encode($k['label']) ?></div>
                <div class="kpi-sub">
                    <?php if ($do > 0): ?><span class="kpi-pill">Đỏ <?= $do ?></span><?php endif; ?>
                    <?php if ($vang > 0): ?><span class="kpi-pill amber">Vàng <?= $vang ?></span><?php endif; ?>
                    <?php if ($xanh > 0): ?><span class="kpi-pill green">Xanh <?= $xanh ?></span><?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="ud-section-title" style="margin-top:28px">Biểu đồ tổng hợp</div>
        <div class="ud-grid-2">
            <div class="ud-card ud-anim">
                <div class="ud-card-head">
                    <div class="ud-card-head-icon" style="background:rgba(59,130,246,.15);">BD</div>
                    <span class="ud-card-title">Phân loại đánh giá theo lớp</span>
                </div>
                <div class="ud-card-body">
                    <div class="chart-wrap">
                        <canvas id="mainBarChart"></canvas>
                    </div>
                </div>
                <div class="bar-legend">
                    <span class="bar-legend-item"><span class="legend-dot" style="background:#ef4444"></span>Đỏ</span>
                    <span class="bar-legend-item"><span class="legend-dot" style="background:#f59e0b"></span>Vàng</span>
                    <span class="bar-legend-item"><span class="legend-dot" style="background:#22c55e"></span>Xanh</span>
                </div>
            </div>

            <div class="ud-card ud-anim">
                <div class="ud-card-head">
                    <div class="ud-card-head-icon" style="background:rgba(34,197,94,.12);">TH</div>
                    <span class="ud-card-title">Tổng hợp mức độ đánh giá</span>
                </div>
                <div class="ud-card-body">
                    <div class="chart-wrap">
                        <div class="donut-wrap">
                            <canvas id="mainDonutChart"></canvas>
                            <div class="donut-center">
                                <div class="donut-total" id="mainDonutTotal">-</div>
                                <div class="donut-lbl">Tổng bản ghi</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php
        $layerMeta = [
            'cayXanh' => ['num' => '01', 'icon' => 'CX', 'color' => '#3b82f6', 'title' => 'Cây xanh đô thị'],
            'chieuSang' => ['num' => '02', 'icon' => 'CS', 'color' => '#06b6d4', 'title' => 'Chiếu sáng công cộng'],
            'tramThoatNuoc' => ['num' => '03', 'icon' => 'TN', 'color' => '#8b5cf6', 'title' => 'Trạm thoát nước'],
            'tuyenCongThoatNuoc' => ['num' => '04', 'icon' => 'TC', 'color' => '#14b8a6', 'title' => 'Tuyến cống thoát nước'],
            'diemRacThai' => ['num' => '05', 'icon' => 'RT', 'color' => '#f97316', 'title' => 'Điểm tập kết rác thải'],
        ];

        foreach ($layerMeta as $key => $meta):
            $layer = $layerData[$key];
            $ldo = $layer['chart']['do'];
            $lvang = $layer['chart']['vang'];
            $lxanh = $layer['chart']['xanh'];
            $ltot = $ldo + $lvang + $lxanh;
            $maxV = max($ldo, $lvang, $lxanh, 1);
            $c = $meta['color'];
            $rgb = sscanf(ltrim($c, '#'), '%02x%02x%02x');
            $cBg = 'rgba(' . implode(',', $rgb) . ',.12)';
            $cBd = 'rgba(' . implode(',', $rgb) . ',.28)';
        ?>
        <div class="layer-sep">
            <div class="layer-sep-num" style="--layer-bg:<?= $cBg ?>;--layer-border:<?= $cBd ?>;--layer-color:<?= $c ?>"><?= $meta['num'] ?></div>
            <span class="layer-sep-title"><?= Html::encode($meta['icon']) ?> <?= Html::encode($meta['title']) ?></span>
            <div class="layer-sep-line"></div>
            <span class="layer-sep-count"><?= $ltot ?> bản ghi</span>
        </div>

        <div class="layer-detail-grid">
            <div class="ud-card">
                <div class="ud-card-head">
                    <div class="ud-card-head-icon" style="background:<?= $cBg ?>">BD</div>
                    <span class="ud-card-title">Biểu đồ phân loại</span>
                </div>
                <div class="ud-card-body">
                    <div class="chart-wrap-sm">
                        <canvas id="bar_<?= Html::encode($key) ?>"></canvas>
                    </div>
                </div>
                <div class="bar-legend">
                    <span class="bar-legend-item"><span class="legend-dot" style="background:#ef4444"></span>Đỏ</span>
                    <span class="bar-legend-item"><span class="legend-dot" style="background:#f59e0b"></span>Vàng</span>
                    <span class="bar-legend-item"><span class="legend-dot" style="background:#22c55e"></span>Xanh</span>
                </div>
            </div>

            <div class="ud-card">
                <div class="ud-card-head">
                    <div class="ud-card-head-icon" style="background:<?= $cBg ?>">DG</div>
                    <span class="ud-card-title">Mức độ đánh giá</span>
                </div>
                <div class="ud-card-body">
                    <div class="eval-bars" style="margin-bottom:20px">
                        <div class="eval-row">
                            <span class="eval-label" style="color:#ef4444">Đỏ</span>
                            <div class="eval-bar-track"><div class="eval-bar-fill" style="width:<?= $maxV ? round($ldo / $maxV * 100) : 0 ?>%;background:#ef4444"></div></div>
                            <span class="eval-num" style="color:#ef4444"><?= $ldo ?></span>
                        </div>
                        <div class="eval-row">
                            <span class="eval-label" style="color:#f59e0b">Vàng</span>
                            <div class="eval-bar-track"><div class="eval-bar-fill" style="width:<?= $maxV ? round($lvang / $maxV * 100) : 0 ?>%;background:#f59e0b"></div></div>
                            <span class="eval-num" style="color:#f59e0b"><?= $lvang ?></span>
                        </div>
                        <div class="eval-row">
                            <span class="eval-label" style="color:#22c55e">Xanh</span>
                            <div class="eval-bar-track"><div class="eval-bar-fill" style="width:<?= $maxV ? round($lxanh / $maxV * 100) : 0 ?>%;background:#22c55e"></div></div>
                            <span class="eval-num" style="color:#22c55e"><?= $lxanh ?></span>
                        </div>
                    </div>
                    <div class="stat-mini-grid">
                        <div class="stat-mini-cell">
                            <div class="stat-mini-val" style="color:#ef4444"><?= $ldo ?></div>
                            <div class="stat-mini-lbl">Nghiêm trọng</div>
                        </div>
                        <div class="stat-mini-cell">
                            <div class="stat-mini-val" style="color:#f59e0b"><?= $lvang ?></div>
                            <div class="stat-mini-lbl">Cần xử lý</div>
                        </div>
                        <div class="stat-mini-cell">
                            <div class="stat-mini-val" style="color:#22c55e"><?= $lxanh ?></div>
                            <div class="stat-mini-lbl">Ổn định</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </main>
</div>

<script>
(function () {
    const chartData = <?= $chartDataJson ?>;
    const summaryData = <?= $summaryChartDataJson ?>;
    const layerData = <?= $layerDataJson ?>;

    const RED = '#ef4444';
    const AMBER = '#f59e0b';
    const GREEN = '#22c55e';

    const ctx = id => document.getElementById(id)?.getContext('2d');
    const sum = arr => arr.reduce((a, b) => a + b, 0);

    function barOpts() {
        return {
            responsive: true,
            maintainAspectRatio: false,
            layout: {
                padding: {
                    top: 22
                }
            },
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(255,255,255,.05)' },
                    ticks: { color: '#8896b3', font: { size: 11 }, stepSize: 1 },
                    border: { color: 'rgba(255,255,255,.08)' }
                },
                x: {
                    grid: { display: false },
                    ticks: { color: '#8896b3', font: { size: 11, weight: '600' } },
                    border: { color: 'rgba(255,255,255,.08)' }
                }
            }
        };
    }

    const dataLabelPlugin = {
        id: 'ud_labels',
        afterDatasetsDraw(chart) {
            if (chart.config.type !== 'bar') {
                return;
            }
            const c = chart.ctx;
            c.save();
            c.font = 'bold 11px JetBrains Mono, monospace';
            c.fillStyle = '#f0f4ff';
            c.textAlign = 'center';
            c.textBaseline = 'bottom';
            chart.data.datasets.forEach((ds, i) => {
                const meta = chart.getDatasetMeta(i);
                meta.data.forEach((bar, j) => {
                    const v = ds.data[j];
                    if (v > 0) {
                        c.fillText(v, bar.x, Math.max(bar.y - 2, 14));
                    }
                });
            });
            c.restore();
        }
    };

    function makeDonut(canvasId, totalSelector, data, colors) {
        const c = ctx(canvasId);
        if (!c) {
            return;
        }
        const total = sum(data.data);
        const totalEl = document.querySelector(totalSelector);
        if (totalEl) {
            totalEl.textContent = total.toLocaleString('vi-VN');
        }

        new Chart(c, {
            type: 'doughnut',
            data: {
                labels: data.labels,
                datasets: [{
                    data: data.data,
                    backgroundColor: colors,
                    borderWidth: 0,
                    hoverBorderWidth: 3,
                    hoverBorderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '68%',
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom',
                        labels: {
                            color: '#8896b3',
                            boxWidth: 10,
                            usePointStyle: true,
                            padding: 14,
                            font: { size: 11 }
                        }
                    }
                },
                onHover(evt, elements) {
                    if (!totalEl) {
                        return;
                    }
                    if (elements.length > 0) {
                        totalEl.textContent = data.data[elements[0].index].toLocaleString('vi-VN');
                    } else {
                        totalEl.textContent = total.toLocaleString('vi-VN');
                    }
                }
            }
        });
    }

    const mainBar = ctx('mainBarChart');
    if (mainBar) {
        new Chart(mainBar, {
            type: 'bar',
            data: {
                labels: chartData.labels,
                datasets: [
                    { label: 'Đỏ', backgroundColor: RED, data: chartData.do, barPercentage: .75, categoryPercentage: .65, borderRadius: 4, borderSkipped: false },
                    { label: 'Vàng', backgroundColor: AMBER, data: chartData.vang, barPercentage: .75, categoryPercentage: .65, borderRadius: 4, borderSkipped: false },
                    { label: 'Xanh', backgroundColor: GREEN, data: chartData.xanh, barPercentage: .75, categoryPercentage: .65, borderRadius: 4, borderSkipped: false }
                ]
            },
            options: barOpts(),
            plugins: [dataLabelPlugin]
        });
    }

    makeDonut('mainDonutChart', '#mainDonutTotal', summaryData, [RED, AMBER, GREEN]);

    ['cayXanh', 'chieuSang', 'tramThoatNuoc', 'tuyenCongThoatNuoc', 'diemRacThai'].forEach((key) => {
        const lyr = layerData[key];
        const canvas = ctx(`bar_${key}`);
        if (!lyr || !canvas) {
            return;
        }

        new Chart(canvas, {
            type: 'bar',
            data: {
                labels: ['Đỏ', 'Vàng', 'Xanh'],
                datasets: [{
                    data: [lyr.chart.do, lyr.chart.vang, lyr.chart.xanh],
                    backgroundColor: [RED, AMBER, GREEN],
                    barPercentage: .6,
                    borderRadius: 5,
                    borderSkipped: false
                }]
            },
            options: barOpts(),
            plugins: [dataLabelPlugin]
        });
    });
})();
</script>
