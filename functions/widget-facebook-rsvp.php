<?php
// Facebook RSVP Custom Widget

wp_register_sidebar_widget(
        'fudge_facebook_rsvp', // your unique widget id
        'Fudge Facebook RSVP Stats', // widget name
        'fudge_facebook_rsvp_display', // callback function to display widget
        array(// options
    'description' => 'Shows a section displaying users who\'ve RSVP\'d via Facebook'
        )
);

wp_register_widget_control(
        'fudge_facebook_rsvp', // id
        'fudge_facebook_rsvp', // name
        'fudge_facebook_rsvp_control' // callback function
);

function fudge_facebook_rsvp_control() {
    //the form is submitted, save into database
    if (isset($_POST['submitted'])) {
        update_option('fudge_facebook_rsvp_widget_eventlink', $_POST['eventlink']);
        update_option('fudge_facebook_rsvp_widget_menu', $_POST['rsvpmenu']);
        update_option('fudge_facebook_rsvp_widget_appid', $_POST['rsvpappid']);
        update_option('fudge_facebook_rsvp_widget_secret', $_POST['rsvpsecret']);
        update_option('fudge_facebook_rsvp_widget_eventid', $_POST['rsvpeventid']);
    }
    //load options
    $eventlink = get_option('fudge_facebook_rsvp_widget_eventlink');
    $rsvpmenu = get_option('fudge_facebook_rsvp_widget_menu');
    $rsvpappid = get_option('fudge_facebook_rsvp_widget_appid');
    $rsvpsecret = get_option('fudge_facebook_rsvp_widget_secret');
    $rsvpeventid = get_option('fudge_facebook_rsvp_widget_eventid');
    ?>
    <?php _e('Link to Event on Facebook:', 'fudge'); ?><br />
    <input type="text" class="widefat" name="eventlink" value="<?php echo stripslashes($eventlink); ?>"/>
    <br /><br />
    <?php _e('Add to main navigation?', 'fudge'); ?><br />
    <input type="text" class="widefat" name="rsvpmenu" value="<?php echo stripslashes($rsvpmenu); ?>"/><br/>
    <small><?php _e('(Enter desired menu link text)', 'fudge'); ?></small>
    <br /><br />
    <em><?php _e('Event ID:', 'fudge'); ?></em><br />
    <input type="text" class="rsvpeventid" name="rsvpeventid" value="<?php echo stripslashes($rsvpeventid); ?>"/>
    <br /><br />
    <em><?php _e('App ID:', 'fudge'); ?></em><br />
    <input type="text" class="rsvpappid" name="rsvpappid" value="<?php echo stripslashes($rsvpappid); ?>"/>
    <br /><br />
    <em><?php _e('Secret Key:', 'fudge'); ?></em><br />
    <input type="text" class="rsvpsecret" name="rsvpsecret" value="<?php echo stripslashes($rsvpsecret); ?>"/>
    <br /><br />
    <input type="hidden" name="submitted" value="1" />
    <?php
}

function fudge_facebook_rsvp_display($args = array()) {
    global $facebook;
    //load options
    $eventlink = get_option('fudge_facebook_rsvp_widget_eventlink');
    $rsvpeventid = get_option('fudge_facebook_rsvp_widget_eventid');
    //widget output
    echo stripslashes($args['before_widget']);
    ?>
    <section id="facebook-rsvp" class="secondary-bkg-color"><div class="container">
            <?php
            $invited = array(
                'summary' => array(
                    'attending_count' => 0,
                    'maybe_count' => 0,
                    'declined_count' => 0
                )
            );
            if (isset($facebook) && !empty($rsvpeventid))
            //$access_token = $facebook->getAccessToken();
                $invited = $facebook->api("/$rsvpeventid/invited?limit=1&summary=1");
            ?>
            <div class="facebook-rsvp-yes"><span><?php echo $invited['summary']['attending_count']; ?></span><?php _e('Yes', 'fudge'); ?></div>
            <div class="facebook-rsvp-maybe"><span><?php echo $invited['summary']['maybe_count']; ?></span><?php _e('Maybe', 'fudge'); ?></div>
            <div class="facebook-rsvp-no"><span><?php echo $invited['summary']['declined_count']; ?></span><?php _e('No', 'fudge'); ?></div>
            <a class="btn secondary-text-color" href="<?php echo stripslashes($eventlink); ?>"><?php _e('View on Facebook', 'fudge'); ?></a>
        </div>
    </section>
    <?php
    echo stripslashes($args['after_widget']);
}