<?php
/*-----------------------------------------------------------------------------
* Live PHP Statistics                                                         *
*                                                                             *
* Main Author: Vaggelis Koutroumpas vaggelis@koutroumpas.gr                   *
* (c)2008-2016 for AWMN                                                       *
* Credits: see CREDITS file                                                   *
*                                                                             *
* This program is free software: you can redistribute it and/or modify        *
* it under the terms of the GNU General Public License as published by        * 
* the Free Software Foundation, either version 3 of the License, or           *
* (at your option) any later version.                                         *
*                                                                             *
* This program is distributed in the hope that it will be useful,             *
* but WITHOUT ANY WARRANTY; without even the implied warranty of              *
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the                *
* GNU General Public License for more details.                                *
*                                                                             *
* You should have received a copy of the GNU General Public License           *
* along with this program. If not, see <http://www.gnu.org/licenses/>.        *
*                                                                             *
*-----------------------------------------------------------------------------*/


//Define current page data
$mysql_table = 'events';
$sorting_array = array("date", "event_code");

// ----------------------------------------------------------------------

$action_title = "All Event Logs"; 

$search_vars = "";

// Filtering
if ($_GET['event_code']){
	
	if (strstr($_GET['event_code'], 'LINK')){
		if ($_GET['event_code'] == 'LINKALL'){
			$sql_event_code = "WHERE  `event_code` LIKE 'LINK%' ";
			$search_vars .="&event_code=LINKALL";
		}elseif($_GET['event_code'] == 'LINKNEW'){
			$sql_event_code = "WHERE  `event_code` = 'LINKNEW' ";
			$search_vars .="&event_code=LINKNEW";
		}elseif($_GET['event_code'] == 'LINKUP'){
			$sql_event_code = "WHERE  `event_code` = 'LINKUP' ";
			$search_vars .="&event_code=LINKALL";
		}elseif($_GET['event_code'] == 'LINKUP'){
			$sql_event_code = "WHERE  `event_code` = 'LINKDOWN' ";
			$search_vars .="&event_code=LINKALL";
		}elseif($_GET['event_code'] == 'LINKDOWN'){
			$sql_event_code = "WHERE  `event_code` = 'LINKDELETE' ";
			$search_vars .="&event_code=LINKDELETE";
		}
		//Filter by AS		
		if ($_GET['as']){
			$_GET['as'] = (int)$_GET['as'];
			$search_query = " AND (`node1` = '".$_GET['as']."' OR `node2` = '".$_GET['as']."' OR `seenby` = '".$_GET['as']."' ) ";
			$search_vars .="&as=".$_GET['as'];
		}
	}elseif (strstr($_GET['event_code'], 'PREFIX')){
		if ($_GET['event_code'] == 'PREFIXALL'){
			$sql_event_code = "WHERE  `event_code` LIKE 'PREFIX%' ";
			$search_vars .="&event_code=PREFIXALL";
		}elseif($_GET['event_code'] == 'PREFIXNEW'){
			$sql_event_code = "WHERE  `event_code` = 'PREFIXNEW' ";
			$search_vars .="&event_code=PREFIXNEW";
		}elseif($_GET['event_code'] == 'PREFIXUP'){
			$sql_event_code = "WHERE  `event_code` = 'PREFIXUP' ";
			$search_vars .="&event_code=PREFIXUP";
		}elseif($_GET['event_code'] == 'PREFIXDOWN'){
			$sql_event_code = "WHERE  `event_code` = 'PREFIXDOWN' ";
			$search_vars .="&event_code=PREFIXDOWN";
		}elseif($_GET['event_code'] == 'PREFIXDELETE'){
			$sql_event_code = "WHERE  `event_code` = 'PREFIXDELETE' ";
			$search_vars .="&event_code=PREFIXDELETE";
		}
		//Filter by Prefix		
		if ($_GET['prefix']){
			$_GET['prefix'] = htmlentities($_GET['prefix']);
			$search_query = " AND `prefix` LIKE '%".mysql_real_escape_string($_GET['prefix'])."%' ";
			$search_vars .="&prefix=".$_GET['prefix'];
		}
		//Filter by AS		
		if ($_GET['as']){
			$_GET['as'] = (int)$_GET['as'];
			$search_query = " AND ( `node1` = '".$_GET['as']."' OR `seenby` = '".$_GET['as']."' ) ";
			$search_vars .="&as=".$_GET['as'];
		}
	}elseif (strstr($_GET['event_code'], 'PREPEND')){
		if ($_GET['event_code'] == 'PREPENDALL'){
			$sql_event_code = "WHERE  `event_code` LIKE 'PREPEND%' ";
			$search_vars .="&event_code=PREPENDALL";
		}elseif($_GET['event_code'] == 'PREPENDNEW'){
			$sql_event_code = "WHERE  `event_code` = 'PREPENDNEW' ";
			$search_vars .="&event_code=PREPENDNEW";
		}elseif($_GET['event_code'] == 'PREPENDUP'){
			$sql_event_code = "WHERE  `event_code` = 'PREPENDUP' ";
			$search_vars .="&event_code=PREPENDUP";
		}elseif($_GET['event_code'] == 'PREPENDDOWN'){
			$sql_event_code = "WHERE  `event_code` = 'PREPENDDOWN' ";
			$search_vars .="&event_code=PREPENDDOWN";
		}elseif($_GET['event_code'] == 'PREPENDDELETE'){
			$sql_event_code = "WHERE  `event_code` = 'PREPENDELETE' ";
			$search_vars .="&event_code=PREPENDELETE";
		}
		//Filter by AS		
		if ($_GET['as']){
			$_GET['as'] = (int)$_GET['as'];
			$search_query = " AND (`node1` = '".$_GET['as']."' OR `node2` = '".$_GET['as']."' OR `seenby` = '".$_GET['as']."' ) ";
			$search_vars .="&as=".$_GET['as'];
		}				
	}elseif($_GET['event_code'] == 'ROUTERSKIP'){
		$sql_event_code = "WHERE  `event_code` = 'ROUTERSKIP' ";
		$search_vars .="&event_code=ROUTERSKIP";
		//Filter by Prefix		
		if ($_GET['router_ip']){
			$_GET['router_ip'] = htmlentities($_GET['router_ip']);
			$search_query = " AND `router_ip` LIKE '%".mysql_real_escape_string($_GET['router_ip'])."%' ";
			$search_vars .="&router_ip=".$_GET['router_ip'];
		}
	}elseif($_GET['event_code'] == 'DAEMONSTART'){
		$sql_event_code = "WHERE  `event_code` = 'DAEMONSTART' ";
		$search_vars .="&event_code=DAEMONSTART";
	}elseif($_GET['event_code'] == 'DAEMONHOLD'){
		$sql_event_code = "WHERE  `event_code` = 'DAEMONHOLD' ";
		$search_vars .="&event_code=DAEMONHOLD";
	}elseif($_GET['event_code'] == 'FATALERROR'){
		$sql_event_code = "WHERE  `event_code` = 'FATALERROR' ";
		$search_vars .="&event_code=FATALERROR";
	}
}


// Sorting
if (isset($_GET['sort'])){
	if (in_array($_GET['sort'], $sorting_array)) {
		if ($_GET['by'] !== "desc" && $_GET['by'] !== "asc") {
			$_GET['by'] = "desc";
		}
		$order = "ORDER BY `". mysql_escape_string($_GET['sort']) ."` ". mysql_escape_string($_GET['by']) . " ";
	}
} else {
	$order = "ORDER BY `date` DESC ";
	$_GET['sort'] = "date";
	$_GET['by'] = "desc";
}
$sort_vars = "&sort=".$_GET['sort']."&by=".$_GET['by'];


// Paging
$count = mysql_query("SELECT id FROM $mysql_table $sql_event_code $search_query",$db);
$items_number  = mysql_num_rows($count);
if ($_GET['items_per_page'] && is_numeric($_GET['items_per_page'])){
	$_SESSION['items_per_page'] = $_GET['items_per_page'];
}
if ($_POST['items_per_page'] && is_numeric($_POST['items_per_page'])){
	$_SESSION['items_per_page'] = $_POST['items_per_page'];
}
if (isset($_SESSION['items_per_page']) && is_numeric($_SESSION['items_per_page'])){
	$num = $_SESSION['items_per_page'];
} else { 
	$_SESSION['items_per_page'] = $CONF['ADMIN_ITEMS_PER_PAGE'];
	$num = $CONF['ADMIN_ITEMS_PER_PAGE'];     
}
$e = $num;
$pages = $items_number/$num;
if (!$_GET['pageno']){
	$pageno = 0; 
}else{
	$pageno = $_GET['pageno'];
}
if (isset($_POST['goto'])) {
	if ($_POST['goto'] <= $pages + 1) {
		$pageno = $num * ($_POST['goto'] - 1);
	} else {
		$pageno = 0;
	}
}
$current_page = 0;
for($i=0;$i<$pages;$i++){
	$y=$i+1;
	$page=$i*$num;
	if ($page == $pageno){
		$current_page = $y;
	}
} 
$total_pages=$i; // sinolo selidon

//Final Query for records listing
$SELECT_RESULTS  = mysql_query("SELECT `".$mysql_table."`.* FROM `".$mysql_table."` ".$sql_event_code." ".$search_query." ".$order." LIMIT ".$pageno.", ".$e ,$db);
$search_vars = htmlspecialchars($search_vars);

?>
<script>
	$(function() {
		
		//SHOW/HIDE INPUT FIELDS BASED ON DROPDOWN MENU SELECTION
        <?if ( strstr($_GET['event_code'], 'LINK') ){?>
        $('#filters').show();
        $('#prefix').hide();
		$('#as').show();
		$('#router_ip').hide();
		<?}elseif ( strstr($_GET['event_code'], 'PREFIX') ){?>
		$('#filters').show();
        $('#prefix').show();
		$('#as').show();
		$('#router_ip').hide();
		<?}elseif ( strstr($_GET['event_code'], 'PREPEND') ){?>
		$('#filters').show();
        $('#prefix').hide();
		$('#as').show();
		$('#router_ip').hide();
		<?}elseif ( strstr($_GET['event_code'], 'ROUTER') ){?>
		$('#filters').show();
        $('#prefix').hide();
		$('#as').hide();
		$('#router_ip').show();
		<?}else{?>
		$('#filters').hide();
		$('#prefix').hide();
		$('#as').hide();
		$('#router_ip').hide();
		<?}?>
        
        $('#event_code').live('change', function(){
            var myval = $('option:selected',this).val();
            if (myval.indexOf("LINK") !=-1){
				$('#filters').show();
				$('#prefix').hide();
				$('#as').show();
				$('#router_ip').hide();
            }else if (myval.indexOf("PREFIX") !=-1){
				$('#filters').show();
				$('#prefix').show();
				$('#as').show();
				$('#router_ip').hide();
			}else if (myval.indexOf("PREPEND") !=-1){
				$('#filters').show();
				$('#prefix').hide();
				$('#as').show();
				$('#router_ip').hide();
			}else if (myval.indexOf("ROUTER") !=-1){
				$('#filters').show();
				$('#prefix').hide();
				$('#as').hide();
				$('#router_ip').show();
			}else{
				$('#filters').hide();
				$('#prefix').hide();
				$('#as').hide();
				$('#router_ip').hide();
            }
        }); 		
		
	});

</script>
                
<div id="main_content">
					               

                    <!-- PRINT EVENT LOGS START -->
                      
                      <fieldset>
                                
                          <legend>&raquo; BGP Event Log</legend>
                        
		                    <form name="search_form" action="index.php?section=<?=$SECTION;?>" method="get" class="search_form">
								<input type="hidden" name="section" value="<?=$SECTION;?>" />
								
								<table border="0" cellspacing="0" cellpadding="4" width="660">
								    <tr>
								        <td width="110">Event Category:</td>
								        <td colspan="2">
								            <select name="event_code" id="event_code"  class="select_box">
								                <option value="">Show All Categories</option> 
								                <option value="" disabled="disabled">----------------------------------------------------------------------------------------------------</option>
								                <option value="LINKALL"       <? if ($_GET['event_code'] == "LINKALL"){       echo "selected=\"selected\""; }?> >Show all Peers/Links categories</option>
								                <option value="LINKNEW"       <? if ($_GET['event_code'] == "LINKNEW"){       echo "selected=\"selected\""; }?> >LINKNEW - Show newly detected peers/links</option>
								                <option value="LINKUP"        <? if ($_GET['event_code'] == "LINKUP"){        echo "selected=\"selected\""; }?> >LINKUP - Show peers/links that went up</option>
								                <option value="LINKDOWN"      <? if ($_GET['event_code'] == "LINKDOWN"){      echo "selected=\"selected\""; }?> >LINKDOWN - Show peers/links that went down</option>
								                <option value="LINKDELETE"    <? if ($_GET['event_code'] == "LINKDELETE"){    echo "selected=\"selected\""; }?> >LINKDELETE - Show peers/links that were deleted</option>
								                <option value="" disabled="disabled">----------------------------------------------------------------------------------------------------</option>
								                <option value="PREFIXALL"     <? if ($_GET['event_code'] == "PREFIXALL"){     echo "selected=\"selected\""; }?> >Show all Prefixes categories</option>
								                <option value="PREFIXNEW"     <? if ($_GET['event_code'] == "PREFIXNEW"){     echo "selected=\"selected\""; }?> >PREFIXNEW - Show newly detected prefixes</option>
								                <option value="PREFIXUP"      <? if ($_GET['event_code'] == "PREFIXUP"){      echo "selected=\"selected\""; }?> >PREFIXUP - Show prefixes that went up</option>
								                <option value="PREFIXDOWN"    <? if ($_GET['event_code'] == "PREFIXDOWN"){    echo "selected=\"selected\""; }?> >PREFIXDOWN - Show prefixes that went down</option>
								                <option value="PREFIXDELETE"  <? if ($_GET['event_code'] == "PREFIXDELETE"){  echo "selected=\"selected\""; }?> >PREFIXDELETE - Show prefixes that were deleted</option>
								                <option value="" disabled="disabled">----------------------------------------------------------------------------------------------------</option>
								                <option value="PREPENDALL"    <? if ($_GET['event_code'] == "PREPENDALL"){    echo "selected=\"selected\""; }?> >Show all Prepend categories</option>
								                <option value="PREPENDNEW"    <? if ($_GET['event_code'] == "PREPENDNEW"){    echo "selected=\"selected\""; }?> >PREPENDNEW - Show newly detected prepends</option>
								                <option value="PREPENDUP"     <? if ($_GET['event_code'] == "PREPENDUP"){     echo "selected=\"selected\""; }?> >PREPENDUP - Show prepends that went up</option>
								                <option value="PREPENDDOWN"   <? if ($_GET['event_code'] == "PREPENDDOWN"){   echo "selected=\"selected\""; }?> >PREPENDDOWN - Show prepends that went down</option>
								                <option value="PREPENDDELETE" <? if ($_GET['event_code'] == "PREPENDDELETE"){ echo "selected=\"selected\""; }?> >PREPENDDELETE - Show prepends that were deleted</option>
								                <option value="" disabled="disabled">----------------------------------------------------------------------------------------------------</option>
								                <option value="ROUTERSKIP"    <? if ($_GET['event_code'] == "ROUTERSKIP"){    echo "selected=\"selected\""; }?> >ROUTERSKIP - Show skipped routers</option>
								                <option value="DAEMONSTART"   <? if ($_GET['event_code'] == "DAEMONSTART"){   echo "selected=\"selected\""; }?> >DAEMONSTART - Show BGP Stats Collect Daemon startups</option>
								                <option value="DAEMONHOLD"    <? if ($_GET['event_code'] == "DAEMONHOLD"){    echo "selected=\"selected\""; }?> >DAEMONHOLD - Show BGP Stats Collect Daemon holds</option>
								                <option value="FATALERROR"    <? if ($_GET['event_code'] == "FATALERROR"){    echo "selected=\"selected\""; }?> >FATALERROR - Show fatal errors</option>
								            </select>
								            
								        </td>
									</tr>
								    <tr height="40">
								        <td><div id="filters"><strong>Filters (Optional)</strong></div></td>
								        <td colspan="">
						        			
											<div id="prefix" style="display: inline; float: left;">
								            Filter Prefix: <input type="text" name="prefix" id="prefix" class="input_field" value="<?=htmlentities($_GET['prefix']);?>" />
								            </div>						        	
						        			
											<div id="as" style="display: inline; float: left;">
								            &nbsp; Filter AS: <input type="text" name="as" id="as" class="input_field" value="<?=htmlentities($_GET['as']);?>" />
								            </div>						        	
						        			
											<div id="router_ip" style="display: inline; float: left;">
								            Filter Router IP: <input type="text" name="router_ip" id="router_ip" class="input_field" value="<?=htmlentities($_GET['router_ip']);?>" />
								            </div>						        	

								        </td>
								        <td align="right"><button type="submit" style="margin-bottom: 0; margin-top:0;">Execute</button></td>
									</tr>
									
								</table> 

							</form> 
							
					        <br />
							
							<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-bottom:15px; margin-top: 15px;">
								<tr>
									<td width="36%" height="30">
										<h3 style="margin:0"><?=$action_title;?> <? if ($q||$p) { ?><span style="font-size:12px"> (<a href="index.php?section=<?=$SECTION;?>" class="tip_south" title="Clear search">x</a>)</span><? } ?></h3> 
									</td>
									<td width="28%" align="center">
										<? if ($items_number) { ?>
										Total Records: <span id="total_records"><?=$items_number?></span>
										<? } ?>
									</td>
									<td width="36%"><? if ($items_number) { include "includes/paging.php"; } ?></td>
								</tr>
							</table>                            

							<table width="100%" border="0" cellspacing="2" cellpadding="5">
								<tr>
									<th width="120"><?=create_sort_link("date","Event Date");?></th>
									<th width="120"><?=create_sort_link("event_code","Event Code");?></th>
									<th>Event Message</th>
								</tr>
								<!-- RESULTS START -->
								<?
								$i=-1;
								while($LISTING = mysql_fetch_array($SELECT_RESULTS)){
								$i++;
								
								if (strstr($LISTING['event_code'], "LINK" ) ){
									if ($LISTING['event_code'] == 'LINKNEW' ){
										$EVENT_CODE = "<span class='blue'>".$LISTING['event_code']."</span>";
										$EVENT = "New peer/link ".$LISTING['node1']."-".$LISTING['node2']." added. Peer detected by AS ". $LISTING['seenby'];
									}elseif($LISTING['event_code'] == 'LINKUP' ){
										$EVENT_CODE = "<span class='green'>".$LISTING['event_code']."</span>";
										$EVENT = "Peer/link ".$LISTING['node1']."-".$LISTING['node2']." is up. Peer detected by AS ". $LISTING['seenby'];
									}elseif($LISTING['event_code'] == 'LINKDOWN' ){
										$EVENT_CODE = "<span class='red'>".$LISTING['event_code']."</span>";
										$EVENT = "Peer/link ".$LISTING['node1']."-".$LISTING['node2']." went down";
									}elseif($LISTING['event_code'] == 'LINKDELETE' ){
										$EVENT_CODE = "<span class='gray'>".$LISTING['event_code']."</span>";
										$EVENT = "Peer/link ".$LISTING['node1']."-".$LISTING['node2']." deleted after 30 days down";
									}
								}elseif (strstr($LISTING['event_code'], "PREFIX" ) ){
									if ($LISTING['event_code'] == 'PREFIXNEW' ){
										$EVENT_CODE = "<span class='blue'>".$LISTING['event_code']."</span>";
										$EVENT = "New prefix ".$LISTING['prefix']." advertised by AS ".$LISTING['node1'].". Advertisment detected by AS ". $LISTING['seenby'];
									}elseif($LISTING['event_code'] == 'PREFIXUP' ){
										$EVENT_CODE = "<span class='green'>".$LISTING['event_code']."</span>";
										$EVENT = "Prefix ".$LISTING['prefix']." advertised by AS ".$LISTING['node1']." is up. Advertisment detected by AS ". $LISTING['seenby'];
									}elseif($LISTING['event_code'] == 'PREFIXDOWN' ){
										$EVENT_CODE = "<span class='red'>".$LISTING['event_code']."</span>";
										$EVENT = "Prefix ".$LISTING['prefix']." no longer advertised by AS ".$LISTING['node1'];
									}elseif($LISTING['event_code'] == 'PREFIXDELETE' ){
										$EVENT_CODE = "<span class='gray'>".$LISTING['event_code']."</span>";
										$EVENT = "Prefix ".$LISTING['prefix']." hasn't been advertised by AS ".$LISTING['node1']." for 30 days. Deleting.";
									}
								}elseif (strstr($LISTING['event_code'], "PREPEND" ) ){
									if ($LISTING['event_code'] == 'PREPENDNEW' ){
										$EVENT_CODE = "<span class='brown'>".$LISTING['event_code']."</span>";
										$EVENT = "New prepend on AS ".$LISTING['node1']." with parent AS ".$LISTING['node2']." added. Prepend detected by AS ". $LISTING['seenby'];
									}elseif($LISTING['event_code'] == 'PREPENDUP' ){
										$EVENT_CODE = "<span class='green'>".$LISTING['event_code']."</span>";
										$EVENT = "Prepend on AS ".$LISTING['node1']." with parent AS ".$LISTING['node2']." is up. Prepend detected by AS ". $LISTING['seenby'];
									}elseif($LISTING['event_code'] == 'PREPENDDOWN' ){
										$EVENT_CODE = "<span class='red'>".$LISTING['event_code']."</span>";
										$EVENT = "Prepend on AS ".$LISTING['node1']." with parent AS ".$LISTING['node2']." went down";
									}elseif($LISTING['event_code'] == 'PREPENDDELETE' ){
										$EVENT_CODE = "<span class='gray'>".$LISTING['event_code']."</span>";
										$EVENT = "Prepend on AS ".$LISTING['node1']." with parent AS ".$LISTING['node2']." deleted after 30 days down";
									}
								}elseif ($LISTING['event_code'] == 'ROUTERSKIP' ){
										$EVENT_CODE = "<span class='red'>".$LISTING['event_code']."</span>";
										$EVENT = "Router with IP ".$LISTING['router_ip']." seems to be down while trying to collect BGP data. Skipping";
								}elseif ($LISTING['event_code'] == 'DAEMONSTART' ){
										$EVENT_CODE = "<span class='orange'>".$LISTING['event_code']."</span>";
										$EVENT = "BGP Statistics Collector Daemon started";
								}elseif ($LISTING['event_code'] == 'DAEMONHOLD' ){
										$EVENT_CODE = "<span class='orange'>".$LISTING['event_code']."</span>";
										$EVENT = "BGP Statistics Collector Daemon is on hold because there are too few routers active/healthy to collect data from. Retrying in 60 seconds";
								}elseif ($LISTING['event_code'] == 'FATALERROR' ){
										$EVENT_CODE = "<span class='red'>".$LISTING['event_code']."</span>";
										$EVENT = $LISTING['event_msg'];
								}
                                ?>      
                                <tr onmouseover="this.className='on' " onmouseout="this.className='off' " id="tr-<?=$LISTING['id'];?>">
									<td nowrap><?=$LISTING['date'];?></td>
									<td nowrap><?=$EVENT_CODE;?></td>
									<td ><?=$EVENT;?></td>
								</tr>
								<?}?>
                                <!-- RESULTS END -->
							</table>

							<? if (!$items_number) { ?>
							<div class="no_records">No records found</div>
							<? } ?>

							<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin:10px 0">
								<tr>
									<td width="36%" height="30">
										<? include "includes/items_per_page.php"; ?>
									</td>
									<td width="28%">&nbsp;</td>
									<td width="36%"> 
										<? if ($items_number) { include "includes/paging.php"; } ?>
									</td>
								</tr>
							</table>
                            
                  </fieldset>
                    
                  <!-- PRINT EVENT LOGS END -->
                   
                </div>  
                