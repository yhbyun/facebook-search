@section('title', 'Facebook API Example')

@section('styles')
@stop

@section('scripts')
<script>
$(function() {
    // Bind progress buttons and simulate loading progress
    Ladda.bind('.ladda-button', {
        callback: function (instance) {
            var progress = 0;
            var interval = setInterval(function() {
                progress = Math.min(progress + Math.random() * 0.1, 1);
                instance.setProgress(progress);

                if (progress === 1) {
                    instance.stop();
                    clearInterval(interval);
                }
            }, 10000);
        }
    });

    $('#btn-import').click(function (e) {
        e.preventDefault();
        var l = Ladda.create(this);
        l.start();

        $.get($(this).attr('href'),
            function (response) {
                alert(response);
            },
            'html'
        )
        .always(function() { l.stop(); });
        return false;
    })
});
</script>
@stop


@section('content')

<div class="row">
    <div class="col-xs-12 col-sm-8">

    @if (Auth::user()->email === 'yonghunbyun@gmail.com')
        <a href="{{ route('facebook.posts.import', $group['id']) }}" id="btn-import" class="btn btn-lg btn-primary ladda-button" data-style="expand-right"><span class="ladda-label">Import</span></a>
    @endif

    @if (!empty($posts))
        @foreach ($posts['data'] as $post)
        <?php flush(); ?>
    <div class="row">
        <div class="col-xs-12">
            <ul class="media-list well">
                <li class="media">
                    <a class="pull-left" href="#">
                        <img class="media-object img-rounded" src="http://graph.facebook.com/{{ $post['from']['id'] }}/picture">
                    </a>
                    <div class="media-body">
                        @if (isset($post['message']))
                        <h4 class="media-heading">{{ MyString::paragraph(MyString::linkUrls($post['message'])) }}</h4>
                        @endif
                        {{ isset($post['full_picture']) ? '<img class="img-responsive" src="'. $post['full_picture'] . '">' : '' }}
                        {{ isset($post['picture']) ? '<img class="picture img-responsive" src="'. $post['picture'] . '">' : '' }}
                        {{ isset($post['link']) && isset($post['name']) ? '<p><a href="'. $post['link'] . '">' . $post['name'] . '</a></p>' : '' }}
                        {{ isset($post['caption']) ? '<p><a href=http://"'. $post['caption'] . '">' . $post['caption'] . '</a></p>' : '' }}
                        {{ isset($post['description']) ? '<p>' . $post['description'] . '</p>' : '' }}
                        <p><strong>{{{ $post['from']['name'] }}}</strong> - {{ MyString::prettyDate(strtotime($post['created_time'])) }} -
                        <span class="glyphicon glyphicon-thumbs-up"></span>
                        <span class="likes">{{ isset($post['likes']) ? count($post['likes']['data'][0]) : 0 }}</span>
                        @if (isset($post['comments']))
                            @foreach ($post['comments']['data'] as $comment)
                        <div class="media">
                            <a class="pull-left" href="#">
                                <img class="media-object img-rounded" src="http://graph.facebook.com/{{ $comment['from']['id'] }}/picture">
                            </a>
                            <div class="media-body">
                                <h4 class="media-heading">{{ MyString::paragraph(MyString::linkUrls($comment['message'])) }}</h4>
                                <p>{{ MyString::prettyDate(strtotime($comment['created_time'])) }}</p>
                                <span class="glyphicon glyphicon-thumbs-up"></span>
                                <span class="likes">{{ $comment['like_count'] }}</span>
                            </div>
                        </div>
                            @endforeach
                        @endif
                    </div>
                </li>
            </ul>
        </div>
    </div>
        @endforeach

        @if (count($posts['data']) == 0)
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
            <ul class="pager">
                @if (isset($posts['paging']['previous_query']))
                <li class="previous"><a href="?{{ $posts['paging']['previous_query'] }}">Previous</a></li>
                @endif
                @if (isset($posts['paging']['next_query']))
                <li class="next"><a href="?{{ $posts['paging']['next_query'] }}">Next</a></li>
                @endif
            </ul>
        </div>
    </div>
        @endif

    @endif
    </div>
    <div class="col-sm-4 hidden-xs">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">{{ $group['name'] }}</h3>
            </div>
            <div class="panel-body">
                {{ isset($group['description']) ? MyString::paragraph(MyString::linkUrls($group['description'])) : '' }}
            </div>
        </div>
    </div>
</div>

@stop
