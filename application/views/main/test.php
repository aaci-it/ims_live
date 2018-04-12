<?php $options = array(
                  'small'  => 'Small Shirt',
                  'med'    => 'Medium Shirt',
                  'large'   => 'Large Shirt',
                  'xlarge' => 'Extra Large Shirt',
                );

$shirts_on_sale = array('small', 'large');

//echo form_dropdown('shirts', $options, 'large'); 

// Would produce:?>


<?php
$js = 'id="shirts" onChange="alert(\\"Hello World\");"';
echo form_dropdown('shirts', $options, 'large', $js);
?>
