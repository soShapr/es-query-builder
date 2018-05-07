Feature: Generate valid search queries

  Scenario: I search for one 'job'
    When I attempt to call the "getJsonSearchQuery" function with JSON arg :
    """
    {
      "criterias": {
        "job": [
          "student"
        ]
      }
    }
    """
    Then i expect the following JSON result :
     """
     {
        "_source": [
            "fk_node_id"
     ],
      "query": {
        "bool": {
          "must": {
            "bool": {
              "minimum_should_match": 1,
              "should": [
                {
                  "match": {
                    "job": {
                      "fuzziness": "AUTO",
                      "minimum_should_match": "2<75%",
                      "prefix_length": 3,
                      "query": "student"
                    }
                  }
                }
              ]
            }
          }
        }
      }
    }
    """

  Scenario: I search for two 'job' and one 'goal'
    When I attempt to call the "getJsonSearchQuery" function with JSON arg :
    """
    {
      "criterias": {
        "job": [
          "student",
          "loleur"
        ],
        "goal": [
           "janpier"
        ]
      }
    }
    """
    Then i expect the following JSON result :
     """
     {
        "_source": [
            "fk_node_id"
        ],
        "query": {
            "bool": {
                "must": {
                    "bool": {
                        "should": [
                            {
                                "match": {
                                    "job": {
                                        "fuzziness": "AUTO",
                                        "minimum_should_match": "2<75%",
                                        "prefix_length": 3,
                                        "query": "student"
                                    }
                                }
                            },
                            {
                                "match": {
                                    "job": {
                                        "fuzziness": "AUTO",
                                        "minimum_should_match": "2<75%",
                                        "prefix_length": 3,
                                        "query": "loleur"
                                    }
                                }
                            }
                        ],
                        "minimum_should_match": 1
                    }
                },
                "filter": {
                    "bool": {
                        "must": [
                            {
                                "bool": {
                                    "should": [
                                        {
                                            "term": {
                                                "goal": "janpier"
                                            }
                                        }
                                    ],
                                    "minimum_should_match": 1
                                }
                            }
                        ]
                    }
                }
            }
        }
    }
    """

