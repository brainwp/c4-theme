<?php

// Template Name: Twitter Full Page

get_header();

?>

<section id="twitter">

    <div class="container">

        <?php

        global $twitter;



        $twitterhash = get_option('fudge_twitter_widget_twitterhash');

        $tweets = array();

        if (isset($twitter) && !empty($twitterhash)) {

            $url = 'https://api.twitter.com/1.1/search/tweets.json';

            $getfield = "?q=$twitterhash&count=7";

            $requestMethod = 'GET';

            $store = $twitter->setGetfield($getfield)

                    ->buildOauth($url, $requestMethod)

                    ->performRequest();

            $tweets = json_decode($store);

        }

        ?>

        <div id="twitter_div">

            <ul id="twitter_update_list">

            </ul>

            <a class="btn secondary-bkg-color" title="<?php _e('Back', 'fudge'); ?>" href="<?php echo home_url(); ?>"><?php _e('Back', 'fudge'); ?></a>

        </div>

    </div>

</div>

</section>

<?php get_footer(); ?>