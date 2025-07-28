<div class="container">
       <h2>Mon Profil</h2>
       <?php
       // Display messages and errors from session
       if (isset($message) && $message != '') {
           echo "<p class='message'>" . htmlspecialchars($message) . "</p>";
       }
       if (isset($error) && $error != '') {
           echo "<p class='error'>" . htmlspecialchars($error) . "</p>";
       }
       ?>
       <style>
           /* Styles for the profile form */
           .container form {
               display: flex;
               flex-direction: column;
               gap: 15px; /* Space between form elements */
               max-width: 500px; /* Limit form width */
               margin: 20px auto; /* Center the form */
               padding: 25px;
               border: 1px solid #ddd;
               border-radius: 8px;
               background-color: #f9f9f9;
               box-shadow: 0 2px 10px rgba(0,0,0,0.05);
           }

           .container form label {
               font-weight: bold;
               color: #555;
               margin-bottom: 5px;
           }

           .container form input[type="text"],
           .container form input[type="email"],
           .container form input[type="password"],
           .container form input[type="file"] {
               width: calc(100% - 20px); /* Adjust for padding */
               padding: 10px;
               border: 1px solid #ccc;
               border-radius: 4px;
               font-size: 1em;
               margin-bottom: 10px; /* Space after input */
           }

           .container form img {
               display: block; /* Make image a block element */
               margin: 10px 0 20px 0; /* Space around image */
               border: 1px solid #eee;
           }

           .container form button[type="submit"] {
               background-color: #039f60;
               color: white;
               padding: 12px 20px;
               border: none;
               border-radius: 4px;
               cursor: pointer;
               font-size: 1.1em;
               transition: background-color 0.3s ease;
               margin-top: 20px; /* Space before button */
           }

           .container form button[type="submit"]:hover {
               background-color: #027a4b;
           }

           /* Responsive adjustments */
           @media (max-width: 768px) {
               .container form {
                   padding: 15px;
                   margin: 15px;
               }
           }
       </style>
       <!-- Form for updating user profile -->
       <form method="POST" action="index.php?page=update_profile" enctype="multipart/form-data">
           <label for="matricule">Matricule:</label>
           <input type="text" name="matricule" id="matricule" value="<?php echo htmlspecialchars($user["matricule"]); ?>" required>

           <label for="nom">Nom:</label>
           <input type="text" name="nom" id="nom" value="<?php echo htmlspecialchars($user["nom"]); ?>" required>

           <label for="prenom">Prénom:</label>
           <input type="text" name="prenom" id="prenom" value="<?php echo htmlspecialchars($user["prenom"]); ?>" required>

           <label for="email">Email:</label>
           <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($user["email"]); ?>" required>

           <label for="telephone">Téléphone:</label>
           <input type="text" name="telephone" id="telephone" value="<?php echo htmlspecialchars($user["telephone"]); ?>" required>

           <label for="formation">Formation:</label>
           <input type="text" name="formation" id="formation" value="<?php echo htmlspecialchars($user["formation"]); ?>" required>

           <label for="image">Image de Profil:</label>
           <?php if (!empty($user["image"])): ?>
               <img src="public/assets/images/profiles/<?php echo htmlspecialchars($user["image"]); ?>" alt="Profile Image" style="width: 100px; height: 100px; object-fit: cover; border-radius: 50%;">
           <?php endif; ?>
           <input type="file" name="image" id="image" accept="image/*">

           <label for="password">Nouveau Mot de Passe:</label>
           <input type="password" name="password" id="password">

           <label for="confirm_password">Confirmer le Mot de Passe:</label>
           <input type="password" name="confirm_password" id="confirm_password">

           <button type="submit">Mettre à Jour</button>
       </form>
   </div>
   