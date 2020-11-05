
<?php

if (!defined('ABSPATH'))
  exit;
use Dompdf\Dompdf as Dompdf;


if (!class_exists('OCPSW_front')) {

    class OCPSW_front {

      protected static $instance;
               function ocpsw_add_my_account_order_actions( $actions, $order ) {
                $action_dow='opsw_invoice';
                  $actions[$action_dow] = array(
                      // adjust URL as needed
                      'url'  => wp_nonce_url('?action=woo_pdf_download&order_id='  . $order->get_id()),
                      'name' => __( 'Invoice', 'my-textdomain' ),
                  );

                  return $actions;
              }

               function OCPSW_add_custom_order_status_actions_button_css_dowload() {
                 $action_dow = 'opsw_invoice'; 
                 $icon= OCPSW_PLUGIN_DIR .'/images/document.png';
                echo '<style>.'.$action_dow.'::after { background: url('. $icon.'); content: "" !important; background-repeat:no-repeat;background-position: center center; position: absolute;width: 27px; height: 21px;}</style>';
               
            }


              function OCPSW_me_post_pdf_invoice_download(){
               
              if(isset($_REQUEST['order_id'])){
                $order = wc_get_order($_REQUEST['order_id']);
              }
           
               if(isset($_REQUEST['action']) && $_REQUEST['action'] == "woo_pdf_download"){

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
              
           
            function init() {
                add_filter( 'woocommerce_my_account_my_orders_actions', array($this ,'ocpsw_add_my_account_order_actions')
                  , 10, 2 );
                 add_action('init',array( $this, 'OCPSW_me_post_pdf_invoice_download'));  
                 add_action( 'wp_head',array( $this, 'OCPSW_add_custom_order_status_actions_button_css_dowload' ));    
            }  


        public static function instance() {
            if (!isset(self::$instance)) {
                self::$instance = new self();
                self::$instance->init();
            }
         return self::$instance;
        }
    }
 OCPSW_front::instance();
}

