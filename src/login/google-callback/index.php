<?php
if (isset($_GET['state'], $_GET['code'], $_GET['scope'])) {
    $state = filter_var($_GET['state'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $code = filter_var($_GET['code'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $scope = filter_var($_GET['scope'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    if (!empty($state) && !empty($code) && !empty($scope)) {
        echo "State: $state <br>";
        echo "Code: $code <br>";
        echo "Scope: $scope <br>";
    } else {
        header("Location: /login");
        exit;
    }
} else {
    header("Location: /login");
    exit;
}


?>
