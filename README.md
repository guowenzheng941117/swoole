## 环境要求

*  centos7
*  php7.3
*  redis
*  nginx
*  mysql5.7

## 安装lnmp环境

`wget http://soft.vpser.net/lnmp/lnmp1.6.tar.gz -cO lnmp1.6.tar.gz && tar zxf lnmp1.6.tar.gz && cd lnmp1.6 && LNMP_Auto="y" DBSelect="4" DB_Root_Password="root" InstallInnodb="y" PHPSelect="9" SelectMalloc="1" ./install.sh lnmp`

## 安装redis拓展

`./addons.sh install redis`

## 安装swoole拓展

安装时可以参考swoole官方文档说明(PHP-7.3 版本的 pcre.jit 存在 bug，可能会导致进程持续崩溃，请修改 php.ini 设置 pcre.jit=0)

```bash
wget https://github.com/swoole/swoole-src/archive/v4.4.8.tar.gz && tar -xzvf v4.4.8.tar.gz

cd swoole

phpize && \
./configure \
--with-php-config=/usr/local/php/bin/php-config \
--enable-openssl  \
--enable-http2  \
--enable-sockets \
--enable-mysqlnd && \
make clean && make && sudo make install
```

`php.ini 增加 extension=swoole.so`


## 配置Nginx和dev

新增nginx虚拟配置

```ini
server {
    root /home/swoolechat;
    server_name es-chat.cc;
    client_max_body_size 200m;  //上传文件大小限制
    location / {
        proxy_http_version 1.1;
        proxy_set_header Connection "keep-alive";
        proxy_set_header X-Real-IP $remote_addr;
        if (!-f $request_filename) {
             proxy_pass http://127.0.0.1:9501;
        }
    }
}
```

php上传文件配置

```ini
file_uploads = On ;打开文件上传选项 
upload_max_filesize = 500M ;上传文件上限 


如果要上传比较大的文件，仅仅以上两条还不够，必须把服务器缓存上限调大，把脚本最大执行时间变长 
post_max_size = 500M ;post上限 
max_execution_time = 0 ; 
max_input_time = 900 ; 
memory_limit = 256M ; 
```

修改虚拟机host

```ini
vi /etc/hosts
127.0.0.1 es.com     //与nginx中配置的域名一致
```


## 安装EASYSWOOLE

安装时遇到提示是否覆盖 `EasySwooleEvent.php` 请选择否 (输入 n 回车)

```bash
git clone https://git.jiruitech.com/root/swooleadmin.git
cd swooleadmin
composer install
php vendor/easyswoole/easyswoole/bin/easyswoole install
```

## 启动

```bash
php easyswoole start
```
