<?php get_header(); ?>

<article>
    <div class="container">
        <?php get_sidebar(); ?>

        <section id="blog">
            <?php if (have_posts()) while (have_posts()) : the_post(); ?>
                    <h1><?php the_title(); ?></h1>
                    <?php the_content(); ?>

                    <p class="meta"><?php _e('Posted','fudge'); ?> <?php the_time('F d, Y'); ?> in: <?php the_category(', '); ?> by <?php the_author(); ?></p>

                    <!-- AddThis Button BEGIN -->
                    <div class="addthis_toolbox addthis_default_style ">
                        <a class="addthis_counter addthis_pill_style"></a>
                    </div>
                    <!-- AddThis Button END -->
                <?php endwhile; ?>

            <?php comments_template(); ?>

            <div class="navigation">
                <span class="older"><a title="<?php _e('Back to list','fudge'); ?>" href="<?php bloginfo('url'); ?>/category/blog"><?php _e('&laquo; Back To List','fudge'); ?></a></span>
            </div>
        </section>
    </div>

    <section class="social-media">
        <div class="container">
            <?php fudge_print_social_media_links(); ?>
        </div>
    </section>
</article>

<?php get_footer(); ?>