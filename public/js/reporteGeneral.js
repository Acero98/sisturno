document.addEventListener("DOMContentLoaded", () => {

    console.log(window.dashboardData);

    // ==============================
    // HORAS PICO
    // ==============================

    const optionsHoras = {

        chart: {
            type: 'bar',
            height: 350,
            toolbar: {
                show: false
            }
        },

        series: [{
            name: 'Tickets',
            data: window.dashboardData.horasData
        }],

        xaxis: {
            categories: window.dashboardData.horasLabels
        },

        dataLabels: {
            enabled: true
        }

    };

    const chartHoras = new ApexCharts(
        document.querySelector("#chartHorasPico"),
        optionsHoras
    );

    chartHoras.render();

    // ==============================
    // SERVICIOS MÁS SOLICITADOS
    // ==============================

    const optionsServicios = {

        chart: {
            type: 'bar',
            height: 350,
            toolbar: {
                show: false
            }
        },

        series: [{
            name: 'Atenciones',
            data: window.dashboardData.serviciosData
        }],

        xaxis: {
            categories: window.dashboardData.serviciosLabels
        },

        plotOptions: {
            bar: {
                horizontal: true,
                borderRadius: 4
            }
        },

        dataLabels: {
            enabled: true
        }

    };

    const chartServicios = new ApexCharts(
        document.querySelector("#chartServicios"),
        optionsServicios
    );

    chartServicios.render();

    // ==============================
    // TENDENCIA SEMANAL
    // ==============================

    const optionsTendencia = {

        chart: {
            type: 'line',
            height: 350,
            toolbar: {
                show: false
            }
        },

        series: [{
            name: 'Tickets',
            data: window.dashboardData.tendenciaData
        }],

        xaxis: {
            categories: window.dashboardData.tendenciaLabels
        },

        stroke: {
            curve: 'smooth',
            width: 4
        },

        dataLabels: {
            enabled: true
        }

    };

    const chartTendencia = new ApexCharts(
        document.querySelector("#chartTendencia"),
        optionsTendencia
    );

    chartTendencia.render();

    //TOTAL DE DE ACUERDO AL ESTADO

    const chartEstados = new ApexCharts(
        document.querySelector("#chartEstados"),
        {
            chart: {
                type: 'donut',
                height: 320
            },

            series: dashboardData.estadosData,

            labels: dashboardData.estadosLabels,

            legend: {
                position: 'bottom'
            },

            dataLabels: {
                enabled: true
            },

            tooltip: {
                y: {
                    formatter: function (val) {
                        return val + " tickets";
                    }
                }
            }
        }
    );

    chartEstados.render();

});