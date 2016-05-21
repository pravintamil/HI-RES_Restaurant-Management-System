<?php
//============================================================+
// File name   : example_006.php
// Begin       : 2008-03-04
// Last Update : 2013-05-14
//
// Description : Example 006 for TCPDF class
//               WriteHTML and RTL support
//
// Author: Nicola Asuni
//
// (c) Copyright:
//               Nicola Asuni
//               Tecnick.com LTD
//               www.tecnick.com
//               info@tecnick.com
//============================================================+

/**
 * Creates an example PDF TEST document using TCPDF
 * @package com.tecnick.tcpdf
 * @abstract TCPDF - Example: WriteHTML and RTL support
 * @author Nicola Asuni
 * @since 2008-03-04
 */

// Include the main TCPDF library (search for installation path).
require_once('tcpdf/examples/tcpdf_include.php');

// // create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('TCPDF Example 006');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');
$pdf->SetPrintHeader(false);
$pdf->SetPrintFooter(false);
$pdf->SetLeftMargin(2);
$pdf->SetRightMargin(2);
$pdf->SetTopMargin(4);
// set default header data

$pdf->SetAutoPageBreak(TRUE, -1);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'tcpdf/examples/lang/eng.php')) {
  require_once(dirname(__FILE__).'tcpdf/examples/lang/eng.php');
  $pdf->setLanguageArray($l);
}

//         $db_key="pravin";
//         $sale_order_id = 92;

// $branch_id=1;
//         $tables = array('address','category','customer','driver','product','sale_order','sale_order_line','table_category','table_details');
//     foreach ($tables as $key => $value) {
//         if($value!="address")
//         $tables[$key]=$value."_1";
//     }
include("../db.php");
// $con=mysql_connect("127.0.0.1","root","root");
// mysql_select_db("pravin_hotapp");
$q="SELECT * FROM `restaurant` WHERE `db_key`='$db_key' ";
$q=mysql_query($q);
$r=mysql_fetch_assoc($q);
mysql_select_db("$db_key");
$address=$r['address'].", ".$r['city'].", ".$r['state'].", ".$r['pincode'];
$q1="SELECT * FROM $tables[5] WHERE `sale_order_id`='$sale_order_id'";
$sale_order= mysql_fetch_assoc(mysql_query("$q1"));
$q2="SELECT DISTINCT `product_name` FROM `$tables[6]` WHERE `order_id`='$sale_order_id'";
$q2=mysql_query($q2);
while ($r2=mysql_fetch_array($q2)) {
  $product_name=$r2['product_name'];
  $product['name']=$product_name;
  $q3=mysql_query("SELECT * FROM `$tables[6]` WHERE `order_id`='$sale_order_id' AND `product_name`='$product_name'");
  while ($r3=mysql_fetch_assoc($q3)) {
    $product['qty']+=$r3['qty'];
    $product['price']=($r3['price']/$r3['qty'])+($r3['price']%$r3['qty']);
    $product['price']=number_format($product['price'], 2, '.', '');
    $product['price_total']+=number_format($r3['price'], 2, '.', '');
  }
  $sale_order_line[]=$product;
  $product= array();
}

// add a page
$pdf->AddPage("P","A7");

// writeHTML($html, $ln=true, $fill=false, $reseth=false, $cell=false, $align='')
// writeHTMLCell($w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=0, $reseth=true, $align='', $autopadding=true)

// create some HTML content
$html = ' <style type="text/css">
    pre {
      white-space: pre-wrap;       /* css-3 */
      white-space: -moz-pre-wrap;  /* Mozilla, since 1999 */
      white-space: -pre-wrap;      /* Opera 4-6 */
      white-space: -o-pre-wrap;    /* Opera 7 */
      word-wrap: break-word;       /* Internet Explorer 5.5+ */
      margin-bottom: 0px;
    }
    h3{
      text-align: center;
      font-size: 10px;
    }
    body{
      margin: 0px;
      font-size: 13px;
      width: 350px
    }
    hr{
      margin-bottom: 3px;
      margin-top: 0px;
      border-color: #000;
    }
    p{
      margin-bottom: 5px;
      font-size:7
    }
  </style>
    <h3>'.$r['restaurant_name'].'</h3>
    <p style="text-align: center;margin-bottom: 15px;">'.$address.'
    </p>'
      
    ;


// Table with rowspans and THEAD
$product = '
<table border="0" cellpadding="1" cellspacing="0" style="font-size:8px;border-bottom: 0.1 solid black;">
<tr nobr="true">
  <th style="font-size:7px;border-bottom: 0.1 solid black;">
  <td width="7" align="center"></td>
  <td width="112" align="center">Items</td>
  <td width="15" align="center">Qty</td>
  <td width="30" align="center">Price</td>
  <td width="30" align="center">Total</td>
  <td width="3" align="center"></td>
  </th>
</tr>';
$i=0;
foreach ($sale_order_line as $product_line) {
  $i++;
  $product.='<tr nobr="true">
  <td width="7" align="center"></td>
  <td width="112" align="left">'.$product_line['name'].'</td>
  <td width="15" align="center">'.$product_line['qty'].'</td>
  <td width="30" align="right">'.$product_line['price'].'</td>
  <td width="30" align="right">'.number_format($product_line['price_total'], 2, '.', '').'</td>
  <td width="3" align="center"> </td>
 </tr>';
 $total+=$product_line['price_total'];
}
$discount=$total*0.05;
$vat=($total-$discount)*0.12;
$g_total=($total-$discount)+$vat;
$product.='
</table>
';
$order='<table border="0" cellpadding="1" cellspacing="0" style="font-size:8px">
 <tr>
  <td width="5" align="center"></td>
  <td width="110" align="left"> Order id : '.$sale_order_id.'</td>
  <td width="110" align="left"> Order type : '.$sale_order['order_type'].' </td>
 </tr>
 <tr>
  <td width="5" align="center"></td>
  <td width="110" align="left"> Table name : '.$sale_order['table_id'].' </td>
  <td width="110" align="left"> Date : '.date("d M Y", strtotime($sale_order['order_date'])).'</td>
 </tr>
 <tr>
  <td width="5" align="center"></td>
  <td width="110" align="left"> Server name : '.$sale_order['server_id'].' </td>
  <td width="110" align="left"> Time : '.date("h:i:s a", strtotime($sale_order['order_date'])).' </td>
 <tr>
 </table>';

$q3="SELECT * FROM `$tables[2]` WHERE `customer_id` ='".$sale_order['customer_id']."';";
$customer=mysql_fetch_assoc(mysql_query($q3));
$order_col='<table border="0" cellpadding="1" cellspacing="0" style="font-size:8px">
 <tr>
  <td width="5" align="center"></td>
  <td width="110" align="left"> Order id : '.$sale_order_id.'</td>
  <td width="110" align="left"> Order type : '.$sale_order['order_type'].' </td>
 </tr>
 <tr>
  <td width="5" align="center"></td>
  <td width="110" align="left"> Customer name : '.$customer['name'].' </td>
  <td width="110" align="left"> Date : '.date("d M Y", strtotime($sale_order['order_date'])).'</td>
 </tr>
 <tr>
  <td width="5" align="center"></td>
  <td width="110" align="left"> Phone : '.$customer['phone'].' </td>
  <td width="110" align="left"> Time : '.date("h:i:s a", strtotime($sale_order['order_date'])).' </td>
 </tr>
 <tr>
  <td width="5" align="center"></td>
  <td width="110" align="left"> Address : '.$customer['address'].', '.$customer['pincode'].' </td>
 </tr>
 </table>';

$total='<table border="0" cellpadding="1" cellspacing="0" style="font-size:8px ">
 <tr>
  <td width="97" align="center" ></td>
  <td width="50" align="left"> Total </td>
  <td width="7" > : </td>
  <td width="40" align="right"> '.number_format($total, 2, '.', '').' </td>
 </tr>
 <tr>
  <td width="97" align="center"></td>
  <td width="50" align="left"> Discount (5%)</td>
  <td width="7" > : </td>
  <td width="40" align="right"> -'.number_format($discount, 2, '.', '').' </td>
 </tr>
 <tr>
  <td width="97" align="center"></td>
  <td width="50" align="left"> Vat (12%)</td>
  <td width="7" > : </td>
  <td width="40" align="right"> '.number_format($vat, 2, '.', '').'</td>
 </tr>
 <tr>
  <td width="97" align="center"></td>
  <td width="50" align="left" style="border-top: 0.1 solid black;border-bottom: 0.1 solid black;"> Grant total </td>
  <td width="7" style="border-top: 0.1 solid black;border-bottom: 0.1 solid black;"> : </td>
  <td width="40" align="right"style="border-top: 0.1 solid black;border-bottom: 0.1 solid black;"> '.number_format($g_total, 2, '.', '').' </td>
 </tr>
 </table>';
// $right_column='<p style="font-size:6">Order type : din_in</p>
//      <p style="font-size:6">date : 16 Feb 2016</p>
//      <p style="font-size:6">time : 04:40:00pm</p>
//      <p style="font-size:6"></p>';

// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');
$y = $pdf->getY();
// $pdf->writeHTMLCell(35, 20, 5, '', $left_column, 0, 0, 0, true, 'L', false);
// write the second column
// $pdf->writeHTMLCell(35, 20, 40, '', $right_column, 0, 1, 0, true, 'L', true);
if($sale_order['order_type']=="delivery"){
  $pdf->writeHTML($order_col, true, false, false, false, '');
}
else{
  $pdf->writeHTML($order, true, false, false, false, '');
}
$pdf->writeHTML($product, true, false, false, false, '');
$pdf->writeHTML($total, true, false, false, false, '');
// - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
// Print all HTML colors
// ---------------------------------------------------------
//Close and output PDF document
mkdir($_SERVER['DOCUMENT_ROOT'] ."testing/hotapp/bill/$db_key", 0777);
mkdir($_SERVER['DOCUMENT_ROOT'] ."testing/hotapp/bill/$db_key/$dir", 0777);
$pdf->Output($_SERVER['DOCUMENT_ROOT'] ."testing/hotapp/bill/$db_key/$dir/$branch_id"."_"."$sale_order_id"."_".$sale_order['order_date'].".pdf", 'F');

//============================================================+
// END OF FILE
// ============================================================+
?>