<?php

namespace esQueryBuilder;

class queryBuilder
{
    const ADD_TO_MATCH_QUERY = "baseMatchQueryTemplate";
    const ADD_TO_TERM_QUERY = "baseTermQueryTemplate";

    /**
     * @param array $criterias
     *
     * @return string
     */
    public static function getJsonSearchQuery(array $criterias)
    {
        return json_encode(self::buildSearchQuery($criterias), JSON_PRETTY_PRINT);
    }

    /**
     * @param array $criterias
     *
     * @return array
     */
    public static function buildSearchQuery(array $criterias)
    {
        $baseQuery = self::baseQuery();

        $baseCriteriasConfig = self::getBaseCriteriasConfig();

        foreach ($criterias as $criteria) {
            foreach ($criteria as $key => $values) {
                switch ($baseCriteriasConfig[$key]) {
                    case self::ADD_TO_TERM_QUERY:
                        $baseQuery['query']['bool']['filter']['bool']['must'][]['bool'] = self::addShouldToBool($key, $values);

                        break;
                    case self::ADD_TO_MATCH_QUERY:
                        $baseQuery['query']['bool']['must']['bool'] = self::addShouldToBool($key, $values);
                        break;
                }
            }
        }

        return $baseQuery;
    }

    /**
     * @return array
     */
    public static function baseQuery()
    {
        $baseQuery = [
            '_source' => ['fk_node_id']
        ];

        return $baseQuery;
    }

    /**
     * @return array
     */
    public static function getBaseCriteriasConfig()
    {

        return ['tag'     => self::ADD_TO_TERM_QUERY,
                'goal'    => self::ADD_TO_TERM_QUERY,
                'city'    => self::ADD_TO_TERM_QUERY,
                'job'     => self::ADD_TO_MATCH_QUERY,
                'country' => self::ADD_TO_TERM_QUERY
        ];
    }

    /**
     * @param       $key
     * @param array $values
     *
     * @return array
     */
    public static function addShouldToBool($key, array $values)
    {
        $baseCriteriasConfig = self::getBaseCriteriasConfig();
        $ar = [];
        foreach ($values as $v) {
            $ar['should'][] = $baseCriteriasConfig[$key]($key, $v);
        }

        $ar["minimum_should_match"] = 1;

        return $ar;
    }

    /**
     * @param $key
     * @param $value
     *
     * @return array
     */
    public static function baseTermQueryTemplate($key, $value)
    {
        return ["term" => [
            $key => $value
        ]];
    }

    /**
     * @param $key
     * @param $value
     *
     * @return array
     */
    public static function baseMatchQueryTemplate($key, $value)
    {
        return ["match" => [
            $key => [
                "fuzziness"            => "AUTO",
                "minimum_should_match" => "2<75%",
                "prefix_length"        => 3,
                "query"                => $value
            ]
        ]];
    }

}
