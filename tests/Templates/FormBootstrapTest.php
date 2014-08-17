<?php
use \Jleagle\FormBuilder\Templates\FormBootstrap as Form;

class FormBootstrapTest extends PHPUnit_Framework_TestCase
{

  public function testForm()
  {

    $form = new Form();
    $this->assertEquals('<form action="" enctype="application/x-www-form-urlencoded" method="post" role="form"></form>', (string)$form);

    $form = new Form('test');
    $this->assertEquals('<form action="test" enctype="application/x-www-form-urlencoded" method="post" role="form"></form>', (string)$form);

  }

}
