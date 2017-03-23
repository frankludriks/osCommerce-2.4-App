<?php
use OSC\OM\HTML;
use OSC\OM\OSCOM;
use OSC\OM\Registry;

$OSCOM_Related = Registry::get('Related');
$OSCOM_Page = Registry::get('Site')->getPage();
?>

<div class="row" style="padding-bottom: 30px;">

  <div class="col-sm-6 text-right text-muted">
    <?= $OSCOM_Related->getTitle() . ' v' . $OSCOM_Related->getVersion(); ?>
  </div>
</div>

<?php
if ($OSCOM_MessageStack->exists('Related')) {
    echo $OSCOM_MessageStack->get('Related');
}
?>