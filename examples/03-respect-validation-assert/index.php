<?php
ini_set('display_errors', 'on');
error_reporting(E_ALL);
require '../../lib/mooforms/mooforms/Form.php';
require_once '../vendor/autoload.php';

$formConfigFile = __DIR__.'/formConfig.php';
$fieldConfigFile = __DIR__.'/fieldConfig.php';

require $formConfigFile;
require $fieldConfigFile;

use MooForms\Form;


try {


  $form = new Form($formConfig);


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
  // catch errors about form/field configuration
  echo "MooForms Error: <br />";
  echo $e->getMessage();
  exit;
}

$TITLE = 'MooForms with Respect Validation using assert';
$HTML = '<div class="thin-form">' . @$form_html . '</div>';

include '../template-bootstrap3.phtml';
