<?php

function fudge_get_random_words($str, $count) {
    $words = str_word_count($str, 1);
    shuffle($words);
    return implode(' ', array_slice($words, 0, $count - 1));
}

function fudge_move_images_to_upload_dir() {
    $upload_info = wp_upload_dir();
    $upload_dir = $upload_info['basedir'];
    if (file_exists($upload_dir)) {
        if (!file_exists($upload_dir . DIRECTORY_SEPARATOR . 'dummy'))
            mkdir($upload_dir . DIRECTORY_SEPARATOR . 'dummy');

        if (file_exists($upload_dir . DIRECTORY_SEPARATOR . 'dummy')) {
            $src_path = get_stylesheet_directory() . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'dummy';
            $dst_path = $upload_dir . DIRECTORY_SEPARATOR . 'dummy';
            $files = array('speaker1.jpg', 'speaker2.jpg', 'speaker3.jpg', 'speaker4.jpg', 'speaker5.jpg', 'event1.jpg', 'event2.jpg', 'event3.jpg', 'event4.jpg', 'event5.jpg', 'sponsor1.png', 'sponsor2.png');
            foreach ($files as $file)
                copy($src_path . DIRECTORY_SEPARATOR . $file, $dst_path . DIRECTORY_SEPARATOR . $file);
        }
    }
}

function fudge_import_dummy_thumbnail($filename, $post_id, $featured) {
    $upload_info = wp_upload_dir();
    $upload_dir = $upload_info['basedir'];
    $filename = $upload_dir . DIRECTORY_SEPARATOR . 'dummy' . DIRECTORY_SEPARATOR . $filename;
    $wp_filetype = wp_check_filetype(basename($filename), null);
    $attachment = array(
        'guid' => $upload_info['url'] . '/' . basename($filename),
        'post_mime_type' => $wp_filetype['type'],
        'post_title' => preg_replace('/\.[^.]+$/', '', basename($filename)),
        'post_content' => '',
        'post_status' => 'inherit'
    );
    $attach_id = wp_insert_attachment($attachment, $filename, $post_id);
    $attach_data = wp_generate_attachment_metadata($attach_id, $filename);
    wp_update_attachment_metadata($attach_id, $attach_data);
    if ($featured)
        if (!is_wp_error($attach_id))
            set_post_thumbnail($post_id, $attach_id);

    return $attach_id;
}

function fudge_install_dummy_content() {

    $news = array();
    $poi = array();
    $sessions = array();
    $speakers = array();
    $locations = array();
    $tracks = array();
    $sponsors = array();
    $sponsor_tiers = array();
    $media_types = array();
    $medias = array();

    $dummy_content = 'Sed a elementum sed tincidunt cursus nisi ridiculus, nascetur turpis est placerat, enim nunc, proin, tortor. Est augue mid porta aenean amet, tempor, lundium, est tortor turpis. Magna! Augue dignissim, in dictumst, dapibus eu magna arcu. Mid sit mauris montes augue magna. Lectus urna mattis! Sociis cursus sit porttitor aenean risus nisi ac in. Turpis placerat mattis. Vut pid amet, integer nascetur dapibus ut integer vut ut egestas est urna augue tempor quis nunc placerat! Elit enim, magnis duis parturient, velit vel massa! Et, amet placerat scelerisque rhoncus et placerat. Hac et ac turpis duis ultricies parturient augue phasellus non, vel ultrices, diam platea arcu lorem et lorem quis adipiscing sed nec eros nunc hac massa parturient dis, turpis ultrices.Dis porta pulvinar, diam in, cursus sit natoque proin, sociis turpis. A turpis mattis nec risus. Risus pid magna augue montes purus lundium vel tristique nec nec elementum nascetur diam et eros, platea odio est. Nec natoque, risus parturient. Auctor, cum, dolor auctor egestas. Rhoncus arcu augue, eu etiam? Nisi pulvinar. Pulvinar risus nunc integer amet nunc adipiscing lundium, vel ac natoque? Enim aliquam risus turpis magnis natoque, eros in enim! Elementum rhoncus! Elementum tristique egestas cursus purus scelerisque egestas, ultricies tincidunt risus, placerat proin? Eu dapibus? Augue, montes, et integer pulvinar in, urna proin, in? Porta vel, ultrices sit massa, parturient proin, ut amet non amet ultricies ac? Egestas ridiculus? Elementum et, tempor ac placerat! Rhoncus? Dictumst adipiscing.';
    $dummy_locations = array('Plenary Room', 'Pavilion Room', 'Auditorium', 'Faculty Lounge', 'Room A');
    $dummy_tracks = array('Social Media', 'Marketing', 'Technology', 'Creativity', 'Mobile Development');
    $speakers_names = array('Robert', 'John', 'Sean', 'Mark');
    $speakers_surnames = array('Harris', 'Martin', 'Robinson', 'Scott');
    $dummy_pois = array(
        array('New York', '40.71455', '-74.007124'),
        array('Jersey City', '40.7174', '-74.043234'),
        array('Ellis Island', '40.698679', '-74.039062'),
    );
    $dummy_news_images = array('event1.jpg', 'event2.jpg', 'event3.jpg', 'event4.jpg', 'event5.jpg');
    $dummy_speaker_images = array('speaker1.jpg', 'speaker2.jpg', 'speaker3.jpg', 'speaker4.jpg', 'speaker5.jpg');
    $dummy_sponsors_images = array('sponsor1.png', 'sponsor2.png');
    $dummy_sponsors_tiers = array('Conference Sponsor', 'Media Sponsor', 'Organizer');
    $dummy_media_types = array('Photos', 'Other Photos');

    fudge_move_images_to_upload_dir();

    // Locations
    foreach ($dummy_locations as $location) {
        $ret = array();
        $term_tmp = term_exists($location, 'session-location');

        if ($term_tmp !== 0 && $term_tmp !== null)
            $ret['term_id'] = $term_tmp['term_id'];
        else
            $ret = wp_insert_term($location, 'session-location', array('slug' => sanitize_title($location)));

        if (!is_wp_error($ret))
            $locations[] = $ret['term_id'];
    }

    // Tracks
    foreach ($dummy_tracks as $track) {
        $ret = array();
        $term_tmp = term_exists($track, 'session-track');

        if ($term_tmp !== 0 && $term_tmp !== null)
            $ret['term_id'] = $term_tmp['term_id'];
        else
            $ret = wp_insert_term($track, 'session-track', array('slug' => sanitize_title($track)));

        if (!is_wp_error($ret))
            $tracks[] = $ret['term_id'];
    }

    // Sponsor Tiers
    foreach ($dummy_sponsors_tiers as $sponsor_tier) {
        $term_tmp = term_exists($sponsor_tier, 'sponsor-tier');

        if ($term_tmp !== 0 && $term_tmp !== null)
            $ret['term_id'] = $term_tmp[term_id];
        else
            $ret = wp_insert_term($sponsor_tier, 'sponsor-tier');

        if (!is_wp_error($ret))
            $sponsor_tiers[] = $ret['term_id'];
    }

    // Media Types
    foreach ($dummy_media_types as $media_type) {
        $ret = array();
        $term_tmp = term_exists($media_type, 'media-type');

        if ($term_tmp !== 0 && $term_tmp !== null)
            $ret['term_id'] = $term_tmp['term_id'];
        else
            $ret = wp_insert_term($media_type, 'media-type', array('slug' => sanitize_title($media_type)));

        if (!is_wp_error($ret))
            $media_types[] = $ret['term_id'];
    }

    // POI
    foreach ($dummy_pois as $poi) {
        $poi_id = wp_insert_post(array(
            'post_content' => '',
            'post_title' => ucfirst($poi[0]),
            'post_status' => 'publish',
            'post_type' => 'poi'
                ));
        update_post_meta($poi_id, 'street_address_1', $poi[0]);
        update_post_meta($poi_id, 'lat', $poi[1]);
        update_post_meta($poi_id, 'lng', $poi[2]);
        $poi[] = $poi_id;
    }

    // Sponsors
    for ($i = 1; $i <= 10; $i++) {
        $sponsor_id = wp_insert_post(array(
            'post_content' => '',
            'post_title' => ucfirst(strtolower(fudge_get_random_words($dummy_content, 5))),
            'post_status' => 'publish',
            'post_type' => 'sponsors'
                ));
        wp_set_object_terms($sponsor_id, $sponsor_tiers[array_rand($sponsor_tiers)], 'sponsor-tier');
        fudge_import_dummy_thumbnail($dummy_sponsors_images[array_rand($dummy_sponsors_images)], $sponsor_id, true);
        update_post_meta($sponsor_id, 'link', 'http://eventmanagerblog.com');
        $sponsors[] = $sponsor_id;
    }

    // Speakers
    for ($i = 1; $i <= 10; $i++) {
        $speaker_id = wp_insert_post(array(
            'menu_order' => $i,
            'post_content' => ucfirst(strtolower(fudge_get_random_words($dummy_content, 50))),
            'post_title' => $speakers_names[array_rand($speakers_names)] . ' ' . $speakers_surnames[array_rand($speakers_surnames)],
            'post_status' => 'publish',
            'post_type' => 'speaker'));
        fudge_import_dummy_thumbnail($dummy_speaker_images[array_rand($dummy_speaker_images)], $speaker_id, true);
        $speakers[] = $speaker_id;
        update_post_meta($speaker_id, 'company', "Company $i");
        update_post_meta($speaker_id, 'short_bio', "Owner");
        update_post_meta($speaker_id, 'website_url', "http://www.eventmanagerblog.com");
        update_post_meta($speaker_id, 'twitter_username', "@EventMB");
    }

    // News
    for ($i = 1; $i <= 10; $i++) {
        $news_id = wp_insert_post(array(
            'post_content' => ucfirst(strtolower(fudge_get_random_words($dummy_content, 50))),
            'post_title' => ucfirst(strtolower(fudge_get_random_words($dummy_content, 5))),
            'post_status' => 'publish'
                ));
        fudge_import_dummy_thumbnail($dummy_news_images[array_rand($dummy_news_images)], $news_id, true);
        $news[] = $news_id;
    }

    // Sessions
    for ($i = 1; $i <= 8; $i++) {
        $session_id = wp_insert_post(array(
            'post_content' => ucfirst(strtolower(fudge_get_random_words($dummy_content, 50))),
            'post_title' => ucfirst(strtolower(fudge_get_random_words($dummy_content, 8))),
            'post_status' => 'publish',
            'post_type' => 'session'
                ));
        wp_set_object_terms($session_id, $dummy_locations[array_rand($dummy_locations)], 'session-location');
        wp_set_object_terms($session_id, $dummy_tracks[array_rand($dummy_tracks)], 'session-track');
        $sessions[] = $session_id;
        update_post_meta($session_id, 'date', strtotime("+" . rand(27, 28) . ' days') * 1000);
        update_post_meta($session_id, 'time', date('H:i', strtotime("+" . rand(2, 1440) . ' minutes')));
        update_post_meta($session_id, 'speakers_list', array($speakers[array_rand($speakers)]));
    }

    // Event Media
    for ($i = 1; $i <= 30; $i++) {
        $media_id = wp_insert_post(array(
            'post_content' => ucfirst(strtolower(fudge_get_random_words($dummy_content, 5))),
            'post_title' => ucfirst(strtolower(fudge_get_random_words($dummy_content, 3))),
            'post_status' => 'publish',
            'post_type' => 'event-media'
                ));
        wp_set_object_terms($media_id, $dummy_media_types[array_rand($dummy_media_types)], 'media-type');
        fudge_import_dummy_thumbnail($dummy_news_images[array_rand($dummy_news_images)], $media_id, true);
        $medias[] = $media_id;
    }

    // Widget Options

    /*update_option('fudge_event_info_widget_eventcity', 'Nashville, TN');
    update_option('fudge_event_info_widget_eventtime', 'Starting at 10:00 am');
    update_option('fudge_event_info_widget_eventlocation', 'Opryland Hotel');
    update_option('fudge_event_info_widget_menu', '');

    update_option('fudge_registration_widget_title', 'Register Now');
    update_option('fudge_registration_widget_registrationtagline', 'Don’t miss out on this truly unique London based event.');
    update_option('fudge_registration_widget_registrationtext', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas congue aliquet pharetra. Integer sed tempor ante. Nulla mollis orci non sem bibendum iaculis. Vivamus faucibus, elit eu vehicula porta, quam velit interdum eros, vel tristique neque lectus sit amet eros.');
    update_option('fudge_registration_widget_registrationeventbrite', '<iframe src="http://www.eventbrite.com/tickets-external?eid=2804433135&ref=etckt&v=2"  height="306"></iframe>');
    update_option('fudge_registration_widget_menu', 'Register');

    update_option('fudge_speakers_widget_title', $_POST['speakerstitle']);
    update_option('fudge_speakers_widget_tagline', $_POST['speakerstagline']);
    update_option('fudge_speakers_widget_menu', $_POST['speakersmenu']);*/
}