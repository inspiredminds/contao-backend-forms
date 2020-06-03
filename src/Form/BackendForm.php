<?php

declare(strict_types=1);

/*
 * This file is part of the ContaoBackendFormsBundle.
 *
 * (c) inspiredminds
 *
 * @license LGPL-3.0-or-later
 */

namespace InspiredMinds\ContaoBackendFormsBundle\Form;

use Contao\FrontendTemplate;
use Contao\System;
use Contao\TemplateLoader;
use Haste\Util\ArrayPosition;
use InspiredMinds\ContaoBackendFormsBundle\EventListener\ParseWidgetListener;

class BackendForm extends \Haste\Form\Form
{
    protected $legend;

    public function generate($templateName = null)
    {
        if (null === $templateName) {
            $templateName = 'form_backend';

            try {
                TemplateLoader::getPath($templateName, 'html5');
            } catch (\Exception $e) {
                $templateName = 'form_wrapper';
            }
        }

        $template = new FrontendTemplate($templateName);
        $template->legend = $this->legend;
        $template->class = 'hasteform_'.$this->getFormId();
        $template->formSubmit = $this->getFormId();

        /** @var ParseWidgetListener $parseWidgetListener */
        $parseWidgetListener = System::getContainer()->get(ParseWidgetListener::class);
        $parseWidgetListener->setIsBackendForm(true);

        $this->addToTemplate($template);

        $parseWidgetListener->reset();

        return $template->parse();
    }

    public function addFormField($strName, array $arrDca, ArrayPosition $position = null)
    {
        self::adjustDcaForBackend($arrDca);

        if (!\is_array($arrDca['label'])) {
            $arrDca['label'] = [$arrDca['label']];
        }

        return parent::addFormField($strName, $arrDca, $position);
    }

    public function addFieldFromFormGenerator($strName, array $arrDca, ArrayPosition $position = null): void
    {
        self::adjustDcaForBackend($arrDca);

        parent::addFieldFromFormGenerator($strName, $arrDca, $position);
    }

    public function addToObject($objObject)
    {
        parent::addToObject($objObject);

        foreach ($objObject->visibleWidgets as $objWidget) {
            /** @var \Contao\Widget $objWidget */
            if ('submit' !== $objWidget->type) {
                $objObject->editFields .= $objWidget->parse();
            } else {
                $objObject->submitFields .= $objWidget->parse();
            }
        }

        return $this;
    }

    public function setLegend(string $legend): self
    {
        $this->legend = $legend;

        return $this;
    }

    protected static function adjustDcaForBackend(array &$arrDca): void
    {
        $type = $arrDca['inputType'] ?: $arrDca['type'];
        $class = $GLOBALS['TL_FFL'][$type];

        /** @var \Contao\Widget $widget */
        $widget = new $class();

        $arrDca['eval']['template'] = $arrDca['eval']['template'] ?? $widget->template.'_backend';
        $arrDca['template'] = $arrDca['template'] ?? $widget->template.'_backend';

        if ('submit' === $type) {
            $arrDca['eval']['class'] = $arrDca['eval']['class'] ?? 'tl_submit';
        }
    }
}
