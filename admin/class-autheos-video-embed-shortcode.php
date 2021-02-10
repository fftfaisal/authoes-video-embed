<?php

/**
 * The shortocde functionality of the plugin.
 *
 * @link       https://faisal.com.bd
 * @since      1.0.0
 *
 * @package    Autheos_Video_Embed
 * @subpackage Autheos_Video_Embed/admin
 */

/**
 * The shortocde functionality of the plugin.
 *
 * @package    Autheos_Video_Embed
 * @subpackage Autheos_Video_Embed/admin
 * @author     Faisal Ahmed <fftfaisal@gmail.com>
 */
class Autheos_Video_Embed_Shortcode {
    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct( $plugin_name, $version ) {
        $this->plugin_name = $plugin_name;
        $this->version     = $version;
    }

    /**
     * get youtube thubmnail from post based on youtube link
     * @param int $post_id
     * @return string $thumbnail_url
     */
    private function autheos_youtube_thumbnail_generator($post_id) {
        $content = get_post_field( 'post_content', $post_id );
        $has_video_url = preg_match( '#youtube(?:\-nocookie)?\.com/watch\?v=([A-Za-z0-9\-_]+)#', $content, $match )
            || preg_match( '#youtube(?:\-nocookie)?\.com/v/([A-Za-z0-9\-_]+)#', $content, $match ) 
            || preg_match( '#youtube(?:\-nocookie)?\.com/embed/([A-Za-z0-9\-_]+)#', $content, $match )
            || preg_match( '#youtu.be/([A-Za-z0-9\-_]+)#', $content, $match )

            || preg_match( '#vimeo\.com/([0-9]+)#', $content, $match )
            || preg_match( '#player.vimeo.com/video/([0-9]+)#', $content, $match );

        $video_url = ( $has_video_url ) ? esc_url( $match[0] ) : null;
        preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $video_url, $match);
        $youtube_id = $match[1];
        if($youtube_id == null) {
            return null;
        }
        $youtube_thumbnail = "https://img.youtube.com/vi/$youtube_id/mqdefault.jpg";
        return $youtube_thumbnail;
    }

    /**
     * Register the shortcode. [autheos]
     *
     * @since    1.0.0
     */
    public function register_autheos_shortcode() {
        add_shortcode( 'autheos', [$this,'autheos_video_generator_callback'] );
    }

    /**
     * shortcode callback function that render when shortcode fired
     *
     * @param array $attr
     * @param strin $content
     * @return mixed $data
     */
    public function autheos_video_generator_callback($attr, $content = null) {
        $attr = shortcode_atts([
            'vid'	=> '',
            'type'	=> 'inline',
            'eanid' => '',
        ],$attr,'autheos');
        extract($attr);

        //only video id based embedded player
        if ($vid) {
            $data  = '<div class="video-container-wrap">';
            $data .= '<div class="ovr-video-container">';
            $data .= "<script>";
            $data .= "Autheos.inline({
                        videoId: ['".$vid ."']
                      });";
            $data .= '</script>';
            $data .= "</div></div>";
            return $data;
        }
        // Ean id based inline multiple thumbnail carousel player
        if ($eanid && $type =="inline") { ob_start(); ?>
            <div class="video-container-wrap">
                <div class="ovr-video-container">
                    <div id="video" class="embed-responsive embed-responsive-21by9"></div>
                </div>
                <div class="autheos-thumbnail-wrapper">
                    <script defer>
                        var wrapper = document.querySelector('.autheos-thumbnail-wrap');
                        function setPreviewPlayer(ctx, video, config) {
                            var player = Autheos.createPlayer(ctx, video.id, config);
                            player.className += " embed-responsive-item";
        
                            document.getElementById("video").innerHTML = "";
                            Autheos.embedPlayer(ctx, player, "#video");
                        };
                        function previewThumbnailTemplate(ctx) {
                            var config = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {};
                            var wrapper = document.createElement("div");
                            wrapper.className +="autheos-thumbnail-wrap";
                            ctx.videos.forEach(function (video, index) {
                                var item = document.createElement('div');
                                var thumbnail = Autheos.createVideoThumbnail(video, config);
                                thumbnail.className += " autheos-videothumbnail-small";
                                thumbnail.addEventListener("click", function () {
                                    setPreviewPlayer(ctx, video, { autoplay: true });
                                });
                                if (index === 0) {
                                    setPreviewPlayer(ctx, video);
                                }
                                if(ctx.videos.length == 1) {
                                    return wrapper;
                                }
                                wrapper.appendChild(item);
                                item.appendChild(thumbnail);
                                item.className +="thumb-item";
                            });
                            return wrapper;
                        }
                        var previewInstance = new Autheos.AutheosUI({
                            templates: {
                                thumbnail: previewThumbnailTemplate
                            },
                        });
                        previewInstance.thumbnail({
                            ean: ["<?php echo $eanid ?>"],
                            language: ["nl", "en"]
                        }, {
                            showTypeLabel: false,
                            showLanguageLabel: false
                        });
                </script>       
            </div>
        </div>
        <?php return ob_get_clean(); }
        if ($eanid && $type == "popup") {
            $data = "<script>";
            $data .= "Autheos.thumbnail({
                        ean: ['".$eanid."'],
                        language: ['nl', 'en'],
                        context: 'product-page'
                     });";
            $data .= '</script>';
            return $data;
        }
        if ($eanid && $type == "button") {
    
            $data = "<script>";
            $data .= "Autheos.button({
                        ean: ['".$eanid."'],
                        language: ['nl', 'en'],
                        context: 'product-page'
                     });";
            $data .= '</script>';
            return $data;
        }
    }

    /**
     * get ean id based thumbnails from utheos api server
     *
     * @param int $ean_id
     * @param int $post_id
     * @return array|string $thumnails;
     */
    private function get_thumbnail_from_autheos_ean_id ( $ean_id, $post_id ) {
        $url = "https://query.autheos.com/v3/video";
        $payload = array(
            array(
                "ean" => array(
                    "$ean_id",
                ),
                "language"  => array(
                    "nl",
                    "en"
                ),
                "template-id"  => "thumbnail",
                "tracker-id" => array(),
                "batch-id" => "$post_id",
                "embedcode-version" => "2.5.1",
                "pageview-id" => "$post_id",
                "session-id" => "$post_id",
                "is-mobile-device" => false,
                "is-touch-device" => false,
                "local-time" => date('h:i:s'),
                "location-url" => get_the_permalink($post_id),
                "request-id" => "$post_id"
            )
        );
        //get response payload from autheos server
        $response = wp_remote_post( $url, array(
            'method'      => 'POST',
            'headers'   => array('Content-Type' => 'application/json; charset=utf-8'),
            'body'    => json_encode($payload),
        ));
        if ( is_wp_error( $response ) ) {
            //$error_message = $response->get_error_message();
            return null;
        } 
        $api_response = json_decode( wp_remote_retrieve_body( $response ), true );
        // select first video thumnail if multiple videos found.
        return $api_response[0][0]['result'][0]['thumbnail']; 
    }

    /**
     * get thumbnails from utheos api server based in single video id
     *
     * @param int $post_id
     * @return array|string $thumnails;
     */
    private function get_thumbnail_from_autheos_video_id( $video_id ) {
        if ($video_id == null || empty($video_id) ) {
            return null;
        }
        $url      = "https://query.autheos.com/v1/video/$video_id";
        $response = wp_remote_get( esc_url_raw( $url ) );

        if ( is_wp_error( $response ) ) {
            return null;
        }
        $api_response = json_decode( wp_remote_retrieve_body( $response ), true );
        if(!$api_response) {
            return null;
        }
        foreach ( $api_response['streams']  as $thumnail_data) {
            //select 720p thumbnail fitst otherwise 360p
            if( strpos($thumnail_data['url'],'720p') == false ) {
                $thumbnail_url = $thumnail_data['thumbnail'];
            } else {
                $thumbnail_url = $thumnail_data['thumbnail'];
            }
        }
        return $thumbnail_url;
    }
    /**
     * collect autheos video id or ean id and from shortcode and make api resuest for get thumbnail
     *
     * @param int $post_id
     * @return string $thumnails;
     */
    private function get_autheos_id_from_shorcode ( $post_id ) {
        $content = get_post_field( 'post_content', $post_id );
        $pattern = get_shortcode_regex();
        $code = preg_match_all( '/'. $pattern .'/s', $content, $matches ) ;
        $keys = array();
        $result = array();
        foreach( $matches[0] as $key => $value) {
            // $matches[3] return the shortcode attribute as string
            // replace space with '&' for parse_str() function
            $get = str_replace(" ", "&" , $matches[3][$key] );
            parse_str($get, $output);

            //get all shortcode attribute keys
            $keys = array_unique( array_merge(  $keys, array_keys($output)) );
            $result[] = $output;

        }
        if( $keys && $result ) {
            // Loop the result array and add the missing shortcode attribute key
            foreach ($result as $key => $value) {
                // Loop the shortcode attribute key
                foreach ($keys as $attr_key) {
                    $result[$key][$attr_key] = isset( $result[$key][$attr_key] ) ? $result[$key][$attr_key] : NULL;
                }
                //sort the array key
                ksort( $result[$key]);              
            }
        }
        $video_id = str_replace('"', "", $result[0]['vid'] );
        
        $ean_id = str_replace('"', "", $result[0]['eanid'] );

        // if both are not found so use feature image or return null for using no thumbail image.
        $feature_image = get_the_post_thumbnail_url($post_id);
        if($feature_image) {
            return $feature_image;
        }
        // first checking it is single video or not
        if( $video_id != null || !empty($video_id) ) {
            $video_thumbnail_url = $this->get_thumbnail_from_autheos_video_id( $video_id ) ;
            if ( $video_thumbnail_url ) {
                return $video_thumbnail_url;
            }
        }
        
        // 2nd checking ean or not
        if ( $ean_id != null || !empty($ean_id) ) {
            $ean_thumbnail_url = $this->get_thumbnail_from_autheos_ean_id($ean_id, $post_id);
            if ($ean_thumbnail_url) {
                return $ean_thumbnail_url;
            }
        }
        //return null;
    }

    /**
     * final function for getting thumbnail from post id
     *
     * @param int $id
     * @return string thumbnail
     */
    private function autheos_get_post_thumbnail_url($post_id) {
        
        $youtube_thumnail = $this->autheos_youtube_thumbnail_generator($post_id);
        $autheos_thumnail = $this->get_autheos_id_from_shorcode($post_id);
        
        if ( $youtube_thumnail == null  && $autheos_thumnail == null ) {
            $thumbnail_url = plugin_dir_url( dirname( __FILE__ ) ) . 'public/images/no-video-thumbnail.jpg';
            return $thumbnail_url;
        }

        if ($youtube_thumnail) return $youtube_thumnail;
    
        if($autheos_thumnail) return $autheos_thumnail;

    
    }

    /**
     * set autheos_thumbnail_url for that post
     * @param string $html
     * @return string $html
     */
    public function autheos_set_post_thumnail( $object,$wp_query ) {
        $object_id = get_the_id($object);
        // Only affect thumbnails on the frontend, do allow ajax calls.
		if ( ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) ) {
			return;
		}

		// Check only empty meta_key and '_thumbnail_id'.
        $thumbnail_id = get_post_meta($object_id,'_thumbnail_id',true);
        $authoes_thumbnail_id = get_post_meta($object_id,'_authoes_thumbnail_id',true);
		if ( !empty($authoes_thumbnail_id) ) {
			return;
		}

		// Check if this post type supports featured images.
		if ( ! post_type_supports( get_post_type( $object ), 'thumbnail' ) ) {
			return; // post type does not support featured images.
		}
        //set fake thumbail for emthy thumbnail post
        update_post_meta($object_id,'_thumbnail_id',-1);
        $url =  $this->autheos_get_post_thumbnail_url( $object_id );
        // seve autheos thubnail as post meta into database
        update_post_meta($object_id,'_authoes_thumbnail_id',$url);
    }

    /**
     * show feature image / authoes video image as post thumbnal in the intire site
     * @param string $html
     * @return string $html
     */
    public function autheos_show_post_thumnail( $html,$post_id,$post_thumbnail_id,$size,$attr ) {
        $authoes_thumbnail_id = get_post_meta($post_id,'_authoes_thumbnail_id',true);
        //var_dump($authoes_thumbnail_id);
        //checking if image is empty and display autheos image
        if( !empty($authoes_thumbnail_id) && empty($html) ) {
            $html = sprintf( '<img class="autheos-video-image" src="%s" alt="%s"/>', $authoes_thumbnail_id, get_the_title($post_id) );
        }
        else {
            //add a custom class into exsiting image
            $class = 'autheos-video-image';
            $html = str_replace('class="','class="'.$class.' ',$html);
        }
        
        /**
         * user can still change use their custom thumbnail by adding filter hook
         * usage: 
         * add_filter('autheos_filter_thumbnail','my_theme_custom_thumnail',10,5);
         * function my_theme_custom_thumnail($html, $post_id, $post_thumbnail_id, $size, $attr) {
         *      custome code here 
         *      return $html;
         * }
         * add this code into your active theme functions.php file 
         */
        $html = apply_filters('autheos_filter_thumbnail', $html, $post_id, $post_thumbnail_id, $size, $attr);
        return $html;
    }
}
