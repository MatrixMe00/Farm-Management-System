<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
?>
<div class="card-body">
  <?php
  $eid=$_POST['edit_id5'];
  $sql="SELECT tblfeed.id,tblfeed.FeedName,tblfeed.QtyPurchase,tblfeed.qtyConsume,tblfeed.price from tblfeed  where tblproducts.id=:eid";
  $query = $dbh -> prepare($sql);
  $query-> bindParam(':eid', $eid, PDO::PARAM_STR);
  $query->execute();
  $results=$query->fetchAll(PDO::FETCH_OBJ);
  if($query->rowCount() > 0)
  {
    foreach($results as $row)
      {?>

        <h4 style="color: blue">Feed Information</h4>
        <table border="1" class="table table-bordered">
          <tr>
            <th>Feed Name</th>
            <td><?php  echo $row->FeedName;?></td>
          </tr>
          <tr>
            <th>QtY Purchase</th>
            <td><?php  echo $row->QtyPurchase;?></td>
          </tr>
          <tr>
            <th>Qty Consume</th>
            <td><b>$</b>&nbsp;<?php  echo $row->qtyConsume;?></td>
          </tr>
          <tr>
          <tr>
            <th>Price</th>
            <td><b>$</b>&nbsp;<?php  echo $row->priceice;?></td>
          </tr>
          <tr>
            <th>Posting Date</th>
            <td><?php  echo htmlentities(date("d-m-Y", strtotime($row->PostingDate)));?></td>
          </tr>
        </table> 
        <?php 
      }
    } ?>
  </div>