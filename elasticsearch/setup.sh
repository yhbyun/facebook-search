#!/usr/bin/env bash

if [ "$1" = "init" ]; then
  echo "register facebook river"
  curl -XPUT 'elastic.dev:9200/_river/facebook/_meta' -d '{
      "type" : "jdbc",
      "jdbc" : {
          "url" : "jdbc:mysql://192.168.22.42:3306/facebook",
          "user" : "homestead",
          "password" : "secret",
          "sql" : "select p.id as \"_id\", u.name as \"from.name\", u.id as \"from.id\", p.message, p.full_picture, p.picture, p.link, p.name, p.caption, p.description, p.icon, p.created_at, p.updated_at, c.name as \"comments.data[from_name]\", c.id as \"comments.data[from_id]\", c.message as \"comments.data[message]\", c.created_at as \"comments.data[created_at]\", c.like_count as \"comments.data[like_count]\" from fb_posts p inner join fb_users u on u.id = p.from left join (select c.fb_post_id, u.id, u.name, c.message, c.like_count, c.created_at from fb_users u inner join fb_comments c on u.id = c.fb_user_id) as c on c.fb_post_id = p.id order by p.created_at, \"comments.data[created_at]\"",
          "index" : "facebook",
          "type" : "post"
      }
  }'
else
  echo "deleting facebook river"
  curl -XDELETE 'elastic.dev:9200/_river/facebook'

  echo "delting faceook index"
  curl -XDELETE 'elastic.dev:9200/facebook'
fi
