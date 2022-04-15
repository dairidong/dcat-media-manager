# Dcat Admin 文件管理扩展
### 安装
1. 下载`zip`压缩包，打开扩展管理页面，点击`本地安装`按钮选择提交，然后找到` jatdung.media-manager`行点击`启用`按钮。
2. 打开 `config/admin.php`，找到 `extension` ，添加配置：
    ```php
    'extension'                 => [
        // 添加 meida-manager 选项
        'media-manager' => [
            'disk'        => 'public',
            // 'allowed_ext' => 'jpg,jpeg,png,pdf,doc,docx,zip'
        ]
    ],
    ```
   
### 说明
本项目代码基本源于 [laravel-admin-extensions/media-manager](https://github.com/laravel-admin-extensions/media-manager) 项目，仅对不兼容的部分做了修改，如涉及侵权问题麻烦联系本人删除该项目。

### 感谢
- [dcat-admin](https://github.com/jqhph/dcat-admin)
- [laravel-admin-extensions/media-manager](https://github.com/laravel-admin-extensions/media-manager)

License
------------
Licensed under [The MIT License (MIT)](LICENSE).

