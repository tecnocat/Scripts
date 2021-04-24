<?php

echo '<pre>';

$server   = '{mail.example.com:110/pop3/notls}INBOX';
$username = 'user@example.com';
$password = '1234567890';
$mailbox  = imap_open($server, $username, $password);
$headers  = imap_headers($mailbox);

echo '<table border="1" cellpadding="1" cellspacing="1">';
echo '<tr><th>DATE</th><th>SUBJECT</th><th>TO</th><th>FROM</th><th>REPLY</th><th>SENDER</th><th>BODY</th></tr>';
foreach ($headers as $id => $dummy) {

  $id++;
  $header = imap_header($mailbox, $id);
  $body   = imap_body($mailbox, $id);

  extract((array)$header);
  echo "
    <tr>
      <td>$date</td>
      <td>$subject</td>
      <td>$toaddress</td>
      <td>$fromaddress</td>
      <td>$reply_toaddress</td>
      <td>$senderaddress</td>
      <td>\$body</td>
    </tr>
  ";
  //die('HEADER: ' . print_r($header, TRUE) . '<hr />BODY: ' . print_r($body, TRUE));
}
echo '</table>';

imap_close($mailbox);

echo '</pre>';