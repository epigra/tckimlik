<?php
namespace Epigra\TcKimlik\Laravel;

use Illuminate\Support\ServiceProvider;

class TCKimlikServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;


    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app['validator']->extend('tckimlik', function($attribute, $value, $parameters, $validator) {
            return TcKimlik::verify($value);
        });

        $this->app['validator']->replacer('tckimlik', function($message, $attribute, $rule, $parameters) {
            if($message=="validation.tckimlik") return "Belirtilen T.C. Kimlik Numarası doğrulanamadı.";
            return $message;
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

}
