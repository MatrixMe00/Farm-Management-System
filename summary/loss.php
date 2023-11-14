<?php 
    //get the losses
    $sql = "SELECT m.CategoryName, (SUM(m.NumberOfDeath) * p.ProductPrice) AS loss, DATE_FORMAT(m.PostingDate, '%Y-%m') AS year_n_month 
        FROM tblmortality m JOIN tblproducts p ON m.CategoryName = p.CategoryName
        GROUP BY m.CategoryName, year_n_month DESC
        ORDER BY m.CategoryName
        LIMIT 5
    ";
    $query = $dbh->prepare($sql);
    $query->execute();
    $loss_data = $query->fetchAll(PDO::FETCH_ASSOC);

    if(count($loss_data) > 0):
        //js chart labels for losses
        $loss_categories = array_unique(array_map(function($data){
            return $data["CategoryName"];
        }, $loss_data));
        $loss_labels = array_unique(array_map(function($mortality){
            return $mortality["year_n_month"];
        }, $loss_data));

        $loss_figures = [];

        foreach($loss_labels as $label){
            foreach($loss_categories as $category){
                $figure = array_map(function($data) use ($label, $category){
                    if($data["CategoryName"] === $category && $data["year_n_month"] === $label){
                        return intval($data["loss"]);
                    }
                }, $loss_data);

                $loss_figures[$label][] = array_sum($figure);
            }
        }

        //create the mortality dataset
        foreach($loss_figures as $label => $figure){
            $color = [rand(0,255),rand(0,255),rand(0,255)];
            $color = "rgb(".implode(", ", $color).")";

            $loss_dataset[] = json_encode([
                "label" => $label,
                "backgroundColor" => $color,
                "borderColor" => $color,
                "data" => $figure
            ]);
        }
?>
    <div class="chart text-center mt-4">
        <h1 class="py-3">Loss Chart</h1>
        <canvas id="loss_chart"></canvas>
    </div>
<?php else: ?>
    <p class="text-center mt-3">No loss data found</p>
<?php endif; ?>