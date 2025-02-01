<?php
session_start();

if (isset($_SESSION["panier"])) {
  $panier = $_SESSION["panier"];
  $total = 0;
  echo "<table border='1'>";
  echo "<tr><th>Photo</th><th>Nom</th><th>Prix</th><th>Quantité</th><th>Total</th><th>Actions</th></tr>";
  foreach ($panier as $id => $article) {
    if (is_array($article)) {
      $total += $article["prix"] * $article["quantite"];
      echo "<tr>";
      echo "<td><img src='" . $article["photo"] . "' width='50'></td>";
      echo "<td>" . $article["nom"] . "</td>";
      echo "<td>" . $article["prix"] . " fcfa</td>";
      echo "<td>" . $article["quantite"] . "</td>";
      echo "<td>" . $article["prix"] * $article["quantite"] . " €</td>";
      echo "<td><button onclick='supprimerDuPanier(" . $id . ")'>Supprimer</button></td>";
      echo "</tr>";
    } else {
      echo "<tr>";
      echo "<td><img src='" . $article . "' width='50'></td>";
      echo "<td></td>";
      echo "<td></td>";
      echo "<td></td>";
      echo "<td></td>";
      echo "<td><button onclick='supprimerDuPanier(" . $id . ")'>Supprimer</button></td>";
      echo "</tr>";
    }
  }

  echo "<tr><td colspan='5'>Total :</td><td>" . $total . " fcfa</td></tr>";
  echo "</table>";
  echo "<p><button onclick='commander()'>Commander</button></p>";

} else {
  echo "Votre panier est vide.";
}


  
?>
