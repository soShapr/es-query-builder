<?php

namespace esQueryBuilder;
use Symfony\Component\Yaml\Yaml;

class queryBuilder
{
    private $_conf_path;
    private $_conf;
    private $_requester_id;

    public function __construct($_conf_path, $_requester_id)
    {
        $this->set_conf_path($_conf_path);
        $this->set_request_id($_requester_id);

        $this->_conf = Yaml::parse(file_get_contents($this->_conf_path));
    }

    public function set_conf_path($_conf_path)
    {
        if (!file_exists($_conf_path)){
            trigger_error('The conf path provided doesnt exist', E_USER_WARNING);
            return;
        }
        $this->_conf_path = $_conf_path;
    }

    public function set_request_id($_requester_id)
    {
        if (!is_string($_requester_id)){
            trigger_error('The requester id must be of type string', E_USER_WARNING);
            return;
        }
        $this->_requester_id = $_requester_id;
    }

    public function get_agg_modes(){
        /*
         returns boost_mode and score mode directly from the configuration file
        */
        return $this->_conf["agg_scores_modes"];
    }
    
    public function get_activity_array(){
        /*
         returns function of function score that deals with lastactivity_at
        */
        $activity_options = $this->_conf["function_score_params"]["options"]["lastactivity_at"];
        $activity_weight = $this->_conf["function_score_params"]["weights"]["lastactivity_at"];
    
        $activity_array = array("origin"=>date("Y-m-d"), 
                                "scale"=>$activity_options["scale"],
                                "offset"=>$activity_options["offset"],
                                "decay"=>$activity_options["decay"]);
        $activity_array = array($activity_options["method"]=>array("lastactivity_at"=>$activity_array), "weight"=>$activity_weight);
    
        return $activity_array;
    }
    
    public function get_random_array(){
        /*
         returns function of function score that deals with random score
        */
        $random_weight = $this->_conf["function_score_params"]["weights"]["random"];
        $random_array = array("random_score"=>(object)[], "weight"=>$random_weight);
    
        return $random_array;
    }
    
    public function get_nb_meetpending_array(){
        /*
         returns function of function score that deals with number of meetpending
        */
        $nb_meetpending_options = $this->_conf["function_score_params"]["options"]["ba_nb_meetpending"];
        $nb_meetpending_weight = $this->_conf["function_score_params"]["weights"]["ba_nb_meetpending"];
    
        $nb_meetpending_array = array("field"=>"ba_nb_meetpending", 
                                      "factor"=>$nb_meetpending_options["factor"], 
                                      "modifier"=>$nb_meetpending_options["modifier"], 
                                      "missing"=>$nb_meetpending_options["missing"]);
        $nb_meetpending_array = array("field_value_factor"=>$nb_meetpending_array, "weight"=>$nb_meetpending_weight);                           
    
        return $nb_meetpending_array;
    }
    
    public static function create_term_query($key, $value, $weight){
        /*
         constructs a term query
        */
        if (isset($weight)) {
            return array("filter"=>array("term"=>array($key=>$value)), "weight"=>$weight);
         } else {
            return array("filter"=>array("term"=>array($key=>$value)));
         }
    }
    
    public function create_match_query($key, $value){
        /*
         constructs a match query
        */
        // check if the key has a type full text. Otherwise it is an error.
        if(array_key_exists($key, $this->_conf["full_text_params"])==FALSE){
            throw new Exception('Key provided is not of type full text.');
        }
        // get specific params for key provided
        $full_text_key_params = $this->_conf["full_text_params"][$key];
        // add query and boost if provided
        $full_text_key_params = array_merge($full_text_key_params, array("query"=>$value));
    
        return array("match"=>array($key=>$full_text_key_params));
    }
    
    public function get_ranking_filters_array(){
        /*
         returns functions of function score that deals with users' interactions
        */
        $fields = $this->_conf["ranking_fields"];
    
        $ranking_filters_array = array();
        foreach( $fields as $key){
            $key_weight = $this->_conf["function_score_params"]["weights"][$key];
            array_push($ranking_filters_array, $this->create_term_query($key, $this->_requester_id, $key_weight));
        }
    
        return $ranking_filters_array;
    }
    
    public function get_boosting_functions(){
        /*
         regroups every functions of function score
        */
        $functions = array();
        array_push($functions, $this->get_activity_array());
        array_push($functions, $this->get_random_array());
        array_push($functions, $this->get_nb_meetpending_array());
        $functions = array_merge($functions, $this->get_ranking_filters_array());
        
        return array("functions"=>$functions);
    }
    
    public static function create_boolean_clause($bool_clause, $filter_queries){
        /*
         create boolean clause from unitary filters
        */
        if(in_array($bool_clause, array("must", "should"))==FALSE){
            throw new Exception('Bool clause provided is not recognized. Should be "should" or "must"');
        }
    
        $final_filter_query = array("bool"=>array($bool_clause=>$filter_queries));
        if($bool_clause == "should"){
            $final_filter_query = array("bool"=>array($bool_clause=>$filter_queries, "minimum_should_match"=>1));
        }else{
            $final_filter_query = array("bool"=>array($bool_clause=>$filter_queries));
        }
    
        return  $final_filter_query;
    }
    
    public function create_exclude_my_id(){
        /*
         specific filters that excludes my id from the result
        */
        return array("must_not"=>array("term"=>array("node_id"=>$this->_requester_id)));
    }
    
    public function construct_filters($criterias){
        /*
         regroups all filters. filter out results from the main query that don't satisfy these filters
        */
        $filters_array = array();
        foreach($criterias["criterias"] as $key => $value){
            // if field is a filter one
            if(in_array($key, $this->_conf["scored_fields"])==FALSE){
                // if the field is full-text type
                $full_text = FALSE;
                if(array_key_exists($key, $this->_conf["full_text_params"])){
                    $full_text = TRUE;
                }
    
                $temp_array = array();
                foreach($value as $sub_value){
                    if($full_text){
                        array_push($temp_array, $this->create_match_query($key, $sub_value));
                    }else{
                        array_push($temp_array, $this->create_term_query($key, $sub_value)["filter"]);
                    }
                }
                if(count($temp_array)>1){
                    array_push($filters_array, $this->create_boolean_clause("should", $temp_array));
                }else{
                    array_push($filters_array, $temp_array[0]);
                }
            }
        }
        if(count($filters_array)==0){
            $filters_array = array_merge($filters_array, array("bool"=>$this->create_exclude_my_id($this->_requester_id)));
        }else{
            $filters_array = $this->create_boolean_clause("must", $filters_array);
            $filters_array["bool"] = array_merge($filters_array["bool"], $this->create_exclude_my_id( $this->_requester_id));
        }
        return array("filter"=>$filters_array);
    }
    
    public function construct_scored_queries($criterias){
        /*
         construct the scored query. the one which uses the full text field job
        */
        $scored_array = array();
        foreach($criterias["criterias"] as $key => $value){
            if(in_array($key, $this->_conf["scored_fields"])==TRUE){
    
                $temp_array = array();
                foreach($value as $sub_value){
                    array_push($temp_array, $this->create_match_query($key, $sub_value));
                }
                
                if(count($temp_array)>1){
                    array_push($scored_array, $this->create_boolean_clause("should", $temp_array));
                }else{
                    array_push($scored_array, $temp_array[0]);
                }
            }
        }
        if(count($scored_array)==0){
            $scored_array = array_merge($scored_array, array("must"=>array("match_all"=>(object)[])));
        }else{
            $scored_array = $this->create_boolean_clause("must", $scored_array[0])["bool"];
        }
    
        return $scored_array;
    
    }
    
    public function construct_body_query($criterias){
        /*
         regroups the scored query and all the filters to form the main query. 
         then this main query is included in the function score with all the functions applied to construct the final body query
        */
        // construct scored query
        $filters_array = $this->construct_filters($criterias);
        $scored_array = $this->construct_scored_queries($criterias);
        $main_query = array("query"=>array("bool"=>array("filter"=>$filters_array["filter"],"must"=>$scored_array["must"])));
        // functions of the function score
        $functions = $this->get_boosting_functions();
        // get agg score modes frm conf
        $agg_modes = $this->get_agg_modes();
    
        $function_score = array("function_score"=>array_merge(array("functions"=>$functions["functions"], "query"=>$main_query["query"]), $agg_modes));
        
        return array("query"=>$function_score);
    }

}
