<?php
include_once 'cors.php';
header('Content-Type: application/json');

// Path to dhcp leases file
$leasesFile = '/var/dhcpd/var/db/dhcpd.leases';

// Check if file exists
if (!file_exists($leasesFile)) {
    echo json_encode(['error' => 'DHCP lease file not found', 'path' => $leasesFile]);
    exit;
}

$leases = [];
$current = [];

$handle = fopen($leasesFile, 'r');
if ($handle) {
    while (($line = fgets($handle)) !== false) {
        $line = trim($line);

        if ($line === 'lease {') {
            $current = [];
        } elseif (preg_match('/^lease ([0-9.]+) {/', $line, $m)) {
            $current['ip'] = $m[1];
        } elseif (preg_match('/^hardware ethernet ([0-9a-f:]+);$/', $line, $m)) {
            $current['mac'] = $m[1];
        } elseif (preg_match('/^client-hostname "(.*)";$/', $line, $m)) {
            $current['hostname'] = $m[1];
        } elseif (preg_match('/^starts \d+ (\d+)\/(\d+)\/(\d+) (\d+):(\d+):(\d+);$/', $line, $m)) {
            $current['start'] = "{$m[1]}-{$m[2]}-{$m[3]} {$m[4]}:{$m[5]}:{$m[6]}";
        } elseif (preg_match('/^ends \d+ (\d+)\/(\d+)\/(\d+) (\d+):(\d+):(\d+);$/', $line, $m)) {
            $current['end'] = "{$m[1]}-{$m[2]}-{$m[3]} {$m[4]}:{$m[5]}:{$m[6]}";
        } elseif ($line === '}') {
            if (!empty($current)) $leases[] = $current;
        }
    }
    fclose($handle);
}

echo json_encode($leases);
?>