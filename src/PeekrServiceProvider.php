<?php

    namespace Fundamental\Peekr\Providers;

    use Illuminate\Support\Facades\Blade;
    use Illuminate\Support\ServiceProvider;

    class PeekrServiceProvider
    {
        public function register()
        {

        }

        public function boot()
        {
            Blade::directive('peekr', function ($url) {
                return "<?php new Fundamental\Peekr\Peekr($url)->peek(); ?>";
            });
        }
    }