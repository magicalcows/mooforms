<?php
ini_set('display_errors', 'on');
error_reporting(E_ALL);
require '../../lib/mooforms/mooforms/Form.php';
require_once '../vendor/autoload.php';

use Respect\Validation\Validator as v;
use MooForms\Form;
use MooForms\Exception\ValidationException;


$formConfigFile = __DIR__.'/formConfig.php';
$fieldConfigFile = __DIR__.'/fieldConfig.php';

require $formConfigFile;
require $fieldConfigFile;

try {

  

  $form = new Form($formConfig);
  
  // Here we configure the entire struture of our form, including all HTML layout.


  $form->setConfig($field_config);

  if (Form::isPost()) {

    if ($form->isValid()) {
      $data = $form->getData();
      echo "Success!";
      echo '<pre>';
      echo var_dump($data);
      echo '</pre>';
      exit;
    }

  }

  $form_html = $form->build();

} catch(\MooForms\Exception\FormException $e) {
  echo "MooForms Error: <br />";
  echo $e->getMessage();
}

$TITLE = 'MooForms Simple Example';
$HTML = '<div class="thin-form">' . @$form_html . '</div>';

include '../template-bootstrap3.phtml';
