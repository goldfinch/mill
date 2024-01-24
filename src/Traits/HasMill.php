<?php

namespace Goldfinch\Mill\Traits;

use Goldfinch\Mill\Mill;

trait HasMill
{
    public static function mill($count = null)
    {
        $factory = Mill::millForModel(get_called_class());

        return $factory->count(is_numeric($count) ? $count : null);
    }
}
