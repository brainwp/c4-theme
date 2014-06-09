<?php
// Timer Custom Widget

wp_register_sidebar_widget(
        'fudge_timer', // your unique widget id
        'Fudge Event Timer', // widget name
        'fudge_timer_display', // callback function to display widget
        array(// options
    'description' => __('Shows a section displaying the event coundown clock', 'fudge')
        )
);

wp_register_widget_control(
        'fudge_timer', // id
        'fudge_timer', // name
        'fudge_timer_control' // callback function
);

add_action('load-widgets.php', 'my_custom_load12');

function my_custom_load12() {
    wp_enqueue_script('jquery-ui-datepicker');
    wp_enqueue_style('jquery-ui-datepicker', get_stylesheet_directory_uri() . '/css/datepicker/smoothness/jquery-ui-1.10.3.custom.min.css');
}

function fudge_timer_control() {
    //the form is submitted, save into database
    if (isset($_POST['submitted'])) {
        update_option('fudge_timer_widget_menu', $_POST['timermenu']);
        update_option('fudge_timer_widget_timerdate', strtotime($_POST['timerdate']) * 1000);
    }
    //load options
    $timermenu = get_option('fudge_timer_widget_menu');
    $timerdate = get_option('fudge_timer_widget_timerdate');
    ?>

    <script type='text/javascript'>  
        jQuery(document).ready(function($) {  
            $('.timerdatestr').datepicker({
                changeMonth:true,
                changeYear: true,
                altField: '.timerdate',
                altFormat: 'yy-mm-dd'
            });
        });  
    </script> 
    <?php _e('Add to main navigation?', 'fudge'); ?><br />
    <input type="text" class="widefat" name="timermenu" value="<?php echo stripslashes($timermenu); ?>"/><br/>
    <small><?php _e('(Enter desired menu link text)', 'fudge'); ?></small>
    <br /><br />
    <em><?php _e('Countdown Date:', 'fudge'); ?></em><br />
    <input type="text" class="timerdatestr" name="timerdatestr" value="<?php echo $timerdate ? date('m/d/Y', timestamp_fix($timerdate)) : ''; ?>"/>
    <input type="hidden" class="timerdate" name="timerdate" value="<?php echo $timerdate ? date('Y-m-d', timestamp_fix($timerdate)) : ''; ?>"/>
    <br /><br />
    <input type="hidden" name="submitted" value="1" />
    <?php
}

function fudge_timer_display($args = array()) {
    //load options
    $timerdate = get_option('fudge_timer_widget_timerdate');
    //widget output
    echo stripslashes($args['before_widget']);
    ?>
    <section id="timer" class="secondary-bkg-color">

		<div class="icon-timer-bg"></div>

        <div class="container">
            <input type="hidden" id="countdown_hidden" />
            
            <h2 class="countdown-title">Contagem Regressiva</h2>
            
            <div class="countdown">
                <?php
                if (!empty($timerdate)) {
                    ?>
                    <div class="days">
                        <span>0</span>
                        <span>0</span>
                        <span>0</span>
                        <?php _e('Days', 'fudge'); ?>
                    </div>
                    <div class="hours">
                        <span>0</span>
                        <span>0</span>
                        <?php _e('Hours', 'fudge'); ?>
                    </div>
                    <div class="minutes">
                        <span>0</span>
                        <span>0</span>
                        <?php _e('Minutes', 'fudge'); ?>
                    </div>
                    <div class="seconds">
                        <span>0</span>
                        <span>0</span>
                        <?php _e('Seconds', 'fudge'); ?>
                    </div>
                </div>
            </div>
            <?php
        }
        ?>
    </section>
    <?php
    echo stripslashes($args['after_widget']);
}