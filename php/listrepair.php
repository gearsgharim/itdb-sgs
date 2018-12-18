<script>
$(document).ready(function() {
	var oTable = $('#itemlisttbl').dataTable( {
                "sPaginationType": "full_numbers",
                "bJQueryUI": true,
                "iDisplayLength": 50,
		"aLengthMenu": [[10,20, 25, 50, 100, -1], [10,20, 25, 50, 100, "All"]],
                "bLengthChange": true,
                "bFilter": true,
                "bSort": true,
                "bInfo": true,
                //"sDom": '<"H"CTlpf>rt<"F"ip>',
                "sDom": '<"H"Tlpf>rt<"F"ip>',
                "oTableTools": {
                        "sSwfPath": "swf/copy_cvs_xls_pdf.swf"
/*

			"aButtons": [ {
			  "sExtends": "ajax",
			  "sButtonText": "Download CSV",
			  "fnClick": function () {
			    var iframe = document.createElement('iframe');
			    iframe.style.height = "0px";
			    iframe.style.width = "0px";
			    iframe.src = "/php/datatables_listitems_ajax_csv.php";
			    document.body.appendChild( iframe );
			  }
			  //"sAjaxUrl": "php/datatables_listitems_ajax_csv.php",
			} ]
*/
                },
		"aoColumnDefs": [ 
			{ "sWidth": "70px", "aTargets": [ 0 ] },
			{ "asSorting": [ "desc","asc" ], "aTargets": [ 0 ] },
			{ "sType": "title-numeric", "aTargets": [ 7 ] }
		],
		//"oColVis": { "buttonText": "+/-", },
		"bProcessing": true,
		"bServerSide": true,
		"sAjaxSource": "php/datatables_listrepair_ajax.php",
		//"sScrollY": "550px", "bScrollCollapse": true,
		"sScrollX": "100%",
		"sScrollXInner": "180%",
		"bScrollCollapse": true,
	} );

	jQuery.fn.dataTableExt.oSort['title-numeric-asc']  = function(a,b) {
		var x = a.match(/title="*(-?[0-9]+)/)[1];
		var y = b.match(/title="*(-?[0-9]+)/)[1];
		x = parseFloat( x );
		y = parseFloat( y );
		return ((x < y) ? -1 : ((x > y) ?  1 : 0));
	};

	jQuery.fn.dataTableExt.oSort['title-numeric-desc'] = function(a,b) {
		var x = a.match(/title="*(-?[0-9]+)/)[1];
		var y = b.match(/title="*(-?[0-9]+)/)[1];
		x = parseFloat( x );
		y = parseFloat( y );
		return ((x < y) ?  1 : ((x > y) ? -1 : 0));
	};

/*
       	new FixedColumns( oTable, {
 		"iLeftColumns": 1,
		"iLeftWidth": 70
 	} );
*/

} );
</script>

<h1><?php te("Repair & Service");?> <a title='<?php te("Add new item repair & service");?>' href='<?php echo $scriptname;?>?action=editrepair&amp;id=new'><img border=0 src='images/add.png'></a></h1>

<table id='itemlisttbl' class="display">
<thead>
<tr>
<th><?php te("ID");?></th>
<th><?php te("Item Type");?></th>
<th><?php te("Manufacturer");?></th>
<th><?php te("Model");?></th>
<th><?php te("S/N");?></th>
<th><?php te("Asset Number");?></th>
<th><?php te("User");?></th>
<th><?php te("Division");?></th>
<th><?php te("Location");?></th>
<th><?php te("Area");?></th>
<th><?php te("Problem");?></th>
<th><?php te("Solution");?></th>
<th><?php te("Part Replacement");?></th>
<th><?php te("Repair Date");?></th>
<th><?php te("Finish Repair Date");?></th>
<th><?php te("Status Repair");?></th>


</tr>
</thead>
<tbody>
  <tr> <td colspan="21" class="dataTables_empty"><?php te("Please wait ... Loading data from server");?></td> </tr>
</tbody>
</table>

