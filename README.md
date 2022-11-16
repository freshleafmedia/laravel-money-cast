# Laravel Money Cast

A Money cast for Laravel models


## Overview

This library provides a Laravel Attribute Cast which serialises [Money](https://github.com/moneyphp/money) instances into strings suitable for database storage.


## Installation

```
composer require freshleafmedia/laravel-money-cast
```

## Usage

```php
use Freshleafmedia\MoneyCast\MoneyCast;
use Illuminate\Database\Eloquent\Model;

class MyModel extends Model
{
    // ...

    protected $casts = [
        'cost' => MoneyCast::class,
    ];
    
    // ...
}
```

### Saving

```php
$model = new MyModel();
$model->cost = new \Money\Money('100', new \Money\Currency('GBP'));
$model->save(); // 'GBP100' is persisted to the database.
```

### Retrieving

```php
$cost = MyModel::first()->cost; // Money\Money

$cost->getAmount() // '100'
$cost->getCurrency()->getCode() // 'GBP'
```

## Decimal Amounts

Note that due to the way moneyphp/money works amounts are in the smallest unit.
For example `GBP100` is £1.00 and `USD100` is $1.00.

See the [Formatting section](https://www.moneyphp.org/en/stable/features/formatting.html) of the moneyphp docs for details

## Tests

Unit tests can be run via `composer test`


## License

See [LICENSE](LICENSE)
