<?php

class AdsView {
	protected $model;
	function __construct($model){
		$this->model = $model;
	}


	public function render($data){ 
		$randomTexts = $this->model->getRandomAdText(count($data));
		$i = 0;
		foreach($data as $d) { ?>
			  <div class="Customers-box Customers-box-<?php echo $d->Car_id; ?>">
				<div class="Customers-image-wrap">
				<?php if($d->picture != null && $d->picture != '') { ?>
					<img src="<?php echo $d->picture; ?>" />
				<?php } ?>
				</div>
				<div class="Customers-ad">
					<div class="Customers-quote">
						<?php echo $this->model->replacePlaceHolders($d->modelName, $d->brand, $randomTexts[$i]); ?>
					</div>
					<div class="Customers-name"><?php echo $d->gender.' '.$d->surname; ?>, <?php echo $d->city; ?></div>
					<div class="Customers-car"><?php echo $d->year.' '.$d->brand.' '.$d->modelName;  ?></div>
				</div>
			</div>
	<?php  $i++;
		}   
	}
}
