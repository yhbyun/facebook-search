facebook-search
===============


## 설치 방법

#### 소스 코드 다운로드 

```
$ git clone https://github.com/yhbyun/facebook-search.git
```

#### VM 생성

```
$ vagrant up
```

#### DB 설정

`facebook` DB 생성

```
$ vagrant ssh
$ mysql -uroot -p
password:root

mysql> create database facebook;
```

DB 설정 변경

```
$ vi app/config/database.php

'mysql' => array(
	'driver'    => 'mysql',
	'host'      => 'localhost',
	'database'  => 'facebook',
	'username'  => 'root',
	'password'  => 'root',
	'charset'   => 'utf8',
	'collation' => 'utf8_unicode_ci',
	'prefix'    => '',
),
```

DB migration

```
$ vagrant ssh
$ cd /vagrant
$ php argisan migrate
```

#### 설치 테스트

`http://192.168.33.12.xip.io` 방문