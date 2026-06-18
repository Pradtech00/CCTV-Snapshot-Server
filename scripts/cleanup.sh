#!/bin/bash

set -u 

CONFIG="/opt/cctv/config/camera.conf"

if  [ ! -f "$CONFIG" ]; then
   echo "camera.conf tidak ditemukan."
   exit 1
fi

source "$CONFIG"

IMAGE_DIR="/opt/cctv/images"
LOG_FILE="/opt/cctv/logs/cleanup.log"

mkdir -p /opt/cctv/logs

DELETED=$(find "$IMAGE_DIR" -type f -name "*.jpg" -mtime +"$RETENTION_DAYS" -print -delete | wc -l)
echo "[$(date '+%Y-%m-%d %H:%M:%S')] Cleanup selesai. File dihapus: $DELETED" >> "$LOG_FILE"

exit 0
