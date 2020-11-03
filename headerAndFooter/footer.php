<?php
	if(isset($_SESSION['type'])){
		if($_SESSION['type'] == 'admin')
		{
		?>
		<footer>
			<div class="footer-content">

				<div class=" part p1">

					<h2>lien de gestion de livre</h2>
					<p>
						<ul>
							<li><a href="#">documentation sur un livre</a></li>
							<li><a href="#">ajout de livre</a></li>
							<li><a href="#">supression de livre</a></li>
							<li><a href="#">gestion des stocks et d'etats</a></li>
						</ul>
					</p>


				</div>

				<div class="part p2">

					<h2>lien de gestion des clients</h2>
					<p>
						<ul>
							<li><a href="#">documentation sur un client</a></li>
							<li><a href="#">ajout de client</a></li>
							<li><a href="#">suppression de client</a></li>
							<li><a href="#">confirmation de restitution de livre</a></li>
						</ul>
					</p>


				</div>

			</div>


			<p class="copyright">copyright 2020 Bibliotheque Project All right reserved</p>
		</footer>

		<?php

		}
		else{
				?>
					<footer>
			<div class="footer-content">

				<div class =" part p1" >
					
					<h2>Bibliotheque presentation</h2>
					<p>
						la bibliotheque de l'ESATIC offre un grand nombre de livre
						et propose des enprunt pour tout les membres afin de rendre 
						accecible a tous la cultures et la connaissance. 
						la bibliotheque de l'ESATIC offre un grand nombre de livre
						et propose des enprunt pour tout les membres afin de rendre 
						accecible a tous la cultures et la connaissance. 
					</p>
					

				</div>
				
				<div class ="part p2" >
					
					<h2>Contactez-nous</h2>
					<form action="">
						<p>
							<label for="mail">enter votre email:*</label> <br/>
							<input type="email" name="mail" id="mail" placeholder="mon.mail@exemple.com" required="required" />
						</p> 
						<p>
							<label for="message">enter votre message:*</label> <br/>
							<textarea type="email" name="message" id="message" placeholder="max 255 caracteres" required="required"></textarea>
						</p>
						<input type="submit" name="valider" value="valider" />
					</form>
					
				</div>
				
			</div>


			<p class="copyright">copyright 2020 Bibliotheque Project All right reserved</p>
		</footer>

			<?php

		}
	}
?>
