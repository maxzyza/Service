<ul>
    <?php foreach($rates as $rate):?>
    <li><?php echo $rate->name.' '.CHtml::link('Купить',array('buy', 'id' => $rate->id))?></li>
    <?php endforeach;?>
</ul>

