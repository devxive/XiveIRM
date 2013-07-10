<?
$dbhost = '';
$dbuser = '';
$dbpass = '';
$dbname = '';

$conn = mysql_connect($dbhost, $dbuser, $dbpass) or die ('Error connecting to mysql');
mysql_select_db($dbname);

if (isset($_POST['search_query'])) {
	$search_query = mysql_real_escape_string(htmlentities($_POST['search_query']));
	echo "<div class=\"searchText\">Search</div><hr />";

	//explode the search term
	$search_query_x = explode(" ",$search_query);

	foreach($search_query_x as $search_each) {
		$x++;
		if($x==1) {
			$construct .="last_name LIKE '%$search_each%'";
		} else {
			$construct .="AND last_name LIKE '%$search_each%'";
		}

		$construct ="SELECT * FROM search WHERE $construct";
		$run = mysql_query($construct);
		$foundnum = mysql_num_rows($run);

		if ($foundnum==0) {
			echo "
				Sorry, there are no matching result for <b>$search_query</b>.</br>
				</br>
				1. Try more general words.</br>
				2. Try different words with similar meaning</br>
				3. Please check your spelling
			";
		} else {
			echo "$foundnum results found !<p>";
		}

		while($runrows = mysql_fetch_assoc($run)) {
			$title = $runrows ['title'];
			$desc = $runrows ['description'];
			$url = $runrows ['url'];
			echo "
				<div class='width: 400px;'>
					<div class='title'><a href='$url'><b>$title</b></a></div>
					<div class='url'>$url</div>
					<div class='desc'>$desc</div>
				</div>
				<br />
			";
		}
	}
} else {
	echo "An ERROR HAS OCCURED ...";
}
?>