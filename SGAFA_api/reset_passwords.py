import os
import sys
import uuid

# Agregar ruta base al sys.path
sys.path.append(os.path.dirname(os.path.abspath(__file__)))

from app.database.db import SessionLocal
from app.database.models import Usuario, Rol

# Hash Bcrypt con 12 rondas para la contraseña: Admin1234
NUEVO_HASH = "$2b$12$5s70/xWQvAyXjx5FZ4B66OyvkAc5SmVNkY7I/h/qn2jXA8hOUJc6W"

USUARIOS_INICIALES = [
    {
        "nombre": "Admin",
        "apellidos": "Sistema",
        "email": "admin@sgafa.com",
        "rol_id": 1
    },
    {
        "nombre": "Auditor",
        "apellidos": "General",
        "email": "auditor@sgafa.com",
        "rol_id": 3
    },
    {
        "nombre": "Resguardante",
        "apellidos": "Operativo",
        "email": "resguardante@sgafa.com",
        "rol_id": 2
    }
]

ROLES_BASE = [
    (1, "Administrador"),
    (2, "Resguardante"),
    (3, "Auditor")
]

def restaurar_o_crear():
    db = SessionLocal()
    try:
        # 1. Asegurar roles
        for r_id, r_nom in ROLES_BASE:
            rol = db.query(Rol).filter(Rol.id == r_id).first()
            if not rol:
                db.add(Rol(id=r_id, rol=r_nom))
        db.commit()

        # 2. Asegurar usuarios y actualizar sus contraseñas
        print("=" * 60)
        print("  SGAFA QR - CONFIGURACION Y RESET DE CONTRASEÑAS")
        print("=" * 60)
        for u_data in USUARIOS_INICIALES:
            usr = db.query(Usuario).filter(Usuario.email == u_data["email"]).first()
            if usr:
                usr.clave_acceso = NUEVO_HASH
                print(f"[ACTUALIZADO] {u_data['email']} -> Clave: Admin1234")
            else:
                nuevo = Usuario(
                    id=uuid.uuid4(),
                    nombre=u_data["nombre"],
                    apellidos=u_data["apellidos"],
                    email=u_data["email"],
                    clave_acceso=NUEVO_HASH,
                    rol_id=u_data["rol_id"]
                )
                db.add(nuevo)
                print(f"[CREADO]      {u_data['email']} -> Clave: Admin1234")
        
        db.commit()
        print("=" * 60)
        print("Listo! Todas las cuentas quedaron con:")
        print("  Password: Admin1234")
        print("=" * 60)
    except Exception as e:
        db.rollback()
        print(f"Error: {e}")
    finally:
        db.close()

if __name__ == "__main__":
    restaurar_o_crear()
