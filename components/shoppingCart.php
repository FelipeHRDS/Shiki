<?php

if (isset($_SESSION['shopping_cart']) && !empty($_SESSION['shopping_cart'])) {
    $shoppingCart = '<div id="table-container"></div><label for="additional-details">Detalhes adicionais (opcional):</label><form style="display: flex; justify-content: center; flex-flow: column nowrap" method="post" action="pdf-template.php"><textarea id="additional-details" name="additional-details" rows="3" columns="20"></textarea>';
    
    if (isset($preco_total_pedido) && ($preco_total_pedido >= 800.00 || ($_SESSION['usersClass'] == "matsuri" && $preco_total_pedido >= 690.00))) {
        $shoppingCart .= '<input type="submit" class="shopping-cart-button" value="Ver prévia do pedido">';
    } else {
        $shoppingCart .= '<p style="text-align: center; color: red;">O pedido não atinge o valor mínimo de R$800,00.</p>';
    }
     $shoppingCart .= '</form>';
} else {
    $shoppingCart = '<div style="display: flex; flex-flow: column nowrap; background-color: #eee;"><p class="empty-cart__alert">Você ainda não tem itens no seu carrinho de compras.</p><a class="shopping-cart-button" href="meus-produtos.php">Conferir produtos disponíveis</a></div>';
}

$shoppingCartScript = '
            function createTable() {
            var table = document.createElement("table");
        
            var headerRow = table.insertRow(0);
            var headers = ["Código Interno", "Referência", "Nome", "Quantidade", "Preço Unitário", "Preço Total", "Ação"];
            for (var header of headers) {
                var th = document.createElement("th");
                th.textContent = header;
                headerRow.appendChild(th);
            }
            
            for (var i = 0; i < shoppingCart.length; i++) {
                    var row = table.insertRow(i + 1);
                    for (var key in shoppingCart[i]) {
                        if (key != "unidade") {
                            if (shoppingCart[i].hasOwnProperty(key)) {
                                var cell = row.insertCell();
                                cell.textContent = shoppingCart[i][key];
                            }
                        }
                    }
            
                    let quantityCell = row.cells[3];
                    quantityCell.setAttribute("contenteditable", "true");
                    quantityCell.addEventListener("focusout", updateQuantity);
                    quantityCell.addEventListener("keydown", function(event) {
                        if (event.key == "Enter") {
                            event.preventDefault();
                            this.blur();
                        } else if (event.key == "ArrowUp") {
                            this.textContent = parseInt(this.textContent) + 1;
                            updateQuantity();
                        } else if (event.key == "ArrowDown") {
                            this.textContent = parseInt(this.textContent) - 1;
                            updateQuantity();
                        }
                    });
                    
                    var actionCell = row.insertCell();
                    var actionSubmit = document.createElement("input");
                    var actionSubmitForm = document.createElement("form");
                    actionSubmitForm.setAttribute("action", "./includes/remove_item.inc.php");
                    actionSubmitForm.setAttribute("method", "get");
                    actionSubmit.setAttribute("type", "hidden");
                    actionSubmit.setAttribute("name", "actionSubmit");
                    actionSubmit.classList.add("actionSubmit");
                    actionCell.appendChild(actionSubmitForm);
                    actionSubmitForm.appendChild(actionSubmit);
                    const actionIcon = document.createElement("button");
                    actionCell.setAttribute("style", "text-align: center; vertical-align: middle; padding: 0;");
                    const actionIconImage = document.createElement("img");
                    actionIconImage.src = "./media/trash-can-icon.png";
                    actionIcon.appendChild(actionIconImage);
                    actionIcon.classList.add("delete-item-button");
                    actionIcon.addEventListener("click", deleteRow);
                    actionSubmitForm.appendChild(actionIcon);
                }
        
            var totalRow = table.insertRow(shoppingCart.length + 1);
            var totalCell = totalRow.insertCell();
            var totalValueCell = totalRow.insertCell();
            totalCell.setAttribute("colspan", 5);
            totalCell.textContent = "Total";
        
            var total = 0;
            for (var i = 0; i < shoppingCart.length; i++) {
                var lastCell = shoppingCart[i]["preco total"];
                total += parseFloat(lastCell);
            }
            totalValueCell.textContent = total.toFixed(2);
            
            let tableContainer = document.getElementById("table-container");
            if (tableContainer) {
                tableContainer.appendChild(table);
            }
        
            document.querySelectorAll(".actionSubmit").forEach((actionSubmitButton) => {
                actionSubmitButton.value = actionSubmitButton.parentNode.parentNode.parentNode.rowIndex - 1;
            })
        
            var additionalDetailsTextarea = document.getElementById("additional-details");

            if (document.querySelector("form")) {
                document.querySelector("form").addEventListener("submit", function (event) {
                    additionalDetailsTextarea.value = additionalDetailsTextarea.value.replace(/\n/g, "<br>");
                });
            }
        
            document.querySelectorAll(".actionSubmit").forEach((actionSubmitButton) => {
                actionSubmitButton.value = actionSubmitButton.parentNode.parentNode.parentNode.rowIndex - 1;
            });
        
            function updateQuantity(event) {
                var input = event.target;
                var quantity = parseInt(input.textContent);
                if (isNaN(quantity) || quantity <= 0) {
                    quantity = 1;
                }
                input.textContent = quantity;
                updateTotalPrice();
            }
        
            function updateTotalPrice() {
            debugger;
            var total = 0;
            var rows = table.rows;
            for (var i = 1; i < rows.length - 1; i++) {
                var quantityCell = rows[i].cells[3];
                var priceCell = rows[i].cells[4];
                var totalPriceCell = rows[i].cells[5];
                var quantity = parseFloat(quantityCell.textContent);
                var price = parseFloat(priceCell.textContent);
                var totalPrice = quantity * price;
                total += totalPrice;
                totalPriceCell.textContent = totalPrice.toFixed(2);
        
                // Get the actual index in the shoppingCart array (adjusting for header rows)
                var rowIndex = i - 1;
        
                // Update the corresponding item in the shoppingCart array
                shoppingCart[rowIndex]["preco total"] = totalPrice.toFixed(2);
                shoppingCart[rowIndex]["quantidade"] = quantity;
            }

            localStorage.setItem(
                "shopping_cart",
                JSON.stringify(shoppingCart)
            );
        
            totalValueCell.textContent = total.toFixed(2);
        
            // Trigger the AJAX request to update the session variable
            updateSessionShoppingCart();
        }
            
            async function updateSessionShoppingCart() {
                try {
                    const response = await fetch("./includes/update_item_quantity_on_cart.inc.php", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify(shoppingCart)
                    });
            
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }

                    const data = await response.json();
                    location.reload();
                } catch (error) {
                    console.error("Error updating shopping cart:", error);
                }
            }


        }
        
        async function deleteRow(event) {

            event.preventDefault();

            const form = this.parentNode;
            const index = form.querySelector(".actionSubmit").value;

            try {

                const response = await fetch(
                    "./includes/remove_item.inc.php?actionSubmit=" + index
                );

                const data = await response.json();

                if (data.success) {

                    shoppingCart = data.shopping_cart;

                    localStorage.setItem(
                        "shopping_cart",
                        JSON.stringify(shoppingCart)
                    );

                    location.reload();
                }

            } catch (error) {

                console.error(
                    "Erro ao remover produto:",
                    error
                );

            }
        }
        </script>';