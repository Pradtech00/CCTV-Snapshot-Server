<?php

header('Content-Type: application/json');

require_once "../includes/functions.php";

$config = readCameraConfig();

$latest = getLatestSnapshot();

$imageUrl = null;

$imageName = null;

if ($latest) {

    $imageUrl = str_replace("/opt/cctv/images", "/images", $latest);

    $imageName = basename($latest);

}

$data = [

    "camera" => $config['CAMERA_NAME'],

    "ip" => $config['CAMERA_IP'],

    "snapshot_today" => countTodaySnapshots(),

    "storage" => formatBytes(getStorageUsage()),

    "server_time" => date("Y-m-d H:i:s"),

    "image" => $imageUrl,

    "image_name" => $imageName,

    "activity" => getActivityLogs(4)

];

echo json_encode($data, JSON_PRETTY_PRINT);