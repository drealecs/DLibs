<form method="post">
	<input type="hidden" value="1" name="a" />
	<input type="hidden" value="2" name="b" />
	<input type="hidden" value="3" name="a" />
	<input type="submit" value="Post!">
</form>


<pre>
<?php
var_dump($_GET);
var_dump($_POST);
var_dump($_SERVER['QUERY_STRING']);
var_dump(getenv('REDIRECT_endPointCode'));
var_dump(getenv('REDIRECT_endPointOperationCode'));

var_dump(file_get_contents('php://input'));
var_dump(getallheaders());
var_dump($_SERVER);
var_dump($_ENV);
die('0 - request');
?>
</pre>
-------------------------------------------------------
<pre>
<?php
require_once '../library/HttpRequest.php';
require_once '../library/HttpCall.php';
require_once '../library/HttpClient.php';
require_once '../library/HttpSession.php';




$session = new HttpSession(17);

//$session->get('http://drealecs.programel.ro/wp-login.php?y=1', array(x => 2));
$session->get('https://www.google.ro/?y=1', array('a'=>5, 'b'=>8), array(''));
