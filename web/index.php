<?php

require_once "includes/functions.php";

$config = readCameraConfig();

$latest = getLatestSnapshot();

$todaySnapshots = countTodaySnapshots();

$activities = getActivityLogs(4);

$storage = getStorageUsage();

$imageUrl = "";

$imageName = "";

$imageSize = "";

$imageTime = "";

if ($latest) {

    $imageUrl = str_replace("/opt/cctv/images", "/images", $latest);

    $imageName = basename($latest);

    $imageSize = formatBytes(filesize($latest));

    $imageTime = date("d-m-Y H:i:s", filemtime($latest));

}

$maxStorage = 5 * 1024 * 1024 * 1024; // 5GB

$percent = min(100, ($storage / $maxStorage) * 100);

?>

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>CCTV Snapshot Server</title>

<link rel="stylesheet" href="assets/css/style.css">

</head>

<body>

<div class="container">

<!-- ================= HEADER ================= -->

<div class="header">

    <div>

        <h1>CCTV Snapshot Server</h1>

        <div id="server-date">

            <?= date("l, d F Y") ?>

        </div>

    </div>

    <div style="text-align:right;">

        <div id="server-time"
             data-time="<?= date('Y-m-d H:i:s') ?>">

            <?= date("H:i:s") ?>

        </div>

        <div class="status-online">

            <span class="dot"></span>

            ONLINE

        </div>

    </div>

</div>

<!-- ================= CARD ================= -->

<div class="cards">

    <div class="card">

        <div class="card-title">

            Camera

        </div>

        <div class="card-value">

            <span id="camera-name">

                <?= htmlspecialchars($config['CAMERA_NAME']) ?>

            </span>

        </div>

    </div>

    <div class="card">

        <div class="card-title">

            IP Address

        </div>

        <div class="card-value">

            <span id="camera-ip">

                <?= htmlspecialchars($config['CAMERA_IP']) ?>

            </span>

        </div>

    </div>

    <div class="card">

        <div class="card-title">

            Snapshot Hari Ini

        </div>

        <div class="card-value">

            <span id="snapshot-count">

                <?= $todaySnapshots ?>

            </span>

        </div>

    </div>

    <div class="card">

        <div class="card-title">

            Storage

        </div>

        <div class="card-value">

            <span id="storage-size">

                <?= formatBytes($storage) ?>

            </span>

        </div>

        <div class="progress">

            <div class="progress-bar"
                 id="storage-bar"
                 style="width:<?= $percent ?>%">

            </div>

        </div>

    </div>

</div>

<!-- ================= CONTENT ================= -->

<div class="content">

    <!-- Preview -->

    <div class="preview">

        <h2>Snapshot Terakhir</h2>

        <?php if($latest): ?>

        <img
            id="snapshot-image"
            src="<?= htmlspecialchars($imageUrl) ?>"
            alt="Snapshot">

        <div class="snapshot-info">

            <div id="snapshot-name">

                <?= $imageName ?>

            </div>

            <div>

                <?= $imageSize ?>

            </div>

            <div>

                <?= $imageTime ?>

            </div>

        </div>

        <?php else: ?>

            <p>Belum ada snapshot.</p>

        <?php endif; ?>

    </div>

    <!-- Activity -->

    <div class="logs">

        <h2>Activity Log</h2>

<pre id="activity-log"><?php

foreach($activities as $log){

    echo htmlspecialchars($log).PHP_EOL;

}

?></pre>

        <div class="refresh-info">

            Auto Refresh : 5 detik

        </div>

    </div>

</div>

</div>

<script src="assets/js/app.js"></script>

</body>

</html>