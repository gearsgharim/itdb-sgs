<script>
$(document).ready(function() {
  $('input#repfilter').quicksearch('table#reptbl tbody tr');
  
});


</script>
	
<?php 
if (!isset($initok)) {echo "do not run this script directly";exit;}




if (isset($sqlsrch) && !empty($sqlsrch)) 
  $where = "where sql like '%$sqlsrch%'";
else { 
 $sqlsrch="";
 $where="";
}


$reports=array (
'itemperagent' => t('Number of items per Manufacturer (Agent)'),
'itemperdivision' => t('Number of items per Division'),
'itemperstatus' => t('Number of items per Status'),
'itempertype' => t('Number of items per Type Items (Agent)'),
'itempermodel' => t('Number of items per Model Items (Agent)'),
'softwareperagent' => t('Number of installed Software per Manufacturer (Agent)'),
'invoicesperagent' => t('Number of invoices per Vendor (Agent)'),
'itemsperlocation' => t('Number of items per Location'),
'percsupitems' => t('Number of Items under support'),
'itemlistrepair' => t('Number of Items Repair & Service'),
'itemlistperlocation' => t('Item list per location'),
'itemsendwarranty' => t('Items with warranty end date close to (before or after) today'),
'allips' => t('List items with defined IPv4 numbers'),
'noinvoice' => t('Items without invoices'),
'software' => t('Items with License Software'),
'nosoftware' => t('Items without License Software'),
'nolocation' => t('Items without location'),
'nolabel' => t('Items without Asset Number'),
'depreciation3' => t('Item depreciation value 3 years'),
'depreciation5' => t('Item depreciation value 5 years'),
);

$printreports=array (
'itemperagent' => t('Number of items per Manufacturer (Agent)'),
'itemperdivision' => t('Number of items per Division'),
'itempertype' => t('Number of items per Type Items (Agent)'),
'itempermodel' => t('Number of items per Model Items (Agent)'),
'softwareperagent' => t('Number of installed Software per Manufacturer (Agent)'),
'invoicesperagent' => t('Number of invoices per Vendor (Agent)'),
'itemsperlocation' => t('Number of items per Location'),
'percsupitems' => t('Number of Items under support'),
'itemlistrepair' => t('Number of Items Repair & Service'),
'itemlistperlocation' => t('Item list per location'),
'itemsendwarranty' => t('Items with warranty end date close to (before or after) today'),
'allips' => t('List items with defined IPv4 numbers'),
'noinvoice' => t('Items without invoices'),
'software' => t('Items with Lioense Software'),
'nosoftware' => t('Items without Lioense Software'),
'nolocation' => t('Items without location'),
'nolabel' => t('Items without Asset Number'),
'depreciation3' => t('Item depreciation value 3 years'),
'depreciation5' => t('Item depreciation value 5 years'),
);
?>
<h1><?php te("Reports");?></h1>

<div style='width:100%;clear:both;'>

  <div style='float:left;text-align:left;padding:5px;height:350px;overflow-y:auto;width:300px;border:1px solid #cecece;'>
  <h2><?php te("Select Report");?></h2>
  <ul>
  


<?php 
  $curdesc="";
  foreach ($reports as $q => $desc) {
    if ($q==$query) {
      echo "<li><b><a href='$scriptname?action=$action&amp;query=$q'>$desc</a></b></li>";
      $curdesc=$desc;
    }
    else
      echo "<li><a href='$scriptname?action=$action&amp;query=$q'>$desc</a></li>";
  }
?>

  </ul>
  </div>
  <div id="chartdiv" style="padding:5px;float:left;height:350px;width:640px;border:1px solid #cecece; "></div>

</div>

<div style='width:100%;clear:both;'>

<?php 
switch ($query) {

  case "depreciation5":
    $sql="select items.id as ID,typedesc as type, agents.title as manufacturer ,model, strftime('%Y-%m-%d', purchasedate,'unixepoch') AS PurchaseDate, ".
	     "purchprice as PurchasePrice, ".
		 " cast( ((strftime('%s','now') - purchasedate)/(60*60*24*30.4)*(purchasedate AND 1)) AS INTEGER)  as Months , ".
		 " (purchprice-purchprice/60*cast( ((strftime('%s','now') - purchasedate)/(60*60*24*30.4)*(purchasedate AND 1)) AS INTEGER))  as CurrentValue  ".
         " FROM items,itemtypes,agents ".
         " WHERE agents.id=manufacturerid AND itemtypes.id=items.itemtypeid ";
    $editlnk="$scriptname?action=edititem&id";
  break;


  case "depreciation3":
    $sql="select items.id as ID,typedesc as type, agents.title as manufacturer ,model, strftime('%Y-%m-%d', purchasedate,'unixepoch') AS PurchaseDate, ".
	     "purchprice as PurchasePrice, ".
		 " cast( ((strftime('%s','now') - purchasedate)/(60*60*24*30.4)*(purchasedate AND 1)) AS INTEGER)  as Months , ".
		 " (purchprice-purchprice/36*cast( ((strftime('%s','now') - purchasedate)/(60*60*24*30.4)*(purchasedate AND 1)) AS INTEGER))  as CurrentValue  ".
         " FROM items,itemtypes,agents ".
         " WHERE agents.id=manufacturerid AND itemtypes.id=items.itemtypeid ";
    $editlnk="$scriptname?action=edititem&id";
  break;


  case "noinvoice":
    $sql="select items.id as ID,typedesc as type, agents.title as manufacturer ,model, strftime('%Y-%m-%d', purchasedate,'unixepoch') AS PurchaseDate".
         " FROM items,itemtypes,agents ".
         " WHERE agents.id=manufacturerid AND itemtypes.id=items.itemtypeid AND items.ID not in (select itemid from item2inv)";
    $editlnk="$scriptname?action=edititem&id";
  break;
  
  
  case "nosoftware":
    $sql="select items.id as ID,typedesc as type, agents.title as manufacturer, sn as serial, model, statusdesc as status, username, division".
         " FROM items,itemtypes,agents,statustypes, users ".
         " WHERE agents.id=manufacturerid AND itemtypes.id=items.itemtypeid AND statustypes.id=status AND users.id=userid AND users.username=username AND items.division=division AND items.ID not in (select itemid from item2soft)";
    $editlnk="$scriptname?action=edititem&id";
  break;
  
   case "software":
    $sql="select items.id as ID,typedesc as type, agents.title as manufacturer, sn as serial, model, statusdesc as status, username, division".
         " FROM items,itemtypes,agents,statustypes, users ".
         " WHERE agents.id=manufacturerid AND itemtypes.id=items.itemtypeid AND statustypes.id=status AND users.id=userid AND users.username=username AND items.division=division AND items.ID in (select itemid from item2soft)";
    $editlnk="$scriptname?action=edititem&id";
  break;

  
  case "nolocation":
    $sql="select items.id as ID,typedesc as type, agents.title as manufacturer ,model ".
         " FROM items,itemtypes,agents ".
         " WHERE agents.id=manufacturerid AND itemtypes.id=items.itemtypeid AND (locationid='' OR locationid is null)";
    $editlnk="$scriptname?action=edititem&id";
  break;
  
  case "nolabel":
    $sql="select items.id as ID,typedesc as type, agents.title as manufacturer ,sn as serial,model , statusdesc as status ,username as user ,division".
         " FROM items,itemtypes,agents,statustypes, users ".
         " WHERE agents.id=manufacturerid AND itemtypes.id=items.itemtypeid AND statustypes.id=status AND users.id=items.userid AND items.division=division AND (label='' OR label is null)";
    $editlnk="$scriptname?action=edititem&id";
  break;



  case "allips":
    $sql="select items.id as ID,ipv4, typedesc as type, agents.title as manufacturer, model, dnsname, sn, name as locations, areaname as locareas  ".
         " FROM items,itemtypes,agents,locations,locareas ".
         " WHERE  agents.id=manufacturerid AND itemtypes.id=items.itemtypeid AND locations.id=items.locationid AND locareas.id=items.locareaid AND ipv4 <> '' order by ipv4";
    $editlnk="$scriptname?action=edititem&id";
  break;

  case "itemsperlocation":
    $sql="select count(*) as TotalCount, ".
         " locations.name as Location  ".
         " FROM items,agents,locations ".
         " WHERE agents.id=items.manufacturerid AND items.locationid=locations.id GROUP BY locationid order by totalcount desc;";
    $editlnk="$scriptname?action=editlocations";
    $graph['type']="pie";
    $graph['colx']="Location";
    $graph['coly']="TotalCount";
    $graph['limit']=15;
  break;

  
  case "itemperstatus":
    $sql="select count(*) as TotalCount, ".
         " statustypes.statusdesc as StatusType  ".
         " FROM items,agents,statustypes ".
         " WHERE agents.id=items.manufacturerid AND items.status=statustypes.id GROUP BY status order by totalcount desc;";
    $editlnk="$scriptname?action=editstatus";
    $graph['type']="pie";
    $graph['colx']="StatusType";
    $graph['coly']="TotalCount";
    $graph['limit']=15;
  break;
  
  
  case "itemperdivision":
    $sql="select count(*) as TotalCount, ".
         " items.division as Division  ".
         " FROM items,agents,users ".
         " WHERE agents.id=items.manufacturerid AND items.userid=users.id GROUP BY division order by totalcount desc;";
    $editlnk="$scriptname?action=editdivision";
    $graph['type']="pie";
    $graph['colx']="Division";
    $graph['coly']="TotalCount";
    $graph['limit']=15;
  break;
  
   case "itempertype":
    $sql="select count(*) as TotalCount, ".
         " itemtypes.typedesc as ItemType  ".
         " FROM items,agents,itemtypes ".
         " WHERE agents.id=items.manufacturerid AND items.itemtypeid=itemtypes.id GROUP BY itemtypeid order by totalcount desc;";
    $editlnk="$scriptname?action=edititem";
    $graph['type']="pie";
    $graph['colx']="ItemType";
    $graph['coly']="TotalCount";
    $graph['limit']=15;
  break;
  
  case "itempermodel":
    $sql="select count(*) as TotalCount, ".
         " typedesc as Type, agents.title as Manufacturer ,Model  ".
         " FROM items,agents,itemtypes ".
         " WHERE agents.id=items.manufacturerid AND items.itemtypeid=itemtypes.id GROUP BY Model order by totalcount desc;";
    $editlnk="$scriptname?action=edititem";
    $graph['type']="pie";
    $graph['colx']="Manufacturer";
    $graph['coly']="TotalCount";
    $graph['limit']=15;
  break;
  
  
  case "itemlistrepair":
    $sql="select itemsrepair.id as ID,typedesc as type, agents.title as manufacturer ,model, username, division, statusrepair".
         " FROM itemsrepair,itemtypes,agents,users ".
         " WHERE agents.id=manufacturerid AND itemtypes.id=itemsrepair.itemtypeid AND users.id=userid AND users.username=username AND itemsrepair.division=division AND itemsrepair.ID not in (select itemid from item2soft)";
    $editlnk="$scriptname?action=editrepair&id";
  break;

  
  
  case "itemlistperlocation":
    $sql="select items.id as ID, typedesc as Type, agents.title as Manufacturer, Model, SN, ".	
         " locations.name  as Location  ".
         " FROM items,agents,locations,itemtypes ".
         " WHERE itemtypes.id=items.itemtypeid AND agents.id=items.manufacturerid ".
         " AND items.locationid=locations.id order by locationid,typedesc desc;";
    $editlnk="$scriptname?action=editlocations";
    $graph['type']="pie";
    $graph['colx']="Location";
    $graph['coly']="TotalCount";
    $graph['limit']=15;
  break;


  case "itemperagent":
    $sql="select count(*) as TotalCount,agents.title as Manufacturer, agents.id as ID from items,agents ".
         "WHERE agents.id=items.manufacturerid group by manufacturerid order by totalcount desc;";
    $editlnk="$scriptname?action=editagent&id";
    $graph['type']="pie";
    $graph['colx']="Manufacturer";
    $graph['coly']="TotalCount";
    $graph['limit']=15;
  break;
  
    

  case "softwareperagent":
    $sql="select count(*) as totalcount,agents.title as Agent, agents.id as ID from software,agents ".
         "WHERE agents.id=software.manufacturerid group by manufacturerid order by totalcount desc;";
    $editlnk="$scriptname?action=editagent&id";
    $graph['type']="pie";
    $graph['colx']="Agent";
    $graph['coly']="totalcount";
    $graph['limit']=15;
  break;

  case "invoicesperagent":
    $sql="select count(*) as totalcount,agents.title as Agent, agents.id as ID from invoices,agents ".
         "WHERE agents.id=invoices.vendorid group by vendorid order by totalcount desc;";
    $editlnk="$scriptname?action=editagent&id";
    $graph['type']="pie";
    $graph['colx']="Agent";
    $graph['coly']="totalcount";
    $graph['limit']=15;
  break;

  case "itemsendwarranty":
    $t=time();
    $sql="select items.id as ID,ipv4, typedesc as type, agents.title as manufacturer, model, dnsname, label,  ".
         " (purchasedate+warrantymonths*30*24*60*60-$t)/(60*60*24) RemainingDays FROM items,itemtypes,agents ".
         " WHERE  agents.id=manufacturerid AND itemtypes.id=items.itemtypeid  AND RemainingDays>-360 AND RemainingDays<360 order by RemainingDays ";
    $editlnk="$scriptname?action=edititem&id";
  break;

  case "percsupitems":
    $sql="select 
    'NotExpired' as Type, (select count(id) from items where ((purchasedate+warrantymonths*30*24*60*60-strftime(\"%s\"))/(60*60*24)) >1 AND purchasedate>0 AND warrantymonths>0) as Items
    UNION SELECT
    'Expired' as Type, (select count(id) from items where ((purchasedate+warrantymonths*30*24*60*60-strftime(\"%s\"))/(60*60*24)) <=1 AND purchasedate>0 AND warrantymonths>0) as Items
    UNION SELECT
    'Undefined' as Type, (select count(id) from items where purchasedate=0 OR purchasedate is null OR warrantymonths=0 OR warrantymonths is null) as Items
    UNION SELECT 'Total' as Type, (select count(id) from items)  as Items
    ";
    $graph['type']="pie";
    $graph['colx']="Type";
    $graph['coly']="Items";
    $graph['limit']=15;
  break;

  default:
   exit;
}
?>


<div style='padding-top:15px;clear:both'>
<h2><?php echo $curdesc?></h2>
  <input style='color:#909090' id="repfilter" name="repfilter" class='filter' 
       value='Filter' onclick='this.style.color="#000"; this.value=""' size="20">
  <table id='reptbl' class='sortable' >

  <?php 


  /// make db query
  $sth=db_execute($dbh,$sql);

  $plot_param="";
  if (isset($graph['type']))
    $plot_param="[";

  /// display results
  $row=0;
  while ($r=$sth->fetch(PDO::FETCH_ASSOC)) {

    echo "\n<tr>";

    if (!$row) { //header
      echo "\n\t<th>No</th>";
      foreach($r as $k => $v) {
	echo "\n\t<th>$k</th>";
      }
      echo "\n</tr>\n<tr>";
    }
    
    if (($graph['type']=='pie') && $graph['limit']-->0) {
      if (!($r[$graph['colx']]=='Total'))  { //don't include totals in pies
	$plot_param.="['".$r[$graph['colx']]."',".$r[$graph['coly']]."],";
      }
    }

    echo "\n\t<td>".($row+1)."</td>";
    foreach($r as $k => $v) {   //values
      if ($k=="ID")
	echo "\n\t<td><a class='editid' href='$editlnk=$v'>$v</a></td>";
      else {
	echo "\n\t<td>$v</td>";
      }
    } 
    echo "</tr>\n";
    $row++;

  }

  if (isset($graph['type'])) {
    $plot_param[strlen($plot_param)-1]=" "; //eat last comma
    $plot_param.="];\n";
  }

//echo "plot_param=".$plot_param;
  ?>
  </table>
</div>

</div>

<script>

<?php
if (strlen($plot_param)) {
?>
$(document).ready(function() {
  line1 = <?php  echo $plot_param; ?> ;
  $.jqplot.config.enablePlugins = true;
  $.jqplot.config.catchErrors = true;
  plot1 = $.jqplot('chartdiv', [line1], {
      //title: 'Default Pie Chart',
      seriesDefaults:{renderer:$.jqplot.PieRenderer,rendererOptions:{sliceMargin:3}},
      grid:{background:'#ffffff', borderWidth:0,shadow:false},
      legend:{show:true,rowSpacing : '0.1em'}
  });
});

<?php
}
?>
</script>



