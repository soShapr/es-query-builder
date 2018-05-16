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
     * @When I attempt to call the function :functionToCall with node id :requesterId and JSON criterias :
     * @param $functionToCall
     * @param $requesterId
     * @param PyStringNode $criterias
     */
    public function iAttemptToCallTheFunctionWithNodeIdAndJsonCriterias($functionToCall, $requesterId, PyStringNode $criterias)
    {
        $this->getQueryResult = queryBuilder::$functionToCall($requesterId, json_decode($criterias, true));

    }

    /**
     * @When I attempt to call the function :functionToCall with field :field and text :text
     * @param $functionToCall
     * @param $field
     * @param $text
     */
    public function iAttemptToCallTheFunctionWithFieldAndText($functionToCall, $field, $text)
    {
        $this->getQueryResult = queryBuilder::$functionToCall($field, $text);

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
                                   "full_text_params|tag|fuzziness",
                                   "full_text_params|tag|prefix_length",
                                   "full_text_params|tag|analyzer",
                                   "full_text_params|tag|boost",
                                   "function_score_params|weights|lastactivity_at",
                                   "function_score_params|weights|random",
                                   "function_score_params|weights|ba_nb_meetpending",
                                   "function_score_params|weights|ab_meetpending_id",
                                   "function_score_params|weights|ba_meetrefuse_id",
                                   "function_score_params|weights|ab_meetrefuse_id",
                                   "function_score_params|weights|ba_meetpending_id",
                                   "function_score_params|options|lastactivity_at|method",
                                   "function_score_params|options|lastactivity_at|scale",
                                   "function_score_params|options|lastactivity_at|offset",
                                   "function_score_params|options|lastactivity_at|decay",
                                   "function_score_params|options|ba_nb_meetpending|factor",
                                   "function_score_params|options|ba_nb_meetpending|modifier",
                                   "autocompletion_params|tag|index_name",
                                   "autocompletion_params|tag|params|analyzer",
                                   "autocompletion_params|tag|params|size",
                                   "autocompletion_params|tag|params|fuzzy|fuzziness",
                                   "autocompletion_params|tag|params|fuzzy|prefix_length");
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

        // replace keys by the conf values via mustache object
        $m = new Mustache_Engine;
        $responseCompleted = $m->render($response->getRaw(), $mustacheInputRender);

        // replace html special cases to match elasticsearch expectations
        $responseCompleted = str_replace(array('&lt;','&gt;'), array('<', '>'), $responseCompleted);
        $responseCompleted = json_decode($responseCompleted, true);

        return $responseCompleted;

    }

}

