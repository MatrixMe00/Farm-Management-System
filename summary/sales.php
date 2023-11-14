<?php 
    //get the profit
    $sql = "SELECT (SUM(o.Quantity) * p.ProductPrice) as Total, o.PaymentMode, DATE_FORMAT(InvoiceGenDate, '%Y-%m') AS year_n_month
        FROM tblorders o JOIN tblproducts p ON o.ProductId = p.id
        GROUP BY o.PaymentMode, year_n_month DESC
        ORDER BY year_n_month
        LIMIT 5";
    $query = $dbh->prepare($sql);
    $query->execute();
    $sales_data = $query->fetchAll(PDO::FETCH_ASSOC);

    if(count($sales_data) > 0):
        // js chart labels for profit
        $sales_categories = array_unique(array_map(function($data){
            return $data["PaymentMode"];
        }, $sales_data));
        $sales_labels = array_unique(array_map(function($mortality){
            return $mortality["year_n_month"];
        }, $sales_data));

        $sales_figures = [];

        foreach($sales_labels as $label){
            foreach($sales_categories as $category){
                $figure = array_map(function($data) use ($label, $category){
                    if($data["PaymentMode"] === $category && $data["year_n_month"] === $label){
                        return intval($data["Total"]);
                    }
                }, $sales_data);

                $sales_figures[$label][] = array_sum($figure);
            }
        }

        //create the mortality dataset
        foreach($sales_figures as $label => $figure){
            $color = [rand(0,255),rand(0,255),rand(0,255)];
            $color = "rgb(".implode(", ", $color).")";

            $sales_dataset[] = json_encode([
                "label" => $label,
                "backgroundColor" => $color,
                "borderColor" => $color,
                "data" => $figure
            ]);
        }
?>
<div class="text-center px-2">
    <p>The chart below shows the cash flow in the system</p>
</div>

<!-- chart goes here -->
<div class="chart text-center">
    <h1 class="py-3">Sales Chart</h1>
    <canvas id="sales_chart"></canvas>
</div>

<?php else : ?>
    <p class="text-center mt-3">No cash data found</p>
<?php endif; ?>