<?php 
    $sql = "SELECT EggCategory, (SUM(TotalNumber) - SUM(NumberCracked)) AS TotalGood, SUM(NumberCracked) AS TotalBad, DATE_FORMAT(PostingDate, '%Y-%m') AS year_n_month
        FROM tblegg
        GROUP BY EggCategory, year_n_month DESC
        ORDER BY year_n_month
        LIMIT 10
    ";
    $query = $dbh->prepare($sql);
    $query->execute();
    $egg_data = $query->fetchAll(PDO::FETCH_ASSOC);

    if(count($egg_data) > 0):
        // js chart labels for profit
        // $egg_labels = ["Good", "Bad"];
        $egg_categories = array_unique(array_map(function($data){
            return $data["EggCategory"];
        }, $egg_data));

        $egg_labels = array_unique(array_map(function($data){
            return $data["year_n_month"];
        }, $egg_data));

        $egg_figures_good = [];
        $egg_figures_bad = [];

        //calculate good eggs
        foreach($egg_labels as $label){
            foreach($egg_categories as $category){
                $figure = array_map(function($data) use ($label, $category){
                    if($data["EggCategory"] === $category && $data["year_n_month"] === $label){
                        return intval($data["TotalGood"]);
                    }
                }, $egg_data);

                $egg_figures_good[$label][] = array_sum($figure);
            }
        }

        //calculate bad eggs
        foreach($egg_labels as $label){
            foreach($egg_categories as $category){
                $figure = array_map(function($data) use ($label, $category){
                    if($data["EggCategory"] === $category && $data["year_n_month"] === $label){
                        return intval($data["TotalBad"]);
                    }
                }, $egg_data);

                $egg_figures_bad[$label][] = array_sum($figure);
            }
        }

        // switch the labels to the categories
        $egg_labels = $egg_categories;

        //dataset for good eggs
        foreach($egg_figures_good as $label => $egg_figure){
            $color = [rand(0,255),rand(0,255),rand(0,255)];
            $color = "rgb(".implode(", ", $color).")";

            $egg_dataset_good[] = json_encode([
                "label" => $label,
                "backgroundColor" => $color,
                "borderColor" => $color,
                "data" => $egg_figure
            ]);
        }

        //dataset for bad eggs
        foreach($egg_figures_bad as $label => $egg_figure){
            $color = [rand(0,255),rand(0,255),rand(0,255)];
            $color = "rgb(".implode(", ", $color).")";

            $egg_dataset_bad[] = json_encode([
                "label" => $label,
                "backgroundColor" => $color,
                "borderColor" => $color,
                "data" => $egg_figure
            ]);
        }
?>
<div class="text-center px-2">
    <p>The chart below shows the eggs summary</p>
</div>

<!-- chart goes here -->
<div class="chart text-center">
    <h1 class="py-3">Eggs Chart [Good]</h1>
    <canvas id="egg_chart_good"></canvas>
</div>

<div class="chart text-center">
    <h1 class="py-3">Eggs Chart [Bad]</h1>
    <canvas id="egg_chart_bad"></canvas>
</div>

<?php else : ?>
    <p class="text-center mt-3">No eggs data found</p>
<?php endif; ?>