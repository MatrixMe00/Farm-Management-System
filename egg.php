<?php
include('includes/checklogin.php');
error_reporting(E_ALL);
ini_set('display_errors', 'On');
check_login();
if(isset($_POST['save']))
{
  $eggCategory = $_POST["eggCategory"];
  $eggNumber=$_POST['number'];
  $numberCracked=$_POST['cracked'];

  if($eggNumber < $numberCracked){
    echo '<script>alert("Total number of eggs cannot be less than number of eggs cracked")</script>';
    echo "<script>window.location.href ='egg.php'</script>";
  }else{
    $sql="insert into tblegg(totalNumber,numberCracked)values(:number,:cracked)";
    $query=$dbh->prepare($sql);
    $query->bindParam(':number',$eggNumber,PDO::PARAM_STR);
    $query->bindParam(':cracked',$numberCracked,PDO::PARAM_STR);
    $query->execute();
    $LastInsertId=$dbh->lastInsertId();
    if ($LastInsertId>0) 
    {
      echo '<script>alert("Registered successfully")</script>';
      echo "<script>window.location.href ='egg.php'</script>";
    }
    else
    {
      echo '<script>alert("Something Went Wrong. Please try again")</script>';
    }
  }
}
if(isset($_GET['del'])){    
  $cmpid=$_GET['del'];
  $query=mysqli_query($con,"delete from tblegg where id='$cmpid'");
  echo "<script>alert('Mortality record deleted.');</script>";   
  echo "<script>window.location.href='egg.php'</script>";
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
                <h5 class="modal-title" style="float: left;">Register Egg</h5>
              </div>
              <div class="col-md-12 mt-4">
                <form class="forms-sample" method="post" enctype="multipart/form-data" class="form-horizontal">
                  <div class="row ">
                    <div class="form-group col-md-6">
                      <label for="exampleInputName1">Category of Egg </label>
                      <select name="eggCategory" id="eggCategory" class="form-control" required>
                        <option value="">Select a category</option>
                        <option value="small">Small Size</option>
                        <option value="medium">Medium Size</option>
                        <option value="large">Large Size</option>
                      </select>
                    </div>
                    <div class="form-group col-md-6">
                      <label for="exampleInputName1">Number of Egg </label>
                      <input type="number" name="number" class="form-control" value="" id="product" placeholder="Enter numberof egg" required>
                    </div>
                    <div class="form-group col-md-6">
                      <label for="exampleInputName1">No. of cracked egg </label>
                      <input type="number" name="cracked" class="form-control" value="" id="product" placeholder="Number of crackked egg" required>
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
                      <th class="text-center">Egg Category</th>
                      <th class="text-center">Total Number</th>
                      <th class="text-center">No. of Crackrd</th>
                      <th class="text-center">Posting Date</th>
                      <th class=" Text-center" style="width: 15%;">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $sql="SELECT tblegg.id,tblegg.EggCategory, tblegg.TotalNumber,tblegg.NumberCracked,tblegg.PostingDate from tblegg ORDER BY id DESC";
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
                          <td class="text-center"><?= ucfirst($row->EggCategory). " Size"?></td>
                          <td class="text-center"><?= $row->TotalNumber ?></td>
                          <td class="text-center"><?= $row->NumberCracked ?></td>
                          <td class="text-center"><?= date("d-m-Y", strtotime($row->PostingDate)) ?></td>
                          <td class=" text-center"><a href="#"  class=" edit_data4" id="<?=  ($row->id); ?>" title="click to edit"><i class="mdi mdi-pencil-box-outline text-success" aria-hidden="true"></i></a>
                            <a href="#"  class=" edit_data5" id="<?=  ($row->id); ?>" title="click to view">&nbsp;<i class="mdi mdi-eye" aria-hidden="true"></i></a>
                            <a href="egg.php?del=<?= ($row->id);?>" data-toggle="tooltip" data-original-title="Delete" onclick="return confirm('Do you really want to delete?');"> <i class="mdi mdi-delete text-danger"></i> </a>
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