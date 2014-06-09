<?php
// Event Information Custom Widget

wp_register_sidebar_widget(
        'fudge_event_info', // your unique widget id
        'Fudge Event Info', // widget name
        'fudge_event_info_display', // callback function to display widget
        array(// options
    'description' => __('Shows a section displaying event time, date and location', 'fudge')
        )
);

wp_register_widget_control(
        'fudge_event_info', // id
        'fudge_event_info', // name
        'fudge_event_info_control' // callback function
);

function fudge_event_info_control() {
    //the form is submitted, save into database
    if (isset($_POST['submitted'])) {
        update_option('fudge_event_info_widget_title', $_POST['eventdate']);
        update_option('fudge_event_info_widget_eventcity', $_POST['eventcity']);
        update_option('fudge_event_info_widget_eventtime', $_POST['eventtime']);
        update_option('fudge_event_info_widget_eventlocation', $_POST['eventlocation']);
        update_option('fudge_event_info_widget_menu', $_POST['eventmenu']);
    }
    //load options
    $eventdate = get_option('fudge_event_info_widget_title');
    $eventcity = get_option('fudge_event_info_widget_eventcity');
    $eventtime = get_option('fudge_event_info_widget_eventtime');
    $eventlocation = get_option('fudge_event_info_widget_eventlocation');
    $eventmenu = get_option('fudge_event_info_widget_menu');
    ?>
    <?php _e('Event Date:', 'fudge'); ?><br />
    <input type="text" class="widefat" name="eventdate" value="<?php echo stripslashes($eventdate); ?>" />
    <br /><br />
    <?php _e('Event Starting Time:', 'fudge'); ?><br />
    <input type="text" class="widefat" name="eventtime" value="<?php echo stripslashes($eventtime); ?>"/>
    <br /><br />
    <?php _e('Event City &amp; Country:', 'fudge'); ?><br />
    <input type="text" class="widefat" name="eventcity" value="<?php echo stripslashes($eventcity); ?>" />
    <br /><br />
    <?php _e('Event Location:', 'fudge'); ?><br />
    <input type="text" class="widefat" name="eventlocation" value="<?php echo stripslashes($eventlocation); ?>"/>
    <br /><br />
    <?php _e('Add to main navigation?', 'fudge'); ?><br />
    <input type="text" class="widefat" name="eventmenu" value="<?php echo stripslashes($eventmenu); ?>"/><br/>
    <small><?php _e('(Enter desired menu link text)', 'fudge'); ?></small>
    <br /><br />
    <input type="hidden" name="submitted" value="1" />
    <?php
}

function fudge_event_info_display($args = array()) {
    //load options
    $eventdate = get_option('fudge_event_info_widget_title');
    $eventtime = get_option('fudge_event_info_widget_eventtime');
    $eventcity = get_option('fudge_event_info_widget_eventcity');
    $eventlocation = get_option('fudge_event_info_widget_eventlocation');
    //widget output
    echo stripslashes($args['before_widget']);
    ?>
    <section id="event-info" class="main-bkg-color">
        <div class="container">
            <?php
            if ($eventdate != '') {
                ?>
                <div class="event-when">
                    <h2><?php _e('When', 'fudge'); ?></h2>
                    <p><?php echo stripslashes($eventdate); ?></p>
                    <p><span><?php _e('Starting at', 'fudge'); ?> <?php echo stripslashes($eventtime); ?></span></p>
                </div>
                <div class="event-where">
                    <h2><?php _e('Where', 'fudge'); ?></h2>
                    <p><?php echo stripslashes($eventcity); ?></p>
                    <p><span><?php echo stripslashes($eventlocation); ?></span></p>
                </div>
                <?php
            }
            ?>
        </div>
    </section>
    <?php
    echo stripslashes($args['after_widget']);
}