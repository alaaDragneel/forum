<?php

namespace Tests;

use App\Exceptions\Handler;
use DB;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;


abstract class TestCase extends BaseTestCase
{

    use CreatesApplication;

    protected $oldExceptionHandler;

    protected function setUp ()
    {
        parent::setUp();

        // DB::statement('SET FOREIGN_KEY_CHECKS = 1'); // for mysql

        DB::statement('PRAGMA foreign_keys=on;'); // for sqlite

        $this->disableExceptionHandling();
    }

    /**
     * @param null $user
     * @return mixed
     */
    protected function signIn ($user = null)
    {
        $user = $user ?: create('App\User');
        $this->actingAs($user);

        return $this;
    }

    // Hat tip, @adamwathan.
    protected function disableExceptionHandling ()
    {
        $this->oldExceptionHandler = $this->app->make(ExceptionHandler::class);
        $this->app->instance(ExceptionHandler::class, new class extends Handler
        {

            public function __construct () { }

            public function report (\Exception $e) { }

            public function render ($request, \Exception $e)
            {
                throw $e;
            }
        });
    }

    protected function withExceptionHandling ()
    {
        $this->app->instance(ExceptionHandler::class, $this->oldExceptionHandler);

        return $this;
    }
}
