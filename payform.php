<?php
/*
Plugin Name: Crowdfunding for PayForm
Version: 2.0
Plugin URI: http://payform.me/
Author: PayForm
Description: Adds crowdfunding capabilities to PayForm payment forms
*/

if (!isset($payform_for_wordpress)) {
  $payform_for_wordpress = array('crowdfunding');
} else {
  $payform_for_wordpress[] = 'crowdfunding';
}

add_action( 'admin_init', 'payform_crowdfunding_has_parent_plugin' );
function payform_crowdfunding_has_parent_plugin() {
    if ( is_admin() && current_user_can( 'activate_plugins' ) &&  !is_plugin_active( 'payform/payform.php' ) ) {

        if (file_exists(WP_PLUGIN_DIR .'/payform/payform.php')) {
            activate_plugin( 'payform/payform.php' );
        } else {
            add_action( 'admin_notices', 'payform_crowdfunding_notice' );

            deactivate_plugins( plugin_basename( __FILE__ ) ); 

            if ( isset( $_GET['activate'] ) ) {
                unset( $_GET['activate'] );
            }
        }

    }
}

function payform_crowdfunding_notice(){
    $action = 'install-plugin';
    $slug = 'payform';
    $url = wp_nonce_url(
        add_query_arg(
            array(
                'action' => $action,
                'plugin' => $slug
            ),
            admin_url( 'update.php' )
        ),
        $action.'_'.$slug
    );
    ?><div class="error"><p>Crowdfunding for PayForm requires PayForm to be installed and active. <a href="<?php echo $url;?>" style="font-weight: bold;">Install plugin</a></p></div><?php
}


add_action( 'deactivated_plugin', 'payform_crowdfunding_detect_plugin_deactivation', 10, 2 );
function payform_crowdfunding_detect_plugin_deactivation( $plugin, $network_activation ) {
    if ($plugin=="payform/payform.php")
    {
        deactivate_plugins(plugin_basename(__FILE__));
    }
}



