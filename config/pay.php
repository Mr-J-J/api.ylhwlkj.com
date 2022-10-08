<?php

return [
    'alipay' => [
        // 支付宝分配的 APPID
        'app_id' => env('ALI_APP_ID', ''), //2021002171665007//2021000118698233

        // 支付宝异步通知地址
        'notify_url' => '',

        // 支付成功后同步通知地址
        'return_url' => '',

        // 阿里公共密钥，验证签名时使用
        'ali_public_key' => app_path('Paycert').'/alipay/alipayCertPublicKey_RSA2.crt',

        // 自己的私钥，签名时使用
        'private_key' => 'MIIEoQIBAAKCAQEAv2douJ7GZSqWwENGvaMqQCRB2KCckSM+jjAO5F/Dyeqay2rGRox8enCW9hvKLOc0Q0rfGzUHw+CJoCLpgMkzvWJWoQ1RBvQC0s35geT4wniWjYeYh7pOveoNg7YdloYJTP3/rvjCcmo+WGc8VrJdYRAmgsnycFqvK519eSLKeM/wFrXgZRZumqIc1DvK+FXHuEMKAv3YaAyAtxIhYB4l8mIdnl4n+c2gjudm6FaaAo+Wbk1/Fw6bnx2MqUOYSS4MYGWbHVjL16xltr9wg15+Yfva/8ZP10U9O457uWqwvvc5oM5gmo7GDuamK40mIDe+Uh8HHHWmYvyjLHU1QnRvMwIDAQABAoIBAQC3m+dlU0pWOeirPt5ZhHA/X4ia2lfXEswrcgaXJRaYV6lugVr6ykGO4vqt1DK5qIEGHIixfMmaKYdcSqFcnaY4gmE/1Zpo5SLgPh6fcJsBUtR5qB219g8bN68HLmhrwOCjLvVbT96YCzsLat0C/c9+ERQgWsFq7lMjr8xxq+I2yPiYpYWxDtYFnPsIgWXtr4jYSN3hQioWmnC0N1+ztg2Ygmo8+PUz7C+lqOIakwoGSW0VWPM0Lc+TXajvzz013E6u4aU1TmXEtJgNOegtWeAtAKsDiTlWXB6gIqrNXZylun39C2szymLpHIMyOw7zfoaeExoSRbDBEyQXarSXZqBBAoGBAOxZWgg3UTvkd/C/KPCn+jeSXMJ9rsD14KjJe+0ox4Y2W0FcHmH8DqMOgQEmRXbtxDE1NolzN6kwV1inKn/REesAI8xaj8XnVmDFIPOgbciVI5PsvNwvIqwQ97UKIL8SGq7uuzOyys4M4hMPY5gVYfd5NEvN2Lm+WzMmRLnhflYJAoGBAM9RaK+tE27980+umn15uTWWQ54dqukFk4124Pd3HYuPM7C5I6KPndiA98k3JCBjZzGP6cpXdet5EDLg2nKJseGDgM9FI/ahtClS8uMUvaxfujndJ6mjIO8p9vpZtaH68zt0tkA1vxZvo5xAtCEQh6aDVQ9MmB+Uu95RFkIRk4pbAoGAS6O06pg5iT9vGz8ybQJ1U7+lrCDpApwuEBabcKTyZgOTZPGET7uJO6nyo1mKNauWdGEhWQ/kqmLsMVUehtV0NI9Q5Z++D4of3Mx55T5cpCsGvdqv/o+fOw4bGHdrT3sNyxpgEwWPXi5FU/BC4XNGbRr/H5t7VsaTDWWb7Rk7g3ECfxHQ7S4rsX/YUxCF8MDXtRLl1um/ovBjikqd7LdXhTJ5G/gT8PwHW4k/jAu+sVwXLscTlwPMgFW8EahNqngtXRwEMQ2e43hObS0f87+QCLzHfTl7x+zMGAh1ksPw8ar55e02GnNqKwHClkzXQqOUyBOHNcgazPfp0DtKvJ7w470CgYBVMyyls3Imn5wwTHk1dsQUNHRyohmqSg6aVvQOvvPSvH9W6Zt+ovF5LfKPWZ77MdlECyFdTG7OYwtLb+diPR0TkrWA5lhd8FN8/rf4RST6/U4iGQ6y1yTvOfJ29Op+AmBvI8Oufk1eNM1z8MU9jWxQ2PUh2C2mphQGV9FAEBwXZg==',

        // 使用公钥证书模式，请配置下面两个参数，同时修改 ali_public_key 为以 .crt 结尾的支付宝公钥证书路径，如（./cert/alipayCertPublicKey_RSA2.crt）
        // 应用公钥证书路径
        'app_cert_public_key' => app_path('Paycert').'/alipay/appCertPublicKey_2021000118698233.crt',

        // 支付宝根证书路径
        'alipay_root_cert' => app_path('Paycert').'/alipay/alipayRootCert.crt',

        // optional，默认 warning；日志路径为：sys_get_temp_dir().'/logs/yansongda.pay.log'
        'log' => [
            'file' => storage_path('logs/alipay.log'),
        //  'level' => 'debug'
        //  'type' => 'single', // optional, 可选 daily.
        //  'max_file' => 30,
        ],

        // optional，设置此参数，将进入沙箱模式
        'mode' => 'dev',
    ],

    'wechat' => [
        // 公众号 APPID
        'app_id' => env('WECHAT_APP_ID', ''),

        // 小程序 APPID
        'miniapp_id' => env('WECHAT_MINIAPP_ID', ''),

        // APP 引用的 appid
        'appid' => env('WECHAT_APPID', ''),

        // 微信支付分配的微信商户号
        'mch_id' => env('WECHAT_MCH_ID', ''),

        // 微信支付异步通知地址
        'notify_url' => '',

        // 微信支付签名秘钥
        'key' => env('WECHAT_KEY', ''),

        // 客户端证书路径，退款、红包等需要用到。请填写绝对路径，linux 请确保权限问题。pem 格式。
        'cert_client' => '',

        // 客户端秘钥路径，退款、红包等需要用到。请填写绝对路径，linux 请确保权限问题。pem 格式。
        'cert_key' => '',

        // optional，默认 warning；日志路径为：sys_get_temp_dir().'/logs/yansongda.pay.log'
        'log' => [
            'file' => storage_path('logs/wechat.log'),
        //  'level' => 'debug'
        //  'type' => 'single', // optional, 可选 daily.
        //  'max_file' => 30,
        ],

        // optional
        // 'dev' 时为沙箱模式
        // 'hk' 时为东南亚节点
        // 'mode' => 'dev',
    ],
];
