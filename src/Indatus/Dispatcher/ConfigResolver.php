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

class ConfigResolver
{

    /**
     * Resolve a class based on the driver configuration
     *
     * @return \Indatus\Dispatcher\Scheduling\Schedulable
     */
    public function resolveSchedulerClass()
    {
        return App::make(
            'Indatus\Dispatcher\Drivers\\'.$this->getDriver().'\\Scheduler', array(
                $this
            )
        );

        /*try {
            return App::make(
                config('dispatche.driver').'\\Scheduler', array(
                    $this
                )
            );
        } catch (\ReflectionException $e) {
            return App::make(
                'Indatus\Dispatcher\Drivers\\'.$this->getDriver().'\\Scheduler', array(
                    $this
                )
            );
        }*/
    }

    /**
     * Resolve a class based on the driver configuration
     *
     * @return \Indatus\Dispatcher\Scheduling\ScheduleService
     */
    public function resolveServiceClass()
    {
        return App::make('Indatus\Dispatcher\Drivers\\'.$this->getDriver().'\\ScheduleService');

        //try {
        //    return App::make(config('dispatcher.driver').'\\ScheduleService');
        //} catch (\ReflectionException $e) {
        //    return App::make('Indatus\Dispatcher\Drivers\\'.$this->getDriver().'\\ScheduleService');
        //}
    }

    /**
     * Get the dispatcher driver class
     *
     * @return string
     */
    public function getDriver()
    {
        return ucwords(strtolower(config('dispatcher.driver')));
    }

}
