<?php

declare(strict_types=1);

/*
 * (c) INSPIRED MINDS
 */

namespace InspiredMinds\ContaoBackendFormsBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class ContaoBackendFormsBundle extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
