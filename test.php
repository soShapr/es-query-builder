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

$my_query_builder = new queryBuilder("C:/Users/paul/Documents/Paul/es-query-builder/conf.yml", "879462");  // Un second personnage
echo json_encode($my_query_builder->construct_body_query($criterias));
