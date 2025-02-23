<?php

declare(strict_types=1);

/*
 * (c) INSPIRED MINDS
 */

namespace InspiredMinds\ContaoBackendFormsBundle\EventListener;

use Contao\CoreBundle\DependencyInjection\Attribute\AsHook;
use Contao\StringUtil;
use Contao\Widget;
use Symfony\Contracts\Service\ResetInterface;

#[AsHook('parseWidget')]
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
