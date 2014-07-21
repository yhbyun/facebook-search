<?php

class SearchController extends BaseController
{
    protected $layout = 'layouts.facebook';

    public function getIndex()
    {
        if (! Auth::check()) {
            return $this->redirectRoute('facebook.main');
        }

        $term = e(Input::get('q'));
        $sort = e(Input::get('s'));
        if (empty($sort)) $sort = 'recent';

        $params = array();
        $params['hosts'] = array (
            Config::get('env.elastic.server')
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

        switch ($sort) {
            case 'recent':
                $searchParams['body']['sort'] = [
                    'updated_at' => 'desc',
                    '_script' => [
                        'script' => '_source.comments.data.size()',
                        'type' => 'number',
                        'order' => 'desc'
                    ],
                    '_score' => 'desc'
                ];
                break;

            case 'accurate':
                $searchParams['body']['sort'] = [
                    '_score' => 'desc',
                    'updated_at' => 'desc',
                    '_script' => [
                        'script' => '_source.comments.data.size()',
                        'type' => 'number',
                        'order' => 'desc'
                    ]
                ];
                break;

            case 'commented':
                $searchParams['body']['sort'] = [
                    '_script' => [
                        'script' => '_source.comments.data.size()',
                        'type' => 'number',
                        'order' => 'desc'
                    ],
                    'updated_at' => 'desc',
                    '_score' => 'desc'
                ];
                break;
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
        View::share('sort', $sort);
        $this->view('facebook.search', compact('res', 'postPage'));
    }
}
