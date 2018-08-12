<?php
$config = array (	
		//应用ID,您的APPID。
		'app_id' => "2016091800538104",

		//商户私钥
		'merchant_private_key' => "MIIEogIBAAKCAQEAqRJCq4355M6/kQ/qmuUlbot212Jk9VEQOc0tSAiXxqz0uDSPq8I2ZBCFxNnwvH7cqSMeKuSVTGuMRF2M6WUS1S9+FXXvZ9czvmMKsOM4r8ROLZOh61PCp3XE2p4qUgDUVbyglAfxKB36cck9Tgfgdw/xLHPo5PZFPfgxnN0SyYpzvlOxA90HuED4q3T4VDlmUwHRe0b0n5iFFK9OXR0p07nXgX4K+vTOd+UML45twzclDVMK7b+pT0mKZ7Ls284rmey2zkHoXrKMQg2Tu0Yh7Oz8PyiwLgPlXtyXUnP/i0cJIm/OTBd110uv7mJbo09NMH+07POi5tbg+yp4n0RnyQIDAQABAoIBAFPdFuz508bHNwbBmmGS4GphC2XzDJkSyLWjLJ7ahE6sZAcrK0jLYSdperEWWe9mDhZnsQMdt2DcyArxTIBI/np4wFXs1f/wI16AykDLtMaa3RyGan7MYXWnWSoH4n4iozCUDCLhfaGknSHr7FjUV2c3pS2joRZRgi8m+ZURo8wL7o3z+KAAM6cYE+ZkqZc+bkIp2YjHUTlDS6/1OSj1PZ3LPqmzi2PebR00cSGHxPG2BVF1TjTkSE5PMZceZTh9jL3WjdLEItj4LVNMO/bbzLa5Kxip/zTXDXegHgcA/mBepCP1sMG8oOmSNiZ+tFWg9pMDySD+Ir1USFA2wgxHxgECgYEA2stuBumECmqzHkkpBv5y5wE5t1npQuzJzlZhuZ+WmwyKx9Z9OWZsaFqjCsxFroaWCFts3J7vnY9Ra9mnCj8CrGxoar5cXP9+DaqVHdSCzH3VKFMFilPhwIgCwHdpXY2o9uD8OTiKQu7zryn5DXfXHRk9F+XvVD+XAXMXw6zz0nECgYEAxdJGWVZZNnMKialjOKbTFeJtsxrWu42qOrvm4eXjvurl4hyTGIXO20P1gVM2TNFBE5Bk4FQFpjyWP/VFlyW8HXhFjUm/Ih80cea+XV6/ho3Se2y2IKnrsZ92KRPP5/D6XLN3g6FCRWmIKykcI8SsyokE94VGPRywYMWZik2eZtkCgYAHceCej5eUmyjZIPgqasISJjKGkKKlSlVblgjhPhLr17NpNV0xdNC/hEYD+gts8ttsEWBU6XuRyuykVNWgPaCjqVSsPSn7aB4r7OACEdcZijaUlq6blFscKASf8/A75LQZInKyp7/cozDbhvwYfzlsng08IPDR1xAWVoo6eEoboQKBgE9DEILtMTO65ocAAsyJM1y6a//uigl+Gq4L6dereBRgJBn0HxAdVSPP2AeoYsJmua9wFKs5n0XbUsxvpyGHshcQwLV6zgWLAUV+Edpxg0YTfzmK9nKiAtkZrouI763chUQteH6aV2ZegXvhre+69wzz17ShhuIno5gytHGQ5h4JAoGAbAoSYrIBNf6h+oXgSX4J5Ag8/aobcrsFQLgkIOIgpy3zuofFsqGoZz1QaZ/PT/1CMpj9VQWmTozZguijo7k9HavA6CqZJiFdygRYJmOASyzhdbw0uybIHEIlw9mUA8dU88PbFY2EnpnIkpUHJeXY9H0atsvvI9ZKQsB/mbSabdY=",
		
		//异步通知地址
		'notify_url' => "http://外网可访问网关地址/alipay.trade.page.pay-PHP-UTF-8/notify_url.php",
		
		//同步跳转
		'return_url' => "http://外网可访问网关地址/alipay.trade.page.pay-PHP-UTF-8/return_url.php",

		//编码格式
		'charset' => "UTF-8",

		//签名方式
		'sign_type'=>"RSA2",

		//支付宝网关(沙箱环境为 alipaydev )
		'gatewayUrl' => "https://openapi.alipaydev.com/gateway.do",

		//支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
		'alipay_public_key' => "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAqNVY09kV2lg24RvRfYfhUbn4ZyEVrvTl6xsPCL1HIbZ8QS1SjAPA/1oTob2/iRZaElFCGBVSwe1IkvYAASwXJ49W4B21cWvWMcbNYsejUo6drUA0GM0Z9Wzc78Rl9jXW2vJLfaZ5sZgjWOicd0G1MQuyiFWVaa5g2OGyn+xhsecZ32oc1KwpYJa74jcrnOM7B65980G13Pnzz6THqt6OJr79BVl7WJVzOLTrtpnxTMShGhlqLjF0L9ff8mB9exW6NwXE48jS0juSS+uCREU7D7mFqWlLw849OdjbzdHF6J5fjHNN8BxuvLFmq6uWqfHFaky/ZaEtQDuG3qrplMJMVwIDAQAB",
);