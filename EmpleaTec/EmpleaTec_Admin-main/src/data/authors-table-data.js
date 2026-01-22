import { getStudentData } from "../reports/estudientsApi";

// reporte de estudiantes en formato json
const getDataStudient = async () => {
  const response = await getStudentData();

  // Iterar sobre cada estudiante y desestructurar sus datos
  const students = response.map(student => {
    const { first_name, last_name, email, education } = student;

    return {
      img: "/img/team-2.jpeg", // Asignamos una imagen por defecto
      name: `${first_name} ${last_name}`,  
      email: email,
      job: [education ? education : "No especificado", "Egresado"], 
      online: true,  
      date: new Date().toLocaleDateString(), 
    };
  });

  return students; 
};

export const authorsTableData = [
  ...(await getDataStudient()).map(student => ({
    img: "/img/usuario.png",  // Imagen por defecto
    name: student.name, 
    email: student.email,
    job: student.job, 
    online: true,  
    date: new Date().toLocaleDateString(), // Fecha actual
  }))
];

export default authorsTableData;
