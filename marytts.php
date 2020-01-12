<?php
require_once './vendor/autoload.php';

$text = urldecode($_REQUEST['target']);
$ainame = $_REQUEST['ainame'];

$filename = "/var/www/tmp/" . md5($text) . '_' . time() . '.wav';

$client = new Goutte\Client();
$url="http://localhost:59125/process?INPUT_TYPE=TEXT&INPUT_TEXT=" . urlencode($text) . "&OUTPUT_TYPE=AUDIO&LOCALE=en_US&VOICE=" . urlencode($ainame) . "&AUDIO=WAVE_FILE";

$result = $client->request('GET', $url);
$download_response = $client->getResponse()->getContent();
$directory_path = '/var/www/tmp/';
$file = new SplFileObject($filename, 'w');
$file->fwrite($download_response);

header('Content-Type: audio/aac');
header('X-Content-Type-Options: nosniff');
header('Content-Length: ' . filesize($filename));
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Connection: close');
while (ob_get_level()) { ob_end_clean(); }
readfile($filename);
unlink($filename);
exit;

?>
