<?php
include_once "functions.php";

$post = '{
"params": {
      "SelectionCriteria": {
        "Filter": []
      },
      "FieldNames": [ "CampaignName", "Clicks", "Cost", "Age", "Conversions", "Device",  "Ctr" ], 
      "OrderBy": [{
        "Field": "Clicks"
      }],
      "ReportName": "Actual Data",
      "ReportType": "CAMPAIGN_PERFORMANCE_REPORT",
      "DateRangeType": "AUTO",
      "Format": "TSV",
      "IncludeVAT": "YES",
      "IncludeDiscount": "YES"
    }
}';
$result = sendQuery($post, '/reports', array(
        "returnMoneyInMicros" => 'false'
    )
);

$data = tsv_to_array($result['result']);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Direct</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
<h1>Hello Direct</h1>
<div class="result">
    <?php

    if($result['http_code']!== 200){
        echo "<pre>";
        print_r(json_decode($result['result']));
        echo "</pre>";
        echo "<pre>";
        print_r($result['info']);
        echo "</pre>";

    }else{
    ?>
    <table>
        <tr>
             <?php foreach ($data[0] as $item=>$value){ ?>
                 <th> <?= $item ?></th>
             <?php } ?>
         </tr>
        <?php foreach ($data as $item){ ?>
                <tr>
                    <?php foreach ($item as $value){ ?>
                            <td><?= $value ?></td>
                    <?php } ?>
                </tr>
        <?php } ?>
    </table>
    <?php } ?>
</div>

<script
    src="https://code.jquery.com/jquery-3.2.1.min.js"
    integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
    crossorigin="anonymous"></script>
<script src="js/direct.js"></script>
</body>
</html>