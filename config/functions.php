<?php

function dd($data) {
    echo "<pre>";
    var_dump($data);
    echo "</pre>";
    die;
}

function getRequestData() {
    parse_str(file_get_contents("php://input"), $post);
    return $post;
}

function getRequestId() {
    $urlParts = explode('/', $_SERVER['REQUEST_URI']);
    return $urlParts[count($urlParts)-1];
}