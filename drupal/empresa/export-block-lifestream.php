<?php
switch (arg(0)) {
  case 'user':
    global $user;
    $id = $user->uid;
    $tid = 'uid';
    $load = true;
    $who = $user->name;
    break;

  case 'node':
    if (arg(0) == 'node' && is_numeric(arg(1))) {
      $id = arg(1);
      $tid = 'nid';
      $load = true;
      $node = node_load($id);
      $who = $node->title;
    }
    break;

  default:
    $load = false;
    break;
}
if ($load) {
$show_icons = array('empresa');
if (in_array($node->type,$show_icons)) { ?><div id="lifestream-icons"><?php echo _company_get_lifestream($id, $tid, true) ?></div><?php } ?>
<input type="hidden" name="id" value="<?php echo $id ?>" />
<input type="hidden" name="tid" value="<?php echo $tid ?>" />
<input type="button" id="getlifestream" value="<?php echo t('Load lifestream of !who',array('!who' => $who)) ?>" />
<div id="lifestreams"></div>
<?php } ?>