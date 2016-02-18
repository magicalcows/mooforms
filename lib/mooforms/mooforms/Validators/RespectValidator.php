<?php
Namespace MooForms\Validators;

require_once __DIR__ . '/ValidatorInterface.php';
use Respect\Validation\Exceptions\NestedValidationException;

Class RespectValidator implements ValidatorInterface {

  public function validate($form, $config, $value) {

    $validator = $config['respectValidator']();
    $error = false;
    $exception = false;

    try {
      $validator->assert($value);
    } catch(NestedValidationException $e) {
      $exception =  $e;
      $error = $e->getFullMessage();
    } catch (Exception $e) {
      $exception =  $e;
      $error = 'Exception:' . $e->getMessage();
    }

    return Array($error,$exception);

  }

}
