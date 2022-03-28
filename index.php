<?php 
include 'ntml/Client.php';


if (isset($_POST['saveOrder'])) { 

    $pageURL = $baseURL.rawurlencode($cur).'/Page/SalesOrder'; 
    // Initialize Page Soap Client 

    $service = new NTLMSoapClient($pageURL);
    $create = new stdClass();
    $sq = new stdClass();        
    $sq->Sell_to_Customer_Name="Customer Name";
    $create->SalesOrder = $sq;
    $result = $service->create($create);
    $key = $result->SalesOrder->Key;
    $update = new stdClass();
    $sq->Key = $key;


    $basket=$db->prepare("SELECT * FROM basket where customerID=:id");
    $basket->execute(array(
        'id' => "customer id"
    ));

    $counter=0;
    $salesLineList = new stdClass();

    while($basketResult=$basket->fetch(PDO::FETCH_ASSOC)) {

        $qty=$basketResult['qty'];
        $itemNo=$basketResult['itemNo'];
        $variantCode=$basketResult['variantCode'];

        $salesLine = new stdClass();

        $salesLine->No = $itemNo;
        $salesLine->Type = 'Item';
        $salesLine->Variant_Code =$variantCode;
        $salesLine->Quantity = $qty;
        $salesLineList->Sales_Order_Line[$counter] = $salesLine;
        $sq->SalesLines = $salesLineList;

        $counter++;

        }
    

    $update->SalesOrder = $sq;
    $result = $service->Update($update);
}

?>
