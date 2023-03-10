<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\TestResponse;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, TestHelpers, DetectRepeatedQueries;
    protected $defaultData = [];

    public function setUp()
    {
        parent::setUp();
        $this->withoutExceptionHandling();

        $this->addTestResponseMacros();

        $this->enableQueryLog();

    }
    public function tearDown()
    {
        $this->flushQueryLog();
        parent::tearDown();
    }

    public function addTestResponseMacros(): void
    {
        TestResponse::macro('viewData', function ($key) {
            $this->ensureResponseHasView();

            $this->assertViewHas($key);

            return $this->original->$key;
        });


        TestResponse::macro('assertViewCollection', function ($var) {
            return new TestCollectionData($this->viewData($var));
        });
    }

}
