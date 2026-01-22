# Usar la imagen base de Node.js con la versión 18.20.4
FROM node:18.20.4

# Crear y establecer el directorio de trabajo
WORKDIR /app

# Copiar el package.json y package-lock.json (si lo tienes) primero para instalar dependencias
COPY package*.json ./

# Instalar las dependencias de tu proyecto
RUN npm install

# Copiar todo el código fuente de tu aplicación
COPY . .

# Exponer el puerto 5173 (el puerto en el que tu app corre)
EXPOSE 5173

# Comando para ejecutar tu aplicación en modo desarrollo
CMD ["npm", "run", "dev"]

#docker build -t EmpleatecFrontend .
#docker run -p 3000:3000 EmpleatecFrontend
