<?php get_header(); ?>
<article>
    <div class="container">
        <?php if (have_posts()) while (have_posts()) : the_post(); ?>
                <h1><?php the_title(); ?></h1>
                <?php if ($myImageFileName = get_post_meta($post->ID, 'tagline', true)) { ?><p class="tagline"><?php echo get_post_meta($post->ID, 'tagline', true); ?></p><?php } ?>
                <?php the_content(); ?>
            <?php endwhile; ?>
    </div>
    <section class="social-media">
        <div class="container">
            <?php fudge_print_social_media_links(); ?>
        </div>
    </section>
</article>
<?php get_footer(); ?>