[![](https://user-images.githubusercontent.com/60096509/91668964-54ecd500-eb11-11ea-9c35-e8f0b20b277a.png)](https://sandwave.io)

# EPP Client (PHP)

[![Codecov](https://codecov.io/gh/sandwave-io/epp-client-php/branch/master/graph/badge.svg?token=CWWIFWRKZC)](https://codecov.io/gh/sandwave-io/epp-client-php)
[![GitHub Workflow Status](https://img.shields.io/github/workflow/status/sandwave-io/epp-client-php/CI)](https://github.com/sandwave-io/epp-client-php/actions)
[![Packagist PHP Version Support](https://img.shields.io/packagist/php-v/sandwave-io/epp-client-php)](https://packagist.org/packages/sandwave-io/epp-client-php)
[![Packagist PHP Version Support](https://img.shields.io/packagist/v/sandwave-io/epp-client-php)](https://packagist.org/packages/sandwave-io/epp-client-php)
[![Packagist Downloads](https://img.shields.io/packagist/dt/sandwave-io/epp-client-php)](https://packagist.org/packages/sandwave-io/epp-client-php)

## Support

This client implements several registries using their EPP API. The base implementation complies with the following RFCs:

* [RFC 5730](https://tools.ietf.org/html/rfc5730)
* [RFC 5731](https://tools.ietf.org/html/rfc5731)
* [RFC 5733](https://tools.ietf.org/html/rfc5733)

The following registries are supported:

* [**SIDN**](https://sidn.nl) (.nl)

Are you missing functionality? Feel free to create an issue, or hit us up with a pull request.

## How to use

```bash
composer require sandwave-io/epp-client-php
```

## How to contribute

Feel free to create a PR if you have any ideas for improvements. Or create an issue.

* When adding code, make sure to add tests for it (phpunit).
* Make sure the code adheres to our coding standards (use php-cs-fixer to check/fix). 
* Also make sure PHPStan does not find any bugs.

```bash

vendor/bin/php-cs-fixer fix

vendor/bin/phpstan analyze

vendor/bin/phpunit --coverage-text

```

These tools will also run in GitHub actions on PR's and pushes on master.
