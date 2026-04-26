/**
 * metier.js — Fonctionnalités Métier pour SmartFood
 * 
 * Contient :
 *   1. Recherche dynamique (Back Office)
 *   2. Tri de tableau par colonnes (Back Office)
 *   3. Graphiques de progression (Front Office — Chart.js)
 *   4. Suggestion intelligente de repas (Front Office)
 */

// ============================================================
// 1. RECHERCHE DYNAMIQUE (Back Office)
// ============================================================

function initSearch() {
    var searchInput = document.getElementById('search-ingredient');
    if (!searchInput) return;

    searchInput.addEventListener('input', function() {
        var query = this.value.toLowerCase().trim();
        var table = document.querySelector('.smart-table');
        if (!table) return;

        var rows = table.querySelectorAll('tbody tr');
        var visibleCount = 0;

        rows.forEach(function(row) {
            // Skip the no-results message row
            if (row.id === 'no-search-results') return;
            // Search across all visible text in the row
            var text = row.textContent.toLowerCase();
            var match = text.indexOf(query) !== -1;
            row.style.display = match ? '' : 'none';
            if (match) visibleCount++;
        });

        // Update the badge count
        var badge = document.querySelector('.badge-status');
        if (badge) {
            badge.textContent = 'Total: ' + visibleCount + ' Ingrédients';
        }

        // Show "no results" message
        var noResults = document.getElementById('no-search-results');
        if (noResults) {
            noResults.style.display = (visibleCount === 0 && query) ? '' : 'none';
        }
    });
}

// ============================================================
// 2. TRI DE TABLEAU (Back Office)
// ============================================================

var sortDirections = {};

function initSort() {
    var headers = document.querySelectorAll('.smart-table th[data-sort]');
    if (!headers.length) return;

    headers.forEach(function(th) {
        th.style.cursor = 'pointer';
        th.style.userSelect = 'none';
        th.addEventListener('click', function() {
            var column = this.getAttribute('data-sort');
            var colIndex = parseInt(this.getAttribute('data-col'));
            var isNumeric = this.getAttribute('data-type') === 'number';

            // Toggle direction
            if (!sortDirections[column]) sortDirections[column] = 'asc';
            else sortDirections[column] = sortDirections[column] === 'asc' ? 'desc' : 'asc';
            var dir = sortDirections[column];

            // Reset all arrows
            headers.forEach(function(h) {
                h.classList.remove('sort-asc', 'sort-desc');
            });
            this.classList.add(dir === 'asc' ? 'sort-asc' : 'sort-desc');

            // Sort
            var tbody = document.querySelector('.smart-table tbody');
            var allRows = Array.from(tbody.querySelectorAll('tr'));
            
            // Filter to only sortable data rows (exclude no-results message etc.)
            var dataRows = allRows.filter(function(row) {
                return row.cells.length > 1 && !row.id.startsWith('no-');
            });

            dataRows.sort(function(a, b) {
                var aText = a.cells[colIndex].textContent.trim();
                var bText = b.cells[colIndex].textContent.trim();

                if (isNumeric) {
                    var aVal = parseFloat(aText.replace(/[^0-9.\-]/g, '')) || 0;
                    var bVal = parseFloat(bText.replace(/[^0-9.\-]/g, '')) || 0;
                    return dir === 'asc' ? aVal - bVal : bVal - aVal;
                } else {
                    aText = aText.toLowerCase();
                    bText = bText.toLowerCase();
                    if (aText < bText) return dir === 'asc' ? -1 : 1;
                    if (aText > bText) return dir === 'asc' ? 1 : -1;
                    return 0;
                }
            });

            dataRows.forEach(function(row) { tbody.appendChild(row); });
        });
    });
}

// ============================================================
// 3. GRAPHIQUES DE PROGRESSION (Front Office — Chart.js)
// ============================================================

var progressCharts = {};

/**
 * Determine the color based on percentage of goal reached
 * Green → Orange → Dark Red
 */
function getProgressColor(percent) {
    if (percent <= 70) {
        // Green zone — safe
        return {
            main: 'rgba(45, 106, 79, 0.85)',
            bg: 'rgba(45, 106, 79, 0.1)',
            glow: 'rgba(45, 106, 79, 0.3)'
        };
    } else if (percent <= 100) {
        // Orange zone — approaching limit
        return {
            main: 'rgba(255, 140, 0, 0.9)',
            bg: 'rgba(255, 140, 0, 0.1)',
            glow: 'rgba(255, 140, 0, 0.3)'
        };
    } else {
        // DANGER — exceeded
        return {
            main: 'rgba(139, 0, 0, 0.95)',
            bg: 'rgba(139, 0, 0, 0.08)',
            glow: 'rgba(139, 0, 0, 0.4)'
        };
    }
}

/**
 * Initialize or update a single donut chart
 */
function updateDonutChart(canvasId, current, goal, label) {
    var canvas = document.getElementById(canvasId);
    if (!canvas || typeof Chart === 'undefined') return;

    var percent = goal > 0 ? Math.round((current / goal) * 100) : 0;
    var displayPercent = Math.min(percent, 100);
    var remaining = Math.max(goal - current, 0);
    var exceeded = Math.max(current - goal, 0);
    var colors = getProgressColor(percent);

    // Update the percentage text
    var percentEl = document.getElementById(canvasId + '-percent');
    if (percentEl) {
        percentEl.textContent = percent + '%';
        percentEl.style.color = colors.main;
    }

    // Update detail text
    var detailEl = document.getElementById(canvasId + '-detail');
    if (detailEl) {
        if (percent > 100) {
            detailEl.textContent = '+' + Math.round(exceeded * 10) / 10 + ' en excès !';
            detailEl.style.color = colors.main;
        } else {
            detailEl.textContent = Math.round(remaining * 10) / 10 + ' restant';
            detailEl.style.color = '#888';
        }
    }

    // Animate the card background on danger
    var card = canvas.closest('.progress-chart-card');
    if (card) {
        if (percent > 100) {
            card.classList.add('danger-zone');
            card.classList.remove('warning-zone', 'safe-zone');
        } else if (percent > 70) {
            card.classList.add('warning-zone');
            card.classList.remove('danger-zone', 'safe-zone');
        } else {
            card.classList.add('safe-zone');
            card.classList.remove('danger-zone', 'warning-zone');
        }
    }

    // Chart data
    var data;
    if (percent > 100) {
        data = {
            datasets: [{
                data: [goal, exceeded],
                backgroundColor: [colors.main, 'rgba(139, 0, 0, 0.4)'],
                borderWidth: 0,
                borderRadius: 6
            }]
        };
    } else {
        data = {
            datasets: [{
                data: [current, remaining],
                backgroundColor: [colors.main, colors.bg],
                borderWidth: 0,
                borderRadius: 6
            }]
        };
    }

    if (progressCharts[canvasId]) {
        progressCharts[canvasId].data = data;
        progressCharts[canvasId].update('none');
    } else {
        progressCharts[canvasId] = new Chart(canvas.getContext('2d'), {
            type: 'doughnut',
            data: data,
            options: {
                cutout: '75%',
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { display: false },
                    tooltip: { enabled: false }
                },
                animation: {
                    animateRotate: true,
                    duration: 600,
                    easing: 'easeOutQuart'
                }
            }
        });
    }
}

/**
 * Update all 4 donut charts with current totals and goals
 */
function updateAllCharts(totals, goals) {
    if (!goals || !goals.cals) return; // Goals not set yet

    updateDonutChart('chart-cals', totals.cals, goals.cals, 'Calories');
    updateDonutChart('chart-prot', totals.prot, goals.prot, 'Protéines');
    updateDonutChart('chart-gluc', totals.gluc, goals.gluc, 'Glucides');
    updateDonutChart('chart-lip', totals.lip, goals.lip, 'Lipides');
}

// ============================================================
// 4. SUGGESTION INTELLIGENTE (Front Office)
// ============================================================

/**
 * Analyze remaining macros and suggest the best ingredient
 * @param {Array} ingredientsDB - Array of ingredient objects from the <select>
 * @param {Object} totals - Current totals { cals, prot, gluc, lip }
 * @param {Object} goals - User goals { cals, prot, gluc, lip }
 */
function updateSuggestion(ingredientsDB, totals, goals) {
    var container = document.getElementById('suggestion-container');
    if (!container || !goals || !goals.cals) return;

    var remainingCals = goals.cals - totals.cals;
    var remainingProt = goals.prot - totals.prot;
    var remainingGluc = goals.gluc - totals.gluc;
    var remainingLip = goals.lip - totals.lip;

    // If all goals are exceeded, show a warning
    if (remainingCals <= 0 && remainingProt <= 0) {
        container.innerHTML = '<div class="suggestion-card suggestion-warning">' +
            '<span class="suggestion-icon">🚫</span>' +
            '<div class="suggestion-text">' +
            '<strong>Objectif atteint !</strong>' +
            '<p>Vous avez atteint vos objectifs journaliers. Pas besoin d\'ajouter plus d\'aliments.</p>' +
            '</div></div>';
        return;
    }

    // Find the most deficient macro
    var maxDeficit = 'prot';
    var deficits = {
        prot: goals.prot > 0 ? remainingProt / goals.prot : 0,
        gluc: goals.gluc > 0 ? remainingGluc / goals.gluc : 0,
        lip: goals.lip > 0 ? remainingLip / goals.lip : 0
    };

    if (deficits.gluc > deficits[maxDeficit]) maxDeficit = 'gluc';
    if (deficits.lip > deficits[maxDeficit]) maxDeficit = 'lip';

    var macroLabels = { prot: 'protéines', gluc: 'glucides', lip: 'lipides' };
    var macroKeys = { prot: 'prot', gluc: 'gluc', lip: 'lip' };

    // Score each ingredient — prioritize the most deficient macro
    var bestScore = -Infinity;
    var bestIngredient = null;
    var bestPortion = 100;

    ingredientsDB.forEach(function(ing) {
        // Skip if adding 100g would exceed calorie limit too much
        if (remainingCals <= 0) return;

        // Calculate an ideal portion (g) that fills the calorie gap
        var portionByCals = (ing.cals > 0) ? (remainingCals / ing.cals) * 100 : 100;
        var portion = Math.min(Math.max(portionByCals, 50), 300); // Between 50g and 300g
        portion = Math.round(portion / 10) * 10; // Round to nearest 10g
        var ratio = portion / 100;

        // Score based on how well it fills the biggest deficit
        var score = 0;
        if (maxDeficit === 'prot') score = ing.prot * ratio;
        else if (maxDeficit === 'gluc') score = ing.gluc * ratio;
        else if (maxDeficit === 'lip') score = ing.lip * ratio;

        // Penalty for exceeding remaining calories too much
        var calPenalty = (ing.cals * ratio > remainingCals * 1.2) ? -50 : 0;
        score += calPenalty;

        if (score > bestScore) {
            bestScore = score;
            bestIngredient = ing;
            bestPortion = portion;
        }
    });

    if (!bestIngredient) {
        container.innerHTML = '<div class="suggestion-card suggestion-empty">' +
            '<span class="suggestion-icon">🤔</span>' +
            '<div class="suggestion-text">' +
            '<strong>Aucune suggestion</strong>' +
            '<p>Configurez vos objectifs pour recevoir des recommandations.</p>' +
            '</div></div>';
        return;
    }

    var ratio = bestPortion / 100;
    var sugCals = Math.round(bestIngredient.cals * ratio);
    var sugProt = Math.round(bestIngredient.prot * ratio * 10) / 10;
    var sugGluc = Math.round(bestIngredient.gluc * ratio * 10) / 10;
    var sugLip = Math.round(bestIngredient.lip * ratio * 10) / 10;

    var deficitLabel = macroLabels[maxDeficit];
    var deficitRemaining = Math.round(Math.max(0, maxDeficit === 'prot' ? remainingProt : maxDeficit === 'gluc' ? remainingGluc : remainingLip) * 10) / 10;

    container.innerHTML =
        '<div class="suggestion-card">' +
            '<span class="suggestion-icon">💡</span>' +
            '<div class="suggestion-text">' +
                '<strong>Suggestion : ' + escapeHtml(bestIngredient.nom) + ' (' + bestPortion + 'g)</strong>' +
                '<p>Il vous manque <b>' + deficitRemaining + 'g de ' + deficitLabel + '</b>. ' +
                'Cet aliment apporterait <b>' + sugCals + ' kcal</b>, ' +
                '<b>' + sugProt + 'g</b> prot, ' +
                '<b>' + sugGluc + 'g</b> gluc, ' +
                '<b>' + sugLip + 'g</b> lip.</p>' +
            '</div>' +
        '</div>';
}

// Utility — escape HTML (reuse if not already defined)
function escapeHtml(text) {
    var div = document.createElement('div');
    div.appendChild(document.createTextNode(text));
    return div.innerHTML;
}

// ============================================================
// INITIALIZATION
// ============================================================

document.addEventListener('DOMContentLoaded', function() {
    // Back Office features
    initSearch();
    initSort();
});
