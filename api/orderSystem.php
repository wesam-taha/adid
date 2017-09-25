<?
//header("content-type: application/json; charset=utf-8");
header('Cache-Control: private');
header('Access-Control-Allow-Origin:*');
header('Alternate-Protocol:80:quic,80:quic');


include_once("connect.php");
error_reporting(E_ALL);

// $a = 0 ;
// $globalQ = $db->query("SELECT * From  global_settings    ");
// $global =$db->fetch_assoc($globalQ);



$citiesQ = $db->query("SELECT * From  cities   order by id ASC  "); 
while($citiesR =$db->fetch_assoc($citiesQ)){
	$cities[] = $citiesR;
}

$locationsQ = $db->query("SELECT * From  locations where publish='1'   order by id ASC  "); 
while($locationsR =$db->fetch_assoc($locationsQ)){
	$locations[] = $locationsR;
}


$order_menu_categoriesQ = $db->query("SELECT * From  order_menu_categories   order by id ASC  "); 
while($order_menu_categoriesR =$db->fetch_assoc($order_menu_categoriesQ)){
	$order_menu_categories[] = $order_menu_categoriesR;
}


$order_menu_optionsQ = $db->query("SELECT * From  order_menu_options   order by id ASC  "); 
while($order_menu_optionsR =$db->fetch_assoc($order_menu_optionsQ)){
	$order_menu_options[] = $order_menu_optionsR;
}


$order_menu_valuesQ = $db->query("SELECT * From  order_menu_values   order by id ASC  "); 
while($order_menu_valuesR =$db->fetch_assoc($order_menu_valuesQ)){
	$order_menu_values[] = $order_menu_valuesR;
}



$order_menuQ = $db->query("SELECT * From  order_menu   order by id ASC  "); 
while($order_menuR =$db->fetch_assoc($order_menuQ)){
	$order_menu[] = $order_menuR;
}




//echo base64_encode(base64_encode('{ "total":'.ceil($totalNum/$pagesize).',"news": '.json_encode($arr).' }'));

echo '{ "cities": '.json_encode($cities).',"locations": '.json_encode($locations).',"categories": '.json_encode($order_menu_categories).',"order_menu": '.json_encode($order_menu).',"order_menu_options": '.json_encode($order_menu_options).',"order_menu_values": '.json_encode($order_menu_values).' }';



?>
