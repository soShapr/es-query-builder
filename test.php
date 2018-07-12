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


/* $criterias = json_decode('{
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
}', true); */

$criterias = json_decode('{
    "tag":[
      "Blockchain"
    ],
    "job":[
        "ios developer"
    ],
    "city":[
      "Paris area"
    ]
}', true);

// body search query example
$my_request = queryBuilder::buildSearchQuery("1879462", $criterias, $from=0, $size=30, $explain=false, $lat=null, $lon=null);
var_dump(json_encode($my_request, true));

// autocompletion example using place
/* $my_request = queryBuilder::buildSearchQueryAutocompletion("place", "New");
var_dump(json_encode($my_request, true)); */
