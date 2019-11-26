<?php
/**
 * Created by PhpStorm.
 * User: aiChenK
 * Date: 2019-11-26
 * Time: 17:50
 */

require_once dirname(__DIR__) . '/src/Bootstrap.php';
\OpenCrypt\Bootstrap::init();

use OpenCrypt\Crypt;

$crypt = new Crypt();
//$crypt->setKeyPath('./key.txt');
$crypt->setKeyPath('fczNJHXjNGKtMXSO/C/kLtHRVejqixTTMA==', true);
$text = 'test1234';
$encryptText = $crypt->encrypt($text);
$decryptText = $crypt->decrypt($encryptText);
echo '原文：', $text, PHP_EOL;
echo '加密：', $encryptText, PHP_EOL;
echo '解密：', $decryptText, PHP_EOL;