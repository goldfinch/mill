<?php

use Faker\Factory;

if (! function_exists('fake') && class_exists(Factory::class)) {

    function fake()
    {
        return Factory::create();
    }
}
