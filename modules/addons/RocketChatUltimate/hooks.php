<?php

use Illuminate\Database\Capsule\Manager as Capsule;

if ($_SESSION['loggedinstatus'] == "true") {
	$client_info = mysql_fetch_all("SELECT firstname, lastname, email FROM tblclients WHERE userid = " . $_SESSION['uid']);
	if (count($client_info)) {
		$firstname = $info[0]['firstname'];
		$lastname = $info[0]['lastname '];
		$email = $info[0]['email'];
	}
};
add_hook('ClientAreaFooterOutput', 1, function ($vars) {
	$enable = Capsule::table('tbladdonmodules')->select('value')->WHERE('module', '=', 'RocketChatUltimate')->WHERE('setting', '=', 'rocketchat-enable')->pluck('value');
	if (is_array($enable)) {
		$enable = current($enable);
	}
	if ('on' != $enable) {
		return;
	}
	$url = Capsule::table('tbladdonmodules')->select('value')->WHERE('module', '=', 'RocketChatUltimate')->WHERE('setting', '=', 'rocketchat-url')->pluck('value');
	if (is_array($url)) {
		$url = current($url);
	}
	return '
	<!-- Start of Rocket.Chat Livechat Script -->
	<script type="text/javascript">
	(function(w, d, s, u) {
		w.RocketChat = function(c) { w.RocketChat._.push(c) }; w.RocketChat._ = []; w.RocketChat.url = u;
		var h = d.getElementsByTagName(s)[0], j = d.createElement(s);
		j.async = true; j.src = "' . $url . '/packages/rocketchat_livechat/assets/rocketchat-livechat.min.js?_=201702160944";
		h.parentNode.insertBefore(j, h);
	})(window, document, "script", "' . $url . '/livechat");
	</script>
	<!-- End of Rocket.Chat Livechat Script -->';
}); 