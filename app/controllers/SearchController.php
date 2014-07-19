<?php

class SearchController extends BaseController
{
    protected $layout = 'layouts.facebook';

    public function getIndex()
    {
        $term = e(Input::get('q'));

        $params = array();
        $params['hosts'] = array (
            'elastic.dev:9200',         // IP + Port
        );

        $client = new Elasticsearch\Client($params);

        $searchParams['index'] = 'facebook';
        $searchParams['type']  = 'post';

        if (! empty($term)) {
            $searchParams['body']['query']['match']['_all'] = $term;
            //$searchParams['body']['highlight']['fields']['_all'] = array();
        } else {
            $searchParams['body']['query']['match_all'] = array();
        }

        $page = Input::has('page') ? Input::get('page') : 1;
        $limit = 10;

        $searchParams['body']['from'] = $limit * ($page - 1);
        $searchParams['body']['size'] = $limit;

        $res = $client->search($searchParams);

        $data = new stdClass;
        $data->page = $page;
        $data->limit = $limit;
        $data->totalItems = $res['hits']['total'];
        $data->items = $res['hits']['hits'];;

        $postPage = Paginator::make($data->items, $data->totalItems, 10);

        View::share('term', $term);
        $this->view('facebook.search', compact('res', 'postPage'));
    }
}
