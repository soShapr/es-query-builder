<?php
// Require the Composer autoloader.
require 'vendor/autoload.php';

use esQueryBuilder\queryBuilder;

// Instantiate an Amazon S3 client.
//var_dump(queryBuilder::validateJsonSchema(["criterias" => ["job"=>["loleur","tibere"], "city" =>["Metz", "Paris"], "country" =>["France"], "interest"=> ["Startups", "dev"], "goal" => ["bull", "shit"]]]));
var_dump(queryBuilder::validateJsonSchema('{"_source":["fk_node_id"],"query":{"bool":{"filter":{"bool":{"must":[{"term":{"job":"loleur"}},{"term":{"job":"tibere"}},{"term":{"city":"Metz"}},{"term":{"city":"Paris"}},{"term":{"country":"France"}},{"term":{"interest":"Startups"}},{"term":{"interest":"dev"}},{"term":{"goal":"bull"}},{"term":{"goal":"shit"}}]}},"must":{"match":[]}}}}'));
//var_dump(queryBuilder::getJsonSearchQuery(["job"=>["loleur","tibere"], "city" =>["Metz", "Paris"], "country" =>["France"], "interest"=> ["Startups", "dev"], "goal" => ["bull", "shit"]]));