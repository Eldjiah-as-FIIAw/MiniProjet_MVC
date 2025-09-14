<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <fieldset>
        <legend>Insertion des Données</legend>
        <form method="POST">
            <label for=""></label>
            <input type="text" placeholder="nom" name="nom">
            <br>
            <label for=""></label>
            <input type="text" name="prenom">
            <br>
            <label for=""></label>
            <input type="text" name="age">
            <br>
            <button type="submit">Ajouter</button>
        </form>
    </fieldset>
    <table border ="2">
        <thead>
            <tr>
                <td>Id</td>
                <td>Nom</td>
                <td>Prenom</td>
                <td>Age</td>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($etudiant as $et): ?>
    
                <tr>
                <td><?= $et['id']; ?></td>
                <td><?= $et['nom']; ?></td>
                <td><?= $et['prenom']; ?></td>
                <td><?= $et['age']; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>