笔记应用
这是一个使用 Vue.js 前端和 Node.js 后端的简单笔记应用，支持创建、编辑、删除笔记。
前置条件
1.XAMPP
2.Node.js
3.Git
步骤：
1.下载所有文件。

2.启动 XAMPP 控制面板，开启 MySQL 模块。
  导入数据库：
mysql -u root -p notes < notes.sql
在命令行（Windows cmd 或 PowerShell）运行，不要在 MariaDB/MySQL 提示符（MariaDB [(none)]>）输入。
默认无密码，直接回车；若有密码，输入 MySQL root 密码。
或
使用 phpMyAdmin：
打开 http://localhost/phpmyadmin.
创建 notes 数据库（如果不存在）。
选择 导入，上传 notes.sql，点击 执行。

3.安装后端依赖
cd backend
npm install

4.启动后端
node server.js

5.部署前端
启动 XAMPP 的 Apache 模块.
复制 文件 到 XAMPP 的 htdocs/notes
浏览器打开 http://localhost/notes/index.php.

