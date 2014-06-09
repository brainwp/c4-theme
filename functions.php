<?php
// ******************* Add Libraries ****************** //
require_once('lib/facebook/facebook.php');
require_once('lib/twitter.php');
require_once('lib/taxonomy-meta.php');
require_once('lib/geocode.php');
require_once('lib/recaptchalib.php');

$facebookAppID = get_option('fudge_facebook_rsvp_widget_appid');
$facebookSecret = get_option('fudge_facebook_rsvp_widget_secret');

if (!empty($facebookAppID) && !empty($facebookSecret))
    $facebook = new Facebook(array(
                'appId' => $facebookAppID,
                'secret' => $facebookSecret,
            ));

$twitterAccessToken = get_option('fudge_twitter_widget_accesstoken');
$twitterAccessTokenSecret = get_option('fudge_twitter_widget_accesstokensecret');
$twitterConsumerKey = get_option('fudge_twitter_widget_consumerkey');
$twitterConsumerSecret = get_option('fudge_twitter_widget_consumersecret');

if (!empty($twitterAccessToken) && !empty($twitterAccessTokenSecret) && !empty($twitterConsumerKey) && !empty($twitterConsumerSecret)) {
    $twitter = new TwitterAPIExchange(array(
                'oauth_access_token' => $twitterAccessToken,
                'oauth_access_token_secret' => $twitterAccessTokenSecret,
                'consumer_key' => $twitterConsumerKey,
                'consumer_secret' => $twitterConsumerSecret
            ));
}

add_action('after_setup_theme', 'fudge_after_theme_setup');

function fudge_after_theme_setup() {

// ******************* Localizations ****************** //
    load_theme_textdomain('fudge', get_template_directory() . '/lang');

// ******************* Add Custom Menus ****************** //    
    add_theme_support('menus');

// ******************* Add Post Thumbnails ****************** //
    add_theme_support('post-thumbnails');
    set_post_thumbnail_size(222, 222, true);
    add_image_size('blog', 306, 306, true);
}



// ******************* Scripts and Styles ****************** //
add_action('wp_enqueue_scripts', 'fudge_enqueue_scripts');

function fudge_enqueue_scripts() {
    wp_deregister_script('jquery');
    wp_register_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js', false, false, false);
    wp_enqueue_script('jquery');
    wp_enqueue_script('fudge-script', get_stylesheet_directory_uri() . '/js/script.js', array('jquery'), false, true);
    wp_localize_script('fudge-script', 'fudge_script_vars', array(
        'closewindow' => __('Close Window', 'fudge'),
        'location' => __('LOCATION:', 'fudge'),
        'date' => __('DATE:', 'fudge'),
        'time' => __('TIME:', 'fudge'),
        'company' => __('Company:', 'fudge'),
        'shortbio' => __('Short Bio:', 'fudge'),
        'website' => __('Website:', 'fudge'),
        'twitter' => __('Twitter:', 'fudge'),
        'sessions' => __('Sessions', 'fudge'),
        'editlink' => __('Edit', 'fudge'),
        'contact_fieldmissing' => __('This field must be filled out.', 'fudge'),
        'contact_invalidemail' => __('Sorry! You\'ve entered an invalid email.', 'fudge'),
        'contact_mailok' => __('<strong>Thanks!</strong> Your email has been delivered.', 'fudge'),
        'contact_mailko' => __('<strong>Sorry!</strong> Your email has not been delivered.', 'fudge'),
            )
    );
    if (is_singular())
        wp_enqueue_script('comment-reply');
    wp_enqueue_script('add-this', '//s7.addthis.com/js/300/addthis_widget.js#pubid=xa-519b89de066e1d5e', false, false, true);
    wp_enqueue_script('fudge-plugins', get_stylesheet_directory_uri() . '/js/plugins.js', false, false, true);
    if (is_front_page() || is_page_template('page-event-over.php'))
        wp_enqueue_script('fudge-home', get_stylesheet_directory_uri() . '/js/home.js', array('jquery'), false, true);
    wp_enqueue_script('google-maps', 'http://maps.google.com/maps/api/js?sensor=true', false, false, true);
    wp_enqueue_script('jquery-ui-map', get_stylesheet_directory_uri() . '/js/jquery.ui.map.full.min.js', array('jquery'), false, true);
    wp_enqueue_script('jquery-tweet-machine', get_stylesheet_directory_uri() . '/js/tweetMachine.min.js', array('jquery'), false, true);
    wp_enqueue_script('modernizr', get_stylesheet_directory_uri() . '/js/libs/modernizr-2.5.0.min.js', false, false, false);
    wp_enqueue_script('fudge-countdown', get_stylesheet_directory_uri() . '/js/countdown.js', array('jquery'), false, true);
	// Chamando o LigthBox Magnific!
	wp_enqueue_script( 'jquery.magnific-popup', get_stylesheet_directory_uri() . '/js/jquery.magnific-popup.js', array('jquery') );
	wp_enqueue_style( 'magnific-popup', get_stylesheet_directory_uri() . '/js/magnific-popup.css' );
}

add_action('admin_enqueue_scripts', 'fudge_admin_enqueue_scripts');

function fudge_admin_enqueue_scripts($hook) {
    global $post_type;

    if (!in_array($hook, array('post.php', 'post-new.php')) || $post_type != 'session')
        return;
    wp_enqueue_script('jquery-ui-datepicker');
    wp_enqueue_style('jquery-ui-datepicker', get_stylesheet_directory_uri() . '/css/datepicker/smoothness/jquery-ui-1.10.3.custom.min.css');
}

add_action('wp_head', 'fudge_frontend_scripts');

function fudge_frontend_scripts() {
    $countdown_expiration = 0;
    $timerdate = get_option('fudge_timer_widget_timerdate');
    if (!empty($timerdate))
        $countdown_expiration = $timerdate;
    ?>
    <script type="text/javascript">
        var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
    var schedule_limit = '<?php echo get_schedule_limit(); ?>';
    var media_limit = '<?php echo get_media_limit(); ?>';
    var speakers_limit=  '<?php echo get_speakers_limit(); ?>';
    var countdown_expiration = <?php echo $countdown_expiration; ?>;
    var mobile_width = 460;
    </script>
    <?php
}

// ******************* Sidebars ****************** //

if (function_exists('register_sidebar')) {
    register_sidebar(array(
        'name' => 'Homepage',
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '',
        'after_title' => '',
    ));

    register_sidebar(array(
        'name' => 'Blog',
        'before_widget' => '<li id="%1$s" class="widget %2$s">',
        'after_widget' => '</li>',
        'before_title' => '<h2>',
        'after_title' => '</h2>',
    ));
}

// ******************* Add Custom Post Types & Taxonomies ****************** //


register_post_type('sponsors', array(
    'labels' => array(
        'name' => __('Sponsors', 'fudge'),
        'singular_name' => __('Sponsor', 'fudge'),
        'add_new' => __('Add New', 'fudge'),
        'add_new_item' => __('Add New Sponsor', 'fudge'),
        'edit_item' => __('Edit Sponsor', 'fudge'),
        'new_item' => __('New Sponsor', 'fudge'),
        'view_item' => __('View Sponsor', 'fudge'),
        'search_items' => __('Search Sponsors', 'fudge'),
        'not_found' => __('No Sponsors found', 'fudge'),
        'not_found_in_trash' => __('No Sponsors found in trash', 'fudge'),
        'menu_name' => __('Sponsors', 'fudge'),
    ),
    'public' => true,
    'show_ui' => true,
    'capability_type' => 'post',
    'hierarchical' => false,
    'rewrite' => true,
    'query_var' => false,
    'supports' => array('title', 'author', 'thumbnail')
));

register_post_type('event-media', array(
    'labels' => array(
        'name' => __('Event Media', 'fudge'),
        'singular_name' => __('Event Media', 'fudge'),
        'add_new' => __('Add New', 'fudge'),
        'add_new_item' => __('Add New Event Media', 'fudge'),
        'edit_item' => __('Edit Event Media', 'fudge'),
        'new_item' => __('New Event Media', 'fudge'),
        'view_item' => __('View Event Media', 'fudge'),
        'search_items' => __('Search Event Media', 'fudge'),
        'not_found' => __('No Event Media found', 'fudge'),
        'not_found_in_trash' => __('No Event Media found in trash', 'fudge'),
        'menu_name' => __('Event Media', 'fudge'),
    ),
    'public' => true,
    'show_ui' => true,
    'capability_type' => 'post',
    'hierarchical' => false,
    'rewrite' => true,
    'query_var' => false,
    'supports' => array('title', 'editor', 'thumbnail')
));

register_post_type('poi', array(
    'labels' => array(
        'name' => __('Points of Interest', 'fudge'),
        'singular_name' => __('Point of Interest', 'fudge'),
        'add_new' => __('Add New', 'fudge'),
        'add_new_item' => __('Add New Point of Interest', 'fudge'),
        'edit_item' => __('Edit Point of Interest', 'fudge'),
        'new_item' => __('New Point of Interest', 'fudge'),
        'view_item' => __('View Point of Interest', 'fudge'),
        'search_items' => __('Search Points of Interest', 'fudge'),
        'not_found' => __('No Points of Interest found', 'fudge'),
        'not_found_in_trash' => __('No Points of Interest found in trash', 'fudge'),
        'menu_name' => __('Points of Interest', 'fudge'),
    ),
    'public' => true,
    'show_ui' => true,
    'capability_type' => 'post',
    'hierarchical' => false,
    'rewrite' => true,
    'query_var' => false,
    'supports' => array('title', 'editor', 'page-attributes')
));

register_post_type('speaker', array(
    'labels' => array(
        'name' => __('Speakers', 'fudge'),
        'singular_name' => __('Speaker', 'fudge'),
        'add_new' => __('Add New', 'fudge'),
        'add_new_item' => __('Add New Speaker', 'fudge'),
        'edit_item' => __('Edit Speaker', 'fudge'),
        'new_item' => __('New Speaker', 'fudge'),
        'view_item' => __('View Speaker', 'fudge'),
        'search_items' => __('Search Speakers', 'fudge'),
        'not_found' => __('No Speakers found', 'fudge'),
        'not_found_in_trash' => __('No Speakers found in trash', 'fudge'),
        'menu_name' => __('Speakers', 'fudge'),
    ),
    'public' => true,
    'publicly_queryable' => true,
    'show_ui' => true,
    'show_in_menu' => true,
    'query_var' => true,
    'rewrite' => array('slug' => 'speakers'),
    'capability_type' => 'post',
    'has_archive' => false,
    'hierarchical' => false,
    'menu_position' => 5,
    'supports' => array('title', 'editor', 'thumbnail', 'page-attributes')
));

register_post_type('session', array(
    'labels' => array(
        'name' => __('Sessions', 'fudge'),
        'singular_name' => __('Session', 'fudge'),
        'add_new' => __('Add New', 'fudge'),
        'add_new_item' => __('Add New Session', 'fudge'),
        'edit_item' => __('Edit Session', 'fudge'),
        'new_item' => __('New Session', 'fudge'),
        'view_item' => __('View Session', 'fudge'),
        'search_items' => __('Search Sessions', 'fudge'),
        'not_found' => __('No Sessions found', 'fudge'),
        'not_found_in_trash' => __('No Sessions found in trash', 'fudge'),
        'menu_name' => __('Sessions', 'fudge'),
    ),
    'public' => true,
    'publicly_queryable' => true,
    'show_ui' => true,
    'show_in_menu' => true,
    'query_var' => true,
    'rewrite' => array('slug' => 'sessions'),
    'capability_type' => 'post',
    'has_archive' => false,
    'hierarchical' => false,
    'menu_position' => 5,
    'supports' => array('title', 'editor', 'thumbnail')
));

add_action('init', 'build_taxonomies', 0);

function build_taxonomies() {
    register_taxonomy('sponsor-tier', 'sponsors', array(
        'hierarchical' => true,
        'labels' => array(
            'name' => __('Tiers', 'fudge'),
            'singular_name' => __('Tier', 'fudge'),
            'search_items' => __('Search Tiers', 'fudge'),
            'all_items' => __('All Tiers', 'fudge'),
            'parent_item' => __('Parent Tier', 'fudge'),
            'parent_item_colon' => __('Parent Tier:', 'fudge'),
            'edit_item' => __('Edit Tier', 'fudge'),
            'update_item' => __('Update Tier', 'fudge'),
            'add_new_item' => __('Add New Tier', 'fudge'),
            'new_item_name' => __('New Tier', 'fudge'),
            'menu_name' => __('Tiers', 'fudge'),
        ),
        'query_var' => true,
        'rewrite' => true)
    );
    register_taxonomy('media-type', 'event-media', array(
        'hierarchical' => true,
        'labels' => array(
            'name' => __('Media Types', 'fudge'),
            'singular_name' => __('Media Type', 'fudge'),
            'search_items' => __('Search Media Types', 'fudge'),
            'all_items' => __('All Media Types', 'fudge'),
            'parent_item' => __('Parent Media Type', 'fudge'),
            'parent_item_colon' => __('Parent Media Type:', 'fudge'),
            'edit_item' => __('Edit Media Type', 'fudge'),
            'update_item' => __('Update Media Type', 'fudge'),
            'add_new_item' => __('Add New Media Type', 'fudge'),
            'new_item_name' => __('New Media Type', 'fudge'),
            'menu_name' => __('Media Types', 'fudge'),
        ),
        'query_var' => true,
        'rewrite' => true)
    );
    register_taxonomy('session-track', 'session', array(
        'hierarchical' => true,
        'labels' => array(
            'name' => __('Session Tracks', 'fudge'),
            'singular_name' => __('Session Track', 'fudge'),
            'search_items' => __('Search Session Tracks', 'fudge'),
            'all_items' => __('All Session Tracks', 'fudge'),
            'parent_item' => __('Parent Session Track', 'fudge'),
            'parent_item_colon' => __('Parent Session Track:', 'fudge'),
            'edit_item' => __('Edit Session Track', 'fudge'),
            'update_item' => __('Update Session Track', 'fudge'),
            'add_new_item' => __('Add New Session Track', 'fudge'),
            'new_item_name' => __('New Session Track', 'fudge'),
            'menu_name' => __('Tracks', 'fudge'),
        ),
        'query_var' => true,
        'rewrite' => true)
    );
    register_taxonomy('session-location', 'session', array(
        'hierarchical' => true,
        'labels' => array(
            'name' => __('Session Locations', 'fudge'),
            'singular_name' => __('Session Location', 'fudge'),
            'search_items' => __('Search Session Locations', 'fudge'),
            'all_items' => __('All Session Locations', 'fudge'),
            'parent_item' => __('Parent Session Location', 'fudge'),
            'parent_item_colon' => __('Parent Session Location:', 'fudge'),
            'edit_item' => __('Edit Session Location', 'fudge'),
            'update_item' => __('Update Session Location', 'fudge'),
            'add_new_item' => __('Add New Session Location', 'fudge'),
            'new_item_name' => __('New Session Location', 'fudge'),
            'menu_name' => __('Locations', 'fudge'),
        ),
        'query_var' => true,
        'rewrite' => true)
    );

    new RW_Taxonomy_Meta(array(
                'id' => 'session-track-metas',
                'taxonomies' => array('session-track'),
                'fields' => array(
                    array(
                        'name' => __('Color', 'fudge'),
                        'id' => 'session_track_color',
                        'type' => 'color'
                    )
                )
                    )
    );

    new RW_Taxonomy_Meta(array(
                'id' => 'session-location-metas',
                'taxonomies' => array('session-location'),
                'fields' => array(
                    array(
                        'name' => __('Color', 'fudge'),
                        'id' => 'session_location_color',
                        'type' => 'color'
                    )
                )
                    )
    );
}

// ******************* Add Custom Meta Boxes ****************** //

add_action('add_meta_boxes', 'fudge_metaboxes');

function fudge_metaboxes() {
    $post_id = $_GET['post'] ? $_GET['post'] : $_POST['post_ID'];

    add_meta_box('metabox-media', __('Media Content', 'fudge'), 'fudge_metabox_media', 'event-media', 'normal', 'high');
    add_meta_box('metabox-additional-content', __('Additional Content', 'fudge'), 'fudge_metabox_additional_content', 'page', 'normal', 'high');
    add_meta_box('metabox-poi', __('POI Address Info', 'fudge'), 'fudge_metabox_poi', 'poi', 'normal', 'high');
    add_meta_box('metabox-speaker', __('Speaker Details', 'fudge'), 'fudge_metabox_speaker', 'speaker', 'normal', 'high');
    add_meta_box('metabox-session', __('Session Details', 'fudge'), 'fudge_metabox_session', 'session', 'normal', 'high');
    add_meta_box('metabox-session-speakers', __('Speakers', 'fudge'), 'fudge_metabox_session_speakers', 'session', 'normal', 'high');
    add_meta_box('metabox-sponsor', __('Sponsor Details', 'fudge'), 'fudge_metabox_sponsor', 'sponsors', 'normal', 'high');

    $template_file = get_post_meta($post_id, '_wp_page_template', TRUE);
    if ($template_file == 'page-event-over.php') {
        add_meta_box('metabox-event-over', __('Event Over Content', 'fudge'), 'fudge_metabox_event_over', 'page', 'normal', 'high');
    }
}

function fudge_metabox_media($post) {
    $video_url = get_post_meta($post->ID, 'video_url', true);
    ?>
    <p>
        <label for="video_url"><?php _e('Video URL', 'fudge'); ?></label>
        <input type="text" class="widefat" id="video_url" name="video_url" value="<?php echo $video_url; ?>" />
    </p>    
    <?php
}

function fudge_metabox_additional_content($post) {
    $tagline = get_post_meta($post->ID, 'tagline', true);
    ?>
    <p>
        <label for="tagline"><?php _e('Top Tagline', 'fudge'); ?></label>
        <input type="text" class="widefat" id="tagline" name="tagline" value="<?php echo $tagline; ?>" />
    </p>    
    <?php
}

function fudge_metabox_event_over($post) {
    $heading = get_post_meta($post->ID, 'heading', true);
    $tagline = get_post_meta($post->ID, 'tagline', true);
    ?>
    <p>
        <label for="heading"><?php _e('Heading', 'fudge'); ?></label>
        <input type="text" class="widefat" id="heading" name="heading" value="<?php echo $heading; ?>" />
    </p>    
    <p>
        <label for="tagline"><?php _e('Top Tagline', 'fudge'); ?></label>
        <input type="text" class="widefat" id="tagline" name="tagline" value="<?php echo $tagline; ?>" />
    </p>
    <?php
}

function fudge_metabox_sponsor($post) {
	$details = get_post_meta($post->ID, 'details', true);
    $phone = get_post_meta($post->ID, 'phone', true);
    $email = get_post_meta($post->ID, 'email', true);
    $link = get_post_meta($post->ID, 'link', true);
    ?>

    <p>
    	<label for="details"><?php _e('Description', 'fudge'); ?></label><br />
        <textarea name="details" class="widefat" style="width:100% !important;height:80px !important;" ><?php echo $details; ?></textarea>
    </p>

    <p>
        <label for="phone"><?php _e('Phone', 'fudge'); ?></label>
        <input type="text" class="widefat" id="phone" name="phone" value="<?php echo $phone; ?>" />
    </p>
    <p>
        <label for="email"><?php _e('Email', 'fudge'); ?></label>
        <input type="text" class="widefat" id="email" name="email" value="<?php echo $email; ?>" />
    </p>

    <p>
        <label for="link"><?php _e('Link', 'fudge'); ?></label>
        <input type="text" class="widefat" id="link" name="link" value="<?php echo $link; ?>" />
    </p>
    <?php
}

function fudge_metabox_poi($post) {
    $street_address_1 = get_post_meta($post->ID, 'street_address_1', true);
    $street_address_2 = get_post_meta($post->ID, 'street_address_2', true);
    $city = get_post_meta($post->ID, 'city', true);
    $postal_code = get_post_meta($post->ID, 'postal_code', true);
    $country = get_post_meta($post->ID, 'country', true);
    ?>
    <p>
        <label for="street_address_1"><?php _e('Street Address 1', 'fudge'); ?></label>
        <input type="text" class="widefat" id="street_address_1" name="street_address_1" value="<?php echo $street_address_1; ?>" />
    </p>   
    <p>
        <label for="street_address_2"><?php _e('Street Address 2', 'fudge'); ?></label>
        <input type="text" class="widefat" id="street_address_2" name="street_address_2" value="<?php echo $street_address_2; ?>" />
    </p>
    <p>
        <label for="city"><?php _e('City', 'fudge'); ?></label>
        <input type="text" class="widefat" id="city" name="city" value="<?php echo $city; ?>" />
    </p>
    <p>
        <label for="postal_code"><?php _e('Postal Code / Zip Code', 'fudge'); ?></label>
        <input type="text" class="widefat" id="postal_code" name="postal_code" value="<?php echo $postal_code; ?>" />
    </p>
    <p>
        <label for="country"><?php _e('Country', 'fudge'); ?></label>
        <input type="text" class="widefat" id="country" name="country" value="<?php echo $country; ?>" />
    </p>
    <?php
}

function fudge_metabox_speaker($post) {
    $company = get_post_meta($post->ID, 'company', true);
    $short_bio = get_post_meta($post->ID, 'short_bio', true);
    $website_url = get_post_meta($post->ID, 'website_url', true);
    $twitter_username = get_post_meta($post->ID, 'twitter_username', true);
    ?>
    <p>
        <label for="company"><?php _e('Company', 'fudge'); ?></label>
        <input type="text" class="widefat" id="company" name="company" value="<?php echo $company; ?>" />
    </p>   
    <p>
        <label for="short_bio"><?php _e('Short Bio', 'fudge'); ?></label>
        <input type="text" class="widefat" id="short_bio" name="short_bio" value="<?php echo $short_bio; ?>" />
    </p>

    <p>
        <label for="website_url">Website Url</label>
        <input type="text" class="widefat" id="website_url" name="website_url" value="<?php echo $website_url; ?>" />
    </p>
    <p>
        <label for="twitter_username"><?php _e('Twitter Username', 'fudge'); ?></label>
        <input type="text" class="widefat" id="twitter_username" name="twitter_username" value="<?php echo $twitter_username; ?>" />
    </p>
    <?php
}

function fudge_metabox_session($post) {
    $date = get_post_meta($post->ID, 'date', true);
    $time = get_post_meta($post->ID, 'time', true);
    $end_time = get_post_meta($post->ID, 'end_time', true);
    ?>
    <script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery('#date_str').datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'mm/dd/yy',
            altFormat: 'yy-mm-dd',
            altField: '#date'
        });
    });
    </script>
    <p>
        <label for="date"><?php _e('Date', 'fudge'); ?></label>
        <input type="text" id="date_str"  value="<?php echo!empty($date) ? date('m/d/Y', timestamp_fix($date)) : ''; ?>" />
        <input type="hidden" id="date" name="date" value="<?php echo!empty($date) ? date('Y-m-d', timestamp_fix($date)) : ''; ?>" />
    </p>   
    <p>
        <label for="time"><?php _e('Start Time', 'fudge'); ?></label>
        <input type="text" id="time" name="time" value="<?php echo $time; ?>" />
        <span><?php _e('Format hh:mm', 'fudge'); ?></span>
    </p>
    <p>
        <label for="time"><?php _e('End Time', 'fudge'); ?></label>
        <input type="text" id="end_time" name="end_time" value="<?php echo $end_time; ?>" />
        <span><?php _e('Format hh:mm', 'fudge'); ?></span>
    </p>
    <?php
}

function fudge_metabox_session_speakers($post) {
    $speakers = get_posts(array('post_type' => 'speaker', 'post_status' => 'publish', 'suppress_filters' => false, 'posts_per_page' => -1, 'orderby' => 'title', 'order' => 'ASC'));
    $associated_speakers = get_post_meta($post->ID, 'speakers_list', true);
    ?>
    <ul>
        <?php
        foreach ($speakers as $speaker) {
            $checked = '';
            if (!empty($associated_speakers) && count($associated_speakers) > 0 && in_array($speaker->ID, $associated_speakers))
                $checked = 'checked="checked"';
            ?>
            <li>
                <input type="checkbox" name="speakers_list[]" id="speakers_list_<?php echo $speaker->ID; ?>" value="<?php echo $speaker->ID; ?>" <?php echo $checked; ?> />
                <label><?php echo $speaker->post_title; ?></label>
            </li>
            <?php
        }
        ?>
    </ul>
    <?php
}

add_action('save_post', 'fudge_save_post');

function fudge_save_post($id) {

    if (isset($_POST['video_url']))
        update_post_meta($id, 'video_url', $_POST['video_url']);

    if (isset($_POST['tagline']))
        update_post_meta($id, 'tagline', $_POST['tagline']);

    if (isset($_POST['heading']))
        update_post_meta($id, 'heading', $_POST['heading']);

    if (isset($_POST['country']))
        update_post_meta($id, 'country', $_POST['country']);

    if (isset($_POST['postal_code']))
        update_post_meta($id, 'postal_code', $_POST['postal_code']);

    if (isset($_POST['city']))
        update_post_meta($id, 'city', $_POST['city']);

    if (isset($_POST['street_address_2']))
        update_post_meta($id, 'street_address_2', $_POST['street_address_2']);

    if (isset($_POST['street_address_1']))
        update_post_meta($id, 'street_address_1', $_POST['street_address_1']);

    if (isset($_POST['company']))
        update_post_meta($id, 'company', $_POST['company']);

    if (isset($_POST['short_bio']))
        update_post_meta($id, 'short_bio', $_POST['short_bio']);

    if (isset($_POST['website_url']))
        update_post_meta($id, 'website_url', $_POST['website_url']);

    if (isset($_POST['twitter_username']))
        update_post_meta($id, 'twitter_username', $_POST['twitter_username']);

    if (isset($_POST['date']))
        update_post_meta($id, 'date', strtotime($_POST['date']) * 1000);

    if (isset($_POST['time']))
        update_post_meta($id, 'time', $_POST['time']);

    if (isset($_POST['end_time']))
        update_post_meta($id, 'end_time', $_POST['end_time']);

    if (isset($_POST['speakers_list']))
        update_post_meta($id, 'speakers_list', $_POST['speakers_list']);

    if (isset($_POST['street_address_1'])) {
        $location = Geocoder::getLocation(sprintf('%s %s, %s, %s, %s', $_POST['street_address_1'], $_POST['street_address_2'], $_POST['city'], $_POST['postal_code'], $_POST['country']));
        if ($location !== false) {
            update_post_meta($id, 'lat', $location['lat']);
            update_post_meta($id, 'lng', $location['lng']);
        }
    }

    if (isset($_POST['link']))
        update_post_meta($id, 'link', $_POST['link']);
		
    if (isset($_POST['phone']))
        update_post_meta($id, 'phone', $_POST['phone']);
		
    if (isset($_POST['email']))
        update_post_meta($id, 'email', $_POST['email']);
		
    if (isset($_POST['details']))
        update_post_meta($id, 'details', $_POST['details']);
}

// ******************* Add Options to Theme Customizer ****************** //


function fudge_customize_register($wp_customize) {

    /*     * ****** SITE OPTIONS********** */

    $wp_customize->add_section('fudge_colors', array(
        'title' => __('General Site Options', 'fudge'),
        'priority' => 130,
    ));

// Color Palette

    $wp_customize->add_setting('fudge_theme_options[color_palette]', array(
        'default' => 'default',
        'capability' => 'edit_theme_options',
        'type' => 'option',
    ));

    $wp_customize->add_control('color_palette', array(
        'label' => __('Color Palette', 'fudge'),
        'section' => 'fudge_colors',
        'settings' => 'fudge_theme_options[color_palette]',
        'type' => 'select',
        'choices' => array(
            'default' => 'Default',
            'corporate-1' => 'Corporate 1',
            'corporate-2' => 'Corporate 2',
            'fluo-1' => 'Fluo 1',
            'fluo-2' => 'Fluo 2',
            'fluo-3' => 'Fluo 3',
            'geek-1' => 'Geek 1',
            'geek-2' => 'Geek 2',
            'minimal-1' => 'Minimal 1',
            'minimal-2' => 'Minimal 2',
        )
    ));

// Logo

    $wp_customize->add_setting('fudge_theme_options[fudge_logo]', array(
        'default' => '',
        'capability' => 'edit_theme_options',
        'type' => 'option',
    ));

    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'fudge_logo', array(
                'label' => __('Logo', 'fudge'),
                'section' => 'fudge_colors',
                'settings' => 'fudge_theme_options[fudge_logo]',
            )));

// Footer Content

    $wp_customize->add_setting('fudge_theme_options[fudge_footer]', array(
        'default' => '',
        'capability' => 'edit_theme_options',
        'type' => 'option',
    ));

    $wp_customize->add_control('fudge_footer', array(
        'label' => __('Footer Content', 'fudge'),
        'section' => 'fudge_colors',
        'settings' => 'fudge_theme_options[fudge_footer]',
    ));


    /*     * ****** HOMEPAGE OPTIONS********** */

    $wp_customize->add_section('fudge_homepage', array(
        'title' => __('Homepage Options', 'fudge'),
        'priority' => 135,
    ));

    // Hero

    $wp_customize->add_setting('fudge_theme_options[fudge_hero]', array(
        'default' => get_bloginfo('url') . '/wp-content/themes/fudge/images/hero.jpg',
        'capability' => 'edit_theme_options',
        'type' => 'option',
    ));

    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'fudge_hero', array(
                'label' => __('Hero Background', 'fudge'),
                'section' => 'fudge_homepage',
                'settings' => 'fudge_theme_options[fudge_hero]',
            )));

    // Title

    $wp_customize->add_setting('fudge_theme_options[fudge_herotitle]', array(
        'default' => '',
        'capability' => 'edit_theme_options',
        'type' => 'option',
    ));

    $wp_customize->add_control('fudge_herotitle', array(
        'label' => __('Event Title', 'fudge'),
        'section' => 'fudge_homepage',
        'settings' => 'fudge_theme_options[fudge_herotitle]',
    ));

    // Tagline

    $wp_customize->add_setting('fudge_theme_options[fudge_herotagline]', array(
        'default' => '',
        'capability' => 'edit_theme_options',
        'type' => 'option',
    ));

    $wp_customize->add_control('fudge_herotagline', array(
        'label' => __('Event Tagline', 'fudge'),
        'section' => 'fudge_homepage',
        'settings' => 'fudge_theme_options[fudge_herotagline]',
    ));

    /*     * ****** EVENT OVER OPTIONS********** */

    $wp_customize->add_section('fudge_over', array(
        'title' => __('Event Over Settings', 'fudge'),
        'priority' => 136,
    ));

// Title

    $wp_customize->add_setting('fudge_theme_options[fudge_overtitle]', array(
        'default' => '',
        'capability' => 'edit_theme_options',
        'type' => 'option',
    ));

    $wp_customize->add_control('fudge_overtitle', array(
        'label' => __('Main Title', 'fudge'),
        'section' => 'fudge_over',
        'settings' => 'fudge_theme_options[fudge_overtitle]',
    ));

// Tagline

    $wp_customize->add_setting('fudge_theme_options[fudge_overtagline]', array(
        'default' => '',
        'capability' => 'edit_theme_options',
        'type' => 'option',
    ));

    $wp_customize->add_control('fudge_overtagline', array(
        'label' => __('Main tagline', 'fudge'),
        'section' => 'fudge_over',
        'settings' => 'fudge_theme_options[fudge_overtagline]',
    ));

// Turn On Event Over

    $wp_customize->add_setting('fudge_theme_options[fudge_eventover]', array(
        'default' => '',
        'capability' => 'edit_theme_options',
        'type' => 'option',
    ));

    $wp_customize->add_control('fudge_eventover]', array(
        'label' => __('Switch to Event Over Content?', 'fudge'),
        'section' => 'fudge_over',
        'settings' => 'fudge_theme_options[fudge_eventover]',
        'type' => 'checkbox'
    ));

    /*     * ****** SOCIAL MEDIA OPTIONS********** */

    $wp_customize->add_section('fudge_social', array(
        'title' => __('Social Media &amp; Connecting', 'fudge'),
        'priority' => 140,
    ));

// Email 

    $wp_customize->add_setting('fudge_theme_options[fudge_email]', array(
        'default' => '',
        'capability' => 'edit_theme_options',
        'type' => 'option',
    ));

    $wp_customize->add_control('fudge_email', array(
        'label' => __('Email Address', 'fudge'),
        'section' => 'fudge_social',
        'settings' => 'fudge_theme_options[fudge_email]',
    ));

// Twitter 

    $wp_customize->add_setting('fudge_theme_options[fudge_twitter]', array(
        'default' => '',
        'capability' => 'edit_theme_options',
        'type' => 'option',
    ));

    $wp_customize->add_control('fudge_twitter', array(
        'label' => __('Twitter Username', 'fudge'),
        'section' => 'fudge_social',
        'settings' => 'fudge_theme_options[fudge_twitter]',
    ));

// facebook 

    $wp_customize->add_setting('fudge_theme_options[fudge_facebook]', array(
        'default' => '',
        'capability' => 'edit_theme_options',
        'type' => 'option',
    ));

    $wp_customize->add_control('fudge_facebook', array(
        'label' => __('Facebook URL', 'fudge'),
        'section' => 'fudge_social',
        'settings' => 'fudge_theme_options[fudge_facebook]',
    ));

// flickr 

    $wp_customize->add_setting('fudge_theme_options[fudge_flickr]', array(
        'default' => '',
        'capability' => 'edit_theme_options',
        'type' => 'option',
    ));

    $wp_customize->add_control('fudge_flickr', array(
        'label' => __('Flickr Username', 'fudge'),
        'section' => 'fudge_social',
        'settings' => 'fudge_theme_options[fudge_flickr]',
    ));

// linkedin 

    $wp_customize->add_setting('fudge_theme_options[fudge_linkedin]', array(
        'default' => '',
        'capability' => 'edit_theme_options',
        'type' => 'option',
    ));

    $wp_customize->add_control('fudge_linkedin', array(
        'label' => __('Full LinkedIn URL', 'fudge'),
        'section' => 'fudge_social',
        'settings' => 'fudge_theme_options[fudge_linkedin]',
    ));

// pinterest 

    $wp_customize->add_setting('fudge_theme_options[fudge_pinterest]', array(
        'default' => '',
        'capability' => 'edit_theme_options',
        'type' => 'option',
    ));

    $wp_customize->add_control('fudge_pinterest', array(
        'label' => __('Pinterest Username', 'fudge'),
        'section' => 'fudge_social',
        'settings' => 'fudge_theme_options[fudge_pinterest]',
    ));

    // instagram 

    $wp_customize->add_setting('fudge_theme_options[fudge_instagram]', array(
        'default' => '',
        'capability' => 'edit_theme_options',
        'type' => 'option',
    ));

    $wp_customize->add_control('fudge_instagram', array(
        'label' => __('Instagram Username', 'fudge'),
        'section' => 'fudge_social',
        'settings' => 'fudge_theme_options[fudge_instagram]',
    ));

    // google+ 

    $wp_customize->add_setting('fudge_theme_options[fudge_googleplus]', array(
        'default' => '',
        'capability' => 'edit_theme_options',
        'type' => 'option',
    ));

    $wp_customize->add_control('fudge_googleplus', array(
        'label' => __('Full Google+ URL', 'fudge'),
        'section' => 'fudge_social',
        'settings' => 'fudge_theme_options[fudge_googleplus]',
    ));
}

add_action('customize_register', 'fudge_customize_register');

// ******************* Custom Comments ****************** //

function fudge_comment($comment, $args) {
    $GLOBALS['comment'] = $comment;
    extract($args, EXTR_SKIP);

    if ('div' == $args['style']) {
        $tag = 'div';
        $add_below = 'comment';
    } else {
        $tag = 'li';
        $add_below = 'div-comment';
    }
    ?>
    <<?php echo $tag ?> <?php comment_class(empty($args['has_children']) ? '' : 'parent') ?> id="comment-<?php comment_ID() ?>">
    <?php if ('div' != $args['style']) : ?>
        <div id="div-comment-<?php comment_ID() ?>" class="comment-body">
        <?php endif; ?>
        <div class="comment-content">
            <?php if ($comment->comment_approved == '0') : ?>
                <p><em class="comment-awaiting-moderation"><?php _e('Your comment is awaiting moderation.') ?></em></p>
            <?php endif; ?>
            <?php comment_text() ?>
        </div>
        <div class="comment-meta">
            <span><?php printf(__('%s', 'fudge'), get_comment_author_link()) ?>, </span>
            <?php printf(__('%1$s at %2$s', 'fudge'), get_comment_date(), get_comment_time()) ?><?php edit_comment_link(__('(Edit)', 'fudge'), '  ', ''); ?>
        </div>
        <?php if ('div' != $args['style']) : ?>
        </div>
    <?php endif; ?>
    <?php
}

// ******************* HIDE WYSIWYG ****************** //

add_action('init', 'remove_editor_init');

function remove_editor_init() {
    $post_id = $_GET['post'] ? $_GET['post'] : $_POST['post_ID'];
    $template_file = get_post_meta($post_id, '_wp_page_template', TRUE);
    if ($template_file == 'page-event-over.php') {
        remove_post_type_support('page', 'editor');
    }
}

// ******************* Add Custom Widgets ****************** //

include_once('functions/widget-event-info.php');
include_once('functions/widget-registration.php');
include_once('functions/widget-btn-inscreva.php');
include_once('functions/widget-connect.php');
include_once('functions/widget-schedule.php');
include_once('functions/widget-facebook-rsvp.php');
include_once('functions/widget-speakers.php');
include_once('functions/widget-sponsors.php');
include_once('functions/widget-media.php');
include_once('functions/widget-twitter.php');
include_once('functions/widget-timer.php');
include_once('functions/widget-news.php');
include_once('functions/widget-contact.php');
include_once('functions/widget-explore.php');
include_once('functions/widget-quotes.php');
include_once('functions/widget-event-description.php');
include_once('functions/widget-event-description.php');
include_once('lib/dummy-content.php');

// ******************* Custom Functions ****************** //

function fudge_get_session_dates() {
    global $wpdb;

    $metas = $wpdb->get_results(
            "SELECT DISTINCT meta_value 
            FROM $wpdb->postmeta
                INNER JOIN $wpdb->posts ON $wpdb->postmeta.post_id = $wpdb->posts.ID
            WHERE
                $wpdb->posts.post_type = 'session' AND
                $wpdb->posts.post_status = 'publish' AND
                $wpdb->postmeta.meta_key = 'date' AND
                $wpdb->postmeta.meta_value != ''
            ORDER BY meta_value ASC");

    return $metas;
}

function fudge_get_term_meta($field_set, $term_id, $field_id) {
    $meta = get_option($field_set);
    if (empty($meta))
        $meta = array();
    if (!is_array($meta))
        $meta = (array) $meta;
    $meta = isset($meta[$term_id]) ? $meta[$term_id] : array();
    $value = $meta[$field_id];

    return $value;
}

// ******************* Ajax ****************** //

add_action('wp_ajax_nopriv_get_session', 'fudge_ajax_get_session');
add_action('wp_ajax_get_session', 'fudge_ajax_get_session');

function fudge_ajax_get_session() {
    $ret = array();

    if (isset($_POST['data-id']) && ctype_digit($_POST['data-id'])) {
        $session_id = intval($_POST['data-id']);
        $session = get_post($session_id);
        $tracks = wp_get_post_terms($session_id, 'session-track', array('fields' => 'all'));
        foreach ($tracks as &$track)
            $track->color = fudge_get_term_meta('session-track-metas', $track->term_id, 'session_track_color');
        $locations = wp_get_post_terms($session_id, 'session-location', array('fields' => 'all'));
        $date = get_post_meta($session_id, 'date', true);
        $time = get_post_meta($session_id, 'time', true);
        $end_time = get_post_meta($session_id, 'end_time', true);

        if (!empty($time)) {
            $time_parts = explode(':', $time);
            if (count($time_parts) == 2)
                $time = date(get_option("time_format"), mktime($time_parts[0], $time_parts[1], 0));
        }
        if (!empty($end_time)) {
            $time_parts = explode(':', $end_time);
            if (count($time_parts) == 2)
                $end_time = date(get_option("time_format"), mktime($time_parts[0], $time_parts[1], 0));
        }

        $speakers = array();
        $speakers_list = get_post_meta($session_id, 'speakers_list', true);
        if (!empty($speakers_list)) {
            foreach ($speakers_list as $speaker_id)
                $speakers[] = array(
                    'post_id' => $speaker_id,
                    'post_title' => apply_filters('the_title', get_the_title($speaker_id)),
                    'post_image' => get_the_post_thumbnail($speaker_id, array(60, 60)),
                );
        }

        $ret = array(
            'post_title' => apply_filters('the_title', $session->post_title),
            'post_content' => apply_filters('the_content', $session->post_content),
            'tracks' => $tracks,
            'location' => !empty($locations) ? $locations[0]->name : '',
            'date' => !empty($date) ? date(get_option('date_format'), timestamp_fix($date)) : '',
            'time' => $time,
            'end_time' => $end_time,
            'post_edit_link' => is_user_logged_in() ? get_edit_post_link($session_id) : '',
            'speakers' => $speakers
        );
    }

    echo json_encode($ret);
    die;
}

add_action('wp_ajax_nopriv_get_speaker', 'fudge_ajax_get_speaker');
add_action('wp_ajax_get_speaker', 'fudge_ajax_get_speaker');

function sessions_posts_fields($sql) {
    global $wpdb;

    return $sql . ", $wpdb->postmeta.meta_value as date, mt2.meta_value as time";
}

function sessions_posts_orderby($sql) {

    return $sql . ", mt2.meta_value ASC";
}

function fudge_ajax_get_speaker() {
    $ret = array();

    if (isset($_POST['data-id']) && ctype_digit($_POST['data-id'])) {
        $speaker_id = intval($_POST['data-id']);
        $speaker = get_post($speaker_id);
        $company = get_post_meta($speaker_id, 'company', true);
        $short_bio = get_post_meta($speaker_id, 'short_bio', true);
        $website_url = get_post_meta($speaker_id, 'website_url', true);
        $twitter_username = get_post_meta($speaker_id, 'twitter_username', true);

        $sessions = array();

        add_filter('posts_fields', 'sessions_posts_fields');
        add_filter('posts_orderby', 'sessions_posts_orderby');

        $sessions_loop = new WP_Query(
                        array(
                            'post_type' => 'session',
                            'nopaging' => true,
                            'meta_query' => array(
                                array(
                                    'key' => 'date',
                                    'compare' => 'EXISTS',
                                ),
                                array(
                                    'key' => 'time',
                                    'compare' => 'EXISTS',
                                ),
                            ),
                            'meta_key' => 'date',
                            'orderby' => 'meta_value',
                            'order' => 'DESC'
                        )
        );

        remove_filter('posts_fields', 'sessions_posts_fields');
        remove_filter('posts_orderby', 'sessions_posts_orderby');

        if ($sessions_loop->have_posts()):
            while ($sessions_loop->have_posts()):
                $sessions_loop->the_post();
                $session_speakers = get_post_meta(get_the_ID(), 'speakers_list', true);
                if ($session_speakers && is_array($session_speakers) && in_array($speaker_id, $session_speakers)) {
                    $date = get_post_meta(get_the_ID(), 'date', true);
                    $sessions[] = array(
                        'post_id' => get_the_ID(),
                        'post_title' => get_the_title(),
                        'date' => !empty($date) ? date(get_option('date_format'), timestamp_fix($date)) : '',
                    );
                }
            endwhile;
            wp_reset_query();
        endif;

        $ret = array(
            'post_title' => apply_filters('the_title', $speaker->post_title),
            'post_content' => apply_filters('the_content', $speaker->post_content),
            'post_image' => get_the_post_thumbnail($speaker_id),
            'company' => $company,
            'short_bio' => $short_bio,
            'website_url' => str_replace('http://', '', $website_url),
            'twitter_username' => $twitter_username,
            'sessions' => $sessions,
            'post_edit_link' => is_user_logged_in() ? get_edit_post_link($speaker_id) : '',
        );
    }

    echo json_encode($ret);
    die;
}

add_action('wp_ajax_nopriv_get_media', 'fudge_ajax_get_media');
add_action('wp_ajax_get_media', 'fudge_ajax_get_media');

function fudge_ajax_get_media() {
    $ret = array(
        'page' => 1,
        'media' => array(),
        'more' => 0,
        'limit' => 1
    );

    if (isset($_POST['data-id']) && ctype_digit($_POST['data-id'])) {
        $page = isset($_POST['data-page']) && ctype_digit($_POST['data-page']) ? intval($_POST['data-page']) : '1';
        $term_id = intval($_POST['data-id']);
        $media_limit = isset($_POST['data-limit']) && ctype_digit($_POST['data-limit']) ? intval($_POST['data-limit']) : get_media_limit();
        $media_loop_args = array(
            'post_type' => 'event-media',
            'posts_per_page' => $media_limit,
            'paged' => $page
        );

        if ($term_id > 0)
            $media_loop_args['tax_query'] = array(
                array(
                    'taxonomy' => 'media-type',
                    'field' => 'id',
                    'terms' => array($term_id)
                )
            );
        $media_loop = new WP_Query($media_loop_args);
        while ($media_loop->have_posts()) {
            $media_loop->the_post();

            $post_video = '';
            $video_url = get_post_meta(get_the_ID(), 'video_url', true);
            if (!empty($video_url))
                $post_video = wp_oembed_get($video_url, array('height' => 222, 'width' => 222));

            $ret['media'][] = array(
                'post_title' => get_the_title(),
                'post_content' => get_the_content(),
                'post_image' => get_the_post_thumbnail(get_the_ID(), array(222, 222)),
                'post_video' => $post_video,
                'post_image_big_url' => wp_get_attachment_url(get_post_thumbnail_id(get_the_ID()))
            );
        }

        $ret['page'] = $page;
        if ($media_loop->found_posts > $page * $media_limit)
            $ret['more'] = 1;
    }

    echo json_encode($ret);
    die;
}

add_action('wp_ajax_nopriv_get_schedule', 'fudge_ajax_get_schedule');
add_action('wp_ajax_get_schedule', 'fudge_ajax_get_schedule');

function fudge_ajax_get_schedule() {
    $ret = array(
        'page' => 1,
        'sessions' => array(),
        'more' => 0
    );

    if (isset($_POST['data-timestamp'])) {
        $timestamp = $_POST['data-timestamp'];
        $page = isset($_POST['data-page']) && ctype_digit($_POST['data-page']) ? intval($_POST['data-page']) : '1';
        $location = isset($_POST['data-location']) && ctype_digit($_POST['data-location']) ? intval($_POST['data-location']) : '0';
        $track = isset($_POST['data-track']) && ctype_digit($_POST['data-track']) ? intval($_POST['data-track']) : '0';
        $schedule_limit = get_schedule_limit();
        $wp_time_format = get_option("time_format");

        add_filter('posts_fields', 'sessions_posts_fields');
        add_filter('posts_orderby', 'sessions_posts_orderby');

        $session_loop_args = array(
            'post_type' => 'session',
            'posts_per_page' => $schedule_limit,
            'paged' => $page,
            'meta_query' => array(
                array(
                    'key' => 'date',
                    'value' => $timestamp,
                ),
                array(
                    'key' => 'time',
                    'compare' => 'EXISTS',
                )
            ),
            'tax_query' => array(),
            'meta_key' => 'date',
            'orderby' => 'meta_value',
            'order' => 'ASC'
        );

        if ($location > 0)
            $session_loop_args['tax_query'][] = array(
                'taxonomy' => 'session-location',
                'field' => 'id',
                'terms' => $location
            );
        if ($track > 0)
            $session_loop_args['tax_query'][] = array(
                'taxonomy' => 'session-track',
                'field' => 'id',
                'terms' => $track
            );
        $sessions_loop = new WP_Query($session_loop_args);

        remove_filter('posts_fields', 'sessions_posts_fields');
        remove_filter('posts_orderby', 'sessions_posts_orderby');

        while ($sessions_loop->have_posts()) {
            $sessions_loop->the_post();
            global $post;

            $time = $post->time;
            if (!empty($time)) {
                $time_parts = explode(':', $time);
                if (count($time_parts) == 2)
                    $time = date($wp_time_format, mktime($time_parts[0], $time_parts[1], 0));
            }
            $locations = wp_get_post_terms(get_the_ID(), 'session-location');
            if ($locations && count($locations) > 0)
                $location = $locations[0];
            $tracks = wp_get_post_terms(get_the_ID(), 'session-track', array('fields' => 'ids', 'count' => 1));
            if ($tracks && count($tracks) > 0)
                $track = $tracks[0];
            $speakers_list = get_post_meta(get_the_ID(), 'speakers_list', true);
            $speakers = array();
            if ($speakers_list && count($speakers_list) > 0) {
                foreach ($speakers_list as $speaker_id)
                    $speakers[] = array(
                        'post_image' => get_the_post_thumbnail($speaker_id, array(60, 60))
                    );
            }

            array_push($ret['sessions'], array(
                'id' => get_the_ID(),
                'post_title' => get_the_title(),
                'time' => $time,
                'end_time' => get_post_meta(get_the_ID(), 'end_time', true),
                'location' => $location ? $location->name : '',
                'background_color' => $track ? fudge_get_term_meta('session-track-metas', $track, 'session_track_color') : '',
                'speakers' => $speakers
            ));
        }

        $ret['page'] = $page;
        if ($sessions_loop->found_posts > $page * $schedule_limit)
            $ret['more'] = 1;
    }
    echo json_encode($ret);
    die;
}

add_action('wp_ajax_nopriv_get_speakers', 'fudge_ajax_get_speakers');
add_action('wp_ajax_get_speakers', 'fudge_ajax_get_speakers');

function fudge_ajax_get_speakers() {
    $ret = array(
        'page' => 1,
        'speakers' => array(),
        'more' => 0,
        'limit' => 1
    );

    $page = isset($_POST['data-page']) && ctype_digit($_POST['data-page']) ? intval($_POST['data-page']) : '1';
    $speakers_limit = isset($_POST['data-limit']) && ctype_digit($_POST['data-limit']) ? intval($_POST['data-limit']) : get_speakers_limit();
    $speakers_loop_args = array(
        'post_type' => 'speaker',
        'posts_per_page' => $speakers_limit,
        'paged' => $page,
        'orderby' => 'menu_order',
        'order' => 'ASC'
    );

    $speakers_loop = new WP_Query($speakers_loop_args);
    while ($speakers_loop->have_posts()) {
        $speakers_loop->the_post();

        $short_bio = get_post_meta(get_the_ID(), 'short_bio', true);
        if (mb_strlen($short_bio, 'UTF-8') > 50)
            $short_bio = substr($short_bio, 0, 50) . '&hellip;';

        $ret['speakers'][] = array(
            'post_ID' => get_the_ID(),
            'post_title' => get_the_title(),
            'post_image' => get_the_post_thumbnail(get_the_ID()),
            'post_company' => get_post_meta(get_the_ID(), 'company', true),
            'post_short_bio' => $short_bio,
        );
    }

    $ret['page'] = $page;
    if ($speakers_loop->found_posts > $page * $speakers_limit)
        $ret['more'] = 1;

    echo json_encode($ret);
    die;
}

add_action('wp_ajax_nopriv_get_tweets', 'fudge_ajax_get_tweets');
add_action('wp_ajax_get_tweets', 'fudge_ajax_get_tweets');

function fudge_ajax_get_tweets() {
    global $twitter;
    $twitterhash = get_option('fudge_twitter_widget_twitterhash');
    $tweets = array();

    if (isset($twitter) && !empty($twitterhash)) {
        $url = 'https://api.twitter.com/1.1/search/tweets.json';
        $getfield = "?q={$_GET['queryParams']['q']}&count={$_GET['queryParams']['count']}";
        $requestMethod = 'GET';
        $store = $twitter->setGetfield($getfield)
                ->buildOauth($url, $requestMethod)
                ->performRequest();
        $tweets = json_decode($store);
    }

    echo json_encode($tweets->statuses);
    die;
}

add_action('wp_ajax_nopriv_send_contact_email', 'fudge_ajax_send_contact_email');
add_action('wp_ajax_send_contact_email', 'fudge_ajax_send_contact_email');

function fudge_ajax_send_contact_email() {
    $ret = array(
        'sent' => false,
        'error' => false,
        'message' => ''
    );

    $recaptchapublickey = get_option('fudge_contact_widget_recaptcha_publickey');
    $recaptchaprivatekey = get_option('fudge_contact_widget_recaptcha_privatekey');
    $contactemail = get_option('fudge_contact_widget_email');

    if (!empty($recaptchapublickey) && !empty($recaptchaprivatekey)) {
        $resp = recaptcha_check_answer($recaptchaprivatekey, $_SERVER["REMOTE_ADDR"], $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]);
        // check reCaptcha
        if (!$resp || !$resp->is_valid) {
            $ret['message'] = __('The reCAPTCHA wasn\'t entered correctly. Go back and try it again.!', 'fudge');
            $ret['error'] = true;
        }
    }
    // require a name from user
    if (trim($_POST['contactName']) === '') {
        $ret['message'] = __('Forgot your name!', 'fudge');
        $ret['error'] = true;
    } else {
        $name = trim($_POST['contactName']);
    }
    // need valid email
    if (trim($_POST['email']) === '') {
        $ret['message'] = __('Forgot to enter in your e-mail address.', 'fudge');
        $ret['error'] = true;
    } else if (!preg_match("/^[[:alnum:]][a-z0-9_.-]*@[a-z0-9.-]+\.[a-z]{2,4}$/i", trim($_POST['email']))) {
        $ret['message'] = __('You entered an invalid email address.', 'fudge');
        $ret['error'] = true;
    } else {
        $email = trim($_POST['email']);
    }
    $phone = trim($_POST['phone']);
    // we need at least some content
    if (trim($_POST['comments']) === '') {
        $ret['message'] = __('You forgot to enter a message!', 'fudge');
        $ret['error'] = true;
    } else {
        if (function_exists('stripslashes')) {
            $comments = stripslashes(trim($_POST['comments']));
        } else {
            $comments = trim($_POST['comments']);
        }
    }
    // upon no failure errors let's email now!
    if (!$ret['error']) {
        $subject = __('Submitted message from ', 'fudge') . $name;
        $body = __('Name:', 'fudge') . " $name \n\n" . __('Email:', 'fudge') . " $email \n\n " . __('Phone:', 'fudge') . " $phone \n\n" . __('Comments:', 'fudge') . " $comments";
        $headers = 'From: ' . $contactemail . "\r\n" . 'Reply-To: ' . $email . "\r\n";

        try {
            mail($contactemail, $subject, $body, $headers);
            $ret['sent'] = true;
            $ret['message'] = __('Your email was sent.', 'fudge');
        } catch (Exception $e) {
            $ret['message'] = __('Error submitting the form', 'fudge');
        }
    }

    echo json_encode($ret);
    die;
}

// ******************* Options Page ****************** //

add_action('admin_menu', 'fudge_admin_menu');

function fudge_admin_menu() {
    add_theme_page('Fudge Dummy Data', 'Fudge Dummy Data', 'edit_theme_options', 'fudge-options-page', 'fudge_options_page');
}

function fudge_options_page() {
    if (isset($_POST) && isset($_POST['generate_dummy_data']) && $_POST['generate_dummy_data'] == 1)
        fudge_install_dummy_content();
    ?>
    <div class="wrap">
        <?php screen_icon(); ?>
        <h2><?php _e('Fudge Dummy Data', 'fudge'); ?></h2>
        <br/><br/>
        <form method="post" action="">
            <span><?php _e('Check to generate dummy data', 'fudge'); ?></span>
            <input type="checkbox" name="generate_dummy_data" value="1" />
            <?php submit_button(__('Generate', 'fudge')); ?>
        </form>
    </div>
    <?php
}

// ******************* Misc ****************** //

add_filter('manage_edit-speaker_columns', 'edit_speaker_columns');

function edit_speaker_columns($columns) {
    $new_columns = array(
        'cb' => $columns['cb'],
        'title' => $columns['title'],
        'menu_order' => __('Order', 'fudge'),
        'date' => $columns['date'],
    );
    return $new_columns;
}

add_action('manage_posts_custom_column', 'edit_post_columns', 10, 2);

function edit_post_columns($column_name) {
    global $post;

    switch ($column_name) {
        case 'menu_order' :
            echo $post->menu_order;
            break;

        default:
    }
}

function getRelativeTime($date) {
    $diff = time() - strtotime($date);
    if ($diff < 60)
        return $diff . _n(' second', ' seconds', $diff, 'fudge') . __(' ago', 'fudge');
    $diff = round($diff / 60);
    if ($diff < 60)
        return $diff . _n(' minute', ' minutes', $diff, 'fudge') . __(' ago', 'fudge');
    $diff = round($diff / 60);
    if ($diff < 24)
        return $diff . _n(' hour', ' hours', $diff, 'fudge') . __(' ago', 'fudge');
    $diff = round($diff / 24);
    if ($diff < 7)
        return $diff . _n(' day', ' days', $diff, 'fudge') . __(' ago', 'fudge');
    $diff = round($diff / 7);
    if ($diff < 4)
        return $diff . _n(' week', ' weeks', $diff, 'fudge') . __(' ago', 'fudge');
    return __('on ', 'fudge') . date("F j, Y", strtotime($date));
}

function get_schedule_limit() {
    return apply_filters('fudge_schedule_limit', 3);
}

function get_media_limit() {
    return apply_filters('fudge_media_limit', 8);
}

function get_speakers_limit() {
    return apply_filters('fudge_speakers_limit', 8);
}

function timestamp_fix($timestamp) {
    return $timestamp / 1000;
}

function fudge_print_social_media_links() {
    $options = get_option('fudge_theme_options');
    if ($options['fudge_email'] != '')
        echo '<a class="icon-email" title="Email ' . get_bloginfo('title') . '" href="mailto:' . $options['fudge_email'] . '">Email</a>';
    echo '<a class="icon-rss" title="Subscribe to ' . get_bloginfo('title') . ' RSS Feed" href="' . get_bloginfo('rss_url') . '">RSS</a>';
    if ($options['fudge_twitter'] != '')
        echo '<a class="icon-twitter" title="Seguir ' . get_bloginfo('title') . ' on Twitter" href="http://www.twitter.com/' . $options['fudge_twitter'] . '">Twitter</a>';
    if ($options['fudge_facebook'] != '')
        echo '<a class="icon-facebook" title="Dê like em ' . get_bloginfo('title') . ' on Facebook" href="' . $options['fudge_facebook'] . '">Facebook</a>';
    if ($options['fudge_flickr'] != '')
        echo '<a class="icon-flickr" title="Ver fotos de  ' . get_bloginfo('title') . '" href="http://www.flickr.com/' . $options['fudge_flickr'] . '">Flickr</a>';
    if ($options['fudge_linkedin'] != '')
        echo '<a class="icon-linkedin" title="Conectar com ' . get_bloginfo('title') . ' on LinkedIn" href="' . $options['fudge_linkedin'] . '">LinkedIn</a>';
    if ($options['fudge_pinterest'] != '')
        echo '<a class="icon-pinterest" title="View pins from ' . get_bloginfo('title') . '" href="http://www.pinterest.com/' . $options['fudge_pinterest'] . '">Pinterest</a>';
    if ($options['fudge_instagram'] != '')
        echo '<a class="icon-instagram" title="View pictures from ' . get_bloginfo('title') . '" href="http://www.instagram.com/' . $options['fudge_instagram'] . '">Instagram</a>';
    if ($options['fudge_googleplus'] != '')
        echo '<a class="icon-googleplus" title="Ver ' . get_bloginfo('title') . '" href="' . $options['fudge_googleplus'] . '">Google+</a>';
}