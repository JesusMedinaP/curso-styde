<?php

namespace Tests;

trait TestHelpers
{
    public function withData(array $custom=[])
    {
        return array_filter(array_merge($this->defaultData(), $custom));
    }

    public function defaultData()
    {
        return $this->defaultData;
    }
}