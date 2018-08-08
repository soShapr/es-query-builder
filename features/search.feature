Feature: Generate valid search queries with fake request id "11111"

    Scenario: I search for one 'job' (simple case full text match)
    When I attempt to call the function "buildSearchQueryJson" with node id "11111" and lat "none" and lon "none" and JSON criterias :
    """
    {
        "job": [
          "student"
        ]
      }
    """
    Then I expect the following JSON result :
    """
    {"explain":false,
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
                    "has_picture": {{function_score_params|weights|has_picture}}
                }
            },
            "weight": 1
            },
            {
            "filter": {
                "term": {
                "ab_meetpending_id_explorer": "11111"
                }
            },
            "weight": {{function_score_params|weights|ab_meetpending_id_explorer}}
            },
            {
            "filter": {
                "term": {
                "ab_meetpending_id_search": "11111"
                }
            },
            "weight": {{function_score_params|weights|ab_meetpending_id_search}}
            },
            {
            "filter": {
                "term": {
                "ba_meetrefuse_id_explorer": "11111"
                }
            },
            "weight": {{function_score_params|weights|ba_meetrefuse_id_explorer}}
            },
            {
            "filter": {
                "term": {
                "ba_meetrefuse_id_search": "11111"
                }
            },
            "weight": {{function_score_params|weights|ba_meetrefuse_id_search}}
            },
            {
            "filter": {
                "term": {
                "ab_meetrefuse_id_explorer": "11111"
                }
            },
            "weight": {{function_score_params|weights|ab_meetrefuse_id_explorer}}
            },
            {
            "filter": {
                "term": {
                "ab_meetrefuse_id_search": "11111"
                }
            },
            "weight": {{function_score_params|weights|ab_meetrefuse_id_search}}
            },
            {
            "filter": {
                "term": {
                "ba_meetpending_id_explorer": "11111"
                }
            },
            "weight": {{function_score_params|weights|ba_meetpending_id_explorer}}
            },
            {
            "filter": {
                "term": {
                "ba_meetpending_id_search": "11111"
                }
            },
            "weight": {{function_score_params|weights|ba_meetpending_id_search}}
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
                                    "query": "student"
                                }
                            }
                        },
                        {
                            "match": {
                                "job.raw": {
                                    "fuzziness": "{{full_text_params|job.raw|fuzziness}}",
                                    "minimum_should_match": "{{full_text_params|job.raw|minimum_should_match}}",
                                    "prefix_length": {{full_text_params|job.raw|prefix_length}},
                                    "zero_terms_query": "{{full_text_params|job.raw|zero_terms_query}}",
                                    "analyzer": "{{full_text_params|job.raw|analyzer}}",
                                    "boost": {{full_text_params|job.raw|boost}},
                                    "query": "student"
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
        "node_id"
    ],
    "from" : 0, 
    "size" : 20
    }
    """

    Scenario: I search for one 'job' (without fuzziness)
    When I attempt to call the function "buildSearchQueryJson" with node id "11111" and lat "none" and lon "none" and JSON criterias :
    """
    {
        "job": [
          "maker"
        ]
    }
    """
    Then I expect the following JSON result :
     """
    {"explain":false,
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
                    "has_picture": {{function_score_params|weights|has_picture}}
                }
            },
            "weight": 1
            },
            {
            "filter": {
                "term": {
                "ab_meetpending_id_explorer": "11111"
                }
            },
            "weight": {{function_score_params|weights|ab_meetpending_id_explorer}}
            },
            {
            "filter": {
                "term": {
                "ab_meetpending_id_search": "11111"
                }
            },
            "weight": {{function_score_params|weights|ab_meetpending_id_search}}
            },
            {
            "filter": {
                "term": {
                "ba_meetrefuse_id_explorer": "11111"
                }
            },
            "weight": {{function_score_params|weights|ba_meetrefuse_id_explorer}}
            },
            {
            "filter": {
                "term": {
                "ba_meetrefuse_id_search": "11111"
                }
            },
            "weight": {{function_score_params|weights|ba_meetrefuse_id_search}}
            },
            {
            "filter": {
                "term": {
                "ab_meetrefuse_id_explorer": "11111"
                }
            },
            "weight": {{function_score_params|weights|ab_meetrefuse_id_explorer}}
            },
            {
            "filter": {
                "term": {
                "ab_meetrefuse_id_search": "11111"
                }
            },
            "weight": {{function_score_params|weights|ab_meetrefuse_id_search}}
            },
            {
            "filter": {
                "term": {
                "ba_meetpending_id_explorer": "11111"
                }
            },
            "weight": {{function_score_params|weights|ba_meetpending_id_explorer}}
            },
            {
            "filter": {
                "term": {
                "ba_meetpending_id_search": "11111"
                }
            },
            "weight": {{function_score_params|weights|ba_meetpending_id_search}}
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
                "bool": {
                    "should": [
                        {
                            "match": {
                                "job": {
                                    "fuzziness": 0,
                                    "minimum_should_match": "{{full_text_params|job|minimum_should_match}}",
                                    "prefix_length": {{full_text_params|job|prefix_length}},
                                    "zero_terms_query": "{{full_text_params|job|zero_terms_query}}",
                                    "analyzer": "{{full_text_params|job|analyzer}}",
                                    "boost": {{full_text_params|job|boost}},
                                    "query": "maker"
                                }
                            }
                        },
                        {
                            "match": {
                                "job.raw": {
                                    "fuzziness": 0,
                                    "minimum_should_match": "{{full_text_params|job.raw|minimum_should_match}}",
                                    "prefix_length": {{full_text_params|job.raw|prefix_length}},
                                    "zero_terms_query": "{{full_text_params|job.raw|zero_terms_query}}",
                                    "analyzer": "{{full_text_params|job.raw|analyzer}}",
                                    "boost": {{full_text_params|job.raw|boost}},
                                    "query": "maker"
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
        "node_id"
    ],
    "from" : 0, 
    "size" : 20
    }
    """

    Scenario: I search for one 'job' and one 'country'
    When I attempt to call the function "buildSearchQueryJson" with node id "11111" and lat "none" and lon "none" and JSON criterias :
    """
    {
        "job": [
          "doctor"
        ],
        "country": [
            "France"
        ]
    }
    """
    Then I expect the following JSON result :
     """
    {"explain":false,
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
                    "has_picture": {{function_score_params|weights|has_picture}}
                }
            },
            "weight": 1
            },
            {
            "filter": {
                "term": {
                "ab_meetpending_id_explorer": "11111"
                }
            },
            "weight": {{function_score_params|weights|ab_meetpending_id_explorer}}
            },
            {
            "filter": {
                "term": {
                "ab_meetpending_id_search": "11111"
                }
            },
            "weight": {{function_score_params|weights|ab_meetpending_id_search}}
            },
            {
            "filter": {
                "term": {
                "ba_meetrefuse_id_explorer": "11111"
                }
            },
            "weight": {{function_score_params|weights|ba_meetrefuse_id_explorer}}
            },
            {
            "filter": {
                "term": {
                "ba_meetrefuse_id_search": "11111"
                }
            },
            "weight": {{function_score_params|weights|ba_meetrefuse_id_search}}
            },
            {
            "filter": {
                "term": {
                "ab_meetrefuse_id_explorer": "11111"
                }
            },
            "weight": {{function_score_params|weights|ab_meetrefuse_id_explorer}}
            },
            {
            "filter": {
                "term": {
                "ab_meetrefuse_id_search": "11111"
                }
            },
            "weight": {{function_score_params|weights|ab_meetrefuse_id_search}}
            },
            {
            "filter": {
                "term": {
                "ba_meetpending_id_explorer": "11111"
                }
            },
            "weight": {{function_score_params|weights|ba_meetpending_id_explorer}}
            },
            {
            "filter": {
                "term": {
                "ba_meetpending_id_search": "11111"
                }
            },
            "weight": {{function_score_params|weights|ba_meetpending_id_search}}
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
                                "job.raw": {
                                    "fuzziness": "{{full_text_params|job.raw|fuzziness}}",
                                    "minimum_should_match": "{{full_text_params|job.raw|minimum_should_match}}",
                                    "prefix_length": {{full_text_params|job.raw|prefix_length}},
                                    "zero_terms_query": "{{full_text_params|job.raw|zero_terms_query}}",
                                    "analyzer": "{{full_text_params|job.raw|analyzer}}",
                                    "boost": {{full_text_params|job.raw|boost}},
                                    "query": "doctor"
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
        "node_id"
    ],
    "from" : 0, 
    "size" : 20
    }
    """

    Scenario: I search for a country only (case of a match all and one clause in filter)
    When I attempt to call the function "buildSearchQueryJson" with node id "11111" and lat "none" and lon "none" and JSON criterias :
    """
    {
        "country": [
            "France"
        ]
    }
    """
    Then I expect the following JSON result :
     """
    {"explain":false,
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
                    "has_picture": {{function_score_params|weights|has_picture}}
                }
            },
            "weight": 1
            },
            {
            "filter": {
                "term": {
                "ab_meetpending_id_explorer": "11111"
                }
            },
            "weight": {{function_score_params|weights|ab_meetpending_id_explorer}}
            },
            {
            "filter": {
                "term": {
                "ab_meetpending_id_search": "11111"
                }
            },
            "weight": {{function_score_params|weights|ab_meetpending_id_search}}
            },
            {
            "filter": {
                "term": {
                "ba_meetrefuse_id_explorer": "11111"
                }
            },
            "weight": {{function_score_params|weights|ba_meetrefuse_id_explorer}}
            },
            {
            "filter": {
                "term": {
                "ba_meetrefuse_id_search": "11111"
                }
            },
            "weight": {{function_score_params|weights|ba_meetrefuse_id_search}}
            },
            {
            "filter": {
                "term": {
                "ab_meetrefuse_id_explorer": "11111"
                }
            },
            "weight": {{function_score_params|weights|ab_meetrefuse_id_explorer}}
            },
            {
            "filter": {
                "term": {
                "ab_meetrefuse_id_search": "11111"
                }
            },
            "weight": {{function_score_params|weights|ab_meetrefuse_id_search}}
            },
            {
            "filter": {
                "term": {
                "ba_meetpending_id_explorer": "11111"
                }
            },
            "weight": {{function_score_params|weights|ba_meetpending_id_explorer}}
            },
            {
            "filter": {
                "term": {
                "ba_meetpending_id_search": "11111"
                }
            },
            "weight": {{function_score_params|weights|ba_meetpending_id_search}}
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
        "node_id"
    ],
    "from" : 0, 
    "size" : 20
    }
    """

    Scenario: I search for a tag only (case of match in filter)
    When I attempt to call the function "buildSearchQueryJson" with node id "11111" and lat "none" and lon "none" and JSON criterias :
    """
    {
        "tag": [
            "Startups"
        ]
    }
    """
    Then I expect the following JSON result :
     """
    {"explain":false,
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
                    "has_picture": {{function_score_params|weights|has_picture}}
                }
            },
            "weight": 1
            },
            {
            "filter": {
                "term": {
                "ab_meetpending_id_explorer": "11111"
                }
            },
            "weight": {{function_score_params|weights|ab_meetpending_id_explorer}}
            },
            {
            "filter": {
                "term": {
                "ab_meetpending_id_search": "11111"
                }
            },
            "weight": {{function_score_params|weights|ab_meetpending_id_search}}
            },
            {
            "filter": {
                "term": {
                "ba_meetrefuse_id_explorer": "11111"
                }
            },
            "weight": {{function_score_params|weights|ba_meetrefuse_id_explorer}}
            },
            {
            "filter": {
                "term": {
                "ba_meetrefuse_id_search": "11111"
                }
            },
            "weight": {{function_score_params|weights|ba_meetrefuse_id_search}}
            },
            {
            "filter": {
                "term": {
                "ab_meetrefuse_id_explorer": "11111"
                }
            },
            "weight": {{function_score_params|weights|ab_meetrefuse_id_explorer}}
            },
            {
            "filter": {
                "term": {
                "ab_meetrefuse_id_search": "11111"
                }
            },
            "weight": {{function_score_params|weights|ab_meetrefuse_id_search}}
            },
            {
            "filter": {
                "term": {
                "ba_meetpending_id_explorer": "11111"
                }
            },
            "weight": {{function_score_params|weights|ba_meetpending_id_explorer}}
            },
            {
            "filter": {
                "term": {
                "ba_meetpending_id_search": "11111"
                }
            },
            "weight": {{function_score_params|weights|ba_meetpending_id_search}}
            },
			{
			"filter": {
				"term": {
				"tag.raw": "Startups"
				}
			},
			"weight": {{function_score_params|weights|tag}}
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
        "node_id"
    ],
    "from" : 0, 
    "size" : 20
    }
    """

    Scenario: I search for multiple criterias including full text in filter and scored queries
    When I attempt to call the function "buildSearchQueryJson" with node id "11111" and lat "none" and lon "none" and JSON criterias :
    """
    {
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
    """
    Then I expect the following JSON result :
     """
    {"explain":false,
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
                    "has_picture": {{function_score_params|weights|has_picture}}
                }
            },
            "weight": 1
            },
            {
            "filter": {
                "term": {
                "ab_meetpending_id_explorer": "11111"
                }
            },
            "weight": {{function_score_params|weights|ab_meetpending_id_explorer}}
            },
            {
            "filter": {
                "term": {
                "ab_meetpending_id_search": "11111"
                }
            },
            "weight": {{function_score_params|weights|ab_meetpending_id_search}}
            },
            {
            "filter": {
                "term": {
                "ba_meetrefuse_id_explorer": "11111"
                }
            },
            "weight": {{function_score_params|weights|ba_meetrefuse_id_explorer}}
            },
            {
            "filter": {
                "term": {
                "ba_meetrefuse_id_search": "11111"
                }
            },
            "weight": {{function_score_params|weights|ba_meetrefuse_id_search}}
            },
            {
            "filter": {
                "term": {
                "ab_meetrefuse_id_explorer": "11111"
                }
            },
            "weight": {{function_score_params|weights|ab_meetrefuse_id_explorer}}
            },
            {
            "filter": {
                "term": {
                "ab_meetrefuse_id_search": "11111"
                }
            },
            "weight": {{function_score_params|weights|ab_meetrefuse_id_search}}
            },
            {
            "filter": {
                "term": {
                "ba_meetpending_id_explorer": "11111"
                }
            },
            "weight": {{function_score_params|weights|ba_meetpending_id_explorer}}
            },
            {
            "filter": {
                "term": {
                "ba_meetpending_id_search": "11111"
                }
            },
            "weight": {{function_score_params|weights|ba_meetpending_id_search}}
            },
			{
            "filter": {
                "term": {
                "tag.raw": "Startups"
                }
            },
            "weight": 1
			},
			{
            "filter": {
                "term": {
                "tag.raw": "Blockchain"
                }
            },
            "weight": 1
			}
        ],
        "query": {
            "bool": {
            "filter": {
                "bool": {
                "must": [
                {
                  "bool": {
                    "should": [
                      {
                        "match": {
                          "tag": {
                            "fuzziness": "{{full_text_params|tag|fuzziness}}",
                            "prefix_length": {{full_text_params|tag|prefix_length}},
                            "analyzer": "{{full_text_params|tag|analyzer}}",
                            "boost": 1,
                            "query": "Startups"
                          }
                        }
                      },
                      {
                        "match": {
                          "tag": {
                            "fuzziness": "{{full_text_params|tag|fuzziness}}",
                            "prefix_length": {{full_text_params|tag|prefix_length}},
                            "analyzer": "{{full_text_params|tag|analyzer}}",
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
                },
				{
					"bool": {
						"should": [
							{
								"term": {
									"country": "France"
								}
							},
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
                                "job.raw": {
                                    "fuzziness": "{{full_text_params|job.raw|fuzziness}}",
                                    "minimum_should_match": "{{full_text_params|job.raw|minimum_should_match}}",
                                    "prefix_length": {{full_text_params|job.raw|prefix_length}},
                                    "zero_terms_query": "{{full_text_params|job.raw|zero_terms_query}}",
                                    "analyzer": "{{full_text_params|job.raw|analyzer}}",
                                    "boost": {{full_text_params|job.raw|boost}},
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
                        },
                        {
                            "match": {
                                "job.raw": {
                                    "fuzziness": "{{full_text_params|job.raw|fuzziness}}",
                                    "minimum_should_match": "{{full_text_params|job.raw|minimum_should_match}}",
                                    "prefix_length": {{full_text_params|job.raw|prefix_length}},
                                    "zero_terms_query": "{{full_text_params|job.raw|zero_terms_query}}",
                                    "analyzer": "{{full_text_params|job.raw|analyzer}}",
                                    "boost": {{full_text_params|job.raw|boost}},
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
        "node_id"
    ],
    "from" : 0, 
    "size" : 20
    }
    """

    Scenario: I search for one 'job' without Location specify (around-me option will be enabled)
    When I attempt to call the function "buildSearchQueryJson" with node id "11111" and lat "40.71" and lon "74" and JSON criterias :
    """
    {
        "job": [
          "senior ios developer"
        ]
      }
    """
    Then I expect the following JSON result :
     """
    {"explain":false,
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
                "has_picture": {{function_score_params|weights|has_picture}}
                }
            },
            "weight": 1
            },
			{
			"{{function_score_params|options|around_me|method}}": {
						"location": {
							"origin": "40.71, 74",
							"scale": "{{function_score_params|options|around_me|scale}}",
							"offset": "{{function_score_params|options|around_me|offset}}",
							"decay": {{function_score_params|options|around_me|decay}}
						}
					},
					"weight": 3
			},
            {
            "filter": {
                "term": {
                "ab_meetpending_id_explorer": "11111"
                }
            },
            "weight": {{function_score_params|weights|ab_meetpending_id_explorer}}
            },
            {
            "filter": {
                "term": {
                "ab_meetpending_id_search": "11111"
                }
            },
            "weight": {{function_score_params|weights|ab_meetpending_id_search}}
            },
            {
            "filter": {
                "term": {
                "ba_meetrefuse_id_explorer": "11111"
                }
            },
            "weight": {{function_score_params|weights|ba_meetrefuse_id_explorer}}
            },
            {
            "filter": {
                "term": {
                "ba_meetrefuse_id_search": "11111"
                }
            },
            "weight": {{function_score_params|weights|ba_meetrefuse_id_search}}
            },
            {
            "filter": {
                "term": {
                "ab_meetrefuse_id_explorer": "11111"
                }
            },
            "weight": {{function_score_params|weights|ab_meetrefuse_id_explorer}}
            },
            {
            "filter": {
                "term": {
                "ab_meetrefuse_id_search": "11111"
                }
            },
            "weight": {{function_score_params|weights|ab_meetrefuse_id_search}}
            },
            {
            "filter": {
                "term": {
                "ba_meetpending_id_explorer": "11111"
                }
            },
            "weight": {{function_score_params|weights|ba_meetpending_id_explorer}}
            },
            {
            "filter": {
                "term": {
                "ba_meetpending_id_search": "11111"
                }
            },
            "weight": {{function_score_params|weights|ba_meetpending_id_search}}
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
                                    "query": "senior ios developer"
                                }
                            }
                        },
                        {
                            "match": {
                                "job.raw": {
                                    "fuzziness": "{{full_text_params|job.raw|fuzziness}}",
                                    "minimum_should_match": "{{full_text_params|job.raw|minimum_should_match}}",
                                    "prefix_length": {{full_text_params|job.raw|prefix_length}},
                                    "zero_terms_query": "{{full_text_params|job.raw|zero_terms_query}}",
                                    "analyzer": "{{full_text_params|job.raw|analyzer}}",
                                    "boost": {{full_text_params|job.raw|boost}},
                                    "query": "senior ios developer"
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
        "node_id"
    ],
    "from" : 0, 
    "size" : 20
    }
    """

  Scenario: I search for autocompletion with field "tag"
    When I attempt to call the function "buildSearchQueryAutocompletionJson" with field "tag" and text "Star"
    Then I expect the following JSON result :
    """
    {
    "_source": [
        "tag"
    ],
    "suggest": {
        "{{autocompletion_params|tag|index_name}}": {
            "prefix": "Star",
            "completion": {
                "field": "{{autocompletion_params|tag|index_name}}",
                "analyzer": "{{autocompletion_params|tag|params|analyzer}}",
                "size": {{autocompletion_params|tag|params|size}},
                "fuzzy": {
                    "fuzziness": {{autocompletion_params|tag|params|fuzzy|fuzziness}},
                    "prefix_length": {{autocompletion_params|tag|params|fuzzy|prefix_length}}
                }
            }
        }
    }
    }
    """

	Scenario: I search for autocompletion with field "job"
    When I attempt to call the function "buildSearchQueryAutocompletionJson" with field "job" and text "Prod"
    Then I expect the following JSON result :
    """
    {
    "suggest": {
        "{{autocompletion_params|job|index_name}}": {
        "completion": {
            "analyzer": "{{autocompletion_params|job|params|analyzer}}",
            "size": {{autocompletion_params|job|params|size}},
            "fuzzy": {
                "fuzziness": {{autocompletion_params|job|params|fuzzy|fuzziness}},
                "prefix_length": {{autocompletion_params|job|params|fuzzy|prefix_length}}
            },
            "field": "{{autocompletion_params|job|index_name}}"
        },
        "prefix": "Prod"
        }
    },
    "_source": [
        "job"
    ]
    }
    """

  Scenario: I search for autocompletion with field "place"
    When I attempt to call the function "buildSearchQueryAutocompletionJson" with field "place" and text "New"
    Then I expect the following JSON result :
    """
    {
    "suggest": {
        "{{autocompletion_params|place|index_name}}": {
        "completion": {
            "analyzer": "{{autocompletion_params|place|params|analyzer}}",
            "size": {{autocompletion_params|place|params|size}},
            "fuzzy": {
                "fuzziness": {{autocompletion_params|place|params|fuzzy|fuzziness}},
                "prefix_length": {{autocompletion_params|place|params|fuzzy|prefix_length}}
            },
            "field": "place_suggest"
        },
        "prefix": "New"
        }
    },
    "_source": [
        "place",
        "type"
    ]
    }
    """