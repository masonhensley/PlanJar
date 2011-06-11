<?php

foreach ($query_result->result_array() as $row)
{
    //echo $row['departmentName'];
    ?> 
<div style="border: 2px solid #fff; width:100%; height: auto; padding: 10px;">
    <?php
    echo $row['name'] . "<br/>";
    echo $row['time_of_day'] . "<br/>";
    echo $row['date'] . "<br/>";
    ?>
</div>
    <?php
}
?>
