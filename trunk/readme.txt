=== ImgCache === 
Contributors: Jie Wang
Donate link: https://ironfeet.me/ 
Tags: cache, image, ImgCache 
Requires at least: 2.6
Tested up to: 6.0
Stable tag: 0.2.1
 
To cache the images under other domains.
为其他站点下的图片作缓存。
 
== Description == 

Some webmasters would like to show the images under some domains which are blocked in some countries such as China.
So they need to cache these kinds of images such as Feedburner subscribers counter into their hosts.

This plugin can help you cache the images easily

一些站长希望在自己的页面展示一些来自其他站点的图片。而被引用图片的站点可能无法被一些国家的网友访问到，例如中国。
所以他们需要将这类图片缓存到本地，例如 Feedburner 的用户订阅数图片

通过此插件可以方便地有选择地对图片进行缓存。
 
== Installation == 

To install: 
 
1. Put the 'imgcache' folder into your 'wp-content/plugins' folder 
 
2. Access the plugins page and activate the "imgcache"

3. Check ImgCache in settings, and read the instructions.
  
To use: 
 
1. Add the ref property whose value is imgcache4wordpress into the img tag.

For example, 

    if you would like to cache this image (http://www.gravatar.com/avatar/27026e1c60e2659f3350af30b78565b0?s=80), we can use 

        &#60;img ref=imgcache4wordpress src=http://www.gravatar.com/avatar/27026e1c60e2659f3350af30b78565b0?s=80 /&#62;

    instead of 

        &#60;img src=http://www.gravatar.com/avatar/27026e1c60e2659f3350af30b78565b0?s=80 /&#62;

Warning:

1. The images will not be cached again within one hour if their cached images exist.

2. The images under the same domain will not be cached.

3. If the images can't be cached by this plugin (such as 404, 403), the sources of the image tag remain.

安装：

1. 将 imgcache 文件夹放入 wp-content/plugins 文件夹

2. 进入插件管理页面，将 imgcache 激活

3. 在“设置”菜单选择 ImgCache，阅读说明

使用：

1. 在 img 标签中加入值为 imgcache4wordpress 的 ref 属性

比如说，

    一般情况下，我们如果想展示一个图片，就会写成

        &#60;img src=http://www.gravatar.com/avatar/27026e1c60e2659f3350af30b78565b0?s=80 /&#62;

    如果想对该图片作缓存展示的话，需要改为

        &#60;img ref=imgcache4wordpress src=http://www.gravatar.com/avatar/27026e1c60e2659f3350af30b78565b0?s=80 /&#62;

注意：

1. 一幅图片如果被缓存后，在一个小时后才会被重新缓存。

2. 本站图片不会被缓存，只缓存其它站点的图片。

3. 如果插件无法对图片进行缓存（链接错误、无权限等等引起的），将会使用其原始 URL

== Screenshots == 

1. https://ironfeet.me
 
== Changelog == 

= 0.2.1 =
* Fixed the SVG suffix issues
* Updated the description
* Updated the code format
* Enabled the HTTPS support

= 0.1.2 =
* Fixed the URL extraction error, if there is no space between src and >

= 0.1.1 =
* Removed redundancy warning

= 0.1 =
* Initial release
 
== Frequently Asked Questions == 
No questions 
 
== Feedback == 
https://github.com/ironfeet/imgcache
