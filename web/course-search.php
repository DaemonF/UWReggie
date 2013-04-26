<?php
	require_once('./snippets/constants.php');
	require_once('./snippets/checkSession.php');
	$error = isset($_GET['error']) && $_GET['error'] == "notFound" ? "I could not find sections for that course. It is either not offered in that quarter, or I couldn't understand your query." : "";
	$title = "Search for Courses";
	$cssBase = "course-search";
	require_once('./snippets/header.php');
?>
<div id="box">
	<h2>Search for a class</h2>
<?php require_once('./snippets/topNav.php'); ?>
	<form id="reggieAlertForm" method="post" action="search-results.php">
		<div id="horzLabels" class="center">
			<label for="year">Year</label>
			<label for="quarter">Quarter</label>
			<label for="dept">Dept.</label>
			<label for="number">Course #</label>
			<label for="number">Section</label>
		</div>
		<div id="horzInputs" class="center">
			<select name="year" id="year" class="horzInput">
				<option value="2013" selected="selected">2013</option>
				<option value="2012">2012</option>
				<option value="2011">2011</option>
			</select>
			<select name="quarter" id="quarter" class="horzInput">
				<option value="autumn">Autumn</option>
				<option value="winter" >Winter</option>
				<option value="spring" selected="selected">Spring</option>
				<option value="summer">Summer</option>
			</select>
<?php	
	$classes = array(array("CSE",143,"BC"),array("ART",124,"A"),array("E E",215,"AC"),array("MUSED",340,"A"),array("ARCH",151,"A"),array("MATH",126,"BF"));
	$class = $classes[rand(0, count($classes)-1)];
?>
			<input name="dept" id="dept" class="horzInput" type="text" title="<?= $class[0] ?>" size="3" />
			<input name="number" id="number" class="horzInput" type="text" title="<?= $class[1] ?>" size="3" />
			<input name="section" id="section" class="horzInput" type="text" title="<?= $class[2] ?>" size="3" />
		</div>
		<button name="submit" class="right" type="submit">Find Sections</button>
		<div class="clear"></div>
	</form>
</div>
<?php require_once('./snippets/footer.php'); ?>
