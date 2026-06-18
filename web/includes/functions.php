<?php

require_once "config.php";

function readCameraConfig()
{
    $data = [];

    if (!file_exists(CONFIG_FILE))
        return $data;

    foreach (file(CONFIG_FILE) as $line)
    {
        $line = trim($line);

        if ($line == "" || str_starts_with($line, "#"))
            continue;

        if (strpos($line, "=") === false)
            continue;

        list($k, $v) = explode("=", $line, 2);

        $data[$k] = trim($v);
    }

    return $data;
}

function getLatestSnapshot()
{
    $files = [];

    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator(IMAGE_DIR)
    );

    foreach ($iterator as $file)
    {
        if ($file->isDir())
            continue;

        if (strtolower($file->getExtension()) != "jpg")
            continue;

        $files[$file->getMTime()] = $file->getPathname();
    }

    if (empty($files))
        return null;

    krsort($files);

    return reset($files);
}

function countTodaySnapshots()
{
    $dir = IMAGE_DIR . "/" . date("Y/m/d");

    if (!is_dir($dir)) {
        return 0;
    }

    $files = glob($dir . "/*.jpg");

    return count($files);
}

function getActivityLogs($limit = 10)
{
    $logs = [];

    $files = [
        "/opt/cctv/logs/snapshot.log",
        "/opt/cctv/logs/cleanup.log",
        "/opt/cctv/logs/healthcheck.log"
    ];

    foreach ($files as $file) {

        if (!file_exists($file))
            continue;

        $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            $logs[] = $line;
        }

    }

    rsort($logs);

    return array_slice($logs, 0, $limit);
}

function formatBytes($bytes)
{
    $units = ['B','KB','MB','GB','TB'];

    $i = 0;

    while ($bytes >= 1024 && $i < count($units)-1) {
        $bytes /= 1024;
        $i++;
    }

    return round($bytes,2).' '.$units[$i];
}

function getStorageUsage()
{
    $size = 0;

    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator(
            IMAGE_DIR,
            FilesystemIterator::SKIP_DOTS
        )
    );

    foreach ($iterator as $file) {

        if ($file->isFile()) {
            $size += $file->getSize();
        }

    }

    return $size;
}