<?php
// explore Custom Widget

wp_register_sidebar_widget(
        'fudge_explore', // your unique widget id
        'Fudge Points of Interest', // widget name
        'fudge_explore_display', // callback function to display widget
        array(// options
    'description' => __('Shows a section displaying points of interest & maps', 'fudge')
        )
);

wp_register_widget_control(
        'fudge_explore', // id
        'fudge_explore', // name
        'fudge_explore_control' // callback function
);

function fudge_explore_control() {
    //the form is submitted, save into database
    if (isset($_POST['submitted'])) {
        update_option('fudge_explore_widget_maintitle', $_POST['exploremaintitle']);
        update_option('fudge_explore_widget_secondarytitle', $_POST['exploresecondarytitle']);
        update_option('fudge_explore_widget_tagline', $_POST['exploretagline']);
        update_option('fudge_explore_widget_menu', $_POST['exploremenu']);
    }
    //load options
    $exploremaintitle = get_option('fudge_explore_widget_maintitle');
    $exploresecondarytitle = get_option('fudge_explore_widget_secondarytitle');
    $exploretagline = get_option('fudge_explore_widget_tagline');
    $exploremenu = get_option('fudge_explore_widget_menu');
    ?>
    <em><?php _e('Main Title:', 'fudge'); ?></em><br />
    <input type="text" class="widefat" name="exploremaintitle" value="<?php echo stripslashes($exploremaintitle); ?>"/>
    <br /><br />
    <em><?php _e('Secondary Title', 'fudge'); ?></em><br />
    <input type="text" class="widefat" name="exploresecondarytitle" value="<?php echo stripslashes($exploresecondarytitle); ?>"/>
    <br /><br />
    <em><?php _e('Tagline', 'fudge'); ?>:</em><br />
    <input type="text" class="widefat" name="exploretagline" value="<?php echo stripslashes($exploretagline); ?>"/>
    <br /><br />
    <?php _e('Add to main navigation?', 'fudge'); ?><br />
    <input type="text" class="widefat" name="exploremenu" value="<?php echo stripslashes($exploremenu); ?>"/><br/>
    <small><?php _e('(Enter desired menu link text)', 'fudge'); ?></small>
    <br /><br />
    <input type="hidden" name="submitted" value="1" />
    <?php
}

function fudge_explore_display($args = array()) {
    //load options
    $exploremaintitle = get_option('fudge_explore_widget_maintitle');
    $exploresecondarytitle = get_option('fudge_explore_widget_secondarytitle');
    $exploretagline = get_option('fudge_explore_widget_tagline');
    $poi_query = new WP_Query(array('post_type' => 'poi', 'orderby' => 'menu_order', 'order' => 'ASC'));
    //widget output
    echo stripslashes($args['before_widget']);
    ?>
    <section id="explore">
        <div class="container">
        </div>
        <div class="poi secondary-bkg-color">
            <h2><?php echo stripslashes($exploremaintitle); ?></h2>
            <div class="content">
                <h3><?php echo stripslashes($exploresecondarytitle); ?></h3>
                <p class="tagline"><?php echo stripslashes($exploretagline); ?></p>
                <p>
                    <?php
                    if ($poi_query->have_posts()) {
                        while ($poi_query->have_posts()) {
                            $poi_query->the_post();
                            echo '<a href="#" data-lat="' . get_post_meta(get_the_ID(), 'lat', true) . '" data-lng="' . get_post_meta(get_the_ID(), 'lng', true) . '">' . get_the_title() . '</a><br/>';
                        }
                    }
                    wp_reset_postdata();
                    ?>
                </p>
            </div>
        </div>
    </section>
    <?php
    echo stripslashes($args['after_widget']);
}