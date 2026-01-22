import { getEmployerReports } from "../reports/empresasApi";

// reporte de estudiantes en formato json
const getDataEmpresas = async () => {
  const response = await getEmployerReports();
  const empresas = response.map(empresas => {
    const { first_name, last_name, email, phone } = empresas;

    return {
      img: "/img/team-2.jpeg", // Asignamos una imagen por defecto
      name: first_name ? first_name : "Hola",  
      members: [{ img: "/img/team-1.jpeg", name: phone }], 
      budget: email,  
      completion: 20,
    };
  });

  return empresas; 
};

export const projectsTableData = [
  ...(await getDataEmpresas()).map(empresas => ({
    img: empresas.img,
    name: empresas.name,  
    members:empresas.members,
    budget: empresas.budget,
    completion: 20, 
  
}))];


export default projectsTableData;
