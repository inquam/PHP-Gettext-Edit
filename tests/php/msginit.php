<?php
define('ROOT_PATH', realpath(dirname(__FILE__)).'/');

$command = '/usr/bin/msginit --input="'.ROOT_PATH.'file.pot" --output="'.ROOT_PATH.'file.po"';
$exec = exec($command);
var_dump($command, $exec);
?>
