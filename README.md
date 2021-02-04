# Laravel Peekr

Made with love and code by [Fundamental Studio Ltd.](https://www.fundamental.bg)

## Installation

The package is compatible with Laravel 7+ version.

Via composer:
``` bash
$ composer require fundamental-studio/laravel-peekr
```

After installing, the package should be auto-discovered by Laravel.
In order to configurate the package, you need to publish the config file using this command:
``` bash
$ php artisan vendor:publish --provider="Fundamental\Peekr\PeekrServiceProvider"
```

You are up & running and ready to go.

## Documentation and Usage instructions

The usage of our package is pretty seamless and easy.
First of all, you need to use the proper namespace for our package:
```
use Fundamental\Peekr\Peekr;

new Peekr('https://mysupercoolurl.com')->peek();
```

That's it.

We have also delivered an Blade directive to use faster within the views:

```blade
@peekr('https://mysupercoolurl.com')
```

## Changelog
All changes are available in our Changelog file.

## Support
For any further questions, feature requests, problems, ideas, etc. you can create an issue tracker or drop us a line at support@fundamental.bg

## Contributing
Read the Contribution file for further information.

## Credits

- Konstantin Rachev
- Vanya Ananieva

The package is bundled and contributed to the community by Fundamental Studio Ltd.'s team.

## Issues
If you discover any issues, please use the issue tracker.

## Security
If your discover any security-related issues, please email konstantin@fundamental.bg or support@fundamental.bg instead of using the issue tracker.

## License
The MIT License(MIT). See License file for further information and reading.