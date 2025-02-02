<?php
session_start();
$serverName = "DESKTOP-H147H0H\\SQLEXPRESS";
$database = "projetsn";
$username = "";
$password = "";
$conn = new PDO("sqlsrv:Server=$serverName;Database=$database", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Récupération des livres en stock
$stmt = $conn->prepare("SELECT * FROM Livres WHERE quantite > 0");
$stmt->execute();
$livres = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ACCUEIL LIBRAIRIE</title>
    <link rel="stylesheet" href="accueil.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

    <div id="modalPanier" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Votre Panier</h2>
        <div id="panierContent"></div>
    </div>
</div>


<style>
    .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); }
    .modal-content { background: white; padding: 20px; width: 50%; margin: 10% auto; border-radius: 10px; }
    .close { float: right; cursor: pointer; font-size: 20px; }
    .commentaire{
       
    background-color: #e67e22;
    cursor: pointer;
    margin-top: 50px;
    display: inline-block;
    display:flex;
    margin-left:800px;
    margin-bottom:10px
    }

    .commentaire:hover {
background-color:darkorange;

}

.sidebar {
    width: 20%;
    background-color: #3498db;
    display: flex;
    flex-direction: column;
    padding: 20px 0;
    gap: 15px;
    align-items: center;
    height: 1000px;
}

.user-info {
    text-align: center;
    margin-bottom: 20px;
}

img{
    border-radius: 50%;
    height: 100px;
    width: 100px;
}

.statu {
    font-size: 12px;
    color: lightgreen;
}

.sidebar-button {
    width: 100%;
    padding: 15px;
    background-color: white;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    display: flex;
    align-items: center;
    gap: 10px;
    cursor: pointer;
    margin-bottom:10px;
}

th{
  background-color:#3498db;
}

</style>



    <div class="container">
        <aside class="sidebar">
        
        <div class="user-info">
            <div class="profile-pic"></div>
            <img class="image" src="téléchargement.png" alt="profile picture">
            <p>Compte Client</p>
            <span class="statu">Online</span>
            <button class="sidebar-button"  onclick="window.location.href='mon_compte.php'"><span>👤</span> Mon Compte</button>
            <button class="sidebar-button" id="monPanier"><span>🛒</span> Mon panier <span id="notifPanier">0</span></button>
            <button class="sidebar-button"  ><span>📦</span> Mes commandes</button>
            <button class="sidebar-button"  id="logoutButton" ><span>⏻</span> Se déconnecter</button>
        </aside>
        
        <main class="main-content">
        <?php

if (!isset($_SESSION['nom'])) {
    header("Location: login.html");
    exit();
}
echo "Bienvenue, " . $_SESSION['nom'];
?>
            <div class="header">
            
                <img src="logo.jpg" alt="Logo" class="logo">
                <h1>BIENVENUE CHEZ BookShop</h1>
            </div>
           
            <div class="search-bar">
                <input type="text" id="search" placeholder="Rechercher un livre...">
                <button class="search-button">Rechercher</button>
                <select id="category">
                    <option value="tous">Toutes les catégories</option>
                    <option value="scolaires">Scolaires</option>
                    <option value="educatifs">Éducatifs</option>
                    <option value="loisirs">Loisirs</option>
                </select>
                <button class="filter-button">Filtrer</button>
            </div>
            <button class="commentaire" onclick="window.location.href='commentaire.html'">Envoyez un commentaire</button>
            
            <table class="book-table">
                <thead>
                    <tr>
                        <th>Photo</th>
                        <th>Nom</th>
                        <th>Catégorie</th>
                        <th>Prix</th>
                        <th>Quantité</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="book-list">
                    <?php foreach ($livres as $livre): ?>
                    <tr>
                        <td><img src="<?= htmlspecialchars($livre['photo']) ?>" width="50"></td>
                        <td><?= htmlspecialchars($livre['nom']) ?></td>
                        <td><?= htmlspecialchars($livre['categorie']) ?></td>
                        <td><?= number_format($livre['prix'], 2) ?>fcfa</td>
                        <td>
                            <input type="number" min="1" max="<?= $livre['quantite'] ?>" value="1" id="quantite-<?= $livre['id'] ?>">
                        </td>
                        <td>
                        <button class="add-to-cart" 
        data-id="<?= $livre['id']; ?>" 
        data-nom="<?= $livre['nom']; ?>" 
        data-prix="<?= $livre['prix']; ?>" 
        data-photo="<?= $livre['photo']; ?>" 
        data-stock="<?= $livre['quantite']; ?>">
    Ajouter au panier
</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </main>
    </div>

    <script>
        var panier = [];

$(".add-to-cart").click(function() {
  var idArticle = $(this).data("id");
  var quantite = $("#quantite-" + idArticle).val();
  var nom = $(this).data("nom");
  var prix = $(this).data("prix");
  var photo = $(this).data("photo");

  // Vérifie si l'article est déjà dans le panier
  if ($.inArray(idArticle, panier) !== -1) {
    // Met à jour la quantité de l'article dans le panier
    panier[idArticle].quantite += parseInt(quantite);
  } else {
    // Ajoute l'article au panier
    panier[idArticle] = {
      id: idArticle,
      nom: nom,
      prix: prix,
      photo: photo,
      quantite: parseInt(quantite)
    };
  }

  // Met à jour le nombre d'articles dans le panier
  $("#notifPanier").text(Object.keys(panier).length);

  // Envoie les données au serveur pour mise à jour de la session
  $.ajax({
    type: "POST",
    url: "ajout_panier.php",
    data: {
      id: idArticle,
      nom: nom,
      prix: prix,
      photo: photo,
      quantite: quantite
    },
    success: function(data) {
      console.log(data);
    }
  });
});

$("#monPanier").click(function() {
  afficherPanier();
});

function afficherPanier() {
  $.ajax({
    type: "POST",
    url: "panier.php",
    success: function(data) {
      $("#panierContent").html(data);
      $("#modalPanier").fadeIn();
    }
  });
}

function supprimerDuPanier(id) {
  $.ajax({
    type: "POST",
    url: "supprimer_Panier.php",
    data: {id: id},
    success: function(data) {
      console.log(data);
      afficherPanier();
    }
  });
}

$(".close").click(function() {
  $("#modalPanier").fadeOut();
});

$("#modalPanier").on("click", function(event) {
  if ($(event.target).hasClass("modal")) {
    $(this).fadeOut();
  }
});

$(document).ready(function() {
  $("#modalPanier").hide();
});

$(document).ready(function() {
    function rechercherFiltrer() {
        var search = $("#search").val();
        var category = $("#category").val();

        $.ajax({
            type: "POST",
            url: "rechercher_filtrage.php",
            data: {
                search: search,
                category: category
            },
            success: function(data) {
                $("#book-list").html(data);
            }
        });
    }

    // Exécuter la recherche quand on tape dans le champ de recherche
    $("#search").on("keyup", function() {
        rechercherFiltrer();
    });

    // Exécuter le filtrage quand on change la catégorie
    $("#category").on("change", function() {
        rechercherFiltrer();
    });

    // Exécuter la recherche quand on clique sur le bouton de recherche
    $(".search-button").click(function() {
        rechercherFiltrer();
    });

    // Exécuter le filtrage quand on clique sur le bouton de filtrage
    $(".filter-button").click(function() {
        rechercherFiltrer();
    });
});



document.getElementById("logoutButton").addEventListener("click", function() {
    window.location.href = "logout.php"; 
});


</script>
</body>
</html>