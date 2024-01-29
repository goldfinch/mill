<?php

namespace Goldfinch\Mill;

use Exception;
use Faker\Factory;
use SilverStripe\ORM\ArrayList;
use SilverStripe\Core\Config\Config;
use SilverStripe\Versioned\Versioned;
use SilverStripe\Versioned\RecursivePublishable;

abstract class Mill
{
    protected $dataObject;

    protected static $model;

    protected $count;

    protected $faker;

    public static function millForModel(string $modelName)
    {
        $cfg = Config::inst()->get(Mill::class, 'millable');

        $k = array_search($modelName, array_values($cfg));

        if ($k !== false && array_keys($cfg)[$k]) {
            $mill = array_keys($cfg)[$k];
        } else {
            throw new Exception('The mill for ' . $modelName . ' is not found');
        }

        $cfg = Config::inst()->get(get_class());

        if (isset($cfg['millable']) && is_array($cfg['millable']) && isset($cfg['millable'][$mill])) {
            $target = $cfg['millable'][$mill];

            return new $mill($target);
        }
    }

    public static function new()
    {
        return (new static);
    }

    public function __construct($dataObject, $count = null)
    {
        $this->dataObject = $dataObject;
        $this->count = $count;
        $this->faker = $this->initFaker();
    }

    protected function initFaker()
    {
        return Factory::create();
    }

    public function count(?int $count)
    {
        return $this->newInstance(['count' => $count]);
    }

    protected function newInstance(array $arguments = [])
    {
        return new static(...array_values(array_merge([
            'dataObject' => $this->dataObject,
            'count' => $this->count,
        ], $arguments)));
    }

    public function make($attributes = [], ?DataObject $parent = null)
    {
        $list = new ArrayList;

        for ($i = 1; $i <= $this->count; $i++) {
            $record = $this->dataObject::create($this->factory());
            if ($attributes && count($attributes)) {
                $record->update($attributes);
            }
            $record->write();

            if ($record->hasExtension(RecursivePublishable::class) && $record->hasExtension(Versioned::class)) {
                $record->publishRecursive();
            }
            $list->push($record);
        }

        return $list;
    }
}
