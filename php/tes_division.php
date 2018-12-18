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
         " users.userdesc as Division  ".
         " FROM items,agents,users ".
         " WHERE agents.id=items.manufacturerid AND items.division=users.userdesc GROUP BY division order by totalcount desc;";
    $editlnk="$scriptname?action=editdivision";
    $graph['type']="pie";
    $graph['colx']="Division";
    $graph['coly']="TotalCount";
    $graph['limit']=15;
  break;
  