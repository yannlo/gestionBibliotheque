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
				<form method="GET" action="search_client.php">
					<p>
						<label for="search">rechercher un emprunt en cour: </label> <br />
						<input type="search" name="search_nom_client" id="search" placeholder="Entrer nom du client" required="required" />
						<input type="hidden" name="sexe_client"  value="0" />
							
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
					<a href="add_book.php"><button>nouveau livre</button></a>

					<a href="gestion_livre_index.php"><button>modifier Stock</button></a>
				</div>

			</section>



		</div>