<?php

namespace esQueryBuilder;

use Symfony\Component\Yaml\Yaml;


class queryBuilder
{

    /**
     * @param $str
     * @return Text
     */
    public static function unaccent($str){
        $transliteration = array(
        'Ĳ' => 'I', 'Ö' => 'O','Œ' => 'O','Ü' => 'U','ä' => 'a','æ' => 'a',
        'ĳ' => 'i','ö' => 'o','œ' => 'o','ü' => 'u','ß' => 's','ſ' => 's',
        'À' => 'A','Á' => 'A','Â' => 'A','Ã' => 'A','Ä' => 'A','Å' => 'A',
        'Æ' => 'A','Ā' => 'A','Ą' => 'A','Ă' => 'A','Ç' => 'C','Ć' => 'C',
        'Č' => 'C','Ĉ' => 'C','Ċ' => 'C','Ď' => 'D','Đ' => 'D','È' => 'E',
        'É' => 'E','Ê' => 'E','Ë' => 'E','Ē' => 'E','Ę' => 'E','Ě' => 'E',
        'Ĕ' => 'E','Ė' => 'E','Ĝ' => 'G','Ğ' => 'G','Ġ' => 'G','Ģ' => 'G',
        'Ĥ' => 'H','Ħ' => 'H','Ì' => 'I','Í' => 'I','Î' => 'I','Ï' => 'I',
        'Ī' => 'I','Ĩ' => 'I','Ĭ' => 'I','Į' => 'I','İ' => 'I','Ĵ' => 'J',
        'Ķ' => 'K','Ľ' => 'K','Ĺ' => 'K','Ļ' => 'K','Ŀ' => 'K','Ł' => 'L',
        'Ñ' => 'N','Ń' => 'N','Ň' => 'N','Ņ' => 'N','Ŋ' => 'N','Ò' => 'O',
        'Ó' => 'O','Ô' => 'O','Õ' => 'O','Ø' => 'O','Ō' => 'O','Ő' => 'O',
        'Ŏ' => 'O','Ŕ' => 'R','Ř' => 'R','Ŗ' => 'R','Ś' => 'S','Ş' => 'S',
        'Ŝ' => 'S','Ș' => 'S','Š' => 'S','Ť' => 'T','Ţ' => 'T','Ŧ' => 'T',
        'Ț' => 'T','Ù' => 'U','Ú' => 'U','Û' => 'U','Ū' => 'U','Ů' => 'U',
        'Ű' => 'U','Ŭ' => 'U','Ũ' => 'U','Ų' => 'U','Ŵ' => 'W','Ŷ' => 'Y',
        'Ÿ' => 'Y','Ý' => 'Y','Ź' => 'Z','Ż' => 'Z','Ž' => 'Z','à' => 'a',
        'á' => 'a','â' => 'a','ã' => 'a','ā' => 'a','ą' => 'a','ă' => 'a',
        'å' => 'a','ç' => 'c','ć' => 'c','č' => 'c','ĉ' => 'c','ċ' => 'c',
        'ď' => 'd','đ' => 'd','è' => 'e','é' => 'e','ê' => 'e','ë' => 'e',
        'ē' => 'e','ę' => 'e','ě' => 'e','ĕ' => 'e','ė' => 'e','ƒ' => 'f',
        'ĝ' => 'g','ğ' => 'g','ġ' => 'g','ģ' => 'g','ĥ' => 'h','ħ' => 'h',
        'ì' => 'i','í' => 'i','î' => 'i','ï' => 'i','ī' => 'i','ĩ' => 'i',
        'ĭ' => 'i','į' => 'i','ı' => 'i','ĵ' => 'j','ķ' => 'k','ĸ' => 'k',
        'ł' => 'l','ľ' => 'l','ĺ' => 'l','ļ' => 'l','ŀ' => 'l','ñ' => 'n',
        'ń' => 'n','ň' => 'n','ņ' => 'n','ŉ' => 'n','ŋ' => 'n','ò' => 'o',
        'ó' => 'o','ô' => 'o','õ' => 'o','ø' => 'o','ō' => 'o','ő' => 'o',
        'ŏ' => 'o','ŕ' => 'r','ř' => 'r','ŗ' => 'r','ś' => 's','š' => 's',
        'ť' => 't','ù' => 'u','ú' => 'u','û' => 'u','ū' => 'u','ů' => 'u',
        'ű' => 'u','ŭ' => 'u','ũ' => 'u','ų' => 'u','ŵ' => 'w','ÿ' => 'y',
        'ý' => 'y','ŷ' => 'y','ż' => 'z','ź' => 'z','ž' => 'z','Α' => 'A',
        'Ά' => 'A','Ἀ' => 'A','Ἁ' => 'A','Ἂ' => 'A','Ἃ' => 'A','Ἄ' => 'A',
        'Ἅ' => 'A','Ἆ' => 'A','Ἇ' => 'A','ᾈ' => 'A','ᾉ' => 'A','ᾊ' => 'A',
        'ᾋ' => 'A','ᾌ' => 'A','ᾍ' => 'A','ᾎ' => 'A','ᾏ' => 'A','Ᾰ' => 'A',
        'Ᾱ' => 'A','Ὰ' => 'A','ᾼ' => 'A','Β' => 'B','Γ' => 'G','Δ' => 'D',
        'Ε' => 'E','Έ' => 'E','Ἐ' => 'E','Ἑ' => 'E','Ἒ' => 'E','Ἓ' => 'E',
        'Ἔ' => 'E','Ἕ' => 'E','Ὲ' => 'E','Ζ' => 'Z','Η' => 'I','Ή' => 'I',
        'Ἠ' => 'I','Ἡ' => 'I','Ἢ' => 'I','Ἣ' => 'I','Ἤ' => 'I','Ἥ' => 'I',
        'Ἦ' => 'I','Ἧ' => 'I','ᾘ' => 'I','ᾙ' => 'I','ᾚ' => 'I','ᾛ' => 'I',
        'ᾜ' => 'I','ᾝ' => 'I','ᾞ' => 'I','ᾟ' => 'I','Ὴ' => 'I','ῌ' => 'I',
        'Θ' => 'T','Ι' => 'I','Ί' => 'I','Ϊ' => 'I','Ἰ' => 'I','Ἱ' => 'I',
        'Ἲ' => 'I','Ἳ' => 'I','Ἴ' => 'I','Ἵ' => 'I','Ἶ' => 'I','Ἷ' => 'I',
        'Ῐ' => 'I','Ῑ' => 'I','Ὶ' => 'I','Κ' => 'K','Λ' => 'L','Μ' => 'M',
        'Ν' => 'N','Ξ' => 'K','Ο' => 'O','Ό' => 'O','Ὀ' => 'O','Ὁ' => 'O',
        'Ὂ' => 'O','Ὃ' => 'O','Ὄ' => 'O','Ὅ' => 'O','Ὸ' => 'O','Π' => 'P',
        'Ρ' => 'R','Ῥ' => 'R','Σ' => 'S','Τ' => 'T','Υ' => 'Y','Ύ' => 'Y',
        'Ϋ' => 'Y','Ὑ' => 'Y','Ὓ' => 'Y','Ὕ' => 'Y','Ὗ' => 'Y','Ῠ' => 'Y',
        'Ῡ' => 'Y','Ὺ' => 'Y','Φ' => 'F','Χ' => 'X','Ψ' => 'P','Ω' => 'O',
        'Ώ' => 'O','Ὠ' => 'O','Ὡ' => 'O','Ὢ' => 'O','Ὣ' => 'O','Ὤ' => 'O',
        'Ὥ' => 'O','Ὦ' => 'O','Ὧ' => 'O','ᾨ' => 'O','ᾩ' => 'O','ᾪ' => 'O',
        'ᾫ' => 'O','ᾬ' => 'O','ᾭ' => 'O','ᾮ' => 'O','ᾯ' => 'O','Ὼ' => 'O',
        'ῼ' => 'O','α' => 'a','ά' => 'a','ἀ' => 'a','ἁ' => 'a','ἂ' => 'a',
        'ἃ' => 'a','ἄ' => 'a','ἅ' => 'a','ἆ' => 'a','ἇ' => 'a','ᾀ' => 'a',
        'ᾁ' => 'a','ᾂ' => 'a','ᾃ' => 'a','ᾄ' => 'a','ᾅ' => 'a','ᾆ' => 'a',
        'ᾇ' => 'a','ὰ' => 'a','ᾰ' => 'a','ᾱ' => 'a','ᾲ' => 'a','ᾳ' => 'a',
        'ᾴ' => 'a','ᾶ' => 'a','ᾷ' => 'a','β' => 'b','γ' => 'g','δ' => 'd',
        'ε' => 'e','έ' => 'e','ἐ' => 'e','ἑ' => 'e','ἒ' => 'e','ἓ' => 'e',
        'ἔ' => 'e','ἕ' => 'e','ὲ' => 'e','ζ' => 'z','η' => 'i','ή' => 'i',
        'ἠ' => 'i','ἡ' => 'i','ἢ' => 'i','ἣ' => 'i','ἤ' => 'i','ἥ' => 'i',
        'ἦ' => 'i','ἧ' => 'i','ᾐ' => 'i','ᾑ' => 'i','ᾒ' => 'i','ᾓ' => 'i',
        'ᾔ' => 'i','ᾕ' => 'i','ᾖ' => 'i','ᾗ' => 'i','ὴ' => 'i','ῂ' => 'i',
        'ῃ' => 'i','ῄ' => 'i','ῆ' => 'i','ῇ' => 'i','θ' => 't','ι' => 'i',
        'ί' => 'i','ϊ' => 'i','ΐ' => 'i','ἰ' => 'i','ἱ' => 'i','ἲ' => 'i',
        'ἳ' => 'i','ἴ' => 'i','ἵ' => 'i','ἶ' => 'i','ἷ' => 'i','ὶ' => 'i',
        'ῐ' => 'i','ῑ' => 'i','ῒ' => 'i','ῖ' => 'i','ῗ' => 'i','κ' => 'k',
        'λ' => 'l','μ' => 'm','ν' => 'n','ξ' => 'k','ο' => 'o','ό' => 'o',
        'ὀ' => 'o','ὁ' => 'o','ὂ' => 'o','ὃ' => 'o','ὄ' => 'o','ὅ' => 'o',
        'ὸ' => 'o','π' => 'p','ρ' => 'r','ῤ' => 'r','ῥ' => 'r','σ' => 's',
        'ς' => 's','τ' => 't','υ' => 'y','ύ' => 'y','ϋ' => 'y','ΰ' => 'y',
        'ὐ' => 'y','ὑ' => 'y','ὒ' => 'y','ὓ' => 'y','ὔ' => 'y','ὕ' => 'y',
        'ὖ' => 'y','ὗ' => 'y','ὺ' => 'y','ῠ' => 'y','ῡ' => 'y','ῢ' => 'y',
        'ῦ' => 'y','ῧ' => 'y','φ' => 'f','χ' => 'x','ψ' => 'p','ω' => 'o',
        'ώ' => 'o','ὠ' => 'o','ὡ' => 'o','ὢ' => 'o','ὣ' => 'o','ὤ' => 'o',
        'ὥ' => 'o','ὦ' => 'o','ὧ' => 'o','ᾠ' => 'o','ᾡ' => 'o','ᾢ' => 'o',
        'ᾣ' => 'o','ᾤ' => 'o','ᾥ' => 'o','ᾦ' => 'o','ᾧ' => 'o','ὼ' => 'o',
        'ῲ' => 'o','ῳ' => 'o','ῴ' => 'o','ῶ' => 'o','ῷ' => 'o','А' => 'A',
        'Б' => 'B','В' => 'V','Г' => 'G','Д' => 'D','Е' => 'E','Ё' => 'E',
        'Ж' => 'Z','З' => 'Z','И' => 'I','Й' => 'I','К' => 'K','Л' => 'L',
        'М' => 'M','Н' => 'N','О' => 'O','П' => 'P','Р' => 'R','С' => 'S',
        'Т' => 'T','У' => 'U','Ф' => 'F','Х' => 'K','Ц' => 'T','Ч' => 'C',
        'Ш' => 'S','Щ' => 'S','Ы' => 'Y','Э' => 'E','Ю' => 'Y','Я' => 'Y',
        'а' => 'A','б' => 'B','в' => 'V','г' => 'G','д' => 'D','е' => 'E',
        'ё' => 'E','ж' => 'Z','з' => 'Z','и' => 'I','й' => 'I','к' => 'K',
        'л' => 'L','м' => 'M','н' => 'N','о' => 'O','п' => 'P','р' => 'R',
        'с' => 'S','т' => 'T','у' => 'U','ф' => 'F','х' => 'K','ц' => 'T',
        'ч' => 'C','ш' => 'S','щ' => 'S','ы' => 'Y','э' => 'E','ю' => 'Y',
        'я' => 'Y','ð' => 'd','Ð' => 'D','þ' => 't','Þ' => 'T','ა' => 'a',
        'ბ' => 'b','გ' => 'g','დ' => 'd','ე' => 'e','ვ' => 'v','ზ' => 'z',
        'თ' => 't','ი' => 'i','კ' => 'k','ლ' => 'l','მ' => 'm','ნ' => 'n',
        'ო' => 'o','პ' => 'p','ჟ' => 'z','რ' => 'r','ს' => 's','ტ' => 't',
        'უ' => 'u','ფ' => 'p','ქ' => 'k','ღ' => 'g','ყ' => 'q','შ' => 's',
        'ჩ' => 'c','ც' => 't','ძ' => 'd','წ' => 't','ჭ' => 'c','ხ' => 'k',
        'ჯ' => 'j','ჰ' => 'h' 
        );
        $str = str_replace( array_keys( $transliteration ),
                            array_values( $transliteration ),
                            $str);
        return $str;
    }

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
		// disable fuzzinness for protected terms (list psecified in config file)
		if ( count(array_intersect(explode(" ", strtolower(self::unaccent($value))), $conf["job_protected_terms"])) > 0 )
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
     * @param      $requester_id
     * @param      $criterias
     * @param int  $from
     * @param int  $size
     *
     * @param bool $explain
     *
     * @return array
     * @throws \Exception
     */
    public static function buildSearchQuery($requester_id, $criterias, $from=0, $size=20, $explain=false){
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
     * @return string
     * @throws \Exception
     */
    public static function buildSearchQueryJson($requester_id, $criterias, $from=0, $size=20, $explain=false){
        return json_encode(self::buildSearchQuery($requester_id, $criterias, $from, $size, $explain), true);
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
