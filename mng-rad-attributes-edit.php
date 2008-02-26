<?php 
    include ("library/checklogin.php");
    $operator = $_SESSION['operator_user'];

//	include('library/check_operator_perm.php');

	$logDebugSQL = "";

	isset($_REQUEST['vendor']) ? $vendor = $_REQUEST['vendor'] : $vendor = "";
	isset($_REQUEST['attribute']) ? $attribute = $_REQUEST['attribute'] : $attribute = "";

	if (isset($_POST["submit"])) {

		isset($_POST['vendor']) ? $vendor = $_POST['vendor'] : $vendor = "";
		isset($_POST['attributeOld']) ? $attributeOld = $_POST['attributeOld'] : $attributeOld = "";
		isset($_POST['attribute']) ? $attribute = $_POST['attribute'] : $attribute = "";
		isset($_POST['type']) ? $type = $_POST['type'] : $type = "";
		isset($_POST['RecommendedOP']) ? $RecommendedOP = $_POST['RecommendedOP'] : $RecommendedOP = "";
		isset($_POST['RecommendedTable']) ? $RecommendedTable = $_POST['RecommendedTable'] : $RecommendedTable = "";
		isset($_POST['RecommendedTooltip']) ? $RecommendedTooltip = $_POST['RecommendedTooltip'] : $RecommendedTooltip = "";

		include 'library/opendb.php';

		$sql = "SELECT * FROM ".$configValues['CONFIG_DB_TBL_DALODICTIONARY']." WHERE vendor='".$dbSocket->escapeSimple($vendor).
			"' AND attribute='".$dbSocket->escapeSimple($attribute)."'";
		$res = $dbSocket->query($sql);
		$logDebugSQL .= $sql . "\n";

		if ($res->numRows() == 1) {
			if (trim($vendor) != "" and trim($attribute) != "") {
				// update vendor/attribute pairs to database
				$sql = "UPDATE ".$configValues['CONFIG_DB_TBL_DALODICTIONARY']." SET 
					type='".
					$dbSocket->escapeSimple($type)."', attribute='".$dbSocket->escapeSimple($attribute).
					"', RecommendedOP='".$dbSocket->escapeSimple($RecommendedOP).
					"', RecommendedTable='".$dbSocket->escapeSimple($RecommendedTable)."', RecommendedTooltip='".
					$dbSocket->escapeSimple($RecommendedTooltip)."'  
					WHERE Vendor='$vendor' AND Attribute='$attributeOld'";
				$res = $dbSocket->query($sql);
				$logDebugSQL .= $sql . "\n";

				$actionStatus = "success";
				$actionMsg = "Updated database with vendor attribute: <b>$attribute</b> of vendor: <b>$vendor</b>";
				$logAction = "Successfully update vendor [$vendor] and attribute [$attribute] on page: ";
			} else {
				$actionStatus = "failure";
				$actionMsg = "you must provide atleast a vendor name and attribute";	
				$logAction = "Failed updating vendor [$vendor] and attribute [$attribute] on page: ";
			}
		} else { 
			$actionStatus = "failure";
			$actionMsg = "You have tried to update a vendor's attribute that either is not present in the database or there
					may be more than 1 entry for this vendor attribute in database (attribute :$attribute)";
			$logAction = "Failed updating vendor attribute already in database [$attribute] on page: ";		
		}
	
		include 'library/closedb.php';

	}



	include 'library/opendb.php';

	$sql = "SELECT * FROM ".$configValues['CONFIG_DB_TBL_DALODICTIONARY']." WHERE vendor='".$dbSocket->escapeSimple($vendor).
		"' AND attribute='".$dbSocket->escapeSimple($attribute)."'";
	$res = $dbSocket->query($sql);
	$logDebugSQL .= $sql . "\n";

        $row = $res->fetchRow(DB_FETCHMODE_ASSOC);

        $attribute = $row['Attribute'];
        $type = $row['Type'];
        $vendor = $row['Vendor'];
        $RecommendedOP = $row['RecommendedOP'];
        $RecommendedTable = $row['RecommendedTable'];
        $RecommendedTooltip = $row['RecommendedTooltip'];

	include 'library/closedb.php';

	include_once('library/config_read.php');
	$log = "visited page: ";

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<title>daloRADIUS</title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="css/1.css" type="text/css" media="screen,projection" />
</head>
<script src="library/javascript/pages_common.js" type="text/javascript"></script>
<?php

	include ("menu-mng-rad-attributes.php");
	
?>
		
		<div id="contentnorightbar">
		
				<h2 id="Intro"><a href="#" onclick="javascript:toggleShowDiv('helpPage')"><?php echo $l['Intro']['mngradattributesedit.php'] ?>
				<h144>+</h144></a></h2>
				
				<div id="helpPage" style="display:none;visibility:visible" >
					<?php echo $l['helpPage']['mngradattributesedit'] ?>
					<br/>
				</div>
				<br/>

				<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">

<div class="tabber">

	<fieldset>

		<h302> <?php echo $l['title']['VendorAttribute']; ?> </h302>
		<br/>

		<ul>

		<input type='hidden' name='vendor' value='<?php if (isset($vendor)) echo $vendor ?>' />

                <li class='fieldset'>
		<label for='vendor' class='form'><?php echo $l['all']['VendorName'] ?></label>
		<input disabled name='vendor' type='text' id='vendor' value='<?php if (isset($vendor)) echo $vendor ?>' tabindex=100
                        onfocus="javascript:toggleShowDiv('vendorNameTooltip')"
                        onblur="javascript:toggleShowDiv('vendorNameTooltip')" />
                <div id='vendorNameTooltip'  style='display:none;visibility:visible' class='ToolTip'>
                        <img src='images/icons/error.png' alt='Tip' border='0' />
                        <?php echo $l['Tooltip']['vendorNameTooltip'] ?>
                </div>
		</li>

		<input type='hidden' name='attributeOld' value='<?php if (isset($attribute)) echo $attribute ?>' />

                <li class='fieldset'>
		<label for='attribute' class='form'><?php echo $l['all']['Attribute'] ?></label>
		<input name='attribute' type='text' id='attribute' value='<?php if (isset($attribute)) echo $attribute ?>' tabindex=101
                        onfocus="javascript:toggleShowDiv('attributeTooltip')"
                        onblur="javascript:toggleShowDiv('attributeTooltip')" />
                <div id='attributeTooltip'  style='display:none;visibility:visible' class='ToolTip'>
                        <img src='images/icons/error.png' alt='Tip' border='0' />
                        <?php echo $l['Tooltip']['attributeTooltip'] ?>
                </div>
		</li>

                <li class='fieldset'>
		<label for='type' class='form'><?php echo $l['all']['Type'] ?></label>
		<input name='type' type='text' id='type' value='<?php if (isset($type)) echo $type ?>' tabindex=102
                        onfocus="javascript:toggleShowDiv('typeTooltip')"
                        onblur="javascript:toggleShowDiv('typeTooltip')" />
                <div id='typeTooltip'  style='display:none;visibility:visible' class='ToolTip'>
                        <img src='images/icons/error.png' alt='Tip' border='0' />
                        <?php echo $l['Tooltip']['typeTooltip'] ?>
                </div>
		</li>

                <li class='fieldset'>
		<label for='RecommendedOP' class='form'><?php echo $l['all']['RecommendedOP'] ?></label>
		<input name='RecommendedOP' type='text' id='RecommendedOP' value='<?php if (isset($RecommendedOP)) echo $RecommendedOP ?>' tabindex=103
                        onfocus="javascript:toggleShowDiv('RecommendedOPTooltip')"
                        onblur="javascript:toggleShowDiv('RecommendedOPTooltip')" />
                <div id='RecommendedOPTooltip'  style='display:none;visibility:visible' class='ToolTip'>
                        <img src='images/icons/error.png' alt='Tip' border='0' />
                        <?php echo $l['Tooltip']['RecommendedOPTooltip'] ?>
                </div>
		</li>

                <li class='fieldset'>
		<label for='RecommendedTable' class='form'><?php echo $l['all']['RecommendedTable'] ?></label>
		<input name='RecommendedTable' type='text' id='RecommendedTable' value='<?php if (isset($RecommendedTable)) echo $RecommendedTable ?>' tabindex=104
                        onfocus="javascript:toggleShowDiv('RecommendedTableTooltip')"
                        onblur="javascript:toggleShowDiv('RecommendedTableTooltip')" />
                <div id='RecommendedTableTooltip'  style='display:none;visibility:visible' class='ToolTip'>
                        <img src='images/icons/error.png' alt='Tip' border='0' />
                        <?php echo $l['Tooltip']['RecommendedTableTooltip'] ?>
                </div>
		</li>

                <li class='fieldset'>
		<label for='RecommendedTooltip' class='form'><?php echo $l['all']['RecommendedTooltip'] ?></label>
		<input name='RecommendedTooltip' type='text' id='RecommendedTooltip' value='<?php if (isset($RecommendedTooltip)) echo $RecommendedTooltip ?>' tabindex=105
                        onfocus="javascript:toggleShowDiv('RecommendedTooltipTooltip')"
                        onblur="javascript:toggleShowDiv('RecommendedTooltipTooltip')" />
                <div id='RecommendedTooltipTooltip'  style='display:none;visibility:visible' class='ToolTip'>
                        <img src='images/icons/error.png' alt='Tip' border='0' />
                        <?php echo $l['Tooltip']['RecommendedTooltipTooltip'] ?>
                </div>
		</li>

	
                <li class='fieldset'>
                <br/>
                <hr><br/>
                <input type='submit' name='submit' value='<?php echo $l['buttons']['apply'] ?>' tabindex=10000
			class='button' />
		</li>

		</ul>
	</fieldset>

				</form>

<?php
	include('include/config/logging.php');
?>
		
		</div>
		
		<div id="footer">
		
								<?php
        include 'page-footer.php';
?>

		
		</div>
		
</div>
</div>


</body>
</html>





