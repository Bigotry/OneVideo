<?php

namespace app\common\service\sms\driver\alidy;

require_once 'SignatureHelper.php';

use app\common\service\sms\driver\alidy\SignatureHelper;

class SmsApi {

    private $accessKeyId;
    private $accessKeySecret;

    /**
     * SmsApi 构造函数.
     * @param $accessKeyId string AccessKeyId，请参阅<a href="https://ak-console.aliyun.com/">阿里云Access Key管理</a>
     * @param $accessKeySecret string AccessKeySecret，请参阅<a href="https://ak-console.aliyun.com/">阿里云Access Key管理</a>
     */
    function  __construct($accessKeyId, $accessKeySecret) {
        $this->accessKeyId = $accessKeyId;
        $this->accessKeySecret = $accessKeySecret;
    }

    /**
     * 发送短信
     *
     * @param string $signName <p>
     * 必填, 短信签名，应严格"签名名称"填写，参考：<a href="https://dysms.console.aliyun.com/dysms.htm#/sign">短信签名页</a>
     * </p>
     * @param string $templateCode <p>
     * 必填, 短信模板Code，应严格按"模板CODE"填写, 参考：<a href="https://dysms.console.aliyun.com/dysms.htm#/template">短信模板页</a>
     * (e.g. SMS_0001)
     * </p>
     * @param string $phoneNumbers 必填, 短信接收号码 (e.g. 12345678901)
     * @param array|null $templateParam <p>
     * 选填, 假如模板中存在变量需要替换则为必填项 (e.g. Array("code"=>"12345", "product"=>"阿里通信"))
     * </p>
     * @param string|null $outId [optional] 选填, 发送短信流水号 (e.g. 1234)
     * @param string|null $smsUpExtendCode [optional] 选填，上行短信扩展码（扩展码字段控制在7位或以下，无特殊需求用户请忽略此字段）
     * @return \stdClass
     */
    public function sendSms($signName, $templateCode, $phoneNumbers, $templateParam = null, $outId = null, $smsUpExtendCode = null) {

        // 初始化SendSmsRequest实例用于设置发送短信的参数
        $helper = new SignatureHelper();

        $params = array (
            "RegionId" => "cn-hangzhou",
            "Action" => "SendSms",
            "Version" => "2017-05-25",
            "PhoneNumbers" => $phoneNumbers,
            "SignName" => $signName,
            "TemplateCode" => $templateCode,
        );


        // 可选，设置模板参数
        if($templateParam) {
            $params['TemplateParam'] = json_encode($templateParam);
        }

        // 可选，设置流水号
        if($outId) {
            $params['OutId'] = $outId;
        }

        // 选填，上行短信扩展码
        if($smsUpExtendCode) {
            $params['SmsUpExtendCode'] = $smsUpExtendCode;
        }

        $content = $helper->request(
            $this->accessKeyId,
            $this->accessKeySecret,
            "dysmsapi.aliyuncs.com",
            $params
        );

        return $content;
    }


    /**
     * 短信发送记录查询
     *
     * @param string $phoneNumbers 必填, 短信接收号码 (e.g. 12345678901)
     * @param string $sendDate 必填，短信发送日期，格式Ymd，支持近30天记录查询 (e.g. 20170710)
     * @param int $pageSize 必填，分页大小
     * @param int $currentPage 必填，当前页码
     * @param string $bizId 选填，短信发送流水号 (e.g. abc123)
     * @return \stdClass
     */
    public function queryDetails($phoneNumbers, $sendDate, $pageSize = 10, $currentPage = 1, $bizId=null) {

        // 初始化SendSmsRequest实例用于设置发送短信的参数
        $helper = new SignatureHelper();

        $params = array (
            "RegionId" => "cn-hangzhou",
            "Action" => "QuerySendDetails",
            "Version" => "2017-05-25",
            "PhoneNumber" => $phoneNumbers,
            "SendDate" => $sendDate,
            "PageSize" => $pageSize,
            "CurrentPage" => $currentPage,
        );

        // 可选，设置流水号
        if($bizId) {
            $params['BizId'] = $bizId;
        }

        $content = $helper->request(
            $this->accessKeyId,
            $this->accessKeySecret,
            "dysmsapi.aliyuncs.com",
            $params
        );

        return $content;
    }
}