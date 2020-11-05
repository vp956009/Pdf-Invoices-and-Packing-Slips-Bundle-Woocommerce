
<?php

if (!defined('ABSPATH'))
  exit;
use Dompdf\Dompdf as Dompdf;


if (!class_exists('OCPSW_backend')) {

    class OCPSW_backend {

      protected static $instance;
 

            function OCPSW_submenu_page() {
              add_submenu_page( 'woocommerce', 'PDF Packing Slip', 'PDF Packing Slip', 'manage_options', 'pdf-packing-slip',array($this, 'OCPSW_callback'));
            }


            function OCPSW_callback() {
               ?>    
                <div class="wrap">
                    <h2><u>Packing Slip</u></h2>
                    <?php if(isset($_REQUEST['message'])&&$_REQUEST['message']== 'success'){ ?>
                        <div class="notice notice-success is-dismissible"> 
                            <p><strong>Record updated successfully.</strong></p>
                        </div>
                    <?php } ?>
                </div>
                <div class="ocpsw-container">
                    <form method="post" >
                       <?php wp_nonce_field( 'ocpsw_nonce_action', 'ocpsw_nonce_field' ); ?>   
                            <div class="cover_div_ocpsw">
                                <table class="ocpsw_data_table">
                                            <h2>Packing Slip Setting</h2>     
                                    <tr>
                                        <th>Shop Title</th>
                                        <td>
                                            <input type="text" name="ocpsw_shop_title" value="<?php if(!empty(get_option( 'ocpsw_shop_title' ))){ echo get_option( 'ocpsw_shop_title' ); }else{ echo "woocommerce";} ?>">
                                        </td>
                                    </tr>
                                    <tr>
                                      <th>
                                        Shop adress:
                                      </th>
                                        <td>
                                          <input type="text" name="ocpsw_shop_addr" value="<?php if(!empty(get_option( 'ocpsw_shop_addr' ))){ echo get_option( 'ocpsw_shop_addr' ); }else{ echo "woocommerce";} ?>">
                                        </td>
                                    </tr>
                                    <tr>
                                      <th>logo image</th>
                                    <td>
                                      <?php  
                                        echo $this->ocpsw_image_uploader_field( 'ocpsw_shop_imagelogo', true  );
                                      ?>
                                    </td>
                                    <td>
                                      <?php $attachment_id=get_option( 'ocpsw_shop_imagelogo');
                                      echo wp_get_attachment_image( $attachment_id, array(225,125),$icon =false ); ?>  
                                    </td> 
                                    </tr>
                                    <tr>
                                       <th>
                                        Footer add terms&condition:
                                      </th>
                                      <td>
                                         <input type="text" name="ocpsw_footer_text" value="<?php if(!empty(get_option( 'ocpsw_footer_text' ))){ echo get_option( 'ocpsw_footer_text' );
                                       }else{ echo "term & condition";} ?>">
                                      </td>
                                    </tr>
                                    <tr>
                                      <th>choose template</th>
                                      <td>
                                          <input type="radio" name="ocwqv_template_pos" value="temlate1" <?php if (get_option( 'ocwqv_template_pos' ) == "temlate1" ) {echo 'checked="checked"';} ?>>Template 1</br>
                                      </td>
                                      <td> 
                                        <input type="radio" name="ocwqv_template_pos" value="temlate2" <?php if (get_option( 'ocwqv_template_pos' ) == "temlate2" || empty(get_option( 'ocwqv_template_pos' ))) {echo 'checked="checked"';} ?>>Template 2
                                      </td>
                                    </tr>
                                    <tr>
                                       <th>
                                       Border background color: 
                                      </th>
                                      <td>
                                         <input type="color" name="ocpsw_bg_color" value="<?php if(!empty(get_option( 'ocpsw_bg_color' ))){ echo get_option( 'ocpsw_bg_color' );
                                       }else{ echo "#000";} ?>">
                                      </td>
                                    </tr>
                                </table>
                            </div>            
                        <input type="hidden" name="action" value="ocpsw_save_option">
                        <input type="submit" value="Save changes" name="submit" class="button-primary" id="wfc-btn-space">
                    </form>  
                </div>
               <?php

            }


            function ocpsw_image_uploader_field( $name, $value = '') {
                $image = ' button">Upload image';
                $image_size = 'full'; // it would be better to use thumbnail size here (150x150 or so)
                $display = 'none'; // display state ot the "Remove image" button
                if( $image_attributes = wp_get_attachment_image_src( $value, $image_size ) ) {
                    $image = '"><img src="' . $image_attributes[0] . '" style="max-width:95%;display:block;" />';
                    $display = 'inline-block';
                } 
                return '
                <div>
                    <a href="#" class="misha_upload_image_button' . $image . '</a>
                    <input type="hidden" name="' . $name . '" id="' . $name . '" value="' . $value . '" />
                    <a href="#" class="misha_remove_image_button" style="display:inline-block;display:' . $display .'">Remove image</a>
                </div>';

            }
             

            function OCPSW_add_custom_order_status_actions_button( $actions, $order ) {
                if ( $order->has_status( array( 'processing' ) ) ) {
                  $action_slug = 'parcial';
                  $action_slugg ='pdf-invoice';
                  $actions[$action_slug] = array(
                  'url' => wp_nonce_url( admin_url( '?action=woo_pdf&order_id=' . $order->get_id() ), 'woocommerce-mark-order-status' ),
                  'name' => __( 'Pdf packing Slip', 'woocommerce' ),
                  'action' => $action_slug,
                  );
                  $actions[$action_slugg] = array(
                  'url' => wp_nonce_url( admin_url( '?action=woo_pdf_invoice&order_id='. $order->get_id() ), 'woocommerce-mark-order-status' ),
                  'name' => __( 'Pdf invoice', 'woocommerce' ),
                  'action' => $action_slugg,
                );
                }
                return $actions;
            }
               

            function OCPSW_add_custom_order_status_actions_button_css() {
                 $action_slug = "parcial"; 
                 $action_slugg='pdf-invoice';
                 $icon= OCPSW_PLUGIN_DIR .'/images/packing-slip.png';
                 $iconn= OCPSW_PLUGIN_DIR .'/images/invoice.png';
                echo '<style>.'.$action_slug.'::after { background: url('. $icon.'); content: "" !important; background-repeat:no-repeat;background-position: center center; }</style>';
                echo '<style>.'.$action_slugg.'::after { background: url('. $iconn.'); content: "" !important; background-repeat: no-repeat;background-position: center center; }</style>';
            }


            function OCPSW_me_post_pdf(){
               
              if(isset($_REQUEST['order_id'])){
                $order = wc_get_order($_REQUEST['order_id']);
              }
           
               if(isset($_REQUEST['action']) && $_REQUEST['action'] == "woo_pdf"){

                include_once(plugin_dir_path( __FILE__ ).'dompdf/autoload.inc.php');
                ob_start();
                if(get_option( 'ocwqv_template_pos' ) == "temlate1"){
                    ?>
                      <div class="template1" >
                        <html>
                            <head>
                                <title>Packing Slip</title>
                            </head>
                            <body>
                              <table style="border-collapse:collapse; width: 100%;">
                                <tr>
                                  <td>
                                      <?php $attachment_id=get_option( 'ocpsw_shop_imagelogo');
                                     echo wp_get_attachment_image( $attachment_id, array(225,125),$icon =false ); ?>
                                  </td>
                                   <td style="width: 40%;">
                                     <h2>shop:<?php echo get_option( 'ocpsw_shop_title' );?></h2>
                                      <p><?php echo get_option( 'ocpsw_shop_addr' );?></p>
                                  </td>  
                                  </td> 
                                </tr>
                                <tr>  
                                  <td style="width: 60%;" >
                                          <h2>Packing Slip</h2>
                                  </td>
                                </tr>
                              </table>
                              <table style=" border-collapse:collapse; width: 100%;">
                                <tr> 
                                  <td style="width: 60%;">
                                     <p> <?php echo $order->get_formatted_billing_address();  ?></p>
                                  </td>    
                                   <td style="width: 40%;">
                                      <p> Order Number:<?php  echo $order->get_order_number();  ?></p>
                                      <p> Order Date:<?php echo $order->get_date_created()->format ('M-d-Y'); ?></p>
                                      <p> Payment method:<?php  echo   $order->get_payment_method_title();  ?></p>
                                  </td> 
                                </tr>
                              </table>
                              <div class="table-add" style=" width:100%;margin: 0px;padding: 0px;">
                                  <table style=" border-collapse: collapse; width: 100%;">
                                      <th style="border: 1px solid #ddd; padding: 8px;  padding-top: 12px; padding-bottom: 12px;text-align: left;background-color: <?php echo get_option( 'ocpsw_bg_color' )?>;color: white; width:50%;" >Product</th>
                                      <th style="border: 1px solid #ddd; padding: 8px;  padding-top: 12px; padding-bottom: 12px;text-align: left;background-color: <?php echo get_option( 'ocpsw_bg_color' )?>;color: white;  width:25%; " >Quantity</th>
                                     <th style="border: 1px solid #ddd; padding: 8px;  padding-top: 12px; padding-bottom: 12px;text-align: left;background-color:<?php echo get_option( 'ocpsw_bg_color' )?>;color: white; wi4dth:25%; " >Price</th>
                                      <?php foreach($order->get_items() as $item) {?>
                                      <tr> 
                                          <td style="border: 1px solid #ddd; padding: 8px; color:#000"  >
                                            <?php echo  $product = $item['name'];?></td>
                                          <td style="border: 1px solid #ddd; padding: 8px; color:#000" ><?php  echo  $product= $item['qty']; 
                                            ?>
                                          </td>
                                          <td style="border: 1px solid #ddd; padding: 8px; color:#000"  >
                                            <?php echo  $product = $item['line_total'];?> 
                                          </td>
                                      </tr>
                                       <?php } ?>
                                  </table>
                              </div>
                              <div class="left-table"  style="width:100%; float:right;margin: 0px;padding: 0px;">    
                                <table  style="float:right;">
                                     <tr>
                                      <td style="padding:10px; border-bottom: 1px solid #ccc;">subtotal:<?php echo $order->get_subtotal_to_display(); ?></td>

                                     </tr>
                                     <tr>
                                       <td style="padding:10px; border-bottom: 1px solid #000;">Discount:<?php echo $order->get_discount_to_display(); ?> </td>
                                     </tr>
                                    
                                        <tr>
                                       <td style="padding:10px;  border-bottom: 1px solid #000;">Total:<?php echo $order->get_total(); ?> </td>
                                     </tr>
                                </table> 
                              </div>
                              <footer style=" position: fixed; bottom:-60px; left: 0px; right: 0px; text-align: center; border-top:1px solid #ddd; height: 50px;"><?php echo get_option( 'ocpsw_footer_text' );?></footer>
                            </body> 
                        </html>
                      </div>
                    <?php }else if (get_option( 'ocwqv_template_pos' ) == "temlate2"){
                    ?>
                      <div class="template2">
                        <html>
                            <head>
                                <title>Packing Slip</title>
                            </head>
                            <body>
                              <table style="border-collapse: collapse; width: 100%;">
                                <tr>
                                  <td style="width: 40%;">
                                     <h2>shop:<?php echo get_option( 'ocpsw_shop_title' );?></h2>
                                      <p><?php echo get_option( 'ocpsw_shop_addr' );?></p>
                                  </td> 
                                   <td>
                                      <?php $attachment_id=get_option( 'ocpsw_shop_imagelogo');
                                      
                                     echo wp_get_attachment_image( $attachment_id, array(225,125),$icon =false ); ?>
                                  </td>  
                                </tr>
                                <tr>  
                                  <td style="width: 60%;" >
                                          <h2>Packing Slip</h2>
                                  </td>
                                </tr>
                              </table>
                              <table style=" border-collapse:collapse; width: 100%;">
                                <th>Billing address</th>
                                <th>Shipping address</th>
                                <tr> 
                                  <td style="width: 30%;">
                                   
                                     <p> <?php echo $order->get_formatted_billing_address();  ?></p>
                                  </td> 
                                  <td  style="width: 30%;">
                                     
                                    <p> <?php echo $order->get_formatted_shipping_address();  ?></p>
                                  
                                  </td>   
                                   <td style="width: 40%;">
                                      <p>Order Number:<?php  echo $order->get_order_number();  ?></p>
                                      <p>Order Date:<?php echo $order->get_date_created()->format ('M-d-Y'); ?></p>
                                      <p>Payment method:<?php  echo   $order->get_payment_method_title();  ?></p>
                                  </td> 
                                </tr>
                              </table>
                              <div class="table-add" style=" width:100%;margin: 0px;padding: 0px;">
                                  <table style=" border-collapse: collapse; width: 100%;">
                                    <th style="border: 1px solid #ddd; padding: 8px;  padding-top: 12px; padding-bottom: 12px;text-align: left;background-color: <?php echo get_option( 'ocpsw_bg_color' )?>;color: white; width:50%;" >Image</th>
                                      <th style="border: 1px solid #ddd; padding: 8px;  padding-top: 12px; padding-bottom: 12px;text-align: left;background-color: <?php echo get_option( 'ocpsw_bg_color' )?>;color: white; width:50%;" >Product</th>
                                      <th style="border: 1px solid #ddd; padding: 8px;  padding-top: 12px; padding-bottom: 12px;text-align: left;background-color: <?php echo get_option( 'ocpsw_bg_color' )?>;color: white;  width:25%; " >Quantity</th>
                                      <th style="border: 1px solid #ddd; padding: 8px;  padding-top: 12px; padding-bottom: 12px;text-align: left;background-color: <?php echo get_option( 'ocpsw_bg_color' )?>;color: white; wi4dth:25%; " >Price</th>
                                      <?php foreach($order->get_items() as $key => $item) {?>
                                      <tr> 
                                        <td style="border: 1px solid #ddd; padding: 8px; color:#000 width:10%"> 
                                         <?php  $product_id = $item['product_id'];
                                          $product = wc_get_product( $product_id );
                                       $image = wp_get_attachment_image_src( get_post_thumbnail_id($product_id),  array(100,100));?>
                                        <img    src="<?php  echo $image[0]; ?>" data-id="<?php echo ($product_id); ?>">
                                          </td>
                                        </td>
                                          <td style="border: 1px solid #ddd; padding: 8px; color:#000"  >
                                            <?php echo  $product = $item['name'];?></td>
                                          <td style="border: 1px solid #ddd; padding: 8px; color:#000" ><?php  echo  $product= $item['qty']; 
                                            ?>
                                          </td>
                                          <td style="border: 1px solid #ddd; padding: 8px; color:#000"  >
                                            <?php echo  $product = $item['line_total'];?> 
                                          </td>
                                      </tr>
                                       <?php } ?>
                                  </table>
                              </div>
                              <div class="left-table"  style="width:100%; float:right;margin:0px;padding: 0px;"> 
                                <table  style="float:right;">
                                     <tr>
                                      <td style="padding:10px; border-bottom: 1px solid #ccc;">subtotal:<?php echo $order->get_subtotal_to_display(); ?></td>

                                     </tr>
                                     <tr>
                                       <td style="padding:10px;  border-bottom: 1px solid #000;">Discount:<?php echo $order->get_discount_to_display(); ?> </td>
                                     </tr>
                                    
                                        <tr>
                                       <td style="padding:10px;  border-bottom: 1px solid #000;">Total:<?php echo $order->get_total(); ?> </td>
                                     </tr>
                                </table> 
                              </div>
                              <footer style=" position: fixed; bottom: -60px; left: 0px; right: 0px; text-align: center; border-top:1px solid #ddd; height: 50px;"><?php echo get_option( 'ocpsw_footer_text' );?></footer>
                            </body> 
                        </html>
                      </div>
                    <?php
                }
                else{}
                $html=ob_get_clean();
                
                $dompdf = new Dompdf();
               
                $dompdf->loadHtml($html);
          
                $dompdf->setPaper('A4', 'portrait');
                
                $dompdf->render();
            
                $dompdf->stream( $_REQUEST['order_id'].'.pdf', array("Attachment" => false));

                }
            }
            

            function OCPSW_me_post_pdf_invoice(){
               
              if(isset($_REQUEST['order_id'])){
                $order = wc_get_order($_REQUEST['order_id']);
              }
           
               if(isset($_REQUEST['action']) && $_REQUEST['action'] == "woo_pdf_invoice"){

                include_once(plugin_dir_path( __FILE__ ).'dompdf/autoload.inc.php');
                ob_start();
                if(get_option( 'ocwqv_template_pos' ) == "temlate1"){
                    ?>
                      <div class="template1" >
                        <html>
                            <head>
                                <title>Packing invoice</title>
                            </head>
                            <body>
                              <table style="border-collapse:collapse; width: 100%;">
                                <tr>
                                  <td>
                                      <?php $attachment_id=get_option( 'ocpsw_shop_imagelogo');
                                     echo wp_get_attachment_image( $attachment_id, array(225,125),$icon =false ); ?>
                                  </td>
                                   <td style="width: 40%;">
                                     <h2>shop:<?php echo get_option( 'ocpsw_shop_title' );?></h2>
                                      <p><?php echo get_option( 'ocpsw_shop_addr' );?></p>
                                  </td>  
                                  </td> 
                                </tr>
                                <tr>  
                                  <td style="width: 60%;" >
                                          <h2>Invoice</h2>
                                  </td>
                                </tr>
                              </table>
                              <table style=" border-collapse:collapse; width: 100%;">
                                <tr> 
                                  <td style="width: 60%;">
                                     <p> <?php echo $order->get_formatted_billing_address();  ?></p>
                                  </td>    
                                   <td style="width: 40%;">
                                      <p> Order Number:<?php  echo $order->get_order_number();  ?></p>
                                      <p> Order Date:<?php echo $order->get_date_created()->format ('M-d-Y'); ?></p>
                                      <p> Payment method:<?php  echo   $order->get_payment_method_title();  ?></p>
                                  </td> 
                                </tr>
                              </table>
                              <div class="table-add" style=" width:100%;margin: 0px;padding: 0px;">
                                  <table style=" border-collapse: collapse; width: 100%;">
                                      <th style="border: 1px solid #ddd; padding: 8px;  padding-top: 12px; padding-bottom: 12px;text-align: left;background-color: <?php echo get_option( 'ocpsw_bg_color' )?>;color: white; width:50%;" >Product</th>
                                      <th style="border: 1px solid #ddd; padding: 8px;  padding-top: 12px; padding-bottom: 12px;text-align: left;background-color: <?php echo get_option( 'ocpsw_bg_color' )?>;color: white;  width:25%; " >Quantity</th>
                                     <th style="border: 1px solid #ddd; padding: 8px;  padding-top: 12px; padding-bottom: 12px;text-align: left;background-color:<?php echo get_option( 'ocpsw_bg_color' )?>;color: white; wi4dth:25%; " >Price</th>
                                      <?php foreach($order->get_items() as $item) {?>
                                      <tr> 
                                          <td style="border: 1px solid #ddd; padding: 8px; color:#000"  >
                                            <?php echo  $product = $item['name'];?></td>
                                          <td style="border: 1px solid #ddd; padding: 8px; color:#000" ><?php  echo  $product= $item['qty']; 
                                            ?>
                                          </td>
                                          <td style="border: 1px solid #ddd; padding: 8px; color:#000"  >
                                            <?php echo  $product = $item['line_total'];?> 
                                          </td>
                                      </tr>
                                       <?php } ?>
                                  </table>
                              </div>
                              <div class="left-table"  style="width:100%; float:right;margin: 0px;padding: 0px;">    
                                <table  style="float:right;">
                                     <tr>
                                      <td style="padding:10px; border-bottom: 1px solid #ccc;">subtotal:<?php echo $order->get_subtotal_to_display(); ?></td>

                                     </tr>
                                     <tr>
                                       <td style="padding:10px; border-bottom: 1px solid #000;">Discount:<?php echo $order->get_discount_to_display(); ?> </td>
                                     </tr>
                                    
                                        <tr>
                                       <td style="padding:10px;  border-bottom: 1px solid #000;">Total:<?php echo $order->get_total(); ?> </td>
                                     </tr>
                                </table> 
                              </div>
                              <footer style=" position: fixed; bottom:-60px; left: 0px; right: 0px; text-align: center; border-top:1px solid #ddd; height: 50px;"><?php echo get_option( 'ocpsw_footer_text' );?></footer>
                            </body> 
                        </html>
                      </div>
                    <?php }else if (get_option( 'ocwqv_template_pos' ) == "temlate2"){
                    ?>
                      <div class="template2">
                        <html>
                            <head>
                                <title>Packing Invoice</title>
                            </head>
                            <body>
                              <table style="border-collapse: collapse; width: 100%;">
                                <tr>
                                  <td style="width: 40%;">
                                     <h2>shop:<?php echo get_option('ocpsw_shop_title' );?></h2>
                                      <p><?php echo get_option( 'ocpsw_shop_addr' );?></p>
                                  </td> 
                                   <td>
                                      <?php $attachment_id=get_option( 'ocpsw_shop_imagelogo');      
                                     echo wp_get_attachment_image( $attachment_id, array(225,125),$icon =false ); ?>
                                  </td>  
                                </tr>
                                <tr>  
                                  <td style="width: 60%;">
                                        <h2>Invoice</h2>
                                  </td>
                                </tr>
                              </table>
                              <table style=" border-collapse:collapse; width: 100%;">
                                <th>Billing address</th>
                                <th>Shipping address</th>
                                <tr> 
                                  <td style="width: 30%;">
                                   
                                     <p> <?php echo $order->get_formatted_billing_address();  ?></p>
                                  </td> 
                                  <td  style="width: 30%;">
                                     
                                    <p> <?php echo $order->get_formatted_shipping_address();  ?></p>
                                  
                                  </td>   
                                   <td style="width: 40%;">
                                      <p>Order Number:<?php  echo $order->get_order_number();  ?></p>
                                      <p>Order Date:<?php echo $order->get_date_created()->format ('M-d-Y'); ?></p>
                                      <p>Payment method:<?php  echo   $order->get_payment_method_title();  ?></p>
                                  </td> 
                                </tr>
                              </table>
                              <div class="table-add" style=" width:100%;margin: 0px;padding: 0px;">
                                  <table style=" border-collapse: collapse; width: 100%;">
                                    <th style="border: 1px solid #ddd; padding: 8px;  padding-top: 12px; padding-bottom: 12px;text-align: left;background-color: <?php echo get_option( 'ocpsw_bg_color' )?>;color: white; width:50%;" >Image</th>
                                      <th style="border: 1px solid #ddd; padding: 8px;  padding-top: 12px; padding-bottom: 12px;text-align: left;background-color: <?php echo get_option( 'ocpsw_bg_color' )?>;color: white; width:50%;" >Product</th>
                                      <th style="border: 1px solid #ddd; padding: 8px;  padding-top: 12px; padding-bottom: 12px;text-align: left;background-color: <?php echo get_option( 'ocpsw_bg_color' )?>;color: white;  width:25%; " >Quantity</th>
                                      <th style="border: 1px solid #ddd; padding: 8px;  padding-top: 12px; padding-bottom: 12px;text-align: left;background-color: <?php echo get_option( 'ocpsw_bg_color' )?>;color: white; wi4dth:25%; " >Price</th>
                                      <?php foreach($order->get_items() as $key => $item) {?>
                                      <tr> 
                                        <td style="border: 1px solid #ddd; padding: 8px; color:#000 width:10%"> 
                                         <?php  $product_id = $item['product_id'];
                                          $product = wc_get_product( $product_id );
                                       $image = wp_get_attachment_image_src( get_post_thumbnail_id($product_id),  array(100,100));?>
                                        <img    src="<?php  echo $image[0]; ?>" data-id="<?php echo ($product_id); ?>">
                                          </td>
                                        </td>
                                          <td style="border: 1px solid #ddd; padding: 8px; color:#000"  >
                                            <?php echo  $product = $item['name'];?></td>
                                          <td style="border: 1px solid #ddd; padding: 8px; color:#000" ><?php  echo  $product= $item['qty']; 
                                            ?>
                                          </td>
                                          <td style="border: 1px solid #ddd; padding: 8px; color:#000"  >
                                            <?php echo  $product = $item['line_total'];?> 
                                          </td>
                                      </tr>
                                       <?php } ?>
                                  </table>
                              </div>
                              <div class="left-table"  style="width:100%; float:right;margin:0px;padding: 0px;"> 
                                <table  style="float:right;">
                                     <tr>
                                      <td style="padding:10px; border-bottom: 1px solid #ccc;">subtotal:<?php echo $order->get_subtotal_to_display(); ?></td>

                                     </tr>
                                     <tr>
                                       <td style="padding:10px;  border-bottom: 1px solid #000;">Discount:<?php echo $order->get_discount_to_display(); ?> </td>
                                     </tr>
                                    
                                        <tr>
                                       <td style="padding:10px;  border-bottom: 1px solid #000;">Total:<?php echo $order->get_total(); ?> </td>
                                     </tr>
                                </table> 
                              </div>
                              <footer style=" position: fixed; bottom: -60px; left: 0px; right: 0px; text-align: center; border-top:1px solid #ddd; height: 50px;"><?php echo get_option( 'ocpsw_footer_text' );?></footer>
                            </body> 
                        </html>
                      </div>
                    <?php
                }
                else{}
                $html=ob_get_clean();
                
                $dompdf = new Dompdf();
               
                $dompdf->loadHtml($html);
          
                $dompdf->setPaper('A4', 'portrait');
                
                $dompdf->render();
            
                $dompdf->stream( $_REQUEST['order_id'].'.pdf', array("Attachment" => false));

                }
            }


            function OCPSW_downloads_bulk_actions_edit_product( $actions ) {
                  $actions['write_downloads'] = __( 'PDF Packing Slip', 'woocommerce' );
                  $actions['write_download_invoice'] = __( 'PDF invoice', 'woocommerce' );
                  return $actions;
            }
            
         
            function OCPSW_downloads_handle_bulk_action_edit_shop_order( $redirect_to, $action, $post_ids ) {
                if ( $action !== 'write_downloads' )
                    return $redirect_to; // Exit

                global $attach_download_dir, $attach_download_file;
                 
                $html = <<<HTML

<html>
    <head>
        <style type="text/css">
            .teacherPage {
           page: teacher;

           page-break-after: always;
        }
         footer { position: fixed; bottom: -60px; left: 0px; right: 0px; text-align: center; border-top:1px solid #ddd; height: 50px; }
        </style>
    </head>
    <body>
HTML;
                   
            foreach ( $post_ids as $post_id ) {
                    
                    $order = wc_get_order( $post_id );

                    $order_data = $order->get_data();
                       
                        include_once(plugin_dir_path( __FILE__ ).'dompdf/autoload.inc.php');
                         if(get_option( 'ocwqv_template_pos' ) == "temlate1"){
                              $html.= '<div class="teacherPage">
                                    <title>Packing Slip</title>
                                    <table style=" border-collapse: collapse; width: 100%;"> 
                                    <td>'.wp_get_attachment_image( get_option( 'ocpsw_shop_imagelogo'), array(225,125),$icon = false ).'</td>
                                        <td style="width: 40%;">
                                           <h2>shop:'.get_option( 'ocpsw_shop_title' ).'</h2> 
                                            <p>'.get_option( 'ocpsw_shop_addr' ).'</p>
                                        </td>
                                      <tr>
                                        <td style="width: 60%;">
                                          <h2 >Packing Slip</h2>
                                        </td>  
                                      </tr>
                                      </table>
                                      <table style=" border-collapse: collapse; width: 100%;">
                                          <tr> 
                                            <td style="width: 60%;">
                                                <h4>Billing address</h4>
                                               <p>'.$order->get_formatted_billing_address().'</p>
                                            </td>    
                                             <td style="width: 40%;">
                                                <p>  Order Number:'.$order->get_order_number().'</p>
                                                <p>  Order Date:'.$order->get_date_created()->format ('M-d-Y').'</p>
                                                <p>  Payment method:'.$order->get_payment_method_title().'</p>
                                            </td> 
                                          </tr>
                                      </table>
                                    <div class="table-add" style=" width:100%;">
                                        <table style=" border-collapse: collapse; width: 100%;">
                                            <th style="border: 1px solid #ddd; padding: 8px;  padding-top: 12px; padding-bottom: 12px;text-align: left;background-color: '. get_option( 'ocpsw_bg_color' ).';color: white; width:50%;" >Product</th>
                                            <th style="border: 1px solid #ddd; padding: 8px;  padding-top: 12px; padding-bottom: 12px;text-align: left;background-color: '.get_option( 'ocpsw_bg_color' ).';color: white;  width:25%; " >Quantity</th>
                                           <th style="border: 1px solid #ddd; padding: 8px;  padding-top: 12px; padding-bottom: 12px;text-align: left;background-color:'.get_option( 'ocpsw_bg_color' ).';color: white; width:25%; " >Price</th>';
                                             foreach($order->get_items() as $item) {
                                                $html.=
                                            '<tr> 
                                                <td style="border: 1px solid #ddd; padding: 8px; color:#000"  >
                                                  '. $product = $item['name'].'</td>
                                                <td style="border: 1px solid #ddd; padding: 8px; color:#000" >'.  $product= $item['qty'] 
                                                  .'</td>
                                                  <td style="border: 1px solid #ddd; padding: 8px; color:#000"  >
                                                  '. $product = $item['line_total'] .'</td>
                                             
                                            </tr>';
                                             } 

                                             $html.=
                                        '</table>
                                    </div>
                                    <div class="left-table"  style="width:100%; float:right;">
                                       <table  style="float:right;">
                                           <tr >
                                            <td style="padding:10px; border-bottom: 1px solid #ccc;">subtotal:'.$order->get_subtotal_to_display() .'</td>

                                           </tr>
                                           <tr>
                                             <td style="padding:10px;  border-bottom: 1px solid #000;">Discount:'.$order->get_discount_to_display() .'</td>
                                           </tr>
                                          
                                              <tr>
                                             <td style="padding:10px;  border-bottom: 1px solid #000;">Total:'. $order->get_total().' </td>
                                           </tr>
                                       </table> 
                                    </div>
                                    <footer>'.get_option( 'ocpsw_footer_text' ).'</footer>
                                </div>';
                                $html.= '</body></html>';
                              }else if(get_option( 'ocwqv_template_pos' ) == "temlate2"){
                                 $html.= '<div class="teacherPage">
                                    <title>Packing Slip</title>

                                    <table style=" border-collapse: collapse; width: 100%;">
                                      <td style="width: 40%;">
                                           <h2>shop:'.get_option( 'ocpsw_shop_title' ).'</h2> 
                                            <p>'.get_option( 'ocpsw_shop_addr' ).'</p>
                                      </td> 
                                     <td>'.wp_get_attachment_image( get_option( 'ocpsw_shop_imagelogo'), array(225,125),$icon = false ).'</td>
                                        
                                      <tr>
                                        <td style="width: 60%;">
                                          <h2 >Packing Slip</h2>
                                        </td>
                                      </tr>
                                      </table>
                                      <table style=" border-collapse: collapse; width: 100%;">
                                          <tr> 
                                            <td style="width: 30%;">
                                                <h4>Billing address</h4>
                                               <p>'.$order->get_formatted_billing_address().'</p>
                                            </td>
                                            <td  style="width: 30%;">
                                                <p> '. $order->get_formatted_shipping_address().' </p>
                                             </td>      
                                             <td style="width: 40%;">
                                                <p>  Order Number:'.$order->get_order_number().'</p>
                                                <p>  Order Date:'.$order->get_date_created()->format ('M-d-Y').'</p>
                                                <p>  Payment method:'.$order->get_payment_method_title().'</p>
                                            </td> 
                                          </tr>
                                      </table>
                                    <div class="table-add" style=" width:100%;">
                                        <table style=" border-collapse: collapse; width: 100%;">
                                            <th style="border: 1px solid #ddd; padding: 8px;  padding-top: 12px; padding-bottom: 12px;text-align: left;background-color: '.get_option( 'ocpsw_bg_color' ).';color: white; width:50%;" >Image</th>
                                            <th style="border: 1px solid #ddd; padding: 8px;  padding-top: 12px; padding-bottom: 12px;text-align: left;background-color:'.get_option( 'ocpsw_bg_color' ).';color: white; width:50%;" >Product</th>
                                            <th style="border: 1px solid #ddd; padding: 8px;  padding-top: 12px; padding-bottom: 12px;text-align: left;background-color: '.get_option( 'ocpsw_bg_color' ).';color: white;  width:25%; " >Quantity</th>
                                           <th style="border: 1px solid #ddd; padding: 8px;  padding-top: 12px; padding-bottom: 12px;text-align: left;background-color:'.get_option( 'ocpsw_bg_color' ).';color: white; width:25%; " >Price</th>';
                                             foreach($order->get_items() as $item) {
                                                $html.=
                                            '<tr> 
                                                <td style="border: 1px solid #ddd; padding: 8px; color:#000 width:10%"> 
                                                  '; $product_id = $item['product_id'];
                                                    $product = wc_get_product( $product_id );
                                                    $image = wp_get_attachment_image_src( get_post_thumbnail_id($product_id),  array(100,100));
                                                    $html.= '
                                                   <img src='. $image[0].' data-id='.$product_id.'>
                                                </td>
                                                <td style="border: 1px solid #ddd; padding: 8px; color:#000"  >
                                                  '. $product = $item['name'].'</td>
                                                <td style="border: 1px solid #ddd; padding: 8px; color:#000" >'.  $product= $item['qty'] 
                                                  .'</td>
                                                  <td style="border: 1px solid #ddd; padding: 8px; color:#000"  >
                                                  '. $product = $item['line_total'] .'</td>
                                             
                                            </tr>';
                                             } 

                                             $html.=
                                        '</table>
                                    </div>
                                    <div class="left-table"  style="width:100%; float:right;">
                                       <table  style="float:right;">
                                           <tr >
                                            <td style="padding:10px; border-bottom: 1px solid #ccc;">subtotal:'.$order->get_subtotal_to_display() .'</td>

                                           </tr>
                                           <tr>
                                             <td style="padding:10px;  border-bottom: 1px solid #000;">Discount:'.$order->get_discount_to_display() .'</td>
                                           </tr>
                                          
                                              <tr>
                                             <td style="padding:10px;  border-bottom: 1px solid #000;">Total:'.$order->get_total().' </td>
                                           </tr>
                                       </table> 
                                    </div>
                                    <footer>'.get_option( 'ocpsw_footer_text' ).'</footer>
                                          </div>';
                                          $html.= '</body></html>';
                              }else{}

                    }
                
                    $dompdf = new Dompdf();
        
                    $dompdf->loadHtml($html);

                    $dompdf->setPaper('A4', 'portrait');
                    
                    $dompdf->render();
                  
                    $output = $dompdf->output();
                   
                    $dompdf->stream("vender_contract_product.pdf", array("Attachment" => false));
                  
               
            }


            function OCPSW_downloads_handle_bulk_action_edit_shop_order_invoice( $redirect_to, $action, $post_ids ) {
                if ( $action !== 'write_download_invoice' )
                    return $redirect_to; // Exit

                global $attach_download_dir, $attach_download_file;
                 
                $html = <<<HTML

<html>
    <head>
        <style type="text/css">
            .teacherPage {
           page: teacher;

           page-break-after: always;
        }
         footer { position: fixed; bottom: -60px; left: 0px; right: 0px; text-align: center; border-top:1px solid #ddd; height: 50px; }
        </style>
    </head>
    <body>
HTML;
                   
            foreach ( $post_ids as $post_id ) {
                    
                    $order = wc_get_order( $post_id );


                    $order_data = $order->get_data();
                       
                        include_once(plugin_dir_path( __FILE__ ).'dompdf/autoload.inc.php');
                         if(get_option( 'ocwqv_template_pos' ) == "temlate1"){
                              $html.= '<div class="teacherPage">
                                    <title>Invoice</title>
                                    <table style=" border-collapse: collapse; width: 100%;"> 
                                    <td>'.wp_get_attachment_image( get_option( 'ocpsw_shop_imagelogo'), array(225,125),$icon = false ).'</td>
                                        <td style="width: 40%;">
                                           <h2>shop:'.get_option( 'ocpsw_shop_title' ).'</h2> 
                                            <p>'.get_option( 'ocpsw_shop_addr' ).'</p>
                                        </td>
                                      <tr>
                                        <td style="width: 60%;">
                                          <h2 >Invoice</h2>
                                        </td>  
                                      </tr>
                                      </table>
                                      <table style=" border-collapse: collapse; width: 100%;">
                                          <tr> 
                                            <td style="width: 60%;">
                                                <h4>Billing address</h4>
                                               <p>'.$order->get_formatted_billing_address().'</p>
                                            </td>    
                                             <td style="width: 40%;">
                                                <p>  Order Number:'.$order->get_order_number().'</p>
                                                <p>  Order Date:'.$order->get_date_created()->format ('M-d-Y').'</p>
                                                <p>  Payment method:'.$order->get_payment_method_title().'</p>
                                            </td> 
                                          </tr>
                                      </table>
                                    <div class="table-add" style=" width:100%;">
                                        <table style=" border-collapse: collapse; width: 100%;">
                                            <th style="border: 1px solid #ddd; padding: 8px;  padding-top: 12px; padding-bottom: 12px;text-align: left;background-color: '. get_option( 'ocpsw_bg_color' ).';color: white; width:50%;" >Product</th>
                                            <th style="border: 1px solid #ddd; padding: 8px;  padding-top: 12px; padding-bottom: 12px;text-align: left;background-color: '.get_option( 'ocpsw_bg_color' ).';color: white;  width:25%; " >Quantity</th>
                                           <th style="border: 1px solid #ddd; padding: 8px;  padding-top: 12px; padding-bottom: 12px;text-align: left;background-color:'.get_option( 'ocpsw_bg_color' ).';color: white; width:25%; " >Price</th>';
                                             foreach($order->get_items() as $item) {
                                                $html.=
                                            '<tr> 
                                                <td style="border: 1px solid #ddd; padding: 8px; color:#000"  >
                                                  '. $product = $item['name'].'</td>
                                                <td style="border: 1px solid #ddd; padding: 8px; color:#000" >'.  $product= $item['qty'] 
                                                  .'</td>
                                                  <td style="border: 1px solid #ddd; padding: 8px; color:#000"  >
                                                  '. $product = $item['line_total'] .'</td>
                                             
                                            </tr>';
                                             } 

                                             $html.=
                                        '</table>
                                    </div>
                                    <div class="left-table"  style="width:100%; float:right;">
                                       <table  style="float:right;">
                                           <tr >
                                            <td style="padding:10px; border-bottom: 1px solid #ccc;">subtotal:'.$order->get_subtotal_to_display() .'</td>

                                           </tr>
                                           <tr>
                                             <td style="padding:10px;  border-bottom: 1px solid #000;">Discount:'. $order->get_discount_to_display() .'</td>
                                           </tr>
                                          
                                              <tr>
                                             <td style="padding:10px;  border-bottom: 1px solid #000;">Total:'. $order->get_total().' </td>
                                           </tr>
                                       </table> 
                                    </div>
                                    <footer>'.get_option( 'ocpsw_footer_text' ).'</footer>
                                </div>';
                                $html.= '</body></html>';
                              }else if(get_option( 'ocwqv_template_pos' ) == "temlate2"){
                                 $html.= '<div class="teacherPage">
                                    <title>Invoice</title>
                                    <table style=" border-collapse: collapse; width: 100%;">
                                      <td style="width: 40%;">
                                           <h2>shop:'.get_option( 'ocpsw_shop_title' ).'</h2> 
                                            <p>'.get_option( 'ocpsw_shop_addr' ).'</p>
                                      </td> 
                                     <td>'.wp_get_attachment_image( get_option( 'ocpsw_shop_imagelogo'), array(225,125),$icon = false ).'</td>    
                                      <tr>
                                        <td style="width: 60%;">
                                          <h2 >Invoice</h2>
                                        </td>
                                      </tr>
                                      </table>
                                      <table style=" border-collapse: collapse; width: 100%;">
                                          <tr> 
                                            <td style="width: 30%;">
                                                <h4>Billing address</h4>
                                               <p>'.$order->get_formatted_billing_address().'</p>
                                            </td>
                                            <td  style="width: 30%;">
                                             <h4>Shipping address</h4>
                                                <p> '. $order->get_formatted_shipping_address().' </p>
                                             </td>      
                                             <td style="width: 40%;">
                                                <p>  Order Number:'.$order->get_order_number().'</p>
                                                <p>  Order Date:'.$order->get_date_created()->format ('M-d-Y').'</p>
                                                <p>  Payment method:'.$order->get_payment_method_title().'</p>
                                            </td> 
                                          </tr>
                                      </table>
                                    <div class="table-add" style=" width:100%;">
                                        <table style=" border-collapse: collapse; width: 100%;">
                                            <th style="border: 1px solid #ddd; padding: 8px;  padding-top: 12px; padding-bottom: 12px;text-align: left;background-color: '.get_option( 'ocpsw_bg_color' ).';color: white; width:50%;" >Image</th>
                                            <th style="border: 1px  #ddd; padding: 8px;  padding-top: 12px; padding-bottom: 12px;text-align: left;background-color:'.get_option( 'ocpsw_bg_color' ).';color: white; width:50%;" >Product</th>
                                            <th style="border: 1px solid #ddd; padding: 8px;  padding-top: 12px; padding-bottom: 12px;text-align: left;background-color: '.get_option( 'ocpsw_bg_color' ).';color: white;  width:25%; " >Quantity</th>
                                           <th style="border: 1px solid #ddd; padding: 8px;  padding-top: 12px; padding-bottom: 12px;text-align: left;background-color:'.get_option( 'ocpsw_bg_color' ).';color: white; width:25%; " >Price</th>';
                                             foreach($order->get_items() as $item) {
                                                $html.=
                                            '<tr> 
                                                <td style="border: 1px solid #ddd; padding: 8px; color:#000 width:10%"> 
                                                  '; $product_id = $item['product_id'];
                                                    $product = wc_get_product( $product_id );
                                                    $image = wp_get_attachment_image_src( get_post_thumbnail_id($product_id),  array(100,100));
                                                    $html.= '
                                                   <img src='. $image[0].' data-id='.$product_id.'>
                                                </td>
                                                <td style="border: 1px solid #ddd; padding: 8px; color:#000"  >
                                                  '. $product = $item['name'].'</td>
                                                <td style="border: 1px solid #ddd; padding: 8px; color:#000" >'.  $product= $item['qty'] 
                                                  .'</td>
                                                  <td style="border: 1px solid #ddd; padding: 8px; color:#000"  >
                                                  '. $product = $item['line_total'] .'</td>
                                             
                                            </tr>';
                                             } 

                                             $html.=
                                        '</table>
                                    </div>
                                    <div class="left-table"  style="width:100%; float:right;">
                                       <table  style="float:right;">
                                           <tr >
                                            <td style="padding:10px; border-bottom: 1px solid #ccc;">subtotal:'.$order->get_subtotal_to_display() .'</td>

                                           </tr>
                                           <tr>
                                             <td style="padding:10px;  border-bottom: 1px solid #000;">Discount:'.$order->get_discount_to_display() .'</td>
                                           </tr>
                                          
                                              <tr>
                                             <td style="padding:10px;  border-bottom: 1px solid #000;">Total:'.$order->get_total().' </td>
                                           </tr>
                                       </table> 

                                    </div>
                                    <footer>'.get_option( 'ocpsw_footer_text' ).'</footer>
                                          </div>';
                                          $html.= '</body></html>';
                              }else{}

                    }
                
                    $dompdf = new Dompdf();
        
                    $dompdf->loadHtml($html);

                    $dompdf->setPaper('A4', 'portrait');
                    
                    $dompdf->render();
                  
                    $output = $dompdf->output();
                   
                    $dompdf->stream("vender_contract_product.pdf", array("Attachment" => false));
                  
               
            }

            function OCPSW_attach_terms_conditions_pdf_to_email ( $attachments, $status , $order ) {
            
                $allowed_statuses =array('new_order');
               if( isset( $status ) && in_array ( $status, $allowed_statuses ) ) {
             
                $order_id=$order->get_id();
                include_once(plugin_dir_path( __FILE__ ).'dompdf/autoload.inc.php');
                ob_start();
                if(get_option( 'ocwqv_template_pos' ) == "temlate1"){
                    ?>
                      <div class="template1" >
                        <html>
                            <head>
                                <title>Packing Slip</title>
                            </head>
                            <body>
                              <table style=" border-collapse: collapse; width: 100%;">
                                <tr>
                                  <td>
                                      <?php $attachment_id=get_option( 'ocpsw_shop_imagelogo');
                                     echo wp_get_attachment_image( $attachment_id, array(225,125),$icon =false ); ?>
                                  </td>
                                   <td style="width: 40%;">
                                     <h2>shop:<?php echo get_option( 'ocpsw_shop_title' );?></h2>
                                      <p><?php echo get_option( 'ocpsw_shop_addr' );?></p>
                                  </td>  
                                  </td> 
                                </tr>
                                <tr>  
                                  <td style="width: 60%;" >
                                          <h2>Packing Slip</h2>
                                  </td>
                                </tr>
                              </table>
                              <table style=" border-collapse:collapse; width: 100%;">
                                <tr> 
                                  <td style="width: 60%;">
                                     <p> <?php echo $order->get_formatted_billing_address();  ?></p>
                                  </td>    
                                   <td style="width: 40%;">
                                      <p> Order Number:<?php  echo $order->get_order_number();  ?></p>
                                      <p> Order Date:<?php echo $order->get_date_created()->format ('M-d-Y'); ?></p>
                                      <p> Payment method:<?php  echo   $order->get_payment_method_title();  ?></p>
                                  </td> 
                                </tr>
                              </table>
                              <div class="table-add" style=" width:100%;margin: 0px;padding: 0px;">
                                  <table style=" border-collapse: collapse; width: 100%;">
                                      <th style="border: 1px solid #ddd; padding: 8px;  padding-top: 12px; padding-bottom: 12px;text-align: left;background-color: <?php echo get_option( 'ocpsw_bg_color' )?>;color: white; width:50%;" >Product</th>
                                      <th style="border: 1px solid #ddd; padding: 8px;  padding-top: 12px; padding-bottom: 12px;text-align: left;background-color: <?php echo get_option( 'ocpsw_bg_color' )?>;color: white;  width:25%; " >Quantity</th>
                                     <th style="border: 1px solid #ddd; padding: 8px;  padding-top: 12px; padding-bottom: 12px;text-align: left;background-color:<?php echo get_option( 'ocpsw_bg_color' )?>;color: white; wi4dth:25%; " >Price</th>
                                      <?php foreach($order->get_items() as $item) {?>
                                      <tr> 
                                          <td style="border: 1px solid #ddd; padding: 8px; color:#000"  >
                                            <?php echo  $product = $item['name'];?></td>
                                          <td style="border: 1px solid #ddd; padding: 8px; color:#000" ><?php  echo  $product= $item['qty']; 
                                            ?>
                                          </td>
                                          <td style="border: 1px solid #ddd; padding: 8px; color:#000"  >
                                            <?php echo  $product = $item['line_total'];?> 
                                          </td>
                                      </tr>
                                       <?php } ?>
                                  </table>
                              </div>
                              <div class="left-table"  style="width:100%; float:right;margin: 0px;padding:0px;">    
                                <table  style="float:right;">
                                     <tr>
                                      <td style="padding:10px; border-bottom: 1px solid #ccc;">subtotal:<?php echo $order->get_subtotal_to_display(); ?></td>

                                     </tr>
                                     <tr>
                                       <td style="padding:10px;  border-bottom: 1px solid #000;">Discount:<?php echo $order->get_discount_to_display(); ?> </td>
                                     </tr>
                                    
                                        <tr>
                                       <td style="padding:10px;  border-bottom: 1px solid #000;">Total:<?php echo $order->get_total(); ?> </td>
                                     </tr>
                                </table> 
                              </div>
                              <footer style=" position: fixed; bottom:-60px; left: 0px; right: 0px; text-align: center; border-top:1px solid #ddd; height: 50px;"><?php echo get_option( 'ocpsw_footer_text' );?></footer>
                            </body> 
                        </html>
                      </div>
                    <?php }else if (get_option( 'ocwqv_template_pos' ) == "temlate2"){
                    ?>
                      <div class="template2">
                        <html>
                            <head>
                                <title>Packing Slip</title>
                            </head>
                            <body>
                              <table style=" border-collapse: collapse; width: 100%;">
                                <tr>     
                                  <td style="width: 40%;">
                                     <h2>shop:<?php echo get_option( 'ocpsw_shop_title' );?></h2>
                                      <p><?php echo get_option( 'ocpsw_shop_addr' );?></p>
                                  </td> 
                                   <td>
                                      <?php $attachment_id=get_option( 'ocpsw_shop_imagelogo');
                                      
                                     echo wp_get_attachment_image( $attachment_id, array(225,125),$icon =false ); ?>
                                  </td>  
                                </tr>
                                <tr>  
                                  <td style="width: 60%;" >
                                          <h2>Packing Slip</h2>
                                  </td>
                                </tr>
                              </table>
                              <table style=" border-collapse:collapse; width: 100%;">
                                <th>Billing address</th>
                                <th>Shipping address</th>
                                <tr> 
                                  <td style="width: 30%;">
                                     <p> <?php echo $order->get_formatted_billing_address();  ?></p>
                                  </td> 
                                  <td  style="width: 30%;">
                                    <p> <?php echo $order->get_formatted_shipping_address();  ?></p>
                                  </td>   
                                   <td style="width: 40%;">
                                      <p> Order Number:<?php  echo $order->get_order_number();  ?></p>
                                      <p> Order Date:<?php echo $order->get_date_created()->format ('M-d-Y'); ?></p>
                                      <p> Payment method:<?php  echo   $order->get_payment_method_title();  ?></p>
                                  </td> 
                                </tr>
                              </table>
                              <div class="table-add" style=" width:100%;margin: 0px;padding: 0px;">
                                  <table style=" border-collapse: collapse; width: 100%;">
                                    <th style="border: 1px solid #ddd; padding: 8px;  padding-top: 12px; padding-bottom: 12px;text-align: left;background-color: <?php echo get_option( 'ocpsw_bg_color' )?>;color: white; width:50%;" >Image</th>
                                      <th style="border: 1px solid #ddd; padding: 8px;  padding-top: 12px; padding-bottom: 12px;text-align: left;background-color: <?php echo get_option( 'ocpsw_bg_color' )?>;color: white; width:50%;" >Product</th>
                                      <th style="border: 1px solid #ddd; padding: 8px;  padding-top: 12px; padding-bottom: 12px;text-align: left;background-color: <?php echo get_option( 'ocpsw_bg_color' )?>;color: white;  width:25%; " >Quantity</th>
                                      <th style="border: 1px solid #ddd; padding: 8px;  padding-top: 12px; padding-bottom: 12px;text-align: left;background-color: <?php echo get_option( 'ocpsw_bg_color' )?>;color: white; wi4dth:25%; " >Price</th>
                                      <?php foreach($order->get_items() as $key => $item) {?>
                                      <tr> 
                                        <td style="border: 1px solid #ddd; padding: 8px; color:#000 width:10%"> 
                                         <?php  $product_id = $item['product_id'];
                                          $product = wc_get_product( $product_id );
                                       $image = wp_get_attachment_image_src( get_post_thumbnail_id($product_id),  array(100,100));?>
                                        <img    src="<?php  echo $image[0]; ?>" data-id="<?php echo ($product_id); ?>">
                                          </td>
                                        </td>
                                          <td style="border: 1px solid #ddd; padding: 8px; color:#000"  >
                                            <?php echo  $product = $item['name'];?></td>
                                          <td style="border: 1px solid #ddd; padding: 8px; color:#000" ><?php  echo  $product= $item['qty']; 
                                            ?>
                                          </td>
                                          <td style="border: 1px solid #ddd; padding: 8px; color:#000"  >
                                            <?php echo  $product = $item['line_total'];?> 
                                          </td>
                                      </tr>
                                       <?php } ?>
                                  </table>
                              </div>
                              <div class="left-table"  style="width:100%; float:right;margin:0px;padding: 0px;"> 
                                <table  style="float:right;">
                                     <tr>
                                      <td style="padding:10px; border-bottom: 1px solid #ccc;">subtotal:<?php echo $order->get_subtotal_to_display(); ?></td>

                                     </tr>
                                     <tr>
                                       <td style="padding:10px;  border-bottom: 1px solid #000;">Discount:<?php echo $order->get_discount_to_display(); ?> </td>
                                     </tr>
                                    
                                        <tr>
                                       <td style="padding:10px;  border-bottom: 1px solid #000;">Total:<?php echo $order->get_total(); ?> </td>
                                     </tr>
                                </table> 
                              </div>
                              <footer style=" position: fixed; bottom: -60px; left: 0px; right: 0px; text-align: center; border-top:1px solid #ddd; height: 50px;"><?php echo get_option( 'ocpsw_footer_text' );?></footer>
                            </body> 
                        </html>
                      </div>
                    <?php
                }
                else{}
                $html=ob_get_clean();
                
                $dompdf = new Dompdf();
               
                $dompdf->loadHtml($html);
          
                $dompdf->setPaper('A4', 'portrait');
                
                $dompdf->render();
             
                $output = $dompdf->output();

                  file_put_contents( OCPSW_BASE_PLUGIN_DIR . 'pdf/'. $order_id.'.pdf', $output);           
                  $file =OCPSW_BASE_PLUGIN_DIR . 'pdf/'. $order_id.'.pdf';
                  $filename = basename($file);
                 $upload_file = wp_upload_bits($filename, null,  file_get_contents(OCPSW_BASE_PLUGIN_DIR . 'pdf/'. $order_id.'.pdf') 
                 );
                if (!$upload_file['error']) {
                  $wp_filetype = wp_check_filetype($filename, null );
                  $attachment = array(
                    'post_mime_type' => $wp_filetype['type'],
                    'post_title' => preg_replace('/\.[^.]+$/', '', $filename),
                    'post_content' => '',
                    'post_status' => 'inherit'
                  );
                  $attachment_id = wp_insert_attachment( $attachment, $upload_file['file'], $parent_post_id );
                  if (!is_wp_error($attachment_id)) {
                    require_once(ABSPATH . "wp-admin" . '/includes/image.php');
                    $attachment_data = wp_generate_attachment_metadata( $attachment_id, $upload_file['file'] );
                    wp_update_attachment_metadata( $attachment_id,  $attachment_data );

                  }
                   unlink($file);
                }
               $your_pdf_path =  wp_get_attachment_url( $attachment_id );

                $attachments[] = $your_pdf_path;
                }
              return $attachments;
            }
            
             function OCPSW_attach_terms_conditions_pdf_to_email_invoice ( $attachments, $status , $order ) {


             
                $allowed_statuses =array('customer_completed_order');
               if( isset( $status ) && in_array ( $status, $allowed_statuses ) ) {
             
                $order_id=$order->get_id();
                include_once(plugin_dir_path( __FILE__ ).'dompdf/autoload.inc.php');
                ob_start();
                if(get_option( 'ocwqv_template_pos' ) == "temlate1"){
                    ?>
                      <div class="template1" >
                        <html>
                            <head>
                                <title>Packing Invoice</title>
                            </head>
                            <body>
                              <table style=" border-collapse: collapse; width: 100%;">
                                <tr>
                                  <td>
                                      <?php $attachment_id=get_option( 'ocpsw_shop_imagelogo');
                                     echo wp_get_attachment_image( $attachment_id, array(225,125),$icon =false ); ?>
                                  </td>
                                   <td style="width: 40%;">
                                     <h2>shop:<?php echo get_option( 'ocpsw_shop_title' );?></h2>
                                      <p><?php echo get_option( 'ocpsw_shop_addr' );?></p>
                                  </td>  
                                  </td> 
                                </tr>
                                <tr>  
                                  <td style="width: 60%;" >
                                          <h2>Invoice</h2>
                                  </td>
                                </tr>
                              </table>
                              <table style=" border-collapse:collapse; width: 100%;">
                                <tr> 
                                  <td style="width: 60%;">
                                     <p> <?php echo $order->get_formatted_billing_address();  ?></p>
                                  </td>    
                                   <td style="width: 40%;">
                                      <p> Order Number:<?php  echo $order->get_order_number();  ?></p>
                                      <p> Order Date:<?php echo $order->get_date_created()->format ('M-d-Y'); ?></p>
                                      <p> Payment method:<?php  echo   $order->get_payment_method_title();  ?></p>
                                  </td> 
                                </tr>
                              </table>
                              <div class="table-add" style=" width:100%;margin: 0px;padding: 0px;">
                                  <table style=" border-collapse: collapse; width: 100%;">
                                      <th style="border: 1px solid #ddd; padding: 8px;  padding-top: 12px; padding-bottom: 12px;text-align: left;background-color: <?php echo get_option( 'ocpsw_bg_color' )?>;color: white; width:50%;" >Product</th>
                                      <th style="border: 1px solid #ddd; padding: 8px;  padding-top: 12px; padding-bottom: 12px;text-align: left;background-color: <?php echo get_option( 'ocpsw_bg_color' )?>;color: white;  width:25%; " >Quantity</th>
                                     <th style="border: 1px solid #ddd; padding: 8px;  padding-top: 12px; padding-bottom: 12px;text-align: left;background-color:<?php echo get_option( 'ocpsw_bg_color' )?>;color: white; wi4dth:25%; " >Price</th>
                                      <?php foreach($order->get_items() as $item) {?>
                                      <tr> 
                                          <td style="border: 1px solid #ddd; padding: 8px; color:#000"  >
                                            <?php echo  $product = $item['name'];?></td>
                                          <td style="border: 1px solid #ddd; padding: 8px; color:#000" ><?php  echo  $product= $item['qty']; 
                                            ?>
                                          </td>
                                          <td style="border: 1px solid #ddd; padding: 8px; color:#000"  >
                                            <?php echo  $product = $item['line_total'];?> 
                                          </td>
                                      </tr>
                                       <?php } ?>
                                  </table>
                              </div>
                              <div class="left-table"  style="width:100%; float:right;margin: 0px;padding:0px;">    
                                <table  style="float:right;">
                                     <tr>
                                      <td style="padding:10px; border-bottom: 1px solid #ccc;">subtotal:<?php echo $order->get_subtotal_to_display(); ?></td>

                                     </tr>
                                     <tr>
                                       <td style="padding:10px;  border-bottom: 1px solid #000;">Discount:<?php echo $order->get_discount_to_display(); ?> </td>
                                     </tr>
                                    
                                        <tr>
                                       <td style="padding:10px;  border-bottom: 1px solid #000;">Total:<?php echo $order->get_total(); ?> </td>
                                     </tr>
                                </table> 
                              </div>
                              <footer style=" position: fixed; bottom:-60px; left: 0px; right: 0px; text-align: center; border-top:1px solid #ddd; height: 50px;"><?php echo get_option( 'ocpsw_footer_text' );?></footer>
                            </body> 
                        </html>
                      </div>
                    <?php }else if (get_option( 'ocwqv_template_pos' ) == "temlate2"){
                    ?>
                      <div class="template2">
                        <html>
                            <head>
                                <title>Packing invoice</title>
                            </head>
                            <body>
                              <table style=" border-collapse: collapse; width: 100%;">
                                <tr>     
                                  <td style="width: 40%;">
                                     <h2>shop:<?php echo get_option( 'ocpsw_shop_title' );?></h2>
                                      <p><?php echo get_option( 'ocpsw_shop_addr' );?></p>
                                  </td> 
                                   <td>
                                      <?php $attachment_id=get_option( 'ocpsw_shop_imagelogo');
                                      
                                     echo wp_get_attachment_image( $attachment_id, array(225,125),$icon =false ); ?>
                                  </td>  
                                </tr>
                                <tr>  
                                  <td style="width: 60%;" >
                                          <h2>Invoice</h2>
                                  </td>
                                </tr>
                              </table>
                              <table style=" border-collapse:collapse; width: 100%;">
                                <th>Billing address</th>
                                <th>Shipping address</th>
                                <tr> 
                                  <td style="width: 30%;">
                                     <p> <?php echo $order->get_formatted_billing_address();  ?></p>
                                  </td> 
                                  <td  style="width: 30%;">
                                    <p> <?php echo $order->get_formatted_shipping_address();  ?></p>
                                  </td>   
                                   <td style="width: 40%;">
                                      <p> Order Number:<?php  echo $order->get_order_number();  ?></p>
                                      <p> Order Date:<?php echo $order->get_date_created()->format ('M-d-Y'); ?></p>
                                      <p> Payment method:<?php  echo   $order->get_payment_method_title();  ?></p>
                                  </td> 
                                </tr>
                              </table>
                              <div class="table-add" style=" width:100%;margin: 0px;padding: 0px;">
                                  <table style=" border-collapse: collapse; width: 100%;">
                                    <th style="border: 1px solid #ddd; padding: 8px;  padding-top: 12px; padding-bottom: 12px;text-align: left;background-color: <?php echo get_option( 'ocpsw_bg_color' )?>;color: white; width:50%;" >Image</th>
                                      <th style="border: 1px solid #ddd; padding: 8px;  padding-top: 12px; padding-bottom: 12px;text-align: left;background-color: <?php echo get_option( 'ocpsw_bg_color' )?>;color: white; width:50%;" >Product</th>
                                      <th style="border: 1px solid #ddd; padding: 8px;  padding-top: 12px; padding-bottom: 12px;text-align: left;background-color: <?php echo get_option( 'ocpsw_bg_color' )?>;color: white;  width:25%; " >Quantity</th>
                                      <th style="border: 1px solid #ddd; padding: 8px;  padding-top: 12px; padding-bottom: 12px;text-align: left;background-color: <?php echo get_option( 'ocpsw_bg_color' )?>;color: white; wi4dth:25%; " >Price</th>
                                      <?php foreach($order->get_items() as $key => $item) {?>
                                      <tr> 
                                        <td style="border: 1px solid #ddd; padding: 8px; color:#000 width:10%"> 
                                         <?php  $product_id = $item['product_id'];
                                          $product = wc_get_product( $product_id );
                                       $image = wp_get_attachment_image_src( get_post_thumbnail_id($product_id),  array(100,100));?>
                                        <img    src="<?php  echo $image[0]; ?>" data-id="<?php echo ($product_id); ?>">
                                          </td>
                                        </td>
                                          <td style="border: 1px solid #ddd; padding: 8px; color:#000"  >
                                            <?php echo  $product = $item['name'];?></td>
                                          <td style="border: 1px solid #ddd; padding: 8px; color:#000" ><?php  echo  $product= $item['qty']; 
                                            ?>
                                          </td>
                                          <td style="border: 1px solid #ddd; padding: 8px; color:#000"  >
                                            <?php echo  $product = $item['line_total'];?> 
                                          </td>
                                      </tr>
                                       <?php } ?>
                                  </table>
                              </div>
                              <div class="left-table"  style="width:100%; float:right;margin:0px;padding: 0px;"> 
                                <table  style="float:right;">
                                     <tr>
                                      <td style="padding:10px; border-bottom: 1px solid #ccc;">subtotal:<?php echo $order->get_subtotal_to_display(); ?></td>

                                     </tr>
                                     <tr>
                                       <td style="padding:10px;  border-bottom: 1px solid #000;">Discount:<?php echo $order->get_discount_to_display(); ?> </td>
                                     </tr>
                                    
                                        <tr>
                                       <td style="padding:10px;  border-bottom: 1px solid #000;">Total:<?php echo $order->get_total(); ?> </td>
                                     </tr>
                                </table> 
                              </div>
                              <footer style=" position: fixed; bottom: -60px; left: 0px; right: 0px; text-align: center; border-top:1px solid #ddd; height: 50px;"><?php echo get_option( 'ocpsw_footer_text' );?></footer>
                            </body> 
                        </html>
                      </div>
                    <?php
                }
                else{}
                $html=ob_get_clean();
                
                $dompdf = new Dompdf();
               
                $dompdf->loadHtml($html);
          
                $dompdf->setPaper('A4', 'portrait');
                
                $dompdf->render();
             
                $output = $dompdf->output();
                  file_put_contents( OCPSW_BASE_PLUGIN_DIR . 'pdf/'. $order_id.'.pdf', $output);           
                  $file =OCPSW_BASE_PLUGIN_DIR . 'pdf/'. $order_id.'.pdf';
                  $filename = basename($file);
                 $upload_file = wp_upload_bits($filename, null,  file_get_contents(OCPSW_BASE_PLUGIN_DIR . 'pdf/'. $order_id.'.pdf') 
                 );
                if (!$upload_file['error']) {
                  $wp_filetype = wp_check_filetype($filename, null );
                  $attachment = array(
                    'post_mime_type' => $wp_filetype['type'],
                    'post_title' => preg_replace('/\.[^.]+$/', '', $filename),
                    'post_content' => '',
                    'post_status' => 'inherit'
                  );
                  $attachment_id = wp_insert_attachment( $attachment, $upload_file['file'], $parent_post_id );
                  if (!is_wp_error($attachment_id)) {
                    require_once(ABSPATH . "wp-admin" . '/includes/image.php');
                    $attachment_data = wp_generate_attachment_metadata( $attachment_id, $upload_file['file'] );
                    wp_update_attachment_metadata( $attachment_id,  $attachment_data );
                  }
                unlink($file);
                }
               $your_pdf_path =  wp_get_attachment_url( $attachment_id );


                $attachments[] = $your_pdf_path;
                }
              return $attachments;
            }

            function OCPSW_save_options(){
                if( current_user_can('administrator') ) { 
                     if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'ocpsw_save_option'){
                        if(!isset( $_POST['ocpsw_nonce_field'] ) || !wp_verify_nonce( $_POST['ocpsw_nonce_field'], 'ocpsw_nonce_action' ) ){
                            print 'Sorry, your nonce did not verify.';
                            exit;
                        }else{
                            update_option('ocpsw_shop_addr',sanitize_text_field( $_REQUEST['ocpsw_shop_addr']),'yes');
                            update_option('ocpsw_shop_title',sanitize_text_field( $_REQUEST['ocpsw_shop_title']),'yes');
                            update_option('ocpsw_shop_imagelogo',sanitize_text_field( $_REQUEST['ocpsw_shop_imagelogo']),'yes');
                            update_option('ocpsw_footer_text',sanitize_text_field( $_REQUEST['ocpsw_footer_text']),'yes');
                            update_option('ocwqv_template_pos', sanitize_text_field( $_REQUEST['ocwqv_template_pos'] ),'yes');
                           update_option('ocpsw_bg_color', sanitize_text_field( $_REQUEST['ocpsw_bg_color'] ),'yes'); 
                        }
                    }
                }
            }
             
            function init() {
                add_action( 'admin_menu',  array($this, 'OCPSW_submenu_page'));
                add_action( 'admin_head',array( $this, 'OCPSW_add_custom_order_status_actions_button_css' ));
                add_action('init',array( $this, 'OCPSW_me_post_pdf'));
                add_action('init',array( $this, 'OCPSW_me_post_pdf_invoice'));
                add_filter( 'bulk_actions-edit-shop_order', array( $this,'OCPSW_downloads_bulk_actions_edit_product'), 20, 1 );
                add_filter( 'handle_bulk_actions-edit-shop_order',array( $this, 'OCPSW_downloads_handle_bulk_action_edit_shop_order'), 10, 3 );
                add_filter( 'handle_bulk_actions-edit-shop_order',array( $this, 'OCPSW_downloads_handle_bulk_action_edit_shop_order_invoice'), 10, 4 );
                add_filter( 'woocommerce_admin_order_actions', array( $this,'OCPSW_add_custom_order_status_actions_button'), 100, 2 );
                add_action( 'init',  array($this, 'OCPSW_save_options'));  
                add_filter( 'woocommerce_email_attachments', array( $this,'OCPSW_attach_terms_conditions_pdf_to_email'), 10, 3);  
                 add_filter( 'woocommerce_email_attachments', array( $this,'OCPSW_attach_terms_conditions_pdf_to_email_invoice'), 10, 3);  
      
            }   
        public static function instance() {
            if (!isset(self::$instance)) {
                self::$instance = new self();
                self::$instance->init();
            }
         return self::$instance;
        }
    }
 OCPSW_backend::instance();
}

