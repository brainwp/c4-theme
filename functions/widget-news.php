<?php
// Latest News Custom Widget

wp_register_sidebar_widget(
        'fudge_news', // your unique widget id
        'Fudge Latest News', // widget name
        'fudge_news_display', // callback function to display widget
        array(// options
    'description' => __('Shows a section displaying the latest four posts', 'fudge')
        )
);

wp_register_widget_control(
        'fudge_news', // id
        'fudge_news', // name
        'fudge_news_control' // callback function
);

function fudge_news_control() {

    if (isset($_POST['submitted'])) {
        update_option('fudge_news_widget_title', $_POST['newstitle']);
        update_option('fudge_news_widget_tagline', $_POST['newstagline']);
        update_option('fudge_news_widget_menu', $_POST['newsmenu']);
        update_option('fudge_news_widget_link', $_POST['newslink']);
    }
    //load options
    $newstitle = get_option('fudge_news_widget_title');
    $newstagline = get_option('fudge_news_widget_tagline');
    $newsmenu = get_option('fudge_news_widget_menu');
    $newslink = get_option('fudge_news_widget_link');
    ?>
    <?php _e('Title:', 'fudge'); ?><br />
    <input type="text" class="widefat" name="newstitle" value="<?php echo stripslashes($newstitle); ?>" />
    <br /><br />
    <?php _e('Tagline:', 'fudge'); ?><br />
    <input type="text" class="widefat" name="newstagline" value="<?php echo stripslashes($newstagline); ?>" />
    <br /><br />
    <?php _e('Link:', 'fudge'); ?><br />
    <input type="text" class="widefat" name="newslink" value="<?php echo stripslashes($newslink); ?>" />
    <br /><br />
    <?php _e('Add to main navigation?', 'fudge'); ?><br />
    <input type="text" class="widefat" name="newsmenu" value="<?php echo stripslashes($newsmenu); ?>"/><br/>
    <small><?php _e('(Enter desired menu link text)', 'fudge'); ?></small>
    <br /><br />
    <input type="hidden" name="submitted" value="1" />
    <?php
}

function fudge_news_display($args = array()) {
    //load options
    $newstitle = get_option('fudge_news_widget_title');
    $newstagline = get_option('fudge_news_widget_tagline');
    $newslink = get_option('fudge_news_widget_link');
    //widget output
    echo stripslashes($args['before_widget']);
    ?>
    <section id="news"><div class="container">
            <h2><?php echo stripslashes($newstitle); ?></h2>
            <p class="tagline"><?php echo stripslashes($newstagline); ?></p>
            <div class="posts">
                <?php
                query_posts('posts_per_page=4');
                if (have_posts())
                    while (have_posts()) : the_post();
                        ?>
                        <div class="post">
                            <a title="<?php the_title(); ?>" href="<?php the_permalink(); ?>">
                                <?php the_post_thumbnail(); ?>
                                <h3><?php the_title(); ?></h3>
                            </a>
                            <p><?php the_time('F d, Y'); ?></p>
                        </div>
                        <?php
                    endwhile;
                wp_reset_query();
                ?>
            </div>
            <?php if (!empty($newslink)) { ?>
                <a class="btn secondary-bkg-color" title="<?php _e('View all News', 'fudge'); ?>" href="<?php echo $newslink; ?>"><?php _e('View all News', 'fudge'); ?></a>
            <?php } ?>
        </div>
    </section>
    <?php
    echo stripslashes($args['after_widget']);
}