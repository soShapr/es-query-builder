<?php
// Require the Composer autoloader.
require 'vendor/autoload.php';

ini_set("xdebug.var_display_max_children", -1);
ini_set("xdebug.var_display_max_data", -1);
ini_set("xdebug.var_display_max_depth", -1);
ini_set("log_errors", 1);
ini_set("error_log", "php-error.log");

use esQueryBuilder\queryBuilder;
use Symfony\Component\Yaml\Yaml;


$criterias = json_decode('{
"criterias": {
    "country": [
        "France"
      ],
      "tag": [
        "Startups",
        "dev"
      ],
      "goal": [
        "bull",
        "shit"
      ],
      "job":[
          "doctor",
          "actor"
      ]
}
}', true);


$my_request = queryBuilder::constructBodyQuery("11111", $criterias);
echo json_encode($my_request, true);