<?php
	require_once('./snippets/constants.php');
	require_once('./snippets/checkSession.php');

	if(!isset($_POST['year'], $_POST['quarter'], $_POST['dept'], $_POST['number'], $_POST['section'])) {
		header('location: course-search.php');
		exit(0);
	}

	$year = trim($_POST['year']);
	$quarter = strtolower(trim($_POST['quarter']));
	$dept = strtoupper(trim($_POST['dept']));
	$number = trim($_POST['number']);
	$section = strtoupper(trim($_POST['section']));
	$mainSection = substr($section,0,1);

	if(isset($_POST['submit']) && $_POST['submit'] == "addAlerts"){
		if(!isset($_POST['checkbox'])){
			$error = "Please select at least one class to add an alert for.";
		} else {
			$uid = $mysql->escape_string($_SESSION['userId']);
			$year = $mysql->escape_string($year);
			$quarter = $mysql->escape_string($quarter);
			$dept = $mysql->escape_string($dept);
			$number = $mysql->escape_string($number);
			foreach ($_POST['checkbox'] as $c) {
				$c = $mysql->escape_string($c);
				$result = $mysql->query("SELECT * FROM alerts WHERE (userId = '$uid') AND (classYear = '$year') AND (classTerm = '$quarter') AND (classDept = '$dept') AND (classNumber = '$number') AND (classSection = '$c')") or die($mysql->error());
				if(!$result->num_rows){
					$sql = "INSERT INTO `alerts` (`userId`, `classYear`, `classTerm`, `classDept`, `classNumber`, `classSection`, `created`) VALUES ('$uid', '$year', '$quarter', '$dept', '$number', '$c', '".time()."');";
					$mysql->query($sql);
				}
			}
			if($error == ""){
				header('location: account.php');
				exit(0);
			}
		}
	}

	//Query the first API, in order to get the linked sections
	$url = "https://ws.admin.washington.edu/student/v4/public/course/$year,$quarter,".rawurlencode($dept).",$number/$mainSection.xml"; 

	// the 'or die()' should probably redirect back to the search with an actual error.
	if(!$xml = file_get_contents($url)){
		echo $url;
		header('location: course-search.php?error=notFound');
		exit(0);
	}
	$classInfo = new SimpleXMLElement($xml);
	$sectionTypes = $classInfo->LinkedSectionTypes->SectionType;
	$classTitle = $classInfo->CourseTitleLong;

	$title = "Related Sections for $dept $number $section";
	$cssBase = "search-results";
	require_once('./snippets/header.php');
?>
<div id="box">
	<h2>Related Sections - <?= "$dept $number $section" ?></h2>
<?php require_once('./snippets/topNav.php'); ?>
	<form method="post" action="">
		<input type="hidden" name="year" value="<?= $year ?>">
		<input type="hidden" name="quarter" value="<?= $quarter ?>">
		<input type="hidden" name="dept" value="<?= $dept ?>">
		<input type="hidden" name="number" value="<?= $number ?>">
		<input type="hidden" name="section" value="<?= $section ?>">
		<div>
			<div class="mainSection">
				<h4>Main Section</h4>
				<input type="checkbox" name="checkbox[]" value="<?= $mainSection ?>" <?= $mainSection == $section ? "checked" : "" ?> /> 
				<label><?= "$dept $number <b>$mainSection</b>" ?></label>
			</div>
<?php 
	if($sectionTypes):
		foreach($sectionTypes as $sectionType):
?>
			<hr />
			<h4><?= ucfirst($sectionType->Type) ?> Sections</h4>
<?php
			foreach($sectionType->LinkedSections->LinkedSection as $subSection):
?>
			<div class="subSsection">
				<input type="checkbox" name="checkbox[]"  value="<?= $subSection->Section->SectionID ?>" <?= $subSection->Section->SectionID == $section ? "checked" : "" ?> />
				<label><?= "$dept $number <b>".$subSection->Section->SectionID."</b>" ?></label>
			</div>
<?php 
			endforeach;
		endforeach;
	endif;
?>
			<hr />
			<div class="allSections">
				<input type="checkbox" class="checkAll" />
				<label>Alert me about <b>all sections</b> of <?= "$dept $number $section" ?></label>
			</div>
		</div>
		<button class="right" name="submit" type="submit" value="addAlerts">Add Alerts</button>
		<div class="clear"></div>
	</form>
</div>
<?php require_once('./snippets/footer.php'); ?>
