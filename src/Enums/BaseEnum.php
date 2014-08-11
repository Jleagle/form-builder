<?php
namespace Jleagle\FormBuilder\Enums;

class BaseEnum
{

  /**
   * @return array
   */
  public static function getConstants()
  {
    $reflection = new \ReflectionClass(new self());
    return $reflection->getConstants();
  }

  /**
   * @param $type
   *
   * @return bool
   */
  public static function constantExists($type)
  {
    return in_array($type, self::getConstants());
  }

}
