<?php
ini_set('display_errors', 'on');
error_reporting(E_ALL);
require '../../library/mooforms/mooforms/Form.php';
require_once '../vendor/autoload.php';

use MooForms\Form;
$formConfigFile = __DIR__.'/formConfig.php';
$fieldConfigFile = __DIR__.'/fieldConfig.php';

require $formConfigFile;
require $fieldConfigFile;

$form_html = '';

try {

  $form = new Form($formConfig);
  
  // Here we configure the entire struture of our form, including all HTML layout.
  

  $form->setConfig($field_config);

  $form_html = $form->build();
  
  if (Form::isPost()) {

    if ($form->isValid()) {
      $data = $form->getData();
      $form_html = "<h3>Success!</h3><b>Submitted Data:</b>" . Form::p($data, false);
      
      
    }

  }


} catch(\MooForms\Exception\FormException $e) {
  echo "MooForms Error: <br />";
  echo $e->getMessage();
}

$TITLE = 'MooForms Kitchen Sink';
$horizontalChecked = $_isHorizontal ? ' checked' : '';
$showFeedbackChecked = (bool)@$_GET['showFeedback'] ? ' checked' : '';
$hideLabelsChecked = (bool)@$_GET['hideLabels'] ? ' checked' : '';

$HTML = <<<EOT
<div class="clearfix" style="margin-bottom: 10px;">
  <button class="btn btn-primary btn-small pull-right" type="button" data-toggle="collapse" data-target="#collapseSettings" aria-expanded="true" aria-controls="collapseSettings">
    <i class="glyphicon glyphicon-cog"></i>
  </button>
</div>
<div class="collapse" id="collapseSettings">
  <div class="well">
    <form class="form form-inline" method="GET">
      <div class="form-group"><div class="checkbox">
          <label><input type="checkbox" name="formStyle" value="horizontal"$horizontalChecked> Horizontal Form</label>
      </div></div><div class="form-group"><div class="checkbox">
          <label><input type="checkbox" name="showFeedback" value="1"$showFeedbackChecked> Show Feedback</label>
      </div></div><div class="form-group"><div class="checkbox">
          <label><input type="checkbox" name="hideLabels" value="1"$hideLabelsChecked> Hide Labels</label>
      </div></div><div class="form-group">
      <input type="submit" class="btn btn-default" value="Submit" />
      </div>
    </form>
  </div>
</div>


EOT;

if ($_isHorizontal)  $HTML .= $form_html;
else $HTML .= '<div style="max-width:400px">'.$form_html .'</div>';
include '../template-bootstrap3.phtml';
?>
<script>$('#collapseSettings').collapse()</script>

