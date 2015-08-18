<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//////////////////////////////////////////////////
/// SIDE BAR LINKS                            ///
////////////////////////////////////////////////

$nav = array();

// $nav['cashier'] = array('title'=>'<i class="fa fa-desktop"></i> <span>Cashier</span>','path'=>'cashier','exclude'=>0);
$nav['dashboard'] = array('title'=>'<i class="fa fa-tachometer"></i> <span>Dashboard</span>','path'=>'dashboard','exclude'=>0);
	$trans['receiving'] = array('title'=>'Receiving','path'=>'receiving','exclude'=>0);
	$trans['adjustment'] = array('title'=>'Adjustment','path'=>'adjustment','exclude'=>0);
$nav['trans'] = array('title'=>'<i class="fa fa-random"></i> <span>Transactions</span>','path'=>$trans,'exclude'=>0);
	$items['list'] = array('title'=>'List','path'=>'items','exclude'=>0);
	$items['gcategories'] = array('title'=>'Categories','path'=>'settings/categories','exclude'=>0);
	$items['gsubcategories'] = array('title'=>'Sub Categories','path'=>'settings/subcategories','exclude'=>0);
	$items['item_inv'] = array('title'=>'Inventory','path'=>'items/inventory','exclude'=>0);
$nav['items'] = array('title'=>'<i class="fa fa-flask"></i> <span>Items</span>','path'=>$items,'exclude'=>0);
	$menus['menulist'] = array('title'=>'List','path'=>'menu','exclude'=>0);
	$menus['menucat'] = array('title'=>'Categories','path'=>'menu/categories','exclude'=>0);
	$menus['menusubcat'] = array('title'=>'Sub Categories','path'=>'menu/subcategories','exclude'=>0);
	$menus['menusched'] = array('title'=>'Schedules','path'=>'menu/schedules','exclude'=>0);
$nav['menu'] = array('title'=>'<i class="fa fa-cutlery"></i> <span>Menu</span>','path'=>$menus,'exclude'=>0);
	$mods['modslist'] = array('title'=>'List','path'=>'mods','exclude'=>0);
	$mods['modgrps'] = array('title'=>'Groups','path'=>'mods/groups','exclude'=>0);
$nav['mods'] = array('title'=>'<i class="fa fa-tags"></i> <span>Modifiers</span>','path'=>$mods,'exclude'=>0);
	$pos_promos['promos'] = array('title'=>'Promos','path'=>'settings/promos','exclude'=>0);
	$pos_promos['gift_cards'] = array('title'=>'<span>Gift Cards</span>','path'=>'gift_cards','exclude'=>0);
	$pos_promos['coupons'] = array('title'=>'<span>Coupons</span>','path'=>'coupons','exclude'=>0);
$nav['pos_promos'] = array('title'=>'<i class="fa fa-tags"></i> <span>Promos</span>','path'=>$pos_promos,'exclude'=>0);


	// $resSettings['types'] = array('title'=>'Restaurants','path'=>'restaurant/','exclude'=>0);
// $nav['restaurant'] = array('title'=>'<i class="fa fa-cutlery"></i> <span>Restaurants</span>','path'=>'restaurants','exclude'=>0);
	
	//$dtr['schedules'] = array('title'=>'Schedules','path'=>'dtr/dtr_schedules','exclude'=>0);
	
// 	$dtr['shifts'] = array('title'=>'Shifts','path'=>'dtr/dtr_shifts','exclude'=>0);
// 	$dtr['scheduler'] = array('title'=>'Scheduler','path'=>'dtr/scheduler','exclude'=>0);
// $nav['dtr'] = array('title'=>'<i class="fa fa-clock-o"></i> <span>DTR</span>','path'=>$dtr,'exclude'=>0);
	// <i class="fa fa-gift"></i>
	// <i class="fa fa-tag"></i>
	// $reps['act_sales'] = array('title'=>'Sales','path'=>'reports/sales_rep_ui','exclude'=>0);
	$reps['act_receipts'] = array('title'=>'Receipts','path'=>'reprint','exclude'=>0);
	$reps['act_logs'] = array('title'=>'Activity Logs','path'=>'reports/activity_logs_ui','exclude'=>0);
	$reps['drawer_count'] = array('title'=>'Drawer Count','path'=>'reports/drawer_count_ui','exclude'=>0);
	$reps['rep_history'] = array('title'=>'Read History','path'=>'history','exclude'=>0);
$nav['reps'] = array('title'=>'<i class="fa fa-file-text-o"></i> <span>Reports</span>','path'=>$reps,'exclude'=>0);
	// <i class="fa fa-asterisk"></i>
$nav['setup'] = array('title'=>'<i class="fa fa-cog"></i> <span>Setup</span>','path'=>'setup/details','exclude'=>0);
	
	$generalSettings['gtaxrates'] = array('title'=>'Tax Rates','path'=>'settings/tax_rates','exclude'=>0);
	$generalSettings['grecdiscs'] = array('title'=>'Receipt Discounts','path'=>'settings/receipt_discounts','exclude'=>0);
	$generalSettings['tblmng'] = array('title'=>'Seating Management','path'=>'settings/seat_management','exclude'=>0);
	$generalSettings['denomination'] = array('title'=>'Denominations','path'=>'settings/denomination','exclude'=>0);
$nav['general_settings'] = array('title'=>'<i class="fa fa-cogs"></i> <span>General Settings</span>','path'=>$generalSettings,'exclude'=>0);
	

	
	$maintenance['customers'] = array('title'=>'<span>Customers</span>','path'=>'customers','exclude'=>0);
	$maintenance['glocations'] = array('title'=>'Locations','path'=>'settings/locations','exclude'=>0);
	$maintenance['gsuppliers'] = array('title'=>'Suppliers','path'=>'settings/suppliers','exclude'=>0);
	$maintenance['charges'] = array('title'=>' <span>Extra Charges</span>','path'=>'charges','exclude'=>0);
	$maintenance['guom'] = array('title'=>'UOM','path'=>'settings/uom','exclude'=>0);
$nav['maintenance'] = array('title'=>'<i class="fa fa-cogs"></i> <span>Maintenance</span>','path'=>$maintenance,'exclude'=>0);


///ADMIN CONTROL////////////////////////////////
	$controlSettings['user'] = array('title'=>'Users','path'=>'user','exclude'=>0);
	$controlSettings['roles'] = array('title'=>'Roles','path'=>'admin/roles','exclude'=>0);
	$controlSettings['restart'] = array('title'=>'Restart','path'=>'admin/restart','exclude'=>0);
$nav['control'] = array('title'=>'<i class="fa fa-user"></i> <span>Admin Control</span>','path'=>$controlSettings,'exclude'=>0);
// $nav['messages'] = array('title'=>'<i class="fa fa-envelope-o"></i> <span>Messages</span>','path'=>'messages','exclude'=>1);
// $nav['messages'] = array('title'=>'<i class="fa fa-envelope-o"></i> <span>Messages</span>','path'=>'messages','exclude'=>1);
// $nav['preferences'] = array('title'=>'<i class="fa fa-wrench"></i> <span>Preferences</span>','path'=>'preference','exclude'=>1);
// $nav['profile'] = array('title'=>'<i class="fa fa-folder-o"></i> <span>Profile</span>','path'=>'profile','exclude'=>1);
///LOGOUT///////////////////////////////////////
// $nav['send_to_rob'] = array('title'=>'<i class="fa fa-envelope-o"></i> <span>RLC Server Files</span>','path'=>'reads/manual_send_to_rob','exclude'=>0);
$nav['logout'] = array('title'=>'<i class="fa fa-sign-out"></i> <span>Logout</span>','path'=>'site/go_logout','exclude'=>1);
$config['sideNav'] = $nav;
