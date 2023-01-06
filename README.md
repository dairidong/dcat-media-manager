# Dcat Admin 文件管理扩展
## Screenshot
![uTools_1650016395341](https://user-images.githubusercontent.com/48778797/163557077-aeadab3f-ffff-4e45-bcef-2303dd0b5df4.png)



## 安装

### 注意：3.0 配置名发生变更，必须更新

1. - 本地安装

   在 [release](https://github.com/dairidong/dcat-media-manager/releases/) 下载`zip`压缩包 ，打开扩展管理页面，点击`本地安装`按钮选择提交。
   
   - composer 安装

	```
	composer require jatdung/media-manager
	或
	composer require jatdung/media-manager:版本号
	```

   

2. 打开 `config/admin.php`，找到 `extension` ，添加配置：

    ```php
    'extension'                 => [
        // 添加 meida-manager 选项
        
        // 2.0 及以下版本
       'media-manager' => [
            // 'disk'        => 'public',
            'disk' => ['public', 'admin'], // 仅 v1.03 后支持多文件 
            // 'allowed_ext' => 'jpg,jpeg,png,pdf,doc,docx,zip'
        ]
        
        // 3.0 以上
        'media-manager' => [
            // 必须配置
            'disks' => ['public', 'qiniu'],
            
            // 可选配置
        	'allowed_ext' => 'jpg,png', // 允许的文件扩展
            'show_hidden_files' => false, // 是否显示隐藏文件，如 .gitignore，默认为 false
            'uploader_use_server_validate' => false, // 上传文件是否使用服务端进行验证，默认为 false
            // 使用的控制器
            'controller' => Jatdung\MediaManager\Http\Controllers\MediaManagerController::class,
            // 自行编写的第三方存储适配器，以下是默认值
            'adapters' => [
            	Overtrue\Flysystem\Qiniu\QiniuAdapter::class => Jatdung\MediaManager\Adapters\OvertrueQiniuAdapter::class,
            ],
        ],
    ],
    ```



3. 在扩展管理页面找到 `jatdung.media-manager` 更新并启用。

   

## 版本

| 扩展包版本 | laravel 版本 | 备注                                                         |
| :--------: | :----------: | :----------------------------------------------------------- |
|    3.x     |      9       | 1. 参考借鉴（抄袭）了 Dcat 的 Grid 模块代码，使用 Dcat 的 Action , Card , Modal 等组件进行重构  <br>2. 可以在不修改源代码的前提下，进行一定的自定义<br>3. |
|    2.x     |      9       |                                                              |
|    1.x     |    9 以下    | 仅在 laravel 8 环境下测试，以下版本请自行测试                |



## 扩展

TODO



## 说明

本项目代码基本源于 [laravel-admin-extensions/media-manager](https://github.com/laravel-admin-extensions/media-manager) 项目，仅对不兼容的部分做了修改，如涉及侵权问题麻烦联系本人删除该项目。



## 感谢

- [dcat-admin](https://github.com/jqhph/dcat-admin)
- [laravel-admin-extensions/media-manager](https://github.com/laravel-admin-extensions/media-manager)



License
------------

Licensed under [The MIT License (MIT)](LICENSE).

