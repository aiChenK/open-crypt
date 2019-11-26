# open-crypt
> 简单php加密类，基于openssl

## 运行环境
- PHP 7.2+
- openssl extension

## 安装方法
        composer require aichenk/open-crypt
        
## 使用
```php
$crypt = new Crypt();
$crypt->setKey('12345678');
//$crypt->setCipher('des-ecb');
//$crypt->setIv('fff');
$text = 'test1234';
$encryptText = $crypt->encrypt($text);
$decryptText = $crypt->decrypt($encryptText);
```

## 命令行
1. php执行

        php bin/encrypt text [key]
        php bin/decrypt encryptText [key]
       
2. 系统命令(composer 引入，项目根目录下)

         vendor/bin/encrypt text [key]
         vendor/bin/decrypt encryptText [key]
    