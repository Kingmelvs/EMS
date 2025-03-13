<?php
include 'admin_class.php';
$crud = new Action();

// Define supported actions
$actionMethods = [
    'login' => 'login',
    'login2' => 'login2',
    'logout' => 'logout',
    'logout2' => 'logout2',
    'save_user' => 'save_user',
    'delete_user' => 'delete_user',
    'signup' => 'signup',
    'save_settings' => 'save_settings',
    'save_venue' => 'save_venue',
    'save_book' => 'save_book',
    'delete_book' => 'delete_book',
    'send_email' => 'send_email',
    'save_register' => 'save_register',
    'delete_register' => 'delete_register',
    'delete_venue' => 'delete_venue',
    'update_order' => 'update_order',
    'delete_order' => 'delete_order',
    'save_event' => 'save_event',
    'delete_event' => 'delete_event',
    'save_artist' => 'save_artist',
    'delete_artist' => 'delete_artist',
    'get_audience_report' => 'get_audience_report',
    'get_venue_report' => 'get_venue_report',
    'save_art_fs' => 'save_art_fs',
    'delete_art_fs' => 'delete_art_fs',
    'get_pdetails' => 'get_pdetails'
];

// Route the action to the appropriate method
if (isset($_GET['action']) && isset($actionMethods[$_GET['action']])) {
    $method = $actionMethods[$_GET['action']];
    $result = $crud->$method();
    echo $result;
} else {
    echo "Invalid action";
}
?>
