# Download-feed-on-google-shopping-for-Opencart-2.X
One file, dont rewrite file system. 

File copy to catalog/controller/product folder
Create cron task curl -s https://yoursite.domain/index.php?route=product/export > /dev/null
Download feed link https://yoursite.domain/feed.xml
