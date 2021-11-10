<?php

namespace App\Search;

use Elasticsearch\Client;

class ElasticsearchObserver
{
    private Client $elasticsearchClient;

    public function __construct(Client $elasticsearchClient)
    {
        $this->elasticsearchClient = $elasticsearchClient;
    }

    public function saved($model)
    {
        $model->elasticSearchIndex($this->elasticsearchClient);
    }

    public function deleted($model)
    {
        $model->elasticSearchDelete($this->elasticsearchClient);
    }
}
