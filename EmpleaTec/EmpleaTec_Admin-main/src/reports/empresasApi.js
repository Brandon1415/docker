import {api} from '../configs/axios'; 

//Consultamos Total de las empresas registradas
export const getTotalEmpresas = async () => {
  try {
    const response = await api.get('reports/employer');
    console.log("reporte de empresas", JSON.stringify(response.data, null));
    return response.data;
  } catch (error) {
    console.error('Error fetching emepresas reports:', error.message);
    throw error; 
  }
};

//Consultamos datos de las empresas registradas
export const getEmployerReports = async () => {
    try {
      const response = await api.get('reports/companies');
      console.log("reporte de empresas", JSON.stringify(response.data, null));
      return response.data;
    } catch (error) {
      console.error('Error fetching emepresas reports:', error.message);
      throw error; 
    }
  };
  