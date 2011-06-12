<?php

define('TW_ID', 'TWITTER_ID');
define('TW_PASS', 'TWITTER_PASSWORD');

# 検索文字列の設定
$keyword = $_GET['w'];
if($keyword == ''){
	# デフォルト検索文字列
	$keyword = '';
} else {
	# サニタイズ
	$keyword = preg_replace('[;|&`$<>]','',$keyword);
	$vKeyword = $keyword;
	$keyword = escapeshellcmd($keyword);
	$keyword = escapeshellarg($keyword);
	$keyword = '\#'. $keyword;
}
define('KEYWORD', $keyword);

$command = 'php lib/streaming.php '. TW_ID. ' '. TW_PASS. ' '. KEYWORD. ' > /dev/null &';
$output = array();
$ret = '';

exec($command, $output, $ret);

include('lib/index.html');
?>