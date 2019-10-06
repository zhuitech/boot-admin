<?php $id = 'line-' . rand(10000,99999); $i = 0; ?>
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
        type: 'line',
        data: {
            labels: {!! json_encode($labels) !!},
            datasets: [
                @foreach($data as $label => $item)
                {
                    label: '{!! $label !!}',
                    backgroundColor: chartColors[{!! $i !!}],
                    borderColor: chartColors[{!! $i !!}],
                    data: {!! json_encode($item) !!},
                    fill: false,
                }
                @php($i++)
                @endforeach
            ]
        },
        options: {
            responsive: true,
            title: {
                display: true,
                text: ''
            },
            tooltips: {
                mode: 'index',
                intersect: false,
            },
            hover: {
                mode: 'nearest',
                intersect: true
            },
            scales: {
                xAxes: [{
                    display: true,
                    scaleLabel: {
                        display: true,
                        labelString: '{!! $x !!}'
                    }
                }],
                yAxes: [{
                    display: true,
                    scaleLabel: {
                        display: true,
                        labelString: '{!! $y !!}'
                    }
                }]
            }
        }
    };

    var ctx = document.getElementById('{!! $id !!}').getContext('2d');
    new Chart(ctx, config);
});
</script>