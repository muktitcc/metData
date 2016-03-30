<?php
error_reporting(E_ALL);
ini_set('memory_limit', '-1'); // Since the records are in millions
$metDataDisplay='';
?> 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<meta charset="utf-8">
		<title>MH::Bhutan</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="">
		<meta name="author" content="">
		<script src="//code.jquery.com/jquery-1.12.0.min.js" type="text/javascript"></script>
		<script src="https://cdn.datatables.net/1.10.10/js/jquery.dataTables.min.js"></script>
		<link href="https://cdn.datatables.net/1.10.10/css/jquery.dataTables.min.css" rel="stylesheet"></link>
	</head>
	<body>
			 Records/page
				<select id="displayPage" class="chosen-select" onchange="displayData()">
				<option value="10">10</option>
				<option value="20">20</option>
				<option value="50">50</option>
				<option value="100">100</option>
				<option value="500">500</option>
				<option value="1000">1000</option>
				</select>
			<button id="mbutton" class="btn btn-primary" onclick="tableToExcel(&#39;metDataDisplay&#39;, &#39;metDataDisplay&#39;)"/><span class="glyphicon glyphicon-refresh"></span> Export selected</button>
				<button id="mbutton" class="btn btn-primary" onclick="exportAll()"/><span class="glyphicon glyphicon-refresh"></span> Export all</button>
			<div><table id="metDataDisplay"><thead>
					<tr>
						<th align="left">Trn#</th>
						<th align="left">Station</th>
						<th align="left">Date time</th>
						<th align="left">Celsius</th>
						<th align="left">Humidity</th>
						<th align="left">Dewpoint</th>
						</tr></thead><tbody></tbody></table></div>
			
			
	</body>
</html>


<script>
var table;
$(document).ready(function() {
displayData();
} );
function displayData(){
				noOfPages=document.getElementById('displayPage').value;
				table= $('#metDataDisplay').dataTable({
					"fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
					$('td', nRow).attr('nowrap','nowrap');
					 return nRow;
					},
					"fnInitComplete": function() {
					$('#metDataDisplay tbody tr').each(function(){
							$(this).find('td:eq(0)').attr('nowrap', 'nowrap');
					});
				},
				"bAutoWidth": false,
				"iDisplayLength": noOfPages,
				"bLengthChange": false,
				"processing": true,
				"bScrollCollapse": true,
				"bPaginate": true,
				"sPaginationType":"full_numbers",
				"order": [[ 0, "desc" ]],
				"aoColumnDefs": [
					{ "bSortable": true, "aTargets": [ 0 ] },
					{ "bSortable": false, "aTargets": [1] },
					{ "bSortable": false, "aTargets": [2] },
					{ "bSortable": false, "aTargets": [3] },
					{ "bSortable": false, "aTargets": [4] },
					{ "bSortable": false, "aTargets": [5] },

				],
				"processing": true,
				"serverSide": true,
				"ajax": "metDataAjaxCall.php",
				"destroy": true,
				"searching": false,
				//"paging": false,
		   });

		$('#metDataDisplay tbody').on( 'click', 'tr', function () {
					if ( $(this).hasClass('selected') ) {
						$(this).removeClass('selected');
					}
					else {
						table.$('tr.selected').removeClass('selected');
						$(this).addClass('selected');
					}
			} );
	
	
}

var tableToExcel = (function () {
var uri = 'data:application/vnd.ms-excel;base64,'
                , template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>'
                , base64 = function (s) { return window.btoa(unescape(encodeURIComponent(s))) }
                , format = function (s, c) { return s.replace(/{(\w+)}/g, function (m, p) { return c[p]; }) }
            return function (table, name) {
				
                if (!table.nodeType) table = document.getElementById(table)
                var ctx = { worksheet: name || 'Worksheet', table: table.innerHTML }
			    window.location.href = uri + base64(format(template, ctx))
				
            }
        })()
function exportAll(){
window.open("exportAllMetData.php");	
}
		</script>