<?php
/**
 * Created by PhpStorm.
 * User: aiChenK
 * Date: 2019-11-19
 * Time: 14:48
 */

require_once dirname(__DIR__) . '/src/Bootstrap.php';
\OpenCrypt\Bootstrap::init();

use OpenCrypt\Crypt;

$crypt = new Crypt();
//$crypt->setCipher('des-ecb');
//$crypt->setIv('fff');
$text = 'test1234';
$encryptText = $crypt->encrypt($text);
$decryptText = $crypt->decrypt($encryptText);
echo '原文：', $text, PHP_EOL;
echo '加密：', $encryptText, PHP_EOL;
echo '解密：', $decryptText, PHP_EOL;