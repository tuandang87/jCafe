<?php
  //echo "123";

  //require 'database.php';

  $content = "";
  $sumAmount=0;
  $sumQuantity=0;
  $sumNum=0;

  //create content here
  $pdo = Database::connect ();
  $sql = "SELECT OrderItems.ProductId, Products.Name AS ProductName, COUNT(OrderItems.Id) AS NumOfOrderItems,
          SUM(OrderItems.Quantity) AS Quantity, SUM(OrderItems.Amount) AS Amount
          FROM OrderItems INNER JOIN Products ON OrderItems.ProductId = Products.Id
          WHERE DATE(OrderItems.Date) = DATE(NOW())
          GROUP BY OrderItems.ProductId
          ORDER BY Amount DESC";
  $stmt = $pdo->prepare ( $sql );
  $stmt->execute ();
  if ($stmt->errorCode () == 0)
  {   $content.="<h1>" . date("Y/m/d") . "</h1><br>";
      $content .= "<table border='1'><thead>";
      $content .= "<th>TT</th>";
      $content .= "<th>Tên SP</th>";
      $content .= "<th>Số lần đặt</th>";
      $content .= "<th>Số ly</th>";
      $content .= "<th>T.tiền</th>";
      $content .= "</thead>";
      $content .= "<tbody>";
      $index=0;
      while ( ($row = $stmt->fetch ()) != false )
       {
          $index++;
          $sumNum=$sumNum+$row['NumOfOrderItems'];
          $sumQuantity=$sumQuantity+$row['Quantity'];
          $sumAmount=$sumAmount+$row['Amount'];
          $content .= "<tr>";
          $content .= "<td>" . $index . "</td>";
          $content .= "<td>" . $row['ProductName'] . "</td>";
          $content .= "<td>" . $row['NumOfOrderItems'] . "</td>";
          $content .= "<td>" . $row['Quantity'] . "</td>";
          $content .= "<td>" . $row['Amount'] . "</td>";
          $content .= "</tr>";
       }
       $content .= "<tr>";
       $content .= "<td></td>";
       $content .= "<td>Tổng:</td>";
       $content .= "<td>" . $sumNum . "</td>";
       $content .= "<td>" . $sumQuantity . "</td>";
       $content .= "<td>" . $sumAmount . "</td>";
       $content .= "</tr>";
       $content .= "</tbody>";
       $content .= "</table><br><br>";
  }
  $sql = "SELECT Employees.Id AS IdE, Receipts.Id AS Id,Employees.Username,Receipts.CheckInTime,Receipts.CheckOutTime, Receipts.SubTotal, Receipts.ExtraPaid,Receipts.Total FROM Employees INNER JOIN Receipts ON Receipts.CheckOutEmpId=Employees.Id AND DATE(Receipts.CheckInTime)=DATE(NOW())ORDER BY Employees.Id ASC, Receipts.CheckInTime ASC";
  $stmt = $pdo->prepare ( $sql );
  $stmt->execute ();
  if ($stmt->errorCode () == 0)
  {
      $content .= "<table border='1'><thead>";
      $content .= "<th>Mã NV</th>";
      $content .= "<th>Nhân viên</th>";
      $content .= "<th>Mã HĐ</th>";
      $content .= "<th>Thời gian đặt</th>";
      $content .= "<th>Thời gian thanh toán</th>";
      $content .= "<th>T.Tiền</th>";
      $content .= "<th>Phụ thu</th>";
      $content .= "<th>Tổng tiền</th>";
      $content .= "</thead>";
      $content .= "<tbody>";
      $temp=0;
      $sumSub=0;
      $Extra=0;
      $sumTotal;
      while ( ($row1 = $stmt->fetch ()) != false )
      {
          $sumSub+=$row1['SubTotal'];
          $Extra+=$row1['ExtraPaid'];
          $sumTotal+=$row1['Total'];

          if($row1['IdE']!=$temp)
          {
          $content .= "<tr>";
          $content .= "<td>" . $row1['IdE'] . "</td>";
          $content .= "<td>" . $row1['Username'] . "</td>";
          $content .= "<td>" . $row1['Id'] . "</td>";
          $content .= "<td>" . $row1['CheckInTime'] . "</td>";
          $content .= "<td>" . $row1['CheckOutTime'] . "</td>";
          $content .= "<td>" . $row1['SubTotal'] . "</td>";
          $content .= "<td>" . $row1['ExtraPaid'] . "</td>";
          $content .= "<td>" . $row1['Total'] . "</td>";
          $content .= "</tr>"; 
         }
         else
         {
          $content .= "<tr>";
          $content .= "<td></td>";
          $content .= "<td></td>";
          $content .= "<td>" . $row1['Id'] . "</td>";
          $content .= "<td>" . $row1['CheckInTime'] . "</td>";
          $content .= "<td>" . $row1['CheckOutTime'] . "</td>";
          $content .= "<td>" . $row1['SubTotal'] . "</td>";
          $content .= "<td>" . $row1['ExtraPaid'] . "</td>";
          $content .= "<td>" . $row1['Total'] . "</td>";
          $content .= "</tr>"; 
         }
         $temp=$row1['IdE'];
      }
       $content .= "<tr>";
       $content .= "<td></td>";
       $content .= "<td></td>";
       $content .= "<td></td>";
       $content .= "<td></td>";
       $content .= "<td></td>";
       $content .= "<td></td>";
       $content .= "<td>T.Tiền:</td>";
       $content .= "<td>" . $sumSub . "</td>";
       $content .= "</tr>"; 
       $content .= "<tr>";
       $content .= "<td></td>";
       $content .= "<td></td>";
       $content .= "<td></td>";
       $content .= "<td></td>";
       $content .= "<td></td>";
       $content .= "<td></td>";
       $content .= "<td>Tổng Phụ thu:</td>";
       $content .= "<td>" . $Extra . "</td>";
       $content .= "</tr>"; 
       $content .= "<tr>";
       $content .= "<td></td>";
       $content .= "<td></td>";
       $content .= "<td></td>";
       $content .= "<td></td>";
       $content .= "<td></td>";
       $content .= "<td></td>";
       $content .= "<td>T.Cộng:</td>";
       $content .= "<td>" . $sumTotal . "</td>";
       $content .= "</tr>"; 
       $content .= "</tbody>";
       $content .= "</table><br><br>";
  }
  echo $content;
  Database::disconnect ();
?>
