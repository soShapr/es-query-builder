Feature: Generate valid search queries with fake request id "11111"

  Scenario: I search for one 'job' (simple case full text match)
    When I attempt to call the function "constructBodyQuery" with node id "11111" and JSON criterias :
    """
    {
      "criterias": {
        "job": [
          "student"
        ]
      }
    }
    """
    Then I expect the following JSON result :
     """
    {
    "query": {
        "function_score": {
        "functions": [
            {
            "{{function_score_params|options|lastactivity_at|method}}": {
                "lastactivity_at": {
                "origin": "{{function_score_params|options|lastactivity_at|origin}}",
                "scale": "{{function_score_params|options|lastactivity_at|scale}}",
                "offset": "{{function_score_params|options|lastactivity_at|offset}}",
                "decay": {{function_score_params|options|lastactivity_at|decay}}
                }
            },
            "weight": {{function_score_params|weights|lastactivity_at}}
            },
            {
            "random_score": {},
            "weight": {{function_score_params|weights|random}}
            },
            {
            "field_value_factor": {
                "field": "ba_nb_meetpending",
                "factor": {{function_score_params|options|ba_nb_meetpending|factor}},
                "modifier": "{{function_score_params|options|ba_nb_meetpending|modifier}}",
                "missing": 1
            },
            "weight": {{function_score_params|weights|ba_nb_meetpending}}
            },
            {
            "filter": {
                "term": {
                "ab_meetpending_id": "11111"
                }
            },
            "weight": {{function_score_params|weights|ab_meetpending_id}}
            },
            {
            "filter": {
                "term": {
                "ba_meetrefuse_id": "11111"
                }
            },
            "weight": {{function_score_params|weights|ba_meetrefuse_id}}
            },
            {
            "filter": {
                "term": {
                "ab_meetrefuse_id": "11111"
                }
            },
            "weight": {{function_score_params|weights|ab_meetrefuse_id}}
            },
            {
            "filter": {
                "term": {
                "ba_meetpending_id": "11111"
                }
            },
            "weight": {{function_score_params|weights|ba_meetpending_id}}
            }
        ],
        "query": {
            "bool": {
            "filter": {
                "bool": {
                "must_not": {
                    "term": {
                    "node_id": "11111"
                    }
                }
                }
            },
            "must": {
                "match": {
                "job": {
                    "fuzziness": "{{full_text_params|job|fuzziness}}",
                    "minimum_should_match": "{{full_text_params|job|minimum_should_match}}",
                    "prefix_length": {{full_text_params|job|prefix_length}},
                    "zero_terms_query": "{{full_text_params|job|zero_terms_query}}",
                    "analyzer": "{{full_text_params|job|analyzer}}",
                    "boost": {{full_text_params|job|boost}},
                    "query": "student"
                }
                }
            }
            }
        },
        "score_mode": "{{agg_scores_modes|score_mode}}",
        "boost_mode": "{{agg_scores_modes|boost_mode}}"
        }
    },
    "_source": [
        "fk_node_id"
    ]
    }
    """

  Scenario: I search for one 'job' and one 'country'
    When I attempt to call the function "constructBodyQuery" with node id "11111" and JSON criterias :
    """
    {
      "criterias": {
        "job": [
          "doctor"
        ],
        "country": [
            "France"
        ]
      }
    }
    """
    Then I expect the following JSON result :
     """
    {
    "query": {
        "function_score": {
        "functions": [
            {
            "{{function_score_params|options|lastactivity_at|method}}": {
                "lastactivity_at": {
                "origin": "{{function_score_params|options|lastactivity_at|origin}}",
                "scale": "{{function_score_params|options|lastactivity_at|scale}}",
                "offset": "{{function_score_params|options|lastactivity_at|offset}}",
                "decay": {{function_score_params|options|lastactivity_at|decay}}
                }
            },
            "weight": {{function_score_params|weights|lastactivity_at}}
            },
            {
            "random_score": {},
            "weight": {{function_score_params|weights|random}}
            },
            {
            "field_value_factor": {
                "field": "ba_nb_meetpending",
                "factor": {{function_score_params|options|ba_nb_meetpending|factor}},
                "modifier": "{{function_score_params|options|ba_nb_meetpending|modifier}}",
                "missing": 1
            },
            "weight": {{function_score_params|weights|ba_nb_meetpending}}
            },
            {
            "filter": {
                "term": {
                "ab_meetpending_id": "11111"
                }
            },
            "weight": {{function_score_params|weights|ab_meetpending_id}}
            },
            {
            "filter": {
                "term": {
                "ba_meetrefuse_id": "11111"
                }
            },
            "weight": {{function_score_params|weights|ba_meetrefuse_id}}
            },
            {
            "filter": {
                "term": {
                "ab_meetrefuse_id": "11111"
                }
            },
            "weight": {{function_score_params|weights|ab_meetrefuse_id}}
            },
            {
            "filter": {
                "term": {
                "ba_meetpending_id": "11111"
                }
            },
            "weight": {{function_score_params|weights|ba_meetpending_id}}
            }
        ],
        "query": {
            "bool": {
            "filter": {
                "bool": {
                "must": [
                    {
                        "term": {
                            "country": "France"
                        }
                    }
                ],
                "must_not": {
                    "term": {
                        "node_id": "11111"
                    }
                }
                }
            },
            "must": {
                "match": {
                "job": {
                    "fuzziness": "{{full_text_params|job|fuzziness}}",
                    "minimum_should_match": "{{full_text_params|job|minimum_should_match}}",
                    "prefix_length": {{full_text_params|job|prefix_length}},
                    "zero_terms_query": "{{full_text_params|job|zero_terms_query}}",
                    "analyzer": "{{full_text_params|job|analyzer}}",
                    "boost": {{full_text_params|job|boost}},
                    "query": "doctor"
                }
                }
            }
            }
        },
        "score_mode": "{{agg_scores_modes|score_mode}}",
        "boost_mode": "{{agg_scores_modes|boost_mode}}"
        }
    },
    "_source": [
        "fk_node_id"
    ]
    }
    """

  Scenario: I search for a country only (case of a match all and one clause in filter)
    When I attempt to call the function "constructBodyQuery" with node id "11111" and JSON criterias :
    """
    {
      "criterias": {
        "country": [
            "France"
        ]
      }
    }
    """
    Then I expect the following JSON result :
     """
    {
    "query": {
        "function_score": {
        "functions": [
            {
            "{{function_score_params|options|lastactivity_at|method}}": {
                "lastactivity_at": {
                "origin": "{{function_score_params|options|lastactivity_at|origin}}",
                "scale": "{{function_score_params|options|lastactivity_at|scale}}",
                "offset": "{{function_score_params|options|lastactivity_at|offset}}",
                "decay": {{function_score_params|options|lastactivity_at|decay}}
                }
            },
            "weight": {{function_score_params|weights|lastactivity_at}}
            },
            {
            "random_score": {},
            "weight": {{function_score_params|weights|random}}
            },
            {
            "field_value_factor": {
                "field": "ba_nb_meetpending",
                "factor": {{function_score_params|options|ba_nb_meetpending|factor}},
                "modifier": "{{function_score_params|options|ba_nb_meetpending|modifier}}",
                "missing": 1
            },
            "weight": {{function_score_params|weights|ba_nb_meetpending}}
            },
            {
            "filter": {
                "term": {
                "ab_meetpending_id": "11111"
                }
            },
            "weight": {{function_score_params|weights|ab_meetpending_id}}
            },
            {
            "filter": {
                "term": {
                "ba_meetrefuse_id": "11111"
                }
            },
            "weight": {{function_score_params|weights|ba_meetrefuse_id}}
            },
            {
            "filter": {
                "term": {
                "ab_meetrefuse_id": "11111"
                }
            },
            "weight": {{function_score_params|weights|ab_meetrefuse_id}}
            },
            {
            "filter": {
                "term": {
                "ba_meetpending_id": "11111"
                }
            },
            "weight": {{function_score_params|weights|ba_meetpending_id}}
            }
        ],
        "query": {
            "bool": {
            "filter": {
                "bool": {
                "must": [
                    {
                        "term": {
                            "country": "France"
                        }
                    }
                ],
                "must_not": {
                    "term": {
                        "node_id": "11111"
                    }
                }
                }
            },
            "must": {
                "match_all":{}
            }
            }
        },
        "score_mode": "{{agg_scores_modes|score_mode}}",
        "boost_mode": "{{agg_scores_modes|boost_mode}}"
        }
    },
    "_source": [
        "fk_node_id"
    ]
    }
    """

  Scenario: I search for a tag only (case of match in filter)
    When I attempt to call the function "constructBodyQuery" with node id "11111" and JSON criterias :
    """
    {
      "criterias": {
        "tag": [
            "Startups"
        ]
      }
    }
    """
    Then I expect the following JSON result :
     """
    {
    "query": {
        "function_score": {
        "functions": [
            {
            "{{function_score_params|options|lastactivity_at|method}}": {
                "lastactivity_at": {
                "origin": "{{function_score_params|options|lastactivity_at|origin}}",
                "scale": "{{function_score_params|options|lastactivity_at|scale}}",
                "offset": "{{function_score_params|options|lastactivity_at|offset}}",
                "decay": {{function_score_params|options|lastactivity_at|decay}}
                }
            },
            "weight": {{function_score_params|weights|lastactivity_at}}
            },
            {
            "random_score": {},
            "weight": {{function_score_params|weights|random}}
            },
            {
            "field_value_factor": {
                "field": "ba_nb_meetpending",
                "factor": {{function_score_params|options|ba_nb_meetpending|factor}},
                "modifier": "{{function_score_params|options|ba_nb_meetpending|modifier}}",
                "missing": 1
            },
            "weight": {{function_score_params|weights|ba_nb_meetpending}}
            },
            {
            "filter": {
                "term": {
                "ab_meetpending_id": "11111"
                }
            },
            "weight": {{function_score_params|weights|ab_meetpending_id}}
            },
            {
            "filter": {
                "term": {
                "ba_meetrefuse_id": "11111"
                }
            },
            "weight": {{function_score_params|weights|ba_meetrefuse_id}}
            },
            {
            "filter": {
                "term": {
                "ab_meetrefuse_id": "11111"
                }
            },
            "weight": {{function_score_params|weights|ab_meetrefuse_id}}
            },
            {
            "filter": {
                "term": {
                "ba_meetpending_id": "11111"
                }
            },
            "weight": {{function_score_params|weights|ba_meetpending_id}}
            }
        ],
        "query": {
            "bool": {
            "filter": {
                "bool": {
                "must": [
                    {"match":
                        {"tag":{
                            "fuzziness": "{{full_text_params|tag|fuzziness}}",
                            "prefix_length": {{full_text_params|tag|prefix_length}},
                            "analyzer":  "{{full_text_params|tag|analyzer}}",
                            "boost": {{full_text_params|tag|boost}},
                            "query": "Startups"
                            }
                        }
                    }
                ],
                "must_not": {
                    "term": {
                        "node_id": "11111"
                    }
                }
                }
            },
            "must": {
                "match_all":{}
            }
            }
        },
        "score_mode": "{{agg_scores_modes|score_mode}}",
        "boost_mode": "{{agg_scores_modes|boost_mode}}"
        }
    },
    "_source": [
        "fk_node_id"
    ]
    }
    """

  Scenario: I search for multiple criterias including full text in filter and scored queries
    When I attempt to call the function "constructBodyQuery" with node id "11111" and JSON criterias :
    """
    {
      "criterias": {
        "job": [
          "doctor",
          "actor"
        ],
        "country": [
            "France"
        ],
        "city":[
            "Saint-malo",
            "Combloux",
            "Paris area"
        ],
        "tag":[
            "Startups",
            "Blockchain"
        ],
        "goal":[
            "Ideas & inspiration"
        ]
      }
    }
    """
    Then I expect the following JSON result :
     """
    {
    "query": {
        "function_score": {
        "functions": [
            {
            "{{function_score_params|options|lastactivity_at|method}}": {
                "lastactivity_at": {
                "origin": "{{function_score_params|options|lastactivity_at|origin}}",
                "scale": "{{function_score_params|options|lastactivity_at|scale}}",
                "offset": "{{function_score_params|options|lastactivity_at|offset}}",
                "decay": {{function_score_params|options|lastactivity_at|decay}}
                }
            },
            "weight": {{function_score_params|weights|lastactivity_at}}
            },
            {
            "random_score": {},
            "weight": {{function_score_params|weights|random}}
            },
            {
            "field_value_factor": {
                "field": "ba_nb_meetpending",
                "factor": {{function_score_params|options|ba_nb_meetpending|factor}},
                "modifier": "{{function_score_params|options|ba_nb_meetpending|modifier}}",
                "missing": 1
            },
            "weight": {{function_score_params|weights|ba_nb_meetpending}}
            },
            {
            "filter": {
                "term": {
                "ab_meetpending_id": "11111"
                }
            },
            "weight": {{function_score_params|weights|ab_meetpending_id}}
            },
            {
            "filter": {
                "term": {
                "ba_meetrefuse_id": "11111"
                }
            },
            "weight": {{function_score_params|weights|ba_meetrefuse_id}}
            },
            {
            "filter": {
                "term": {
                "ab_meetrefuse_id": "11111"
                }
            },
            "weight": {{function_score_params|weights|ab_meetrefuse_id}}
            },
            {
            "filter": {
                "term": {
                "ba_meetpending_id": "11111"
                }
            },
            "weight": {{function_score_params|weights|ba_meetpending_id}}
            }
        ],
        "query": {
            "bool": {
            "filter": {
                "bool": {
                "must": [
                {
                  "term": {
                    "country": "France"
                  }
                },
                {
                  "bool": {
                    "should": [
                      {
                        "term": {
                          "city": "Saint-malo"
                        }
                      },
                      {
                        "term": {
                          "city": "Combloux"
                        }
                      },
                      {
                        "term": {
                          "city": "Paris area"
                        }
                      }
                    ],
                    "minimum_should_match": 1
                  }
                },
                {
                  "bool": {
                    "should": [
                      {
                        "match": {
                          "tag": {
                            "fuzziness": "AUTO",
                            "prefix_length": 3,
                            "analyzer": "shapr_analyzer_tag_en",
                            "boost": 1,
                            "query": "Startups"
                          }
                        }
                      },
                      {
                        "match": {
                          "tag": {
                            "fuzziness": "AUTO",
                            "prefix_length": 3,
                            "analyzer": "shapr_analyzer_tag_en",
                            "boost": 1,
                            "query": "Blockchain"
                          }
                        }
                      }
                    ],
                    "minimum_should_match": 1
                  }
                },
                {
                  "term": {
                    "goal": "Ideas & inspiration"
                  }
                }
                ],
                "must_not": {
                    "term": {
                        "node_id": "11111"
                    }
                }
                }
            },
            "must": {
            "bool": {
              "should": [
                {
                  "match": {
                    "job": {
                      "fuzziness": "{{full_text_params|job|fuzziness}}",
                      "minimum_should_match": "{{full_text_params|job|minimum_should_match}}",
                      "prefix_length": {{full_text_params|job|prefix_length}},
                      "zero_terms_query": "{{full_text_params|job|zero_terms_query}}",
                      "analyzer": "{{full_text_params|job|analyzer}}",
                      "boost": {{full_text_params|job|boost}},
                      "query": "doctor"
                    }
                  }
                },
                {
                  "match": {
                    "job": {
                      "fuzziness": "{{full_text_params|job|fuzziness}}",
                      "minimum_should_match": "{{full_text_params|job|minimum_should_match}}",
                      "prefix_length": {{full_text_params|job|prefix_length}},
                      "zero_terms_query": "{{full_text_params|job|zero_terms_query}}",
                      "analyzer": "{{full_text_params|job|analyzer}}",
                      "boost": {{full_text_params|job|boost}},
                      "query": "actor"
                    }
                  }
                }
              ],
              "minimum_should_match": 1
            }
            }
            }
        },
        "score_mode": "{{agg_scores_modes|score_mode}}",
        "boost_mode": "{{agg_scores_modes|boost_mode}}"
        }
    },
    "_source": [
        "fk_node_id"
    ]
    }
    """
