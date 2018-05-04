Feature: Search
  I want to search some users

  Scenario: i write a job search
    Given i am in the 'job' input
    When i write a 'job' name
    And i submit the search form
    Then i should see the results

  Scenario: i write an interest search
    Given i am in the 'interest' input
    When i write an 'interest' name
    And i submit the search form
    Then i should see the results

  Scenario: i write a goal search
    Given i am in the 'goal' input
    When i write a 'goal' name
    And i submit the search form
    Then i should see the results

  Scenario: i write a city search
    Given i am in the 'city' input
    When i write a 'city' name
    And i submit the search form
    Then i should see the results

  Scenario: i write a country search
    Given i am in the 'country' input
    When i write an 'country' name
    And i submit the search form
    Then i should see the results

  Scenario: i write two job search
    Given i am in the 'job' input
    When i write two 'job' names
    And i submit the search form
    Then i should see the results

  Scenario: i write two interest search
    Given i am in the 'interest' input
    When i write two 'interest' names
    And i submit the search form
    Then i should see the results

  Scenario: i write two goal search
    Given i am in the 'goal' input
    When i write two 'goal' names
    And i submit the search form
    Then i should see the results

  Scenario: i write two city search
    Given i am in the 'city' input
    When i write two 'city' names
    And i submit the search form
    Then i should see the results

  Scenario: i write two country search
    Given i am in the 'country' input
    When i write two 'country' names
    And i submit the search form
    Then i should see the results