<?php
// Contact Us Custom Widget

wp_register_sidebar_widget(
        'fudge_contact', // your unique widget id
        'Fudge Contact Form', // widget name
        'fudge_contact_display', // callback function to display widget
        array(// options
    'description' => __('Shows a section displaying the latest four posts', ' fudge')
        )
);

wp_register_widget_control(
        'fudge_contact', // id
        'fudge_contact', // name
        'fudge_contact_control' // callback function
);

function fudge_contact_control() {

    if (isset($_POST['submitted'])) {
        update_option('fudge_contact_widget_title', $_POST['contacttitle']);
        update_option('fudge_contact_widget_tagline', $_POST['contacttagline']);
        update_option('fudge_contact_widget_email', $_POST['contactemail']);
        update_option('fudge_contact_widget_menu', $_POST['contactmenu']);
        update_option('fudge_contact_widget_recaptcha_publickey', $_POST['recaptchapublickey']);
        update_option('fudge_contact_widget_recaptcha_privatekey', $_POST['recaptchaprivatekey']);
    }
    //load options
    $contacttitle = get_option('fudge_contact_widget_title');
    $contacttagline = get_option('fudge_contact_widget_tagline');
    $contactemail = get_option('fudge_contact_widget_email');
    $contactmenu = get_option('fudge_contact_widget_menu');
    $recaptchapublickey = get_option('fudge_contact_widget_recaptcha_publickey');
    $recaptchaprivatekey = get_option('fudge_contact_widget_recaptcha_privatekey');
    ?>
    <?php _e('Title:', 'fudge'); ?><br />
    <input type="text" class="widefat" name="contacttitle" value="<?php echo stripslashes($contacttitle); ?>" />
    <br /><br />
    <?php _e('Tagline:', 'fudge'); ?><br />
    <input type="text" class="widefat" name="contacttagline" value="<?php echo stripslashes($contacttagline); ?>" />
    <br /><br />
    <?php _e('Email Address To Send Forms To:', 'fudge'); ?><br />
    <input type="text" class="widefat" name="contactemail" value="<?php echo stripslashes($contactemail); ?>" />
    <br /><br />
    <?php _e('Recaptcha Public Key:', 'fudge'); ?><br />
    <input type="text" class="widefat" name="recaptchapublickey" value="<?php echo stripslashes($recaptchapublickey); ?>" />
    <br /><br />
    <?php _e('Recaptcha Private Key:', 'fudge'); ?><br />
    <input type="text" class="widefat" name="recaptchaprivatekey" value="<?php echo stripslashes($recaptchaprivatekey); ?>" />
    <br /><br />
    <?php _e('Add to main navigation?', 'fudge'); ?><br />
    <input type="text" class="widefat" name="contactmenu" value="<?php echo stripslashes($contactmenu); ?>"/><br/>
    <small><?php _e('(Enter desired menu link text)', 'fudge'); ?></small>
    <br /><br />
    <input type="hidden" name="submitted" value="1" />
    <?php
}

function fudge_contact_display($args = array()) {
    //load options
    $contacttitle = get_option('fudge_contact_widget_title');
    $contacttagline = get_option('fudge_contact_widget_tagline');
    $recaptchapublickey = get_option('fudge_contact_widget_recaptcha_publickey');
    $recaptchaprivatekey = get_option('fudge_contact_widget_recaptcha_privatekey');

    //widget <output></output>
    echo stripslashes($args['before_widget']);
    ?>
    <section id="contact">
        <div class="container">
            <h2><?php echo stripslashes($contacttitle); ?></h2>
            <p class="tagline"><?php echo stripslashes($contacttagline); ?></p>
            <div id="contact-form">
                <form id="contact-us" method="post">
                    <div class="message-details">
                        <p>
                            <!--input type="text" name="contactName" class="requiredField" placeholder="Name"/-->
                            <input type="text" name="contactName" class="requiredField" placeholder="Nome"/>
                        </p>
                        <p>
                            <!--input type="text" name="phone" placeholder="Number"/-->
                            <input type="text" name="phone" placeholder="Telefone"/>
                        </p>
                        <p>
                            <input type="email" name="email" class="requiredField" placeholder="Email"/>
                        </p>
                    </div>
                    <div class="message">
                        <!--textarea name="comments" class="requiredField" placeholder="Message"></textarea-->
                        <textarea name="comments" class="requiredField" placeholder="Mensagem"></textarea>
                    </div>
                    <?php
                    if (!empty($recaptchapublickey) && !empty($recaptchaprivatekey))
                        echo recaptcha_get_html($recaptchapublickey);
                    ?>
                    <input type="hidden" name="action" value="send_contact_email" />
                    <!--input type="submit" name="submit" class="subbutton btn secondary-bkg-color" value="Send Message"/-->
                    <input type="submit" name="submit" class="subbutton btn secondary-bkg-color" value="Enviar Mensagem"/>
                    <input type="hidden" name="submitted" id="submitted" value="true" />
                </form>		
                <div class="social-media">
                    <?php fudge_print_social_media_links(); ?>
                </div>	
            </div>
        </div>
    </section>
    <?php
    echo stripslashes($args['after_widget']);
}