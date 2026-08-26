<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Carregando...</title>
</head>
<body>

<p>Carregando...</p>

<script>
const savedCart = localStorage.getItem("shopping_cart");

if (savedCart) {

    fetch("./includes/restore-cart.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: savedCart
    })
    .then(response => response.json())
    .then(() => {
        window.location.href = "./index.php";
    });

} else {

    window.location.href = "./index.php";

}
</script>

</body>
</html>