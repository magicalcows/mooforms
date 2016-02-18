<?php
ini_set('display_errors', 'on');
error_reporting(E_ALL);

require(__DIR__.'/config.php');



ob_start();

?>

<ul>
<?php foreach($examples as $k=>$v) {?>
<li><a href="/<?php echo $k?>"><?php echo $v?></a></li>
<?php } ?>
</ul>
<?php

$HTML = ob_get_contents();
ob_end_clean();




$TITLE = 'MooForms Examples';

require __DIR__.'/template-bootstrap3.phtml';
