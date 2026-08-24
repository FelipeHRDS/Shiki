<?php

include './includes/Formatter.php';

session_start();
include_once './includes/session_variables.inc.php';

$formatted_cnpj = Formatter::formatCnpj($userCnpj);
$formattedUserSystemCode = Formatter::formatUserSystemCode($userSystemCode);

$shoppingCart = $_SESSION["shopping_cart"];

date_default_timezone_set('America/Sao_Paulo'); 
$purchaseDate = date('d/m/y');

$additionalDetails = htmlspecialchars($_POST['additional-details']);

function getAndUpdateCounter() {
    $counterFile = './includes/global-counter.txt';
    $counter = (int)file_get_contents($counterFile);
    $counter++;
    file_put_contents($counterFile, str_pad($counter, 6, '0', STR_PAD_LEFT));
    $_SESSION['counter'] = $counter;
    return $counter;
}

$purchaseCode = str_pad(getAndUpdateCounter(), 6, '0', STR_PAD_LEFT);

// if the user has nothing in the shopping cart, redirect
// removing this could lead to an empty purchase to be generated
if (empty($shoppingCart)) {
    header("location: ./shopping-cart.php");
}
?>

<!DOCTYPE html>
<html>
	<head>
	    <meta charset="UTF-8">
		<title>PDF Template</title>
		<link rel="stylesheet" href="./pdf-template.css">
		<link rel="stylesheet" href="https://unpkg.com/gutenberg-css@0.7">
		<link rel="stylesheet" href="https://unpkg.com/gutenberg-css@0.7/dist/themes/oldstyle.min.css">
		<link rel="apple-touch-icon" sizes="180x180" href="./favicon/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="./favicon/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="./favicon/favicon-16x16.png">
        <link rel="manifest" href="./favicon/site.webmanifest">
	</head>

	<body style="display: flex; flex-flow: column nowrap;">
	    <div id="header-alert"><p style="margin-top: 4rem">Seu pedido foi gerado com sucesso! Confira as informações abaixo e clique em "enviar pedido" para confirmá-lo.</p></div>

	    <div id="send-this">
    	    <img src="./media/shiki-logo.jpg" style="position: absolute; top: 1rem; left: 1rem; width: 150px;">
    		<div id="header-container"></div>
    		<div id="table-container"></div>
    		<div id="additional-details"></div>
		</div>

		<form action="./generate-pdf.php" method="post" style="display: flex; justify-content: center;" id="submitHtmlForm">
		    <input type="hidden" id="submit-html" name="submit-html" value="">
            <div class="choosedelivery__wrapper">
                <?php if ($userDispensaTransp != "1"): ?>
                    <label for="choosedelivery">Escolha uma transportadora:</label>
                    <input type="text" name="choosedelivery" id="choosedelivery" value="A decidir">
                <?php else: ?>

                    <?php if($userClass === "parada10"): ?>
                        <p style="margin:0; font-size: 1.2rem;">O prazo para entrega será de <b>7 dias úteis</b>.</p>
                        <input type="hidden" id="choosedelivery" name="choosedelivery" value="KBK">

                    <?php elseif($userUid === "mirairdt"): ?>
                        <input type="hidden" id="choosedelivery" name="choosedelivery" value="Transportadora TAF">
                    
                    <?php elseif($userUid === "atctsuki"): ?>
                        <input type="hidden" id="choosedelivery" name="choosedelivery" value="Transmarinho">

                    <?php elseif($userClass === "restki"): ?>
                        <input type="hidden" id="choosedelivery" name="choosedelivery" value="Grativol">

                    <?php elseif($userClass === 'sushiaki'): ?>
                        <input type="hidden" id="choosedelivery" name="choosedelivery" value="Minuano">

                    <?php elseif($userUid === 'kizuna'): ?>
                        <input type="hidden" id="choosedelivery" name="choosedelivery" value="Trans Apucarana">

                    <?php elseif($userCnpj === "30441181000152" || $userCnpj === "08866603000182"): ?>
                        <input type="hidden" id="choosedelivery" name="choosedelivery" value="Almeida Fante - TAF">

                    <?php else: ?>
                        <input type="hidden" id="choosedelivery" name="choosedelivery" value="Nenhuma">

                    <?php endif; ?>

                <?php endif; ?>
            </div>
		</form>

		<button onclick="finishPurchase()" id="submit-html-button">
            <img src="./media/email-icon.png" style="width: 1.5rem; display: inline; margin-right: 0.5rem;">
            <p>Enviar Pedido</p>
        </button>
		
        <script>
            function generateTable(shoppingCartObject) {
                const table = document.createElement("table");
                table.setAttribute("style", "page-break-before: avoid;");
                table.classList.add("greyGridTable");

                const headerRow = table.insertRow(0);
                const headers = ["Código Interno", "Referência", "Nome", "Quantidade", "Preço Unitário", "Preço Total"];
                for (let header of headers) {
                        const th = document.createElement("th");
                        th.textContent = header;
                        headerRow.appendChild(th);
                }

                for (let i = 0; i < shoppingCartObject.length; i++) {
                        const row = table.insertRow(i + 1);
                        for (let key in shoppingCartObject[i]) {
                            if (key != "unidade") {
                                if (shoppingCartObject[i].hasOwnProperty(key)) {
                                    const cell = row.insertCell();
                                    if (key != "quantidade") {
                                        cell.textContent = shoppingCartObject[i][key];
                                    } else {
                                        cell.textContent = shoppingCartObject[i][key] + " " + shoppingCartObject[i]["unidade"];
                                    }
                                }
                            }
                        }
                    }
                return table;
            }

            const shoppingCart = <?= json_encode($shoppingCart); ?>;
            const table = generateTable(shoppingCart);
            
            document.addEventListener('DOMContentLoaded', () => {
                <?php if ($userRepNo !== 67 && $userUid !== 'atcjphouse' && $userCnpj !== '01291503000126'): ?>
                    <?php if ($userClass === "parada10"): ?>
                        var freteRow = table.insertRow(shoppingCart.length + 1);
                        var freteCell = freteRow.insertCell();
                        var freteValueCell = freteRow.insertCell();
                        freteCell.setAttribute("colspan", 5);
                        freteCell.textContent = "FRETE";
                        freteCell.style.textAlign = "center";
                        freteCell.style.color = "green";
                        var freteAmount = 30.00;
                        freteValueCell.textContent = freteAmount.toFixed(2);
                        freteValueCell.style.color = "green";
                    <?php endif; ?>
                    
                    var totalRow = table.insertRow(-1);
                    var totalCell = totalRow.insertCell();
                    var totalValueCell = totalRow.insertCell();
                    totalCell.setAttribute("colspan", 5);
                    totalCell.textContent = "Total";
                    var total = 0;
                    <?php for ($i = 0; $i < count($shoppingCart); $i++): ?>
                        <?php $lastCell = $shoppingCart[$i]["preco total"]; ?>
                        total += parseFloat(<?= json_encode($lastCell); ?>);
                    <?php endfor; ?>
                    
                    <?php if ($userClass === "parada10"): ?>
                        total += freteAmount;
                    <?php endif; ?>
                    
                    totalValueCell.textContent = total.toFixed(2);

                <?php else: ?>
                    var totalParcialRow = table.insertRow(-1);
                    var totalParcialCell = totalParcialRow.insertCell();
                    var totalParcialValueCell = totalParcialRow.insertCell();
                    totalParcialCell.setAttribute("colspan", 5);
                    totalParcialCell.textContent = "Total Parcial";
                    var totalParcial = 0;
                    <?php for ($i = 0; $i < count($shoppingCart); $i++): ?>
                        <?php $lastCell = $shoppingCart[$i]["preco total"]; ?>
                        totalParcial += parseFloat(<?= json_encode($lastCell); ?>);
                    <?php endfor; ?>
                    totalParcialValueCell.textContent = totalParcial.toFixed(2);
                    totalParcialValueCell.classList.add("old-price");
                    // Insert row for Total com Desconto
                    var totalRow = table.insertRow(-1);
                    var totalCell = totalRow.insertCell();
                    var totalValueCell = totalRow.insertCell();
                    totalCell.setAttribute("colspan", 5);
                    
                    <?php if ($userRepNo == 67): ?>
                        totalCell.textContent = "Total com Desconto (10%)";
                        var discountAmount = totalParcial * 0.1;
                    <?php else: ?>
                        totalCell.textContent = "Total com Desconto (5%)";
                        var discountAmount = totalParcial * 0.05;
                    <?php endif; ?>
                    
                    let total = totalParcial;
                    total -= discountAmount;
                    totalValueCell.textContent = total.toFixed(2);
                    totalValueCell.classList.add("new-price");
                <?php endif; ?>
            });

            const headerContainer = document.getElementById('header-container');
            headerContainer.innerHTML = "<?php if ($userType !== "rep") {
            echo '<table><tbody><tr><td>Usuário: ' . $userName . ' (' . $formattedUserSystemCode . ')</td><td>Número do pedido: ' . $purchaseCode . '</td><td>CNPJ: ' . $formatted_cnpj . '</td><tr><td>Data: ' . $purchaseDate . '</td><td>UF: ' . $userUf . '</td><td>Cidade: ' . $userCity . '</td></tr></tbody></table>';
            } else {
                echo '<table><tbody><tr><td>Cliente: ' . $_SESSION['userReplacementFormalName'] . ' (' . $_SESSION['userReplacementSystemCode'] . ')</td><td>Número do pedido: ' . $purchaseCode . '</td><td>CNPJ: ' . Formatter::formatCnpj($_SESSION['userReplacementCnpj']) . '</td><tr><td>Data: ' . $purchaseDate . '</td><td>UF: ' . $_SESSION['userReplacementUF'] . '</td><td>Cidade: ' . $_SESSION['userReplacementCity'] . '</td></tr></tbody></table>';
            }
            ?>";
            
            document.getElementById("table-container").appendChild(table);
            
            document.getElementById("additional-details").textContent = "Detalhes adicionais: " + <?php echo json_encode($additionalDetails, JSON_UNESCAPED_UNICODE); ?>;

                function cloneTableWithoutLastTwoColumns(originalTable) {
                let newTable = document.createElement("table");
        
                let headerRow = originalTable.rows[0].cloneNode(true);
        
                headerRow.deleteCell(-1);
                headerRow.deleteCell(-1);
        
                newTable.appendChild(headerRow);
        
                for (let i = 1; i < originalTable.rows.length; i++) {
                    let originalRow = originalTable.rows[i];
                    let newRow = originalRow.cloneNode(true);
        
                    newRow.deleteCell(-1);
                    newRow.deleteCell(-1);
        
                    newTable.appendChild(newRow);
                }
        
                return newTable.outerHTML;
            }
            
            let myTable = document.querySelector(".greyGridTable");
            let newTableHTML = cloneTableWithoutLastTwoColumns(myTable);

            function generatePdfContent() {
                let pdfContent = '';

                pdfContent += document.getElementById('send-this').outerHTML;
                
                let selectedDeliveryService = document.getElementById('choosedelivery').value.trim() || "A decidir";
                pdfContent += `<p style='font-weight: bold;'>Transportadora escolhida: ${selectedDeliveryService}</p>`;
                
                pdfContent += "<div style='page-break-before: avoid; page-break-inside: avoid; page-break-before: always;'></div>";
                
                pdfContent += headerContainer.innerHTML;

                pdfContent += '<img src="./media/shiki-logo.jpg" style="position: absolute; top: 1rem; left: 1rem; width: 150px;">';
                
                pdfContent += newTableHTML;

                pdfContent += "Detalhes adicionais: " + <?php echo json_encode($additionalDetails, JSON_UNESCAPED_UNICODE); ?>;

                pdfContent += `<p style='font-weight: bold;'>Transportadora escolhida: ${selectedDeliveryService}</p>`;

                return pdfContent;
            }

            

            function finishPurchase() {
                document.getElementById("submit-html-button").innerText = "Enviando...";
                document.getElementById("submit-html-button").disabled = true;
                
                document.getElementById("submit-html").value = generatePdfContent();
                document.getElementById("submitHtmlForm").submit();
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['finalizar_compra'])) {
                finishPurchase();
            }

        </script>

	</body>
</html>