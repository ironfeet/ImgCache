=== ImgCache === 
Contributors: Iron_Feet
Donate link: http://www.iron-feet.com/ 
Tags: cache, image, ImgCache 
Requires at least: 2.6
Tested up to: 2.9
Stable tag: 0.1.1
 
Cache the imgs from other domains.
为其他站点下的图片作缓存。
 
== Description == 
 
Some webmasters want to show the counter of feedburner subscribers, but feedburner.com is forbidden in some countries such as China.
So we should cache some images via our servers. 

This plugin can help you cache the images easily

一些站长希望让浏览者看着自己站点的Feedburner订阅量、Twitter的Follow数量等等，Feedburner和twittercounter之类的站点刚好又提供这类统计的图片。您可以在 http://www.iron-feet.com 侧边栏看到相应的图片。
但是有些地区的网络对部分站点进行了限制，比如在中国对Feedburner和twittercounter都无法访问的。

如果我们用的是国外的服务器的话，我们可以利用服务器将这些图片进行缓存，然后展示给浏览者，因此这个插件便应运而生。
通过此插件可以简单地有选择地对图片进行缓存。
 
== Installation == 

To install: 
 
1. Drop the 'imgcache' folder into your 'wp-content/plugins' folder 
 
2. Plugins page and activate the "imgcache"

3. Settings: ImgCache , and read the instructions.
  
To use: 
 
1. Add the ref property whose value is imgcache4wordpress into the img tag.

For example, 

    if you wanna cache the pic ( http://www.gravatar.com/avatar/27026e1c60e2659f3350af30b78565b0?s=80 ), we can use 

        &#60;img ref=imgcache4wordpress src=http://www.gravatar.com/avatar/27026e1c60e2659f3350af30b78565b0?s=80 /&#62;

    instead of 

        &#60;img src=http://www.gravatar.com/avatar/27026e1c60e2659f3350af30b78565b0?s=80 /&#62;

Warning:

1. The imgs will not be recache in one hour, if their cached imgs exist.

2. The imgs in own sites will not be cached.

3. If the imgs cann't be cache by this plugin (such as 404, 403), the original url of the imgs will be used.

Known issues:

1. imgs via https are not supported.(It will be solved in next version)

安装：

1. 将imgcache文件夹放入wp-content/plugins文件夹

2. 进入插件管理页面，将imgcache激活

3. 在“设置”菜单选择ImgCache，阅读说明

使用：

1. 在img标签中加入值为imgcache4wordpress的ref属性

比如说，

    一般情况下，我们如果想展示一个图片，就会写成

        &#60;img src=http://www.gravatar.com/avatar/27026e1c60e2659f3350af30b78565b0?s=80 /&#62;

    如果想对该图片作缓存展示的话，需要改为

        &#60;img ref=imgcache4wordpress src=http://www.gravatar.com/avatar/27026e1c60e2659f3350af30b78565b0?s=80 /&#62;

注意：

1. 一幅图片如果被缓存后，在一个小时后才会被重新缓存。

2. 本站图片不会被缓存，只缓存其它站点的图片。

3. 如果插件无法对图片进行缓存（链接错误、无权限等等引起的），将会使用其原始URL

已知问题：

1. 链接为https的图片无法被缓存（将会在新版本中解决）

== Screenshots == 

1. http://www.iron-feet.com
 
== Changelog == 

= 0.1.1
* remove redundancy warning

= 0.1 =
* initial release 
 
== Frequently Asked Questions == 
No questions 
 
== Feedback == 
http://www.iron-feet.com/it/wordpress-plugin-imgcache/
