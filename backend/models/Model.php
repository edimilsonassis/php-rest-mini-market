<?php

namespace models;

class Model
{
    public function toArray()
    {
        return get_object_vars($this);
    }
}