<?php get_header(); ?>

<article>

    <div class="container">

        <h1><?php _e('404 Not Found', 'fudge'); ?></h1>

        <p><?php _e('Apologies, but the page you requested could not be found. Perhaps searching will help.', 'fudge'); ?></p>

        <?php get_search_form(); ?>

    </div>

    <section class="social-media">

        <div class="container">

            <?php fudge_print_social_media_links(); ?>

        </div>

    </section>

</article>

<?php get_footer(); ?>