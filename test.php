<?php
// Require the Composer autoloader.
require 'vendor/autoload.php';

use esQueryBuilder\queryBuilder;

// Instantiate an Amazon S3 client.
var_dump(queryBuilder::test());