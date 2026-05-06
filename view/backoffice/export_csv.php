<?php
// view/backoffice/export_csv.php — Export all ingredients as a downloadable CSV

require_once __DIR__ . '/../../controller/IngredientController.php';

$ctrl = new IngredientController();
$ingredients = $ctrl->listIngredients();

// Force download headers BEFORE any output
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="smartfood_ingredients_' . date('Y-m-d') . '.csv"');
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

$out = fopen('php://output', 'w');

// UTF-8 BOM so Excel opens it correctly
fputs($out, "\xEF\xBB\xBF");

// Column headers
fputcsv($out, [
    'ID',
    'Nom',
    'Calories (kcal/100g)',
    'Proteines (g/100g)',
    'Glucides (g/100g)',
    'Lipides (g/100g)'
], ';');

// Data rows
foreach ($ingredients as $ing) {
    fputcsv($out, [
        $ing['idIngredient'],
        $ing['nom'],
        number_format((float)$ing['calories'],  2, '.', ''),
        number_format((float)$ing['proteines'], 2, '.', ''),
        number_format((float)$ing['glucides'],  2, '.', ''),
        number_format((float)$ing['lipides'],   2, '.', '')
    ], ';');
}

fclose($out);
exit;
