<?php
/**
 * Created by PhpStorm.
 * User: aiChenK
 * Date: 2019-11-19
 * Time: 14:42
 */
namespace OpenCrypt;

class Crypt
{
    const AUTH_CIPHER         = ['gcm', 'ccm'];

    private $cipher           = 'aes-128-cfb';
    private $defKey           = '80JgPsjGYupCUYtu';
    private $ivLength         = 16;
    private $iv               = '';
    private $mode             = 'cfb';
    private $availableCiphers = [];
    private $key              = '';

    /**
     * 设置加密方式
     *
     * @param string $cipher
     * @return $this
     * @throws \Exception
     */
    public function setCipher(string $cipher)
    {
        if (!in_array($cipher, $this->getAvailableCiphers())) {
            throw new \Exception('不支持该加密方式');
        }
        $this->cipher   = $cipher;
        $this->ivLength = openssl_cipher_iv_length($cipher);
        $this->mode     = strtolower(substr($cipher, strrpos($cipher, "-") - strlen($cipher) + 1));
        return $this;
    }

    /**
     * 设置密钥路径
     * 可传入加密后路径 - 需使用bin\encrypt加密
     *
     * @param $path
     * @param bool $encrypt
     * @return $this
     * @throws \Exception
     */
    public function setKeyPath(string $path, bool $encrypt = false)
    {
        $path = $encrypt ? $this->decrypt($path, $this->defKey) : $path;
        if (!is_file($path)) {
            throw new \Exception('key file does not\'t exist');
        }
        $this->key = file_get_contents($path);
        return $this;
    }

    /**
     * 获取加密方式
     *
     * @return string
     */
    public function getCipher()
    {
        return $this->cipher;
    }

    /**
     * 设置密钥
     *
     * @param string $key
     * @return $this
     */
    public function setKey(string $key)
    {
        $this->key = $key;
        return $this;
    }

    /**
     * 获取使用密钥
     *
     * @return string
     */
    public function getKey()
    {
        return $this->key ?: $this->defKey;
    }

    /**
     * 设置偏移量
     *
     * @param string $iv
     * @return $this
     */
    public function setIv(string $iv)
    {
        $length = strlen($iv);
        if ($length > $this->getIvLength()) {
            $iv = substr($iv, 0, $this->getIvLength());
        } elseif ($length < $this->getIvLength()){
            $iv .= str_repeat(chr(0), $this->getIvLength() - $length);
        }
        $this->iv = $iv;
        return $this;
    }

    /**
     * 获取偏移量
     *
     * @return string
     */
    public function getIv()
    {
        if (!$this->iv) {
            $this->iv = openssl_random_pseudo_bytes($this->getIvLength());
        }
        return $this->iv;
    }

    /**
     * 加密
     *
     * @param string $text          --需加密内容
     * @param string|null $key      --密钥，不传入则使用getKey()
     * @return string
     */
    public function encrypt(string $text, string $key = null)
    {
        $iv      = $this->getIv();
        $key     = $key ?: $this->getKey();
        $content = openssl_encrypt($text, $this->cipher, $key, OPENSSL_RAW_DATA, $iv);
        return base64_encode($iv . $content);
    }

    /**
     * 解密
     *
     * @param string $text          --需解密内容
     * @param string|null $key      --密钥，不传入则使用getKey()
     * @return string
     */
    public function decrypt(string $text, string $key = null)
    {
        $key        = $key ?: $this->getKey();
        $text       = base64_decode($text);
        $iv         = mb_substr($text, 0, $this->getIvLength(), "8bit");
        $cipherText = mb_substr($text, $this->getIvLength(), null, "8bit");
        $content    = openssl_decrypt($cipherText, $this->cipher, $key, OPENSSL_RAW_DATA, $iv);
        return $content;
    }

    /**
     * 获取支持的加密方式列表
     *
     * @return array
     */
    public function getAvailableCiphers()
    {
        if (!$this->availableCiphers) {
            $ciphers = openssl_get_cipher_methods(true);
            foreach ($ciphers as $cipher) {
                $cipher = strtolower($cipher);
                if (in_array(substr($cipher, -3, 3), self::AUTH_CIPHER)) {
                    continue;
                }
                $this->availableCiphers[] = $cipher;
            }
        }
        return $this->availableCiphers;
    }

    /**
     * 获取偏移量长度
     *
     * @return int
     */
    private function getIvLength()
    {
        return $this->ivLength;
    }
}