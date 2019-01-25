# can-directory

サブディレクトリを含めて、ディレクトリを走査してファイルをリストアップします。

# インストール

```shell
$ composer install
```

# 使い方

```shell
$ php console.php scan ./vendor
```

上記は、プロジェクトルートにある`vendor`ディレクトリを走査します。
ディレクトリは、`console.php`からの相対パスで指定します。
パスの先頭は`./`で開始してください。

```shell
$ php console.php scan ./sample  # OK
$ php console.php scan sample    # NG
```

# Option

eオプションを使ってYamlファイルに指定したファイルを走査から除外できます。

```shell
$ php console.php scan ./sample -e exclusion.yaml
```

```yaml
- "./vendor/bin"
- "./vendor/doctrine"
- "./vendor/composer"
- "./vendor/webmozart"
```
