### 文件管理系统 截图

user: admin

password: hello0

此项目存在BUG，仅在window调试过。主要使用了AJAX文件上传和下载。无法通过更改数据库跳过注册验证，密码加密非完全MD5。必须配置邮箱来发送激活邮件

![截图1](http://static-old.wktrf.com/yn_screenshots_1.png)
![截图2](http://static-old.wktrf.com/yn_screenshots_2.png)
![截图3](http://static-old.wktrf.com/yn_screenshots_3.png)

# 使用步骤
- 安装docker, docker-compose
- 执行`docker-compose up -d`
- 导入SQL文件，在config/database.sql
- 添加配置文件，config/config.php
