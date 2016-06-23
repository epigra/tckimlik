<?php
namespace Epigra\TCKimlik\Laravel;

use Epigra\TCKimlik\TCKimlik;
use \Illuminate\Support\ServiceProvider;

class TCKimlikServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;


    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {


        // $this->app['validator']->extend('tckimlik', function($attribute, $value, $parameters, $validator) {
        //     return TCKimlik::verify($value);
        // });

        // $this->app['validator']->replacer('tckimlik', function($message, $attribute, $rule, $parameters) {
        //     if($message=="validation.tckimlik") return "Belirtilen T.C. Kimlik Numarası doğrulanamadı.";
        //     return $message;
        // });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->alias('TCKimlik', 'Epigra\TCKimlik\TCKimlik');
    }

    /**
     * {@inheritDoc}
     */
    public function provides()
    {
        return ['tckimlik'];
    }

}
