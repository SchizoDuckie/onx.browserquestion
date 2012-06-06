<?php

if(isset($_GET) && isset($_GET['action']) && $_GET['action'] =='store') {
	if(!isset($_POST['question']) || !isset($_POST['response']) || ($_POST['response'] != 'yes' && $_POST['response'] != 'no')) {
		die("NO WAY");
	} else {
		$memcache_obj = memcache_connect('localhost', 11211);
		$var = memcache_set($memcache_obj, md5(urldecode($_POST['question'])), $_POST['response'], 0, 60);
	}

} elseif(isset($_GET) && isset($_GET['action']) && $_GET['action'] == 'get') {
	if(!isset($_POST['question']) && !isset($_GET['question'])) {
		die("NO WAY");
	} else {
		$q = isset($_POST['question']) ? urldecode($_POST['question'])  : urldecode($_GET['question']);
		$memcache_obj = memcache_connect('localhost', 11211);
		$var = memcache_get($memcache_obj, md5($q));
		if(!$var) {
			sleep(3);
		}
		die($var);
	}
}
?>
<html>
<body>
<title>The Question</title>
<link rel="stylesheet" href="style.css">
<?php 

if(empty($_GET) && empty($_POST)) {   

?>

<script type='text/javascript'>

window.addEventListener('load', function() {
	var q = $('q');
	q.innerText = $('question').value = GET('q') || 'Ask a question by using the q get parameter';
	document.title += ' is: '+q.innerText;	
});

function $(id) {
	return document.getElementById(id);
}

function answerYes() {
	$('response').value='yes';
	$('f').submit();
}

function answerNo() {
	$('response').value= 'no';
	$('f').submit();
}

function GET( name )
{
  name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
  var _GET = new RegExp("[\\?&]"+name+"=([^&#]*)" );
  var results = _GET.exec( window.location.href );
  return results == null ? null : decodeURIComponent(results[1]);
}

</script>

<div class='arrow_box'>
<h1 id='q'></h1>

<div class='buttons'>
<div class="button yes">
  <a class="butt" onclick='answerYes()'>YES</a>
</div>

<div class="button no">
  <a class="butt" onclick='answerNo()'>NO</a>
</div>
</div>

<form method='post' action='?action=store' id='f' style='display:none'>
	<input type='hidden' name='question' value='' id='question'>
	<input type='hidden' name='response' value='' id='response'>
</form>

<?php } else { ?>

<div class='arrow_box'>
	<h1 id='q'>Thanks. You may now press back to close.</h1>
</div>

<? } ?>

<link href="http://fonts.googleapis.com/css?family=Terminal+Dosis:400,700" rel="stylesheet" type="text/css">
</html>