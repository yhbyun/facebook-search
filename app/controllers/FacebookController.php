<?php

class FacebookController extends BaseController
{
    protected $layout = 'layouts.facebook';

    public function getIndex()
    {
        $data = array();
        $groups = array();

        if (Auth::check()) {
            $data = Auth::user();

            try {
                $facebook = new Facebook(Config::get('facebook'));
                $access_token = $facebook->getAccessToken();
                $groups = $facebook->api('/me/groups', 'GET', array('access_token=' => $access_token));
            } catch (FacebookApiException $e) {
                MyLog::error($e);
                Session::flash('message', 'There was an error');
            }
        }

        $this->view('facebook.user', compact('data', 'groups'));
    }

    public function getPosts($id)
    {
        try {
            $facebook = new Facebook(Config::get('facebook'));

            // 그룹 정보
            $group = $facebook->api('/' . $id, 'GET');

            // 그룹내 피드 리스트
            // TODO : 글 이미지 큰 이미지 사용하기
            // TODO : 댓글에 첨부 이미지가 있는 경우 표시 되게
            $params = ['limit' => 10];
            if (Input::has('since'))
                $params['since'] = Input::get('since');
            if (Input::has('until'))
                $params['until'] = Input::get('until');
            if (Input::has('__paging_token'))
                $params['__paging_token'] = Input::get('__paging_token');
            if (Input::has('__previous'))
                $params['__previous'] = Input::get('__previous');
            $posts = $facebook->api('/' . $id . '/feed', 'GET', $params);

            // 페이징
            if (isset($posts['paging']['previous'])) {
                $posts['paging']['previous_query'] = parse_url($posts['paging']['previous'], PHP_URL_QUERY);
            }
            if (isset($posts['paging']['next'])) {
                $posts['paging']['next_query'] = parse_url($posts['paging']['next'], PHP_URL_QUERY);
            }

        } catch (FacebookApiException $e) {
            MyLog::error($e);
            return Redirect::route('facebook.main')
                ->with('message', 'There was an error');
        }

        $this->view('facebook.posts', compact('group', 'posts'));
    }

    public function getPostsImport($id)
    {
        set_time_limit(1800);

        try {
            $facebook = new Facebook(Config::get('facebook'));

            // 그룹 정보
            $group = $facebook->api('/' . $id, 'GET');

            $apiCnt = $createdCnt = $updatedCnt = 0;
            $params = ['limit' => 100];

            while (true) {
                $posts = $facebook->api('/' . $id . '/feed', 'GET', $params);

                foreach ($posts['data'] as $post) {
                    // feed api returns 25 comments only
                    if (isset($post['comments'])) {
                        $comments = $this->getComments($facebook, $post['id']);
                        $post['comments'] = $comments;
                    }

                    $result = $this->addPost($post);
                    if ($result) {
                        $result['created'] ? $createdCnt++ : $updatedCnt++;
                    }
                }

                if (isset($posts['paging']['next'])) {
                    $queryString = parse_url($posts['paging']['next'], PHP_URL_QUERY);
                    parse_str($queryString, $params);
                } else {
                    break;
                }

                $apiCnt++;
            }

            die("$apiCnt api called, $createdCnt created, $updatedCnt updated");

        } catch (FacebookApiException $e) {
            MyLog::error($e);
            return Redirect::route('facebook.main')
                ->with('message', 'There was an error');
        }
    }


    private function getComments($facebook, $id)
    {
        $limit = 20;
        $params = ['limit' => $limit];
        $comments = ['data' => []];

        while (true) {
            $objects = $facebook->api('/' . $id . '/comments', 'GET', $params);
            $comments['data'] = array_merge($comments['data'], $objects['data']);

            if (isset($objects['paging']['next'])) {
                $queryString = parse_url($objects['paging']['next'], PHP_URL_QUERY);
                parse_str($queryString, $params);
            } else {
                break;
            }
        }

        return $comments;
    }


    private function addPost($post)
    {
        $created = false;

        $fbPost = FbPost::find($post['id']);
        if (! $fbPost)  {
            // newly added post
            $fbPost = new FbPost;
            $fbPost->id = $post['id'];
            $created = true;
        } else {
            // if not updated, do nothing
            //if ($fbPost->updated_at->eq($this->toDateTime($post['updated_time']))) {
            //    return false;
            //}
        }
        $fbPost->from = $this->findOrCreateUser($post['from']['id'], $post['from']['name'])->id;;
        $fbPost->to = $this->findOrCreateUser($post['to']['data'][0]['id'], $post['to']['data'][0]['name'])->id;
        $fbPost->message = get_if_set($post['message']);
        $fbPost->full_picture = get_if_set($post['full_picture']);
        $fbPost->picture = get_if_set($post['picture']);
        $fbPost->link = get_if_set($post['link']);
        $fbPost->name = get_if_set($post['name']);
        $fbPost->caption = get_if_set($post['caption']);
        $fbPost->description = get_if_set($post['description']);
        $fbPost->icon = get_if_set($post['icon']);
        $fbPost->created_at = $this->toDateTime($post['created_time']);
        $fbPost->updated_at = $this->toDateTime($post['updated_time']);
        $fbPost->save();

        $fbPost->likes()->detach();

        // like 추가
        if (isset($post['likes'])) {
            foreach($post['likes']['data'] as $user) {
                $fbUser = $this->findOrCreateUser($user['id'], $user['name']);
                $fbPost->likes()->attach($fbUser->id, [
                    'created_at' => new \DateTime,
                    'updated_at' => new \DateTime
                ]);
            }
        }

        $fbPost->comments()->delete();

        // 댓글 추가
        if (isset($post['comments'])) {
            foreach($post['comments']['data'] as $comment) {
                try {
                    $fbComment = new FbComment;
                    $fbComment->id = $comment['id'];
                    $fbComment->fb_user_id = $this->findOrCreateUser($comment['from']['id'], $comment['from']['name'])->id;;
                    $fbComment->fb_post_id = $fbPost->id;
                    $fbComment->message = $comment['message'];
                    $fbComment->like_count = $comment['like_count'];
                    $fbComment->created_at = $this->toDateTime($comment['created_time']);
                    $fbComment->save();
                } catch (Exception $e) {
                    MyLog::error($e);
                }
            }
        }

        return ['created' => $created, 'post' => $fbPost];
    }

    private function findOrCreateUser($id, $name)
    {
        $fbUser = FbUser::find($id);
        if (! $fbUser) {
            $fbUser = new FbUser;
            $fbUser->id = $id;
            $fbUser->name = $name;
            $fbUser->save();
        }
        return $fbUser;
    }

    private function toDateTime($utc)
    {
        return \Carbon\Carbon::createFromTimestamp(strtotime($utc));
    }

    public function getLogin()
    {
        $facebook = new Facebook(Config::get('facebook'));
        $params = array(
            //TODO : named route 사용
            'redirect_uri' => url('/login/callback'),
            'scope' => ['email', 'read_stream', 'publish_actions', 'user_groups']
        );
        return Redirect::away($facebook->getLoginUrl($params));
    }

    public function getCallback()
    {
        $code = Input::get('code');
        if (strlen($code) == 0) {
            MyLog::error('code is empty');
            return Redirect::route('facebook.main')
                ->with('message', 'There was an error communicating with Facebook');
        }

        $facebook = new Facebook(Config::get('facebook'));
        $uid = $facebook->getUser();

        if ($uid == 0) {
            MyLog::error('getUser api returns 0');
            return Redirect::route('facebook.main')
                ->with('message', 'There was an error');
        }

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

    public function getLogout()
    {
        Auth::logout();
        return Redirect::route('facebook.main');
    }
}
