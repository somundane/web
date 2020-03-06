<!-- link to stargate -->
<!-- https://stargate.ncc.edu/~syc6134/ITE254Proj2.php -->
<!--
Dana Sy-Ching
ITE 254 GA
submitted Nov. 15, 2019 | Due Nov. 19, 2019
-->


<?php
	require_once(".htpasswd");
	//update votes if isset is valid
	if(isset($_POST['radio'])){
		//query to add number
		$updatequery = "UPDATE voting SET votes = votes + 1 WHERE id = ".$_POST['radio'];
		//perform query to add number
		$runquery = mysqli_query( $db, $updatequery )
			or die( "Failed to update db.". mysqli_error($db) );
	}
?>
<html>
<head>
<title> Vote </title>
<link href="https://fonts.googleapis.com/css?family=Anton|Archivo+Black|Fjalla+One|Francois+One|Permanent+Marker|Quicksand&display=swap" rel="stylesheet">

<style type="text/css">

body {

	font-family: 'Quicksand', sans-serif;
	background-attachment: fixed;
	background-image: url("https://media.giphy.com/media/1oETSTTe9nkRP9bi2Z/giphy.gif");
	background-repeat: round;
}

#contentwrap {
	background: rgba(95, 158, 160, 0.96);
	width: 725px;
	padding: 20px;
	margin: 20px auto 0px auto;
	border-radius: 5px;
	box-shadow: black 2px 2px 10px;
}

#heading {
	font-size: 2.25em;
	background: white;
	opacity: 0.3;
	width: auto;
	padding: 25px 0px 25px 0px;
	margin: 15px auto 0px auto;
	text-align: center;
	border-radius: 5px;
}

#innerdiv {
	padding: 15px;
	margin: 25px auto 25px auto;

}

.textstyle {
	font-size: 1.75em;
	font-weight: bold;
	color: black;
	margin: 10px 0px 10px 25px;
	border-bottom: 5px black double;
}

.spacer {
	margin: 5px 0px 5px 25px;
}

#success {
	max-width: 150px;
}
#win {
	font-weight: bold;
	color: darkgreen;
}

</style>

</head>
<body>
<div id="contentwrap">
	<div id="heading"> Vote for a favorite show! </div>
	<div id="innerdiv">
		<div class="textstyle">Vote Here</div>

			<form method = "post" action = "<?php echo $_SERVER['PHP_SELF']?>">

			<?php

				$totalvotes = 0;
				$query = "SELECT * FROM voting ORDER BY title";

				$results = mysqli_query( $db, $query )
					or die( "Could not get data". mysqli_error($db) );

				//loop to create radio buttons
				for( $i = 0; $i < mysqli_num_rows( $results ); $i++ ) {

					$data = mysqli_fetch_array( $results );
					$totalvotes = $totalvotes + $data['votes'];

					echo "<div class='spacer' style='font-weight: bold;'> <input type='radio' name='radio' value='".$data['id']."'";
						//if button has been clicked check which one and keep it there
						if(isset($_POST['submit'])){
							if ($_POST['radio'] == $data['id']) {
								echo "checked";
							}
						}
						//otherwise, default to first
						else if ($i == 0) {
							echo "checked";
						}

					echo ">".$data['title']."</div>\n";

				}

			?>

			<div style = "margin-top: 15px; margin-left: 25px;">
				<input type="submit" name="submit" value="Place Vote">
			</div>

			</form>

		<?php
			if(isset($_POST['radio'])){
				echo "<div class='textstyle'>Results So Far</div>\n";
				echo "<div class='spacer' style='font-weight: bold;'>Total number of votes is ".$totalvotes."</div>\n";

				$results = mysqli_query( $db, $query )
					or die( "Could not get data". mysqli_error($db) );

				$topvotes = 0;
				for( $i = 0; $i < mysqli_num_rows( $results ); $i++ ) {
					$data = mysqli_fetch_array( $results );
					$gif;
					$win;
					//calculate percentage
					if($data['votes'] > 0) {
						$percent = ($data['votes'] / $totalvotes ) * 100;
					}
					else {
						$percent = 0;
					}

					if($data['votes'] > $topvotes) {
						$topvotes = $data['votes'];
						$gif = $data['img_url'];
						$win = "<div id='win' style='margin-left: 25px;'> The show with the most votes is ".$data['title']."! </div>\n";
					}

					echo "<div class='spacer'>".$data['title']." has ".$data['votes']." votes (".round($percent, 2)."% of total) </div>\n";

				}
				echo $win;
				echo "<div id='success' style='margin-left: 25px;'> <img src='".$gif."'> </div>\n";
			}

		?>

	</div>
</div>

</body>
</html>