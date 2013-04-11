<?php

/*
 * Forumsystem coded for Ascent II
 * @author Yissu
 */

require('libs/Smarty.class.php');

$smarty = new Smarty;

$smarty->debugging = true;
$smarty->caching = false;

$smarty->display('index.tpl');
?>
