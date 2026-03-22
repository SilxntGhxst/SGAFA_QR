from pydantic import BaseModel
from typing import Optional

# Basado en la tabla 'categorias' del PDF
class CategoriaSchema(BaseModel):
    id: int
    categoria: str

# Basado en la tabla 'ubicaciones' del PDF
class UbicacionSchema(BaseModel):
    id: int
    nombre: str
    edificio: str

# Basado en la tabla 'usuarios' del PDF
class UsuarioSchema(BaseModel):
    id: str # UUID
    nombre: str
    apellidos: str

# Basado en la tabla 'bienes_muebles' del PDF
class BienMuebleSchema(BaseModel):
    id: str # UUID
    codigo_inventario: str
    nombre: str
    descripcion: Optional[str] = None
    categoria_id: int
    ubicacion_id: int
    usuario_responsable_id: Optional[str] = None
    estado: str # funcional, mantenimiento, baja
    creado_en: str
    
    
class BienMuebleEnriquecido(BaseModel):
    id: str
    codigo: str
    nombre: str
    categoria: str
    ubicacion: str
    usuario: str
    estado: str
    fecha: str