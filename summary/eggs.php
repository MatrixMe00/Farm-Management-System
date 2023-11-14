<?php 
    $sql = "SELECT SUM(TotalNumber) AS TotalGood, SUM(NumberCracked) AS TotalBad, DATE_FORMAT(PostingDate, '%Y-%m') AS year_n_month
        FROM tblegg
        GROUP BY year_n_month DESC
        ORDER BY year_n_month
        LIMIT 10
    ";
    $query = $dbh->prepare($sql);
    $query->execute();
    $egg_data = $query->fetchAll(PDO::FETCH_ASSOC);

    if(count($egg_data) > 0):
        // js chart labels for profit
        $egg_labels = ["Good", "Bad"];

        foreach($egg_data as $data){
            $color = [rand(0,255),rand(0,255),rand(0,255)];
            $color = "rgb(".implode(", ", $color).")";

            $egg_dataset[] = json_encode([
                "label" => $data["year_n_month"],
                "backgroundColor" => $color,
                "borderColor" => $color,
                "data" => [intval($data["TotalGood"]), intval($data["TotalBad"])]
            ]);
        }
?>
<div class="text-center px-2">
    <p>The chart below shows the eggs summary</p>
</div>

<!-- chart goes here -->
<div class="chart text-center">
    <h1 class="py-3">Eggs Chart</h1>
    <canvas id="egg_chart"></canvas>
</div>

<?php else : ?>
    <p class="text-center mt-3">No eggs data found</p>
<?php endif; ?>