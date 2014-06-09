<?php
$options = get_option('fudge_theme_options');
$color_scheme = !empty($options['color_palette']) ? $options['color_palette'] : 'default';
?>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> 
<!--<![endif]-->
<html <?php language_attributes(); ?>>
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
        <title><?php wp_title('&laquo;', true, 'right'); ?> <?php bloginfo('name'); ?></title>
        <link href='http://fonts.googleapis.com/css?family=Muli' rel='stylesheet' type='text/css' />
        <link href='http://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css' />
        <link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/css/schemes/<?php echo $color_scheme; ?>.css" />
        <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
        <?php wp_head(); ?>
    </head>
    <body <?php body_class(); ?>>

			<!-- Chamar e rodar o magnificPopup -->
			 <script type="text/javascript">
			 jQuery(function() {
				jQuery('.open-popup-link').magnificPopup({
				  type:'inline',
				  midClick: true // allow opening popup on middle mouse click. Always set it to true if you don't provide alternative source.
				});
			 });
            </script>
            
        <div id="fb-root"></div>
        <?php if ($options['fudge_eventover']) { ?>
            <section id="hero" <?php if ($options['fudge_hero']) { ?>style="background-image: url('<?php echo $options['fudge_hero']; ?>');"<?php } ?>>
                <div class="container">
                    <h1><?php echo $options['fudge_overtitle']; ?></h1>
                    <p><?php echo $options['fudge_overtagline']; ?></p>
                    <a class="btn secondary-bkg-color"><?php _e('Event Over', 'fudge'); ?></a>
                </div>
            <?php } else if (is_front_page()) { ?>
                <section id="hero" >
                    <!-- REMOVIDO DO HERO
                    <?php if ($options['fudge_hero']) { ?>style="background-image: url('<?php echo $options['fudge_hero']; ?>');"<?php } ?>
                    -->
                    <!--div class="container">
                        <h1><?php echo $options['fudge_herotitle']; ?></h1>
                        <p><?php echo $options['fudge_herotagline']; ?></p>
                        <a class="btn secondary-bkg-color" title="<?php _e('Register Now', 'fudge'); ?>" href="#contact"><?php _e('Register Now', 'fudge'); ?></a>
                    </div-->
                    <?php echo do_shortcode("[metaslider id=98]"); ?>
                <?php } ?>
                <header class="menu-header">
                    <div class="container">
                        <?php if (is_front_page() || $options['fudge_eventover']) { ?>
                            <?php } else { ?>	
                                <?php } ?>
                                <?php if ($options['fudge_logo']) { ?>
                                <?php } else { ?>
                                <?php } ?>

							<div id="contato" class="white-popup mfp-hide">
								<?php
									$contact = get_page_by_title( 'Contato' );
									if( $contact ) :
									echo apply_filters( 'the_content', $contact->post_content);
									endif;
								?>
							</div>
							<div id="call" class="white-popup mfp-hide">
								<?php
									$call = get_post(136); 
									if( $call ) :
										echo "<h2>" . $call->post_title . "</h2>";
										echo apply_filters( 'the_content', $call->post_content);
									endif;
								?>
							</div>
                            <div id="download" class="white-popup mfp-hide">
                                <?php
                                    $schedulelink = get_option('fudge_schedule_widget_link');
                                    $call = get_post(181); 
                                    if( $call ) :
                                        echo "<h2>" . $call->post_title . "</h2>";
                                        echo apply_filters( 'the_content', $call->post_content);
                                    endif;
                                ?>
                            </div>
                            
                            <div id="patrocine" class="white-popup mfp-hide">
                                <?php
                                    $call = get_post(107); 
                                    if( $call ) :
                                        echo "<h2>" . $call->post_title . "</h2>";
                                        echo apply_filters( 'the_content', $call->post_content);
                                    endif;
                                ?>
                            </div>

                            <ul>
                                <?php if (get_option('fudge_event_info_widget_menu')) { ?>
                                    <li><a title="<?php bloginfo('title'); ?>" href="<?php bloginfo('url'); ?>#event-info"><?php echo get_option('fudge_event_info_widget_menu'); ?></a></li>
                                <?php } ?>
                                <?php if (get_option('fudge_event_description_widget_menu')) { ?>
                                    <li><a title="<?php bloginfo('title'); ?>" href="<?php bloginfo('url'); ?>#event-description"><?php echo get_option('fudge_event_description_widget_menu'); ?></a></li>
                                <?php } ?>
                                <?php if (get_option('fudge_connect_widget_menu')) { ?>
                                    <li><a title="<?php bloginfo('title'); ?>" href="<?php bloginfo('url'); ?>#connect"><?php echo get_option('fudge_connect_widget_menu'); ?></a></li>
                                <?php } ?>
                                <?php if (get_option('fudge_schedule_widget_menu')) { ?>
                                    <li><a title="<?php echo get_option('fudge_schedule_widget_menu'); ?>" href="<?php bloginfo('url'); ?>#schedule"><?php echo get_option('fudge_schedule_widget_menu'); ?></a></li>
                                <?php } ?>
                                <?php if (get_option('fudge_facebook_rsvp_widget_menu')) { ?>
                                    <li><a title="<?php echo get_option('fudge_facebook_rsvp_widget_menu'); ?>" href="<?php bloginfo('url'); ?>#facebook-rsvp"><?php echo get_option('fudge_facebook_rsvp_widget_menu'); ?></a></li>
                                <?php } ?>
                                <?php if (get_option('fudge_speakers_widget_menu')) { ?>
                                    <li><a title="<?php echo get_option('fudge_speakers_widget_menu'); ?>" href="<?php bloginfo('url'); ?>#speakers"><?php echo get_option('fudge_speakers_widget_menu'); ?></a></li>
                                <?php } ?>
                                <?php if (get_option('fudge_sponsors_widget_menu')) { ?>
                                    <li><a title="<?php echo get_option('fudge_sponsors_widget_menu'); ?>" href="<?php bloginfo('url'); ?>#sponsors"><?php echo get_option('fudge_sponsors_widget_menu'); ?></a></li>
                                <?php } ?>
                                <?php if (get_option('fudge_media_widget_menu')) { ?>
                                    <li><a title="<?php echo get_option('fudge_media_widget_menu'); ?>" href="<?php bloginfo('url'); ?>#event-media"><?php echo get_option('fudge_media_widget_menu'); ?></a></li>
                                <?php } ?>
                                <?php if (get_option('fudge_twitter_widget_menu')) { ?>
                                    <li><a title="<?php echo get_option('fudge_twitter_widget_menu'); ?>" href="<?php bloginfo('url'); ?>#twitter"><?php echo get_option('fudge_twitter_widget_menu'); ?></a></li>
                                <?php } ?>
                                <?php if (get_option('fudge_timer_widget_menu')) { ?>
                                    <li><a title="<?php echo get_option('fudge_timer_widget_menu'); ?> <?php bloginfo('title'); ?>" href="<?php bloginfo('url'); ?>#timer"><?php echo get_option('fudge_timer_widget_menu'); ?></a></li>
                                <?php } ?>
                                <?php if (get_option('fudge_explore_widget_menu')) { ?>
                                    <li><a title="<?php echo get_option('fudge_explore_widget_menu'); ?> <?php bloginfo('title'); ?>" href="<?php bloginfo('url'); ?>#explore"><?php echo get_option('fudge_explore_widget_menu'); ?></a></li>
                                <?php } ?>
                                <?php if (get_option('fudge_contact_widget_menu')) { ?>
                                    <li><a title="<?php echo get_option('fudge_contact_widget_menu'); ?>" class="open-popup-link" href="#contato"><?php echo get_option('fudge_contact_widget_menu'); ?></a></li>
                                <?php } ?>
                                <?php if (get_option('fudge_registration_widget_menu')) { ?>
                                    <li class="register"><a class="btn-register secondary-bkg-color" title="<?php echo get_option('fudge_registration_widget_menu'); ?>" href="<?php bloginfo('url'); ?>#registration"><?php echo get_option('fudge_registration_widget_menu'); ?></a></li>
                                <?php } ?>

                                    <li class="each-menu-botoes inscreva-se">
                                        <a class="open-popup-link" title="<?php bloginfo('title'); ?>" href="#inscrevase-popup"></a>
                                    </li><!-- each-menu-botoes inscreva-se -->
                                    <li class="each-menu-botoes download">
                                        <a class="open-popup-link" title="<?php bloginfo('title'); ?>" href="#download"></a>
                                    </li><!-- each-menu-botoes download -->
                                    <li class="each-menu-botoes parceiro">
                                        <a class="open-popup-link" title="<?php bloginfo('title'); ?>" href="#patrocine"></a>
                                    </li><!-- each-menu-botoes parceiro -->

									<li class="info">
										<p class="menu-p fone">11-41918463</p>
                                        <p class="menu-p email"><a href="mailto:info.c4@fsacademy.com.br">info.c4@fsacademy.com.br</a></p>
									</li>
                            </ul>
                            <a class="mobile-nav-icon"></a>
                            <?php if (get_option('fudge_registration_widget_menu')) { ?>
                                <a class="btn-register secondary-bkg-color outside-nav" title="<?php echo get_option('fudge_registration_widget_menu'); ?>" href="<?php bloginfo('url'); ?>#registration"><?php echo get_option('fudge_registration_widget_menu'); ?></a>
                            <?php } ?>
                    </div>
                </header><!-- .menu-header -->
                <?php if (is_front_page() || $options['fudge_eventover']) { ?></section><?php } ?>
