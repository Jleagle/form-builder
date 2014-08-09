<?php
namespace Jleagle\FormBuilder\Enums;

class InputTypeEnum extends BaseEnum
{

  // Text
  const COLOUR         = 'color';
  const DATE           = 'date';
  const DATETIME       = 'datetime';
  const DATETIME_LOCAL = 'datetime-local';
  const EMAIL          = 'email';
  const MONTH          = 'month';
  const NUMBER         = 'number';
  const PASSWORD       = 'password';
  const RANGE          = 'range';
  const SEARCH         = 'search';
  const TEL            = 'tel';
  const TEXT           = 'text';
  const TIME           = 'time';
  const URL            = 'url';
  const WEEK           = 'week';

  // Buttons
  const BUTTON         = 'button';
  const SUBMIT         = 'submit';
  const IMAGE          = 'image';
  const RESET          = 'reset';

  // Other
  const SELECT         = 'select';
  const HIDDEN         = 'hidden';
  const CHECKBOX       = 'checkbox';
  const RADIO          = 'radio';
  const FILE           = 'file';
  const TEXTAREA       = 'textarea';

}
