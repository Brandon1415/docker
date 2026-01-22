# Usamos la imagen base de Node.js 18.20.4
FROM node:18.20.4

# Establecemos el directorio de trabajo dentro del contenedor
WORKDIR /usr/src/app

# Copiamos los archivos de tu proyecto al contenedor
COPY package*.json ./

# Instalamos las dependencias
RUN npm install

# Copiamos el resto de los archivos de la aplicación al contenedor
COPY . .

# Exponemos el puerto que usa tu API (puedes cambiarlo si es diferente)
EXPOSE 3000

# Definimos el comando para iniciar la aplicación
CMD ["node", "server.js"]

#Comando spara crear la Imagen
#docker build -t EmpleaTec-Admin .
#Lebanta la Imagen
#docker run -p 3000:3000 Empleatec-Admin
