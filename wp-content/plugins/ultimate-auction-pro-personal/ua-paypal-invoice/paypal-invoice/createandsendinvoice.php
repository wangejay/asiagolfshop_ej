<?php
  
  require_once('PayPalInvoiceAPI.php');
  require_once('credentials.php'); //NOTE: edit this file with your info!

  $ppAPI = new PayPalInvoiceAPI($api_username, $api_password, $api_signature, $app_id);
  
  //required request fields
  $req['currencyCode'] = $auction_data['auc_currency'];
  $req['merchantEmail'] = $sandbox_seller_email;
  $req['payerEmail'] = $auction_data['auc_payer'];
  $req['paymentTerms'] = "DueOnReceipt"; //DueOnReceipt, DueOnDateSpecified, Net10, Net15, Net30, or Net45
  //include shipping amount in invoice
  $req['shippingAmount'] = "";
  $req['shippingAmount'] = apply_filters('ua_shipping_data_invoice', $req['shippingAmount'], $auction_data['auc_id'], $auction_data['auc_payer']); 
  //required item fields
  $items[0]['name'] = substr($auction_data['auc_name'], 0, 59); //have limited to max 60 characters as there is limit by PayPal for this field
  $items[0]['quantity'] = "1";
  $items[0]['unitPrice'] = $auction_data['auc_bid'];
  
  //some optional fields
  //$items[0]['description'] = $auction_data['auc_desc']; //have disabled this as PayPal has limit of 1000 characters for this field and also broken html related issue could arise
  $current_time = time();
  $items[0]['date'] = date(DATE_ATOM, $current_time); //date product or service was provided
  //$items[0]['taxName'] = "";
  //$items[0]['taxRate'] = "";
  
  // For complete field listing, read the example PayPalInvoiceAPI.php and
  // https://cms.paypal.com/cms_content/US/en_US/files/developer/PP_InvoicingAPIGuide.pdf
  
  $res = $ppAPI->createAndSendInvoice($req, $items);

  if($res['responseEnvelope.ack']== "Success") {
    $invoiceID = $res['invoiceID'];
    $invoiceNumber = $res['invoiceNumber'];
    $invoice_data = $ppAPI->getInvoiceDetails($invoiceID);
    $inv_stat = isset($invoice_data['invoiceDetails.status']) ? $invoice_data['invoiceDetails.status'] : '';
    $inv_url = isset($res['invoiceURL']) ? $res['invoiceURL'] : '';
  }
  else{
    $inv_stat = 'failed_to_send';
    $invoiceID = '';
  }
?>