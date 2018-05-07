<?php
/**
 * Created by PhpStorm.
 * User: belazâr
 * Date: 07/05/2018
 * Time: 19:20
 */

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use esQueryBuilder\queryBuilder;


class FeatureContext implements Context
{
     private $getQueryResult;

    /**
     * @When /^I attempt to call the "([^"]*)" function with JSON arg :$/
     * @param                                  $arg1
     * @param PyStringNode $string
     */
    public function iAttemptToCallTheFunctionWithJSONArg($arg1, PyStringNode $string)
    {

          $this->getQueryResult = queryBuilder::$arg1(json_decode($string,1));

    }

    /**
     * @Then /^i expect the following JSON result :$/
     * @param PyStringNode $string
     */
    public function iExpectTheFollowingJSONResult(PyStringNode $string)
    {

         PHPUnit_Framework_Assert::assertEquals(
            json_decode($this->getQueryResult),
            json_decode($string)
        );

    }
}