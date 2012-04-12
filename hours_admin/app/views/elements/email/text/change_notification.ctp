<?php
echo $model.' record '; if(!empty($name)) { echo 'for '.$name.' '; } echo $changed.".\r\n";

if(!empty($fields)) {
    echo "\r\n".'Changed Fields: ';
    $count = count($fields); $i = 1;
    foreach($fields as $field) {
        echo "'".$field."'";
        if($i == $count-1) { echo ' and '; }
        elseif($i !== $count) { echo ', '; }
        $i++;
    }
    echo "\r\n";
}

if($changed !== 'deleted') {
?>

See <?php echo ADMIN_URL.$models."/view/".$id; ?>.
<?php
} // end if $changed !== 'deleted'
if(!empty($details)) {
?>

Details of <?php echo $changed; ?> record:

<?php
    foreach($details as $key=>$value) {
        echo $key.": ".$value."\r\n";
    }
} // end if !empty(details)

?>

Contact Library HR with any questions.