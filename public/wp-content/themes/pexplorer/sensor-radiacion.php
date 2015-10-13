<?php

global $sensor;

$sensor->units = 'kW-h/m^2';
$sensor->description = 'Radiación solar acumulada';

?>

<h5><?php echo $sensor->description; ?> <small>(<?php echo $sensor->units; ?>)</small></h5>

<p>Canal #<?php echo $sensor->channelNumber; ?></p>

<?php if( ! empty( $sensor->height ) ): ?>
  <small>Altura del sensor: <?php echo $sensor->height; ?>m.</small>
<?php endif; ?>

<?php $records = windpexplorer_get_last_records( $sensor->idSensor ); ?>

<script type="text/javascript" id="ds-<?php echo $sensor->idSensor; ?>">
  var dataSource<?php echo $sensor->idSensor; ?> = [
    <?php foreach( $records as $record ): ?>
      {
        day:   "<?php echo date_format( date_create( $record->dateCreated ), 'd-M' ); ?>",
        value: <?php echo $record->avg * 0.024; ?>
      },
    <?php endforeach; ?>
  ];
</script>

<div id="chart-<?php echo $sensor->idSensor; ?>" class="chart" data-sensor="<?php echo $sensor->idSensor; ?>" data-name="<?php echo $sensor->description; ?> (<?php echo $sensor->units; ?>)" style="height: 300px;"></div>
