<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
error_reporting(0);//抑制下标不存在和变量不存的错误提示
//发送短信验证方法
function sendSms($to,$datas,$tempId)
{
	include_once("../extend/sendSms/CCPRestSmsSDK.php");
    //主帐号,对应开官网发者主账号下的 ACCOUNT SID
$accountSid= '8aaf070864ef775a0164f07f639b00b3';

//主帐号令牌,对应官网开发者主账号下的 AUTH TOKEN
$accountToken= '006581c481c44155808ccfbf8f5f4e0f';

//应用Id，在官网应用列表中点击应用，对应应用详情中的APP ID
//在开发调试的时候，可以使用官网自动为您分配的测试Demo的APP ID
$appId='8aaf070864ef775a0164f07f639b00b3';

//请求地址
//沙盒环境（用于应用开发调试）：sandboxapp.cloopen.com
//生产环境（用户应用上线使用）：app.cloopen.com
$serverIP='app.cloopen.com';


//请求端口，生产环境和沙盒环境一致
$serverPort='8883';

//REST版本号，在官网文档REST介绍中获得。
$softVersion='2013-12-26';
  // 初始化REST SDK
     //global $accountSid,$accountToken,$appId,$serverIP,$serverPort,$softVersion;
     $rest = new REST($serverIP,$serverPort,$softVersion);
     $rest->setAccount($accountSid,$accountToken);
     $rest->setAppId($appId);
    
     // 发送模板短信
     
     $result = $rest->sendTemplateSMS($to,$datas,$tempId);
     return $result;
}

//发送邮件的方法

function sendEmail($to,$title,$content){
    // 实例化
        include "../extend/sendEmail/class.phpmailer.php";
        $pm = new PHPMailer();
        // 服务器相关信息
        $pm->Host = 'smtp.163.com'; // SMTP服务器
        $pm->IsSMTP(); // 设置使用SMTP服务器发送邮件
        $pm->SMTPAuth = true; // 需要SMTP身份认证
        $pm->Username = 'fly668812'; // 登录SMTP服务器的用户名，邮箱@前面一串字符
        $pm->Password = 'fly668812'; //授权码 登录SMTP服务器的密码

        // 发件人信息
        $pm->From = 'fly668812@163.com'; //自己的邮箱
        $pm->FromName = '飞天'; // 发件人昵称，名字可以随便取
        foreach ($to as $email) {
            $pm->AddAddress($email,'');
        }
        // 收件人信息
        //$pm->AddAddress('897163955@qq.com', '飞哥'); // 设置收件人邮箱和昵称，昵称名字随便取
        //$pm->AddAddress('888888@qq.com', '8哥'); // 添加另一个收件人
        // $pm->From = '897163955@qq.com';
        // $pm->FromName = '飞哥';
        //循环设置收件人的信息


        $pm->CharSet = 'utf-8'; // 内容编码
        $pm->Subject = $title; // 邮件标题
        //$pm->MsgHTML('<a href="http://www.itcast.cn" target="_blank">商城找回密码</a>！'); // 邮件内容
        $pm->MsgHTML($content);
        //var_dump($pm->Send()); //成功返回true
        // 发送邮件
        if($pm->Send()){
           return ture;
        }else {
           //echo $pm->ErrorInfo;
           return false;
        }
}
