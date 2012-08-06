<pre>
<?php
require_once '../library/HttpClient.php';
require_once '../library/HttpSession.php';
require_once '../library/HttpCall.php';




$session = new HttpSession(17);

//$session->get('http://drealecs.programel.ro/wp-login.php?y=1', array(x => 2));
$session->post('https://rm.epayment.local/?y=1', array('a'=>5, 'b'=>8), array('x' => 2), array(''));
