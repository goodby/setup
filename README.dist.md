# PackageName

[![Build Status](https://secure.travis-ci.org/goodby/setup.png?branch=master)](https://travis-ci.org/goodby/setup)

## What is PackageName?

editing...

## Requirements

editing...

## Installation

Install composer in your project:

```
curl -s http://getcomposer.org/installer | php
```

Create a `composer.json` file in your project root:

```json
{
    "require": {
        "goodby/setup": "*"
    }
}
```

Install via composer:

```
php composer.phar install
```

## License

PackageName is open-sourced software licensed under the MIT License - see the LICENSE file for details

## Documentation

editing...


## Contributing

We works under test driven development.

Checkout master source code from github:

```
hub clone goodby/setup
```

Install components via composer:

```
# If you don't have composer.phar
./scripts/bundle-devtools.sh .

# If you have composer.phar
composer.phar install --dev
```

Run phpunit:

```
./vendor/bin/phpunit
```

## Acknowledgement

editing...