<p style='padding-top:20px;'>
</p>
<h1>
<?php 
echo $settings['companytitle'];
?>
</h1>

<br>
<b>SGS IDIT Inventory DataBase</b>
<br>
<div style='text-align:left;width:90%;'>
Tips for new users:<br>
<ol>
<li>First, add one or more Agents (vendors, S/W &amp; H/W manufacturers, contractors) by clicking the "+" next to the "Agents" on the left.
<li>Then, add some item types (PC, Printer, Switch FC, etc) and optionally contract types (License, S&amp;M,...) using the "Item Types" menu
<li>Then, you can start adding Items, Software and Contracts.
</ol>

Menu help:
<ul>
<li><b>Items</b>: all physical assets. PCs, printers, servers, phones, tape libraries, video players, etc. 
Items can be associated with other items, and invoices. You may also add relevant files (manuals, offers, etc)</li>
<li><b>Invoices</b>: proofs of purchase for hardware, software, contracts, etc. These are different from other files/documents (manuals, offers, etc) because they contain extra metadata like vendor, buyer, dates etc </li>
<li><b>Software</b>: all software metadata. You may associate software with items in this menu (e.g. assign a software to multiple PCs)</li>
<li><b>Agents</b>: agents are entities like Vendors, S/W Manufacturers, H/W Manufacturers, Contractors, and Buyers 
<li><b>Racks</b>: here you may enter rack data + view  rack layouts. Items are assigned to racks based on their rackmountable,rack and rack-position properties.</li>
<li><b>Contracts</b>: enter contracts like support&amp;maintenance,leases etc. Contracts can be associated with Items and Software and have related documents and invoices. Contract events are also kept here.</li>
<li><b>Files</b>: you may edit file data here for files that were previously uploaded through the Items, Software, Invoices or Contract file upload tabs. You may also upload new files (except invoices) and relate them to more Items, Software or Contracts.</li>
<li><b>Repair & Services</b>: All repair and services physical assets. Notebook, PCs, Monitor, printers, servers, phones, tape libraries, video players, etc.</li>
</ul>
Changelog SGS ITDB version 1.01:
<ul>
<li><b>Adding feature "Repair & Service".</b></li>
<li><b>Adding feature "Report Number of items per Status"</b></li>
<li><b>Adding feature "Report Number of items per Type Items (Agent)"</b></li>
<li><b>Adding feature "Report Items without Asset Number" (24/05/2015)</b></li>
<li><b>Adding feature "Report Items without License Software" (28/07/2015)</b></li>
<li><b>Adding feature "Report Items Repair & Services" (30/07/2015)</b></li>
<li><b>Adding feature "Report Items with License Software" (08/09/2015)</b></li>
<li><b>Adding feature "Purchase Date on Item List" (16/08/2016)</b></li>
</ul>


