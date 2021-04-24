<?php /* UTF-8 Verified (áéíóú) */

// $Id$

if ($data['data']): ?>
<h2 class="pane-title"><?php echo t('Change history of this company:') ?></h2>
<?php foreach ($data['data'] as $row): $user = user_load($row['uid']); $replaces = array('!field' => t($row['field'])); ?>
<div class="empresa-update-date"><?php echo date('d/m/Y', $row['date']) ?></div>
<div class="empresa-update-type"><?php echo l($user->name, 'user/' . $user->uid) . ' ' . t('was updated !field', $replaces) ?></div>
<?php endforeach; ?>
<?php endif; ?>
<?php if (user_access('edit own empresa content') && user_access('create empresa content') && user_access('access content')): ?>
<p>&nbsp;</p><div class="empresa-edit-data"><?php echo l(t('Add or update company\'s data'), 'node/' . arg(1) . '/edit') ?></div>
<?php endif; ?>