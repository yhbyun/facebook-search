<?php

class FacebookController extends BaseController {

    protected $layout = 'layouts.facebook';

    public function getIndex() {
        $data = array();
        $groups = array();

        if (Auth::check()) {
            $data = Auth::user();

            try {
                $facebook = new Facebook(Config::get('facebook'));
                $access_token = $facebook->getAccessToken();
                $groups = $facebook->api('/me/groups', 'GET', array('access_token=' => $access_token));
            } catch (FacebookApiException $e) {
                return Redirect::route('facebook.main')
                    ->with('message', 'There was an error');
            }
        }

        $this->view('facebook.user', compact('data', 'groups'));
    }

    public function getPosts($id) {
        try {
            $facebook = new Facebook(Config::get('facebook'));

            // 그룹 정보
            $group = $facebook->api('/' . $id, 'GET');

            $params = ['limit' => 10];
            if (Input::has('since')) $params['since'] = Input::get('since');
            if (Input::has('until')) $params['until'] = Input::get('until');
            if (Input::has('__paging_token')) $params['__paging_token'] = Input::get('__paging_token');
            if (Input::has('__previous')) $params['__previous'] = Input::get('__previous');
            $posts = $facebook->api('/' . $id . '/feed', 'GET', $params);

            // 페이징
            if (isset($posts['paging']['previous'])) {
                $posts['paging']['previous_query'] = parse_url($posts['paging']['previous'], PHP_URL_QUERY);
            }
            if (isset($posts['paging']['next'])) {
                $posts['paging']['next_query'] = parse_url($posts['paging']['next'], PHP_URL_QUERY);
            }

        } catch (FacebookApiException $e) {
            return Redirect::route('facebook.main')
                ->with('message', 'There was an error');
        }

        $this->view('facebook.posts', compact('group', 'posts'));
    }

    public function getLogin() {
        $facebook = new Facebook(Config::get('facebook'));
        $params = array(
            //TODO : named route 사용
            'redirect_uri' => url('/login/callback'),
            'scope' => ['email', 'read_stream', 'publish_actions', 'user_groups']
        );
        return Redirect::away($facebook->getLoginUrl($params));
    }

    public function getCallback() {
        $code = Input::get('code');
        if (strlen($code) == 0) return Redirect::route('facebook.main')
            ->with('message', 'There was an error communicating with Facebook');

        $facebook = new Facebook(Config::get('facebook'));
        $uid = $facebook->getUser();

        if ($uid == 0) return Redirect::route('facebook.main')
            ->with('message', 'There was an error');

        $me = $facebook->api('/me');

        $profile = Profile::whereUid($uid)->first();
        if (empty($profile)) {

            $user = new User;
            $user->name = $me['first_name'].' '.$me['last_name'];
            $user->email = $me['email'];
            $user->photo = 'https://graph.facebook.com/'.$me['username'].'/picture?type=large';

            $user->save();

            $profile = new Profile();
            $profile->uid = $uid;
            $profile->username = $me['username'];
            $profile = $user->profiles()->save($profile);
        }

        $facebook->setExtendedAccessToken(); //long-live access_token 60 days
        $profile->access_token = $facebook->getAccessToken();
        $profile->save();

        $user = $profile->user;

        Auth::login($user);

        return Redirect::route('facebook.main')->with('message', 'Logged in with Facebook');
    }

    public function getLogout() {
        Auth::logout();
        return Redirect::route('facebook.main');
    }
}