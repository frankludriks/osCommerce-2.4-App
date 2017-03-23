<?php
use OSC\OM\HTML;

require(__DIR__ . '/template_top.php');
?>

<div class="row">
  <div class="col-sm-6">
    <div class="panel panel-primary">
      <div class="panel-heading"><?= $OSCOM_Related->getDef('onboarding_intro_title'); ?></div>
      <div class="panel-body">
        <?=
            $OSCOM_Related->getDef('onboarding_intro_body', [
                'button_install' => HTML::button($OSCOM_Related->getDef('button_install'), null, $OSCOM_Related->link('Start&Process'), null, 'btn-primary')
            ]);
        ?>
      </div>
    </div>
  </div>
</div>

<?php
require(__DIR__ . '/template_bottom.php');
?>