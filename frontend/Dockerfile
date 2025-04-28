FROM node:16-alpine

# Establecer directorio de trabajo
WORKDIR /app

# Instalar dependencias globales
RUN npm install -g @vue/cli

# Copiar package.json y package-lock.json
COPY package*.json ./

# Instalar dependencias del proyecto
RUN npm install

# Copiar todo el código
COPY . .

# Exponer puerto para desarrollo
EXPOSE 8080

# Comando para iniciar el servidor de desarrollo
CMD ["npm", "run", "serve"]