<?php

namespace App\Events;
class PriceChanged
{
public function __construct(
public int $advertisementId,
public array $data
) {}
}
