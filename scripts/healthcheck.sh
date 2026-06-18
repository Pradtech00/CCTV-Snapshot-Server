#!/bin/bash

set -u

CONFIG="/opt/cctv/config/camera.conf"

if [ ! -f "$CONFIG" ]; then
    echo "camera.conf tidak ditemukan."
    exit 1
fi

source "$CONFIG"

LOG_FILE="/opt/cctv/logs/healthcheck.log"
TMP_FILE="/tmp/healthcheck.jpg"

NOW=$(date '+%Y-%m-%d %H:%M:%S')

STATUS="OK"
MESSAGE="Camera Normal"

# =====================================================
# 1. Ping Kamera
# =====================================================

if ! ping -c 1 -W 2 "$CAMERA_IP" >/dev/null 2>&1; then
    STATUS="ERROR"
    MESSAGE="Ping gagal"
fi

# =====================================================
# 2. Port HTTP
# =====================================================

if [ "$STATUS" = "OK" ]; then

    if ! nc -z -w2 "$CAMERA_IP" "$CAMERA_PORT" >/dev/null 2>&1; then
        STATUS="ERROR"
        MESSAGE="Port HTTP tidak dapat diakses"
    fi

fi

# =====================================================
# 3. Snapshot Test
# =====================================================

if [ "$STATUS" = "OK" ]; then

    curl \
        --silent \
        --show-error \
        --digest \
        --connect-timeout "$TIMEOUT" \
        -u "$CAMERA_USER:$CAMERA_PASS" \
        "http://$CAMERA_IP:$CAMERA_PORT/cgi-bin/snapshot.cgi" \
        -o "$TMP_FILE"

    if [ ! -f "$TMP_FILE" ]; then

        STATUS="ERROR"
        MESSAGE="Snapshot gagal"

    elif ! file "$TMP_FILE" | grep -q "JPEG"; then

        STATUS="ERROR"
        MESSAGE="Snapshot bukan JPEG"

    fi

fi

rm -f "$TMP_FILE"

echo "[$NOW] $STATUS | $CAMERA_NAME | $MESSAGE" >> "$LOG_FILE"

if [ "$STATUS" = "OK" ]; then
    exit 0
else
    exit 1
fi