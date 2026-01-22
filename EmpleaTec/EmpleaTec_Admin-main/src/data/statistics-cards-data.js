import {
  BanknotesIcon,
  UserPlusIcon,
  UsersIcon,
  ChartBarIcon,
} from "@heroicons/react/24/solid";
import{getStudentReports} from "../reports/estudientsApi"; 
import{getTotalEmpresas} from "../reports/empresasApi"; 
import{getTotalJobs} from "../reports/empleosApi"; 
// reporte de estudiantes en formato json
const getTotalEstudiantes = async () => {
    const response = await getStudentReports(); 
    const totalEstudiantes = response.reduce((acc, item) => acc + item.total_estudiantes, 0);
    return totalEstudiantes;
};
// reporte de empresas en formato json
const getTotalEmpresa = async () => {
  const response = await getTotalEmpresas(); 
  const totalEmpresas = response.reduce((acc, item) => acc + item.total_empresas, 0);
  return totalEmpresas;
};
// reporte de empleos en formato json
const getTotalEmpleos = async () => {
  const response = await getTotalJobs(); 
  const totalEmpleos = response.reduce((acc, item) => acc + item.total_ofertas_empleo, 0);
  return totalEmpleos;
};
export const statisticsCardsData = [
  {
    color: "gray",
    icon: UsersIcon,
    title: "Total Estudiantes Registrados",
    value:  await getTotalEstudiantes() || "Loading...",
    footer: {
      color: "text-green-500",
      value: "+3%",
      label: "than last month",
    },
  },
  {
    color: "gray",
    icon: BanknotesIcon,
    title: "Total Empresas Registradas",
    value: await getTotalEmpresa() || "Loading...",
    footer: {
      color: "text-green-500",
      value: "+55%",
      label: "than last week",
    },
  },
  {
    color: "gray",
    icon: UserPlusIcon,
    title: "Ofertas Empleo Registradas",
    value: await getTotalEmpleos() || "Loading...",
    footer: {
      color: "text-red-500",
      value: "2%",
      label: "than yesterday",
    },
  }
];

export default statisticsCardsData;
