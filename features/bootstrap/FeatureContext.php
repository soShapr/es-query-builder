<?php
/**
 * Created by PhpStorm.
 * User: belazâr, paul
 * Date: 07/05/2018
 * Time: 19:20
 */

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use esQueryBuilder\queryBuilder;
use Symfony\Component\Yaml\Yaml;


class FeatureContext implements Context
{
    private $getQueryResult;

    /**
     * @When I attempt to call the function :functionToCall with node id :requesterId and lat :lat and lon :lon and JSON criterias :
     * @param $functionToCall
     * @param $requesterId
	 * @param $lat
	 * @param $lon
	 * @param PyStringNode $criterias
     */
    public function iAttemptToCallTheFunctionWithNodeIdAndJsonCriterias($functionToCall, $requesterId, $lat, $lon, PyStringNode $criterias)
    {
        $this->getQueryResult = json_decode(queryBuilder::$functionToCall($requesterId, json_decode($criterias, true), 0, 20, false, $lat, $lon), true);

    }

    /**
     * @When I attempt to call the function :functionToCall with field :field and text :text
     * @param $functionToCall
     * @param $field
     * @param $text
     */
    public function iAttemptToCallTheFunctionWithFieldAndText($functionToCall, $field, $text)
    {
        $this->getQueryResult = json_decode(queryBuilder::$functionToCall($field, $text), true);

    }

    /**
     * @Then /^I expect the following JSON result :$/
     * @param PyStringNode $response
     */
    public function iExpectTheFollowingJSONResult(PyStringNode $response)
    {
        $responseCompleted = self::fillTemplate($response);

        // casting problem with php. to transform std class to array in nested object, one needs to use:
        // json_decode(json_encode(<NESTED ARRAY>, JSON_FORCE_OBJECT), true)
        PHPUnit_Framework_Assert::assertEquals(
            json_decode(json_encode($this->getQueryResult, JSON_FORCE_OBJECT), true),
            $responseCompleted);

    }

    /**
     * @param $response
     * @return mixed|string
     */
    public static function fillTemplate(PyStringNode $response){
        // load configuration file in source folder
        $conf_path = str_replace('\\', '/', realpath(__DIR__. '/../../src') . DIRECTORY_SEPARATOR . "conf.yml");
        $conf = Yaml::parse(file_get_contents($conf_path));
        // list parameters
        $mustacheFiedNames = array("agg_scores_modes|score_mode",
                                   "agg_scores_modes|boost_mode",
                                   "full_text_params|job|fuzziness",
                                   "full_text_params|job|minimum_should_match",
                                   "full_text_params|job|prefix_length",
                                   "full_text_params|job|zero_terms_query",
                                   "full_text_params|job|analyzer",
                                   "full_text_params|job|boost",
                                   "full_text_params|job.raw|fuzziness",
                                   "full_text_params|job.raw|minimum_should_match",
                                   "full_text_params|job.raw|prefix_length",
                                   "full_text_params|job.raw|zero_terms_query",
                                   "full_text_params|job.raw|analyzer",
                                   "full_text_params|job.raw|boost",
                                   "full_text_params|tag|fuzziness",
                                   "full_text_params|tag|prefix_length",
                                   "full_text_params|tag|analyzer",
                                   "full_text_params|tag|boost",
                                   "function_score_params|weights|lastactivity_at",
                                   "function_score_params|weights|random",
                                   "function_score_params|weights|has_picture",
                                   "function_score_params|weights|ba_nb_meetpending",
                                   "function_score_params|weights|ab_meetrefuse_id_search",
                                   "function_score_params|weights|ab_meetrefuse_id_explorer",
                                   "function_score_params|weights|ba_meetrefuse_id_search",
                                   "function_score_params|weights|ba_meetrefuse_id_explorer",
                                   "function_score_params|weights|ab_meetpending_id_search",
                                   "function_score_params|weights|ab_meetpending_id_search_super",
                                   "function_score_params|weights|ab_meetpending_id_explorer",
                                   "function_score_params|weights|ab_meetpending_id_explorer_super",
                                   "function_score_params|weights|ba_meetpending_id_search",
                                   "function_score_params|weights|ba_meetpending_id_explorer",
                                   "function_score_params|weights|tag",
                                   "function_score_params|options|lastactivity_at|method",
                                   "function_score_params|options|lastactivity_at|scale",
                                   "function_score_params|options|lastactivity_at|offset",
                                   "function_score_params|options|lastactivity_at|decay",
                                   "function_score_params|options|around_me|method",
                                   "function_score_params|options|around_me|scale",
                                   "function_score_params|options|around_me|offset",
                                   "function_score_params|options|around_me|decay",
                                   "function_score_params|options|ba_nb_meetpending|factor",
                                   "function_score_params|options|ba_nb_meetpending|modifier",
                                   "autocompletion_params|tag|index_name",
                                   "autocompletion_params|tag|params|analyzer",
                                   "autocompletion_params|tag|params|size",
                                   "autocompletion_params|tag|params|fuzzy|fuzziness",
                                   "autocompletion_params|tag|params|fuzzy|prefix_length",
                                   "autocompletion_params|job|index_name",
                                   "autocompletion_params|job|params|analyzer",
                                   "autocompletion_params|job|params|size",
                                   "autocompletion_params|job|params|fuzzy|fuzziness",
                                   "autocompletion_params|job|params|fuzzy|prefix_length",
                                   "autocompletion_params|place|index_name",
                                   "autocompletion_params|place|params|analyzer",
                                   "autocompletion_params|place|params|size",
                                   "autocompletion_params|place|params|fuzzy|fuzziness",
                                   "autocompletion_params|place|params|fuzzy|prefix_length");
        // construct array that will be used to fill in the template
        $mustacheInputRender = array();
        foreach($mustacheFiedNames as $key){
            $conf_path_keys =  explode("|", $key);
            $confValue = $conf;
            foreach($conf_path_keys as $conf_path_key){
                $confValue = $confValue[$conf_path_key];
            }
            $mustacheInputRender = array_merge($mustacheInputRender, array($key=>$confValue));
        }
        $mustacheInputRender = array_merge($mustacheInputRender, array("function_score_params|options|lastactivity_at|origin"=>date("Y-m-d")));

        // field containing a dot is forbidden in mustache that's why we replace all . by _ 
        $mustacheInputRenderModified = array();
        foreach($mustacheInputRender as $key=>$value){
            $mustacheInputRenderModified[str_replace(".","_",$key)]=$value;
        }
        // for now only job and tag might have .raw
        $responseModified = str_replace("job.raw", "job_raw",$response->getRaw());
        $responseModified = str_replace("tag.raw", "tag_raw", $responseModified);

        // replace keys by the conf values via mustache object
        $m = new Mustache_Engine;
        $responseCompleted = $m->render($responseModified, $mustacheInputRenderModified);

        // replace back real field name
        $responseCompleted = str_replace("job_raw", "job.raw", $responseCompleted);
        $responseCompleted = str_replace("tag_raw", "tag.raw", $responseCompleted);

        // replace html special cases to match elasticsearch expectations
        $responseCompleted = str_replace(array('&lt;','&gt;'), array('<', '>'), $responseCompleted);
        $responseCompleted = json_decode($responseCompleted, true);

        return $responseCompleted;

    }

}

