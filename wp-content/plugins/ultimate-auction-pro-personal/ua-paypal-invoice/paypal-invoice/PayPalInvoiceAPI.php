<?php

class PayPalInvoiceAPI {
  private $base_url, $headers, $end_url;

  function __construct($api_username, $api_password, $api_signature, $app_id) {
    //Note: To use Invoice API on the live PayPal site, you must request your own APP ID from PayPal
    if ($app_id == "sandbox") {
      $app_id = "APP-80W284485P519543T"; //Use hardcoded sandbox APP ID
      $this->base_url = "https://svcs.sandbox.paypal.com/Invoice/";
    } else {
      $this->base_url = "https://svcs.paypal.com/Invoice/";
    }

    $this->headers = array( "Content-Type: text/namevalue",
                            "X-PAYPAL-SECURITY-USERID: $api_username",
                            "X-PAYPAL-SECURITY-PASSWORD: $api_password",
                            "X-PAYPAL-SECURITY-SIGNATURE: $api_signature",
                            "X-PAYPAL-APPLICATION-ID: $app_id",
                            "X-PAYPAL-REQUEST-DATA-FORMAT: NV",  //NV=Name Value Pair
                            "X-PAYPAL-RESPONSE-DATA-FORMAT: NV");
    //echo "<pre>" . "headers: " . print_r($this->headers,true) . " </pre>";
  }

  function curlRequest($suburl, $str_req) {
    //echo "<pre>" . "curlRequest: $str_req" . "</pre>";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $this->base_url . $suburl);
    curl_setopt($ch, CURLOPT_VERBOSE, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    // Enable security.
    // If you have trouble, set these to FALSE for sandbox testing purposes.
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, TRUE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__).'/api_cert_chain.crt');

    // Append the data
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $str_req);

    $httpResponse = curl_exec($ch);
    if(!$httpResponse) {
      $response = "{$this->end_url} failed: ".curl_error($ch)."(".curl_errno($ch).")";
      return $response;
    }

    foreach (explode("&", $httpResponse) as $nvp) {
      $nvp = explode("=", $nvp);
      if(sizeof($nvp) > 1)
        $response[$nvp[0]] = urldecode($nvp[1]);
    }
    return $response;
  }

  function prepareInvoiceData($req_data, $items) {
    //defaults that may be overridden by req_data
    $req = array( 'requestEnvelope.errorLanguage' => 'en_US',
                  'invoice.currencyCode' => 'USD', );

    $map = array( 'requestEnvelope.errorLanguage' => 'language',
                  'invoice.merchantEmail' => 'merchantEmail',
                  'invoice.payerEmail' => 'payerEmail',
                  'invoice.currencyCode' => 'currencyCode',
                  'invoice.number' => 'orderID',
                  'invoiceID' => 'invoiceID',
                  'invoice.paymentTerms' => 'paymentTerms',
                  'invoice.discountPercent' => 'discountPercent',
                  'invoice.discountAmount' => 'discountAmount',
                  'invoice.terms' => 'invoiceTerms',
                  'invoice.note' => 'invoiceNote',
                  'invoice.merchantMemo' => 'merchantMemo',
                  'invoice.shippingAmount' => 'shippingAmount',
                  'invoice.shippingTaxName' => 'shippingTaxName',
                  'invoice.shippingTaxRate' => 'shippingTaxRate',
                  'invoice.logoUrl' => 'logoURL',
                  'invoice.merchantInfo.firstName' => 'merchantFirstName',
                  'invoice.merchantInfo.lastName' => 'merchantLastName',
                  'invoice.merchantInfo.businessName' => 'merchantBusinessName',
                  'invoice.merchantInfo.phone' => 'merchantPhone',
                  'invoice.merchantInfo.fax' => 'merchantFax',
                  'invoice.merchantInfo.website' => 'merchantWebsite',
                  'invoice.merchantInfo.customValue' => 'merchantCustomValue',
                  'invoice.merchantInfo.address.line1' => 'merchantLine1',
                  'invoice.merchantInfo.address.line2' => 'merchantLine2',
                  'invoice.merchantInfo.address.city' => 'merchantCity',
                  'invoice.merchantInfo.address.state' => 'merchantState',
                  'invoice.merchantInfo.address.postalCode' => 'merchantPostalCode',
                  'invoice.merchantInfo.address.countryCode' => 'merchantCountryCode',
                  'invoice.billingInfo.firstName' => 'billingFirstName',
                  'invoice.billingInfo.lastName' => 'billingLastName',
                  'invoice.billingInfo.businessName' => 'billingBusinessName',
                  'invoice.billingInfo.phone' => 'billingPhone',
                  'invoice.billingInfo.fax' => 'billingFax',
                  'invoice.billingInfo.website' => 'billingWebsite',
                  'invoice.billingInfo.customValue' => 'billingCustomValue',
                  'invoice.billingInfo.address.line1' => 'billingLine1',
                  'invoice.billingInfo.address.line2' => 'billingLine2',
                  'invoice.billingInfo.address.city' => 'billingCity',
                  'invoice.billingInfo.address.state' => 'billingState',
                  'invoice.billingInfo.address.postalCode' => 'billingPostalCode',
                  'invoice.billingInfo.address.countryCode' => 'billingCountryCode',
                  'invoice.shippingInfo.firstName' => 'shippingFirstName',
                  'invoice.shippingInfo.lastName' => 'shippingLastName',
                  'invoice.shippingInfo.businessName' => 'shippingBusinessName',
                  'invoice.shippingInfo.phone' => 'shippingPhone',
                  'invoice.shippingInfo.fax' => 'shippingFax',
                  'invoice.shippingInfo.website' => 'shippingWebsite',
                  'invoice.shippingInfo.customValue' => 'shippingCustomValue',
                  'invoice.shippingInfo.address.line1' => 'shippingLine1',
                  'invoice.shippingInfo.address.line2' => 'shippingLine2',
                  'invoice.shippingInfo.address.city' => 'shippingCity',
                  'invoice.shippingInfo.address.state' => 'shippingState',
                  'invoice.shippingInfo.address.postalCode' => 'shippingPostalCode',
                  'invoice.shippingInfo.address.countryCode' => 'shippingCountryCode',
                  'invoice.invoiceDate' => 'invoiceDate',
                  'invoice.dueDate' => 'dueDate',
                );

    foreach ($map as $apikey => $ourkey)
      if(trim(@$req_data[$ourkey]) != '') {
        if ($ourkey == 'invoiceDate' || $ourkey == 'dueDate')
          //dates might come urlencoded, so decode
          $req[$apikey] = urldecode($req_data[$ourkey]);
        else
          $req[$apikey] = $req_data[$ourkey];
      }

    for($i=0;$i<count($items);$i++) {
      $imap = array(
        "invoice.itemList.item($i).name" => 'name',
        "invoice.itemList.item($i).description" => 'description',
        "invoice.itemList.item($i).quantity" => 'quantity',
        "invoice.itemList.item($i).unitPrice" => 'unitPrice',
        "invoice.itemList.item($i).taxName" => 'taxName',
        "invoice.itemList.item($i).taxRate" => 'taxRate',
      );
      foreach ($imap as $apikey => $ourkey) {
        if(trim(@$items[$i][$ourkey]) != '') {
          $req[$apikey] = $items[$i][$ourkey];
        }
      }
    }
    
    return http_build_query($req);
  }

  function createInvoice($req_data, $items) {
    $str_req = $this->prepareInvoiceData($req_data, $items);
    return $this->curlRequest('CreateInvoice', $str_req);
  }
  function createAndSendInvoice($req_data, $items) {
    $str_req = $this->prepareInvoiceData($req_data, $items);
    return $this->curlRequest('CreateAndSendInvoice', $str_req);
  }
  function updateInvoice($req_data, $items) {
    $str_req = $this->prepareInvoiceData($req_data, $items);
    return $this->curlRequest('UpdateInvoice', $str_req);
  }

  function prepareIDreq($invoiceID) {
    $req = array();
    $req['requestEnvelope.errorLanguage'] = "en_US";
    $req['invoiceID'] = $invoiceID;
    return http_build_query($req);
  }
  function sendInvoice($invoiceID) {
    $str_req = $this->prepareIDreq($invoiceID);
    return $this->curlRequest('SendInvoice', $str_req);
  }
  function getInvoiceDetails($invoiceID) {
    $str_req = $this->prepareIDreq($invoiceID);
    return $this->curlRequest('GetInvoiceDetails', $str_req);
  }

  function prepareCancelInvoice($req_data) {
    $req = array();
    $req['requestEnvelope.errorLanguage'] = "en_US";
    $req['invoiceID'] = $req_data['invoiceID'];
    $req['subject']   = $req_data['emailSubject'];
    $req['noteForPayer'] = $req_data['emailBody'];
    $req['sendCopyToMerchant'] = "true";
    return http_build_query($req);
  }
  function cancelInvoice($req_data) {
    $str_req = $this->prepareCancelInvoice($req_data);
    return $this->curlRequest('CancelInvoice', $str_req);
  }

  function prepareSearchInvoices($req_data) {
    $req = array( 'requestEnvelope.errorLanguage' => 'en_US' );

    $map = array( 'requestEnvelope.errorLanguage' => 'language',
                  'merchantEmail' => 'merchantEmail',
                  'page' => 'page',
                  'pageSize' => 'pageSize',
                  'parameters.email' => 'email',
                  'parameters.recipientName' => 'recipientName',
                  'parameters.businessName' => 'businessName',
                  'parameters.invoiceNumber' => 'invoiceNumber',
                  'parameters.status' => 'status',
                  'parameters.lowerAmount' => 'lowerAmount',
                  'parameters.upperAmount' => 'upperAmount',
                  'parameters.currencyCode' => 'currencyCode',
                  'parameters.memo' => 'memo',
                  'parameters.origin' => 'origin', );

    foreach ($map as $apikey => $ourkey)
      if(trim(@$req_data[$ourkey]) != '')
        $req[$apikey] = $req_data[$ourkey];
    
    $datemap = array( 'parameters.invoiceDate.startDate' => 'invoice_start_date',
                      'parameters.invoiceDate.endDate' => 'invoice_end_date',
                      'parameters.dueDate.startDate' => 'due_start_date',
                      'parameters.dueDate.endDate' => 'due_end_date',
                      'parameters.paymentDate.startDate' => 'payment_start_date',
                      'parameters.paymentDate.endDate' => 'payment_end_date',
                      'parameters.creationDate.startDate' => 'creation_start_date',
                      'parameters.creationDate.endDate' => 'creation_end_date', );

    foreach ($datemap as $apikey => $ourkey)
      if(trim(@$req_data[$ourkey]) != '')
        $req[$apikey] = urldecode($req_data[$ourkey]);

    return http_build_query($req);
  }
  function searchInvoices($req_data) {
    $str_req = $this->prepareSearchInvoices($req_data);
    return $this->curlRequest('SearchInvoices', $str_req);
  }

  function prepareMarkPaid($req_data) {
    $req = array();
    $req['requestEnvelope.errorLanguage'] = "en_US";
    $req['invoiceID'] = $req_data['invoiceID'];
    $req['payment.date']   = date(DATE_ATOM, strtotime($req_data['paymentDate']));
    $req['payment.note'] = $req_data['paymentNote'];
    $req['payment.method'] = $req_data['method'];
    return http_build_query($req);
  }
  function markInvoiceAsPaid($req_data) {
    $str_req = $this->prepareMarkPaid($req_data);
    return $this->curlRequest('MarkInvoiceAsPaid', $str_req);
  }

}//class PayPalInvoiceAPI

?>
