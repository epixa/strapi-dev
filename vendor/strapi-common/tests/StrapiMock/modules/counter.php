<?php

$counter = 0;
return function() use (&$counter)
{
    return ++$counter;
};
