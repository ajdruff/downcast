<?php

$form = new DowncastForm();

$forms_plugin=$this->getPlugin('Forms');


/*
 * Map the Ajax Action to a Method
 * 
 * Always map the submitted action with an matching
 * method in the Forms class
 * For 'MyAction' the mapped method would be 'formAjaxActionMyAction'
 * 
 * If there is already an action mapped, the call will have no effect.
 * We map it here only as a convienance so the user doesnt have to
 * unless he wants to override the default behavior
 * 
 * 
 */


if ( isset($_POST['dc_ajax_action'])) {
    $action=$_POST['dc_ajax_action'];
          $forms_plugin->addAjaxAction(
                $action, //action name submitted by form
                array( $forms_plugin, 'formAjaxAction'.$action ) //method to call when action is triggered
        );  
}



$ajax_actions=$this->getPlugin('Forms')->getAjaxActions();



$callback=$ajax_actions[$_POST['dc_ajax_action']];
        header( "Content-type: application/json" );
        if ( is_callable($callback)) {
    call_user_func($callback);
}else {
    
   

    

        if ( $_POST['dc_ajax_action']==='action_warning') {
     
    $success['error_message']='<div class="alert alert-danger"><strong>Plugin Error:&nbsp;</strong>No action has been configured for this form. Please see the Help documentation for the DownCast Forms plugin  to fix this.</div>';
    
    }else {
        
    $success['error_message']='<div class="alert alert-danger"><strong>Plugin Error:&nbsp;</strong>The action '.$_POST['dc_ajax_action'].' was not found. Please see the Help documentation for the DownCast Forms plugin  to fix this.</div>';
    
    }
    
        $success['success']=false;
    $success['form']=$_POST['form'];
    
    exit(json_encode($success));
    
    
}       



?>
