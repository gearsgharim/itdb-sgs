<SCRIPT LANGUAGE="JavaScript"> 

  $(document).ready(function() {
    $('input#invoicefilter').quicksearch('table#invoicelisttbl tbody tr');
    $('input#itemsfilter').quicksearch('table#itemslisttbl tbody tr');
    $('input#softfilter').quicksearch('table#softwarelisttbl tbody tr');
    $('input#contrfilter').quicksearch('table#contrlisttbl tbody tr');

    $("#tabs").tabs();
    $("#tabs").show();

    $("#locationid").change(function() {
      var locationid=$(this).val();
      var locareaid=$('#locareaid').val();
      var dataString = 'locationid='+ locationid;//+'&locareaid='+'<?php echo $locareaid?>';
      //var dataString2 = 'locationid='+ locationid+'&locareaid='+locareaid;

      $.ajax ({
	  type: "POST",
	  url: "php/locarea_options_ajax.php",
	  data: dataString,
	  cache: false,
	  success: function(html) {
	    $("#locareaid").html(html);
	  }
      });

      $.ajax ({
	  type: "POST",
	  url: "php/racks_perlocarea_ajax.php",
	  data: dataString,
	  cache: false,
	  success: function(html) {
	    $("#rackid").html(html);
	  }
      });



    });

  });

</SCRIPT>
<?php 

if (!isset($initok)) {echo "do not run this script directly";exit;}


if ($id!="new") {
  //get current item data
  $id=$_GET['id'];
  $sql="SELECT * FROM itemsrepair WHERE id='$id'";
  $sth=db_execute($dbh,$sql);
  $item=$sth->fetchAll(PDO::FETCH_ASSOC);
}


$sql="SELECT * FROM itemtypes order by typedesc";
$sth=$dbh->query($sql);
$itypes=$sth->fetchAll(PDO::FETCH_ASSOC);

for ($i=0;$i<count($itypes);$i++) {
  $typeid2name[$itypes[$i]['id']]=$itypes[$i]['typedesc'];
}

$sql="SELECT * FROM users order by upper(username)";
$sth=$dbh->query($sql);
$userlist=$sth->fetchAll(PDO::FETCH_ASSOC);

$sql="SELECT * FROM locations order by name";
$sth=$dbh->query($sql);
$locations=$sth->fetchAll(PDO::FETCH_ASSOC);



//$sql="SELECT * FROM racks"; $sth=$dbh->query($sql); $racks=$sth->fetchAll(PDO::FETCH_ASSOC);

$sql="SELECT id,title,type FROM agents order by title";
$sth=db_execute($dbh,$sql);
while ($r=$sth->fetch(PDO::FETCH_ASSOC)) $agents[$r['id']]=$r;

$sql="SELECT * FROM statustypes";
$sth=$dbh->query($sql);
$statustypes=$sth->fetchAll(PDO::FETCH_ASSOC);



$sql="SELECT itemsrepair.* from itemsrepair,itemtypes where ".
  " (itemtypes.typedesc like '%switch%' or itemtypes.typedesc like '%router%' ) ".
  " and itemtypes.id=itemsrepair.itemtypeid ";
$sth=$dbh->query($sql);
$netitems=$sth->fetchAll(PDO::FETCH_ASSOC);


//change displayed form items in input fields
if ($id=="new") {
  $caption=t("Add New Repair & Service Item");
  foreach ($formvars as $formvar){
    $$formvar="";
  }
  $d="";
  //$mend="";
}
//if editing, fill in form with data from supplied item id
else if ($action=="editrepair") {
  $caption=t("Item Data Repair")." ($id)";
  foreach ($formvars as $formvar){
    $$formvar=$item[0][$formvar];
  }
  //seconds from 1970
  $d=strlen($item[0]['purchasedate'])?date($dateparam,$item[0]['purchasedate']):"";
}
?>

<h1><?php echo $caption?></h1>
<?php echo $disperr;?>

<!-- our error errcontainer -->
<div class='errcontainer ui-state-error ui-corner-all' style='padding: 0 .7em;width:700px;margin-bottom:3px;'>
	<p><span class='ui-icon ui-icon-alert' style='float: left; margin-right: .3em;'></span>
	<h4><?php te("There are errors in your form submission, please see below for details");?>.</h4>
	<ol>
		<li><label for="itemtypeid" class="error"><?php te("Please select item type");?></label></li>
		<li><label for="ispart" class="error"><?php te("Please specify if this item is a part of another");?></label></li>
		<li><label for="manufacturerid" class="error"><?php te("Manufacturer is missing");?></label></li>
		<li><label for="model" class="error"><?php te("Specify model");?></label></li>
		<li><label for="userid" class="error"><?php te("Specify user responsible for this item");?></label></li>
		<li><label for="division" class="error"><?php te("Specify division of user responsible for this item");?></label></li>
	</ol>
</div>


 
<div id="tabs">

  <ul>
    <li><a href="#tab1"><span><?php te("Repair & Service Item Data");?></span></a></li>
   
  </ul>

<div id="tab1" class="tab_content">

  <form class='frm1' enctype='multipart/form-data' method=post name='additmfrm' id='mainform'>

  <table border='0' class=tbl1 >
  <tr>
  <td class='tdtop'>
    <table border='0' class=tbl2>
    <tr><td colspan=2><h3><?php te("Intrinsic Properties");?></h3></td></tr>

    <tr>
    <td class='tdt'><?php te("Item Type");?>:<sup class='red'>*</sup></td>
    <td title='<?php te("Populate list from the Item Types menu");?>'>

    <?php 
    echo "\n<select class='mandatory' validate='required:true' name='itemtypeid'>\n";
    echo "<option value=''>Select</option>\n";
    for ($i=0;$i<count($itypes);$i++) {
      $dbid=$itypes[$i]['id']; $itype=$itypes[$i]['typedesc']; $s="";
      if ($itemtypeid=="$dbid") $s=" SELECTED ";
      echo "<option $s title='id=$dbid' value='$dbid'>$itype</option>\n";
    }
    ?>
      </select>
      </td>
      </tr>

      <tr>

    <?php 
    //ispart
    $y="";$n="";
    if ($ispart=="1") {$y="checked";$n="";}
    if ($ispart=="0") {$n="checked";$y="";}

    ?>
      <td class='tdt'><?php te("Is Part");?>:<sup class='red'>*</sup></td>
      <td title='Select yes for parts/components'>
      <div class='mandatory'>
	<input  validate='required:true' <?php echo $y?> class='radio' type=radio name='ispart' value='1'><?php te("Yes");?>
	<input  class='radio' type=radio <?php echo $n?> name='ispart' value='0'><?php te("No");?>
      </div>
      </td>
      </tr>
      <tr>

   

    


    <?php 
    //manufacturer
    ?>

      <tr>
      <td class='tdt'>

    <?php   if (is_numeric($manufacturerid)) 
      echo "<a title='Edit selected manufacturer (agent)' href='$scriptname?action=editagent&amp;id=$manufacturerid'><img src='images/edit.png'></a> "; ?>
      <?php te("Manufact.");?><sup class='red'>*</sup>:</td>

      <td title='<?php te("Populated from H/W Manufacturers defined in agents menu");?>'>

       <select validate='required:true' class='mandatory' name='manufacturerid'>
       <option value=''><?php te("Select");?></option>
      <?php 
	foreach ($agents as $a) {
	  if (!($a['type']&8)) continue;
	  $dbid=$a['id']; 
	  $atype=$a['title']; $s="";
	  if (isset($manufacturerid) && $manufacturerid==$a['id']) $s=" SELECTED ";
	  echo "<option $s value='$dbid' title='$dbid'>$atype</option>\n";
	}
	echo "</select>\n";
      ?>
      </td>

      </tr>

      <tr> <td class='tdt'><?php te("Model");?><sup class='red'>*</sup>:</td><td><input type=text validate='required:true' class='mandatory' value="<?php echo $model?>" name='model'></td> </tr>

      <tr>

    

    </select>
    </td>

      </tr>

      <tr> <td class='tdt'><?php te("S/N");?>:</td><td title='<?php te("show also this text on serial number");?>'><input type=text value='<?php echo $sn?>' name='sn'></td> </tr>
      <tr> <td class='tdt'><?php te("Asset Number");?>:</td><td title='<?php te("show also this text on printable asset number");?>'><input type='text' value="<?php echo $label?>" name='label'></td> </tr>
      <tr>

    <?php 

    //dell service tag
    if (isset($manufacturerid)) {
      $st=getagenturlbytag($manufacturerid,"service");
      if (strlen($st)) $st="<a target=_blank href='$st'>Service Tag</a>";
      else $st="Service Tag";
    }
    ?>
      <td class='tdt'><?php echo $st?></td><td><input type=text value='<?php echo $sn3?>' name='sn3'></td>
      </tr>

      

    
      </table>
    </td>

    <td class='tdtop'>

      <table border='0' class=tbl2><!-- Usage -->
      <tr><td colspan=2 ><h3><?php te("Usage");?></h3></td></tr>

      <tr>

      


      <?php 
      //user
      ?>

      <tr>
      <td class='tdt'><?php te("User");?><sup class='red'>*</sup>:</td><td title='<?php te("User responsible for this item");?>'>
      <select validate='required:true' class='mandatory' name='userid'>
      <option value=''><?php te("Select User");?></option>
      <?php 
      for ($i=0;$i<count($userlist);$i++) {
	$dbid=$userlist[$i]['id']; $itype=$userlist[$i]['username']; $s="";
	if ($userid==$dbid) $s=" SELECTED ";
	//echo "<option $s value='$dbid'>".sprintf("%02d",$dbid)."-$itype</option>\n";
	echo "<option $s value='$dbid'>$itype</option>\n";
      }
      ?>

      </select>
      </td>
      </tr>

	  <tr>
	
	<tr> <td class='tdt'><?php te("Division");?><sup class='red'>*</sup>:</td><td><input type=text validate='required:true' class='mandatory' value="<?php echo $division?>" name='division'></td> </tr>
      </tr>
	  
      <tr>
      <?php 
      //location
      ?>
      <td class='tdt' class='tdt'><?php te("Location");?>:</td>
      <td>
	<select id='locationid' name='locationid'>
	<option value=''><?php te("Select");?></option>
	<?php 
	foreach ($locations  as $key=>$location ) {
	  $dbid=$location['id']; 
	  $itype=$location['name'];
	  $s="";
	  if (($locationid=="$dbid")) $s=" SELECTED "; 
	  echo "    <option $s value='$dbid'>$itype</option>\n";
	}
	?>
	</select>

      </td>
      </tr>

      <tr>
      <?php 
      //area
      if (is_numeric($locationid)) {
	$sql="SELECT * FROM locareas WHERE locationid=$locationid order by areaname";
	$sth=$dbh->query($sql);
	$locareas=$sth->fetchAll(PDO::FETCH_ASSOC);
      } 
      else 
	$locareas=array();
      ?>
      <td class='tdt' class='tdt'><?php te("Area/Room");?>:</td>
      <td>
	<select id='locareaid' name='locareaid'>
	  <option value=''><?php te("Select");?></option>
	  <?php 
	  foreach ($locareas  as $key=>$locarea ) {
	    $dbid=$locarea['id']; 
	    $itype=$locarea['areaname'];
	    $s="";
	    if (($locareaid=="$dbid")) $s=" SELECTED "; 
	    echo "    <option $s value='$dbid'>$itype</option>\n";
	  }
	  ?>
     

	</select>

      </td>
      </tr>



      <tr>
      


      <tr>
      <td class='tdt'><?php te("Problem");?>:</td><td title='<?php te("Problem of Items");?>'>
       <textarea wrap='soft' class=tarea1 name='problem'><?php echo $problem?></textarea></td>
      </tr>

	  <td class='tdt'><?php te("Solution");?>:</td><td title='<?php te("Solution about problem of items");?>'>
       <textarea wrap='soft' class=tarea1 name='solution'><?php echo $solution?></textarea></td>
      </tr>
	  
	  <td class='tdt'><?php te("Replacement");?>:</td><td title='<?php te("Replacement of part items");?>'>
       <textarea wrap='soft' class=tarea1 name='replacement'><?php echo $replacement?></textarea></td>
      </tr>
	  
	  

      </table><!--/usage-->


    </td>
    <td class='tdtop'>


      <table border='0' class=tbl2> <!-- 2-Warranty & Support -->
      <tr><td colspan=2><h3><?php te("Date Repair");?></h3></td></tr>
      <tr>
      <?php  
      $sdate=strlen($r["repairdate"])?date($dateparam,$r['repairdate']):"";
      ?>
      <td class="tdt"><?php te("Repair Date");?>:</td> <td><input  class='dateinp mandatory' id=repairdate size=10 title='<?php echo $datetitle?>' type=text id='repairdate' name='repairdate' value='<?php echo $sdate?>'> <!-- id is for validation to work with date selectors -->
      </td>
      </tr>

      <tr>
      <?php  
      $edate=strlen($r["finishdate"])?date($dateparam,$r['finishdate']):"";
      ?>
      <td class="tdt"><?php te("Finish Repair Date");?>:</td> <td><input  class='dateinp mandatory' size=10 title='<?php echo $datetitle?>' type=text id='finishdate' name='finishdate' value='<?php echo $edate?>'>
      <tr>
	  <tr> <td class='tdt'><?php te("Status Repair");?>:</td><td title='<?php te("show status repair of items");?>'><input type='text' value="<?php echo $statusrepair?>" name='statusrepair'></td> </tr>

	  </select>
	  </td>
       </tr>
     </table>

    </td>


 












<table><!-- save buttons -->
<tr>
<td style='text-align: center' colspan=1><button type="submit"><img src="images/save.png" alt="Save" > <?php te("Save");?></button></td>
<?php 
if ($id!="new") {
  echo "\n<td style='text-align: center' ><button type='button' onclick='javascript:delconfirm2(\"Item {$_GET['id']}\",\"$scriptname?action=$action&amp;delid={$_GET['id']}\");'>".
       "<img title='Delete' src='images/delete.png' border=0>".t("Delete")."</button></td>\n";

  echo "\n<td style='text-align: center' ><button type='button' onclick='javascript:cloneconfirm(\"Item {$_GET['id']}\",\"$scriptname?action=$action&amp;cloneid={$_GET['id']}\");'>".
       "<img  src='images/copy.png' border=0>". t("Clone")."</button></td>\n";
} 
else 
  echo "\n<td>&nbsp;</td>";
?>
 
</tr>
</table>

<input type=hidden name=action value='<?php echo $_GET["action"]?>'>
</form>


