import uuid
import enum
from sqlalchemy import Column, Integer, String, Text, ForeignKey, Enum, TIMESTAMP
from sqlalchemy.dialects.postgresql import UUID
from sqlalchemy.sql import func
from app.database.db import Base


# ─── ENUMERADOS ────────────────────────────────────────────────────────────────

class EstadoActivo(str, enum.Enum):
    funcional     = "funcional"
    mantenimiento = "mantenimiento"
    baja          = "baja"

class EstatusSolicitud(str, enum.Enum):
    pendiente  = "Pendiente"
    aprobado   = "Aprobado"
    rechazado  = "Rechazado"

class Planta(str, enum.Enum):
    alta = "Alta"
    baja = "Baja"

class TipoCambio(str, enum.Enum):
    alta          = "Alta"
    baja          = "Baja"
    reubicacion   = "Reubicacion"
    mantenimiento = "Mantenimiento"


# ─── TABLA: roles ───────────────────────────────────────────────────────────────
class Rol(Base):
    __tablename__ = "roles"

    id  = Column(Integer, primary_key=True, index=True)
    rol = Column(String(50), unique=True, nullable=False)


# ─── TABLA: ubicaciones ─────────────────────────────────────────────────────────
class Ubicacion(Base):
    __tablename__ = "ubicaciones"

    id       = Column(Integer, primary_key=True, index=True)
    nombre   = Column(String(100), nullable=False)
    edificio = Column(Text, nullable=False)
    area     = Column(Text, nullable=False)
    oficina  = Column(Text, nullable=False)
    planta   = Column(Enum(Planta, name="plantas"), default=Planta.baja)


# ─── TABLA: categorias ──────────────────────────────────────────────────────────
class Categoria(Base):
    __tablename__ = "categorias"

    id        = Column(Integer, primary_key=True, index=True)
    categoria = Column(Text, unique=True, nullable=False)


# ─── TABLA: usuarios ────────────────────────────────────────────────────────────
class Usuario(Base):
    __tablename__ = "usuarios"

    id           = Column(UUID(as_uuid=True), primary_key=True, default=uuid.uuid4)
    nombre       = Column(String(50), nullable=False)
    apellidos    = Column(String(100), nullable=False)
    email        = Column(String(100), unique=True, nullable=False)
    clave_acceso = Column(Text, nullable=False)
    rol_id       = Column(Integer, ForeignKey("roles.id"), nullable=False)
    creado_en    = Column(TIMESTAMP, server_default=func.now())


# ─── TABLA: bienes_muebles ──────────────────────────────────────────────────────
class BienMueble(Base):
    __tablename__ = "bienes_muebles"

    id                     = Column(UUID(as_uuid=True), primary_key=True, default=uuid.uuid4)
    codigo_inventario      = Column(String(50), unique=True, nullable=False)
    nombre                 = Column(String(100), nullable=False)
    descripcion            = Column(Text)
    marca                  = Column(Text)
    modelo                 = Column(Text)
    QR                     = Column(Integer)
    categoria_id           = Column(Integer, ForeignKey("categorias.id"), nullable=False)
    ubicacion_id           = Column(Integer, ForeignKey("ubicaciones.id"), nullable=False)
    usuario_responsable_id = Column(UUID(as_uuid=True), ForeignKey("usuarios.id"), nullable=True)
    estado                 = Column(Enum(EstadoActivo, name="estado_activo"), default=EstadoActivo.funcional)
    creado_at              = Column(TIMESTAMP, server_default=func.now())
    actualizado_at         = Column(TIMESTAMP, server_default=func.now(), onupdate=func.now())


# ─── TABLA: solicitudes_cambio ──────────────────────────────────────────────────
class SolicitudCambio(Base):
    __tablename__ = "solicitudes_cambio"

    id                     = Column(Integer, primary_key=True, index=True)
    bien_id                = Column(UUID(as_uuid=True), ForeignKey("bienes_muebles.id", ondelete="CASCADE"))
    usuario_solicitante_id = Column(UUID(as_uuid=True), ForeignKey("usuarios.id"), nullable=False)
    tipo_cambio            = Column(Enum(TipoCambio, name="tipo_cambio"), default=TipoCambio.reubicacion)
    nueva_ubicacion_id     = Column(Integer, ForeignKey("ubicaciones.id"))
    nuevo_estado           = Column(Enum(EstadoActivo, name="estado_activo"), nullable=True)
    justificacion          = Column(Text)
    fecha_solicitud        = Column(TIMESTAMP, server_default=func.now())


# ─── TABLA: plan_auditoria ──────────────────────────────────────────────────────
class PlanAuditoria(Base):
    __tablename__ = "plan_auditoria"

    id             = Column(Integer, primary_key=True, index=True)
    descripcion    = Column(Text, nullable=False)
    responsable_id = Column(UUID(as_uuid=True), ForeignKey("usuarios.id"), nullable=False)
    fecha          = Column(TIMESTAMP, server_default=func.now())


# ─── TABLA: auditorias ──────────────────────────────────────────────────────────
class Auditoria(Base):
    __tablename__ = "auditorias"

    id              = Column(Integer, primary_key=True, index=True)
    folio           = Column(String(50), unique=True)
    ubicacion_id    = Column(Integer, ForeignKey("ubicaciones.id"))
    usuario_id      = Column(UUID(as_uuid=True), ForeignKey("usuarios.id"))
    fecha           = Column(TIMESTAMP, server_default=func.now())
    fecha_inicio    = Column(String(20))
    fecha_fin       = Column(String(20))
    estado          = Column(String(50), default="Pendiente")
    escaneados      = Column(Integer, default=0)
    total_esperados = Column(Integer, default=0)
    resumen_final   = Column(Text, default="")


# ─── TABLA: auditoria_activos (pivote) ──────────────────────────────────────────
class AuditoriaActivo(Base):
    __tablename__ = "auditoria_activos"

    id           = Column(Integer, primary_key=True, index=True)
    auditoria_id = Column(Integer, ForeignKey("auditorias.id", ondelete="CASCADE"))
    bien_id      = Column(UUID(as_uuid=True), ForeignKey("bienes_muebles.id"))


# ─── TABLA: buzon ───────────────────────────────────────────────────────────────
class Buzon(Base):
    __tablename__ = "buzon"

    id            = Column(Integer, primary_key=True, index=True, autoincrement=True)
    codigo        = Column(String(20))
    activo        = Column(String(100), nullable=False)
    tipo          = Column(String(100), nullable=False)
    reportado_por = Column(String(100), nullable=False)
    area          = Column(String(100), nullable=False)
    estado        = Column(String(50), default="Pendiente")
    descripcion   = Column(Text)
    foto          = Column(Text)
    created_at    = Column(TIMESTAMP, server_default=func.now())
