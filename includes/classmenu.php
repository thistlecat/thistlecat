<h3 class="parent"><input id="All" type="radio" name="lcclass" value="" onclick="this.form.submit();"> <label for="All"><strong>All Classes</strong></label></h3><div></div>
<?php
//generate LC class menu
$sql1 = 'SELECT * FROM LCclasses';

$stmt1 = $pdo->prepare($sql1);
$stmt1->execute();

$recordresults1 = $stmt1->fetchAll();

asort($recordresults1);

$prev = null;
foreach ($recordresults1 as $val){
	
	if ((strlen($val['class']) == 2) and ($val['class'][1] == '0'))  { 
	$showclass = $val['class'][0] . "1-" . $val['class'][0] . "9999"; 
	}
	else { 
	$showclass = $val['class']; 
	}
	
	
if (strlen($val['class']) == 1){
//check if last one was a last child that needs to be closed	
	if (($prev == 'E') or ($prev == 'F')) {echo "<div></div>";}
	if (strlen($prev) > 1) {echo "</div>";}
	
	echo '<h3 id="' . $val['class']. 'class" class="parent"><input id="' .  $val['class'] . '" type="radio" name="lcclass" value="' . $val['class'] . '" onclick="this.form.submit();"> <label for="' .  $val['class'] . '">' .   $showclass . ' <small>' . $val['description'] . '</small></label></h3>';
}
else{
	//check if last element was the parent or not
	if (strlen($prev) == 1){echo "<div>";}
	
	echo '<input id="' .  $val['class'] . '" type="radio" name="lcclass" value="' .  $val['class'] . '" onclick="this.form.submit();"> <label for="' . $val['class'] . '"><strong>' .   $showclass . '</strong> <small>' . $val['description'] . '</small></label><br />';
}

$prev = $val['class'];
}
?>
