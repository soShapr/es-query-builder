<?php
// Require the Composer autoloader.
require 'vendor/autoload.php';

ini_set("xdebug.var_display_max_children", -1);
ini_set("xdebug.var_display_max_data", -1);
ini_set("xdebug.var_display_max_depth", -1);
ini_set("log_errors", 1);
ini_set("error_log", "php-error.log");

use esQueryBuilder\queryBuilder;

$data = <<<JSON
  {
      "criterias": {
        "job": [
          "student",
          "loleur"
        ],
        "goal": [
           "janpier"
        ]
      }
    }
JSON;




//echo json_encode(queryBuilder::baseMatchQueryTemplate("job","loleur"),1);

// Instantiate an Amazon S3 client.
echo queryBuilder::getJsonSearchQuery(json_decode($data,1));