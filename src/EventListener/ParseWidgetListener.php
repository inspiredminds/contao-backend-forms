<?php

declare(strict_types=1);

/*
 * This file is part of the ContaoBackendFormsBundle.
 *
 * (c) inspiredminds
 *
 * @license LGPL-3.0-or-later
 */

namespace InspiredMinds\ContaoBackendFormsBundle\EventListener;

use Contao\CoreBundle\ServiceAnnotation\Hook;
use Contao\StringUtil;
use Contao\Widget;
use Symfony\Contracts\Service\ResetInterface;

/** 
 * @Hook("parseWidget")
 */
class ParseWidgetListener implements ResetInterface
{
    private $isBackendForm = false;

    public function __invoke(string $buffer, Widget $widget): string
    {
        if ('html' === $widget->type && $this->isBackendForm) {
            $buffer = StringUtil::decodeEntities($buffer);
        }

        return $buffer;
    }

    public function setIsBackendForm(bool $isBackendForm): self
    {
        $this->isBackendForm = $isBackendForm;

        return $this;
    }

    public function reset(): void
    {
        $this->isBackendForm = false;
    }
}
