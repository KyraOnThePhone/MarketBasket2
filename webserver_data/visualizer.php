<?php include 'admincheck.php'; ?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Analyse – Amazing Shop</title>


  <link href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  
  <style>
    canvas {
      max-width: 100%;
      max-height: 400px;
    }
    .card {
      margin-bottom: 30px;
    }
  </style>
</head>
<body>

<?php include 'header.php'; ?>

<main class="container">
  <h4 class="center-align">Produktanalyse nach Kundengruppen</h4>
  <div id="chartContainer"></div>
</main>

<?php include 'footer.php'; ?>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    fetch('analyse.php')
        .then(response => response.json())
        .then(result => {
            if (!result.success) {
                M.toast({html: 'Analyse fehlgeschlagen!', classes: 'red'});
                return;
            }

            const container = document.getElementById('chartContainer');

            result.data.forEach((gruppe, index) => {

                const card = document.createElement('div');
                card.className = 'card';

                card.innerHTML = `
                    <div class="card-content">
                        <span class="card-title">${gruppe.Gruppenname}</span>
                        <canvas id="chart_${index}"></canvas>
                    </div>
                `;
                container.appendChild(card);

                const labels = gruppe.Produkte.map(p => p.Produktname);
                const daten = gruppe.Produkte.map(p => p.Prozent);

                new Chart(document.getElementById(`chart_${index}`).getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Käufe in %',
                            data: daten,
                            backgroundColor: 'rgba(33, 150, 243, 0.6)',
                            borderColor: 'rgba(33, 150, 243, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        plugins: {
                            title: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return context.parsed.x + '%';
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                beginAtZero: true,
                                max: 100,
                                ticks: {
                                    callback: function(value) {
                                        return value + '%';
                                    }
                                }
                            }
                        }
                    }
                });
            });
        })
        .catch(error => {
            console.error('Fehler beim Laden der Daten:', error);
            M.toast({html: 'Fehler beim Laden der Analyse-Daten!', classes: 'red'});
        });
});
</script>

</body>
</html>
