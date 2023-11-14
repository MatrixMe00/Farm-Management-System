<?php
    $sql = "SELECT CategoryName, DATE_FORMAT(PostingDate, '%Y-%m') AS year_n_month, SUM(NumberOfDeath) as TotalDeath 
        FROM tblmortality
        GROUP BY CategoryName, year_n_month DESC
        ORDER BY CategoryName
        LIMIT 5";
    $query = $dbh->prepare($sql);
    $query->execute();
    $mortality_data = $query->fetchAll(PDO::FETCH_ASSOC);

    if(count($mortality_data) > 0):
        //js chart labels
        $mortality_categories = array_unique(array_map(function($data){
            return $data["CategoryName"];
        }, $mortality_data));
        $mortality_labels = array_unique(array_map(function($mortality){
            return $mortality["year_n_month"];
        }, $mortality_data));

        $mortality_figures = [];

        foreach($mortality_labels as $label){
            foreach($mortality_categories as $category){
                $figure = array_map(function($data) use ($label, $category){
                    if($data["CategoryName"] === $category && $data["year_n_month"] === $label){
                        return intval($data["TotalDeath"]);
                    }
                }, $mortality_data);

                $mortality_figures[$label][] = array_sum($figure);
            }
        }

        //create the mortality dataset
        foreach($mortality_figures as $label => $figure){
            $color = [rand(0,255),rand(0,255),rand(0,255)];
            $color = "rgb(".implode(", ", $color).")";

            $mortality_dataset[] = json_encode([
                "label" => $label,
                "backgroundColor" => $color,
                "borderColor" => $color,
                "data" => $figure
            ]);
        }
?>
<div class="text-center px-2">
    <p>The chart below shows the mortality rate of different category of animals</p>
    <p>Featured Categories: <?= implode(", ",$mortality_categories) ?></p>
    <p>Featured Period: from <?= date("M Y", strtotime(end($mortality_labels)))." to ".date("M Y", strtotime($mortality_labels[0])) ?></p>
</div>

<!-- chart goes here -->
<div class="chart">
    <canvas id="mortality_chart"></canvas>
</div>
<?php else : ?>
    <p class="text-center mt-3">No data for mortality found</p>
<?php endif; ?>