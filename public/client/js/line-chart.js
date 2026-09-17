// src/public/js/line-chart.js

am5.ready(function() {
    // Chart 1: Đơn line
    if (document.getElementById("chartdiv")) {
      const lineChartConfig = window.lineChartConfig;
      let root = am5.Root.new("chartdiv");
      root._logo.dispose();
  
      let chart = root.container.children.push(
        am5xy.XYChart.new(root, {
          panX: false,
          panY: false,
          wheelX: "none",
          wheelY: "none"
        })
      );
  
      // Tạo trục X
      let xAxis = chart.xAxes.push(
        am5xy.CategoryAxis.new(root, {
          categoryField: "time",
          renderer: am5xy.AxisRendererX.new(root, {})
        })
      );
      xAxis.data.setAll(lineChartConfig.data);
  
      // Tạo trục Y
      let yAxis = chart.yAxes.push(
        am5xy.ValueAxis.new(root, {
          min: lineChartConfig.yAxis.min,
          max: lineChartConfig.yAxis.max,
          renderer: am5xy.AxisRendererY.new(root, {})
        })
      );
  
      // Tạo series
      let series = chart.series.push(
        am5xy.LineSeries.new(root, {
          name: "Price",
          xAxis: xAxis,
          yAxis: yAxis,
          valueYField: "value",
          categoryXField: "time",
          stroke: am5.color(lineChartConfig.lineColor),
          fill: am5.color(lineChartConfig.fillColor),
          tension: 0.8,
        })
      );
      series.data.setAll(lineChartConfig.data);
  
      // Tooltip
      series.set("tooltip", am5.Tooltip.new(root, {
        labelText: lineChartConfig.tooltip.format.replace("{valueY}", "{valueY}")
      }));
  
      // Bullet
      series.bullets.push(function() {
        return am5.Bullet.new(root, {
          sprite: am5.Circle.new(root, {
            radius: 5,
            fill: am5.color(lineChartConfig.bulletColor),
            stroke: am5.color(lineChartConfig.bulletBorder),
            strokeWidth: 2
          })
        });
      });
  
      // Fill dưới line
      series.fills.template.setAll({
        visible: true,
        fill: am5.color(lineChartConfig.fillColor),
        fillOpacity: 0.2
      });
  
      chart.appear(1000, 100);
    }
  
    // Chart 2: Multi-line
    if (document.getElementById("chart-onfa-shares")) {
      const lineChartConfig = window.onfaSharesConfig;
      let root = am5.Root.new("chart-onfa-shares");
      root._logo.dispose();
  
      let chart = root.container.children.push(
        am5xy.XYChart.new(root, {
          panX: false,
          panY: false,
          wheelX: "none",
          wheelY: "none"
        })
      );
  
      // Tạo trục X
      let xAxis = chart.xAxes.push(
        am5xy.CategoryAxis.new(root, {
          categoryField: "time",
          renderer: am5xy.AxisRendererX.new(root, {})
        })
      );
      xAxis.data.setAll(lineChartConfig.data);
  
      // Tạo trục Y
      let yAxis = chart.yAxes.push(
        am5xy.ValueAxis.new(root, {
          min: lineChartConfig.yAxis.min,
          max: lineChartConfig.yAxis.max,
          renderer: am5xy.AxisRendererY.new(root, {})
        })
      );
  
      // Tạo nhiều series
      lineChartConfig.series.forEach((s, idx) => {
        let series = chart.series.push(
          am5xy.LineSeries.new(root, {
            name: "Series " + (idx + 1),
            xAxis: xAxis,
            yAxis: yAxis,
            valueYField: s.valueYField,
            categoryXField: "time",
            stroke: am5.color(s.lineColor),
            fill: am5.color(s.fillColor),
            tension: 0, // Giữ line góc cạnh như hình mẫu, nếu muốn cong thì để 0.8
            tooltip: am5.Tooltip.new(root, {
              labelText: "{valueY}"
            })
          })
        );
        series.data.setAll(lineChartConfig.data);
  
        // Fill dưới line (gradient)
        series.fills.template.setAll({
          visible: true,
          fillGradient: am5.LinearGradient.new(root, {
            stops: [
              { color: am5.color(s.fillGradient[0]), opacity: 0.2 },
              { color: am5.color(s.fillGradient[1]), opacity: 0 }
            ],
            rotation: 90
          })
        });
      });
  
      chart.appear(1000, 100);
    }
  });