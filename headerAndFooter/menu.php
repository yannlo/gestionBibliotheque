<?php 
if(isset($_SESSION['type'])){

	if($_SESSION['type']=='admin'){

?>

			<nav>
				<input type="checkbox" id="check" />
				<label for="check" class="checkbtn">
					<span class="fas fa-bars"></span>
				</label>
				<label class="logo">Bibliothèque</label>
				<div class="menu">
					<ul>
						<li><a id='id-link1' href="index.php">Acceuil</a></li>
						<li>
							<label for="btn-1" class="show1">
								<a id="link1">Gestion des livres <i id="fleche-1" class="fas fa-angle-down"></i></a>
							</label>

							<input type="checkbox" id="btn-1" onclick="changed_angle('fleche-1','link1','list1');" />
							<ul id='list1'>
								<li><a href="#">Documentation d'un livre</a></li>
								<li><a href="add_book.php">Ajout de livre</a></li>
								<li><a href="#">Gestion des stocks et d'etats</a></li>
							</ul>
						</li>
						<li>
							<label for="btn-2" class="show2">
								<a id='link2'>Gestion des clients <i id="fleche-2" class="fas fa-angle-down"></i></a>
							</label>
							<input type="checkbox" id="btn-2" onclick="changed_angle('fleche-2','link2','list2');" />
							<ul id="list2">
								<li><a href="#">Documentation sur un client</a></li>
								<li><a href="#">Ajout de client</a></li>
								<li><a href="#">Suppression de client</a></li>
								<li><a href="#">Liste d'emprunt</a></li>
								<li><a href="#">Confirmation de restitution de livre</a></li>
							</ul>
						</li>
						<li><a href="deconnection.php" class="connect">Deconnexion</a></li>
					</ul>
				</div>
			</nav>

			<?php
		}
		 else if($_SESSION['type']=='user'){
			?>

			<nav>
				<input type="checkbox" id="check" />
				<label for="check" class="checkbtn">
					<span class="fas fa-bars"></span>
				</label>
				<label class="logo">Bibliothèque</label>
				<div class="menu">
					<ul>
						<li><a id='id-link1'  href="index.php">Acceuil</a></li>
						<li>
							<label for="btn-1" class="show1">
								<a id="link1">Nos livres <i id="fleche-1" class="fas fa-angle-down"></i></a>
							</label>

							<input type="checkbox" id="btn-1" onclick="changed_angle('fleche-1','link1','list1');" />
							<ul id='list1'>
								<li><a href="#">listes des livres</a></li>
								<li><a href="#">Demander un emprunt</a></li>
							</ul>
						</li>
						<li><a href="#" id='link2'>Vos emprunts</a></li>
						<li><a href="deconnection.php" class="connect">Deconnexion</a></li>
					</ul>
				</div>
			</nav>
	
			<?php
		}else{


		?>
		<nav>
			<input type="checkbox" id="check" />
			<label for="check" class="checkbtn">
				<span class="fas fa-bars"></span>
			</label>
			<label class="logo">Bibliothèque</label>
			<div class="menu">
				<ul>
					<li><a  href="index.php" id='id-link1'>Acceuil</a></li>
					<li><a  href="#" id='id-link2'>Nos livres</a></li>
					<li><a href="connection.php"  id="id-link-connect" class="connect">Connexion</a></li>
				</ul>
			</div>
		</nav>

		<?php
		}
	}
	
	if(!isset($_SESSION['type'])){
			?>

		<nav>
			<input type="checkbox" id="check" />
			<label for="check" class="checkbtn">
				<span class="fas fa-bars"></span>
			</label>
			<label class="logo">Bibliothèque</label>
			<div class="menu">
				<ul>
					<li><a id='id-link1' href="index.php">Acceuil</a></li>
					<li><a  href="#" id='id-link2' >Nos livres</a></li>
					<li><a href="connection.php"  id="id-link-connect" class="connect">Connexion</a></li>
				</ul>
			</div>
		</nav>
					
		
	<?php
	}

?>

<script>
	function changed_angle(fleche, link, list) {
		/**
		 * move up and down for all angle
		 */
		const elt = document.getElementById(fleche);
		const elt2 = document.getElementById(link);
		const elt3 = document.getElementById(list);
		if(elt.className !='active'){
			if (elt.className == 'fas fa-angle-down') {
				elt.setAttribute("class", "fas fa-angle-up");
				elt2.style.background = "#0066ff";
				elt3.style.visibility = "visible";
				elt3.style.opacity = '1';
				elt3.style.top = '70px';
			} else if (elt.className == 'fas fa-angle-up') {
				elt.setAttribute("class", "fas fa-angle-down");
				elt2.style.background = "none";
				elt3.style.visibility = "hidden";
				elt3.style.opacity = '0';
				elt3.style.top = '90px';
			}
		}else{
			elt2.style.background = "#0066ff";

		}
	}

	function actived_link_page(valeur){
		const link1 = document.getElementById('id-link1');
		const link2 = document.getElementById('id-link2');
		const link3 = document.getElementById('id-link-connect');
		const link4 = document.getElementById('link1');
		const link5 = document.getElementById('link2');
		switch (valeur) {
			case 'index':
				link1.setAttribute("class", "active");
				console.log('ok');
				break;
			case 'book':
				link2.setAttribute("class", "active");
				console.log('ok');
				break;
			case 'connect-book':
				link4.setAttribute("class", "active");
				console.log('ok');
				break;
			case 'client':
				link5.setAttribute("class", "active");
				console.log('ok');
				break;
			case 'connect':
				link3.setAttribute("class", "active");
				console.log('ok');
				break;
		
			default:
				console.log('error');
				break;
		}

	}


	
</script>