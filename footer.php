<?php
$options = get_option('fudge_theme_options');
$twitterhash = get_option('fudge_twitter_widget_twitterhash');
?>

<footer>
    <div class="container">
        <h2><?php bloginfo('title'); ?></h2>
        <p><?php echo $options['fudge_footer']; ?></p>
    </div>
</footer>

<!--[if lt IE 9 ]>
<script src="<?php bloginfo('template_url'); ?>/js/ie.js"></script>
<![endif]-->

<!--[if lt IE 8 ]>
  <script src="//ajax.googleapis.com/ajax/libs/chrome-frame/1.0.3/CFInstall.min.js"></script>
  <script>window.attachEvent('onload',function(){CFInstall.check({mode:'overlay'})})</script>
<![endif]-->

<?php wp_footer(); ?>

<script type="text/javascript">
    jQuery(document).ready(function(){
            
<?php if (get_page_template_slug() == 'page-twitter-full-screen.php' && !empty($twitterhash)) { ?>
            $('#twitter_update_list').tweetMachine(
            '#<?php echo $twitterhash; ?>', {
                backendScript: ajaxurl,
                tweetFormat: '<li><img class="avatar" src=""/><span class="content"></span><a href="" class="time"></a></li>',
                limit: 4,
                rate: 30000
            });
<?php } ?>

<?php
if (is_front_page()) {
    $poi_query = new WP_Query(array('post_type' => 'poi'));
    ?>                                        
                jQuery('#explore .container').gmap({scrollwheel: false}).bind('init', function(ev, map) {
    <?php
    if ($poi_query->have_posts()) {
        while ($poi_query->have_posts()) {
            $poi_query->the_post();
            $address = sprintf('%s %s<br/>%s - %s - %s<br/>%s', get_post_meta(get_the_ID(), 'street_address_1', true), get_post_meta(get_the_ID(), 'street_address_2', true), get_post_meta(get_the_ID(), 'city', true), get_post_meta(get_the_ID(), 'postal_code', true), get_post_meta(get_the_ID(), 'country', true), get_the_content());
            ?>
                                    jQuery('#explore .container').gmap('addMarker', {
                                        'position': '<?php echo get_post_meta(get_the_ID(), 'lat', true); ?>,<?php echo get_post_meta(get_the_ID(), 'lng', true); ?>',
                                        'bounds': true,
                                        'icon' : new google.maps.MarkerImage('<?php echo get_stylesheet_directory_uri(); ?>/images/schemes/<?php echo!empty($options['color_palette']) ? $options['color_palette'] : 'default'; ?>/icon-map-pointer.png')
                                    }).click(function() {
                                        jQuery('#explore .container').gmap('openInfoWindow', {'content': <?php echo json_encode($address); ?>}, this);
                                    });
            <?php
        }
    }
    wp_reset_postdata();
    ?>
                });
				jQuery('#explore .container').gmap('option', 'zoom', 10);
                                                            
                jQuery('#explore a[data-lat]').click(function(e){
                    e.preventDefault();
                    jQuery('#explore .container').gmap('get','map').setOptions({'center': new google.maps.LatLng(jQuery(this).attr('data-lat'),jQuery(this).attr('data-lng'))});
                });
                    
                if($(window).width() <= mobile_width){
                    $('#schedule .date-picker-mobile ul li a:first').trigger('click');
                    $('#event-media .filter ul li a:first').trigger('click');
                }
                else{
                    jQuery('#schedule .date-picker a:first').trigger('click');
                    jQuery('#event-media .media-category a:first').trigger('click');
                }
                jQuery('#speakers .btn-more').trigger('click');
<?php } ?>
    });
</script>

</body>
</html>