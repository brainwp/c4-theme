<?php get_header(); ?>

<h1><?php _e('Search Results', 'fudge'); ?></h1>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <h1><a href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h1>
        <?php the_excerpt(); ?>
    <?php endwhile; ?>
<?php else : ?>
    <p><?php _e('No posts found. Try a different search?', 'fudge'); ?></p>
    <?php get_search_form(); ?>
<?php endif; ?>

<?php get_sidebar(); ?>
<?php get_footer(); ?>