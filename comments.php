<?php
/**
 * @package WordPress
 * @subpackage Amber App
 */
// Do not delete these lines
if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
    die(__('Please do not load this page directly. Thanks!', 'fudge'));

if (post_password_required()) {
    ?>
    <p class="nocomments"><?php _e('This post is password protected. Enter the password to view comments.', 'fudge'); ?></p>
    <?php
    return;
}
?>
<!-- You can start editing here. -->
<?php if (have_comments()) : ?>
    <ol class="commentlist">
        <?php wp_list_comments('callback=fudge_comment'); ?>
    </ol>
<?php else : // this is displayed if there are no comments so far ?>
    <?php if (comments_open()) : ?>
        <!-- If comments are open, but there are no comments. -->
    <?php else : // comments are closed ?>
        <!-- If comments are closed. -->
        <p class="nocomments"><?php _e('Comments are closed.', 'fudge'); ?></p>
    <?php endif; ?>
<?php endif; ?>
<?php /* BEGIN TRACKBACK/PINGBACK CODE */ ?>
<?php global $trackbacks; ?>
<?php if ($trackbacks) : ?>
    <?php $comments = $trackbacks; ?>
    <div id="pingback-trackback">
        <h3 id="trackbacks"><?php echo sizeof($trackbacks); ?> <?php _e('Trackbacks/Pingbacks', 'fudge'); ?></h3>
        <ol class="pings">
            <?php foreach ($comments as $comment) : ?>
                <!-- Start Your trackback Code -->
                <li <?php echo $oddcomment; ?>id="comment-<?php comment_ID() ?>">
                    <cite><?php comment_author_link() ?></cite>
                    <?php if ($comment->comment_approved == '0') : ?>
                        <em><?php _e('Your comment is awaiting moderation.', 'fudge'); ?></em>
                    <?php endif; ?>  
                </li>
                <!-- End Your trackback Code -->
                <?php
                /* Changes every other comment to a different class */
                $oddcomment = ( empty($oddcomment) ) ? 'class="alt" ' : '';
                ?>
            <?php endforeach; /* end for each comment */ ?>
        </ol>
    </div>
<?php endif; ?>
<?php /* END TRACKBACK/PINGBACK CODE */ ?>
<?php if (comments_open()) : ?>
    <section id="respond">
        <div class="cancel-comment-reply">
            <small><?php cancel_comment_reply_link(); ?></small>
        </div>
        <?php if (get_option('comment_registration') && !is_user_logged_in()) : ?>
            <p>You must be <a href="<?php echo wp_login_url(get_permalink()); ?>">logged in</a> to post a comment.</p>
        <?php else : ?>
            <form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">
                <?php if (is_user_logged_in()) : ?>
                    <p><?php _e('Logged in as', 'fudge'); ?> <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. <a href="<?php echo wp_logout_url(get_permalink()); ?>" title="<?php _e('Log out of this account', 'fudge'); ?>"><?php _e('Log out &raquo;', 'fudge'); ?></a></p>
                <?php else : ?>
                    <div class="comment-details">
                        <p><input type="text" name="author" id="author" placeholder="<?php _e('Name', 'fudge'); ?>" value="<?php echo esc_attr($comment_author); ?>" size="22" tabindex="1"/></p>
                        <p><input type="text" name="email" id="email" placeholder="<?php _e('Email', 'fudge'); ?>" value="<?php echo esc_attr($comment_author_email); ?>" size="22" tabindex="2" /></p>
                        <p><input type="text" name="url" id="url" placeholder="<?php _e('Website', 'fudge'); ?>" value="<?php echo esc_attr($comment_author_url); ?>" size="22" tabindex="3" /></p>
                    </div>
                <?php endif; ?>
                <div class="comment-field<?php if (is_user_logged_in()) echo ' logged-in'; ?>">
                    <p><textarea name="comment" id="comment" cols="70" rows="10" tabindex="4" placeholder="<?php _e('Comment', 'fudge'); ?>"></textarea></p>
                </div>
                <input class="btn secondary-bkg-color" name="submit" type="submit" id="submit" tabindex="5" value="<?php _e('Leave Comment', 'fudge'); ?>" />
                <?php comment_id_fields(); ?>
                </p>
                <?php do_action('comment_form', $post->ID); ?>
            </form>
        <?php endif; // If registration required and not logged in ?>
    </section>
<?php endif; // if you delete this the sky will fall on your head ?>