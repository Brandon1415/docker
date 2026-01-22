import {api} from '../configs/axios'; 
//Consultamos el total de los estudiantes registrados
export const getTotalJobs = async () => {
    try {
      const response = await api.get('reports/totalJobs');
      console.log("reporte de estudiantes", JSON.stringify(response.data, null));
      return response.data;
    } catch (error) {
      console.error('Error fetching student reports:', error.message);
      throw error; 
    }
  };