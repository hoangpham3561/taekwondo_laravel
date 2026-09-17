// src/public/js/chart-config.js

// Chart 1: lineChartConfig
window.lineChartConfig = {
  data: [
    { time: "01:00", value: 42000 },
    { time: "03:00", value: 43000 },
    { time: "06:00", value: 41000 },
    { time: "09:00", value: 43362.18 },
    { time: "12:00", value: 47000 },
    { time: "15:00", value: 44000 },
    { time: "18:00", value: 43000 },
    { time: "21:00", value: 45000 },
    { time: "24:00", value: 44000 }
  ],
  yAxis: {
    min: 40000,
    max: 48000
  },
  tooltip: {
    enabled: true,
    format: "${valueY}"
  },
  lineColor: "#FFD600",
  fillColor: "#FFF9C4",
  bulletColor: "#fff",
  bulletBorder: "#FFD600"
};

// Chart 2: onfaSharesConfig (ví dụ chart 2 là multi-line)
window.onfaSharesConfig = {
data: [
  // Dữ liệu mẫu, bạn thay bằng dữ liệu thực tế
  { time: "15/04/2025", value1: 10, value2: 2 },
  { time: "16/04/2025", value1: 11, value2: 2 },
  { time: "17/04/2025", value1: 13, value2: 2 },
  { time: "18/04/2025", value1: 15, value2: 2 },
  { time: "19/04/2025", value1: 15, value2: 4 },
  { time: "20/04/2025", value1: 18, value2: 4 },
  { time: "21/04/2025", value1: 18, value2: 6 },
  { time: "22/04/2025", value1: 18, value2: 6 },
  { time: "23/04/2025", value1: 18, value2: 6 },
  { time: "24/04/2025", value1: 18, value2: 6 },
  { time: "25/04/2025", value1: 18, value2: 6 }
],
yAxis: {
  min: 0,
  max: 20
},
tooltip: {
  enabled: true,
  format: "{valueY}"
},
series: [
  {
    valueYField: "value1",
    lineColor: "#FF2D2D",
    fillColor: "#FF2D2D",
    fillGradient: ["#FF2D2D", "#fff"],
    bulletColor: "#fff",
    bulletBorder: "#FF2D2D"
  },
  {
    valueYField: "value2",
    lineColor: "#1DB954",
    fillColor: "#1DB954",
    fillGradient: ["#1DB954", "#fff"],
    bulletColor: "#fff",
    bulletBorder: "#1DB954"
  }
]
};  

