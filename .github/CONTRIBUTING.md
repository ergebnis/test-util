# CONTRIBUTING

We're using [Travis CI](https://travis-ci.org) as a continuous integration system.

For details, see [`.travis.yml`](../.travis.yml).

## Tests

We're using [`phpunit/phpunit`](https://github.com/sebastianbergmann/phpunit) to drive the development.

Run

```
$ make test
```

to run all the tests.

## Coding Standards

We are using [`friendsofphp/php-cs-fixer`](https://github.com/FriendsOfPHP/PHP-CS-Fixer) to enforce coding standards.

Run

```
$ make cs
```

to automatically fix coding standard violations.

## Mutation Testing

We are using [`infection/infection`](https://github.com/infection/infection) to ensure a minimum quality of the tests.

Enable `xdebug` and run

```
$ make infection
```

to run mutation tests.

## Extra lazy?

Run

```
$ make
```

to run both coding standards check and tests!
