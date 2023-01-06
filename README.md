======== INSTRUCTIONS ========

Réaliser un formulaire d'avis (de type avis sur fiche produit sur un site e-commerce),
ainsi que le listing des avis postés.

Le formulaire doit contenir au minimum les champs suivants :

email
pseudo
note
commentaire (avec possibilité de mise en forme)
upload de photo

La liste des avis doit être triable par date ou par note.
Elle doit également être filtrable par note.

======== ÉTAPES ========

1.Créer des entités "Product" et "Review" ✅

Champs de l'entité Product :
-id (pk) ✅
-name (required) ✅
-reviews (relation One to Many with Review) ✅
-picture ✅
-rating (auto calculated) ✅

Champs de l'entité Review :
-id (pk) ✅
-product (relation Many to One with Product) ✅
-user_email (required) ✅
-pseudo (required) ✅
-user_rating (required) ✅
-comment (required) ✅
-picture ✅
-date_time (auto generated) ✅

2.Affichage

Mettre en forme une page produit-avis, qui devra comporter :
=== Affichage du produit ===
-nom ✅
-photo ✅
-note (créer méthode pour calculer la note en récupérant toutes les notes des avis) ✅

=== Liste des avis ===
-pseudo ✅
-note ✅
-commentaire ✅
-image ✅
-date ✅

=== Formulaire d'ajout d'avis ===
-email ✅
-pseudo ✅
-note ✅
-commentaire (avec possibilité de mise en forme)
-upload de photo ✅
(optionnel : si e-mail identique trouvée, proposer de remplacer l'ancien avis par le nouveau)

3.Styling

Rendre le tout agréable à l'oeil et à l'utilisation ✅
