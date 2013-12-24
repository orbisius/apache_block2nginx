<?php

/**
 * This parses an htaccess file with definitions to block user IP addresses.
 * The script loads it and converts into nginx rules
 *
 * Usage:
 * Set the .txt file that contains the IP list in $file variable.
 * from the command prompt
 * php apache_block2nginx.php > block_ips_nginx.txt
 * copy the contents of nginx.txt and append them into /etc/nginx/conf.d/block_ips.conf
 *
 * Test config: nginx -t
 *
 * Reload config nginx -s reload
 *
 * @author Slavi Marinov | orbisius.com
 * @see http://www.wizcrafts.net/chinese-blocklist.html
 */

$file = 'block_ips.txt';

//$buff = file_get_contents($file);
$buff = file_get_contents($file);

$lines = preg_split('#[\r\n]+#si', $buff);
$new_lines = array();

foreach ($lines as $line) {
    // Skip htaccess <files or order classes
    if (preg_match('#\</?Files|order\s+deny\s*,\s*allow|order\s+allow\s*,\s*deny#si', $line, $matches)) {
        continue;
    }

    // goal: deny from 192.83.122.0/24 192.124.154.0/24 192.188.170.0/24
    // to deny 192.83.122.0/24;
    // to deny 192.124.154.0/24;
    // etc.
	if (preg_match('#^\s*(allow|deny)\s+from\s+(.+)#si', $line, $matches)) {
		$cmd = $matches[1];
		$ips_list = $matches[2];
		
		$ips = preg_split('#\s+#si', $ips_list);	
		
		foreach ($ips as $ip) {
            $new_lines[] = $cmd . ' ' . $ip . ';';
		}
	} else { // don't process the line if we don't understand it.
        $new_lines[] = "\n";
        $new_lines[] = $line;
    }
}

echo join("\n", $new_lines);
