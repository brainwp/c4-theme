<?php
// Event Description Custom Widget

wp_register_sidebar_widget(
        'fudge_event_description', // your unique widget id
        'Fudge Event Description', // widget name
        'fudge_event_description_display', // callback function to display widget
        array(// options
    'description' => __('Shows the event description', 'fudge')
        )
);

wp_register_widget_control(
        'fudge_event_description', // id
        'fudge_event_description', // name
        'fudge_event_description_control' // callback function
);

function fudge_event_description_control() {
    //the form is submitted, save into database
    if (isset($_POST['submitted'])) {
        update_option('fudge_event_description_widget_title', $_POST['eventdescriptiontitle']);
        update_option('fudge_event_description_widget_content', $_POST['eventdescriptioncontent']);
        update_option('fudge_event_description_widget_menu', $_POST['eventdescriptionmenu']);
    }
    //load options
    $eventdescriptiontitle = get_option('fudge_event_description_widget_title');
    $eventdescriptioncontent = get_option('fudge_event_description_widget_content');
    $eventdescriptionmenu = get_option('fudge_event_description_widget_menu');
    ?>
    <?php _e('Title:', 'fudge'); ?><br />
    <input type="text" class="widefat" name="eventdescriptiontitle" value="<?php echo stripslashes($eventdescriptiontitle); ?>" />
    <br /><br />
    <?php _e('Content:', 'fudge'); ?><br />
    <textarea id="eventdescriptioncontent" name="eventdescriptioncontent" class="widefat" rows="10"><?php echo stripslashes($eventdescriptioncontent); ?></textarea>
    <br /><br />
    <?php _e('Add to main navigation?', 'fudge'); ?><br />
    <input type="text" class="widefat" name="eventdescriptionmenu" value="<?php echo stripslashes($eventdescriptionmenu); ?>"/><br/>
    <small>(Enter desired menu link text)</small>
    <br /><br />
    <input type="hidden" name="submitted" value="1" />
    <?php
}

function fudge_event_description_display($args = array()) {
    //load options
    $eventdescriptiontitle = get_option('fudge_event_description_widget_title');
    $eventdescriptioncontent = get_option('fudge_event_description_widget_content');
    //widget output
    echo stripslashes($args['before_widget']);
    ?>
    <section id="event-description">
        <div class="container">
            <h2 ><?php echo stripslashes($eventdescriptiontitle); ?></h2>
            <div class="content"><?php echo do_shortcode(stripslashes($eventdescriptioncontent)); ?></div>
        </div>
    </section>
    <?php
    echo stripslashes($args['after_widget']);
}