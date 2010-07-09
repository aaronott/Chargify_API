#!/usr/bin/env php
<?php
require_once 'Services/VideoBloom.php';

try {
    $api = Services_VideoBloom::factory('Video');

    $videos = $api->getAll();

    foreach ($videos as $vid => $video) {
        $v = $api->getVideo($vid);
        $v['videoid'] = $vid;
        print_R($v);
    }
} catch (PEAR_Exception $error) {
    echo $error->getMessage() . '<br />' . "\n";
}
?>
