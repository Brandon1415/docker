import {api} from '../configs/axios'; 

//Consultamos Datos de los estudiantes registrados
export const getStudentData = async () => {
  try {
    const response = await api.get('reports/usuarios');
    console.log("reporte de estudiantes", JSON.stringify(response.data, null));
    return response.data;
  } catch (error) {
    console.error('Error fetching student reports:', error.message);
    throw error; 
  }
};

//Consultamos el total de los estudiantes registrados
export const getStudentReports = async () => {
    try {
      const response = await api.get('reports/students');
      console.log("reporte de estudiantes", JSON.stringify(response.data, null));
      return response.data;
    } catch (error) {
      console.error('Error fetching student reports:', error.message);
      throw error; 
    }
  };
  