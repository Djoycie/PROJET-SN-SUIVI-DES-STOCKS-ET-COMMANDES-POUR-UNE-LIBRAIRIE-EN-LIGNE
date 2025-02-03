<?php
session_start();

if (isset($_SESSION["panier"])) {
  $panier = $_SESSION["panier"];
  $total = 0;
  echo "<table border='1' style='border-collapse:collapse;  width:670px;'>";
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
      echo "<td><button style='background-color:red; text-align:center; margin-left:30px;' onclick='supprimerDuPanier(" . $id . ")'>Supprimer</button></td>";
      echo "</tr>";
    } else {
      echo "<tr>";
      echo "<td><img src='" . $article . "' width='50'></td>";
      echo "<td></td>";
      echo "<td></td>";
      echo "<td></td>";
      echo "<td></td>";
      echo "<td   ><button  style='background-color:red; margin-left:30px;' onclick='supprimerDuPanier(" . $id . ")'>Supprimer</button></td>";
      echo "</tr>";
    }
  }

  echo "<tr><td colspan='5'>Total :</td><td>" . $total . " fcfa</td></tr>";
  echo "</table>";
  echo "<form action='passer_commande.php' method='POST'>
          <button style='background-color:#3498db; margin-left:280px; cursor:pointer;' type='submit'>Commander</button>
        </form>";


} else {
  echo "Votre panier est vide.";
}


  
?>
