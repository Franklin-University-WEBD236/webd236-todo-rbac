<?php

$passwords = array(
  'Arya' => 'v@larM0rghul1s',
  'Theon' => '!r0nBorn',
  'Tyrion' => 'th3Imp?!',
  'Todd' => 'N1ceP@ssword',
);

foreach ($passwords as $name => $plaintext) {
  $hash = password_hash($plaintext, PASSWORD_DEFAULT);
  echo "$name has password $plaintext hashed as $hash\n";
}

?>