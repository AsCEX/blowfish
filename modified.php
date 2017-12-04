<?php
error_reporting(E_ERROR | E_PARSE);
define("SITEURL", "http://localhost/blowfish");
define("HEX", "0123456789ABCDEF");
define("BIN", "01");

require_once('blowfish_mod128.php');

$blowfishMod = new BlowfishMod();
$blowfishMod2 = new BlowfishMod();
$blowfish = null;
$blowfish2 = null;

$text = "";
$key = "";
$iv = "";
$ciphertext = "";
$deciphered = "";
$base64text = "";
$hexKey = 0;
$hexText = 0;
$paddingCBC = 0;
$text2 = "";

if(isset($_POST['encrypt'])){


	$base = trim($_POST['base']);
	$text = trim($_POST['text']);

	if($blowfishMod->isBinary($text)){
		$text = $blowfishMod->convBase($text, BIN, HEX);
	}

	$key = trim($_POST['key']);
	$iv = ($_POST['vector'] != "") ? $_POST['vector'] : NULL;


	$blowfish = $blowfishMod->encrypt($text, $key, $iv);
	$dblowfish = $blowfishMod->decrypt();



	$base2 = trim($_POST['base2']);
	$text2 = trim($_POST['text2']);

	if($blowfishMod2->isBinary($text2)){
		$text2 = $blowfishMod2->convBase($text2, BIN, HEX);
	}

	$key2 = trim($_POST['key2']);
	$iv2 = ($_POST['vector2'] != "") ? $_POST['vector2'] : NULL;

	$blowfish2 = $blowfishMod2->encrypt($text2, $key2, $iv2);
	$dblowfish2 = $blowfishMod2->decrypt();
}



?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel ="stylesheet" href="<?php echo SITEURL; ?>/vendor/twbs/bootstrap/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="<?php echo SITEURL; ?>/vendor/components/font-awesome/css/font-awesome.min.css" />
	<style>
		.alert{
			word-break: break-all;
		}

        .navbar{
            border-radius: 0px;
        }
        
        .icon-bar {
            width: 100%; /* Full-width */
            background-color: #555; /* Dark-grey background */
            overflow: auto; /* Overflow due to float */
        }

        .icon-bar a {
            float: left; /* Float links side by side */
            text-align: center; /* Center-align text */
            width: 20%; /* Equal width (5 icons with 20% width each = 100%) */
            padding: 12px 0; /* Some top and bottom padding */
            transition: all 0.3s ease; /* Add transition for hover effects */
            color: white; /* White text color */
            font-size: 36px; /* Increased font size */
        }
        .icon-bar a small {
            display: block;
            font-size: 18px;
        }

        .icon-bar a:hover {
            background-color: #000; /* Add a hover color */
        }

        .active {
            background-color: #4CAF50; /* Add an active/current color */
        }
        td{
            max-width: 175px;
		    word-wrap: break-word;
		    /*vertical-align: middle!important;*/
		}
	</style>
</head>
<body>
		<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
		  <a class="navbar-brand" href="<?php echo SITEURL; ?>">ModBlowfish</a>
		  <ul class="navbar-nav">
      <li class="nav-item active">
        <a class="nav-link" href="<?php echo SITEURL; ?>">Encrypt <span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="<?php echo SITEURL; ?>/decrypt.php">Decrypt</a>
      </li>
  </ul>
		</nav>


<div class="container">
	<div class ="row">

				<?php if(isset($_POST['encrypt'])): ?>
					<div class ="col-12" style="border:1px solid #e2e2e2; margin-top:20px;padding:20px;margin-right:10px;">
						<h1>Blowfish Encryption Results</h1>

							<?php
							$ham1 = $blowfishMod->convBase($blowfish->cipher, HEX, BIN);
							$ham2 = $blowfishMod2->convBase($blowfish2->cipher, HEX, BIN);

							$hamming_dist =  $blowfishMod->ham_distance($ham1, $ham2);

							$dham1 = $blowfishMod->convBase($dblowfish->plaintext, HEX, BIN);
							$dham2 = $blowfishMod2->convBase($dblowfish2->plaintext, HEX, BIN);
							$hamming_dist2 =  $blowfishMod->ham_distance($dham1, $dham2);

							?>

						<table class="table table-striped table-bordered table-dark table-sm">
								<thead>
									<tr>
										<td width="30%">Name</td>
										<td>Value</td>
									</tr>
								</thead>
								<tr class="bg-info">
									<td>Hamming Distance</td>
									<td><?php echo ($text2) ? $hamming_dist : ""; ?></td>
								</tr>
								<tr class="bg-info">
									<td>Avalance Effect</td>
									<td><?php echo ($text2) ? sprintf("%.6f %%", ($hamming_dist/strlen($ham1) * 100) ) : ""; ?></td>
								</tr>
							</table>

						<table class="table table-striped table-bordered table-dark table-sm">
							<thead>
								<tr class="bg-success">
									<td width="15%">Type</td>
									<td colspan="2" style="width:40%;text-align:center;">Set A</td>
									<td colspan="2" style="width:50%;text-align:center;">Set B</td>
								</tr></thead>
								<tr>
									<td></td>
									<td width="25%">Hex</td>
									<td width="25%">Binary</td>
									<td width="25%">Hex</td>
									<td width="25%">Binary</td>
								</tr>
								<tr>
									<td>Plain Text</td>
									<td><?php echo $blowfish->plaintext; ?></td>
									<td><?php echo $blowfishMod->convBase($blowfish->plaintext, HEX, BIN); ?></td>

									<td><?php echo ($text2) ? $blowfish2->plaintext : ""; ?></td>
									<td><?php echo ($text2) ? $blowfishMod2->convBase($blowfish2->plaintext, HEX, BIN) : ""; ?></td>
								</tr>
								<tr>
									<td>Encryption Key</td>
									<td><?php echo $blowfish->key; ?></td>
									<td><?php echo $blowfishMod->convBase($blowfish->key, HEX, BIN); ?></td>

									<td><?php echo ($text2) ? $blowfish2->key : ""; ?></td>
									<td><?php echo ($text2) ? $blowfishMod2->convBase($blowfish2->key, HEX, BIN) : ""; ?></td>
								</tr>
								<tr>
									<td>IV</td>
									<td><?php echo $blowfish->iv; ?></td>
									<td><?php echo $blowfishMod->convBase($blowfish->iv, HEX, BIN); ?></td>

									<td><?php echo ($text2) ? $blowfish2->iv : ""; ?></td>
									<td><?php echo ($text2) ? $blowfishMod2->convBase($blowfish2->iv, HEX, BIN) : ""; ?></td>
								</tr>

								<tr >
									<td>Encryption Time</td>
									<td colspan="2" style="width:40%;text-align: center;"><?php echo sprintf("%.6f ",$blowfish->encryption_time); ?> seconds</td>
									<td colspan="2" style="width:50%;text-align: center;"><?php echo sprintf("%.6f ",$blowfish2->encryption_time); ?> seconds</td>
								</tr>
								<tr>
									<td>Cipher Text</td>
									<td><?php echo $blowfish->cipher; ?></td>
									<td><?php echo $blowfishMod->convBase($blowfish->cipher, HEX, BIN); ?></td>

									<td><?php echo ($text2) ? $blowfish2->cipher : ""; ?></td>
									<td><?php echo ($text2) ? $blowfishMod2->convBase($blowfish2->cipher, HEX, BIN) : ""; ?></td>
								</tr>

						</table>	

			</div>

				<?php endif; ?>	
	</div>
</div>

<div class="container border p-4 mt-2 mb-4">
		<h1>Blowfish Encryption</h1>
		<form method ="post" action ="">
			<div class="row">		

					<div class="col-6 border p-3">
 
						  <div class="form-group">
						    <label for="exampleInputfirst name">*Text A <small class="inputType"></small></label>
						    <textarea class="form-control" required="required" id="exampleInputfirstname" rows="10" placeholder="Enter Text" name ="text"></textarea>
						  </div>

						  <div class="form-group">
						    <label for="exampleInputlast name">*Key A <small class="inputType"></small></label>
						    <input type="text" class="form-control" required="required" id="exampleInputlastname" placeholder="key" name ="key" value='' />
						  </div>

						  <div class="form-group">
						    <label for="exampleInputlast name">Initialization Vector A <small class="inputType"></small></label>
						    <input type="text" class="form-control" id="exampleInputlastname" placeholder="vector" name ="vector" value="" />
						  </div>

					</div>

					<div class="col-6 border p-3">  
 
						  <div class="form-group">
						    <label for="exampleInputfirst name">*Text B <small class="inputType2"></small></label>
						    <textarea class="form-control"  id="exampleInputfirstname" rows="10" placeholder="Enter Text" name ="text2"></textarea>
						  </div>

						  <div class="form-group">
						    <label for="exampleInputlast name">*Key B <small class="inputType2"></small></label>
						    <input type="text" class="form-control" id="exampleInputlastname" placeholder="key" name ="key2" value='' />
						  </div>

						  <div class="form-group">
						    <label for="exampleInputlast name">Initialization Vector B <small class="inputType2"></small></label>
						    <input type="text" class="form-control" id="exampleInputlastname" placeholder="vector" name ="vector2" value="" />
						  </div>
					</div>
				<button type="submit" name="encrypt" class="btn btn-danger" style="display:block;width:80%;margin:0 auto;">Encrypt</button>
			
	</div>
</form>
</div>
	
	<script src="<?php echo SITEURL; ?>/vendor/components/jquery/jquery.min.js"></script>
	<script src="<?php echo SITEURL; ?>/vendor/twbs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>