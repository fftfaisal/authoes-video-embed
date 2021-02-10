# === Autheos Video Embed ===
Embed authoes videos into wordpress,woocommerce site.Also Generate thumbnail from autheos from Video,EAN ID then save into wordpress and use as post thumbnail automatically.

## == Installation ==

1. Upload `autheos-video-embed.zip` to the `/wp-content/plugins/` directory
2. Unzip this zip file , You will get autheos-video-embed folder 
3. Activate the plugin through the 'Plugins' menu in WordPress
4. thats it.

## Frequently Asked Questions 

### How to use this plugin? 

simple. just use shortcode into your post.

`[autheos vid="999999999"]`  . it will display single video player into your post details page 

`[autheos eanid="99999999"]` .  It will display same video player also  it will support multiple videos . Show as slider 

`[autheos eanid="99999999" type ="button"]` . it will display button for video player. Once the button has been clicked video will popup.

`[autheos eanid="99999999" type ="thumbnail"]` . it will display a thumbnail for video player. Once the thumbnail has been clicked video will popup.


### How to generate thumbnail?

This plugin take video id / EAN id from inserted shortcode and then generate thumbnail by using that id. that thumbnail show every where into website. it wont override current post feature images. Only works for which post dont have thumbnail. Also 

### Plugin data will deleted when it self is uninstall?

Yes. It self deleted all thumbnail when you uninstall this plugin that generated for the posts.

### can I change this custom thumbnail?

Yes. 

```
add_filter('autheos_filter_thumbnail','my_theme_custom_thumnail',10,5);
function my_theme_custom_thumnail($html, $post_id, $post_thumbnail_id, $size, $attr) {
    //your custome code here 
    //for example add custom classes into thumbnail
    $class = 'your-custom-class-here your-another-custom-class-here';
    $html = str_replace('class="','class="'.$class.' ',$html);
    return $html;
}
```

add this code into your active theme functions.php file


## Changelog 

- 1.0.0 
* Initial release of this plugin.


