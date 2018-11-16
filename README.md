# Timber

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]
[![Build Status][ico-travis]][link-travis]

A Laravel 5+ wrapper for the [Timber Logger](https://timber.io/) service. Use it to log HTTP requests or custom events to Timber.

## Installation

**1.** Require the package via Composer
``` bash
$ composer require rebing/timber
```

**2.** Laravel 5.5+ will autodiscover the package, for older versions add the
following service provider
```php
Rebing\Timber\TimberServiceProvider::class,
```

and alias
```php
'Timber' => 'Rebing\Timber\Support\Facades\Timber',
```

in your `config/app.php` file.

**3.** Publish the configuration file
```bash
$ php artisan vendor:publish --provider="Rebing\Timber\TimberServiceProvider"
```

**4.** Review the configuration file
```
config/graphql.php
```
and add your Timber API key to `.env`

## Usage

### HTTP Requests

To log HTTP requests use the `Rebing\Timber\Middleware\LogRequest::class` middleware. 
This will log all incoming requests and responses, including context and Auth data.

For example, you can add it to `Kernel.php`:

```php
class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     */
    protected $middleware = [
        Rebing\Timber\Middleware\LogRequest::class,
    ];
}
```

### Custom Events

You can also log custom data. Context will be added automatically.
```php
use Rebing\Timber\Requests\Events\CustomEvent;

$data = [
    'some' => 'data',
];

$customEvent = new CustomEvent('Log message', 'custom', $data);
dispatch($customEvent);
// Or $customEvent->send();
```

## Change log

Please see the [changelog](changelog.md) for more information on what has changed recently.

## Testing

``` bash
$ phpunit
```

## Contributing

Please see [contributing.md](contributing.md) for details and a todolist.

## Security

If you discover any security related issues, please email mikk.nurges@rebing.ee instead of using the issue tracker.

## Credits

- [Mikk Mihkel Nurges][link-author]
- [All Contributors][link-contributors]

## License

MIT. Please see the [license file](license.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/rebing/timber.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/rebing/timber.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/rebing/timber/master.svg?style=flat-square
[ico-styleci]: https://styleci.io/repos/12345678/shield

[link-packagist]: https://packagist.org/packages/rebing/timber-laravel
[link-downloads]: https://packagist.org/packages/rebing/timber-laravel
[link-travis]: https://travis-ci.org/rebing/timber
[link-styleci]: https://styleci.io/repos/12345678
[link-author]: https://github.com/rebing
[link-contributors]: ../../contributors]