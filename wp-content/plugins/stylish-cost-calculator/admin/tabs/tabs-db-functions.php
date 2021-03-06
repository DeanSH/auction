<?php
function scc_get_all_tabs(){
#https://wordpress.stackexchange.com/questions/236514/uninstalling-a-plugin-delete-all-options-with-specific-prefix
global $wpdb;

$prefix=scc_get_option_prefix();
//$prefix = str_replace('scc', 'spl', $prefix);
$option_names = $wpdb->get_results( "SELECT option_name FROM $wpdb->options WHERE option_name LIKE '$prefix%'" );

 

$cats_data=array();
foreach( $option_names as $opt ) {
    $id=scc_get_id_from_option_name($opt->option_name);
    $option=get_option($opt->option_name);

    $cat=new stdClass();
    $cat->id=$id;
    $cat->shortcode='[pricelist id="' . $id .'"]';
    $cat->list_name=$option['list_name'];

    $cats_data[$id]=$cat;

    // delete_option($opt->option_name);
}
return $cats_data;
}

function scc_get_tabs_coun(){
    return count(scc_get_all_tabs());
}

function scc_insert_tabs($cats_data){
    $insert_id=time();
    if(!empty($cats_data['id'])){
        $insert_id=$cats_data['id'];
    }
	
    $option_name=scc_get_option_name($insert_id);
    update_option($option_name,$cats_data);
    return $insert_id;
}

function scc_get_option_name($id){
    return scc_get_option_prefix() . $id;
}

function scc_get_id_from_option_name($option_name){
    $id=str_replace(scc_get_option_prefix(),'',$option_name);
    return $id;
}

function scc_get_option_prefix(){
    return 'scc_cats_';
}

function scc_delete_tabs_by_id($id){
    $option_name=scc_get_option_name($id);
    delete_option($option_name);
}

function scc_get_option($id){
    $option_name=scc_get_option_name($id);
    $cats_data=get_option($option_name);
    return $cats_data;
}
