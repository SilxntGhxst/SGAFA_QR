import ssl
from fastapi_mail import ConnectionConfig, FastMail, MessageSchema, MessageType
from app.database.models import AjusteSistema
from sqlalchemy.orm import Session
import os

async def get_mail_config(db: Session):
    """
    Obtiene la configuración de correo desde la tabla ajustes_sistema.
    Si no existen, usa valores por defecto del entorno.
    """
    def get_val(clave, default):
        adj = db.query(AjusteSistema).filter(AjusteSistema.clave == clave).first()
        return adj.valor if adj else os.getenv(clave.upper(), default)

    conf = ConnectionConfig(
        MAIL_USERNAME = get_val("mail_username", ""),
        MAIL_PASSWORD = get_val("mail_password", ""),
        MAIL_FROM     = get_val("mail_from", "noreply@sgafa.com"),
        MAIL_PORT     = int(get_val("mail_port", 587)),
        MAIL_SERVER   = get_val("mail_server", "smtp.gmail.com"),
        MAIL_FROM_NAME= get_val("mail_from_name", "SGAFA QR Sistema"),
        MAIL_STARTTLS = get_val("mail_starttls", "True") == "True",
        MAIL_SSL_TLS  = get_val("mail_ssl_tls", "False") == "True",
        USE_CREDENTIALS = True,
        VALIDATE_CERTS = True
    )
    return conf

async def enviar_correo_recuperacion(email: str, codigo: str, db: Session):
    config = await get_mail_config(db)
    
    html = f"""
    <div style="font-family: Arial, sans-serif; padding: 20px; color: #333;">
        <h2 style="color: #1e40af;">Recuperación de Contraseña - SGAFA QR</h2>
        <p>Has solicitado restablecer tu contraseña. Usa el siguiente código para completar el proceso:</p>
        <div style="background: #f3f4f6; padding: 15px; border-radius: 8px; text-align: center; margin: 20px 0;">
            <span style="font-size: 32px; font-weight: bold; letter-spacing: 5px; color: #1e40af;">{codigo}</span>
        </div>
        <p>Este código expirará en 10 minutos.</p>
        <p style="font-size: 12px; color: #6b7280; margin-top: 30px;">Si no solicitaste este cambio, puedes ignorar este correo.</p>
    </div>
    """
    
    message = MessageSchema(
        subject="Código de Recuperación - SGAFA QR",
        recipients=[email],
        body=html,
        subtype=MessageType.html
    )

    fm = FastMail(config)
    await fm.send_message(message)
