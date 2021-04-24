<?php
eco('pruebas');
function eco($text) {
  echo '<pre>/**' . "\n * " . $text . "\n */" . '</pre>';
}
eco(ord('A'));
eco(ord('Z'));
eco(ord('a'));
eco(ord('z'));
while (true) {
  $s = rand(1,12); for ($i=0; $i <= $s; $i++) { $usr .= str_replace(array(1,2),array(chr(rand(65,90)),chr(rand(97,122))),rand(1,2)); }
  $s = rand(1,12); for ($i=0; $i <= $s; $i++) { $fqdm .= str_replace(array(1,2),array(chr(rand(65,90)),chr(rand(97,122))),rand(1,2)); }
  $s = rand(10,12); for ($i=0; $i <= $s; $i++) { $tdl .= str_replace(array(1,2),array(chr(rand(65,90)),chr(rand(97,122))),rand(1,2)); }
  $email = $usr . '@' . $fqdm . '.' . $tdl;
  break;
}
if (valid_email_address($email)) {
  eco('<h1 style="color:red;">E-mail valido ' . $email . '</h1>');
}
function valid_email_address($mail) {
  $user = '[a-zA-Z0-9_\-\.\+\^!#\$%&*+\/\=\?\`\|\{\}~\']+';
  $domain = '(?:(?:[a-zA-Z0-9]|[a-zA-Z0-9][a-zA-Z0-9\-]*[a-zA-Z0-9])\.?)+';
  $ipv4 = '[0-9]{1,3}(\.[0-9]{1,3}){3}';
  $ipv6 = '[0-9a-fA-F]{1,4}(\:[0-9a-fA-F]{1,4}){7}';
  return preg_match("/^$user@($domain|(\[($ipv4|$ipv6)\]))$/", $mail);
}
eco(strlen('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam in nisl neque, ac sollicitudin odio. Donec at nunc nec leo molestie suscipit. Cras diam dui, laoreet et rhoncus id, pellentesque nec lectus. Quisque in tellus turpis, in ultrices diam. Nulla facilisi. Integer hendrerit ante quis libero imperdiet dapibus. Sed orci ligula, posuere non aliquet eget, adipiscing sit amet est. Aliquam ut justo sed purus aliquam sodales ac quis elit. Aenean odio quam, porttitor eu pulvinar id, convallis vel lectus. Aliquam pellentesque tristique sem vitae fringilla.

Phasellus vulputate, leo vitae hendrerit aliquet, velit nunc lacinia dolor, vel malesuada arcu leo vel quam. Curabitur rhoncus, lorem at sollicitudin aliquet, dolor justo tincidunt magna, nec pulvinar mi lectus ac nisl. Mauris porttitor, felis a pellentesque semper, mi augue vulputate eros, vel eleifend leo risus a quam. Vivamus faucibus odio facilisis ipsum scelerisque sit amet scelerisque urna molestie. Aenean turpis risus, pharetra vel lobortis non, vestibulum sed est. Phasellus vitae velit lacus. Proin tempor fringilla orci sit amet consequat. Aliquam ut ligula libero, nec dapibus leo. Aliquam diam nulla, porta vel convallis non, faucibus accumsan nunc. Morbi libero dui, pharetra eget commodo ac, varius luctus nibh. Vestibulum a varius quam. Curabitur ac vulputate nisi. Ut pulvinar imperdiet mauris id blandit. Ut ac libero erat, vitae commodo lacus. Duis tincidunt venenatis metus sit amet fringilla. Suspendisse potenti. In ligula nisl, commodo eget placerat sed, mollis vitae augue. Morbi tincidunt purus sodales velit condimentum vel tempus purus porttitor. Sed ultrices massa vitae sapien feugiat tempor sit amet sit amet diam. Nullam sed enim eget lacus pharetra dictum.

Quisque est nibh, feugiat non ornare eu, auctor ac augue. Pellentesque ornare aliquam tellus, ut vestibulum mauris mattis vitae. Aliquam aliquet felis ac metus venenatis vel elementum ligula porttitor. Proin blandit aliquet justo, nec mattis odio sagittis ac. Suspendisse potenti. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Praesent eget justo felis, nec viverra purus. Vestibulum lorem enim, faucibus a malesuada sed, bibendum nec mi. Suspendisse urna eros, molestie et pulvinar gravida, vestibulum nec metus. In quis diam eu est commodo facilisis. Etiam venenatis bibendum nibh sit amet dapibus. Praesent mattis, tortor vel scelerisque sagittis, nulla mauris auctor orci, sit amet volutpat tellus eros nec metus. Fusce semper, nulla sit amet interdum tempor, dui enim volutpat odio, ut dapibus velit felis id lectus. Vestibulum in libero magna, sed sollicitudin arcu. Vestibulum volutpat sodales nisi, eu scelerisque leo sollicitudin ac. Pellentesque rutrum consectetur mollis. Etiam rhoncus facilisis urna, quis adipiscing purus commodo sed. Fusce aliquet, elit vel euismod condimentum, lorem felis mattis odio, et interdum neque augue vitae leo. In sapien ipsum, varius et pharetra vel, commodo sit amet diam. Vivamus non turpis est.'));

list($m, $s) = explode(" ", microtime()); eco((float)$m + (float)$s);


$result = array(
  12 => array(
    'start' => 1294763149.7268,
    'end' => 1294763161.269,
    'pages' => 10,
  ),
  543 => array(
    'start' => 1294763171.7399,
    'end' => 1294763180.163,
    'pages' => 98,
  ),
);
foreach ($result as $id => $stats) {
  $replaces = array(
    '!nid'   => $id,
    '!time'  => round((float)$stats['end'] - (float)$stats['start'],3),
    '!pages' => $stats['pages'],
  );
}
var_dump($result);
var_dump($replaces);
echo mktime(0,0,0,1,1,2011);