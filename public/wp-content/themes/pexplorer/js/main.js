$(function() {

    $('.chart').each(function(index, item) {
        $(item).dxChart({
            dataSource: window['dataSource' + $(item).data('sensor')],
            commonSeriesSettings: {
                type: "spline",
                argumentField: 'day'
            },
            tooltip: {
                enabled: true
            },
            series: [
                {
                    name: $(item).data('name'),
                    valueField: 'value'
                }
            ],
            legend: {
                horizontalAlignment: 'center',
                verticalAlignment: 'bottom'
            }
        });
    });

});