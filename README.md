# hs-scan-directory

Script to scan directories made symfony4 console command.

# Usage

```shell
$ composer install
```

Display file list in target directory(Including subdirectories).

```shell
$ php console.php hs:scan:list {{target_directory}}
```

Display number of files in target directory(Including subdirectories).

```shell
$ php console.php hs:scan:count {{target_directory}}
```

Display updated time of files in target directory(Including subdirectories).

```shell
$ php console.php hs:scan:update {{target_directory}}
```

# Exclude the directory

```sql
$ php console.php hs:scan:list {{target_directory}} -e {{excluded_directories}}.yml
```

## Sample

```shell
$ php console.php hs:scan:list vendor -e exclusion.yml
````

```yml
# exclusion.yml
- "vendor/bin"
- "vendor/doctrine"
- "vendor/composer"
- "vendor/symfony"
```
