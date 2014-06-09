<?php
// media Custom Widget

wp_register_sidebar_widget(
        'fudge_media', // your unique widget id
        'Fudge Media Grid', // widget name
        'fudge_media_display', // callback function to display widget
        array(// options
    'description' => __('Shows a section displaying media by category in the media custom post type', 'fudge')
        )
);

wp_register_widget_control(
        'fudge_media', // id
        'fudge_media', // name
        'fudge_media_control' // callback function
);

function fudge_media_control() {
    //the form is submitted, save into database
    if (isset($_POST['submitted'])) {
        update_option('fudge_media_widget_title', $_POST['mediatitle']);
        update_option('fudge_media_widget_tagline', $_POST['mediatagline']);
        update_option('fudge_media_widget_menu', $_POST['mediamenu']);
        update_option('fudge_media_widget_moretext', $_POST['mediamoretext']);
        update_option('fudge_media_widget_lesstext', $_POST['medialesstext']);
    }
    //load options
    $mediatitle = get_option('fudge_media_widget_title');
    $mediatagline = get_option('fudge_media_widget_tagline');
    $mediamenu = get_option('fudge_media_widget_menu');
    $mediamoretext = get_option('fudge_media_widget_moretext');
    $medialesstext = get_option('fudge_media_widget_lesstext');
    ?>
    <?php _e('Title:', 'fudge'); ?>
    <input type="text" class="widefat" name="mediatitle" value="<?php echo stripslashes($mediatitle); ?>"/>
    <br /><br />
    <?php _e('Tagline:', 'fudge'); ?>
    <input type="text" class="widefat" name="mediatagline" value="<?php echo stripslashes($mediatagline); ?>"/>
    <br /><br />
    <?php _e('"View More" Button Text:', 'fudge'); ?>
    <input type="text" class="widefat" name="mediamoretext" value="<?php echo stripslashes($mediamoretext); ?>"/>
    <br /><br />
    <?php _e('"View Less" Button Text:', 'fudge'); ?>
    <input type="text" class="widefat" name="medialesstext" value="<?php echo stripslashes($medialesstext); ?>"/>
    <br /><br />
    <?php _e('Add to main navigation?', 'fudge'); ?><br />
    <input type="text" class="widefat" name="mediamenu" value="<?php echo stripslashes($mediamenu); ?>"/><br/>
    <small><?php _e('(Enter desired menu link text)', 'fudge'); ?></small>
    <br /><br />
    <input type="hidden" name="submitted" value="1" />
    <?php
}

function fudge_media_display($args = array()) {
    //load options
    $mediatitle = get_option('fudge_media_widget_title');
    $mediatagline = get_option('fudge_media_widget_tagline');
    $mediamoretext = get_option('fudge_media_widget_moretext');
    $medialesstext = get_option('fudge_media_widget_lesstext');
    //widget output
    echo stripslashes($args['before_widget']);
    ?>
    <section id="event-media" class="main-bkg-color">
        <div class="container">
            <h2><?php echo stripslashes($mediatitle); ?></h2>
            <p class="tagline"><?php echo stripslashes($mediatagline); ?></p>
            <div class="sorting">
                <ul class="media-category">
                    <li>
                        <a class="btn all active" title="<?php _e('All Media', 'fudge'); ?>" href="#" data-id="0"><?php _e('All', 'fudge'); ?></a>
                        <?php
                        $terms = get_terms("media-type");
                        $count = count($terms);
                        if ($count > 0) {
                            foreach ($terms as $term) {
                                ?>
                            <li><a class="btn <?php echo $term->name; ?>" href='#' data-id="<?php echo $term->term_id; ?>"><?php echo $term->name; ?></a></li>
                            <?php
                        }
                    }
                    ?>
                </ul>
            </div>
            <ul class="filter track">
                <li>
                    <a title="<?php _e('Filter Results', 'fudge'); ?>" data-id="" data-ignore-click="1"><?php _e('Filter Results', 'fudge'); ?></a>
                    <ul>
                        <li><a title="<?php _e('All Media', 'fudge'); ?>" href="#" data-id="0"><?php _e('All', 'fudge'); ?></a></li>
                        <?php
                        if ($count > 0) {
                            foreach ($terms as $term) {
                                ?>
                                <li><a title="<?php echo $term->name; ?>" href='#' data-id="<?php echo $term->term_id; ?>"><?php echo $term->name; ?></a></li>
                                <?php
                            }
                        }
                        ?>
                    </ul>
                </li>
            </ul>
            <div id="media-grid">
                <div id="all-media">
                </div>
            </div>
            <input type="hidden" id="cur_media_page" value="0" />
            <a class="btn btn-less main-text-color" title="<?php echo !empty($medialesstext) ? $medialesstext : __('View Less', 'fudge'); ?>" href="#"><?php echo !empty($medialesstext) ? $medialesstext : __('View Less', 'fudge'); ?></a>
            <a class="btn btn-more main-text-color" title="<?php echo !empty($mediamoretext) ? $mediamoretext : __('View More', 'fudge'); ?>" href="#"><?php echo !empty($mediamoretext) ? $mediamoretext : __('View More', 'fudge'); ?></a>
        </div>
    </section>
    <?php
    echo stripslashes($args['after_widget']);
}