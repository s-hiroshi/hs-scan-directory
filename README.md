# scan-directory

サブディレクトリを含めて、ディレクトリを走査してファイルをリストアップします。

# インストール

```shell
$ composer install
```

# 使い方

## ヘルプ

```shell
$ php console.php help scan
```


## （サブディレクトリも含めた）ディレクトリのファイル一覧を出力

```shell
$ php console.php scan ./vendor
```

上記は、プロジェクトルートにある`vendor`ディレクトリをに含まれるファイルを出力します。

ディレクトリは、`console.php`からの相対パスで指定します。
パスの先頭は`./`で開始してください。

```shell
$ php console.php scan ./vendor  # OK
$ php console.php scan vendor    # NG
```

## ファイルの更新日をチェック

走査ディレクトリの更新日から指定した間隔を超える更新日を持つファイルを出力します（間隔は間隔指示子で指定：例 2時間 PT2H）。

```shell
php console.php scan ./vendor -i 'P1Y'
```

## Option

eオプションを使って、Yamlファイルに指定したディレクトリを走査対象から除外できます。

```shell
$ php console.php scan ./sample -e exclusion.yaml
```

```yaml
- "./vendor/bin"
- "./vendor/doctrine"
- "./vendor/composer"
- "./vendor/webmozart"
```
