<?php
// export_csv.php — Export ingrédients en CSV
require_once __DIR__ . '/../../controller/IngredientController.php';

$ctrl        = new IngredientController();
$ingredients = $ctrl->listIngredients();

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="smartfood_ingredients_' . date('Y-m-d') . '.csv"');
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

$out = fopen('php://output', 'w');
fputs($out, "\xEF\xBB\xBF"); // BOM UTF-8 pour Excel

fputcsv($out, ['ID', 'Nom', 'Quantité', 'Unité'], ';');

foreach ($ingredients as $ing) {
    fputcsv($out, [
        $ing['idIngredient'],
        $ing['nom'],
        $ing['quantite'],
        $ing['unite']
    ], ';');
}

fclose($out);
exit;
