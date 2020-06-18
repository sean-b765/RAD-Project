<?php

require '../private/initialise.php';

$data['current'] = (int)check_for_changes();
// don't update unless below IF statement is performed
$data['update']  = false;

// check if this page is POSTED to with the correct data
//  and check that there was a change in the database:  {  'change' != $current  }
if (isset($_POST['change']) && 
    ( (int) $_POST['change'] != (int) $data['current'] ))
{
    // ignore change variable being 0, as we don't want 
    //  an infinitely refreshing page
    if ($_POST['change'] != 0) {
        $data['update'] = true;
    }
}
echo json_encode($data);

?>