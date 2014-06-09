<?php
// Registration Custom Widget

wp_register_sidebar_widget(
        'fudge_registration', // your unique widget id
        'Fudge Registration', // widget name
        'fudge_registration_display', // callback function to display widget
        array(// options
    'description' => __('Shows registration information & ticket display', 'fudge')
        )
);

wp_register_widget_control(
        'fudge_registration', // id
        'fudge_registration', // name
        'fudge_registration_control' // callback function
);

function fudge_registration_control() {
    //the form is submitted, save into database
    if (isset($_POST['submitted'])) {
        update_option('fudge_registration_widget_title', $_POST['registrationtitle']);
        update_option('fudge_registration_widget_registrationtagline', $_POST['registrationtagline']);
        update_option('fudge_registration_widget_registrationtext', $_POST['registrationtext']);
        update_option('fudge_registration_widget_registrationeventbrite', $_POST['registrationeventbrite']);
        update_option('fudge_registration_widget_menu', $_POST['registrationmenu']);
    }
    //load options
    $registrationtitle = get_option('fudge_registration_widget_title');
    $registrationtagline = get_option('fudge_registration_widget_registrationtagline');
    $registrationtext = get_option('fudge_registration_widget_registrationtext');
    $registrationeventbrite = get_option('fudge_registration_widget_registrationeventbrite');
    $registrationmenu = get_option('fudge_registration_widget_menu');
    ?>
    <?php _e('Title:', 'fudge'); ?><br />
    <input type="text" class="widefat" name="registrationtitle" value="<?php echo stripslashes($registrationtitle); ?>" />
    <br /><br />
    <?php _e('Tagline:', 'fudge'); ?><br />
    <input type="tagline" class="widefat" name="registrationtagline" value="<?php echo stripslashes($registrationtagline); ?>"/>
    <br /><br />
    <?php _e('Main Text:', 'fudge'); ?><br />
    <textarea rows="10" class="widefat" name="registrationtext"><?php echo stripslashes($registrationtext); ?></textarea>
    <br /><br />
    <?php _e('Registration Embed Code:', 'fudge'); ?><br />
    <textarea rows="10" class="widefat" name="registrationeventbrite"><?php echo stripslashes($registrationeventbrite); ?></textarea>
    <br /><br />
    <?php _e('Add to main navigation?', 'fudge'); ?><br />
    <input type="text" class="widefat" name="registrationmenu" value="<?php echo stripslashes($registrationmenu); ?>"/><br/>
    <small>(Enter desired menu link text)</small>
    <br /><br />
    <input type="hidden" name="submitted" value="1" />
    <?php
}

function fudge_registration_display($args = array()) {
    //load options
    $registrationtitle = get_option('fudge_registration_widget_title');
    $registrationtext = get_option('fudge_registration_widget_registrationtext');
    $registrationtagline = get_option('fudge_registration_widget_registrationtagline');
    $registrationeventbrite = get_option('fudge_registration_widget_registrationeventbrite');
    //widget output
    echo stripslashes($args['before_widget']);
    ?>
    <section id="registration"><div class="container">
            <h2 ><?php echo stripslashes($registrationtitle); ?></h2>
            <p><span><?php echo stripslashes($registrationtagline); ?></span></p>
            <p><?php echo stripslashes($registrationtext); ?></p>
            <div class="eventbrite"><?php echo do_shortcode(stripslashes($registrationeventbrite)); ?></div>
        </div>
    </section>
    <?php
    echo stripslashes($args['after_widget']);
}