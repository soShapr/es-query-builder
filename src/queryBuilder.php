<?php

namespace esQueryBuilder;

use JsonSchema\Validator;


class queryBuilder
{

    public static function validateJsonSchema($jsonData)
    {
        $schemapath = $source = str_replace('\\', '/', realpath(__DIR__ . '/../sources/schema.json'));
        $jsonSchema = <<<JSON
{
    "\$ref":  "file://{$schemapath}#/definitions/criterias"
}

JSON;

        $validator = new Validator();
        $arrayData = json_decode($jsonData);

        $validator->validate($arrayData, json_decode($jsonSchema,true));

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
                ]
                ]
            ]
        ];
        return $baseQuery;
    }

    public static function buildSearchQuery($criterias)
    {
        $criterias = json_decode($criterias);
        $baseQuery = self::baseQuery();

        foreach ($criterias as $key => $criteria) {
            foreach ($criteria as $value) {
                if ($criterias["job"] == "job") {
//                    if (count($value) >= 2)
//                    {
//                        $baseQuery["query"]["bool"]["must"]["bool"]["minimum_should_match"][1]["should"][]["match"][$key]["fuzziness"]["AUTO"]["minimum_should_match"]["2<75%"]["prefix_lenght"][3]["query"] = $value;
//                    }
//                    else{
//                        $baseQuery["query"]["bool"]["must"] = $value;
//                    }
                } else {
                    $baseQuery["query"]["bool"]["filter"]["bool"]['must'][]['term'][$key] = $value;
                }
            }
        }

        return json_encode($baseQuery);
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
