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
        $type = $arrDca['inputType'];

        $arrDca['eval']['template'] = $arrDca['eval']['template'] ?? 'form_' . $type . '_backend';

        if ('submit' === $type) {
            $arrDca['eval']['class'] = $arrDca['eval']['class'] ?? 'tl_submit';
        }

        return parent::addFormField($strName, $arrDca, $position);
    }
}
