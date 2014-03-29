@section('title', 'Facebook API Example')

@section('styles')
@stop

@section('scripts')
@stop


@section('content')
    @if(Session::has('message'))

    <div class="row">
        <div class="col-xs-12">
            <div class="alert alert-success alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                {{ Session::get('message')}}
            </div>
        </div>
    </div>
    @endif

    @if (!empty($data))
    <div class="row">
        <div class="col-xs-12">
            <div class="media">
                <a class="pull-left">
                    <img class="media-object img-rounded img-responsive" src="{{ $data['photo']}}" alt="Profile image">
                </a>
                <div class="media-body">
                    <h4 class="media-heading">{{{ $data['name'] }}} </h4>
                    Your email is {{ $data['email']}}
                </div>
            </div>
        </div>
    </div>

    <h3>{{ count($groups['data']) }}개 그룹</h3>
    <ul>
        @foreach ($groups['data'] as $group)
        <li><a href="{{ route('facebook.posts', $group['id']) }}">{{{ $group['name'] }}} - ID = {{{ $group['id'] }}}</a></li>
        @endforeach
    </ul>

    <hr>
    <a href="{{ route('facebook.logout') }}">Logout</a>
    @else
    <div class="row">
        <div class="col-xs-12">
            <div class="well">
                <h1>Facebook login</h1>
                <p class="text-center">
                    <a class="btn btn-lg btn-primary" href="{{ route('facebook.login') }}"><i class="icon-facebook"></i> Login with Facebook</a>
                </p>
            </div>
        </div>
    </div>
    @endif

@stop
