<?php
    require_once '../private/database/userData.php';

    $result = userData::existByEmail("usefr1@example.com");

    if($result) {
        print_r($result);
    } else {
        echo "false";
    }