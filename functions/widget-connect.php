<?php
// Connect With Us Custom Widget

wp_register_sidebar_widget(
        'fudge_connect', // your unique widget id
        'Fudge Social Media Links', // widget name
        'fudge_connect_display', // callback function to display widget
        array(// options
    'description' => __('Shows a section displaying icons for the social media links filled out in the Customizer', 'fudge')
        )
);

wp_register_widget_control(
        'fudge_connect', // id
        'fudge_connect', // name
        'fudge_connect_control' // callback function
);

function fudge_connect_control() {
    //the form is submitted, save into database
    if (isset($_POST['submitted'])) {
        update_option('fudge_connect_widget_title', $_POST['connecttitle']);
        update_option('fudge_connect_widget_menu', $_POST['connectmenu']);
    }
    //load options
    $connecttitle = get_option('fudge_connect_widget_title');
    $connectmenu = get_option('fudge_connect_widget_menu');
    ?>
    <?php _e('Title:', 'fudge'); ?><br />
    <input type="text" class="widefat" name="connecttitle" value="<?php echo stripslashes($connecttitle); ?>" />
    <br /><br />
    <?php _e('Add to main navigation?', 'fudge'); ?><br />
    <input type="text" class="widefat" name="connectmenu" value="<?php echo stripslashes($connectmenu); ?>"/><br/>
    <small><?php _e('(Enter desired menu link text)', 'fudge'); ?></small>
    <br /><br />
    <input type="hidden" name="submitted" value="1" />
    <?php
}

function fudge_connect_display($args = array()) {
    //load options
    $connecttitle = get_option('fudge_connect_widget_title');
    $options = get_option('fudge_theme_options');
    //widget output
    echo stripslashes($args['before_widget']);
    ?>
    <section id="connect" class="secondary-bkg-color">
        <div class="container">
            <h2><?php echo stripslashes($connecttitle); ?></h2>
            <?php
            if ($options['fudge_email'] != '')
                echo '<a class="icon-email" title="Email ' . get_bloginfo('title') . '" href="mailto:' . $options['fudge_email'] . '">Email</a>';
            echo '<a class="icon-rss" title="Subscribe to ' . get_bloginfo('title') . ' RSS Feed" href="' . get_bloginfo('rss_url') . '">RSS</a>';
            if ($options['fudge_twitter'] != '')
                echo '<a class="icon-twitter" title="Follow ' . get_bloginfo('title') . ' on Twitter" href="http://www.twitter.com/' . $options['fudge_twitter'] . '">Twitter</a>';
            if ($options['fudge_facebook'] != '')
                echo '<a class="icon-facebook" title="Like ' . get_bloginfo('title') . ' on Facebook" href="' . $options['fudge_facebook'] . '">Facebook</a>';
            if ($options['fudge_flickr'] != '')
                echo '<a class="icon-flickr" title="See photos from ' . get_bloginfo('title') . '" href="http://www.flickr.com/' . $options['fudge_flickr'] . '">Flickr</a>';
            if ($options['fudge_linkedin'] != '')
                echo '<a class="icon-linkedin" title="Connect with ' . get_bloginfo('title') . ' on LinkedIn" href="' . $options['fudge_linkedin'] . '">LinkedIn</a>';
            if ($options['fudge_pinterest'] != '')
                echo '<a class="icon-pinterest" title="View pins from ' . get_bloginfo('title') . '" href="http://www.pinterest.com/' . $options['fudge_pinterest'] . '">Pinterest</a>';
            if ($options['fudge_instagram'] != '')
                echo '<a class="icon-instagram" title="View pictures from ' . get_bloginfo('title') . '" href="http://www.instagram.com/' . $options['fudge_instagram'] . '">Instagram</a>';
            if ($options['fudge_googleplus'] != '')
                echo '<a class="icon-googleplus" title="View ' . get_bloginfo('title') . '" href="' . $options['fudge_googleplus'] . '">Google+</a>';
            ?>
        </div>
    </section>
    <?php
    echo stripslashes($args['after_widget']);
}