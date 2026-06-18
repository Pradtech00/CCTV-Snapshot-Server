#!/bin/bash

set -u 

LOCK_FILE="/tmp/cctv_snapshot.lock"

exec 200>"$LOCK_FILE"

if ! flock -n 200; then
   echo "[$(date '+%F %T')] INFO | Snapshot dilewati karena proses sebelumnya masih aktif." >> /opt/cctv/logs/snapshot.log
   exit 0
fi


CONFIG="/opt/cctv/config/camera.conf"

if [ ! -f "$CONFIG" ]; then
echo "camera.conf tidak ditemukan."
exit 1
fi

source "$CONFIG"
DATE_PATH=$(date +%Y/%m/%d)
TIME_NAME=$(date +%H%M%S)
NOW=$(date '+%Y-%m-%d %H:%M:%S')

SAVE_DIR="/opt/cctv/images/$DATE_PATH"
SAVE_FILE="$SAVE_DIR/$TIME_NAME.jpg"

LOG_DIR="/opt/cctv/logs"
LOG_FILE="$LOG_DIR/snapshot.log"

mkdir -p "$SAVE_DIR"
mkdir -p "$LOG_DIR"

SUCCESS=0


for ((i=1; i<=RETRY; i++))
do

curl --silent --show-error --digest --connect-timeout "$TIMEOUT" -u "$CAMERA_USER:$CAMERA_PASS" "http://$CAMERA_IP:$CAMERA_PORT/cgi-bin/snapshot.cgi" -o "$SAVE_FILE"

if [ $? -eq 0 ]; then
 if file "$SAVE_FILE" | grep -q "JPEG";then
 SIZE=$(stat -c%s "$SAVE_FILE")
 if [ "$SIZE" -ge "$IMAGE_QUALITY_MIN_SIZE" ]; then
  SUCCESS=1
  break
 fi
 fi
fi

sleep 2

done

if [ "$SUCCESS" -eq 1 ]; then
  SIZE_HUMAN=$(du -h "$SAVE_FILE" | cut -f1)
  echo "[$NOW] SUCCESS |$CAMERA_NAME | $TIME_NAME.jpg | $SIZE_HUMAN" >> "$LOG_FILE"
  exit 0
else
  rm -f "$SAVE_FILE"
  echo "[$NOW] FAILED | $CAMERA_NAME | Gagal mengambil snapshot setelah $RETRY percobaan." >> "$LOG_FILE"
  exit 1
fi
