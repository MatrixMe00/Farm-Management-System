<?php
include('includes/checklogin.php');
check_login();
if(isset($_POST['save']))
{
  
  $feedName=$_POST['feed'];
  $qtyPurchase=$_POST['feedNum'];
  $qtyConsume=$_POST['consume'];
  $rate=$_POST['price'];

  if($qtyPurchase < $qtyConsume){
    echo '<script>alert("Quantity consumed is beyond quantity purchased")</script>';
  }else{
    $sql="insert into tblfeed(FeedName,QtyPurchase,qtyConsume,price)values(:feed,:feedNum,:consume,:price)";
    $query=$dbh->prepare($sql);
    $query->bindParam(':feed',$feedName,PDO::PARAM_STR);
    $query->bindParam(':feedNum',$qtyPurchase,PDO::PARAM_STR);
    $query->bindParam(':consume',$qtyConsume,PDO::PARAM_STR);
    $query->bindParam(':price',$rate,PDO::PARAM_STR);
    $query->execute();
    $LastInsertId=$dbh->lastInsertId();
    if ($LastInsertId>0) 
    {
      echo '<script>alert("Registered successfully")</script>';
      echo "<script>window.location.href ='feed.php'</script>";
    }
    else
    {
      echo '<script>alert("Something Went Wrong. Please try again")</script>';
    }
  }
}
if(isset($_GET['del'])){    
  $cmpid=$_GET['del'];
  $query=mysqli_query($con,"delete from tblfeed where id='$cmpid'");
  echo "<script>alert('Mortality record deleted.');</script>";   
  echo "<script>window.location.href='feed.php'</script>";
}
?>
<!DOCTYPE html>
<html lang="en">
<?php @include("includes/head.php");?>
<body>
  <div class="container-scroller">
    <!-- partial:../../partials/_navbar.html -->
    <?php @include("includes/header.php");?>
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <!-- partial:../../partials/_sidebar.html -->
      <?php @include("includes/sidebar.php");?>
      <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
              <div class="card">
               <div class="modal-header">
                <h5 class="modal-title" style="float: left;">Register Feed</h5>
              </div>
              <div class="col-md-12 mt-4">
                <form class="forms-sample" method="post" enctype="multipart/form-data" class="form-horizontal">
                  <div class="row ">
                  <div class="form-group col-md-6">
                      <label for="exampleInputName1">Feed Category </label>
                      <select name="feed" id="product" class="form-control" required>
                        <option value="">Select Category</option>
                        <option value="broiler">Broiler Feed</option>
                        <option value="kuroiler">Kuroiler Feed</option>
                        <option value="layer">Layer Feed</option>
                        <option value="chick">Chick Feed</option>
                      </select>
                    </div>
                    <div class="form-group col-md-6">
                      <label for="exampleInputName1">Feed quatity purchased</label>
                      <input type="number" name="feedNum" class="form-control" value="" id="product" placeholder="Enter quantity" required>
                    </div>
                  </div>
                  <div class="row ">
                    <div class="form-group col-md-6">
                      <label for="exampleInputName1">Feed quantity consumed</label>
                      <input type="number" name="consume" value="" placeholder="Enter quantity" class="form-control" id="price" required>
                    </div>
                    <div class="form-group col-md-6">
                      <label for="exampleInputName1">Rate</label>
                      <input type="number" name="price" value="" placeholder="Enter Price" class="form-control" id="price"required>
                    </div>
                  </div>
                  <button type="submit" style="float: left;" name="save" class="btn btn-primary  mr-2 mb-4">Save</button>
                </form>
              </div>
            </div>
          </div>
          <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
              <!--  start  modal -->
              <div id="editData4" class="modal fade">
                <div class="modal-dialog modal-md">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title">Edit Product details</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body" id="info_update4">
                      <?php @include("edit_product.php");?>
                    </div>
                    <div class="modal-footer ">
                      <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    </div>
                    <!-- /.modal-content -->
                  </div>
                  <!-- /.modal-dialog -->
                </div>
                <!-- /.modal -->
              </div>
              <!--   end modal -->
              <!--  start  modal -->
              <div id="editData5" class="modal fade">
                <div class="modal-dialog modal-md">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title">View product details</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body" id="info_update5">
                      <?php @include("view_product.php");?>
                    </div>
                    <div class="modal-footer ">
                      <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    </div>
                    <!-- /.modal-content -->
                  </div>
                  <!-- /.modal-dialog -->
                </div>
                <!-- /.modal -->
              </div>
              <!--   end modal -->
              <div class="table-responsive p-3">
                <table class="table align-items-center table-flush table-hover table-bordered" id="dataTableHover">
                  <thead>
                    <tr>
                      <th class="text-center">No</th>
                      <th class="text-center">Feed Category</th>
                      <th class="text-center">Bird Category</th>
                      <th class="text-center"> Price</th>
                      <th class="text-center"> Qty Purchase</th>
                      <th class="text-center"> Qty Consumed</th>
                      <th class="text-center">Posting Date</th>
                      <th class=" Text-center" style="width: 15%;">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $sql="SELECT tblfeed.id,tblfeed.FeedName,tblfeed.QtyPurchase,tblfeed.qtyConsume,tblfeed.price,tblfeed.PostingDate from tblfeed ORDER BY id DESC";
                    $query = $dbh -> prepare($sql);
                    $query->execute();
                    $results=$query->fetchAll(PDO::FETCH_OBJ);
                    $cnt=1;
                    if($query->rowCount() > 0)
                    {
                      foreach($results as $row)
                      { 
                        ?>
                        <tr>
                          <td class="text-center"><?= $cnt ?></td>
                          <td class="text-center"><?= ucwords($row->FeedName)." Feed" ?></td>
                          <td class="text-center"><?= ucfirst($row->FeedName) ?></td>
                          <td class="text-center"><?= $row->price ?></td>
                          <td class="text-center"><?= $row->QtyPurchase ?></td>
                          <td class="text-center"><?= $row->qtyConsume ?></td>
                          <td class="text-center"><?= htmlentities(date("d-m-Y", strtotime($row->PostingDate)));?></td>
                          <td class=" text-center"><a href="#"  class=" edit_data4" id="<?=  ($row->id); ?>" title="click to edit"><i class="mdi mdi-pencil-box-outline text-success" aria-hidden="true"></i></a>
                            <a href="#"  class=" edit_data5" id="<?=  ($row->id); ?>" title="click to view">&nbsp;<i class="mdi mdi-eye" aria-hidden="true"></i></a>
                            <a href="feed.php?del=<?= ($row->id);?>" data-toggle="tooltip" data-original-title="Delete" onclick="return confirm('Do you really want to delete?');"> <i class="mdi mdi-delete text-danger"></i> </a>
                          </td>
                        </tr>
                        <?php 
                        $cnt=$cnt+1;
                      }
                    } ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- content-wrapper ends -->
      <!-- partial:../../partials/_footer.html -->
      <?php @include("includes/footer.php");?>
      <!-- partial -->
    </div>
    <!-- main-panel ends -->
  </div>
  <!-- page-body-wrapper ends -->
</div>
<!-- container-scroller -->
<?php @include("includes/foot.php");?>
<!-- End custom js for this page -->
<script type="text/javascript">
  $(document).ready(function(){
    $(document).on('click','.edit_data4',function(){
      var edit_id4=$(this).attr('id');
      $.ajax({
        url:"edit_product.php",
        type:"post",
        data:{edit_id4:edit_id4},
        success:function(data){
          $("#info_update4").html(data);
          $("#editData4").modal('show');
        }
      });
    });
  });
</script>
<script type="text/javascript">
  $(document).ready(function(){
    $(document).on('click','.edit_data5',function(){
      var edit_id5=$(this).attr('id');
      $.ajax({
        url:"view_product.php",
        type:"post",
        data:{edit_id5:edit_id5},
        success:function(data){
          $("#info_update5").html(data);
          $("#editData5").modal('show');
        }
      });
    });
  });
</script>

</body>
</html>