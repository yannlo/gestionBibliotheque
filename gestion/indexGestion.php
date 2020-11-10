		<div class="center">

			<h1>Acceuil des gestionnaires</h1>

			<section id="listDemande">

				<h2>Listes des demandes recentes :</h2>
				<table>
					<tr>
						<th>nom du livre</th>
						<th>nom du client</th>
						<th>date de la demande</th>
						<th>section de validation</th>
						<th>information complementaire</th>
					</tr>
					<tr>
						<td>les fleurs du mal</td>
						<td>kouakou frederick</td>
						<td>24/11/2020</td>
						<td><a href="#">autoriser</a></td>
						<td><a href="#">detail de la demande</a></td>
					</tr>
					<tr>
						<td>le cahier noir</td>
						<td>kouakou frederick</td>
						<td>01/12/2020</td>
						<td><a href="#">autoriser</a></td>
						<td><a href="#">detail de la demande</a></td>
					</tr>
				</table>

			</section>

			<section id="confirmerRestitution">

				<h2>Confirmation de restitution de livre</h2>
				<form method="POST" action="recherche_emprunt.php">
					<p>
						<label for="search">rechercher un emprunt en cour: </label> <br />
						<input type="search" name="search" id="search" placeholder="Entrer nom du client"
							required="required" />
						<input type="submit" name="valider" value="valider" />
					</p>
				</form>

			</section>

			<section id="listRetardDepot">

				<h2>Listes des retard de restitution :</h2>
				<table>
					<tr>
						<th>nom du livre</th>
						<th>nom du client</th>
						<th>date de restitution presumé</th>
						<th>jour de retard</th>
					</tr>
					<tr>
						<td>les fleurs du mal</td>
						<td>kouakou frederick</td>
						<td>10/12/2020</td>
						<td>4 jours</td>
					</tr>
				</table>

			</section>

			<section id="gestionStock">

				<h2>Gestion du stock de livre</h2>
				<p>
					Souhaitez vous ajouter un nouveau livre a votre stock ou modifier le stock d'un livre deja existant?
				</p>

				<div class="bouttonBox">
					<a href="#"><button>nouveau livre</button></a>

					<a href="#"><button>modifier Stock</button></a>
				</div>

			</section>

			<section id="nouveauClient">

				<h2>Enregistrer un nouveau client:</h2>
				<form method="POST" action="#">
					<!-- enctype="multipart/form-data" -->
					<fieldset id="form-parti1">
						<legend>information personnel (1/3)</legend>
						<p>
							<label for="firstName">Entrer votre nom:</label>
							<input type="text" name="firstName" id="firstName" placeholder="Nom" required="required" />
						</p>
						<p>
							<label for="lastName">Entrer votre prenom:</label>
							<input type="text" name="lastName" id="lastName" placeholder="Prenom" required="required" />
						</p>
						<p>
							<label for="Birthdate">Entrer date de naissance:</label>
							<input type="date" name="Birthdate" id="Birthdate" required="" />
						</p>
					</fieldset>

					<fieldset id="form-parti2">
						<legend>email et mot de passe (2/3)</legend>
						<p>
							<label for="mail">Entrer votre email:</label>
							<input type="email" name="mail" id="mail" placeholder="mon.email@exemple.com"
								required="required" />
						</p>
						<p>
							<label for="password">Entrer votre mot de passe:</label>
							<input type="password" name="password" id="password" required="required" />
						</p>
						<p>
							<label for="passwordVerif">Entrer votre mot de passe:</label>
							<input type="password" name="passwordVerif" id="passwordVerif" required="required" />
						</p>
					</fieldset>

					<fieldset id="form-parti3">
						<legend>contactes (3/3)</legend>
						<p>
							<label for="contact">Entrer votre contacte 1:</label>
							<input type="tel" name="contact" id="contact" placeholder="01 02 03 04" required="required" />
						</p>
						<p>
							<label for="contact2">Entrer votre contacte 2:</label>
							<input type="tel" name="contact2" id="contact2" placeholder="01 02 03 04" />
						</p>
						<input type="submit" name="valider" value="valider" />
					</fieldset>
				</form>

			</section>

		</div>