<!--
---- php-ldap.php (0644): server example PHP/LDAP code
--->

<?php

echo "<html><head><title>PHP/LDAP Query Test</title></head><body>";

$lc = ldap_connect("10.70.4.27", 389);

ldap_set_option($lc, LDAP_OPT_PROTOCOL_VERSION, 3);
ldap_set_option($con, LDAP_OPT_REFERRALS, 0);

if (empty($lc)) {
  echo "<h1>Unable to connect to the LDAP server!</h1>";
  echo "</body></html>";
  exit;
}

if (!ldap_bind($lc, 'lulu', 'lulu')) {
  echo "<h1>Can't contact LDAP server</h1>";
  echo "</body></html>";
  exit;
}

echo "<h1>LDAP query results</h1>";

//$base = "DC=companydot, DC=net";
$base = "OU=CalendarioEventos,OU=UniOrg_Usuarios,DC=companydot,DC=net";
$filt = "samaccountname=*";
$sr = ldap_search($lc, $base, $filt);
$info = ldap_get_entries($lc, $sr);

echo "Searched from base " . $base . " with filter " . $filt . ".<br><br>";

print "<pre>" . print_r($info, TRUE) . "</pre>";
for ($i = 0; $i < $info["count"]; $i++) {
  echo "Match " . $i . ": " . $info[$i]["cn"][0];
  echo " (e-mail: " . $info[$i]["cn"][0] . ")<br>";
}

if ($i == 0) {
  echo "No matches found!";
}

ldap_close($lc);

echo "</body></html>";

?>
