<?php

declare(strict_types=1);

/*
 * (c) INSPIRED MINDS
 */

namespace InspiredMinds\ContaoBackendFormsBundle\Form;

use Codefog\HasteBundle\Form\Form;
use Codefog\HasteBundle\Util\ArrayPosition;
use Contao\CoreBundle\Csrf\ContaoCsrfTokenManager;
use Contao\FrontendTemplate;
use Contao\System;
use Contao\TemplateLoader;
use Contao\Widget;
use InspiredMinds\ContaoBackendFormsBundle\EventListener\ParseWidgetListener;

class BackendForm extends Form
{
    protected $legend;

    public function generate(string|null $templateName = null): string
    {
        if (null === $templateName) {
            $templateName = 'form_backend';

            try {
                TemplateLoader::getPath($templateName, 'html5');
            } catch (\Exception) {
                $templateName = 'form_wrapper';
            }
        }

        $container = System::getContainer();
        /** @var ContaoCsrfTokenManager $tokenManager */
        $tokenManager = $container->get('contao.csrf.token_manager');
        $tokenName = $container->getParameter('contao.csrf_token_name');

        $template = new FrontendTemplate($templateName);
        $template->legend = $this->legend;
        $template->class = 'hasteform_'.$this->getFormId();
        $template->formSubmit = $this->getFormId();
        $template->requestToken = $tokenManager->getToken($tokenName)->getValue();

        /** @var ParseWidgetListener $parseWidgetListener */
        $parseWidgetListener = $container->get(ParseWidgetListener::class);
        $parseWidgetListener->setIsBackendForm(true);

        $this->addToObject($template);

        $parseWidgetListener->reset();

        return $template->parse();
    }

    public function addFormField(string $fieldName, array $fieldConfig, ArrayPosition|null $position = null): self
    {
        self::adjustDcaForBackend($fieldConfig);

        if (!\is_array($fieldConfig['label'])) {
            $fieldConfig['label'] = [$fieldConfig['label']];
        }

        return parent::addFormField($fieldName, $fieldConfig, $position);
    }

    public function addFieldFromFormGenerator(string $fieldName, array $fieldConfig, ArrayPosition|null $position = null): self
    {
        self::adjustDcaForBackend($fieldConfig);

        return parent::addFieldFromFormGenerator($fieldName, $fieldConfig, $position);
    }

    public function addToObject(object $objObject): self
    {
        parent::addToObject($objObject);

        foreach ($objObject->visibleWidgets as $objWidget) {
            /** @var Widget $objWidget */
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

        /** @var Widget $widget */
        $widget = new $class();

        $arrDca['eval']['template'] ??= $widget->template.'_backend';
        $arrDca['template'] ??= $widget->template.'_backend';

        if ('submit' === $type) {
            $arrDca['eval']['class'] ??= 'tl_submit';
        }
    }
}
