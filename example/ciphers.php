<?php
/**
 * Created by PhpStorm.
 * User: aiChenK
 * Date: 2019-11-19
 * Time: 17:58
 */

require_once dirname(__DIR__) . '/src/Bootstrap.php';
\OpenCrypt\Bootstrap::init();

use OpenCrypt\Crypt;

$crypt = new Crypt();
print_r($crypt->getAvailableCiphers());