<?php

namespace esQueryBuilder;

interface queryBuilderInterface
{

    // Force les classes filles à définir cette méthode en utilisant les types definis
    static function buildSearchQuery($requester_id, array $criterias, $from = 0, $size = 20, $explain = false, $lat = null, $lon = null);

}