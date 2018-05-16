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
      ],
      "city":[
        "paris"
      ]
}
}', true);


// body search query example
$my_request = queryBuilder::buildSearchQuery("11111", $criterias, $from=10, $size=30, $explain=true);
var_dump(json_encode($my_request, true));

// autocompletion example using place
$my_request = queryBuilder::buildSearchQueryAutocompletion("place", "New");
var_dump(json_encode($my_request, true));
