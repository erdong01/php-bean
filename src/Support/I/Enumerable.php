<?php

namespace Marstm\Support\I;

use Countable;
use IteratorAggregate;
use JsonSerializable;

/**
 * Interface Enumerable
 * @package Marstm\Support\I
 */
interface Enumerable extends Arrayable, Countable, IteratorAggregate, JsonSerializable
{

}