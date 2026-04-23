import sys
import os

sys.path.append(os.path.dirname(os.path.abspath(__file__)))

from app.database.db import SessionLocal
from app.database.models import Buzon

def wipe_buzon():
    db = SessionLocal()
    try:
        # Delete all records from the Buzon table
        filas_borradas = db.query(Buzon).delete()
        db.commit()
        print(f"Limpieza completada: Se han borrado {filas_borradas} registros de actividad del buzon.")
    except Exception as e:
        db.rollback()
        print(f"Error al limpiar la actividad: {e}")
    finally:
        db.close()

if __name__ == "__main__":
    wipe_buzon()
