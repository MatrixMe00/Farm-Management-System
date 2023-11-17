<?php
require_once('includes/checklogin.php');
check_login();

function formatDump(...$data) {
    echo "<pre>";
    foreach ($data as $item) {
        var_dump($item);
    }
    echo "</pre>";
}
?>
<!DOCTYPE html>
<html lang="en">
<?php $hasChart = true; require_once("includes/head.php");?>
<body>
    <div class="container-scroller">
        <!-- partial:../../partials/_navbar.html -->
        <?php include_once("includes/header.php");?>

        <div class="container-fluid page-body-wrapper">
            <!-- partial:../../partials/_sidebar.html -->
            <?php include_once("includes/sidebar.php");?>

            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="row">
                        <div class="col-lg-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="modal-header">
                                    <h5 class="modal-title">Choose Summary Type</h5>
                                </div>
                                <div class="p-2 d-inline-flex flex-wrap justify-content-center align-items-center">
                                    <button type="button" class="btn btn-primary m-1 card-btn" data-card-id="sales">Cash Summary</button>
                                    <button type="button" class="btn btn-primary m-1 card-btn" data-card-id="mortality">Mortality</button>
                                    <button type="button" class="btn btn-primary m-1 card-btn" data-card-id="eggs">Egg Summary</button>
                                    <button type="button" class="btn btn-primary m-1 card-btn" data-card-id="feed">Feed Summary</button>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12 grid-margin stretch-card" id="summary_displays">
                            <div class="card show-me" id="sales">
                                <div class="modal-header">
                                    <h5 class="modal-title">Cash Summary</h5>
                                </div>
                                <div class="col-md-12 pt-1">
                                    <?php 
                                        require_once("summary/sales.php");
                                        require_once("summary/loss.php");
                                    ?>
                                </div>
                            </div>
                            <div class="card" id="mortality">
                                <div class="modal-header">
                                    <h5 class="modal-title">Mortality Summary</h5>
                                </div>
                                <div class="col-md-12 pt-1">
                                    <?php require_once("summary/mortality.php"); ?>
                                </div>
                            </div>
                            <div class="card" id="eggs">
                                <div class="modal-header">
                                    <h5 class="modal-title">Eggs Summary</h5>
                                </div>
                                <div class="col-md-12 pt-1">
                                    <?php require_once("summary/eggs.php"); ?>
                                </div>
                            </div>
                            <div class="card" id="feed">
                                <div class="modal-header">
                                    <h5 class="modal-title">Feed Summary</h5>
                                </div>
                                <div class="col-md-12 pt-1">
                                    <?php require_once("summary/feed.php"); ?>
                                </div>
                            </div>
                        </div>
                    </div>

                <!-- partial:../../partials/_footer.html -->
                <?php @include("includes/footer.php");?>
            </div>
        </div>
    </div>

    <!-- container-scroller -->
    <?php require_once("includes/foot.php");?>

    <script>
        $(document).ready(function(){
            function updateSummaryDisplays(id = ""){
                $("#summary_displays .card:not(.show-me)").hide();

                if(id !== ""){
                    $("#summary_displays .card#" + id).show();
                }
            }

            // good eggs graph
            function goodEggGraph(){
                var egg_chart = $("canvas#egg_chart_good");
                var config = {
                    type: 'bar',
                    data: {
                        labels: <?= json_encode($egg_labels) ?>,
                        datasets: [
                            <?= implode(",", $egg_dataset_good) ?>
                        ]
                    },
                    options: {}
                }

                var demoChart = new Chart(egg_chart,config);
            }

            // bad eggs graph
            function badEggGraph(){
                var egg_chart = $("canvas#egg_chart_bad");
                var config = {
                    type: 'bar',
                    data: {
                        labels: <?= json_encode($egg_labels) ?>,
                        datasets: [
                            <?= implode(",", $egg_dataset_bad) ?>
                        ]
                    },
                    options: {}
                }

                var demoChart = new Chart(egg_chart,config);
            }

            function mortalityGraph(){
                var mortality_chart = $("canvas#mortality_chart");
                var config = {
                    type: 'bar',
                    data: {
                        labels: <?= json_encode(array_values($mortality_categories)) ?>,
                        datasets: [
                            <?= implode(",", $mortality_dataset) ?>
                        ]
                    },
                    options: {}
                }

                var demoChart = new Chart(mortality_chart,config);
            }

            function lossGraph(){
                var loss_chart = $("canvas#loss_chart");
                var config = {
                    type: 'bar',
                    data: {
                        labels: <?= json_encode(array_values($loss_categories)) ?>,
                        datasets: [
                            <?= implode(",", $loss_dataset) ?>
                        ]
                    },
                    options: {}
                }

                var demoChart = new Chart(loss_chart,config);
            }

            function salesGraph(){
                var sales_chart = $("canvas#sales_chart");
                var config = {
                    type: 'bar',
                    data: {
                        labels: <?= json_encode(array_values($sales_categories)) ?>,
                        datasets: [
                            <?= implode(",", $sales_dataset) ?>
                        ]
                    },
                    options: {}
                }

                var demoChart = new Chart(sales_chart,config);
            }

            function feedsGraph(){
                var feeds_chart = $("canvas#feeds_chart");
                var config = {
                    type: 'bar',
                    data: {
                        labels: <?= json_encode($feed_labels) ?>,
                        datasets: [
                            <?= implode(",", $feed_dataset) ?>
                        ]
                    },
                    options: {}
                }

                var demoChart = new Chart(feeds_chart,config);
            }

            function drawGraphs(){
                salesGraph();
                lossGraph();
                mortalityGraph();
                goodEggGraph();
                badEggGraph();
                feedsGraph();
            }

            //hide summary display cards
            updateSummaryDisplays();
            drawGraphs();

            //show the summary to show based on the button clicked
            $(".card-btn").click(function(){
                const card_id = $(this).attr("data-card-id");
                updateSummaryDisplays(card_id);
            })
        })
    </script>
</body>
</html>