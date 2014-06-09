<?php
// twitter Custom Widget

wp_register_sidebar_widget(
        'fudge_twitter', // your unique widget id
        'Fudge Latest Tweets', // widget name
        'fudge_twitter_display', // callback function to display widget
        array(// options
    'description' => __('Shows a section displaying latest tweets', 'fudge')
        )
);

wp_register_widget_control(
        'fudge_twitter', // id
        'fudge_twitter', // name
        'fudge_twitter_control' // callback function
);

function fudge_twitter_control() {
    //the form is submitted, save into database
    if (isset($_POST['submitted'])) {
        update_option('fudge_twitter_widget_twitterhash', $_POST['twitterhash']);
        update_option('fudge_twitter_widget_menu', $_POST['twittermenu']);
        update_option('fudge_twitter_widget_accesstoken', $_POST['twitteraccesstoken']);
        update_option('fudge_twitter_widget_accesstokensecret', $_POST['twitteraccesstokensecret']);
        update_option('fudge_twitter_widget_consumerkey', $_POST['twitterconsumerkey']);
        update_option('fudge_twitter_widget_consumersecret', $_POST['twitterconsumersecret']);
    }
    //load options
    $twitterhash = get_option('fudge_twitter_widget_twitterhash');
    $twittermenu = get_option('fudge_twitter_widget_menu');
    $twitteraccesstoken = get_option('fudge_twitter_widget_accesstoken');
    $twitteraccesstokensecret = get_option('fudge_twitter_widget_accesstokensecret');
    $twitterconsumerkey = get_option('fudge_twitter_widget_consumerkey');
    $twitterconsumersecret = get_option('fudge_twitter_widget_consumersecret');
    ?>
    <?php _e('Event Hashtag Keyword:', 'fudge'); ?><br />
    <input type="text" class="widefat" name="twitterhash" value="<?php echo stripslashes($twitterhash); ?>"/><br/>
    <small><?php _e('(Leave out the #)', 'fudge'); ?></small>
    <br /><br />
    <?php _e('Add to main navigation?', 'fudge'); ?><br />
    <input type="text" class="widefat" name="twittermenu" value="<?php echo stripslashes($twittermenu); ?>"/><br/>
    <small><?php _e('(Enter desired menu link text)', 'fudge'); ?></small>
    <br /><br />
    <em><?php _e('Access Token:', 'fudge'); ?></em><br />
    <input type="text" class="twitteraccesstoken" name="twitteraccesstoken" value="<?php echo stripslashes($twitteraccesstoken); ?>"/>
    <br /><br />
    <em><?php _e('Access Token Secret:', 'fudge'); ?></em><br />
    <input type="text" class="twitteraccesstokensecret" name="twitteraccesstokensecret" value="<?php echo stripslashes($twitteraccesstokensecret); ?>"/>
    <br /><br />
    <em><?php _e('Consumer Key:', 'fudge'); ?></em><br />
    <input type="text" class="twitterconsumerkey" name="twitterconsumerkey" value="<?php echo stripslashes($twitterconsumerkey); ?>"/>
    <br /><br />
    <em><?php _e('Consumer Secret:', 'fudge'); ?></em><br />
    <input type="text" class="twitterconsumersecret" name="twitterconsumersecret" value="<?php echo stripslashes($twitterconsumersecret); ?>"/>
    <br /><br />
    <input type="hidden" name="submitted" value="1" />
    <?php
}

function fudge_twitter_display($args = array()) {
    global $twitter;
    //load options
    $twitterhash = get_option('fudge_twitter_widget_twitterhash');
    $tweets = array();

    if (isset($twitter) && !empty($twitterhash)) {
        $url = 'https://api.twitter.com/1.1/search/tweets.json';
        $getfield = "?q=#$twitterhash&count=4";
        $requestMethod = 'GET';
        $store = $twitter->setGetfield($getfield)
                ->buildOauth($url, $requestMethod)
                ->performRequest();
        $tweets = json_decode($store);
    }
    //widget output
    echo stripslashes($args['before_widget']);
    ?>
    <section id="twitter"><div class="container">
            <div id="twitter_div">
                <ul id="twitter_update_list">
                    <?php if (count($tweets->statuses) == 0) : ?>
                        <li><?php _e('No items', 'fudge'); ?></li>
                    <?php else : ?>
                        <?php foreach ($tweets->statuses as $tweet) : ?>
                            <li>
                                <span id="tweet-<?php echo $tweet->id_str; ?>"><?php echo $tweet->text; ?></span>
                                <a href="<?php echo esc_url($tweet->url); ?>"><?php echo getRelativeTime($tweet->created_at); ?></a>
                            </li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
        <div class="container-btns <?php if (!is_user_logged_in()) echo 'not-logged'; ?>">
            <?php if (is_user_logged_in()) { ?>
                <a class="btn secondary-bkg-color" title="<?php _e('View in Fullscreen', 'fudge'); ?>" href="<?php bloginfo('url'); ?>/twitter-full-screen"><?php _e('View in Fullscreen', 'fudge'); ?></a>
            <?php } ?>
            <div class="tweet-hash">
                <a href="https://twitter.com/intent/tweet?button_hashtag=<?php echo stripslashes($twitterhash); ?>" class="twitter-hashtag-button"><?php _e('Tweet ', 'fudge'); ?>#<?php echo stripslashes($twitterhash); ?></a>
            </div>
        </div>
    </section>
    <?php
    echo stripslashes($args['after_widget']);
}