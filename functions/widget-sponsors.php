<?php
// Sponsors Custom Widget

wp_register_sidebar_widget(
        'fudge_sponsors', // your unique widget id
        'Fudge Sponsor List', // widget name
        'fudge_sponsors_display', // callback function to display widget
        array(// options
    'description' => __('Shows a section displaying sponsors by tier type created in the Sponsors custom post type', 'fudge')
        )
);

wp_register_widget_control(
        'fudge_sponsors', // id
        'fudge_sponsors', // name
        'fudge_sponsors_control' // callback function
);

function fudge_sponsors_control() {
    //the form is submitted, save into database
    if (isset($_POST['submitted'])) {
        update_option('fudge_sponsors_widget_title', $_POST['sponsorstitle']);
        update_option('fudge_sponsors_widget_tagline', $_POST['sponsorstagline']);
        update_option('fudge_sponsors_widget_link', $_POST['sponsorslink']);
        update_option('fudge_sponsors_widget_menu', $_POST['sponsorsmenu']);
		update_option('fudge_sponsors_widget_sponsorsdownload', $_POST['sponsorsdownload']);
    }

    //load options
    $sponsorstitle = get_option('fudge_sponsors_widget_title');
    $sponsorstagline = get_option('fudge_sponsors_widget_tagline');
    $sponsorsmenu = get_option('fudge_sponsors_widget_menu');
    $sponsorslink = get_option('fudge_sponsors_widget_menu');
	$sponsorsdownload = get_option('fudge_sponsors_widget_sponsorsdownload');
    ?>

    <?php _e('Title:', 'fudge'); ?><br />

    <input type="text" class="widefat" name="sponsorstitle" value="<?php echo stripslashes($sponsorstitle); ?>"/>

    <br /><br />

    <?php _e('Tagline:', 'fudge'); ?><br />

    <input type="text" class="widefat" name="sponsorstagline" value="<?php echo stripslashes($sponsorstagline); ?>"/>

    <br /><br />

    <?php _e('Add to main navigation?', 'fudge'); ?><br />

    <input type="text" class="widefat" name="sponsorsmenu" value="<?php echo stripslashes($sponsorsmenu); ?>"/><br/>

    <small><?php _e('(Enter desired menu link text)', 'fudge'); ?></small>

    <br /><br />

    <?php _e('Download Link:', 'fudge'); ?><br />

    <input type="text" class="widefat" name="sponsorsdownload" value="<?php echo stripslashes($sponsorsdownload); ?>" />

    <br /><br />

    <input type="hidden" name="submitted" value="1" />

    <?php

}

function fudge_sponsors_display($args = array()) {

    //load options
    $sponsorstitle = get_option('fudge_sponsors_widget_title');
    $sponsorstagline = get_option('fudge_sponsors_widget_tagline');
    //widget output
    echo stripslashes($args['before_widget']);
    ?>

    <section id="sponsors">
        <div class="container">
            <h2><?php echo stripslashes($sponsorstitle) ?></h2>
            <p class="tagline"><?php echo stripslashes($sponsorstagline); ?></p>
        </div>
        <?php
        $i = 0;
        $categories = get_terms('sponsor-tier');
        foreach ($categories as $category):
            $i++;
            ?>
            <div class="sponsor-tier cada-<?php echo $i; ?> sponsor-<?php echo $category->slug; ?>">
                <div class="container">
                    <h3><?php echo $category->name; ?></h3>
                    <?php
                    $args = array(
                        'posts_per_page' => -1,
                        'post_type' => 'sponsors',
                        'orderby' => 'menu_order',
                        'tax_query' => array(
                            array(
                                'taxonomy' => 'sponsor-tier',
                                'field' => 'slug',
                                'terms' => array($category->slug)
                            ),
                        )
                    );
                    $query = new WP_Query($args);
                    if ($query->have_posts()) :
                        while ($query->have_posts()) :
                            setup_postdata($query->the_post());

							$details = get_post_meta(get_the_ID(), 'details', true);
							$phone = get_post_meta(get_the_ID(), 'phone', true);
							$email = get_post_meta(get_the_ID(), 'email', true);
							$link = get_post_meta(get_the_ID(), 'link', true);
							$id_link = sanitize_title( get_the_title() );
					?>

						<div id="<?php echo $id_link; ?>" class="white-popup mfp-hide">
							<h2><?php the_title(); ?></h2>
							<?php the_post_thumbnail('full'); ?>
							<?php if ( $details ) : ?>
								<div class="sponsor-details">
									<?php echo $details; ?>
								</div>
							<?php endif; ?>

							<?php if ( $phone ) : ?>
								<div class="sponsor-phone">
									<?php _e('Phone ', 'fudge'); ?><?php echo $phone; ?>
								</div>
							<?php endif; ?>

							<?php if ( $email ) : ?>
								<div class="sponsor-email">
									<?php _e('Email ', 'fudge'); ?><?php echo $email; ?>
								</div>
							<?php endif; ?>

							<?php if ( $link ) : ?>
									<a class="btn-site-sponsor" href="<?php echo $link; ?>" target="_blank"><?php _e('Visit the Site', 'fudge'); ?></a>
							<?php endif; ?>

						</div>
						<a class='open-popup-link' href='#<?php echo $id_link; ?>' title="<?php get_the_title(); ?>" target='_blank'>

                            <?php the_post_thumbnail('full'); ?>

						</a>

					<?php

                        endwhile;

                        wp_reset_query();

					?>
                    </div>

                </div>

                <?php

            endif;

        endforeach;

        ?>

        <div class="container">

            <a class="btn secondary-bkg-color open-popup-link" title="<?php _e('Sponsor this event', 'fudge'); ?>" href="<?php echo get_option('fudge_sponsors_widget_sponsorsdownload'); ?>"><?php _e('Sponsor The Event', 'fudge'); ?></a>

        </div>

    </section>

    <?php

    echo stripslashes($args['after_widget']);

}