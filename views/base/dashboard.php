<?php

?> 
	<div class="row" id="dashboard-header">
		<div class="col-xs-10 col-sm-12">
			<h2> Dashboard </h2>
		</div>  
	</div> 
	<div id='dashboard-content' class='row  '> 
		<div class="col-xs-12 col-sm-6">
			<div class="box ui-draggable ui-droppable">
				<div class="box-header">
					<div class="box-name">
						<i class="fa fa-bar-chart-o"></i>
						<span>Actividad por Instancia</span>
					</div>
					<div class="box-icons">
						<a class="collapse-link">
							<i class="fa fa-chevron-up"></i>
						</a>
						<a class="expand-link">
							<i class="fa fa-expand"></i>
						</a>
						<a class="close-link">
							<i class="fa fa-times"></i>
						</a>
					</div>
					<div class="no-move"></div>
				</div>
				<div class="box-content">
					<div style="min-height: 200px; position: relative;" id="google-chart-1">
					</div>
				</div>
			</div>
		</div>
		<div class="col-xs-12 col-sm-6">
			<div class="box ui-draggable ui-droppable">
				<div class="box-header">
					<div class="box-name">
						<i class="fa fa-circle"></i>
						<span>Usuarios por Instancia</span>
					</div>
					<div class="box-icons">
						<a class="collapse-link">
							<i class="fa fa-chevron-up"></i>
						</a>
						<a class="expand-link">
							<i class="fa fa-expand"></i>
						</a>
						<a class="close-link">
							<i class="fa fa-times"></i>
						</a>
					</div>
					<div class="no-move"></div>
				</div>
				<div class="box-content">
					<div style="min-height: 200px; position: relative;" id="google-chart-2"> 
						
					</div>
				</div>
			</div>
		</div>
	</div> 
	<script type="text/javascript">
	$(document).ready(function() {
		// Load Google Chart API and callback to draw test graphs
		$.getScript('http://www.google.com/jsapi?autoload={%22modules%22%3A[{%22name%22%3A%22visualization%22%2C%22version%22%3A%221%22%2C%22packages%22%3A[%22corechart%22%2C%22geochart%22]%2C%22callback%22%3A%22DrawAllCharts%22}]}');
		// This need for correct resize charts, when box open or drag
		var graphxChartsResize;
		$(".box").resize(function(event){
			event.preventDefault();
			clearTimeout(graphxChartsResize);
			graphxChartsResize = setTimeout(DrawAllCharts, 500);
		});
		// Add Drag-n-Drop action for .box
		WinMove();
	});
	
	/*-------------------------------------------
Demo graphs for Google Chart page (charts_google.html)
---------------------------------------------*/
//
// One function for create all graphs on Google Chart page
//
function DrawAllCharts(){
// Chart 1
var chart1_data = [
	['Smartphones', 'Instancia 1', 'Instancia 2', 'Instancia 3','Instancia 4', 'Instancia 5' ],
	['01.01.2014', 1234, 2342, 344, 232,131],
	['02.01.2014', 1254, 232, 314, 232, 331],
	['03.01.2014', 2234, 342, 298, 232, 665],
	['04.01.2014', 2234, 42, 559, 232, 321],
	['05.01.2014', 1999, 82, 116, 232, 334],
	['06.01.2014', 1634, 834, 884, 232, 191],
	['07.01.2014', 321, 342, 383, 232, 556],
	['08.01.2014', 845, 112, 499, 232, 731]
];
var chart1_options = {
	title: 'Actividad por Instancia',
	hAxis: {title: 'Date', titleTextStyle: {color: 'red'}},
	backgroundColor: '#fcfcfc',
	vAxis: {title: 'Visitas', titleTextStyle: {color: 'blue'}}
};
var chart1_element = 'google-chart-1';
var chart1_type = google.visualization.ColumnChart;
drawGoogleChart(chart1_data, chart1_options, chart1_element, chart1_type);
// Chart 2
var chart2_data = [
	['Height', 'Width'],
	['Instancia 1', 74.5],
	['Instancia 2', 31.24],
	['Instancia 3', 12.10],
	['Instancia 4', 11.14] ,
	['Instancia 5', 23.14] 
];
var chart2_options = {
title: 'Usuarios por Instancia',
backgroundColor: '#fcfcfc'
};
var chart2_element = 'google-chart-2';
var chart2_type = google.visualization.PieChart;
drawGoogleChart(chart2_data, chart2_options, chart2_element, chart2_type);
 
}

function drawGoogleChart(chart_data, chart_options, element, chart_type) {
	// Function for visualize Google Chart
	var data = google.visualization.arrayToDataTable(chart_data);
	var chart = new chart_type(document.getElementById(element));
	chart.draw(data, chart_options);
} 

	</script>
	