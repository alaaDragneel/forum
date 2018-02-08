<?php

function create($class, $attributies = [])
{
    return factory($class)->create($attributies);
}

function make($class, $attributies = [])
{
    return factory($class)->make($attributies);
}
