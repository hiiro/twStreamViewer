<?php
require_once dirname(__FILE__).'/lime/lime.php';
require_once dirname(__FILE__).'/Twitter.php';
require_once dirname(__FILE__).'/Pusher.php';


# Pusher定義
define('PUSHER_CHANNEL_NAME', 'stream');
define('PUSHER_EVENT_NAME', 'sv');

define('PUSHER_KEY', 'PUSHER_KEY');
define('PUSHER_SECRET', 'PUSHER_SECRET_STR');
define('PUSHER_APP_ID', 'PUSHER_APP_ID');

# 時間制限
set_time_limit(60*60*24);
ini_set("max_execution_time",60*60*24);


$t = new lime_test(3, new lime_output_color());
try {
	if (!$t->is(count($argv),4,'Usage: php streaming.php [acount] [password] [keyword]')) {
		exit;
	}
	$twitter = new Twitter();
	$twitter->basicAuth($argv[1], $argv[2]);
	$t->ok($twitter->streaming('statuses/filter',array('track'=>$argv[3]),'_callback'),'statuses/filter');
} catch (Twitter_Exception $e) {
	$t->fail($e);
} catch (Exception $e) {
	$t->fail($e);
}


function _callback($status) {
	$pusher  = new Pusher(PUSHER_KEY, PUSHER_SECRET, PUSHER_APP_ID);
	
	$id_str = $status['id_str'];
	$text = $status['text'];
//	$id = $status['user']['id'];
	$screen_name = $status['user']['screen_name'];
	$name = $status['user']['name'];
	$profile_image_url = $status['user']['profile_image_url'];
	
	$json = array("data"=>array("id_str"=>$id_str, "text"=>$text, "user"=>array("name"=>$name, "screen_name"=>$screen_name, "profile_image_url"=>$profile_image_url)));
	
	if($id_str != ""){
		$pusher->trigger(PUSHER_CHANNEL_NAME, PUSHER_EVENT_NAME, $json);
	}
	
//	echo $status['user']['name'].':'.$status['text'] . PHP_EOL;
	return true;
}

?>