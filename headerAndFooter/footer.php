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
							<li><a href="page_documentation_book.php">documentation sur un livre</a></li>
							<li><a href="add_book.php">ajout de livre</a></li>
							<li><a href="gestion_livre_index.php">gestion des stocks et d'etats</a></li>
						</ul>
					</p>


				</div>

				<div class="part p2">

					<h2>lien de gestion des clients</h2>
					<p>
						<ul>
							<li><a href="client_doc_index.php">documentation sur un client</a></li>
							<li><a href="add_client.php">ajout de client</a></li>
							<li><a href="list_all_demande.php">liste des demandes d'emprunts</a></li>
							<li><a href="list_emprunt.php">liste des emprunts</a></li>
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
					<form method="POST" action="send_message.php">
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
	if(!isset($_SESSION['type']))	{
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
			<form method="POST" action="send_message.php">
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
	if(isset($_SESSION['send_message'])){
		if($_SESSION['send_message'] == 1){
			echo '<script> alert("le message a bien été envoyée.") </script>';
			unset($_SESSION['send_message']);
		}
	}

}
?>
