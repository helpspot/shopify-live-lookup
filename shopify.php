<?php
header('Content-type: text/xml');
echo '<?xml version="1.0" encoding="UTF-8"' . "?" . ">\n";
$ll_key = "enter-a-random-string-here";
//your store url without a trailing "/"
$store_url = "";
//The api key and password are created when setting up the private app in the shopify settings
$api_key = "";
$api_pwd = "";

if (!empty($_GET['ll_key']) && $_GET['ll_key'] == $ll_key) {
    $curl = curl_init();

    //To expand on the search syntax use the shopify documentation here https://help.shopify.com/en/api/getting-started/search-syntax
    $searchURL = $store_url . "/admin/api/2019-04/customers/search.json?query=";
    //If we have the customer id use that
    if (!empty($_GET['customer_id'])) {
        $searchURL .= "id:" . $_GET['customer_id'];
    }
    //First try an email based search
    elseif (!empty($_GET['email'])) {
        $searchURL .= "email:" . $_GET['email'];
    }
    //If no email is availible try lookup via first and last name
    elseif (!empty($_GET['first_name']) && !empty($_GET['last_name'])) {
        $searchURL .= urlencode("first_name:" . $_GET['first_name'] . " AND last_name:" . $_GET['last_name']);
    } else {
        die;
    }

    curl_setopt_array($curl, array(
        CURLOPT_URL => $searchURL,
        CURLOPT_USERPWD => $api_key . ":" . $api_pwd,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_POSTFIELDS => "",
        CURLOPT_HTTPHEADER => array(
            "Accept: */*",
            "Cache-Control: no-cache",
            "Connection: keep-alive",
            "Host: userscapedev.myshopify.com",
            "accept-encoding: gzip, deflate",
            "cache-control: no-cache",
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        $response = json_decode($response);
        
        echo '<livelookup version="1.0" columns="customer_id,first_name,last_name,email,phone">';
        foreach ($response->customers as $customer) {
            echo '<customer>
                    <customer_id>' . $customer->id . '</customer_id>
                    <first_name>' . $customer->first_name . '</first_name>
                    <last_name>' . $customer->last_name . '</last_name>
                    <email>' . $customer->email . '</email>
                    <phone>' . $customer->phone . '</phone>	
                    <Order_Count>' . $customer->orders_count . '</Order_Count>
                    <Total_Spent>' . $customer->total_spent . '</Total_Spent>
                    ', (!empty($customer->last_order_id) ?
                '<Last_Order_ID><![CDATA[
                        <a href="' . $store_url . '/admin/orders/' . $customer->last_order_id . '" target="_blank">' . $customer->last_order_id . '</a>
                        ]]></Last_Order_ID>' : ''),
                '<Link><![CDATA[
                        <a href="' . $store_url . '/admin/customers/' . $customer->id . '" target="_blank">View In Shopify</a>
                        ]]></Link>				
                </customer>';
        }
        echo '</livelookup>';
    }
}
