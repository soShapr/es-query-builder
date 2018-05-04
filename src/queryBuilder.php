<?php

namespace esQueryBuilder;

use JsonSchema\Validator;


class queryBuilder
{

    public static function validateJsonSchema($jsonData, $type = 'criterias')
    {


        var_dump("file://schema.json#definitions/{$type}");

        $jsonSchema = <<<JSON
{
    "\$ref":  "file://schema.json#/definitions/criterias"
}

JSON;

//        var_dump($jsonSchema);die();
//        var_dump(json_decode($jsonSchema));die();

var_dump( json_decode($jsonSchema));
        $validator = new Validator();

//        $arrayData = json_decode($jsonData);
        $validator->validate($jsonData, json_decode($jsonSchema));

        $result['isSuccess'] = true;

        if ($validator->isValid()) {
            // "The supplied JSON validates against the schema.";
            return $result;
        } else {
            // "JSON does not validate.";
            $result['isSuccess'] = false;

            foreach ($validator->getErrors() as $error) {
                $result['errorList'][] = sprintf("[path:%s][error:%s]", $error['property'], $error['message']);
            }
        }
        return $result;
    }


    public static function baseQuery()
    {
        $baseQuery = [
            '_source' => ['fk_node_id'],
            'query' => ['bool' =>
                ['filter' => [
                    'bool' => [
                        'must' => [
                        ]
                    ]
                ], "must" => [
                    "match" => [
                        "criteria" => [
                            "fuzziness" =>
                                "AUTO", "minimum_should_match" =>
                                '2<75%', "prefix_lenght" =>
                                [3, "query"]
                        ]
                    ]
                ]]]
        ];
        return $baseQuery;
    }

    public static function buildSearchQuery($criterias)
    {
        $baseQuery = self::baseQuery();

        foreach ($criterias as $key => $criteria) {
            foreach ($criteria as $value) {
                if ($key == "job") {
//                    $baseQuery["query"]["bool"]["must"]["match"]["job"]["fuzziness"][]["query"] = $value;
                } else {
                    $baseQuery["query"]["bool"]["filter"]["bool"]['must'][]['term'][$key] = $value;
                }
            }
        }

        return $baseQuery;
    }


    /**
     * @param $criterias
     * @return string
     */
    public static function getJsonSearchQuery($criterias)
    {
        return json_encode(self::buildSearchQuery($criterias));
    }

}
