# Dcat Admin 文件管理扩展
## Screenshot
![uTools_1650016395341](https://user-images.githubusercontent.com/48778797/163557077-aeadab3f-ffff-4e45-bcef-2303dd0b5df4.png)


## 安装
1. 下载`zip`压缩包，打开扩展管理页面，点击`本地安装`按钮选择提交，然后找到 `jatdung.media-manager` 行点击 `启用` 按钮。
2. 打开 `config/admin.php`，找到 `extension` ，添加配置：
    ```php
    'extension'                 => [
        // 添加 meida-manager 选项
       'media-manager' => [
            // 'disk'        => 'public',
            'disk' => ['public', 'admin'], // 仅 v1.03 后支持多文件 
            // 'allowed_ext' => 'jpg,jpeg,png,pdf,doc,docx,zip'
        ]
    ],
    ```
   
## 注意
~~目前本项目只在 `laravel8` 版本下测试通过（对应 `league/flysystem` 版本为 `^1.1`），`laravel9` 以上由于 `league/flysystem` 已升级为 `3.0` 版本，应该有兼容性问题，低版本 `laravel` 可自行测试。~~

`2.x` 版本更新对 `laravel9` ( `league/flysystem:3.x` )的支持

`laravel9` 请使用 `2.x` 版本

旧版本 `laravel` 使用 `1.x` 版本


## 说明
本项目代码基本源于 [laravel-admin-extensions/media-manager](https://github.com/laravel-admin-extensions/media-manager) 项目，仅对不兼容的部分做了修改，如涉及侵权问题麻烦联系本人删除该项目。

## 感谢
- [dcat-admin](https://github.com/jqhph/dcat-admin)
- [laravel-admin-extensions/media-manager](https://github.com/laravel-admin-extensions/media-manager)

License
------------
Licensed under [The MIT License (MIT)](LICENSE).


