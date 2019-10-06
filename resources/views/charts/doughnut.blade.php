<?php $id = 'doughnut-' . rand(10000,99999); $i = 0; ?>
<canvas id="{!! $id !!}" style="width: 100%;"></canvas>
<script>
$(function () {
    var chartColors = [
        'rgb(54, 162, 235)',
        'rgb(255, 99, 132)',
        'rgb(255, 159, 64)',
        'rgb(255, 205, 86)',
        'rgb(75, 192, 192)',
        'rgb(153, 102, 255)',
        'rgb(201, 203, 207)'
    ];

    var config = {
        type: 'doughnut',
        data: {
            datasets: [{
                data: {!! json_encode($data) !!},
                backgroundColor: chartColors,
                label: 'Dataset 1'
            }],
            labels: {!! json_encode($labels) !!}
        },
        options: {
            responsive: true,
            legend: {
                position: 'top',
            },
            title: {
                display: true,
                text: ''
            },
            animation: {
                animateScale: true,
                animateRotate: true
            }
        }
    };

    var ctx = document.getElementById('{!! $id !!}').getContext('2d');
    new Chart(ctx, config);
});
</script>