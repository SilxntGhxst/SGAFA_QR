import { apiClient } from './apiClient';

const mapBuzonToActivity = (item) => {
  let tipoBadge = "info";
  const tipoStr = (item.tipo || "").toLowerCase();
  
  if (tipoStr.includes("daño") || tipoStr.includes("dañado")) {
    tipoBadge = "alerta";
  } else if (tipoStr.includes("faltante") || tipoStr.includes("sin registro")) {
    tipoBadge = "aviso";
  } else {
    tipoBadge = "exito";
  }

  const d = new Date(item.created_at || Date.now());
  const dia  = String(d.getDate()).padStart(2, '0');
  const mes  = String(d.getMonth() + 1).padStart(2, '0');
  const hora = String(d.getHours()).padStart(2, '0');
  const min  = String(d.getMinutes()).padStart(2, '0');
  const fmtDate = `${dia}/${mes}, ${hora}:${min}`;

  return {
    id:     String(item.id),
    tipo:   tipoBadge,
    titulo: item.activo || item.tipo,
    fecha:  fmtDate,
    estado: item.estado || "Pendiente"
  };
};

/**
 * Fetch dashboard data.
 * @param {object|null} authUser - user object from AuthContext (si es null no se incluye)
 */
export const fetchDashboardData = async (authUser = null) => {
  try {
    const [statsRes, buzonRes] = await Promise.all([
      apiClient('/activos/stats'),
      apiClient('/buzon')
    ]);
    
    const buzonList = (buzonRes.data || []).sort((a, b) => b.id - a.id);
    const mappedActivities = buzonList.map(mapBuzonToActivity);
    
    return {
      // Usar datos reales del usuario autenticado
      usuario: authUser
        ? { nombre: authUser.nombre, rol: authUser.rol || 'Resguardante' }
        : { nombre: 'Usuario', rol: '' },
      metricas: { 
        auditoriasPendientes: statsRes.solicitudes_pendientes || 0, 
        activosAsignados:     statsRes.total_activos || 0 
      },
      actividadReciente: mappedActivities.slice(0, 3),
      historial:         mappedActivities
    };
  } catch (error) {
    console.error("Dashboard fetch error:", error);
    throw error;
  }
};
