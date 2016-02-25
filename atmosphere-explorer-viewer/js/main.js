(function($){

	$('.chart').each(function(index, item) {
		var series = [
			{
				name: $(item).data('name'),
				valueField: 'value'
			}
		];

		if ( $(item).data('hasmin') ) {
			series.push({
				name: 'Min',
				valueField: 'min',
				color: '#597f3f',
			});
		}

		if ( $(item).data('hasmax') ) {
			series.push({
				name: 'Max',
				valueField: 'max',
				color: '#BA4D51',
			});
		}

		$(item).dxChart({
			dataSource: window['dataSource' + $(item).data('sensor')],
			commonSeriesSettings: {
				type: $(item).data('type') ? $(item).data('type') : "spline",
				argumentField: 'day'
			},
			tooltip: {
				enabled: true
			},
			series: series,
			legend: {
				horizontalAlignment: 'center',
				verticalAlignment: 'bottom'
			}
		});
	});

})(jQuery);