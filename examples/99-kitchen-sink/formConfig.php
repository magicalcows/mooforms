<?php

$formConfig = Array(
    'respectValidation' => false,
    'formClass' => 'form',
    'template' => 'bootstrap3',
    // we'll use parsely on the client.
    'parsleyUrl' => 'https://cdnjs.cloudflare.com/ajax/libs/parsley.js/2.3.3/parsley.min.js',
    'showFeedback' => (bool)@$_GET['showFeedback'],
    'hideLabels' => (bool)@$_GET['hideLabels'],
);
$_isHorizontal = false;
if (@$_GET['formStyle'] == 'horizontal') {
  $_isHorizontal = true;
  $formConfig['formClass'] .= ' form-horizontal';
  $formConfig['template'] .= '/horizontal';
}