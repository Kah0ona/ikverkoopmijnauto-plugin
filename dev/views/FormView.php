<?php

class FormView {
	protected $model;
	function __construct($model){
		$this->model = $model;
	}	


	public function render(){ ?>
		<script>
			var serviceUrlBackend = '<?php echo BASE_URL_BACKEND.'/carbrands'; ?>';
			var submitFormUrl = '<?php echo BASE_URL_BACKEND.'/carbrands'; ?>';
		</script>
		<script src="<?php echo plugins_url().'/ikverkoopmijnauto-plugin/js/jquery.form.min.js'; ?>" type="text/javascript"></script>
		<script src="<?php echo plugins_url().'/ikverkoopmijnauto-plugin/js/jquery-validation/jquery.validate.min.js'; ?>" type="text/javascript"></script>
		<script src="<?php echo plugins_url().'/ikverkoopmijnauto-plugin/js/form.js'; ?>" type="text/javascript"></script>
		<div class="Sell">
			<form class="Sell-form" enctype="multipart/form-data" id="order-form" action="<?php echo site_url(); ?>/wp-admin/admin-ajax.php" method="post">
				<fieldset>
				<div class="Sell-sectiontitle">Persoonsgegevens</div>
				<div class="Sell-controlgroup">
					<label for="gender">Aanhef: *</label>
					<div class="Sell-radiogroup">
						<input type="radio" name="gender" value="Dhr">Dhr.
						<input type="radio" name="gender" value="Mevrouw">Mevrouw
					</div>
				</div>

				<div class="Sell-controlgroup">
					<label for="firstname">Voornaam: *</label>
					<input type="text" name="firstname" placeholder="Vul uw gegevens hier in..." />
				</div>

				<div class="Sell-controlgroup">
					<label for="surname">Achternaam: *</label>
					<input type="text" name="surname" />
				</div>

				<div class="Sell-controlgroup">
					<label for="street">Adres: *</label>
					<input type="text" name="street" />
				</div>

				<div class="Sell-controlgroup">
					<label for="zipcode">Postcode: *</label>
					<input type="text" name="zipcode" />
				</div>

				<div class="Sell-controlgroup">
					<label for="city">Stad: *</label>
					<input type="text" name="city" />
				</div>

				<div class="Sell-controlgroup">
					<label for="country">Land: *</label>
					<input type="text" name="country" />
				</div>

				<div class="Sell-controlgroup">
					<label for="email">Email: *</label>
					<input type="text" name="email" />
				</div>

				<div class="Sell-controlgroup">
					<label for="phone">Telefoon: *</label>
					<input type="text" name="phone" />
				</div>

				<div class="Sell-sectionbreak"></div>
				<div class="Sell-sectiontitle">Autokenmerken</div>

				<div class="Sell-controlgroup">
					<label for="brand">Merk: *</label>
					<select name="brand" class="brand"></select>
				</div>

				<div class="Sell-controlgroup">
					<label for="modelName">Model: *</label>
					<select name="modelName" class="modelName"></select>
				</div>

				<div class="Sell-controlgroup">
					<label for="year">Bouwjaar: *</label>
					<input type="text" name="year" />
				</div>

				<div class="Sell-controlgroup">
					<label for="km">KM-stand: *</label>
					<input type="text" name="km" />
				</div>

				<div class="Sell-controlgroup">
					<label for="carrosserie">Carrosserie: *</label>
					<select name="carrosserie" >
						<option>hatchback 3dr</option>  
						<option>sedan 4dr</option>  
						<option>hatchback 5dr</option>  
						<option>MPV 5 persoons</option>  
						<option>MPV 7 persoons</option>  
						<option>SUV</option>  
						<option>Stationcar</option>  
						<option>Cabriolet</option>  
						<option>Coupe</option>  
						<option>CC Cabriolet en Coupe</option>  
						<option>Terreinwagen</option>  
						<option>Bestelwagen enkele cabine</option>  
						<option>Bestelwagen dubbele cabine</option>  
						<option>Camper</option>  
						<option>Caravan</option>  
						<option>Aanhanger</option>  
						<option>Vouwwagen</option> 
					</select>
				</div>
				<div class="Sell-controlgroup">
					<label for="gearing">Versnelling: *</label>
					<select name="gearing" >
						<option>Handgeschakeld</option> 
						<option>Automaat</option> 
						<option value="Half automaat">Half-automaat</option> 
					</select>
				</div>
				<div class="Sell-controlgroup">
					<label for="colour">Kleur: *</label>
					<select name="colour" >
						<option>BEIGE</option>  
						<option>BEIGE METALLIC</option>  
						<option>BLAUW</option>  
						<option>BLAUW METALLIC</option>  
						<option>GROEN</option>  
						<option>GROEN METALLIC</option>  
						<option>BRUIN</option>  
						<option>BRUIN METALLIC</option>  
						<option>GEEL</option>  
						<option>GEEL METALLIC</option>  
						<option>GRIJS</option>  
						<option>GRIJS METALLIC</option>  
						<option>ORANJE</option>  
						<option>ORANJE METALLIC</option>  
						<option>PAARS</option>  
						<option>PAARS METALLIC</option>  
						<option>ROOD</option>  
						<option>ROOD METALLIC</option>  
						<option>ROSE</option>  
						<option>ROSE METALLIC</option>  
						<option>WIT</option>  
						<option>WIT METALLIC</option>  
						<option>ZILVERGRIJS</option>  
						<option>ZILVERGRIJS METALLIC</option>  
						<option>ZWART</option>  
						<option>ZWART METALLIC</option>
					</select>
				</div>
				<div class="Sell-controlgroup">
					<label for="paintType">Laksoort: *</label>
					<select name="paintType" >
						<option>Gewone lak</option>  
				        <option>Metallic effect</option>  
					    <option>Pareleffect</option> 
					</select>
				</div>
				<div class="Sell-controlgroup">
					<label for="interior">Bekleding: *</label>
					<select name="interior" >
						<option>Alcantara</option>  
						<option>Stof</option>  
						<option>Half leder</option>  
						<option>Leder</option> 
					</select>
				</div>
				<div class="Sell-controlgroup">
					<label for="hasPictures">Heeft u foto's?: *</label>
					<select name="hasPictures" >
						<option value="true">Ja</option>  
						<option value="false">Nee</option>  
					</select>
				</div>
				<div class="Sell-controlgroup">
					<label for="hasDamage">Is er schade?: *</label>
					<select name="hasDamage" >
						<option value="true">Ja</option>  
						<option value="false">Nee</option>  
					</select>
				</div>
				<div class="Sell-controlgroup">
					<label for="statusOutside">Staat buitenkant: *</label>
					<select name="statusOutside" >
						<option>Goed</option>  
						<option>Redelijk</option>  
						<option>Slecht</option>  
					</select>
				</div>

				<div class="Sell-controlgroup">
					<label for="statusInside">Staat binnenkant: *</label>
					<select name="statusInside" >
						<option>Goed</option>  
						<option>Redelijk</option>  
						<option>Slecht</option>  
					</select>
				</div>

				<div class="Sell-controlgroup">
					<label for="statusMechanical">Technische staat: *</label>
					<select name="statusMechanical" >
						<option>Goed</option>  
						<option>Redelijk</option>  
						<option>Slecht</option>  
					</select>
				</div>

				<div class="Sell-controlgroup">
					<label for="statusTires">Staat banden: *</label>
					<select name="statusTires" >
						<option>Goed</option>  
						<option>Redelijk</option>  
						<option>Slecht</option>  
					</select>
				</div>

				<div class="Sell-controlgroup" >
					<label for="driveable">Rijdbaar?: *</label>
					<select name="driveable" >
						<option value="Ja">Ja</option>  
						<option value="Nee">Nee</option>  
					</select>
				</div>
				<div class="Sell-controlgroup">
					<label for="comment" class="block" >Heeft u zelf nog opmerkingen?</label>
					<textarea name="comment" rows="10"></textarea>
				</div>
				

				<div class="Sell-sectionbreak"></div>
				<div class="Sell-sectiontitle">Foto's</div>

				<div class="Sell-controlgroup" >
					<?php for($i=1;$i<=5;$i++){ ?>
					<label for="picture[]">Foto <?php echo $i ?>:</label>
					<input type="file" name="picture[]"  />
					<?php } ?>
				</div>


				<p>Bij het versturen gaat u akkoord met de <a href="/algemene-voorwaarden">algemene voorwaarden</a>.
				<div class="Sell-controlgroup">
					<input type="submit" name="submit" value="Auto aanmelden" />
				</div>
				</fieldset>
			</form>
		</div>
	<?php 
	}

}
