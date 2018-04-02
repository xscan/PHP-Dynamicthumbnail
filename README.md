## php动态生成缩略图
暂时支持jpg，png图片格式转换
### 项目结构

        |   1.ttf //字体文件
        |   nginx.conf //nginx配置文件
        |   README.md
        |   resize_gd.php //gd版本
        |   resize_imagick.php //imagemarkic版本
        |
        \---uploads
            +---images //源图片路径
            |       1.jpg
            |       2.jpg
            |
            \---thumbs /缩略图片路径
                    1_11000x11000.jpg


### 使用方法
1. 修改nginx.conf配置文件
2. 加入项目nginx.conf文件到自己的nginx服务器配置
3. 部署项目到web服务器，重启nginx，让修改的配置文件生效（默认为根路径，如修改路径请修改nginx.conf的url重写路径）
4. 测试http://127.0.0.1/uploads/1_500x500.jpg（生成一张./uploads/images/1.jpg,500x500大小的缩略图，假如没有生成一张有500x500字符的图片）