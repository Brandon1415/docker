import { chartsConfig } from "@/configs";

const estudiantesRegistradosChart = {
  type: "bar",
  height: 280,
  series: [
    {
      name: "Estudiantes Registrados",
      data: [50, 20, 10, 22, 50, 10, 40],
    },
  ],
  options: {
    ...chartsConfig,
    colors: ["#667eea"], // Gradiente azul-púrpura moderno
    fill: {
      type: "gradient",
      gradient: {
        shade: "dark",
        type: "vertical",
        shadeIntensity: 0.5,
        gradientToColors: ["#764ba2"],
        inverseColors: false,
        opacityFrom: 0.9,
        opacityTo: 0.6,
        stops: [0, 90, 100],
      },
    },
    plotOptions: {
      bar: {
        columnWidth: "45%",
        borderRadius: 12,
        borderRadiusApplication: "end",
        borderRadiusWhenStacked: "last",
        dataLabels: {
          position: "top",
        },
      },
    },
    dataLabels: {
      enabled: true,
      formatter: function (val) {
        return val + "";
      },
      offsetY: -25,
      style: {
        fontSize: "12px",
        colors: ["#667eea"],
        fontWeight: "bold",
      },
    },
    xaxis: {
      ...chartsConfig.xaxis,
      categories: ["Lun", "Mar", "Mié", "Jue", "Vie", "Sáb", "Dom"],
      labels: {
        style: {
          colors: "#64748b",
          fontSize: "12px",
          fontWeight: 600,
          fontFamily: "Inter, sans-serif",
        },
      },
      axisBorder: {
        show: false,
      },
      axisTicks: {
        show: false,
      },
    },
    yaxis: {
      labels: {
        style: {
          colors: "#64748b",
          fontSize: "12px",
          fontWeight: 500,
          fontFamily: "Inter, sans-serif",
        },
        formatter: function (val) {
          return val;
        },
      },
    },
    tooltip: {
      theme: "dark",
      style: {
        fontSize: "13px",
        fontFamily: "Inter, sans-serif",
      },
      custom: function({ series, seriesIndex, dataPointIndex, w }) {
        return `
          <div class="px-4 py-3 bg-gray-900 rounded-lg shadow-2xl">
            <div class="flex items-center space-x-2">
              <div class="w-3 h-3 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full"></div>
              <span class="text-white font-medium">${series[seriesIndex][dataPointIndex]} estudiantes</span>
            </div>
            <div class="text-gray-300 text-xs mt-1">${w.globals.labels[dataPointIndex]}</div>
          </div>
        `;
      },
    },
    grid: {
      borderColor: "#e2e8f0",
      strokeDashArray: 3,
      xaxis: {
        lines: {
          show: false,
        },
      },
      yaxis: {
        lines: {
          show: true,
        },
      },
      padding: {
        top: 20,
        right: 20,
        bottom: 0,
        left: 10,
      },
    },
    states: {
      hover: {
        filter: {
          type: "lighten",
          value: 0.1,
        },
      },
    },
  },
};

const empresasRegistradasChart = {
  type: "line",
  height: 280,
  series: [
    {
      name: "Empresas Registradas",
      data: [50, 40, 300, 320, 500, 350, 200, 230, 500],
    },
  ],
  options: {
    ...chartsConfig,
    colors: ["#ff6b6b"], // Coral vibrante
    stroke: {
      curve: "smooth",
      width: 4,
      lineCap: "round",
    },
    fill: {
      type: "gradient",
      gradient: {
        shade: "dark",
        gradientToColors: ["#ee5a24"],
        shadeIntensity: 1,
        type: "vertical",
        opacityFrom: 0.8,
        opacityTo: 0.1,
        stops: [0, 100, 100, 100],
      },
    },
    markers: {
      size: 8,
      colors: ["#ffffff"],
      strokeColors: "#ff6b6b",
      strokeWidth: 3,
      hover: {
        size: 10,
        sizeOffset: 2,
      },
    },
    dataLabels: {
      enabled: false,
    },
    xaxis: {
      ...chartsConfig.xaxis,
      categories: ["Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"],
      labels: {
        style: {
          colors: "#64748b",
          fontSize: "12px",
          fontWeight: 600,
          fontFamily: "Inter, sans-serif",
        },
      },
      axisBorder: {
        show: false,
      },
      axisTicks: {
        show: false,
      },
    },
    yaxis: {
      labels: {
        style: {
          colors: "#64748b",
          fontSize: "12px",
          fontWeight: 500,
          fontFamily: "Inter, sans-serif",
        },
        formatter: function (val) {
          return val;
        },
      },
    },
    tooltip: {
      theme: "dark",
      style: {
        fontSize: "13px",
        fontFamily: "Inter, sans-serif",
      },
      custom: function({ series, seriesIndex, dataPointIndex, w }) {
        return `
          <div class="px-4 py-3 bg-gray-900 rounded-lg shadow-2xl">
            <div class="flex items-center space-x-2">
              <div class="w-3 h-3 bg-gradient-to-r from-red-500 to-orange-500 rounded-full"></div>
              <span class="text-white font-medium">${series[seriesIndex][dataPointIndex]} empresas</span>
            </div>
            <div class="text-gray-300 text-xs mt-1">${w.globals.labels[dataPointIndex]}</div>
          </div>
        `;
      },
    },
    grid: {
      borderColor: "#e2e8f0",
      strokeDashArray: 3,
      xaxis: {
        lines: {
          show: false,
        },
      },
      yaxis: {
        lines: {
          show: true,
        },
      },
      padding: {
        top: 20,
        right: 20,
        bottom: 0,
        left: 10,
      },
    },
    states: {
      hover: {
        filter: {
          type: "lighten",
          value: 0.1,
        },
      },
    },
  },
};

export const statisticsChartsData = [
  {
    color: "white",
    title: "📊 REGISTRO DE ESTUDIANTES",
    description: "Seguimiento semanal de nuevos estudiantes registrados",
    footer: "Tendencia: +15% esta semana",
    chart: estudiantesRegistradosChart,
    gradient: "from-blue-500 to-purple-600",
    icon: "👥",
    metric: "50",
    metricLabel: "Total esta semana",
  },
  {
    color: "white", 
    title: "🏢 REGISTRO DE EMPRESAS",
    description: "Crecimiento mensual de empresas afiliadas",
    footer: "Actualizado hace 4 minutos • Tendencia: +8%",
    chart: empresasRegistradasChart,
    gradient: "from-red-500 to-orange-500",
    icon: "📈",
    metric: "500",
    metricLabel: "Registradas este mes",
  },
];

export default statisticsChartsData;