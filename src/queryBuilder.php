<?php

namespace esQueryBuilder;

use Symfony\Component\Yaml\Yaml;


class queryBuilder
{

    /**
     * @param $conf
     * @return mixed
     */
    public static function getAggModes($conf)
    {
        /*
        returns boost_mode and score mode directly from the configuration file
        */
        return $conf["agg_scores_modes"];
    }

    /**
     * @param $conf
     * @return array
     */
    public static function getActivityArray($conf)
    {
        /*
        returns function of function score that deals with lastactivity_at
        */
        $activity_options = $conf["function_score_params"]["options"]["lastactivity_at"];
        $activity_weight = $conf["function_score_params"]["weights"]["lastactivity_at"];

        $activity_array = array("origin" => date("Y-m-d"),
            "scale" => $activity_options["scale"],
            "offset" => $activity_options["offset"],
            "decay" => $activity_options["decay"]);
        $activity_array = array($activity_options["method"] => array("lastactivity_at" => $activity_array), "weight" => $activity_weight);

        return $activity_array;
    }

    /**
     * @param $conf
     * @return array
     */
    public static function getRandomArray($conf)
    {
        /*
        returns function of function score that deals with random score
        */
        $random_weight = $conf["function_score_params"]["weights"]["random"];
        $random_array = array("random_score" => new \stdClass(), "weight" => $random_weight);

        return $random_array;
    }

    /**
     * @param $conf
     * @return array
     */
    public static function getNbMeetpendingArray($conf)
    {
        /*
        returns function of function score that deals with number of meetpending
        */
        $nb_meetpending_options = $conf["function_score_params"]["options"]["ba_nb_meetpending"];
        $nb_meetpending_weight = $conf["function_score_params"]["weights"]["ba_nb_meetpending"];

        $nb_meetpending_array = array("field" => "ba_nb_meetpending",
            "factor" => $nb_meetpending_options["factor"],
            "modifier" => $nb_meetpending_options["modifier"],
            "missing" => $nb_meetpending_options["missing"]);
        $nb_meetpending_array = array("field_value_factor" => $nb_meetpending_array, "weight" => $nb_meetpending_weight);

        return $nb_meetpending_array;
    }

    /**
     * @param $key
     * @param $value
     * @param null $weight
     * @return array
     */
    public static function createTermQuery($key, $value, $weight = null)
    {
        /*
        constructs a term query
        */
        if ($weight) {
            return array("filter" => array("term" => array($key => $value)), "weight" => $weight);
        } else {
            return array("filter" => array("term" => array($key => $value)));
        }
    }

    /**
     * @param $conf
     * @param $key
     * @param $value
     * @return array
     * @throws \Exception
     */
    public static function createMatchQuery($conf, $key, $value)
    {
        /*
        constructs a match query
        */
        // check if the key has a type full text. Otherwise it is an error.
        if (array_key_exists($key, $conf["full_text_params"]) == FALSE) {
            throw new \Exception('Key provided is not of type full text.');
        }
        // get specific params for key provided
        $full_text_key_params = $conf["full_text_params"][$key];
        // add query and boost if provided
        $full_text_key_params = array_merge($full_text_key_params, array("query" => $value));

        return array("match" => array($key => $full_text_key_params));
    }

    /**
     * @param $requester_id
     * @param $conf
     * @return array
     */
    public static function getRankingFiltersArray($requester_id, $conf)
    {
        /*
        returns functions of function score that deals with users' interactions
        */
        $fields = $conf["ranking_fields"];

        $ranking_filters_array = array();
        foreach ($fields as $key) {
            $key_weight = $conf["function_score_params"]["weights"][$key];
            array_push($ranking_filters_array, self::createTermQuery($key, $requester_id, $key_weight));
        }

        return $ranking_filters_array;
    }

    /**
     * @param $requester_id
     * @param $conf
     * @return array
     */
    public static function getBoostingFunctions($requester_id, $conf)
    {
        /*
        regroups every functions of function score
        */
        $functions = array();
        array_push($functions, self::getActivityArray($conf));
        array_push($functions, self::getRandomArray($conf));
        array_push($functions, self::getNbMeetpendingArray($conf));
        $functions = array_merge($functions, self::getRankingFiltersArray($requester_id, $conf));

        return array("functions" => $functions);
    }

    /**
     * @param $bool_clause
     * @param $filter_queries
     * @return array
     * @throws \Exception
     */
    public static function createBooleanClause($bool_clause, $filter_queries)
    {
        /*
        create boolean clause from unitary filters
        */
        if (in_array($bool_clause, array("must", "should")) == FALSE) {
            throw new \Exception('Bool clause provided is not recognized. Should be "should" or "must"');
        }

        $final_filter_query = array("bool" => array($bool_clause => $filter_queries));
        if ($bool_clause == "should") {
            $final_filter_query = array("bool" => array($bool_clause => $filter_queries, "minimum_should_match" => 1));
        }

        return $final_filter_query;
    }

    /**
     * @param $requester_id
     * @return array
     */
    public static function createExcludeMyId($requester_id)
    {
        /*
        specific filters that excludes my id from the result
        */
        return array("must_not" => array("term" => array("node_id" => $requester_id)));
    }

    /**
     * @param $requester_id
     * @param $conf
     * @param $criterias
     * @return array
     * @throws \Exception
     */
    public static function constructFilters($requester_id, $conf, $criterias)
    {
        /*
        regroups all filters. filter out results from the main query that don't satisfy these filters
        */
        $filters_array = array();
        foreach ($criterias["criterias"] as $key => $value) {
            // if field is a filter one
            if (in_array($key, $conf["scored_fields"]) == FALSE) {
                // if the field is full-text type
                $full_text = FALSE;
                if (array_key_exists($key, $conf["full_text_params"])) {
                    $full_text = TRUE;
                }

                $temp_array = array();
                foreach ($value as $sub_value) {
                    if ($full_text) {
                        array_push($temp_array, self::createMatchQuery($conf, $key, $sub_value));
                    } else {
                        array_push($temp_array, self::createTermQuery($key, $sub_value)["filter"]);
                    }
                }
                if (count($temp_array) > 1) {
                    array_push($filters_array, self::createBooleanClause("should", $temp_array));
                } else {
                    array_push($filters_array, $temp_array[0]);
                }
            }
        }
        if (count($filters_array) == 0) {
            $filters_array = array_merge($filters_array, array("bool" => self::createExcludeMyId($requester_id)));
        } else {
            $filters_array = self::createBooleanClause("must", $filters_array);
            $filters_array["bool"] = array_merge($filters_array["bool"], self::createExcludeMyId($requester_id));
        }
        return array("filter" => $filters_array);
    }

    /**
     * @param $conf
     * @param $criterias
     * @return array|mixed
     * @throws \Exception
     */
    public static function constructScoredQuery($conf, $criterias)
    {
        /*
        construct the scored query. the one which uses the full text field job
        */
        $scored_array = array();
        foreach ($criterias["criterias"] as $key => $value) {
            if (in_array($key, $conf["scored_fields"]) == TRUE) {

                $temp_array = array();
                foreach ($value as $sub_value) {
                    array_push($temp_array, self::createMatchQuery($conf, $key, $sub_value));
                }

                if (count($temp_array) > 1) {
                    array_push($scored_array, self::createBooleanClause("should", $temp_array));
                } else {
                    array_push($scored_array, $temp_array[0]);
                }
            }
        }
        if (count($scored_array) == 0) {
            $scored_array = array_merge($scored_array, array("must" => array("match_all" => new \stdClass())));
        } else {
            $scored_array = self::createBooleanClause("must", $scored_array[0])["bool"];
        }

        return $scored_array;

    }

    /**
     * @param $requester_id
     * @param $criterias
	 * @param 0 $from
	 * @param 20 $size
	 * @param $criterias
     * @return array
     * @throws \Exception
     */
    public static function buildSearchQuery($requester_id, $criterias, $from=0, $size=20){
        /*
        regroups the scored query and all the filters to form the main query. 
        then this main query is included in the function score with all the functions applied to construct the final body query
        */

        // path to configuration file
        $conf_path = str_replace('\\', '/', realpath(__DIR__) . DIRECTORY_SEPARATOR . "conf.yml");
        $conf = Yaml::parse(file_get_contents($conf_path));

        // construct scored query
        $filters_array = self::constructFilters($requester_id, $conf, $criterias);
        $scored_array = self::constructScoredQuery($conf, $criterias);
        $main_query = array("query" => array("bool" => array("filter" => $filters_array["filter"], "must" => $scored_array["must"])));
        // functions of the function score
        $functions = self::getBoostingFunctions($requester_id, $conf);
        // get agg score modes frm conf
        $agg_modes = self::getAggModes($conf);

		// construct function score
        $function_score = array("function_score" => array_merge(array("functions" => $functions["functions"], "query" => $main_query["query"]), $agg_modes));

        return array("query" => $function_score, "_source" => $conf["source_fields"], "from"=>$from, "size"=>$size);
    }

    /**
     * @param $requester_id
     * @param $criterias
	 * @param 0 $from
	 * @param 20 $size
     * @return string
     * @throws \Exception
     */
    public static function buildSearchQueryJson($requester_id, $criterias, $from=0, $size=20){
        return json_encode(self::buildSearchQuery($requester_id, $criterias, $from=$from, $size=$size), true);
    }

}
