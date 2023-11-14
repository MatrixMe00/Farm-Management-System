<?php 
    //get the profit
    $sql = "SELECT FeedName, SUM(QtyPurchase) as Total, SUM(qtyConsume) AS Consumed, DATE_FORMAT(PostingDate, '%Y-%m') AS year_n_month
        FROM tblfeed
        GROUP BY FeedName, year_n_month DESC
        ORDER BY FeedName
        LIMIT 10
    ";
    $query = $dbh->prepare($sql);
    $query->execute();
    $feed_data = $query->fetchAll(PDO::FETCH_ASSOC);

    if(count($feed_data) > 0):
        // js chart labels for profit
        /*$feed_labels = array_map(function($feed){
            return $feed["year_n_month"];
        }, $feed_data);*/
        $feed_labels = ["TotalQuantity", "TotalConsumed"];

        foreach($feed_data as $data){
            $color = [rand(0,255),rand(0,255),rand(0,255)];
            $color = "rgb(".implode(", ", $color).")";

            $feed_dataset[] = json_encode([
                "label" => $data["FeedName"],
                "backgroundColor" => $color,
                "borderColor" => $color,
                "data" => [intval($data["Total"]), intval($data["Consumed"])]
            ]);
        }
?>
<div class="text-center px-2">
    <p>The chart below shows the eggs summary</p>
</div>

<!-- chart goes here -->
<div class="chart text-center">
    <h1 class="py-3">Feeds Chart</h1>
    <canvas id="feeds_chart"></canvas>
</div>

<?php else : ?>
    <p class="text-center mt-3">No feeds data found</p>
<?php endif; ?>