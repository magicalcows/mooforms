<?php

Namespace MooForms\Validators;

interface ValidatorInterface {
  public function validate($form, $config, $value);
}
