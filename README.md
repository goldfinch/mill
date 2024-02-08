
# 🦅 Mill for Silverstripe

[![Silverstripe Version](https://img.shields.io/badge/Silverstripe-5.1-005ae1.svg?labelColor=white&logoColor=ffffff&logo=data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAxMDEuMDkxIDU4LjU1NSIgZmlsbD0iIzAwNWFlMSIgeG1sbnM6dj0iaHR0cHM6Ly92ZWN0YS5pby9uYW5vIj48cGF0aCBkPSJNNTAuMDE1IDUuODU4bC0yMS4yODMgMTQuOWE2LjUgNi41IDAgMCAwIDcuNDQ4IDEwLjY1NGwyMS4yODMtMTQuOWM4LjgxMy02LjE3IDIwLjk2LTQuMDI4IDI3LjEzIDQuNzg2czQuMDI4IDIwLjk2LTQuNzg1IDI3LjEzbC02LjY5MSA0LjY3NmM1LjU0MiA5LjQxOCAxOC4wNzggNS40NTUgMjMuNzczLTQuNjU0QTMyLjQ3IDMyLjQ3IDAgMCAwIDUwLjAxNSA1Ljg2MnptMS4wNTggNDYuODI3bDIxLjI4NC0xNC45YTYuNSA2LjUgMCAxIDAtNy40NDktMTAuNjUzTDQzLjYyMyA0Mi4wMjhjLTguODEzIDYuMTctMjAuOTU5IDQuMDI5LTI3LjEyOS00Ljc4NHMtNC4wMjktMjAuOTU5IDQuNzg0LTI3LjEyOWw2LjY5MS00LjY3NkMyMi40My0zLjk3NiA5Ljg5NC0uMDEzIDQuMTk4IDEwLjA5NmEzMi40NyAzMi40NyAwIDAgMCA0Ni44NzUgNDIuNTkyeiIvPjwvc3ZnPg==)](https://packagist.org/packages/goldfinch/mill)
[![Package Version](https://img.shields.io/packagist/v/goldfinch/mill.svg?labelColor=333&color=F8C630&label=Version)](https://packagist.org/packages/goldfinch/mill)
[![Total Downloads](https://img.shields.io/packagist/dt/goldfinch/mill.svg?labelColor=333&color=F8C630&label=Downloads)](https://packagist.org/packages/goldfinch/mill)
[![License](https://img.shields.io/packagist/l/goldfinch/mill.svg?labelColor=333&color=F8C630&label=License)](https://packagist.org/packages/goldfinch/mill) 

**Mill** is a factory component 🏗️ that helps you to generate fake records for Silverstripe whether to test your application or handle some automation.

If you got tired of constantly adding test content and placeholders to test your application and its components, you can forget about it, **Mill** will handle it for you.

**Mill** is using [FakerPHP](https://fakerphp.github.io/) as a fake content supplier. To learn more about available formatters you can use in your own mills, please refer to [the full list of available formatters](https://fakerphp.github.io/formatters/).

## Install

#### 1. Install module
```
composer require goldfinch/mill
```

#### 2. Create new mill

Use [**Taz**](https://github.com/goldfinch/taz)🌪️ to generate your Mill. It will quickly lead you through the setup and take care of it for you.

```bash
php taz make:mill
```
*example:*
```bash
php taz make:mill Article
# What [class name] this mill is going to work with? : App\Models\Article
```

If you haven't used [**Taz**](https://github.com/goldfinch/taz)🌪️ before, *taz* file must be in your root project folder to be able to use the command above. Just copy it first:

```bash
cp vendor/goldfinch/taz/taz taz
```

#### 3. Prepare your mill

Modify further the recently created mill by **Taz** and prepare suitable fake data for its fields.

*example:*
```php
namespace App\Mills;

use Goldfinch\Mill\Mill;

class ArticleMill extends Mill
{
    public function factory(): array
    {
        return [
            'Title' => $this->faker->catchPhrase(),
            'Summary' => $this->faker->sentence(200),
            'Content' => $this->faker->paragraph(10),
            'Date' => $this->faker->dateTimeBetween('-8 week')->format('Y-m-d H:i:s'),
            'Publisher' => $this->faker->name(),
            'Email' => $this->faker->email(),
            'Phone' => $this->faker->e164PhoneNumber(),
            'Address' => $this->faker->address(),
            'Country' => $this->faker->country(),
        ];
    }
}
```

#### 4. Make your model millable

Lastly, you need to add `Millable` trait to the model this mill is going to work with:

*example:*
```php
namespace App\Models;

use Goldfinch\Mill\Traits\Millable;

class Article
{
    use Millable;

    // ..
}
```

## Usage

> Generate 10 articles:
```php
App\Models\Article::mill(10)->make();
```

> Generate one article, overwriting some of its fields:
```php
App\Models\Article::mill(1)->make([
    'Title' => 'Custom article title',
    'Content' => 'Custom text',
]);
```

> Generate 10 articles and add random categories for each (mapping):
```php
App\Models\Article::mill(10)->make()->each(function($item) {
    $categories = App\Models\ArticleCategory::get()->shuffle()->limit(rand(0,4));

    foreach ($categories as $category) {
        $item->Categories()->add($category);
    }
});
```

## Recommendation
This module plays nicely with harvest seeder [goldfinch/harvest](https://github.com/goldfinch/harvest)

## License

The MIT License (MIT)
