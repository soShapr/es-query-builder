<?php

namespace esQueryBuilder;

use Symfony\Component\Yaml\Yaml;


class queryBuilder implements queryBuilderInterface
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
        if (!is_null($weight)) {
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
	// disable fuzzinness for protected terms (list psecified in config file)
        if ( count(array_intersect(explode(" ", strtolower($value)), $conf["job_protected_terms"])) > 0 )
			$full_text_key_params["fuzziness"] = 0;
		
        // add query and boost if provided
        $full_text_key_params = array_merge($full_text_key_params, array("query" => $value));

        return array("match" => array($key => $full_text_key_params));
    }

    /**
     * @param $requester_id
     * @param $conf
     * @return array
     */
    public static function getInteractionFiltersArray($requester_id, $conf)
    {
        /*
        returns functions of function score that deals with users' interactions
        */
        $fields = $conf["interaction_fields"];

        $interaction_filters_array = array();
        foreach ($fields as $key) {
            $key_weight = $conf["function_score_params"]["weights"][$key];
            array_push($interaction_filters_array, self::createTermQuery($key, $requester_id, $key_weight));
        }

        return $interaction_filters_array;
    }

    /**
     * @param $conf
     * @param $criterias
     *
     * @return array
     */
    public static function getBoostingFiltersArray($conf, $criterias)
    {
        /*
        returns functions of function score that deals with users' interactions
        */
		$boosting_filters_array = array();
		foreach ($criterias as $key => $value) {
			if (in_array($key, $conf["boosting_fields"]) == TRUE) {
				// The overall weight should be shared between all modalities
				$key_weight = $conf["function_score_params"]["weights"][$key] / count($value);
				
				// test if it is a full text field
				// in this case we suppose that a field .raw has been created
				if (array_key_exists($key, $conf["full_text_params"])) {
					$key = $key . ".raw";
				}
				foreach ($value as $sub_value) {
					array_push($boosting_filters_array, self::createTermQuery($key, $sub_value, $key_weight));
				}
			}
		}
        return $boosting_filters_array;
    }


    /**
     * @param $lat
     * @param $lon
     * @param $conf
     *
     * @return array
     */
    public static function getAroundmeFilterArray($lat, $lon, $conf)
    {
        /*
        returns function of function score that deals with geo point. Boost users around me.
        */
        $aroundme_options = $conf["function_score_params"]["options"]["around_me"];
        $aroundme_weight = $conf["function_score_params"]["weights"]["around_me"];

        $location_array = array("origin" => strval($lat) . ', ' . strval($lon), 
                                "scale" => $aroundme_options["scale"],
                                "offset" => $aroundme_options["offset"],
                                "decay" => $aroundme_options["decay"]);
        $aroundme_array = array($aroundme_options["method"] => array("location" => $location_array), "weight" => $aroundme_weight);

        return $aroundme_array;
    }


    /**
     * @param $requester_id
     * @param $lat
     * @param $lon
     * @param $conf
     * @param $criterias
     *
     * @return array
     * @throws \Exception
     */
    public static function getBoostingFunctions($requester_id, $lat, $lon, $conf, $criterias)
    {
        /*
        regroups every functions of function score
        */
        $functions = array();
        array_push($functions, self::getActivityArray($conf));
        array_push($functions, self::getRandomArray($conf));
        array_push($functions, self::getNbMeetpendingArray($conf));
        
        // needs to add "around me filter" if no location is precised
        if (((array_key_exists("city", $criterias)==FALSE) && (array_key_exists("country", $criterias)==FALSE))) {
            // check if both lat and long have a correct formating
            if(is_numeric($lat) && is_numeric($lon)) {
                // cast lon and lat
                $lat = floatval($lat);
                $lon = floatval($lon);                
                if (($lat<=90) && ($lat>=-90) && ($lon<=180) && ($lon>=-180)) {
                    array_push($functions, self::getAroundmeFilterArray($lat, $lon, $conf));
                } else {
                    throw new \Exception('Wrong range value. lat, lon should be between [-90,90] and [-180,180] respectively.');
                }
            }
        }
        $functions = array_merge($functions, self::getInteractionFiltersArray($requester_id, $conf));
	    $functions = array_merge($functions, self::getBoostingFiltersArray($conf, $criterias));

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
        $place_array = array();
        foreach ($criterias as $key => $value) {
            // if field is a filter one
			if (in_array($key, $conf["filter_fields"]) == TRUE) {
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
				// stock city and country information together
				if(in_array($key, array("city", "country")) == TRUE) {
					$place_array = array_merge($place_array, $temp_array);
				} else {
					if (count($temp_array) > 1) {
						array_push($filters_array, self::createBooleanClause("should", $temp_array));
					} else {
						array_push($filters_array, $temp_array[0]);
					}
				}
            }
        }
        // use a should whatever if it's a city or a country
		if (count($place_array) > 1) {
			array_push($filters_array, self::createBooleanClause("should", $place_array));
        }
		// if only one place, just add it
		if (count($place_array) == 1) {
            array_push($filters_array, $place_array[0]);
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
        foreach ($criterias as $key => $value) {
            if (in_array($key, $conf["match_fields"]) == TRUE) {

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
     * @param      $requester_id
     * @param      $criterias
     * @param int  $from
     * @param int  $size
     *
     * @param bool $explain
     *
     * @param null $lat
     * @param null $lon
     *
     * @return array
     * @throws \Exception
     */
    public static function buildSearchQuery($requester_id, array $criterias, $from=0, $size=20, $explain=false, $lat=null, $lon=null){
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
	    $functions = self::getBoostingFunctions($requester_id, $lat, $lon, $conf, $criterias);
        // get agg score modes frm conf
        $agg_modes = self::getAggModes($conf);

		// construct function score
        $function_score = array("function_score" => array_merge(array("functions" => $functions["functions"], "query" => $main_query["query"]), $agg_modes));

        return array("query" => $function_score, "_source" => $conf["source_fields"], "from"=>$from, "size"=>$size, "explain"=>$explain);
    }

    /**
     * @param     $field
     * @param     $text
     *
     * @return array
     * @throws \Exception
     */
    public static function buildSearchQueryAutocompletion($field, $text){
        /*
        suggest propositions for the field including the string "text". 
        */

        // path to configuration file
        $conf_path = str_replace('\\', '/', realpath(__DIR__) . DIRECTORY_SEPARATOR . "conf.yml");
        $conf = Yaml::parse(file_get_contents($conf_path));
        // test if an autocompletion has been set for this field
        if (array_key_exists($field, $conf["autocompletion_params"]) == FALSE) {
            throw new \Exception('No autocompletion set for this field.');
        }
        // get autocompletion data from configuration file
        $index_name = $conf["autocompletion_params"][$field]["index_name"];
        $autocompletion_params = $conf["autocompletion_params"][$field]["params"];

        // construct the autocompletion query
        $completion = array("completion"=>array_merge($autocompletion_params, array("field"=>$index_name)));
        $autocompletion_query = array("suggest"=>array($index_name=>array_merge($completion, array("prefix"=>$text))));

        return array_merge($autocompletion_query, array("_source"=>$conf["autocompletion_params"][$field]["source_fields"]));
    }

    /**
     * @param      $requester_id
     * @param      $criterias
     * @param int  $from
     * @param int  $size
     *
     * @param bool $explain
     *
     * @param null $lat
     * @param null $lon
     *
     * @return string
     * @throws \Exception
     */
    public static function buildSearchQueryJson($requester_id, $criterias, $from=0, $size=20, $explain=false, $lat=null, $lon=null){
        return json_encode(self::buildSearchQuery($requester_id, $criterias, $from, $size, $explain, $lat, $lon), true);
    }

    /**
     * @param     $field
     * @param     $text
     *
     * @return string
     * @throws \Exception
     */
    public static function buildSearchQueryAutocompletionJson($field, $text){
        return json_encode(self::buildSearchQueryAutocompletion($field, $text), true);
    }

}
