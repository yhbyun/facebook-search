<?php

class FacebookController extends BaseController {

    protected $layout = 'layouts.facebook';

    public function getIndex() {
        $data = array();
        $groups = array();

        if (Auth::check()) {
            $data = Auth::user();

            $facebook = new Facebook(Config::get('facebook'));
            $access_token = $facebook->getAccessToken();

            $groups = $facebook->api('/me/groups', 'GET', array('access_token=' => $access_token));
        }

        $this->view('facebook.user', compact('data', 'groups'));
    }

    public function getPosts($id) {
        try {
            $facebook = new Facebook(Config::get('facebook'));

            // 그룹 정보
            $group = $facebook->api('/' . $id, 'GET');

            // fql 쿼리는 편하긴 한데 정보가 부족하다.
            /*
            $fql_query = 'SELECT post_id, actor_id, app_data, created_time, message, comment_info, likes FROM stream WHERE source_id = '
                . $id .' ORDER BY created_time DESC LIMIT 10';
            $posts = $facebook->api(array('method' => 'fql.query', 'query' => $fql_query));
            */

            $params = ['limit' => 10];
            if (Input::has('since')) $params['since'] = Input::get('since');
            if (Input::has('until')) $params['until'] = Input::get('until');
            if (Input::has('__paging_token')) $params['__paging_token'] = Input::get('__paging_token');
            if (Input::has('__previous')) $params['__previous'] = Input::get('__previous');
            $posts = $facebook->api('/' . $id . '/feed', 'GET', $params);

            foreach ($posts['data'] as &$post) {
                /*
                if (isset($post['picture'])) {
                    $image = $facebook->api('/' . $post['id'] . '?fields=full_picture');
                    $post['full_picture'] = $image['full_picture'];
                    unset($post['picture']);
                }
                */

                /*
                if ($num_comments > 0) {
                    $fql_query = "SELECT likes, id, time, text, fromid FROM comment WHERE post_id='" . $post['id'] . "'";
                    $comments = $facebook->api(array('method' => 'fql.query', 'query' => $fql_query));
                    $post['comments'] = $comments;
                }
                */
            }

            if (isset($posts['paging']['previous'])) {
                $posts['paging']['previous_query'] = parse_url($posts['paging']['previous'], PHP_URL_QUERY);
            }
            if (isset($posts['paging']['next'])) {
                $posts['paging']['next_query'] = parse_url($posts['paging']['next'], PHP_URL_QUERY);
            }

        } catch (FacebookApiException $e) {
            dd($e);
        }

        $this->view('facebook.posts', compact('group', 'posts'));
    }

    public function getLogin() {
        $facebook = new Facebook(Config::get('facebook'));
        $params = array(
            'redirect_uri' => url('/login/callback'),
            'scope' => ['email', 'read_stream', 'publish_actions', 'user_groups']
        );
        return Redirect::away($facebook->getLoginUrl($params));
    }

    public function getCallback() {
        $code = Input::get('code');
        if (strlen($code) == 0) return Redirect::away('/')->with('message', 'There was an error communicating with Facebook');

        $facebook = new Facebook(Config::get('facebook'));
        $uid = $facebook->getUser();

        if ($uid == 0) return Redirect::away('/')->with('message', 'There was an error');

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

        return Redirect::away('/')->with('message', 'Logged in with Facebook');
    }

    public function getLogout() {
        Auth::logout();
        return Redirect::away('/');
    }
}