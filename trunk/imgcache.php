<?php
/*
Plugin Name: ImgCache
Plugin URI: http://www.iron-feet.com/it/wordpress-plugin-imgcache/
Description: Cache the imgs from other domains.
Author: Iron_Feet
Version: 0.1.1
Author URI: http://www.iron-feet.com/
 */

error_reporting(E_ALL^E_NOTICE^E_WARNING);

// Pre-2.6 compatibility
if( !defined('WP_CONTENT_URL') )
    define( 'WP_CONTENT_URL', get_option('siteurl') . '/wp-content');
if( !defined('WP_CONTENT_DIR') )
    define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );

define( 'IMGCACHEDIR', WP_CONTENT_DIR.'/imgcache/' );
define( 'IMGCACHEURL', WP_CONTENT_URL.'/imgcache/' );

require_once('Snoopy.class.php');

function checkdir()
{
    if(is_dir(IMGCACHEDIR)===FALSE)
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
            if(strpos($val,'Content-Type')!==FALSE && strpos($val,'image')!==FALSE )
            {
                $imgtype=trim(substr($val,strpos($val,'image')+6));
                if(strpos($imgtype,';')!==FALSE)
                {
                    $imgtype=trim(substr($imgtype,0,strpos($imgtype,';')));
                }
                $picDIR=IMGCACHEDIR.md5($picURL).'.'.$imgtype;
                $picURLnew=IMGCACHEURL.md5($picURL).'.'.$imgtype;

                if(file_exists($picDIR) && date('U')-filemtime($picDIR)<=3600 )
                {
                    break;
                }
                    
                $handle = fopen($picDIR,'w') ;
                fwrite($handle, $snoopy->results) ; 
                fclose($handle) ;
                break;
            }
        }
    } 
    return $picURLnew;
}

function getURL($preURL)
{
    if(strpos($preURL,'\'')===0 || strpos($preURL,'"')===0)
    {
        $preURL=substr($preURL,1);
    }

    if(strrpos($preURL,'\'')===strlen($preURL)-1 || strrpos($preURL,'"')===strlen($preURL)-1)
    {
        $preURL=substr($preURL,0,strlen($preURL)-1);
    }
    return trim($preURL);
}

// inline_imgcachelink
function inline_imgcachelink($content='') 
{
    $hostname=$_SERVER["HTTP_HOST"];

    $pattern= "/<\s*img[^<>]*imgcache4wordpress[^<>]*>/i";
    $imgcount=preg_match_all($pattern,$content,$imgs);

    if( $imgcount!=0 ) 
    {
        foreach( $imgs[0] as $img )
        {
            $imgnew=$img;

            $pattern_src='/(?<=src)\s*\=[\s"\']*\S*(?=[\s]*)/i';
            if( preg_match_all($pattern_src, $img, $src)!=0 )
            {
                $srcurl=trim(substr(trim($src[0][0]),1));
                $srcurl=getURL($srcurl);
                
                //if(preg_match_all('/^https{0,1}:\/\//i',$srcurl, $nouse)!=0)
		if(preg_match_all('/^http:\/\//i',$srcurl, $nouse)!=0)
                {
                    //if( preg_match_all('/^https{0,1}:\/\/'.$hostname.'/i', $srcurl, $nouse)!=0 )
                    if( preg_match_all('/^http:\/\/'.$hostname.'/i', $srcurl, $nouse)!=0 )
                    {
                        continue;
                    }

                    $srcurlnew=cacheimg($srcurl);
                    
                    $imgnew=str_replace($srcurl,$srcurlnew,$imgnew);
                    $content=str_replace($img,$imgnew,$content);
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

Some webmasters want to show the counter of feedburner subscribers, but feedburner.com is forbidden in some countries such as China.
So we should cache some images via our servers. 

This plugin can help you cache the images easily

一些站长希望让浏览者看着自己站点的Feedburner订阅量、Twitter的Follow数量等等，Feedburner和twittercounter之类的站点刚好又提供这类统计的图片。您可以在 http://www.iron-feet.com 侧边栏看到相应的图片。
但是有些地区的网络对部分站点进行了限制，比如在中国对Feedburner和twittercounter都无法访问的。

如果我们用的是国外的服务器的话，我们可以利用服务器将这些图片进行缓存，然后展示给浏览者，因此这个插件便应运而生。
通过此插件可以简单地有选择地对图片进行缓存。

== Installation == 

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

使用：
1、在img标签中加入值为imgcache4wordpress的ref属性

比如说，
    一般情况下，我们如果想展示一个图片，就会写成
        &#60;img src=http://www.gravatar.com/avatar/27026e1c60e2659f3350af30b78565b0?s=80 /&#62;
    如果想对该图片作缓存展示的话，需要改为
        &#60;img ref=imgcache4wordpress src=http://www.gravatar.com/avatar/27026e1c60e2659f3350af30b78565b0?s=80 /&#62;

注意：
1、一幅图片如果被缓存后，再一个小时后才会被重新缓存。
2、本站图片不会被缓存，只缓存其它站点的图片。
3、如果插件无法对图片进行缓存（链接错误、无权限等等引起的），将会使用其原始URL

已知问题：
1、链接为https的图片无法被缓存（将会在新版本中解决）
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
