<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Articles;
use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->app->bind(Articles\SearchRepository::class, function ($app) {
            // This is useful in case we want to turn-off our
            // search cluster or when deploying the search
            // to a live, running application at first.
            if (! config('services.search.enabled')) {
                return new Articles\EloquentRepository();
            }

            return new Articles\ElasticsearchRepository(
                $app->make(Client::class)
            );
        });
        $this->bindSearchClient();
    }

    private function bindSearchClient()
    {
        $this->app->bind(Client::class, function ($app) {
            // return ClientBuilder::create()
                // ->setHosts(config('services.search.hosts'))

                // ->setApikey('Qz_QKX0BfjiEAb9uVyUP', 'SUD960LUQt-F-ASNoGvPIg')
                // ->build();
                $config = [
                    'hosts'     => config('services.search.hosts'),
                    'retries'   => 1,
                    'apikey'   => ['Qz_QKX0BfjiEAb9uVyUP', 'SUD960LUQt-F-ASNoGvPIg']
                ];

                return ClientBuilder::fromConfig($config);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
