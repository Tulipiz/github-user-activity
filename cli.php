<?php
require './services/github.php';

$service = new GitHubService();

do {
    echo "\ngithub-activity ";
    $userName = trim(fgets(STDIN));

    if ($userName !== "exit") {
        $service->usersEvents($userName);
    }
} while ($userName != "exit");
