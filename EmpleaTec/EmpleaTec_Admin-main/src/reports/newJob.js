import {api} from '../configs/axios'; 
export const postNewJob = async (jobData) => {
    try {
      // Transformando los datos a JSON
      const jsonData = JSON.stringify(jobData);
  
      // Realizando la solicitud POST
      console.log("Datos que se enviarán:", jsonData); // Muestra los datos transformados a JSON
  
      // Asegúrate de que la URL base esté configurada correctamente en 'api'
      const response = await api.post('create/jobs', jsonData, {
        headers: {
          'Content-Type': 'application/json', // Asegura que el encabezado sea 'application/json'
        },
      });
  
      // Verificar si la respuesta tiene los datos esperados
      if (response && response.data) {
        console.log('Trabajo publicado con éxito', response.data);
        window.location.href = '/dashboard/profile'; // Asegúrate de que la ruta sea correcta
      } else {
        console.error('Error inesperado en la respuesta de la API');
      }
    } catch (error) {
      // Manejo de errores
      console.error('Error al publicar el empleo:', error.message || error);
      throw error; // Relanzamos el error para que pueda ser manejado más arriba si es necesario
    }
  };
  