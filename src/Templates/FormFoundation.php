<?php
namespace Jleagle\FormBuilder\Templates;

use Jleagle\FormBuilder\Field;
use Jleagle\FormBuilder\Form;
use Jleagle\Helpers\Dom;

class FormFoundation extends Form
{

  /**
   * @param Field $field
   *
   * @return Dom
   */
  public function renderField(Field $field)
  {
    $fieldDom = $field->render();
    $labelDom = $field->renderLabel();
    $type     = $field->getType();

    switch ($type)
    {

      case 'select':
      default:

        $labelDom->setChildren($fieldDom);
        return $labelDom;

    }

  }

}
