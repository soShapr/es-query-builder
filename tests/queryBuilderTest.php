<?php

namespace esQueryBuilder\Test;

use PHPUnit\Framework\TestCase;
use esQueryBuilder\queryBuilder;
use JsonSchema\Validator;

class queryBuilderTest extends TestCase
{


    public function testValidBaseQuery()
    {
        $data = <<<JSON
{
  "criterias": {
    "tag": [
      "lol",
      "lol2"
    ],
    "job": [
      "coucou"
    ],
    "goal": [
      "lol",
      "lol2"
    ],
    "city": [            
      "Metz",
      "Paris area"
    ],
    "country": [
      "France"
    ]
  }
}
JSON;
        $getBasicSearchQuery = self::validateSchemaFile(queryBuilder::getJsonSearchQuery(json_decode($data, 1)), "baseQuerySchema.json");
       // var_dump($getBasicSearchQuery);

        return $this->assertEquals($getBasicSearchQuery, true);
    }

    public static function validateSchemaFile($jsondata, $filename)
    {
        $schemapath = str_replace('\\', '/', realpath(__DIR__) . DIRECTORY_SEPARATOR . $filename);
        $jsonSchema = <<<JSON
{
    "\$ref":  "file://$schemapath"
}

JSON;

        $validator = new Validator();
        $arrayData = json_decode($jsondata);

        $validator->validate($arrayData, json_decode($jsonSchema, true));

        $result['isSuccess'] = true;

        if ($validator->isValid()) {
            return true;
        } else {
            foreach ($validator->getErrors() as $error) {
                $result['errorList'][] = sprintf("[path:%s][error:%s]", $error['property'], $error['message']);
            }

            return $result;
        }
    }

    public function testInValidJobQuery()
    {
//        ini_set("xdebug.var_display_max_children", -1);
//        ini_set("xdebug.var_display_max_data", -1);
//        ini_set("xdebug.var_display_max_depth", -1);
        $data = <<<JSON
{
  "criterias": {
    "job": [
      "coucou","lol"
    ]
   }
}
JSON;
        $basicSearchQuery = queryBuilder::buildSearchQuery(json_decode($data, 1));
        // Remove mandatory data
        unset($basicSearchQuery['query']['bool']['must']['bool']['should'][0]['match']['job']['fuzziness']);
        //  var_dump($basicSearchQuery);
        $validateSearchQuery = self::validateSchemaFile(json_encode($basicSearchQuery), "baseQuerySchema.json");

        //  var_dump($validateSearchQuery);

        return $this->assertEquals($validateSearchQuery['errorList'][0], "[path:query.bool.must.bool.should[0].match.job.fuzziness][error:The property fuzziness is required]");
    }
}
