/*
    Author: Graham P and Graham S
    Purpose: Sets up chart template data and templat charts for viewing statistics
*/
var ctx;
var ctx2;
var wellnessChart;
var wellnessChartAvg;

var dataLine = {
    mood: {
        label: "Mood",
        data: [],
        backgroundColor: 'rgba(255,255,255,0)',
        borderColor: 'rgba(237,109,109,1)',
        pointRadius: 7,
        pointHoverRadius: 10,
        pointBackgroundColor: 'rgba(237,109,109,1)'
    },
    energy: {
        label: "Energy",
        data: [],
        backgroundColor: 'rgba(255,255,255,0)',
        borderColor: 'rgba(239,198,35,1)',
        pointRadius: 7,
        pointHoverRadius: 10,
        pointBackgroundColor: 'rgba(239,198,35,1)'
    },
    sleep: {
        label: "Sleep",
        data: [],
        backgroundColor: 'rgba(255,255,255,0)',
        borderColor: 'rgba(35,175,239,1)',
        pointRadius: 7,
        pointHoverRadius: 10,
        pointBackgroundColor: 'rgba(35,175,239,1)'
    },
    thoughts: {
        label: "Thoughts",
        data: [],
        backgroundColor: 'rgba(255,255,255,0)',
        borderColor: 'rgba(106,235,119,1)',
        pointRadius: 7,
        pointHoverRadius: 10,
        pointBackgroundColor: 'rgba(106,235,119,1)'
    }
};


var dataAvg = {
    data: [0, 0, 0, 0]
};


// Establishes templates for statistics charts
function generateCharts() {

    //All data
    ctx = document.getElementById("wellnessChart").getContext('2d');

    wellnessChart = new Chart(ctx, {
        responsive: true,
        type: 'line',

        data: {
            datasets: []
        },

        options: {
            layout: {
                padding: 25
            },
            legend: {
                position: 'bottom',

            },
            maintainAspectRatio: false,
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                        min: 0,
                        max: 10
                    }
                }],
                xAxes: [{
                    type: 'time',
                    distribution: 'linear',
                    time: {

                        unit: 'day'
                    }
                }]

            }

        }

    });

    //Averages
    
    ctx2 = document.getElementById("wellnessChartAvg").getContext('2d');

    wellnessChartAvg = new Chart(ctx2, {
        responsive: true,
        type: 'polarArea',

        data: {

            datasets:
            [
                {

                    data: dataAvg.data,
                    backgroundColor: ['rgba(237,109,109,.6)', 'rgba(239,198,35,.6)', 'rgba(35,175,239,.6)', 'rgba(106,235,119,.6)'],
                    borderColor: ['rgba(237,109,109,1)', 'rgba(239,198,35,1)', 'rgba(35,175,239,1)', 'rgba(106,235,119,1)'],

                }

            ],
            labels: ["Mood", "Energy", "Sleep", "Thoughts"]

        },

        options: {
            layout: {
                padding: 25
            },

            startAngle: 0.64 * Math.PI,
            legend: {
                position: 'bottom',

            },
            maintainAspectRatio: false,
            scale: {
                ticks: {
                    min: 0,
                    max: 10,
                    stepSize: 1,
                    beginAtZero: true,
                    padding: 25
                }

            }

        }

    });


}