<?php
// Speakers Custom Widget

wp_register_sidebar_widget(
        'fudge_speakers', // your unique widget id
        'Fudge Speaker List', // widget name
        'fudge_speakers_display', // callback function to display widget
        array(// options
    'description' => __('Shows a section displaying speakers created in the Speakers custom post type', 'fudge')
        )
);

wp_register_widget_control(
        'fudge_speakers', // id
        'fudge_speakers', // name
        'fudge_speakers_control' // callback function
);

function fudge_speakers_control() {
    //the form is submitted, save into database
    if (isset($_POST['submitted'])) {
        update_option('fudge_speakers_widget_title', $_POST['speakerstitle']);
        update_option('fudge_speakers_widget_tagline', $_POST['speakerstagline']);
        update_option('fudge_speakers_widget_menu', $_POST['speakersmenu']);
        update_option('fudge_speakers_widget_moretext', $_POST['speakersmoretext']);
        update_option('fudge_speakers_widget_lesstext', $_POST['speakerslesstext']);
    }
    //load options
    $speakerstitle = get_option('fudge_speakers_widget_title');
    $speakerstagline = get_option('fudge_speakers_widget_tagline');
    $speakersmenu = get_option('fudge_speakers_widget_menu');
    $speakersmoretext = get_option('fudge_speakers_widget_moretext');
    $speakerslesstext = get_option('fudge_speakers_widget_lesstext');
    ?>
    <?php _e('Title:', 'fudge'); ?><br />
    <input type="text" class="widefat" name="speakerstitle" value="<?php echo stripslashes($speakerstitle); ?>"/>
    <br /><br />
    <?php _e('Tagline:', 'fudge'); ?><br />
    <input type="text" class="widefat" name="speakerstagline" value="<?php echo stripslashes($speakerstagline); ?>"/>
    <br /><br />
    <?php _e('"View More" Button Text:', 'fudge'); ?>
    <input type="text" class="widefat" name="speakersmoretext" value="<?php echo stripslashes($speakersmoretext); ?>"/>
    <br /><br />
    <?php _e('"View Less" Button Text:', 'fudge'); ?>
    <input type="text" class="widefat" name="speakerslesstext" value="<?php echo stripslashes($speakerslesstext); ?>"/>
    <br /><br />
    <?php _e('Add to main navigation?', 'fudge'); ?><br />
    <input type="text" class="widefat" name="speakersmenu" value="<?php echo stripslashes($speakersmenu); ?>"/><br/>
    <small><?php _e('(Enter desired menu link text)', 'fudge'); ?></small>
    <br /><br />
    <input type="hidden" name="submitted" value="1" />
    <?php
}

function fudge_speakers_display($args = array()) {
    //load options
    $speakerstitle = get_option('fudge_speakers_widget_title');
    $speakerstagline = get_option('fudge_speakers_widget_tagline');
    $speakersmoretext = get_option('fudge_speakers_widget_moretext');
    $speakerslesstext = get_option('fudge_speakers_widget_lesstext');
    //widget output
    echo stripslashes($args['before_widget']);
    ?>
    <section id="speakers" class="main-bkg-color">
        <div class="container">
            <h2><?php echo stripslashes($speakerstitle); ?></h2>
            <p class="tagline"><?php echo stripslashes($speakerstagline); ?></p>
            <div id="speakers-grid">
                <div id="all-speakers">
                </div>
            </div>
            <input type="hidden" id="cur_speakers_page" value="0" />
            <a class="btn btn-less main-text-color" title="<?php echo!empty($speakerslesstext) ? $speakerslesstext : __('View Less', 'fudge'); ?>" href="#"><?php echo!empty($speakerslesstext) ? $speakerslesstext : __('View Less', 'fudge'); ?></a>
            <a class="btn btn-more main-text-color" title="<?php echo!empty($speakersmoretext) ? $speakersmoretext : __('View More', 'fudge'); ?>" href="#"><?php echo!empty($speakersmoretext) ? $speakersmoretext : __('View More', 'fudge'); ?></a>
        </div>
        <div class="lightbox-container speaker-pop">
            <div class="lightbox">
            </div>
        </div>
    </section>
    <?php
    echo stripslashes($args['after_widget']);
}