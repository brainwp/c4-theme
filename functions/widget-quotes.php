<?php
// Sidebar Quotes Custom Widget

wp_register_sidebar_widget(
        'fudge_quotes', // your unique widget id
        'Fudge Sidebar Quotes', // widget name
        'fudge_quotes_display', // callback function to display widget
        array(// options
    'description' => __('Shows a section displaying latest tweets', 'fudge')
        )
);

wp_register_widget_control(
        'fudge_quotes', // id
        'fudge_quotes', // name
        'fudge_quotes_control' // callback function
);

function fudge_quotes_control() {
    //the form is submitted, save into database
    if (isset($_POST['submitted'])) {
        update_option('fudge_quotes_widget_quote1', $_POST['quote1']);
        update_option('fudge_quotes_widget_quotetime1', $_POST['quotetime1']);
        update_option('fudge_quotes_widget_quote2', $_POST['quote2']);
        update_option('fudge_quotes_widget_quotetime2', $_POST['quotetime2']);
    }
    //load options
    $quote1 = get_option('fudge_quotes_widget_quote1');
    $quotetime1 = get_option('fudge_quotes_widget_quotetime1');
    $quote2 = get_option('fudge_quotes_widget_quote2');
    $quotetime2 = get_option('fudge_quotes_widget_quotetime2');
    ?>
    <h3><?php _e('Quote', 'fudge'); ?></h3>
    <?php _e('Text', 'fudge'); ?><br />
    <textarea rows="5" class="widefat" name="quote1"><?php echo stripslashes($quote1); ?></textarea><br/>
    <br />
    <?php _e('Time', 'fudge'); ?><br />
    <input type="text" class="widefat" name="quotetime1" value="<?php echo stripslashes($quotetime1); ?>"/><br/>
    <br /><br />
    <hr/>
    <h3><?php _e('Quote', 'fudge'); ?></h3>
    <?php _e('Text', 'fudge'); ?><br />
    <textarea rows="5" class="widefat" name="quote2"><?php echo stripslashes($quote2); ?></textarea><br/>
    <br />
    <?php _e('Time', 'fudge'); ?><br />
    <input type="text" class="widefat" name="quotetime2" value="<?php echo stripslashes($quotetime2); ?>"/><br/>
    <br /><br />
    <input type="hidden" name="submitted" value="1" />
    <?php
}

function fudge_quotes_display($args = array()) {
    //load options
    $quote1 = get_option('fudge_quotes_widget_quote1');
    $quotetime1 = get_option('fudge_quotes_widget_quotetime1');
    $quote2 = get_option('fudge_quotes_widget_quote2');
    $quotetime2 = get_option('fudge_quotes_widget_quotetime2');
    //widget output
    echo stripslashes($args['before_widget']);
    ?>
    <div class="quote">
        <p><?php echo stripslashes($quote1); ?></p>
        <p><span><?php echo stripslashes($quotetime1); ?></span></p>
    </div>
    <div class="quote">
        <p><?php echo stripslashes($quote2); ?></p>
        <p><span><?php echo stripslashes($quotetime2); ?></span></p>
    </div>
    <?php
    echo stripslashes($args['after_widget']);
}