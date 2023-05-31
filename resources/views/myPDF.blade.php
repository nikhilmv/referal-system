<!DOCTYPE html>
<html>
<head>
<style>
table, th, td {
  border: 1px solid black;
  border-collapse: collapse;
}
th, td {
  padding: 5px;
  text-align: left;
}
</style>
</head>
<body>

<h2>INVOICE</h2>

<!-- <table>
  <tr>
    <th>Sl#</th>
    <th>Test</th>
    <th>Date</th>
  </tr>
  @foreach ($invoiceExport as $key=> $value)
  <tr>
    <td>1</td>
    <td>2</td>
    <td>3</td>
  </tr>
  @endforeach


</table> -->


<table style="width:100%">
 
  <tr>
    <th>Order ID:</th>
    <td>{{$invoiceExport->id}}</td>
  </tr>
  <tr>
    <th>Products:</th>
    <td>

   
   @foreach ( json_decode($invoiceExport->product_id) as $key=> $value)
      {{$value->product_id}}. {{$value->product_name}} X {{$value->quantity}} = {{$value->total}} 
    @endforeach  
    </td>
  </tr>
  <tr>
    <th>Total:</th>
    <td>{{$invoiceExport->amount}}</td>
  </tr> 
</table>


</body>
</html>

