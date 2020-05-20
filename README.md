# sandbox-wordpress

Wordpress for Docker Compose

- [クイックスタート](https://docs.docker.com/compose/wordpress/)
- [composeファイルリファレンス](https://docs.docker.com/compose/compose-file/)
- [コマンドリファレンス](https://docs.docker.com/compose/reference/)

## Build the project

```sh
$ docker-compose up -d
```

## Wordpressを表示

ブラウザで`http://localhost:8000`を開く

## Start and Stop

```sh
# コンテナを起動する
$ docker-compose start db wordpress

# コンテナを終了する
$ docker-compose stop
```

## Shutdown and cleanup

コンテナとネットワークを削除

```sh
$ docker-compose down
```

Wordpress databaseも合わせて削除

```sh
$ docker-compose down --volumes
```
