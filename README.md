apache_block2nginx
==================

This parses an htaccess file with definitions to block user IP addresses. The script loads it and converts into nginx rules

Usage
------

Download block rules from http://www.wizcrafts.net/chinese-blocklist.html and save them in a text file called *block_ips.txt*

Start a common prompt and type

`php apache_block2nginx.php > nginx_block_ips.txt`

Copy the contents of nginx_block_ips.txt and create/append them to /etc/nginx/conf.d/block_ips.conf

Test config: nginx -t

Reload config nginx -s reload

Slavi Marinov
orbisius.com
