find /var/www/html/upload -type f | grep '.jpg\|.jpeg\|.JPG\|.JPEG' | while read i; do convert -strip -quality 85 "$i" "$i";done
find /var/www/html/upload -type f | grep '.png\|.PNG' | while read i; do convert  "$i" -strip -depth 8 -define png:compression-level=7  "$i";done
