<?php 


if (!isset($initok)) {echo "do not run this script directly";exit;}

//form variables
$formvars=array("itemtypeid","division","manufacturerid","label",
  "model","sn","sn2","sn3","locationid","locareaid",
  "userid","comments","history","history2","history3","ispart",
  "problem","solution","replacement","repairdate","finishdate","statusrepair");

/* delete item */
if (isset($_GET['delid'])) { 
  //first handle file associations
  //get a list of files associated with us
  $f=itemid2files($delid,$dbh);
  for ($fids=array(),$c=0;$c<count($f);$c++) {
    array_push($fids,$f[$c]['id']);
  }

  

  
  //delete item 
  $sql="DELETE from itemsrepair where id=".$_GET['delid'];
  $sth=db_exec($dbh,$sql);

  echo "<script>document.location='$scriptname?action=listrepair'</script>";
  echo "<a href='$scriptname?action=listrepair'>Go here</a></body></html>"; 
  exit;
}

if (isset($_GET['cloneid'])) { 
  $cols="itemtypeid , division, manufacturerid ,model,".
        "comments,ispart, locationid ,label,problem , solution , replacement , repairdate , finishdate , statusrepair";

  $sql="insert into itemsrepair ($cols) ".
     " select $cols from itemsrepair ".
     " where id={$_GET['cloneid']}";
  $sth=db_exec($dbh,$sql);

  $lastid=$dbh->lastInsertId();
  $newid=$lastid;
  echo "<script>document.location='$scriptname?action=editrepair&id=$newid'</script>";
  echo "<a href='$scriptname?action=editrepair&amp;id=$newid'>Go here</a></body></html>"; 
  exit;


  $sql="SELECT itemsrepair.userid,users.username from users,itemsrepair where userid=users.id and itemsrepair.id='$id'";
  $sth=db_execute($dbh,$sql);
  $curruser=$sth->fetchAll(PDO::FETCH_ASSOC);
  $curruser=$curruser[0];

  $sql="SELECT username from users where id=$userid";
  $sth=db_execute($dbh,$sql);
  $newuser=$sth->fetchAll(PDO::FETCH_ASSOC);
  $newuser=$newuser[0];

  if ($userid!=$curruser['userid']) { //changed user
    $str="Updated user from {$curruser['username']} to {$newuser['username']}";
    $sql="INSERT into actions (itemid, actiondate,description,invoiceinfo,isauto,entrydate) values ".
	 "($id,".time().",'$str' , '',1,".time().")";
    db_exec($dbh,$sql);
  }

  $sql="UPDATE itemsrepair set $set WHERE id=$id";
  db_exec($dbh,$sql); 

  //Add new action entry
  //if not exists already for today
  $sql="SELECT itemid,entrydate,description, isauto FROM actions WHERE itemid='$id' ORDER BY entrydate DESC LIMIT 1";
  $sth=db_execute($dbh,$sql);
  $laction=$sth->fetchAll(PDO::FETCH_ASSOC);

  $upstr="Updated by {$_COOKIE["itdbuser"]}";
  $ldesc=$laction[0]['description'];
  $ldate=date("Ymd",$laction[0]['entrydate']);
  $ndate=date("Ymd",time());


  if (($upstr != $ldesc) && ($ldate != $ndate) ) {
    //add new action entry
    $sql="INSERT into actions (itemid, actiondate,description,invoiceinfo,isauto,entrydate) values ".
	 "($id,".time().",'$upstr' , '',1,".time().")";
    db_exec($dbh,$sql);
//echo "HERE:($upstr,$ldesc), ($ldate,$ndate);";
  }

  //update item links
  //remove old links for this object
  $dbh->beginTransaction();
  $sql="delete from itemlink2 where itemid1=$id";
  db_exec($dbh,$sql);
  //add new links for each checked checkbox
  for ($i=0;$i<count($itlnk);$i++) {
    $sql="INSERT into itemlink2 (itemid1, itemid2) values ($id,".$itlnk[$i].")";
    db_exec($dbh,$sql);
  }
  //update invoice links
  //remove old links for this object
  $sql="delete from item2inv where itemid=$id";
  db_exec($dbh,$sql);
  //add new links for each checked checkbox
  for ($i=0;$i<count($invlnk);$i++) {
    $sql="INSERT into item2inv (itemid, invid) values ($id,".$invlnk[$i].")";
    db_exec($dbh,$sql);
  }
  $dbh->commit();

  //update software - item links 
  //remove old links for this object
  $sql="delete from item2soft where itemid=$id";
  db_exec($dbh,$sql);
  //add new links for each checked checkbox
  for ($i=0;$i<count($softlnk);$i++) {
    $sql="INSERT into item2soft (itemid,softid) values ($id,".$softlnk[$i].")";
    db_exec($dbh,$sql);
  }

  //update contract - item links 
  //remove old links for this object
  $sql="delete from contract2item where itemid=$id";
  db_exec($dbh,$sql);
  //add new links for each checked checkbox
  for ($i=0;$i<count($contrlnk);$i++) {
    $sql="INSERT into contract2item (itemid,contractid) values ($id,".$contrlnk[$i].")";
    db_exec($dbh,$sql);
  }
} //if updating
/* add new item */
elseif (isset($_POST['itemtypeid']) && ($_GET['id']=="new")&&isvalidfrm()) {

  //ok, save new item
  //find a new ID 
  //handle file uploads
  $photofn="";
  $manualfn="";

  foreach($_POST as $k => $v) { if (!is_array($v)) ${$k} = (trim($v));}
  $purchasedate2=ymd2sec($purchasedate);// mktime(0, 0, 0, $x[1], $x[0], $x[2]);

  $mend=ymd2sec($maintend);

  if ($switchid=="") $switchid="NULL";
  if ($usize=="") $usize="NULL";
  if ($locationid=="") $locationid="NULL";
  if ($locareaid=="") $locareaid="NULL";
  if ($rackid=="") $rackid="NULL";
  if ($rackposition=="") $rackposition="NULL";
  if ($userid=="") $userid="NULL";
  $warrantymonths=(int)$warrantymonths;
  if (!$warrantymonths || !strlen($warrantymonths) || !is_integer($warrantymonths)) $warrantymonths="NULL";

  //// STORE DATA
  $sql="INSERT into itemsrepair (label, itemtypeid, manufacturerid, ".
  " model, sn, sn2, sn3, ".
  " userid, division, locationid, locareaid,  ".
  " comments,ispart, ".
  " problem, solution, replacement, repairdate, finishdate, statusrepair) VALUES ".
  " ('$label', '$itemtypeid', '$manufacturerid', ".
  " '$model', '$sn', '$sn2', '$sn3', ".
  " $userid, '$division', '$locationid', '$locareaid', ".
  " '". htmlspecialchars($comments,ENT_QUOTES,'UTF-8')  ."','$ispart', " .
    " '$problem', '$solution', '$replacement', '$repairdate', '$finishdate', '$statusrepair' ) ";

  //echo $sql."<br>";
  db_exec($dbh,$sql);

  $lastid=$dbh->lastInsertId();
  $id=$lastid;

  //add new links for each checked checkbox
  if (isset($_POST['itlnk'])) {
    $itlnk=$_POST['itlnk'];
    for ($i=0;$i<count($itlnk);$i++) {
      $sql="INSERT into itemlink2 (itemid1, itemid2) values ($lastid,".$itlnk[$i].")";
      db_exec($dbh,$sql);
    }
  }//add item links

  //add new links for each checked checkbox
  if (isset($_POST['invlnk'])) {
    $itlnk=$_POST['invlnk'];
    for ($i=0;$i<count($invlnk);$i++) {
      $sql="INSERT into item2inv (itemid, invid) values ($lastid,".$invlnk[$i].")";
      db_exec($dbh,$sql);
    }
  }//add invoice links

  //update software - item links 
  //remove old links for this object
  $sql="DELETE from item2soft where itemid=$lastid";
  db_exec($dbh,$sql);
  //add new links for each checked checkbox
  for ($i=0;$i<count($softlnk);$i++) {
    $sql="INSERT into item2soft (itemid,softid) values ($lastid,".$softlnk[$i].")";
    db_exec($dbh,$sql);
  }

  //update contract - item links 
  //remove old links for this object
  $sql="DELETE from contract2item where itemid=$lastid";
  db_exec($dbh,$sql);
  //add new links for each checked checkbox
  for ($i=0;$i<count($contrlnk);$i++) {
    $sql="INSERT into contract2item (itemid,contractid) values ($lastid,".$contrlnk[$i].")";
    db_exec($dbh,$sql);
  }


  //add new action entry
  $sql="INSERT into actions (itemid, actiondate,description,invoiceinfo,isauto,entrydate) values ".
       "($lastid,".time().",'Added by {$_COOKIE["itdbuser"]}' , '',1,".time().")";
  db_exec($dbh,$sql);

  print "\n<br><b>Added item <a href='$scriptname?action=editrepair&amp;id=$lastid'>$lastid</a></b><br>\n";
  if ($lastid) echo "<script>window.location='$scriptname?action=editrepair&id=$lastid'</script> "; //go to the new item

}//xxxadd new item

function isvalidfrm() {
global $disperr,$err,$_POST;
  //check for mandatory fields
  $err="";
  $disperr="";
  if ($_POST['itemtypeid']=="") $err.="Missing Item Type<br>";
  if ($_POST['userid']=="") $err.="Missing User<br>";
  if ($_POST['manufacturerid']=="") $err.="Missing manufacturer<br>";
  if (!isset($_POST['ispart'])) $err.="Missing 'Part' classification<br>";
  //if (!isset($_POST['status'])) $err.="Missing 'Status' classification<br>";
  if ($_POST['model']=="") $err.="Missing model<br>";
  if ($_POST['division']=="") $err.="Missing division user<br>";

  if (strlen($err)) {
      $disperr= "
      <div class='ui-state-error ui-corner-all' style='padding: 0 .7em;width:300px;margin-bottom:3px;'> 
	      <p><span class='ui-icon ui-icon-alert' style='float: left; margin-right: .3em;'></span>
	      <strong>Error: Item not saved, correct these errors:</strong><br><div style='text-align:left'>$err</div></p>
      </div>
      ";
    return 0;
  }
  return 1;
}

require('itemrepair2.php');
?>
