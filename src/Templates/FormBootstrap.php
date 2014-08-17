<?php
namespace Jleagle\FormBuilder\Templates;

use Jleagle\FormBuilder\Field;
use Jleagle\FormBuilder\Form;
use Jleagle\Dom;

class FormBootstrap extends Form
{

  /**
   * @param Field $field
   *
   * @return Dom
   */
  public function _renderField(Field $field)
  {
    $fieldDom = $field->render();
    $labelDom = $field->renderLabel();
    $type     = $field->getType();

    switch ($type)
    {

      case 'select':
      default:

        $fieldDom->setAttribute('class', 'form-control');
        return new Dom('div', ['class' => 'form-group'], [$labelDom, $fieldDom]);

    }

  }

  /**
   * @param Dom[] $children
   *
   * @return Dom
   */
  protected function _renderForm(array $children)
  {

    $attributes = $this->getAttributes();
    $attributes['role'] = 'form';

    return new Dom(
      'form',
      $attributes,
      $children
    );

  }

}
