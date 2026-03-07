# Importamos la imagen base
FROM python:3.12-slim

# Crear la carpeta de trabajo
WORKDIR /app

# Copiar los requerimientos
COPY requirements.txt .
RUN pip install --no-cache-dir -r requirements.txt

# Copiar el proyecto
COPY ./app ./app

# Exponemos el puerto
EXPOSE 5000

# Comando para ejecutar FastAPI
CMD ["uvicorn", "app.main:app", "--host", "0.0.0.0", "--port", "5000", "--reload"]
