<!-- link to stargate -->
<!-- https://stargate.ncc.edu/~syc6134/ITE254Proj1.php -->
<!--
Dana Sy-Ching
ITE 254 GA
Submitted Oct. 4, 2019 | Due Oct. 6, 2019
-->

<html>
<head> <title> Tickets </title>
<!-- google fonts -->
<link href="https://fonts.googleapis.com/css?family=Anton|Archivo+Black|Fjalla+One|Francois+One|Permanent+Marker|Quicksand&display=swap" rel="stylesheet">

<!--trying out conditional fade in -->
<script type="text/javascript">
function optionDisp(){

	var checkBox = document.getElementById("check");
	var div = document.getElementById("checkbox");
	var div2 = document.getElementById("fadein");

	//switches between default "checkbox" id which shows nothing
	//or "fadein" id which displays options
	if (checkBox.checked == true){
		div.id = "fadein";
	}
	else {
		div2.id = "checkbox";
	}
}
</script>

<style type="text/css">

body {

	font-family: arial;
	background-image: url("https://i.imgur.com/VGNu29F.jpg");
	font-family: 'Quicksand', sans-serif;
	background-attachment: fixed;
}

#heading {
	font-size: 2.25em;
	font-family: 'Anton', sans-serif;
	background: white;
	opacity: 0.3;
	width: 750px;
	padding: 25px 0px 25px 0px;
	margin: 50px auto 0px auto;
	text-align: center;
}

#contentwrap {
	background: rgba(47, 79, 79, 0.4);
	width: 725px;
	padding: 20px;
	margin: 20px auto 0px auto;
	border-radius: 5px;
	box-shadow: black 2px 2px 10px;
}

/* aligns everything left, but padded relative to contentwrap div */
#invisiblewrap {
	margin-left: 225px;
}

.formtext {
	margin: 20px auto 5px 0px;
	opacity: 1;
	text-align: left;
	color: white;
	font-family: 'Quicksand', sans-serif;
	font-weight: bold;
}

.fieldstyle {
	background: rgba(173, 216, 230, 0.3);
	width: 300px;
	font-size: 1.1em;
	padding: 5px;
	border: 2px white solid;
	color: white;
}

/* Default to no display until checkbox is cheked (js) */
#checkbox {
	display: none;
}

/* Remove spinners for number input */
input[type=number]::-webkit-inner-spin-button, 
input[type=number]::-webkit-outer-spin-button { 
  -webkit-appearance: none; 
  margin: 0; 
}

/* fade in select carrier option */
#fadein {
	animation: fadeIn ease 1s;
    -webkit-animation: fadeIn ease 1s;

}

@keyframes fadeIn{
  0% {
    opacity:0;
  }
  100% {
    opacity:1;
  }
}
/* error outputs show up in red */
.error {
	color: red;
}


</style>
</head>


<body>
<!-- Validating errors | no blank inputs and must be proper email format -->
<?php

$nameErr = $emailErr = $numErr = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
		$emailErr = "*Invalid email format";
	}
}
?>

<div id = "heading"> Ticket Order Form </div>

	<div id = "contentwrap">
	<div id="invisiblewrap">
		<form method = "post" action = "<?php echo $_SERVER['PHP_SELF']?>">

			<div class = "formtext"> Name <span class="error"> <?php echo $nameErr;?> </span> </div> 
			<input type="text" class="fieldstyle" name="name" id="name" required="">

			<div class = "formtext"> Email <span class="error"> <?php echo $emailErr;?> </span> </div> 
			<input type="text" class="fieldstyle" name="email" id="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" required="">

			<!--numerical input only, no negatives, max length is 12-->
			<div class = "formtext"> Number <span class="error"> <?php echo $numErr;?> </span> </div> 
			<input type="number" class="fieldstyle" name="phonenum" min="0"
			onKeyPress="if(this.value.length==12) return false;" id="phonenum" required="">

			<div class = "formtext"> Select Ticket Type </div>
			<select name="ticket">
				<option value="Balcony|99.99"> Balcony [$99.99] </option>
				<option value="Mezzanine|129.99"> Mezzanine [$129.99] </option>
				<option value="Box|199.99"> Box [$199.99] </option>
				<option value="Orchestra|299.99"> Orchestra [$299.99] </option>
			</select>

			<div class = "formtext"> Select Quantity </div>
			<select name="qty">
				<option value="1"> 1 </option>
				<option value="2"> 2 </option>
				<option value="3"> 3 </option>
			</select>

			<div class = "formtext"> Select Date </div>
			<input type="date" name="date" value="<?php echo date('Y-m-d')?>">

			<div class="formtext"> <input type="checkbox" id="check" name="check" onclick="optionDisp()"> I would like to recieve the details through text </div>
			
			<div id = "checkbox">
			<div class="formtext"> Select carrier </div>
				<select name="carrier">
					<option value="@txt.att.net"> AT&T </option>
					<option value="@vtext.com"> Verizon </option>
					<option value="@messaging.sprintpcs.com"> Sprint </option>
				</select>
			</div>

			<div style="margin-top: 25px;">
				<input type="submit" value="Submit">
			</div>

			<?php
			//check if isset and if no errors
			if(isset($_POST['name'])) {

				$tkt = $_POST['ticket'];
				$tktDetails = explode('|', $tkt);
				$qty = $_POST['qty'];
				$cost = $tktDetails[1] * $qty;
				$date = date('l, F d, Y', strtotime($_POST['date']));

				echo "<div class='formtext'> Hi, ".$_POST['name'].". </div>\n";
				echo "<div class='formtext'> You are purchasing $qty $tktDetails[0] ticket(s) to the show on $date. </div>\n";
				echo "<div class='formtext'> Your purchase amounts to: ".money_format('$%i', $cost)."</div>\n";


				if(isset($_POST['check'])) {
					$to = $_POST['phonenum'].$_POST['carrier'];
					//mail(to, headers, message, from)
					$msg = "You have purchased ".$qty . $tktDetails[0]." ticket(s) to the show on ".$date.". Your purchase amounted to: ".money_format('$%i', $cost).".";
					mail($to, "", $msg, "From us.");
					echo "<div='formtext'>Your text was sent to ".$to."</div>.\n";
				}

			}

			?>




		</form>
		</div>
		</div>

		</div>

</body>

</html>


