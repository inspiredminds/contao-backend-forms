[![](https://img.shields.io/packagist/v/inspiredminds/contao-backend-forms.svg)](https://packagist.org/packages/inspiredminds/contao-backend-forms)
[![](https://img.shields.io/packagist/dt/inspiredminds/contao-backend-forms.svg)](https://packagist.org/packages/inspiredminds/contao-backend-forms)

Contao Backend Forms
=====================

Extension of [`Codefog\HasteBundle\Form\Form`](https://github.com/codefog/contao-haste/blob/main/docs/Form.md) of `codefog/contao-haste` to quickly build a form for the back end.

## Example

Create the form like a regular Haste Form, just via the `BackendForm` class in your back end controller for example:

```php
// src/Controller/MyFormController.php
namespace App\Controller;

use Contao\CoreBundle\Controller\AbstractBackendController;
use InspiredMinds\ContaoBackendFormsBundle\Form\BackendForm;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('%contao.backend.route_prefix%/my-form', name: self::class, defaults: ['_scope' => 'backend'])]
class MyFormController extends AbstractBackendController
{
    public function __invoke(Request $request): Response
    {
        $form = new BackendForm('my-form', 'POST');

        $form->addFormField('lorem', [
            'label' => ['Lorem', 'Lorem description.'],
            'inputType' => 'text',
            'eval' => ['tl_class' => 'w50', 'maxlength' => 255],
        ]);

        $form->addFormField('ipsum', [
            'label' => ['Ipsum', 'Ipsum description.'],
            'inputType' => 'text',
            'eval' => ['tl_class' => 'w50', 'maxlength' => 255],
        ]);

        $form->addSubmitFormField('Submit');

        if ($form->validate()) {
            // Do something …

            return new RedirectResponse($request->getUriForPath($request->getPathInfo()));
        }

        return $this->render('my-form.html.twig', [
            'title' => 'My form',
            'headline' => 'My form',
            'form' => $form,
        ]);
    }
}
```

```twig
{# templates/my-form.html.twig #}
{% extends "@Contao/be_main" %}

{% block main_content %}
    {{ form.generate()|raw }}
{% endblock %}
```

This would then render a form like this:

<img src="https://raw.githubusercontent.com/inspiredminds/contao-backend-forms/refs/heads/master/screenshot.png" width="748">
