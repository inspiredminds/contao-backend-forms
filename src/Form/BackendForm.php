<?php

namespace InspiredMinds\ContaoBackendFormsBundle\Form;

use Haste\Util\ArrayPosition;

class BackendForm extends \Haste\Form\Form
{
    public function generate($templateName = null)
    {
        return parent::generate($templateName ?? 'form_backend');
    }

    public function addFormField($strName, array $arrDca, ArrayPosition $position = null)
    {
        self::adjustDcaForBackend($arrDca);

        if (!is_array($arrDca['label'])) {
            $arrDca['label'] = [$arrDca['label']];
        }

        return parent::addFormField($strName, $arrDca, $position);
    }

    public function addFieldFromFormGenerator($strName, array $arrDca, ArrayPosition $position = null)
    {
        self::adjustDcaForBackend($arrDca);

        parent::addFieldFromFormGenerator($strName, $arrDca, $position);
    }

    protected static function adjustDcaForBackend(array &$arrDca): void
    {
        $type = $arrDca['inputType'] ?: $arrDca['type'];
        $class = $GLOBALS['TL_FFL'][$type];

        /** @var \Contao\Widget $widget */
        $widget = new $class;

        $arrDca['eval']['template'] = $arrDca['eval']['template'] ?? $widget->template.'_backend';
        $arrDca['template'] = $arrDca['template'] ?? $widget->template.'_backend';

        if ('submit' === $type) {
            $arrDca['eval']['class'] = $arrDca['eval']['class'] ?? 'tl_submit';
        }
    }
}
