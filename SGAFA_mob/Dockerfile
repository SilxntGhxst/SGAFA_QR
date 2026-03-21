# 1. Usar una imagen oficial de Node.js ligera (Alpine)
FROM node:20-alpine

# 2. Establecer el directorio de trabajo dentro del contenedor
WORKDIR /app

# 3. Copiar primero los archivos de dependencias para aprovechar la caché de Docker
COPY package*.json ./

# 4. Instalar las dependencias del proyecto de forma limpia en Linux
RUN npm install

# 5. Copiar el resto del código genético del proyecto
COPY . .

# 6. Exponer el puerto 8081 (El puerto oficial de Metro Bundler en Expo 50+)
EXPOSE 8081

# 7. Comando por defecto para arrancar el servidor
CMD ["npx", "expo", "start"]