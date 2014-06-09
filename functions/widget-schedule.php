<?php
// schedule With Us Custom Widget

wp_register_sidebar_widget(
        'fudge_schedule', // your unique widget id
        'Fudge Event Schedule', // widget name
        'fudge_schedule_display', // callback function to display widget
        array(// options
    'description' => __('Displays Event Schedule widget', 'fudge')
        )
);

wp_register_widget_control(
        'fudge_schedule', // id
        'fudge_schedule', // name
        'fudge_schedule_control' // callback function
);

function fudge_schedule_control() {
    //the form is submitted, save into database
    if (isset($_POST['submitted'])) {
        update_option('fudge_schedule_widget_title', $_POST['scheduletitle']);
        update_option('fudge_schedule_widget_tagline', $_POST['scheduletagline']);
        update_option('fudge_schedule_widget_menu', $_POST['schedulemenu']);
        update_option('fudge_schedule_widget_text', $_POST['scheduletext']);
        update_option('fudge_schedule_widget_moretext', $_POST['schedulemoretext']);
        update_option('fudge_schedule_widget_lesstext', $_POST['schedulelesstext']);
		update_option('fudge_schedule_widget_link', $_POST['schedulelink']);
    }
    //load options
    $scheduletitle = get_option('fudge_schedule_widget_title');
    $scheduletagline = get_option('fudge_schedule_widget_tagline');
    $schedulemenu = get_option('fudge_schedule_widget_menu');
    $scheduletext = get_option('fudge_schedule_widget_text');
    $schedulemoretext = get_option('fudge_schedule_widget_moretext');
    $schedulelesstext = get_option('fudge_schedule_widget_lesstext');
	$schedulelink = get_option('fudge_schedule_widget_link');
    ?>
    <?php _e('Title:', 'fudge'); ?><br />
    <input type="text" class="widefat" name="scheduletitle" value="<?php echo stripslashes($scheduletitle); ?>" />
    <br /><br />
    <?php _e('Tagline:', 'fudge'); ?><br />
    <input type="text" class="widefat" name="scheduletagline" value="<?php echo stripslashes($scheduletagline); ?>" />
    <br /><br />
    <?php _e('Main Text:', 'fudge'); ?><br />
    <textarea rows="10" class="widefat" name="scheduletext"><?php echo stripslashes($scheduletext); ?></textarea>
    <br /><br />
    <?php _e('"View More" Button Text:', 'fudge'); ?>
    <input type="text" class="widefat" name="schedulemoretext" value="<?php echo stripslashes($schedulemoretext); ?>"/>
    <br /><br />
    <?php _e('"View Less" Button Text:', 'fudge'); ?>
    <input type="text" class="widefat" name="schedulelesstext" value="<?php echo stripslashes($schedulelesstext); ?>"/>
    <br /><br />
    <?php _e('Add to main navigation?', 'fudge'); ?><br />
    <input type="text" class="widefat" name="schedulemenu" value="<?php echo stripslashes($schedulemenu); ?>"/><br/>
    <small><?php _e('(Enter desired menu link text)', 'fudge'); ?></small>
    <br /><br />
     <?php _e('Download Link:', 'fudge'); ?><br />
    <input type="text" class="widefat" name="schedulelink" value="<?php echo stripslashes($schedulelink); ?>" />
    <br /><br />
    <input type="hidden" name="submitted" value="1" />
    <?php
}

function fudge_schedule_display($args = array()) {
    //load options
    $scheduletitle = get_option('fudge_schedule_widget_title');
    $scheduletagline = get_option('fudge_schedule_widget_tagline');
    $scheduletext = get_option('fudge_schedule_widget_text');
    $session_dates = fudge_get_session_dates();
    $session_tracks = get_terms('session-track');
    $session_locations = get_terms('session-location');
    $schedulemoretext = get_option('fudge_schedule_widget_moretext');
    $schedulelesstext = get_option('fudge_schedule_widget_lesstext');
	$schedulelink = get_option('fudge_schedule_widget_link');

    //widget output

    echo stripslashes($args['before_widget']);
    ?>
    <section id="schedule">
        <div class="container">
            <h2><?php echo stripslashes($scheduletitle); ?></h2>
            <p class="tagline"><?php echo stripslashes($scheduletagline); ?></p>
            <p><?php echo stripslashes($scheduletext); ?></p>
            <div class="date-picker">
                <?php
                foreach ($session_dates as $session_date) {
                    ?>
                    <a href="#" data-timestamp="<?php echo $session_date->meta_value; ?>">
                        <span class="weekday"><?php setlocale(LC_ALL, 'pt_BR.utf8'); echo(strftime ('%A', timestamp_fix($session_date->meta_value))); ?></span>
                        <span class="date"><?php echo(date('d', timestamp_fix($session_date->meta_value))); ?></span>
                        <span class="month"><?php setlocale(LC_ALL, 'pt_BR'); echo(strftime('%b', timestamp_fix($session_date->meta_value))); ?></span>
                    </a>
                    <?php
                }
                ?>
            </div>
            <div class="filters">
                <ul class="filter date-picker-mobile main-bkg-color">
                    <li>
                        <a title="<?php _e('Filter by Track', 'fudge'); ?>" data-timestamp="" data-ignore-click="1"></a>
                        <ul>
                            <?php
                            foreach ($session_dates as $session_date) {
                                ?>
                                <li>
                                    <a href="#" data-timestamp="<?php echo $session_date->meta_value; ?>"><?php echo date(get_option('date_format'), timestamp_fix($session_date->meta_value)); ?></a>
                                </li>
                                <?php
                            }
                            ?>
                        </ul>
                    </li>
                </ul>
                <ul class="filter track">
                    <li>
                        <a title="<?php _e('Filter by Track', 'fudge'); ?>" data-track="0"><?php _e('Filter By Track', 'fudge'); ?></a>
                        <ul>
                            <li><a href="#" data-track="0"><?php _e('Reset', 'fudge'); ?></a></li>
                            <?php
                            foreach ($session_tracks as $session_track) {
                                ?>
                                <li><a href="#" data-track="<?php echo $session_track->term_id; ?>"><?php echo $session_track->name; ?></a></li>
                                <?php
                            }
                            ?>
                        </ul>
                    </li>
                </ul>
                <ul class="filter location">
                    <li>
                        <a title="<?php _e('Filter by Location', 'fudge'); ?>" data-location="0"><?php _e('Filter By Location', 'fudge'); ?></a>
                        <ul>
                            <li><a href="#" data-track="0"><?php _e('Reset', 'fudge'); ?></a></li>
                            <?php
                            foreach ($session_locations as $session_location) {
                                ?>
                                <li><a href="#" data-location="<?php echo $session_location->term_id; ?>"><?php echo $session_location->name; ?></a></li>
                                <?php
                            }
                            ?>
                        </ul>
                    </li>
                </ul>
            </div>
            <div id="schedule-sessions">
                <input type="hidden" id="cur_page" value="0" />
                <a class="btn-less main-bkg-color" title="<?php echo!empty($schedulelesstext) ? $schedulelesstext : __('View Less', 'fudge'); ?>" href="#"><?php echo!empty($schedulelesstext) ? $schedulelesstext : __('View Less', 'fudge'); ?></a>
                <a class="btn-more main-bkg-color" title="<?php echo!empty($schedulemoretext) ? $schedulemoretext : __('View More', 'fudge'); ?>" href="#"><?php echo!empty($schedulemoretext) ? $schedulemoretext : __('View More', 'fudge'); ?></a>
                <a class="btn-download-programacao main-bkg-color open-popup-link" href="#download">Baixe a Programação</a>
            </div>
        </div>
        <div class="lightbox-container session-info">
            <div class="lightbox">
            </div>
        </div>
    </section> 
    <?php
    echo stripslashes($args['after_widget']);
}