<?php
namespace Epigra\TcKimlik\Laravel;

use Epigra\TcKimlik\TcKimlik;
use \Illuminate\Support\ServiceProvider;

class TcKimlikServiceProvider extends ServiceProvider
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

        $container = \Illuminate\Container\Container::getInstance();

        $langs = realpath(__DIR__.'/resources/lang');

        //set the package namespace into translator
        $container['translator']->addNamespace('tckimlik', $langs);

        $this->publishes([
            $langs => resource_path('lang/vendor/tckimlik'),
        ]);

        //extending the validator
        $container['validator']->extend('tckimlik', function($attribute, $value, $parameters, $validator) {
            return TCKimlik::verify($value);
        });
        //replacing the message
        $container['validator']->replacer('tckimlik', function($message, $attribute, $rule, $parameters) {

            if($message === "validation.tckimlik") {
                return trans('tckimlik::tckimlik-validation.message');
            }
            return "Geçerli TC Kimlik numarası değil"; // default unless file is n found, {not a valid tc identity number}
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {

        $container = \Illuminate\Container\Container::getInstance();
        $container->singleton('tckimlik', 'Epigra\TcKimlik\TcKimlik');


        $loader = \Illuminate\Foundation\AliasLoader::getInstance();
        $loader->alias('TcKimlik', "Epigra\TcKimlik\Laravel\Facade\TcKimlik");
    }

    /**
     * {@inheritDoc}
     */
    public function provides()
    {
        return ['tckimlik'];
    }
}
