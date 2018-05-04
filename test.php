<?php
// Require the Composer autoloader.
require 'vendor/autoload.php';

use esQueryBuilder\queryBuilder;

$data = file_get_contents('./sources/data.json');

// Instantiate an Amazon S3 client.
var_dump(queryBuilder::buildSearchQuery($data));