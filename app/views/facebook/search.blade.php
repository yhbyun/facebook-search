@section('title', 'Facebook Search')

@section('styles')
@stop

@section('scripts')
@stop


@section('content')

<?php
$posts = $res['hits']['hits'];
?>

<div class="row">
    <div class="col-xs-12 col-sm-8">
        <h1 class="page-title">{{ $res['hits']['total'] }} Search {{Str::plural('result', count($posts));}} for &quot;<strong>{{{$term}}}</strong>&quot;</h1>

    @if (!empty($posts))
        @foreach ($posts as $data)
        <?php
            $post = $data['_source'];
            //dd($data);
            //dd($data['highlight']);
        ?>
    <div class="row">
        <div class="col-xs-12">
            <ul class="media-list well">
                <li class="media">
                    <a class="pull-left" href="#">
                        <img class="media-object img-rounded" src="http://graph.facebook.com/{{ $post['from']['id'] }}/picture">
                    </a>
                    <div class="media-body" rel="{{ $data['_id'] }}">
                        @if (isset($post['message']))
                        <h4 class="media-heading">{{ MyString::paragraph(MyString::linkUrls($post['message'])) }}</h4>
                        @endif
                        {{ isset($post['full_picture']) ? '<img class="img-responsive" src="'. $post['full_picture'] . '">' : '' }}
                        {{ isset($post['picture']) ? '<img class="picture img-responsive" src="'. $post['picture'] . '">' : '' }}
                        {{ isset($post['link']) && isset($post['name']) ? '<p><a href="'. $post['link'] . '">' . $post['name'] . '</a></p>' : '' }}
                        {{ isset($post['caption']) ? '<p><a href=http://"'. $post['caption'] . '">' . $post['caption'] . '</a></p>' : '' }}
                        {{ isset($post['description']) ? '<p>' . $post['description'] . '</p>' : '' }}
                        <p><strong>{{{ $post['from']['name'] }}}</strong> - {{ \Carbon\Carbon::createFromTimestamp(strtotime($post['created_at']))->diffForHumans() }}
                        {{-- FIXME: likes 수 현재 보이지 않음 --}}
                        @if (isset($post['likes']) && count($post['likes']))
                        - <span class="glyphicon glyphicon-thumbs-up"></span>
                        <span class="likes">{{ count($post['likes']) }}</span>
                        @endif
                        @if (isset($post['comments']))
                            {{-- var_dump($post['comments']['data']) --}}
                            @foreach ($post['comments']['data'] as $comment)
                                {{-- why having 1 array although no comments --}}
                                @if ($comment['message'])
                        <div class="media">
                            <a class="pull-left" href="#">
                                <img class="media-object img-rounded" src="http://graph.facebook.com/{{ $comment['from_id'] }}/picture">
                            </a>
                            <div class="media-body">
                                <h4 class="media-heading">{{ MyString::paragraph(MyString::linkUrls($comment['message'])) }}</h4>
                                <p>{{ \Carbon\Carbon::createFromTimestamp(strtotime($comment['created_at']))->diffForHumans() }}
                                @if ($comment['like_count'] > 0)
                                - <span class="glyphicon glyphicon-thumbs-up"></span>
                                <span class="likes">{{ $comment['like_count'] }}</span>
                                @endif
                                </p>
                            </div>
                        </div>
                                @endif
                            @endforeach
                        @endif
                    </div>
                </li>
            </ul>
        </div>
    </div>
        @endforeach

        @if ($postPage->count() === 0)
    <div class="row">
        <div class="col-xs-12">
            <h3>No More Posts</h3>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <ul class="pager">
                <li class="next"><a href="{{ URL::current() }}">Next</a></li>
            </ul>
        </div>
    </div>
         @else

    <div class="row">
        <div class="col-xs-12">
            {{ $postPage->appends(['q' => $term])->links(); }}
        </div>
    </div>
        @endif
    @endif
    </div>
    <div class="col-sm-4 hidden-xs">
        {{--
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">{{ $group['name'] }}</h3>
            </div>
            <div class="panel-body">
                {{ isset($group['description']) ? MyString::paragraph(MyString::linkUrls($group['description'])) : '' }}
            </div>
        </div>
        --}}
    </div>
</div>

@stop
