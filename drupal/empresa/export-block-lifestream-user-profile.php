<?php
global $user;
echo privacidad_notificaciones_txt_info_modificar_privacidad_userid($user->uid);
echo '<h3>' . t('My services') . '</h3>';
echo '<p>' . t('Added to your lifestream') . '</p>';
echo _company_get_lifestream($user->uid, 'uid', true);
echo l(t('Modify services'), 'user/%user:uid/edit', array('attributes' => array('class' => 'mis_datos_servicios_lifestream', 'id' => 'cambiar_servicios_lifestream', 'title' => t('Modify services')), 'fragment' => 'lifestream_user_profile'));
?>