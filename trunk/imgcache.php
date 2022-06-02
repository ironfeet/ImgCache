<?php
/*
Plugin Name: ImgCache
Plugin URI: https://ironfeet.me/
Description: Cache the imgs from other domains.
Author: Jie Wang
Version: 0.2.1
Author URI: https://ironfeet.me/
 */

error_reporting(E_ALL^E_NOTICE^E_WARNING);

// Pre-2.6 compatibility
if( !defined('WP_CONTENT_URL') )
    define('WP_CONTENT_URL', get_option('siteurl') . '/wp-content');
if( !defined('WP_CONTENT_DIR') )
    define('WP_CONTENT_DIR', ABSPATH . 'wp-content');

define('IMGCACHEDIR', WP_CONTENT_DIR.'/imgcache/');
define('IMGCACHEURL', WP_CONTENT_URL.'/imgcache/');

require_once('Snoopy.class.php');

function checkdir()
{
    if(is_dir(IMGCACHEDIR) === FALSE)
    {
        mkdir(IMGCACHEDIR);
    }
}

function cacheimg($picURL)
{
    checkdir();
    $picURLnew=$picURL;
    $snoopy = new Snoopy;
    $snoopy->agent = 'ImgCache http://www.iron-feet.com';
    $snoopy->fetch($picURL);
    if(strpos($snoopy->response_code, '200'))
    {
        $imgtype="";
        foreach($snoopy->headers as $val)
        {
            if(strpos($val,'Content-Type') !== FALSE && strpos($val,'image') !== FALSE)
            {
                $imgtype = trim(substr($val, strpos($val, 'image') + 6));
                if(strpos($imgtype, ';') !== FALSE)
                {
                    $imgtype = trim(substr($imgtype, 0, strpos($imgtype, ';')));
                }
				$imgtype = $imgtype == 'svg+xml'? 'svg' : $imgtype;
				
                $picDIR = IMGCACHEDIR.md5($picURL) . '.' . $imgtype;
                $picURLnew = IMGCACHEURL.md5($picURL) . '.' . $imgtype;

                if(file_exists($picDIR) && date('U') - filemtime($picDIR) <= 3600)
                {
                    break;
                }
                    
                $handle = fopen($picDIR, 'w');
                fwrite($handle, $snoopy->results); 
                fclose($handle);
                break;
            }
        }
    } 
    return $picURLnew;
}

function getURL($preURL)
{
    if(strpos($preURL,'\'') === 0 || strpos($preURL, '"') === 0)
    {
        $preURL = substr($preURL, 1);
    }

    if(strrpos($preURL, '\'') === strlen($preURL) - 1 || strrpos($preURL, '"') === strlen($preURL) - 1)
    {
        $preURL = substr($preURL, 0, strlen($preURL) - 1);
    }
    return trim($preURL);
}

// inline_imgcachelink
function inline_imgcachelink($content = '') 
{
    $hostname = $_SERVER["HTTP_HOST"];

    $pattern = "/<\s*img[^<>]*imgcache4wordpress[^<>]*>/i";
    $imgcount = preg_match_all($pattern, $content, $imgs);

    if($imgcount != 0) 
    {
        foreach($imgs[0] as $img)
        {
            $imgnew = str_replace(">", " >", str_replace("/>", " />", $img));

            $pattern_src = '/(?<=src)\s*\=[\s"\']*\S*(?=[\s]*)/i';
            if(preg_match_all($pattern_src, $imgnew, $src)!=0)
            {
                $srcurl=trim(substr(trim($src[0][0]), 1));
                $srcurl=getURL($srcurl);
                
                if(preg_match_all('/^https{0,1}:\/\//i', $srcurl, $nouse)!=0)
		        //if(preg_match_all('/^http:\/\//i',$srcurl, $nouse)!=0)
                {
                    if(preg_match_all('/^https{0,1}:\/\/' . $hostname . '/i', $srcurl, $nouse) != 0)
                    //if( preg_match_all('/^http:\/\/'.$hostname.'/i', $srcurl, $nouse)!=0 )
                    {
                        continue;
                    }

                    $srcurlnew = cacheimg($srcurl);
                    
                    $imgnew = str_replace($srcurl, $srcurlnew, $imgnew);
                    $content = str_replace($img, $imgnew, $content);
                }
            }
        }
    }
    return $content;
}

// imgcache options
function imgcache_control() 
{
?>
<div class="wrap">
<?php    
    echo "<h2>" . __( 'ImgCache', '' ) . "</h2>"; 
?>
<?php    
    echo "<h4>" . __( 'Instructions', 'instruction_h4' ) . "</h4>"; 
?>    
    <table class="form-table">
        <tr>
            <td>		
<pre>
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
</pre>
            </td>
        </tr>
    </table>
</div> 
<?php
}

function imgcache_admin_actions() 
{
    add_options_page("ImgCache", "ImgCache", 1, "ImgCache", "imgcache_control");
}

add_action('admin_menu', 'imgcache_admin_actions');
add_filter('the_content', 'inline_imgcachelink');
add_filter('the_content_rss', 'inline_imgcachelink');
add_filter ('the_excerpt', 'inline_imgcachelink');
add_filter ('the_excerpt_rss', 'inline_imgcachelink');
add_filter ('widget_text', 'inline_imgcachelink');
?>
