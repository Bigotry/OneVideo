<?php
/**
 * Created by PhpStorm
 * 下载开源RSA密钥生成工具openssl（通常Linux系统都自带该程序），解压缩至独立的文件夹，进入其中的bin目录，执行以下命令：

openssl genrsa -out rsa_private_key.pem 1024
openssl pkcs8 -topk8 -inform PEM -in rsa_private_key.pem -outform PEM -nocrypt -out private_key.pem
openssl rsa -in rsa_private_key.pem -pubout -out rsa_public_key.pem

第一条命令生成原始 RSA私钥文件 rsa_private_key.pem，第二条命令将原始 RSA私钥转换为 pkcs8格式，第三条生成RSA公钥 rsa_public_key.pem
从上面看出通过私钥能生成对应的公钥，因此我们将私钥private_key.pem用在服务器端，公钥发放给android跟ios等前端
 * User: dy
 * Date: 2017/12/28
 * Time: 9:50
 */
namespace rsa;
/**
 * RSA算法类
 * 签名及密文编码：base64字符串/十六进制字符串/二进制字符串流
 * 填充方式: PKCS1Padding（加解密）/NOPadding（解密）
 *
 * Notice:Only accepts a single block. Block size is equal to the RSA key size!
 * 如密钥长度为1024 bit，则加密时数据需小于128字节，加上PKCS1Padding本身的11字节信息，所以明文需小于117字节
 *
 * @author: ZHIHUA_WEI
 * @version: 1.0.0
 * @date: 2017/06/30
 */
/**
 * 使用方法:
 * 下载openssl(liunx自带)
 * openssl genrsa -out rsa_private_key.pem 1024
 * openssl rsa -in rsa_private_key.pem -pubout -out rsa_public_key.pem
 * 这两个文件就是公钥和私钥
 *  new \rsa\RSA('pri_key_path','pub_key_path')->encrypt("123");//加密
 *  new \rsa\RSA('pri_key_path','pub_key_path')->decrypt("xxx");//解密
 *  new \rsa\RSA('pri_key_path','pub_key_path')->sign("xxx");//签名
 *  new \rsa\RSA('pri_key_path','pub_key_path')->verify("xxx");//验证签名
 * Class RSA
 * @package rsa
 */
class RSA
{
    private $pubKey = null;
    private $priKey = null;

    /**
     * 构造函数
     *
     * @param string $public_key_file 公钥文件（验签和加密时传入）
     * @param string $private_key_file 私钥文件（签名和解密时传入）
     */
    public function __construct($public_key_file = '', $private_key_file = '')
    {
        if ($public_key_file) {
            $this->_getPublicKey($public_key_file);
        }
        if ($private_key_file) {
            $this->_getPrivateKey($private_key_file);
        }
    }

    // 私有方法
    /**
     * 自定义错误处理
     */
    private function _error($msg)
    {
        die('RSA Error:' . $msg); //TODO
    }

    /**
     * 检测填充类型
     * 加密只支持PKCS1_PADDING
     * 解密支持PKCS1_PADDING和NO_PADDING
     *
     * @param int  $padding 填充模式
     * @param string $type 加密en/解密de
     * @return bool
     */
    private function _checkPadding($padding, $type)
    {
        if ($type == 'en') {
            switch ($padding) {
                case OPENSSL_PKCS1_PADDING:
                    $ret = true;
                    break;
                default:
                    $ret = false;
            }
        } else {
            switch ($padding) {
                case OPENSSL_PKCS1_PADDING:
                case OPENSSL_NO_PADDING:
                    $ret = true;
                    break;
                default:
                    $ret = false;
            }
        }
        return $ret;
    }

    private function _encode($data, $code)
    {
        switch (strtolower($code)) {
            case 'base64':
                $data = base64_encode('' . $data);
                break;
            case 'hex':
                $data = bin2hex($data);
                break;
            case 'bin':
            default:
        }
        return $data;
    }

    private function _decode($data, $code)
    {
        switch (strtolower($code)) {
            case 'base64':
                $data = base64_decode($data);
                break;
            case 'hex':
                $data = $this->_hex2bin($data);
                break;
            case 'bin':
            default:
        }
        return $data;
    }

    private function _getPublicKey($file)
    {
        $key_content = $this->_readFile($file);
        if ($key_content) {
            $this->pubKey = openssl_get_publickey($key_content);
        }
    }

    private function _getPrivateKey($file)
    {
        $key_content = $this->_readFile($file);
        if ($key_content) {
            $this->priKey = openssl_get_privatekey($key_content);
        }
    }

    private function _readFile($file)
    {
        $ret = false;
        if(is_file($file)) {
            if (!file_exists($file)) {
                $this->_error("The file {$file} is not exists");
            } else {
                $ret = file_get_contents($file);
            }
        }else{
            $ret = $file;
        }
        return $ret;
    }

    private function _hex2bin($hex = false)
    {
        $ret = $hex !== false && preg_match('/^[0-9a-fA-F]+$/i', $hex) ? pack("H*", $hex) : false;
        return $ret;
    }

    /**
     * 生成签名
     *
     * @param string  $data 签名材料
     * @param string  $code 签名编码（base64/hex/bin）
     * @return string 签名值
     */
    public function sign($data, $code = 'base64')
    {
        $ret = false;
        if (openssl_sign($data, $ret, $this->priKey)) {
            $ret = $this->_encode($ret, $code);
        }
        return $ret;
    }

    /**
     * 验证签名
     *
     * @param string $data 签名材料
     * @param string $sign 签名值
     * @param string $code 签名编码（base64/hex/bin）
     * @return bool
     */
    public function verify($data, $sign, $code = 'base64')
    {
        $ret = false;
        $sign = $this->_decode($sign, $code);
        if ($sign !== false) {
            switch (openssl_verify($data, $sign, $this->pubKey)) {
                case 1:
                    $ret = true;
                    break;
                case 0:
                case -1:
                default:
                    $ret = false;
            }
        }
        return $ret;
    }

    /**
     * 加密
     *
     * @param string  $data 明文
     * @param string  $code 密文编码（base64/hex/bin）
     * @param int  $padding 填充方式（貌似php有bug，所以目前仅支持OPENSSL_PKCS1_PADDING）
     * @return string 密文
     */
    public function encrypt($data, $code = 'base64', $padding = OPENSSL_PKCS1_PADDING)
    {
        $ret = false;
        if (!$this->_checkPadding($padding, 'en')) $this->_error('padding error');
        if (openssl_private_encrypt($data, $result, $this->priKey, $padding)) {
            $ret = $this->_encode($result, $code);
        }
        return $ret;
    }

    /**
     * 解密
     *
     * @param string $data 密文
     * @param string $code 密文编码（base64/hex/bin）
     * @param int $padding 填充方式（OPENSSL_PKCS1_PADDING / OPENSSL_NO_PADDING）
     * @param bool $rev 是否翻转明文（When passing Microsoft CryptoAPI-generated RSA cyphertext, revert the bytes in the block）
     * @return string 明文
     */
    public function decrypt($data, $code = 'base64', $padding = OPENSSL_PKCS1_PADDING, $rev = false)
    {
        $ret = false;
        $data = $this->_decode($data, $code);
        if (!$this->_checkPadding($padding, 'de')) $this->_error('padding error');
        if ($data !== false) {
            if (openssl_public_decrypt($data, $result, $this->pubKey, $padding)) {
                $ret = $rev ? rtrim(strrev($result), "\0") : '' . $result;
            }
        }
        return $ret;
    }
}