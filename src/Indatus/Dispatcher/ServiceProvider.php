<?php namespace Indatus\Dispatcher;

/**
 * This file is part of Dispatcher
 *
 * (c) Ben Kuhl <bkuhl@indatus.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use App;
use Config;
use Illuminate\Contracts\Support\DeferrableProvider;

class ServiceProvider extends \Illuminate\Support\ServiceProvider implements DeferrableProvider
{

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        //$this->package('indatus/dispatcher');

        $this->publishes([
            __DIR__.'/../../config/config.php' => config_path('dispatcher.php'),
        ]);
    }


    /**
     * Register the service provider.
     *
     * @codeCoverageIgnore
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/config.php', 'dispatcher');

        /** @var \Indatus\Dispatcher\ConfigResolver $resolver */
        $resolver = App::make('\Indatus\Dispatcher\ConfigResolver');

        //load the scheduler of the appropriate driver
        App::bind('Indatus\Dispatcher\Scheduling\Schedulable', function () use ($resolver) {
            return $resolver->resolveSchedulerClass();
        });

        //load the schedule service of the appropriate driver
        App::bind('Indatus\Dispatcher\Services\ScheduleService', function () use ($resolver) {
            return $resolver->resolveServiceClass();
        });

        App::bind('Indatus\Dispatcher\OptionReader', function () use ($resolver) {
            return $resolver->resolveSchedulerClass();
        });

        App::bind('Indatus\Dispatcher\Drivers\Cron\Scheduler', function () use ($resolver) {
            return $resolver->resolveSchedulerClass();
        });

        $this->registerCommands();
    }


    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'command.scheduled.summary',
            'command.scheduled.make',
            'command.scheduled.run'
        ];
    }


    /**
     * Register artisan commands
     * @codeCoverageIgnore
     */
    private function registerCommands()
    {
        //scheduled:summary
        $this->app->bind('command.scheduled.summary', function ($app) {
            return App::make('Indatus\Dispatcher\Commands\ScheduleSummary');
        });
        $this->commands('command.scheduled.summary');

        //scheduled:make
        $this->app->bind('command.scheduled.make', function ($app) {
            return App::make('Indatus\Dispatcher\Commands\Make');
        });
        $this->commands('command.scheduled.make');

        //scheduled:run
        $this->app->bind('command.scheduled.run', function ($app) {
            return App::make('Indatus\Dispatcher\Commands\Run');
        });
        $this->commands('command.scheduled.run');
    }

}
