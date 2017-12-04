<?php

require_once('blowfish128.php');

$pt = "1D9D5C5018F728C2";
$k = "018310DC409B26D6";
$ciphertext = "";


$pt2= pack('H'. strlen($pt), $pt);
$key= pack('H' . strlen($k), $k);

$mode = Blowfish128::BLOWFISH_MODE_EBC;
$padding = Blowfish128::BLOWFISH_PADDING_NONE;
$iv = null;

echo "<div >Plaintext: <strong>" . $pt . "</strong><br />";
echo "Key: <strong>" . $k . "</strong></div><br />";
echo "<div style='float:left;width:35%'>";

$bfish = Blowfish128::encrypt($pt2, $key, $mode, $padding, $iv);
$c = unpack("H*", $bfish['ciphertext']);
$time = $bfish['end'] - $bfish['start'];

$cipher_text =  strtoupper(reset($c));
echo "Cipher Text: <strong>". $cipher_text."</strong><br />";
echo sprintf("%.6f ",$time) . " seconds<br />";
echo "</div>";



echo "<div style='float:left;width:35%'>";
$ct = pack("H*", $cipher_text);
$dfish = Blowfish128::decrypt($ct, $key, $mode, $padding, $iv);
$time = $dfish['end'] - $dfish['start'];

$p = unpack("H*", $dfish['plaintext']);
$plain_text =  strtoupper(reset($p));
echo "Plain Text: <strong>". $plain_text."</strong><br />";
echo sprintf("%.6f ",$time) . " seconds<br />";
echo "</div>";
?>